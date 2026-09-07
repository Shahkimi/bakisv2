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

final class SemakRenewalYearDisplayTest extends TestCase
{
    use RefreshDatabase;

    private const MEMBER_KP = '900101011234';

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

    /**
     * Mirrors MemberService::submitRenewalPayment: multi-year renewal creates one
     * Payment row per selected year, all sharing the same tahun_bayar (the year the
     * payment was made), with the coverage year stored in tahun_mula/tahun_tamat.
     */
    private function createMemberWithPendingMultiYearRenewal(array $refs, array $coverageYears): Member
    {
        $member = Member::create([
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'member_status_id' => $refs['status']->id,
            'nama' => 'AHLI SEMAK',
            'no_kp' => self::MEMBER_KP,
            'jantina' => 'L',
            'tarikh_daftar' => now()->toDateString(),
        ]);

        $tahunBayar = (int) date('Y');

        foreach ($coverageYears as $year) {
            Payment::create([
                'member_id' => $member->id,
                'yuran_id' => $refs['yuran']->id,
                'tahun_bayar' => $tahunBayar,
                'tahun_mula' => $year,
                'tahun_tamat' => $year,
                'status' => Payment::STATUS_PENDING,
            ]);
        }

        return $member;
    }

    public function test_coverage_label_uses_coverage_years_not_tahun_bayar(): void
    {
        $current = (int) date('Y');

        $single = new Payment(['tahun_bayar' => $current, 'tahun_mula' => $current, 'tahun_tamat' => $current]);
        $this->assertSame((string) $current, $single->coverageLabel());

        $range = new Payment(['tahun_bayar' => $current, 'tahun_mula' => $current, 'tahun_tamat' => $current + 1]);
        $this->assertSame($current.'–'.($current + 1), $range->coverageLabel());

        // Legacy row with no tahun_mula/tahun_tamat falls back to tahun_bayar.
        $legacy = new Payment(['tahun_bayar' => $current, 'tahun_mula' => null, 'tahun_tamat' => null]);
        $this->assertSame((string) $current, $legacy->coverageLabel());
    }

    public function test_semak_result_shows_distinct_years_for_multi_year_pending_renewal(): void
    {
        $refs = $this->seedRefs();
        $currentYear = (int) date('Y');
        $nextYear = $currentYear + 1;

        $this->createMemberWithPendingMultiYearRenewal($refs, [$currentYear, $nextYear]);

        $response = $this->get(route('semak.result', ['no_kp' => self::MEMBER_KP]));

        $response->assertOk();
        $response->assertSeeInOrder([(string) $currentYear, (string) $nextYear]);
    }

    public function test_semak_payments_data_returns_distinct_coverage_labels_ordered_desc(): void
    {
        $refs = $this->seedRefs();
        $currentYear = (int) date('Y');
        $nextYear = $currentYear + 1;

        $this->createMemberWithPendingMultiYearRenewal($refs, [$currentYear, $nextYear]);

        $response = $this->get(route('semak.payments.data', ['no_kp' => self::MEMBER_KP]), [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(2, $data);
        $this->assertSame((string) $nextYear, $data[0]['tahun_label']);
        $this->assertSame((string) $currentYear, $data[1]['tahun_label']);
    }
}
