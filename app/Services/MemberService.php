<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\User;
use App\Models\Yuran;
use App\Notifications\PaymentApprovedReceiptNotification;
use App\Notifications\PaymentProofPendingReviewNotification;
use App\Notifications\PaymentProofUploadedNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

final readonly class MemberService
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    public function checkMemberStatus(string $noKp): array
    {
        $noKp = preg_replace('/\D/', '', $noKp);
        if (strlen($noKp) !== 12) {
            return ['status' => 'not_found', 'member' => null];
        }

        $member = Member::where('no_kp', $noKp)->with('payments')->first();
        if (! $member) {
            return ['status' => 'not_found', 'member' => null];
        }

        $currentYear = (int) date('Y');
        $graceThreshold = $currentYear - Member::GRACE_YEARS;
        $paymentThisYear = $member->payments()
            ->where('tahun_mula', '<=', $currentYear)
            ->where(function ($q) use ($graceThreshold) {
                $q->whereNull('tahun_tamat')
                    ->orWhere('tahun_tamat', '>=', $graceThreshold);
            })
            ->orderByDesc('tahun_bayar')
            ->orderByDesc('id')
            ->first();

        if ($paymentThisYear) {
            if ($paymentThisYear->status === Payment::STATUS_APPROVED) {
                // Coverage ended before the current year: member is only active by
                // grace period and must still be offered the renewal form.
                $coverageEnd = (int) ($paymentThisYear->tahun_tamat
                    ?? $paymentThisYear->tahun_mula
                    ?? $paymentThisYear->tahun_bayar);

                return [
                    'status' => 'active',
                    'member' => $member,
                    'payment' => $paymentThisYear,
                    'needs_renewal' => $coverageEnd < $currentYear,
                ];
            }
            if ($paymentThisYear->status === Payment::STATUS_PENDING) {
                return ['status' => 'pending', 'member' => $member, 'payment' => $paymentThisYear];
            }
            if ($paymentThisYear->status === Payment::STATUS_WAIVED) {
                return ['status' => 'expired', 'member' => $member, 'payment' => null];
            }

            return ['status' => 'rejected', 'member' => $member, 'payment' => $paymentThisYear];
        }

        return ['status' => 'expired', 'member' => $member, 'payment' => null];
    }

    public function registerNewMember(array $data): Member
    {
        return DB::transaction(function () use ($data) {
            $pendingStatus = MemberStatus::where('code', 'pending')->first()
                ?? MemberStatus::where('code', 'tidak_aktif')->first();

            $memberData = [
                'jabatan_id' => $data['jabatan_id'],
                'jawatan_id' => $data['jawatan_id'],
                'member_status_id' => $pendingStatus->id,
                'nama' => $data['nama'],
                'no_kp' => $data['no_kp'],
                'email' => $data['email'] ?? null,
                'jantina' => $data['jantina'],
                'alamat1' => $data['alamat1'] ?? null,
                'alamat2' => $data['alamat2'] ?? null,
                'poskod' => $data['poskod'] ?? null,
                'bandar' => $data['bandar'] ?? null,
                'negeri' => $data['negeri'] ?? null,
                'no_tel' => $data['no_tel'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'tarikh_daftar' => $data['tarikh_daftar'] ?? now()->toDateString(),
            ];

            if (! empty($data['gambar']) && $data['gambar'] instanceof UploadedFile) {
                $memberData['gambar'] = $this->fileUploadService->uploadMemberPhoto($data['gambar'], $data['no_kp']);
            }

            $member = Member::create($memberData);

            $pendaftaranYuran = Yuran::findByCode(Member::YURAN_CODE_PENDAFTARAN);
            $paymentData = [
                'member_id' => $member->id,
                'tahun_bayar' => (int) date('Y'),
                'yuran_id' => $pendaftaranYuran?->id ?? 1,
                'status' => Payment::STATUS_PENDING,
            ];

            $payment = Payment::create($paymentData);

            if (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof UploadedFile) {
                $path = $this->fileUploadService->uploadPaymentProof($data['bukti_bayaran'], $payment->id);
                $payment->update(['bukti_bayaran' => $path]);
            }

            return $member->fresh();
        });
    }

    public function createMemberByAdmin(array $data): Member
    {
        return DB::transaction(function () use ($data) {
            $statusId = $data['member_status_id'] ?? MemberStatus::where('code', 'aktif')->first()?->id;
            $memberData = [
                'no_ahli' => $data['no_ahli'] ?? null,
                'jabatan_id' => $data['jabatan_id'],
                'jawatan_id' => $data['jawatan_id'],
                'member_status_id' => $statusId,
                'nama' => $data['nama'],
                'no_kp' => $data['no_kp'],
                'email' => $data['email'] ?? null,
                'jantina' => $data['jantina'],
                'alamat1' => $data['alamat1'] ?? null,
                'alamat2' => $data['alamat2'] ?? null,
                'poskod' => $data['poskod'] ?? null,
                'bandar' => $data['bandar'] ?? null,
                'negeri' => $data['negeri'] ?? null,
                'no_tel' => $data['no_tel'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'tarikh_daftar' => $data['tarikh_daftar'] ?? now()->toDateString(),
            ];

            if (! empty($data['gambar']) && $data['gambar'] instanceof UploadedFile) {
                $memberData['gambar'] = $this->fileUploadService->uploadMemberPhoto($data['gambar'], $data['no_kp']);
            }

            $member = Member::create($memberData);

            if (empty($member->no_ahli)) {
                $member->update([
                    'no_ahli' => $this->generateNoAhli(),
                ]);
            }

            $approveImmediately = ! empty($data['approve_immediately']);
            $hasPayment = ! empty($data['tahun_bayar'])
                || ! empty($data['yuran_id'])
                || (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof UploadedFile);

            if ($hasPayment) {
                $tahunBayar = (int) ($data['tahun_bayar'] ?? date('Y'));
                $yuranId = isset($data['yuran_id']) ? (int) $data['yuran_id'] : (Yuran::findByCode(Member::YURAN_CODE_PENDAFTARAN)?->id ?? 1);
                $paymentCombo = isset($data['payment_combo']) && is_string($data['payment_combo'])
                    ? $data['payment_combo']
                    : 'registration_only';
                $paymentData = [
                    'member_id' => $member->id,
                    'tahun_bayar' => $tahunBayar,
                    'yuran_id' => $yuranId,
                    'no_resit_sistem' => $data['no_resit_sistem'] ?? null,
                    'status' => $approveImmediately ? Payment::STATUS_APPROVED : Payment::STATUS_PENDING,
                    'approved_by' => $approveImmediately ? auth()->id() : null,
                    'approved_at' => $approveImmediately ? now() : null,
                ];

                if ($paymentCombo === 'registration_advance_next_year') {
                    $tahunMula = isset($data['tahun_mula']) && $data['tahun_mula'] !== null
                        ? (int) $data['tahun_mula']
                        : $tahunBayar;
                    $tahunTamat = isset($data['tahun_tamat']) && $data['tahun_tamat'] !== null
                        ? (int) $data['tahun_tamat']
                        : ($tahunBayar + 1);

                    $pembaharuanYuran10Id = Yuran::query()
                        ->where('code', Member::YURAN_CODE_PEMBAHARUAN)
                        ->where('tempoh_tahun', 1)
                        ->value('id');

                    $registrationPaymentData = $paymentData;
                    $registrationPaymentData['yuran_id'] = $yuranId;
                    $registrationPaymentData['tahun_mula'] = $tahunMula;
                    $registrationPaymentData['tahun_tamat'] = $tahunMula;

                    $advancePaymentData = $paymentData;
                    $advancePaymentData['yuran_id'] = (int) ($pembaharuanYuran10Id ?? 2);
                    $advancePaymentData['tahun_mula'] = $tahunTamat;
                    $advancePaymentData['tahun_tamat'] = $tahunTamat;

                    $registrationPayment = Payment::create($registrationPaymentData);
                    $advancePayment = Payment::create($advancePaymentData);

                    if (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof UploadedFile) {
                        $path = $this->fileUploadService->uploadPaymentProof($data['bukti_bayaran'], $registrationPayment->id);
                        $registrationPayment->update(['bukti_bayaran' => $path]);
                        $advancePayment->update(['bukti_bayaran' => $path]);
                    }
                } else {
                    if (isset($data['tahun_mula'], $data['tahun_tamat'])) {
                        $paymentData['tahun_mula'] = (int) $data['tahun_mula'];
                        $paymentData['tahun_tamat'] = (int) $data['tahun_tamat'];
                    }

                    $payment = Payment::create($paymentData);

                    if (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof UploadedFile) {
                        $path = $this->fileUploadService->uploadPaymentProof($data['bukti_bayaran'], $payment->id);
                        $payment->update(['bukti_bayaran' => $path]);
                    }
                }

                if ($approveImmediately) {
                    $aktifStatus = MemberStatus::where('code', 'aktif')->first();
                    $member->update([
                        'no_ahli' => $member->no_ahli ?? $this->generateNoAhli(),
                        'member_status_id' => $aktifStatus?->id ?? $member->member_status_id,
                    ]);
                }
            }

            return $member->fresh();
        });
    }

    public function approvePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => Payment::STATUS_APPROVED,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $member = $payment->member;
            $aktifStatus = MemberStatus::where('code', 'aktif')->first();
            $member->update([
                'no_ahli' => $member->no_ahli ?: $this->generateNoAhli(),
                'member_status_id' => $aktifStatus?->id ?? $member->member_status_id,
            ]);
        });

        Log::info('Pembayaran disahkan.', [
            'payment_id' => $payment->id,
            'member_id' => $payment->member_id,
            'approved_by' => auth()->id(),
        ]);

        $payment->refresh()->load(['member.jabatan', 'member.jawatan', 'member.memberStatus', 'yuran']);
        $member = $payment->member;

        if (filled($member->email)) {
            try {
                $email = strtolower(trim((string) $member->email));
                Notification::route('mail', [$email => $member->nama])
                    ->notify(new PaymentApprovedReceiptNotification($payment));
            } catch (\Throwable $e) {
                Log::warning('Kelulusan pembayaran: e-mel resit gagal dihantar.', [
                    'member_id' => $member->id,
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::info('Kelulusan pembayaran: tiada e-mel ahli untuk resit PDF.', [
                'member_id' => $member->id,
                'payment_id' => $payment->id,
            ]);
        }
    }

    public function waivePayment(Payment $payment, ?string $reason, int $waivedById): void
    {
        DB::transaction(function () use ($payment, $reason, $waivedById) {
            $payment->update([
                'status' => Payment::STATUS_WAIVED,
                'waived_by' => $waivedById,
                'waived_at' => now(),
                'waiver_reason' => $reason ?? $payment->waiver_reason,
            ]);

            $member = $payment->member;
            if ($member->memberStatus?->code === 'aktif' && ! $member->isAktifThisYear()) {
                $tidakAktifStatus = MemberStatus::where('code', 'tidak_aktif')->first();
                $member->update([
                    'member_status_id' => $tidakAktifStatus?->id ?? $member->member_status_id,
                ]);
            }
        });
    }

    public function submitRenewalPayment(string $noKp, array $data): Member
    {
        $noKp = preg_replace('/\D/', '', $noKp);
        $member = Member::where('no_kp', $noKp)->first();
        if (! $member) {
            throw new \InvalidArgumentException('Ahli tidak ditemui.');
        }

        /** @var list<int> $years */
        $years = array_map(static fn (int|string $y): int => (int) $y, $data['years']);
        $years = array_values(array_unique($years));
        sort($years);

        $yuran = Yuran::findByCode(Member::YURAN_CODE_PEMBAHARUAN);
        $bukti = $data['bukti_bayaran'] ?? null;

        /** @var list<int> $paymentIds */
        $paymentIds = [];
        $firstPayment = null;

        DB::transaction(function () use ($member, $years, $yuran, $bukti, &$paymentIds, &$firstPayment): void {
            $tahunBayar = (int) date('Y');

            foreach ($years as $year) {
                $payment = Payment::create([
                    'member_id' => $member->id,
                    'yuran_id' => $yuran?->id ?? 2,
                    'tahun_bayar' => $tahunBayar,
                    'tahun_mula' => $year,
                    'tahun_tamat' => $year,
                    'status' => Payment::STATUS_PENDING,
                ]);

                $paymentIds[] = $payment->id;
                if ($firstPayment === null) {
                    $firstPayment = $payment;
                }
            }

            if ($bukti instanceof UploadedFile && $firstPayment !== null) {
                $path = $this->fileUploadService->uploadPaymentProof($bukti, $firstPayment->id);
                Payment::query()->whereIn('id', $paymentIds)->update(['bukti_bayaran' => $path]);
            }
        });

        if ($firstPayment !== null) {
            $firstPayment->refresh()->load(['member', 'yuran']);

            $staffEmails = $this->collectSemakStaffReviewerEmails($member);
            if ($staffEmails === []) {
                Log::warning('Semak renewal: tiada e-mel staff untuk notifikasi bukti pembayaran.', [
                    'member_id' => $member->id,
                ]);
            } else {
                foreach ($staffEmails as $email) {
                    Notification::route('mail', $email)
                        ->notify(new PaymentProofPendingReviewNotification($firstPayment, $years));
                }
            }

            if (filled($member->email)) {
                Notification::route('mail', [$member->email => $member->nama])
                    ->notify(new PaymentProofUploadedNotification($firstPayment, $years));
            }
        }

        return $member->fresh(['payments.yuran']);
    }

    /**
     * Pentadbir dan akaun peranan pengguna (panel pembayaran), plus ADMIN_NOTIFICATION_EMAIL.
     *
     * @return list<string>
     */
    private function collectSemakStaffReviewerEmails(Member $member): array
    {
        $memberEmail = filled($member->email) ? strtolower(trim((string) $member->email)) : null;

        $emails = User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_USER])
            ->whereNotNull('email')
            ->pluck('email')
            ->map(fn ($e) => strtolower(trim((string) $e)))
            ->filter()
            ->unique()
            ->when($memberEmail !== null, fn ($c) => $c->reject(fn (string $e) => $e === $memberEmail))
            ->values()
            ->all();

        $optional = config('mail.admin_notification_email');
        if (filled($optional)) {
            $opt = strtolower(trim((string) $optional));
            if ($opt !== '' && ($memberEmail === null || $opt !== $memberEmail)) {
                $emails = array_values(array_unique([...$emails, $opt]));
            }
        }

        return $emails;
    }

    private function generateNoAhli(): string
    {
        $yy = date('y');
        $prefix = 'BKS-'.$yy;

        $last = Member::where('no_ahli', 'like', $prefix.'%')
            ->orderByRaw('CAST(SUBSTR(no_ahli, '.(strlen($prefix) + 1).') AS UNSIGNED) DESC')
            ->value('no_ahli');

        $next = $last ? ((int) substr($last, strlen($prefix)) + 1) : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
