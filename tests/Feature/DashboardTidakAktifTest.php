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

final class DashboardTidakAktifTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{status: MemberStatus, jabatan: Jabatan, jawatan: Jawatan}
     */
    private function seedReferenceData(): array
    {
        MemberStatus::create(['name' => 'Meninggal', 'code' => 'meninggal', 'is_active' => true]);
        MemberStatus::create(['name' => 'Pending', 'code' => 'pending', 'is_active' => true]);

        $status = MemberStatus::create(['name' => 'Tidak Aktif', 'code' => 'tidak_aktif', 'is_active' => true]);

        $jabatan = Jabatan::create([
            'nama_jabatan' => 'Unit Test Jabatan',
            'is_active' => true,
        ]);

        $jawatan = Jawatan::create([
            'kod_jawatan' => 'UT-DASH',
            'nama_jawatan' => 'Jawatan Test',
            'is_active' => true,
        ]);

        return compact('status', 'jabatan', 'jawatan');
    }

    /**
     * @param  array{status: MemberStatus, jabatan: Jabatan, jawatan: Jawatan}  $refs
     * @param  array<string, mixed>  $overrides
     */
    private function createMember(array $refs, array $overrides = []): Member
    {
        return Member::create(array_merge([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['status']->id,
            'nama' => 'TEST MEMBER',
            'no_kp' => '900101011234',
            'jantina' => 'L',
            'tarikh_daftar' => now()->toDateString(),
        ], $overrides));
    }

    public function test_guest_cannot_access_tidak_aktif_endpoint(): void
    {
        $this->getJson(route('dashboard.tidak-aktif'))
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_tidak_aktif_members(): void
    {
        $refs = $this->seedReferenceData();
        $user = User::factory()->create();

        $member = $this->createMember($refs, [
            'nama' => 'AHMAD BIN ALI',
            'no_kp' => '900101011234',
        ]);

        $yuran = Yuran::create([
            'jenis_yuran' => 'Pembaharuan Tahunan',
            'code' => Member::YURAN_CODE_PEMBAHARUAN,
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $yuran->id,
            'tahun_bayar' => 2020,
            'tahun_mula' => 2020,
            'tahun_tamat' => 2020,
            'status' => Payment::STATUS_APPROVED,
        ]);

        $this->actingAs($user)
            ->getJson(route('dashboard.tidak-aktif'))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'AHMAD BIN ALI')
            ->assertJsonPath('data.0.no_kp', '900101011234')
            ->assertJsonPath('data.0.last_payment', 2020);
    }

    public function test_search_filters_by_name_and_no_kp(): void
    {
        $refs = $this->seedReferenceData();
        $user = User::factory()->create();

        $this->createMember($refs, [
            'nama' => 'AHMAD BIN ALI',
            'no_kp' => '900101011234',
        ]);

        $this->createMember($refs, [
            'nama' => 'SITI BINTI OMAR',
            'no_kp' => '880202022345',
        ]);

        $this->actingAs($user)
            ->getJson(route('dashboard.tidak-aktif', ['search' => 'SITI']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'SITI BINTI OMAR');

        $this->actingAs($user)
            ->getJson(route('dashboard.tidak-aktif', ['search' => '880202']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.no_kp', '880202022345');
    }

    public function test_results_are_limited_to_five(): void
    {
        $refs = $this->seedReferenceData();
        $user = User::factory()->create();

        for ($i = 1; $i <= 7; $i++) {
            $this->createMember($refs, [
                'nama' => 'PEGAWAI '.$i,
                'no_kp' => '90010101'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
            ]);
        }

        $this->actingAs($user)
            ->getJson(route('dashboard.tidak-aktif'))
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }
}
