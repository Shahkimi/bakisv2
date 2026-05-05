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
use App\Notifications\PaymentApprovedReceiptNotification;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class PembayaranApproveReceiptNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /** @return array{member: Member, payment: Payment} */
    private function seedPendingPaymentForMemberWithEmail(?string $email = 'ahli@example.test'): array
    {
        $status = MemberStatus::create([
            'name' => 'Menunggu',
            'code' => 'menunggu',
            'is_active' => true,
        ]);

        $jabatan = Jabatan::create([
            'nama_jabatan' => 'Unit Test Jabatan',
            'is_active' => true,
        ]);

        $jawatan = Jawatan::create([
            'kod_jawatan' => 'UT-APR',
            'nama_jawatan' => 'Jawatan Test',
            'is_active' => true,
        ]);

        $yuran = Yuran::create([
            'jenis_yuran' => 'Pembaharuan Keahlian',
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        $member = Member::create([
            'no_ahli' => 'T-APR-1',
            'jabatan_id' => $jabatan->id,
            'jawatan_id' => $jawatan->id,
            'member_status_id' => $status->id,
            'nama' => 'Tester Bin Approve',
            'no_kp' => '910101011234',
            'email' => $email,
            'jantina' => 'L',
            'tarikh_daftar' => now(),
        ]);

        $payment = Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $yuran->id,
            'tahun_bayar' => 2025,
            'tahun_mula' => 2025,
            'tahun_tamat' => 2025,
            'status' => Payment::STATUS_PENDING,
        ]);

        return ['member' => $member, 'payment' => $payment];
    }

    public function test_admin_approve_pembayaran_sends_receipt_notification_when_member_has_email(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        ['payment' => $payment] = $this->seedPendingPaymentForMemberWithEmail();

        $this->actingAs($admin)
            ->post(route('admin.pembayaran.approve', $payment))
            ->assertRedirect(route('admin.pembayaran.index'));

        $payment->refresh();
        $this->assertSame(Payment::STATUS_APPROVED, $payment->status);

        Notification::assertSentOnDemand(
            PaymentApprovedReceiptNotification::class,
            function (PaymentApprovedReceiptNotification $notification) use ($payment): bool {
                return $notification->payment->is($payment);
            }
        );
    }

    public function test_admin_approve_pembayaran_does_not_send_notification_when_member_has_no_email(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        ['payment' => $payment] = $this->seedPendingPaymentForMemberWithEmail(null);

        $this->actingAs($admin)
            ->post(route('admin.pembayaran.approve', $payment))
            ->assertRedirect(route('admin.pembayaran.index'));

        Notification::assertNothingSent();
    }
}
