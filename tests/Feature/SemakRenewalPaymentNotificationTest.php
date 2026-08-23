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
use App\Notifications\PaymentProofPendingReviewNotification;
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
            'code' => Member::YURAN_CODE_PEMBAHARUAN,
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

    public function test_renewal_payment_sends_email_to_member_and_staff_reviewers(): void
    {
        Notification::fake();

        $refs = $this->seedRefs();

        User::factory()->admin()->create([
            'email' => 'admin-notify@example.test',
        ]);

        User::factory()->create([
            'email' => 'user-panel-notify@example.test',
            'role' => User::ROLE_USER,
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
            'years' => [(int) date('Y')],
            'bukti_bayaran' => $file,
        ])->assertRedirect();

        Notification::assertSentTimes(PaymentProofUploadedNotification::class, 1);
        Notification::assertSentTimes(PaymentProofPendingReviewNotification::class, 2);
    }

    public function test_renewal_payment_notifies_staff_when_member_has_no_email(): void
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
            'years' => [(int) date('Y')],
            'bukti_bayaran' => $file,
        ])->assertRedirect();

        Notification::assertSentTimes(PaymentProofUploadedNotification::class, 0);
        Notification::assertSentTimes(PaymentProofPendingReviewNotification::class, 1);
    }

    public function test_renewal_payment_creates_one_pending_row_per_year_with_shared_proof(): void
    {
        Notification::fake();

        $refs = $this->seedRefs();

        User::factory()->admin()->create([
            'email' => 'admin-kutipan@example.test',
        ]);

        Member::create([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['status']->id,
            'nama' => 'PEMBAHARIAN PELBAGAI TAHUN',
            'no_kp' => '900101011240',
            'email' => null,
            'jantina' => 'L',
            'tarikh_daftar' => now()->toDateString(),
        ]);

        $y = (int) now()->year;
        $file = FileTestHelper::createValidPdf(1, 'bukti-multi.pdf');

        $this->post(route('semak.bayar'), [
            'no_kp' => '900101011240',
            'years' => [$y, $y + 1, $y + 2],
            'bukti_bayaran' => $file,
        ])->assertRedirect();

        $member = Member::query()->where('no_kp', '900101011240')->first();
        $this->assertNotNull($member);

        $pending = Payment::query()
            ->where('member_id', $member->id)
            ->where('status', Payment::STATUS_PENDING)
            ->orderBy('tahun_mula')
            ->get();

        $this->assertCount(3, $pending);
        $this->assertSame([$y, $y + 1, $y + 2], $pending->pluck('tahun_mula')->all());

        $proofPaths = $pending->pluck('bukti_bayaran')->filter()->unique()->values();
        $this->assertCount(1, $proofPaths);
        foreach ($pending as $payment) {
            $this->assertSame($proofPaths[0], $payment->bukti_bayaran);
        }
    }

    public function test_renewal_notifications_skipped_when_mail_not_operational(): void
    {
        Notification::fake();
        config(['mail.default' => 'log']);

        $refs = $this->seedRefs();

        User::factory()->admin()->create([
            'email' => 'admin-notify@example.test',
        ]);

        Member::create([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['status']->id,
            'nama' => 'AHLI TANPA E-MEL SISTEM',
            'no_kp' => '900101011241',
            'email' => 'member@example.test',
            'jantina' => 'L',
            'tarikh_daftar' => now()->toDateString(),
        ]);

        $file = FileTestHelper::createValidPdf(1, 'bukti.pdf');

        $this->post(route('semak.bayar'), [
            'no_kp' => '900101011241',
            'years' => [(int) date('Y')],
            'bukti_bayaran' => $file,
        ])->assertRedirect();

        $member = Member::query()->where('no_kp', '900101011241')->first();
        $this->assertNotNull($member);
        $this->assertDatabaseHas('payments', [
            'member_id' => $member->id,
            'status' => Payment::STATUS_PENDING,
        ]);

        Notification::assertNothingSent();
    }
}
