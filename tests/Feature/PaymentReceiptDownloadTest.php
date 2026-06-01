<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\User;
use App\Models\Yuran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PaymentReceiptDownloadTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{member: Member, payment: Payment} */
    private function seedMemberWithApprovedPayment(): array
    {
        $status = MemberStatus::create([
            'name' => 'Aktif',
            'code' => 'aktif',
            'is_active' => true,
        ]);

        $jabatan = Jabatan::create([
            'nama_jabatan' => 'Unit Test Jabatan',
            'is_active' => true,
        ]);

        $jawatan = Jawatan::create([
            'kod_jawatan' => 'UT-1',
            'nama_jawatan' => 'Jawatan Test',
            'is_active' => true,
        ]);

        $yuran = Yuran::create([
            'jenis_yuran' => 'Pembaharuan Keahlian',
            'code' => Member::YURAN_CODE_PEMBAHARUAN,
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        $member = Member::create([
            'no_ahli' => 'T-1001',
            'jabatan_id' => $jabatan->id,
            'jawatan_id' => $jawatan->id,
            'member_status_id' => $status->id,
            'nama' => 'Tester Bin Receipt',
            'no_kp' => '900101011234',
            'jantina' => 'L',
            'tarikh_daftar' => now(),
        ]);

        $payment = Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $yuran->id,
            'tahun_bayar' => 2025,
            'tahun_mula' => 2025,
            'tahun_tamat' => 2025,
            'no_resit_sistem' => 'SYS-RCPT-1',
            'status' => Payment::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        return ['member' => $member, 'payment' => $payment];
    }

    public function test_admin_can_download_pdf_for_approved_payment(): void
    {
        $admin = User::factory()->admin()->create();
        ['payment' => $payment] = $this->seedMemberWithApprovedPayment();

        $response = $this->actingAs($admin)->get(route('admin.payments.receipt', $payment));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));
        $this->assertGreaterThan(1000, strlen((string) $response->getContent()));
    }

    public function test_pending_payment_returns_not_found(): void
    {
        $admin = User::factory()->admin()->create();
        ['payment' => $payment] = $this->seedMemberWithApprovedPayment();
        $payment->update(['status' => Payment::STATUS_PENDING]);

        $this->actingAs($admin)->get(route('admin.payments.receipt', $payment))
            ->assertNotFound();
    }

    public function test_user_with_matching_kp_can_download(): void
    {
        ['member' => $member, 'payment' => $payment] = $this->seedMemberWithApprovedPayment();
        $user = User::factory()->create([
            'no_kp' => $member->no_kp,
            'role' => User::ROLE_USER,
        ]);

        $this->actingAs($user)->get(route('user.payments.receipt', $payment))
            ->assertOk();
    }

    public function test_user_with_different_kp_is_forbidden(): void
    {
        ['payment' => $payment] = $this->seedMemberWithApprovedPayment();
        $user = User::factory()->create([
            'no_kp' => '880202025678',
            'role' => User::ROLE_USER,
        ]);

        $this->actingAs($user)->get(route('user.payments.receipt', $payment))
            ->assertForbidden();
    }
}
