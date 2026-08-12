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
use App\Services\DashboardService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PembayaranWaiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * @return array{
     *     aktifStatus: MemberStatus,
     *     tidakAktifStatus: MemberStatus,
     *     jabatan: Jabatan,
     *     jawatan: Jawatan,
     *     yuran: Yuran,
     *     member: Member,
     *     payment: Payment,
     * }
     */
    private function seedApprovedPaymentForActiveMember(string $noKp = '900101011299'): array
    {
        $aktifStatus = MemberStatus::create([
            'name' => 'Aktif',
            'code' => 'aktif',
            'is_active' => true,
        ]);

        $tidakAktifStatus = MemberStatus::create([
            'name' => 'Tidak Aktif',
            'code' => 'tidak_aktif',
            'is_active' => true,
        ]);

        $jabatan = Jabatan::create([
            'nama_jabatan' => 'Unit Test Jabatan',
            'is_active' => true,
        ]);

        $jawatan = Jawatan::create([
            'kod_jawatan' => 'UT-WV',
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
            'no_ahli' => 'T-WV-1',
            'jabatan_id' => $jabatan->id,
            'jawatan_id' => $jawatan->id,
            'member_status_id' => $aktifStatus->id,
            'nama' => 'Tester Bin Waive',
            'no_kp' => $noKp,
            'email' => 'waive@example.test',
            'jantina' => 'L',
            'tarikh_daftar' => now(),
        ]);

        $currentYear = (int) now()->year;

        $payment = Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $yuran->id,
            'tahun_bayar' => $currentYear,
            'tahun_mula' => $currentYear,
            'tahun_tamat' => $currentYear,
            'status' => Payment::STATUS_APPROVED,
            'no_resit_sistem' => 'RESIT-WAIVE-0001',
        ]);

        return [
            'aktifStatus' => $aktifStatus,
            'tidakAktifStatus' => $tidakAktifStatus,
            'jabatan' => $jabatan,
            'jawatan' => $jawatan,
            'yuran' => $yuran,
            'member' => $member,
            'payment' => $payment,
        ];
    }

    public function test_staff_can_request_waiver_on_approved_payment(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $staff = User::factory()->create();

        $response = $this->actingAs($staff)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), [
                'waiver_reason' => 'Tersalah sahkan bayaran.',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Permohonan pembatalan telah dihantar.');

        $seed['payment']->refresh();
        $this->assertSame(Payment::STATUS_APPROVED, $seed['payment']->status);
        $this->assertSame($staff->id, $seed['payment']->waiver_requested_by);
        $this->assertNotNull($seed['payment']->waiver_requested_at);
        $this->assertSame('Tersalah sahkan bayaran.', $seed['payment']->waiver_reason);
        $this->assertTrue($seed['payment']->hasPendingWaiverRequest());
    }

    public function test_request_waiver_requires_reason(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $staff = User::factory()->create();

        $response = $this->actingAs($staff)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['waiver_reason']);

        $seed['payment']->refresh();
        $this->assertFalse($seed['payment']->hasPendingWaiverRequest());
    }

    public function test_staff_cannot_request_waiver_twice(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), [
                'waiver_reason' => 'Sebab pertama.',
            ])->assertOk();

        $response = $this->actingAs($staff)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), [
                'waiver_reason' => 'Sebab kedua.',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Pembayaran ini tidak boleh dimohon batal.');

        $seed['payment']->refresh();
        $this->assertSame('Sebab pertama.', $seed['payment']->waiver_reason);
    }

    public function test_request_waiver_on_pending_payment_is_rejected(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $seed['payment']->update(['status' => Payment::STATUS_PENDING]);

        $staff = User::factory()->create();

        $response = $this->actingAs($staff)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), [
                'waiver_reason' => 'Sebab.',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Pembayaran ini tidak boleh dimohon batal.');

        $seed['payment']->refresh();
        $this->assertNull($seed['payment']->waiver_requested_at);
    }

    public function test_staff_cannot_access_admin_waive_route(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->postJson(route('admin.pembayaran.waive', $seed['payment']), [
                'waiver_reason' => 'Cuba akses admin.',
            ])
            ->assertForbidden();

        $seed['payment']->refresh();
        $this->assertSame(Payment::STATUS_APPROVED, $seed['payment']->status);
    }

    public function test_admin_cannot_access_user_request_waiver_route(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), [
                'waiver_reason' => 'Cuba akses user.',
            ])
            ->assertForbidden();

        $seed['payment']->refresh();
        $this->assertFalse($seed['payment']->hasPendingWaiverRequest());
    }

    public function test_admin_can_directly_waive_approved_payment_and_deactivates_member(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.pembayaran.waive', $seed['payment']), [
                'waiver_reason' => 'Tersalah sahkan.',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Pembayaran telah dibatalkan.');

        $seed['payment']->refresh();
        $this->assertSame(Payment::STATUS_WAIVED, $seed['payment']->status);
        $this->assertSame($admin->id, $seed['payment']->waived_by);
        $this->assertNotNull($seed['payment']->waived_at);
        $this->assertSame('Tersalah sahkan.', $seed['payment']->waiver_reason);

        $member = $seed['member']->fresh();
        $this->assertSame($seed['tidakAktifStatus']->id, (int) $member->member_status_id);
    }

    public function test_admin_can_approve_waiver_request_without_body_and_keeps_requester_audit(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $staff = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($staff)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), [
                'waiver_reason' => 'Mohon batal oleh staff.',
            ])->assertOk();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.pembayaran.waive', $seed['payment']), []);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $seed['payment']->refresh();
        $this->assertSame(Payment::STATUS_WAIVED, $seed['payment']->status);
        $this->assertSame($admin->id, $seed['payment']->waived_by);
        $this->assertSame($staff->id, $seed['payment']->waiver_requested_by);
        $this->assertNotNull($seed['payment']->waiver_requested_at);
        $this->assertSame('Mohon batal oleh staff.', $seed['payment']->waiver_reason);
    }

    public function test_member_stays_aktif_when_second_approved_payment_covers_year(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $currentYear = (int) now()->year;

        $secondPayment = Payment::create([
            'member_id' => $seed['member']->id,
            'yuran_id' => $seed['yuran']->id,
            'tahun_bayar' => $currentYear,
            'tahun_mula' => $currentYear,
            'tahun_tamat' => $currentYear,
            'status' => Payment::STATUS_APPROVED,
            'no_resit_sistem' => 'RESIT-WAIVE-0002',
        ]);

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('admin.pembayaran.waive', $seed['payment']), [
                'waiver_reason' => 'Dibatalkan tapi ada bayaran lain.',
            ])->assertOk();

        $seed['payment']->refresh();
        $this->assertSame(Payment::STATUS_WAIVED, $seed['payment']->status);

        $secondPayment->refresh();
        $this->assertSame(Payment::STATUS_APPROVED, $secondPayment->status);

        $member = $seed['member']->fresh();
        $this->assertSame($seed['aktifStatus']->id, (int) $member->member_status_id);
    }

    public function test_admin_can_decline_waiver_request_and_second_decline_is_rejected(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $staff = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($staff)
            ->postJson(route('user.pembayaran.request-waiver', $seed['payment']), [
                'waiver_reason' => 'Mohon batal.',
            ])->assertOk();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.pembayaran.decline-waiver', $seed['payment']));

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Permohonan pembatalan telah ditolak. Pembayaran kekal disahkan.');

        $seed['payment']->refresh();
        $this->assertSame(Payment::STATUS_APPROVED, $seed['payment']->status);
        $this->assertNull($seed['payment']->waiver_requested_by);
        $this->assertNull($seed['payment']->waiver_requested_at);
        $this->assertNull($seed['payment']->waiver_reason);

        $secondDecline = $this->actingAs($admin)
            ->postJson(route('admin.pembayaran.decline-waiver', $seed['payment']));

        $secondDecline->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Tiada permohonan pembatalan untuk pembayaran ini.');
    }

    public function test_waived_payments_excluded_from_dashboard_status_counts(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $admin = User::factory()->admin()->create();
        $currentYear = (int) now()->year;

        $dashboardService = app(DashboardService::class);
        $before = $dashboardService->getStatusCounts($currentYear);
        $this->assertSame(1, $before['aktif']);
        $this->assertSame(0, $before['tidak_aktif']);

        $this->actingAs($admin)
            ->postJson(route('admin.pembayaran.waive', $seed['payment']), [
                'waiver_reason' => 'Tersalah sahkan.',
            ])->assertOk();

        $after = $dashboardService->getStatusCounts($currentYear);
        $this->assertSame(0, $after['aktif']);
        $this->assertSame(1, $after['tidak_aktif']);
    }

    public function test_waived_status_filter_returns_waived_rows_in_datatable(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $admin = User::factory()->admin()->create();

        $seed['payment']->update([
            'status' => Payment::STATUS_WAIVED,
            'waived_by' => $admin->id,
            'waived_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->getJson(route('admin.pembayaran.data', ['status' => 'waived', 'draw' => 1]));

        $response->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonPath('data.0.id', $seed['payment']->id)
            ->assertJsonPath('data.0.status', 'waived');
    }

    public function test_waived_payment_receipt_download_returns_404(): void
    {
        $seed = $this->seedApprovedPaymentForActiveMember();
        $admin = User::factory()->admin()->create();

        $seed['payment']->update([
            'status' => Payment::STATUS_WAIVED,
            'waived_by' => $admin->id,
            'waived_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.payments.receipt', $seed['payment']))
            ->assertNotFound();
    }
}
