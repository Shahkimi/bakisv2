<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\Yuran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

final readonly class KutipanService
{
    /** Years after the current calendar year that may be selected for pembaharuan (prabayar). */
    public const int RENEWAL_SELECTABLE_YEARS_AHEAD = 2;

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
            ->where('jumlah', 10.00)
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
                            'no_resit_transfer',
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
            ->where('jumlah', 12.00)
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
                            'no_resit_transfer',
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
     *   no_resit_transfer?:string|null,
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

        $tahunBayar = (int) $data['tahun_bayar'];
        $receiptNo = DB::transaction(function () use ($member, $data, $tahunBayar): string {
            $receipt = $this->generateReceiptNumber($tahunBayar);

            $payment = Payment::create([
                'member_id' => $member->id,
                'yuran_id' => (int) $data['yuran_id'],
                'tahun_bayar' => $tahunBayar,
                'tahun_mula' => $data['tahun_mula'] ?? null,
                'tahun_tamat' => $data['tahun_tamat'] ?? null,
                'no_resit_transfer' => $data['no_resit_transfer'] ?? null,
                'no_resit_sistem' => $receipt,
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

            return $receipt;
        });

        return response()->json([
            'success' => true,
            'message' => 'Bayaran berjaya direkodkan.',
            'receipt_no' => $receiptNo,
        ]);
    }

    /**
     * @param array{
     *   member_id:int,
     *   yuran_id:int,
     *   years:array<int, int|string>,
     *   no_resit_transfer?:string|null,
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
            $batchReceipt = $this->generateReceiptNumber($currentYear);
            $bukti = $data['bukti_bayaran'] ?? null;
            $buktiPath = null;
            /** @var list<int> $paymentIds */
            $paymentIds = [];

            foreach ($years as $year) {
                $payment = Payment::create([
                    'member_id' => $member->id,
                    'yuran_id' => $yuran->id,
                    'tahun_bayar' => $currentYear,
                    'tahun_mula' => $year,
                    'tahun_tamat' => $year,
                    'no_resit_transfer' => $data['no_resit_transfer'] ?? null,
                    'no_resit_sistem' => $batchReceipt,
                    'bukti_bayaran' => null,
                    'status' => Payment::STATUS_APPROVED,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'catatan_admin' => $data['catatan_admin'] ?? null,
                ]);

                $paymentIds[] = $payment->id;

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

            return $batchReceipt;
        });

        $totalAmount = count($years) * $perYearAmount;

        return response()->json([
            'success' => true,
            'message' => 'Bayaran berjaya direkodkan untuk '.count($years).' tahun.',
            'receipt_no' => $receiptBatch,
            'years' => $years,
            'total_amount' => number_format($totalAmount, 2),
        ]);
    }

    private function generateReceiptNumber(int $tahunBayar): string
    {
        $last = Payment::query()
            ->where('tahun_bayar', $tahunBayar)
            ->whereNotNull('no_resit_sistem')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $nextSeq = 1;
        if ($last && $last->no_resit_sistem) {
            if (preg_match('/-(\d+)\s*$/', (string) $last->no_resit_sistem, $m) === 1) {
                $nextSeq = ((int) $m[1]) + 1;
            }
        }

        return sprintf('RESIT-%d-%05d', $tahunBayar, $nextSeq);
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
            'no_resit_transfer' => $payment->no_resit_transfer ?? '–',
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
            'noresittransfer' => $payment->no_resit_transfer ?? '–',
            'noresitsistem' => $payment->no_resit_sistem ?? null,
            'approvedat' => $payment->approved_at?->toDateTimeString(),
        ];
    }
}
