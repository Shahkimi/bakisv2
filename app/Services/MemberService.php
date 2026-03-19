<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\Yuran;
use Illuminate\Support\Facades\DB;

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
        $paymentThisYear = $member->payments()
            ->where('tahun_mula', '<=', $currentYear)
            ->where(function ($q) use ($currentYear) {
                $q->whereNull('tahun_tamat')
                    ->orWhere('tahun_tamat', '>=', $currentYear);
            })
            ->orderByDesc('tahun_bayar')
            ->first();

        if ($paymentThisYear) {
            if ($paymentThisYear->status === Payment::STATUS_APPROVED) {
                return ['status' => 'active', 'member' => $member, 'payment' => $paymentThisYear];
            }
            if ($paymentThisYear->status === Payment::STATUS_PENDING) {
                return ['status' => 'pending', 'member' => $member, 'payment' => $paymentThisYear];
            }

            return ['status' => 'rejected', 'member' => $member, 'payment' => $paymentThisYear];
        }

        return ['status' => 'expired', 'member' => $member, 'payment' => null];
    }

    /**
     * @return array<int, array{year: int, status: string, payment: Payment|null, coverage_label: string}>
     */
    public function getPaymentHistoryByYear(Member $member, ?int $fromYear = null, ?int $toYear = null): array
    {
        $currentYear = (int) date('Y');
        $fromYear = $fromYear ?? $member->tarikh_daftar?->year ?? $currentYear;
        $toYear = $toYear ?? $currentYear;

        $approvedPayments = $member->payments()
            ->where('status', Payment::STATUS_APPROVED)
            ->with('yuran')
            ->get();

        $result = [];
        for ($year = $fromYear; $year <= $toYear; $year++) {
            $payment = $approvedPayments->first(fn (Payment $p) => $p->coversYear($year));

            $coverageLabel = '';
            if ($payment) {
                $amount = $payment->yuran ? number_format((float) $payment->yuran->jumlah, 2) : '0.00';
                $start = $payment->tahun_mula ?? $payment->tahun_bayar;
                $end = $payment->tahun_tamat ?? $payment->tahun_bayar;
                $coverageLabel = $start === $end
                    ? sprintf('Bayar %d, RM %s', $payment->tahun_bayar, $amount)
                    : sprintf('Bayar %d, RM %s, liputan %d–%d', $payment->tahun_bayar, $amount, $start, $end);
            }

            $result[] = [
                'year' => $year,
                'status' => $payment ? 'paid' : 'unpaid',
                'payment' => $payment,
                'coverage_label' => $coverageLabel,
            ];
        }

        return $result;
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

            if (! empty($data['gambar']) && $data['gambar'] instanceof \Illuminate\Http\UploadedFile) {
                $memberData['gambar'] = $this->fileUploadService->uploadMemberPhoto($data['gambar'], $data['no_kp']);
            }

            $member = Member::create($memberData);

            $pendaftaranYuran = Yuran::where('jumlah', 12)->first();
            $paymentData = [
                'member_id' => $member->id,
                'tahun_bayar' => (int) date('Y'),
                'yuran_id' => $pendaftaranYuran?->id ?? 1,
                'no_resit_transfer' => $data['no_resit_transfer'] ?? null,
                'status' => Payment::STATUS_PENDING,
            ];

            $payment = Payment::create($paymentData);

            if (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $this->fileUploadService->uploadPaymentProof($data['bukti_bayaran'], $payment->id);
                $payment->update(['bukti_bayaran' => $path]);
            }

            return $member->fresh();
        });
    }

    public function createMemberByAdmin(array $data): Member
    {
        return DB::transaction(function () use ($data) {
            $statusId = $data['member_status_id'] ?? MemberStatus::where('code', 'tidak_aktif')->first()?->id;
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

            if (! empty($data['gambar']) && $data['gambar'] instanceof \Illuminate\Http\UploadedFile) {
                $memberData['gambar'] = $this->fileUploadService->uploadMemberPhoto($data['gambar'], $data['no_kp']);
            }

            $member = Member::create($memberData);

            $approveImmediately = ! empty($data['approve_immediately']);
            $hasPayment = ! empty($data['no_resit_transfer']) || (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof \Illuminate\Http\UploadedFile);

            if ($hasPayment) {
                $tahunBayar = (int) ($data['tahun_bayar'] ?? date('Y'));
                $yuranId = isset($data['yuran_id']) ? (int) $data['yuran_id'] : (Yuran::where('jumlah', 12)->first()?->id ?? 1);
                $paymentData = [
                    'member_id' => $member->id,
                    'tahun_bayar' => $tahunBayar,
                    'yuran_id' => $yuranId,
                    'no_resit_transfer' => $data['no_resit_transfer'] ?? null,
                    'no_resit_sistem' => $data['no_resit_sistem'] ?? null,
                    'status' => $approveImmediately ? Payment::STATUS_APPROVED : Payment::STATUS_PENDING,
                    'approved_by' => $approveImmediately ? auth()->id() : null,
                    'approved_at' => $approveImmediately ? now() : null,
                ];
                if (isset($data['tahun_mula'], $data['tahun_tamat'])) {
                    $paymentData['tahun_mula'] = (int) $data['tahun_mula'];
                    $paymentData['tahun_tamat'] = (int) $data['tahun_tamat'];
                }
                $payment = Payment::create($paymentData);

                if (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $this->fileUploadService->uploadPaymentProof($data['bukti_bayaran'], $payment->id);
                    $payment->update(['bukti_bayaran' => $path]);
                }

                if ($approveImmediately) {
                    $aktifStatus = MemberStatus::where('code', 'aktif')->first();
                    $member->update([
                        'no_ahli' => $member->no_ahli ?? 'AHL-'.str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
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
                'no_ahli' => $member->no_ahli ?: 'AHL-'.str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
                'member_status_id' => $aktifStatus?->id ?? $member->member_status_id,
            ]);
        });
    }

    public function submitRenewalPayment(string $noKp, array $data): Member
    {
        $noKp = preg_replace('/\D/', '', $noKp);
        $member = Member::where('no_kp', $noKp)->first();
        if (! $member) {
            throw new \InvalidArgumentException('Ahli tidak ditemui.');
        }

        $yuran = Yuran::where('jumlah', 10)->first();
        $payment = Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $yuran?->id ?? 2,
            'tahun_bayar' => (int) date('Y'),
            'no_resit_transfer' => $data['no_resit_transfer'] ?? null,
            'status' => Payment::STATUS_PENDING,
        ]);

        if (! empty($data['bukti_bayaran']) && $data['bukti_bayaran'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $this->fileUploadService->uploadPaymentProof($data['bukti_bayaran'], $payment->id);
            $payment->update(['bukti_bayaran' => $path]);
        }

        return $member->fresh(['payments.yuran']);
    }
}
