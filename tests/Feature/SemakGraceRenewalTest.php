<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\Yuran;
use App\Services\MemberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SemakGraceRenewalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{status: MemberStatus, jabatan: Jabatan, jawatan: Jawatan, yuran: Yuran}
     */
    private function seedRefs(): array
    {
        $status = MemberStatus::create([
            'name' => 'Aktif',
            'code' => 'aktif',
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

        $yuran = Yuran::create([
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
            'yuran' => $yuran,
        ];
    }

    private function createMemberWithApprovedPaymentFor(int $year, array $refs): Member
    {
        $member = Member::create([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['status']->id,
            'nama' => 'AHLI GRACE',
            'no_kp' => '900101011234',
            'jantina' => 'L',
            'tarikh_daftar' => now()->toDateString(),
        ]);

        Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $refs['yuran']->id,
            'tahun_bayar' => $year,
            'tahun_mula' => $year,
            'tahun_tamat' => $year,
            'status' => Payment::STATUS_APPROVED,
        ]);

        return $member;
    }

    public function test_member_active_by_grace_gets_needs_renewal_flag_and_renewal_form(): void
    {
        $refs = $this->seedRefs();
        $lastYear = (int) date('Y') - 1;
        $this->createMemberWithApprovedPaymentFor($lastYear, $refs);

        $result = app(MemberService::class)->checkMemberStatus('900101011234');

        $this->assertSame('active', $result['status']);
        $this->assertTrue($result['needs_renewal']);

        $response = $this->get(route('semak.result', ['no_kp' => '900101011234']));

        $response->assertOk();
        $response->assertSee('id="paymentForm"', false);
        $response->assertSee('semakYear'.date('Y'), false);
    }

    public function test_member_paid_current_year_sees_no_renewal_form(): void
    {
        $refs = $this->seedRefs();
        $this->createMemberWithApprovedPaymentFor((int) date('Y'), $refs);

        $result = app(MemberService::class)->checkMemberStatus('900101011234');

        $this->assertSame('active', $result['status']);
        $this->assertFalse($result['needs_renewal']);

        $response = $this->get(route('semak.result', ['no_kp' => '900101011234']));

        $response->assertOk();
        $response->assertDontSee('id="paymentForm"', false);
    }
}
