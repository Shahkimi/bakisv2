<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\User;
use App\Models\Yuran;
use App\Notifications\PaymentProofUploadedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Support\FileTestHelper;
use Tests\TestCase;

final class SemakRenewalPaymentNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{status: MemberStatus, jabatan: Jabatan, jawatan: Jawatan}
     */
    private function seedRefs(): array
    {
        $status = MemberStatus::create([
            'name' => 'Tidak Aktif',
            'code' => 'tidak_aktif',
            'is_active' => true,
        ]);

        $jabatan = Jabatan::create([
            'nama_jabatan' => 'J Test',
            'is_active' => true,
        ]);

        $jawatan = Jawatan::create([
            'kod_jawatan' => 'JT-1',
            'nama_jawatan' => 'Jawatan Test',
            'is_active' => true,
        ]);

        Yuran::create([
            'jenis_yuran' => 'Pembaharuan Keahlian',
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        return [
            'status' => $status,
            'jabatan' => $jabatan,
            'jawatan' => $jawatan,
        ];
    }

    public function test_renewal_payment_sends_email_to_member_and_cc_admins(): void
    {
        Notification::fake();

        $refs = $this->seedRefs();

        $admin = User::factory()->admin()->create([
            'email' => 'admin-notify@example.test',
        ]);

        Member::create([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['status']->id,
            'nama' => 'AHLI UJIAN',
            'no_kp' => '900101011234',
            'email' => 'member@example.test',
            'jantina' => 'L',
            'tarikh_daftar' => now()->toDateString(),
        ]);

        $file = FileTestHelper::createValidPdf(1, 'bukti.pdf');

        $this->post(route('semak.bayar'), [
            'no_kp' => '900101011234',
            'no_resit_transfer' => 'REF-12345',
            'bukti_bayaran' => $file,
        ])->assertRedirect();

        Notification::assertSentOnDemand(
            PaymentProofUploadedNotification::class,
            function (PaymentProofUploadedNotification $notification) use ($admin): bool {
                return in_array(strtolower((string) $admin->email), $notification->ccAdminEmails, true);
            }
        );
    }

    public function test_renewal_payment_skips_notification_when_member_has_no_email(): void
    {
        Notification::fake();

        $refs = $this->seedRefs();

        User::factory()->admin()->create([
            'email' => 'admin-notify@example.test',
        ]);

        Member::create([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['status']->id,
            'nama' => 'TANPA EMAIL',
            'no_kp' => '900101011239',
            'email' => null,
            'jantina' => 'L',
            'tarikh_daftar' => now()->toDateString(),
        ]);

        $file = FileTestHelper::createValidPdf(1, 'bukti.pdf');

        $this->post(route('semak.bayar'), [
            'no_kp' => '900101011239',
            'no_resit_transfer' => 'REF-999',
            'bukti_bayaran' => $file,
        ])->assertRedirect();

        Notification::assertNothingSent();
    }
}
