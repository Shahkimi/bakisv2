<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\Yuran;
use App\Notifications\KutipanPaymentConfirmedNotification;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

final readonly class KutipanService
{
    /** Years after the current calendar year that may be selected for pembaharuan (prabayar). */
    public const int RENEWAL_SELECTABLE_YEARS_AHEAD = 2;

    public const string EMAIL_NOTICE_SENT = 'E-mel pengesahan telah berjaya dihantar kepada pegawai.';

    public const string EMAIL_NOTICE_MISSING = 'Pegawai tidak mempunyai e-mel. Pengesahan pembayaran tidak dapat dihantar.';

    public function __construct(
        private MemberService $memberService,
        private FileUploadService $fileUploadService,
    ) {}

    /**
     * @return Collection<int, Yuran>
     */
    public function getRenewalYurans(): Collection
    {
        return Yuran::query()
            ->where('is_active', true)
            ->where('code', Member::YURAN_CODE_PEMBAHARUAN)
            ->orderBy('jenis_yuran')
            ->get();
    }

    public function searchMembers(string $search): JsonResponse
    {
        $search = trim($search);
        if ($search === '') {
            return response()->json([
                'success' => false,
                'message' => 'Sila masukkan carian.',
            ], 422);
        }

        $digits = preg_replace('/\D/', '', $search) ?: '';
        $query = Member::query()
            ->with([
                'memberStatus:id,code,name,is_active',
                'payments' => function ($q) {
                    return $q
                        ->with(['yuran:id,jenis_yuran,jumlah,tempoh_tahun'])
                        ->select([
                            'id',
                            'member_id',
                            'yuran_id',
                            'tahun_bayar',
                            'tahun_mula',
                            'tahun_tamat',
                            'no_resit_sistem',
                            'status',
                            'bukti_bayaran',
                            'catatan_admin',
                            'approved_by',
                            'approved_at',
                            'created_at',
                        ])
                        ->orderByDesc('tahun_bayar')
                        ->orderByDesc('id')
                        ->limit(10);
                },
            ]);

        $query->where(function (Builder $q) use ($search, $digits): void {
            $q->where('nama', 'like', '%'.$search.'%')
                ->orWhere('no_ahli', 'like', '%'.$search.'%');

            if ($digits !== '') {
                $q->orWhere('no_kp', $digits);
            }
        });

        /** @var Member|null $member */
        $member = $query->first();
        if (! $member) {
            return response()->json([
                'success' => true,
                'member' => null,
                'renewal' => [
                    'eligible' => false,
                    'status' => 'not_found',
                    'message' => 'Tiada ahli ditemui.',
                ],
                'history' => [],
            ]);
        }

        $statusData = $this->memberService->checkMemberStatus($member->no_kp);
        $eligible = $statusData['status'] !== 'active';

        $history = $member->payments
            ?->map(fn (Payment $payment) => $this->formatPaymentHistoryRow($payment))
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'nama' => $member->nama,
                'no_kp' => $member->no_kp,
                'no_ahli' => $member->no_ahli,
                'member_status_code' => $member->memberStatus?->code,
                'member_status_name' => $member->memberStatus?->name,
            ],
            'renewal' => [
                'eligible' => $eligible,
                'status' => $statusData['status'],
                'current_payment' => $statusData['payment']
                    ? $this->formatPaymentHistoryRow($statusData['payment'])
                    : null,
                'message' => $eligible
                    ? 'Ahli boleh dikutip untuk pembaharuan.'
                    : 'Ahli telah aktif untuk tahun ini.',
            ],
            'history' => $history,
        ]);
    }

    public function autocompleteMembers(string $search): JsonResponse
    {
        $search = trim($search);
        if ($search === '') {
            return response()->json([
                'members' => [],
            ]);
        }

        $digits = preg_replace('/\D/', '', $search) ?: '';

        $query = Member::query()
            ->select(['id', 'nama', 'no_kp', 'no_ahli'])
            ->where(function (Builder $q) use ($search, $digits): void {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('no_ahli', 'like', '%'.$search.'%');

                if ($digits !== '') {
                    $q->orWhere('no_kp', 'like', '%'.$digits.'%');
                }
            })
            ->orderBy('nama')
            ->limit(15);

        $members = $query->get()->map(static function (Member $member): array {
            $encryptedNoKp = Crypt::encryptString((string) $member->no_kp);

            return [
                'id' => $encryptedNoKp,
                'text' => (string) $member->nama.' - '.(string) $member->no_kp,
                'nama' => (string) $member->nama,
                'no_kp' => (string) $member->no_kp,
                'no_ahli' => (string) ($member->no_ahli ?? ''),
            ];
        })->values()->all();

        return response()->json([
            'members' => $members,
        ]);
    }

    /**
     * First calendar year Pembaharuan may cover, i.e. the year after approved
     * Pendaftaran Keahlian (RM12) coverage ends. Null when no such registration payment exists.
     */
    public function getMinimumRenewalYearAfterPendaftaran(Member $member): ?int
    {
        $pendaftaranYuranIds = Yuran::query()
            ->where('code', Member::YURAN_CODE_PENDAFTARAN)
            ->pluck('id');

        if ($pendaftaranYuranIds->isEmpty()) {
            return null;
        }

        $registrationPayments = Payment::query()
            ->where('member_id', $member->id)
            ->where('status', Payment::STATUS_APPROVED)
            ->whereIn('yuran_id', $pendaftaranYuranIds)
            ->get();

        if ($registrationPayments->isEmpty()) {
            return null;
        }

        $maxCoverageEnd = $registrationPayments->map(function (Payment $p): int {
            $mula = $p->tahun_mula ?? $p->tahun_bayar;
            $tamat = $p->tahun_tamat ?? $mula;

            return max((int) $mula, (int) $tamat);
        })->max();

        return $maxCoverageEnd + 1;
    }

    /**
     * @return array<int, int>
     */
    public function getUnpaidYears(Member $member, int $rangeStart = 2020, ?int $rangeEnd = null): array
    {
        $rangeEnd = $rangeEnd ?? ((int) now()->year + self::RENEWAL_SELECTABLE_YEARS_AHEAD);

        $renewalFloor = $this->getMinimumRenewalYearAfterPendaftaran($member);
        if ($renewalFloor !== null) {
            $rangeStart = max($rangeStart, $renewalFloor);
        }

        $approvedPayments = Payment::query()
            ->where('member_id', $member->id)
            ->where('status', Payment::STATUS_APPROVED)
            ->get();

        $unpaidYears = [];
        for ($year = $rangeStart; $year <= $rangeEnd; $year++) {
            $covered = $approvedPayments->contains(fn (Payment $p) => $p->coversYear($year));
            if (! $covered) {
                $unpaidYears[] = $year;
            }
        }

        return $unpaidYears;
    }

    /**
     * @return array{
     *   member: array<string, mixed>,
     *   renewal: array<string, mixed>,
     *   history: array<int, array<string, mixed>>,
     *   unpaid_years: array<int, int>,
     *   renewal_min_year: int|null,
     * }
     *
     * @throws DecryptException
     */
    public function getMemberPageDataByEncryptedNoKp(string $encryptedNoKp): array
    {
        $noKp = Crypt::decryptString($encryptedNoKp);
        $noKpDigits = preg_replace('/\D/', '', $noKp) ?: '';

        /** @var Member $member */
        $member = Member::query()
            ->with([
                'memberStatus:id,code,name,is_active',
                'payments' => function ($q) {
                    return $q
                        ->with(['yuran:id,jenis_yuran,jumlah,tempoh_tahun'])
                        ->select([
                            'id',
                            'member_id',
                            'yuran_id',
                            'tahun_bayar',
                            'tahun_mula',
                            'tahun_tamat',
                            'no_resit_sistem',
                            'status',
                            'bukti_bayaran',
                            'catatan_admin',
                            'approved_by',
                            'approved_at',
                            'created_at',
                        ])
                        ->orderByDesc('tahun_bayar')
                        ->orderByDesc('id')
                        ->limit(10);
                },
            ])
            ->where('no_kp', $noKpDigits)
            ->firstOrFail();

        $statusData = $this->memberService->checkMemberStatus($member->no_kp);
        $eligible = $statusData['status'] !== 'active';

        $history = $member->payments
            ?->map(fn (Payment $payment) => $this->formatPaymentHistoryRow($payment))
            ->values()
            ->all() ?? [];

        return [
            'member' => [
                'id' => $member->id,
                'nama' => $member->nama,
                'no_kp' => $member->no_kp,
                'no_ahli' => $member->no_ahli,
                'member_status_code' => $member->memberStatus?->code,
                'member_status_name' => $member->memberStatus?->name,
            ],
            'renewal' => [
                'eligible' => $eligible,
                'status' => $statusData['status'],
                'current_payment' => $statusData['payment']
                    ? $this->formatPaymentHistoryRow($statusData['payment'])
                    : null,
                'message' => $eligible
                    ? 'Ahli boleh dikutip untuk pembaharuan.'
                    : 'Ahli telah aktif untuk tahun ini.',
            ],
            'history' => $history,
            'unpaid_years' => $this->getUnpaidYears($member),
            'renewal_min_year' => $this->getMinimumRenewalYearAfterPendaftaran($member),
        ];
    }

    /**
     * @param array{
     *   member_id:int,
     *   yuran_id:int,
     *   tahun_bayar:int,
     *   tahun_mula?:int|null,
     *   tahun_tamat?:int|null,
     *   bukti_bayaran?:UploadedFile|null,
     *   catatan_admin?:string|null,
     * } $data
     */
    public function collectPayment(array $data): JsonResponse
    {
        /** @var Member $member */
        $member = Member::query()
            ->with(['payments'])
            ->findOrFail((int) $data['member_id']);

        $statusData = $this->memberService->checkMemberStatus($member->no_kp);
        if ($statusData['status'] === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Ahli sudah aktif untuk tahun ini.',
            ], 422);
        }

        $receiptNo = DB::transaction(function () use ($member, $data): string {
            $payment = Payment::create([
                'member_id' => $member->id,
                'yuran_id' => (int) $data['yuran_id'],
                'tahun_bayar' => (int) $data['tahun_bayar'],
                'tahun_mula' => $data['tahun_mula'] ?? null,
                'tahun_tamat' => $data['tahun_tamat'] ?? null,
                'bukti_bayaran' => null,
                'status' => Payment::STATUS_APPROVED,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan_admin' => $data['catatan_admin'] ?? null,
            ]);

            $bukti = $data['bukti_bayaran'] ?? null;
            if ($bukti instanceof UploadedFile) {
                $path = $this->fileUploadService->uploadPaymentProof($bukti, $payment->id);
                $payment->update(['bukti_bayaran' => $path]);
            }

            $aktifStatus = MemberStatus::query()
                ->where('code', 'aktif')
                ->first();

            $member->update([
                'no_ahli' => $member->no_ahli ?: 'AHL-'.str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
                'member_status_id' => $aktifStatus?->id ?? $member->member_status_id,
            ]);

            return (string) $payment->refresh()->no_resit_sistem;
        });

        $yuranRow = Yuran::query()->find((int) $data['yuran_id']);
        $detailLines = [
            'Tahun bayaran: '.(int) $data['tahun_bayar'],
            'Jumlah: RM '.number_format((float) ($yuranRow?->jumlah ?? 0), 2),
        ];
        $mula = $data['tahun_mula'] ?? null;
        $tamat = $data['tahun_tamat'] ?? null;
        if ($mula !== null && $tamat !== null) {
            $detailLines[] = 'Tahun liputan: '.$mula.' hingga '.$tamat;
        }

        $emailNotice = $this->dispatchKutipanConfirmationMail($member, $receiptNo, $detailLines);

        return response()->json([
            'success' => true,
            'message' => 'Bayaran berjaya direkodkan.',
            'receipt_no' => $receiptNo,
            'email_notice' => $emailNotice,
        ]);
    }

    /**
     * @param array{
     *   member_id:int,
     *   yuran_id:int,
     *   years:array<int, int|string>,
     *   bukti_bayaran?:UploadedFile|null,
     *   catatan_admin?:string|null,
     * } $data
     */
    public function collectPaymentsForYears(array $data): JsonResponse
    {
        /** @var Member $member */
        $member = Member::query()->findOrFail((int) $data['member_id']);

        $years = array_map(static fn (int|string $y): int => (int) $y, $data['years']);
        $years = array_values(array_unique($years));
        sort($years);

        /** @var Yuran $yuran */
        $yuran = Yuran::query()->findOrFail((int) $data['yuran_id']);
        $perYearAmount = (float) $yuran->jumlah;

        $receiptBatch = DB::transaction(function () use ($member, $data, $years, $yuran): string {
            $currentYear = (int) now()->year;
            $bukti = $data['bukti_bayaran'] ?? null;
            $buktiPath = null;
            /** @var list<int> $paymentIds */
            $paymentIds = [];
            $firstReceipt = null;

            foreach ($years as $year) {
                $payment = Payment::create([
                    'member_id' => $member->id,
                    'yuran_id' => $yuran->id,
                    'tahun_bayar' => $currentYear,
                    'tahun_mula' => $year,
                    'tahun_tamat' => $year,
                    'no_resit_sistem' => $firstReceipt,
                    'bukti_bayaran' => null,
                    'status' => Payment::STATUS_APPROVED,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'catatan_admin' => $data['catatan_admin'] ?? null,
                ]);

                $paymentIds[] = $payment->id;
                if ($firstReceipt === null) {
                    $firstReceipt = $payment->no_resit_sistem;
                }

                if ($bukti instanceof UploadedFile && $buktiPath === null) {
                    $buktiPath = $this->fileUploadService->uploadPaymentProof($bukti, $payment->id);
                }
            }

            if ($buktiPath !== null) {
                Payment::query()->whereIn('id', $paymentIds)->update(['bukti_bayaran' => $buktiPath]);
            }

            $aktifStatus = MemberStatus::query()
                ->where('code', 'aktif')
                ->first();

            $member->update([
                'no_ahli' => $member->no_ahli ?: 'AHL-'.str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
                'member_status_id' => $aktifStatus?->id ?? $member->member_status_id,
            ]);

            return (string) $firstReceipt;
        });

        $totalAmount = count($years) * $perYearAmount;

        $detailLines = [
            'Tahun dikutip: '.implode(', ', array_map(static fn (int $y): string => (string) $y, $years)),
            'Jumlah keseluruhan: RM '.number_format($totalAmount, 2),
        ];

        $emailNotice = $this->dispatchKutipanConfirmationMail($member, $receiptBatch, $detailLines);

        return response()->json([
            'success' => true,
            'message' => 'Bayaran berjaya direkodkan untuk '.count($years).' tahun.',
            'receipt_no' => $receiptBatch,
            'years' => $years,
            'total_amount' => number_format($totalAmount, 2),
            'email_notice' => $emailNotice,
        ]);
    }

    /**
     * @param  list<string>  $detailLines
     */
    private function dispatchKutipanConfirmationMail(Member $member, string $receiptNo, array $detailLines): string
    {
        $member->refresh();

        if (! filled($member->email)) {
            return self::EMAIL_NOTICE_MISSING;
        }

        try {
            $email = strtolower(trim((string) $member->email));
            Notification::route('mail', [$email => $member->nama])
                ->notify(new KutipanPaymentConfirmedNotification($member, $receiptNo, $detailLines));

            return self::EMAIL_NOTICE_SENT;
        } catch (\Throwable $e) {
            Log::warning('Kutipan confirmation email failed', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);

            return 'E-mel pengesahan tidak dapat dihantar pada masa ini. Sila hubungi pentadbir.';
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function formatPaymentHistoryRow(Payment $payment): array
    {
        $jumlah = (float) ($payment->yuran?->jumlah ?? 0);
        $jenisLabel = match ($payment->jenis) {
            'pendaftaran_baru' => 'Pendaftaran Baru',
            'pembaharuan' => 'Pembaharuan',
            default => $payment->yuran?->jenis_yuran ?? '–',
        };

        return [
            'id' => $payment->id,
            'tahun_bayar' => (int) $payment->tahun_bayar,
            'tahun_mula' => isset($payment->tahun_mula) ? (int) $payment->tahun_mula : null,
            'tahun_tamat' => isset($payment->tahun_tamat) ? (int) $payment->tahun_tamat : null,
            'jenis_label' => $jenisLabel,
            'jumlah' => $jumlah,
            'jumlah_formatted' => 'RM '.number_format($jumlah, 2),
            'no_resit_sistem' => $payment->no_resit_sistem ?? null,
            'status' => $payment->status,
            'bukti_bayaran' => (bool) $payment->bukti_bayaran,
            'catatan_admin' => $payment->catatan_admin,
            'approved_at' => $payment->approved_at?->toDateTimeString(),

            // Backward/forward compatible aliases for frontend rendering.
            'tahunbayar' => (int) $payment->tahun_bayar,
            'tahunmula' => isset($payment->tahun_mula) ? (int) $payment->tahun_mula : null,
            'tahuntamat' => isset($payment->tahun_tamat) ? (int) $payment->tahun_tamat : null,
            'jenislabel' => $jenisLabel,
            'jumlahformatted' => 'RM '.number_format($jumlah, 2),
            'noresitsistem' => $payment->no_resit_sistem ?? null,
            'approvedat' => $payment->approved_at?->toDateTimeString(),
        ];
    }
}
