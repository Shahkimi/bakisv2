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
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

final class AdminPembayaranApprovalTest extends TestCase
{
    use RefreshDatabase;

    private function seedBasicReferenceData(): array
    {
        $notActiveStatus = MemberStatus::create([
            'name' => 'Tidak Aktif',
            'code' => 'tidak_aktif',
            'is_active' => true,
        ]);

        $activeStatus = MemberStatus::create([
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

        return [
            'notActiveStatus' => $notActiveStatus,
            'activeStatus' => $activeStatus,
            'jabatan' => $jabatan,
            'jawatan' => $jawatan,
            'yuran' => $yuran,
        ];
    }

    private function makeMemberWithPendingPayment(array $refs, string $nama, string $noKp): array
    {
        $member = Member::create([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['notActiveStatus']->id,
            'nama' => $nama,
            'no_kp' => $noKp,
            'email' => null,
            'jantina' => 'L',
            'alamat1' => 'Alamat 1',
            'tarikh_daftar' => now()->toDateString(),
        ]);

        $payment = Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $refs['yuran']->id,
            'tahun_bayar' => (int) date('Y'),
            'status' => Payment::STATUS_PENDING,
        ]);

        return [$member, $payment];
    }

    public function test_admin_approving_payment_stamps_approved_by_and_logs(): void
    {
        Log::spy();

        $refs = $this->seedBasicReferenceData();
        [, $payment] = $this->makeMemberWithPendingPayment($refs, 'AHLI UJIAN ADMIN', '900101011111');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.pembayaran.approve', $payment))
            ->assertRedirect(route('admin.pembayaran.index'));

        $payment->refresh();
        $this->assertSame(Payment::STATUS_APPROVED, $payment->status);
        $this->assertSame($admin->id, $payment->approved_by);
        $this->assertNotNull($payment->approved_at);

        Log::shouldHaveReceived('info')
            ->with('Pembayaran disahkan.', [
                'payment_id' => $payment->id,
                'member_id' => $payment->member_id,
                'approved_by' => $admin->id,
            ])
            ->once();
    }

    public function test_user_role_approving_payment_stamps_approved_by(): void
    {
        $refs = $this->seedBasicReferenceData();
        [, $payment] = $this->makeMemberWithPendingPayment($refs, 'AHLI UJIAN USER', '900101012222');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('user.pembayaran.approve', $payment))
            ->assertRedirect(route('user.pembayaran.index'));

        $payment->refresh();
        $this->assertSame(Payment::STATUS_APPROVED, $payment->status);
        $this->assertSame($user->id, $payment->approved_by);
        $this->assertNotNull($payment->approved_at);
    }

    public function test_admin_data_table_exposes_approver_for_approved_payment(): void
    {
        $refs = $this->seedBasicReferenceData();
        [, $payment] = $this->makeMemberWithPendingPayment($refs, 'AHLI UJIAN DATA', '900101013333');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.pembayaran.approve', $payment));

        $response = $this->actingAs($admin)->getJson(
            route('admin.pembayaran.data', ['status' => 'approved', 'draw' => 1, 'start' => 0, 'length' => 10])
        );

        $response->assertOk();
        $row = collect($response->json('data'))->firstWhere('id', $payment->id);
        $this->assertNotNull($row);
        $this->assertSame($admin->name, $row['approved_by']);
        $this->assertNotNull($row['approved_at']);
    }

    public function test_admin_data_table_exposes_rejecter_for_rejected_payment(): void
    {
        $refs = $this->seedBasicReferenceData();
        [, $payment] = $this->makeMemberWithPendingPayment($refs, 'AHLI UJIAN TOLAK', '900101014444');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.pembayaran.reject', $payment), [
            'catatan_admin' => 'Resit tidak jelas.',
        ]);

        $response = $this->actingAs($admin)->getJson(
            route('admin.pembayaran.data', ['status' => 'rejected', 'draw' => 1, 'start' => 0, 'length' => 10])
        );

        $response->assertOk();
        $row = collect($response->json('data'))->firstWhere('id', $payment->id);
        $this->assertNotNull($row);
        $this->assertSame($admin->name, $row['approved_by']);
        $this->assertNotNull($row['approved_at']);
        $this->assertSame('Resit tidak jelas.', $row['catatan_admin']);
    }
}
