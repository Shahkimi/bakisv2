<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\Yuran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SemakPaymentReceiptDownloadTest extends TestCase
{
    use RefreshDatabase;

    private const string MEMBER_KP = '920202025678';

    /** @return array{member: Member, payment: Payment} */
    private function seedMemberAndPayment(string $paymentStatus): array
    {
        $status = MemberStatus::create([
            'name' => 'Aktif',
            'code' => 'aktif',
            'is_active' => true,
        ]);

        $jabatan = Jabatan::create([
            'nama_jabatan' => 'Semak Receipt Jabatan',
            'is_active' => true,
        ]);

        $jawatan = Jawatan::create([
            'kod_jawatan' => 'SR-1',
            'nama_jawatan' => 'Jawatan Semak',
            'is_active' => true,
        ]);

        $yuran = Yuran::create([
            'jenis_yuran' => 'Pembaharuan Keahlian',
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        $member = Member::create([
            'no_ahli' => 'SR-1001',
            'jabatan_id' => $jabatan->id,
            'jawatan_id' => $jawatan->id,
            'member_status_id' => $status->id,
            'nama' => 'Semak Bin Resit',
            'no_kp' => self::MEMBER_KP,
            'email' => 'semak-receipt@test.test',
            'jantina' => 'L',
            'tarikh_daftar' => now(),
        ]);

        $payment = Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $yuran->id,
            'tahun_bayar' => 2025,
            'tahun_mula' => 2025,
            'tahun_tamat' => 2025,
            'status' => $paymentStatus,
            'approved_at' => $paymentStatus === Payment::STATUS_APPROVED ? now() : null,
        ]);

        return ['member' => $member, 'payment' => $payment];
    }

    public function test_guest_can_download_receipt_with_matching_no_kp_when_approved(): void
    {
        ['payment' => $payment] = $this->seedMemberAndPayment(Payment::STATUS_APPROVED);

        $url = route('semak.payments.receipt', $payment).'?'.http_build_query(['no_kp' => self::MEMBER_KP]);

        $response = $this->get($url);

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));
        $this->assertGreaterThan(1000, strlen((string) $response->getContent()));
    }

    public function test_download_returns_not_found_when_no_kp_query_missing(): void
    {
        ['payment' => $payment] = $this->seedMemberAndPayment(Payment::STATUS_APPROVED);

        $this->get(route('semak.payments.receipt', $payment))
            ->assertNotFound();
    }

    public function test_download_returns_not_found_when_no_kp_does_not_match(): void
    {
        ['payment' => $payment] = $this->seedMemberAndPayment(Payment::STATUS_APPROVED);

        $url = route('semak.payments.receipt', $payment).'?'.http_build_query(['no_kp' => '111111111111']);

        $this->get($url)->assertNotFound();
    }

    public function test_download_returns_not_found_when_payment_not_approved(): void
    {
        ['payment' => $payment] = $this->seedMemberAndPayment(Payment::STATUS_PENDING);

        $url = route('semak.payments.receipt', $payment).'?'.http_build_query(['no_kp' => self::MEMBER_KP]);

        $this->get($url)->assertNotFound();
    }
}
