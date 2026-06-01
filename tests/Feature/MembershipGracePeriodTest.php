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

/**
 * Verifies the 1-year grace-period membership policy.
 *
 * Rule under test:
 * - Every approved payment grants coverage for `tahun_mula`..`tahun_tamat`
 *   plus an additional `Member::GRACE_YEARS` (= 1) grace year.
 * - A member shows as "Aktif" while the current year is within that window.
 * - Membership fee:
 *     - Pembaharuan yuran amount while still within active grace.
 *     - Pendaftaran yuran amount once grace has lapsed.
 */
final class MembershipGracePeriodTest extends TestCase
{
    use RefreshDatabase;

    private MemberStatus $aktifStatus;

    private MemberStatus $tidakAktifStatus;

    private Yuran $newMembershipYuran;

    private Yuran $renewalYuran;

    private Jabatan $jabatan;

    private Jawatan $jawatan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->aktifStatus = MemberStatus::create([
            'name' => 'Aktif',
            'code' => 'aktif',
            'is_active' => true,
        ]);

        $this->tidakAktifStatus = MemberStatus::create([
            'name' => 'Tidak Aktif',
            'code' => 'tidak_aktif',
            'is_active' => true,
        ]);

        $this->newMembershipYuran = Yuran::create([
            'jenis_yuran' => 'Pendaftaran Keahlian',
            'code' => Member::YURAN_CODE_PENDAFTARAN,
            'jumlah' => 12.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
            'is_show' => true,
        ]);

        $this->renewalYuran = Yuran::create([
            'jenis_yuran' => 'Pembaharuan Keahlian',
            'code' => Member::YURAN_CODE_PEMBAHARUAN,
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
            'is_show' => true,
        ]);

        $this->jabatan = Jabatan::create([
            'nama_jabatan' => 'Jabatan Ujian',
            'is_active' => true,
        ]);
        $this->jawatan = Jawatan::create([
            'kod_jawatan' => 'UT-1',
            'nama_jawatan' => 'Pegawai Ujian',
            'is_active' => true,
        ]);
    }

    public function test_grace_year_constant_is_one_year(): void
    {
        $this->assertSame(1, Member::GRACE_YEARS);
        $this->assertSame('pendaftaran_keahlian', Member::YURAN_CODE_PENDAFTARAN);
        $this->assertSame('pembaharuan_tahunan', Member::YURAN_CODE_PEMBAHARUAN);
    }

    public function test_member_is_aktif_during_paid_year(): void
    {
        $currentYear = (int) date('Y');
        $member = $this->makeMember();
        $this->createApprovedPayment($member, $currentYear, $currentYear, $currentYear);

        $this->assertTrue($member->isAktifThisYear());
        $this->assertSame((float) $this->renewalYuran->jumlah, $member->getMembershipFee());
    }

    public function test_member_remains_aktif_during_grace_year_after_payment(): void
    {
        $currentYear = (int) date('Y');
        $lastPaidYear = $currentYear - 1;

        $member = $this->makeMember();
        $this->createApprovedPayment($member, $lastPaidYear, $lastPaidYear, $lastPaidYear);

        $this->assertTrue(
            $member->isAktifThisYear(),
            'Member must stay Aktif during the grace year following the last paid year.'
        );
        $this->assertSame((float) $this->renewalYuran->jumlah, $member->getMembershipFee());
    }

    public function test_member_is_tidak_aktif_when_grace_period_expires(): void
    {
        $currentYear = (int) date('Y');
        $lastPaidYear = $currentYear - 2;

        $member = $this->makeMember();
        $this->createApprovedPayment($member, $lastPaidYear, $lastPaidYear, $lastPaidYear);

        $this->assertFalse(
            $member->isAktifThisYear(),
            'Member must lapse once the grace year has passed.'
        );
        $this->assertSame((float) $this->newMembershipYuran->jumlah, $member->getMembershipFee());
    }

    public function test_new_member_with_no_payments_charges_new_membership_fee(): void
    {
        $member = $this->makeMember();

        $this->assertFalse($member->isAktifThisYear());
        $this->assertSame((float) $this->newMembershipYuran->jumlah, $member->getMembershipFee());
    }

    public function test_membership_fee_reflects_admin_updated_yuran_amounts(): void
    {
        $this->renewalYuran->update(['jumlah' => 11.50]);
        $this->newMembershipYuran->update(['jumlah' => 13.50]);

        $currentYear = (int) date('Y');
        $activeMember = $this->makeMember(null, '850101015556');
        $this->createApprovedPayment($activeMember, $currentYear, $currentYear, $currentYear);

        $lapsedMember = $this->makeMember(null, '850101015557');
        $this->createApprovedPayment($lapsedMember, $currentYear - 3, $currentYear - 3, $currentYear - 3);

        $this->assertSame(11.50, $activeMember->fresh()->getMembershipFee());
        $this->assertSame(13.50, $lapsedMember->fresh()->getMembershipFee());
    }

    public function test_pending_payment_does_not_grant_aktif_status(): void
    {
        $currentYear = (int) date('Y');
        $member = $this->makeMember();

        Payment::create([
            'member_id' => $member->id,
            'yuran_id' => $this->renewalYuran->id,
            'tahun_bayar' => $currentYear,
            'tahun_mula' => $currentYear,
            'tahun_tamat' => $currentYear,
            'status' => Payment::STATUS_PENDING,
        ]);

        $this->assertFalse($member->isAktifThisYear());
    }

    public function test_multi_year_payment_grace_extends_one_year_after_tahun_tamat(): void
    {
        $currentYear = (int) date('Y');
        $member = $this->makeMember();
        $this->createApprovedPayment(
            $member,
            $currentYear,
            $currentYear - 3,
            $currentYear - 2,
        );

        $this->assertFalse(
            $member->isAktifThisYear(),
            'Multi-year payment with tahun_tamat 2 years ago must not keep member Aktif.'
        );
    }

    public function test_multi_year_payment_grace_keeps_member_aktif_when_tamat_is_last_year(): void
    {
        $currentYear = (int) date('Y');

        $member = $this->makeMember();
        $this->createApprovedPayment(
            $member,
            $currentYear,
            $currentYear - 2,
            $currentYear - 1,
        );

        $this->assertTrue(
            $member->isAktifThisYear(),
            'A multi-year payment whose tahun_tamat is last year must still be Aktif via grace.'
        );
    }

    public function test_list_status_display_returns_aktif_when_within_grace(): void
    {
        $currentYear = (int) date('Y');
        $member = $this->makeMember();
        $this->createApprovedPayment($member, $currentYear - 1, $currentYear - 1, $currentYear - 1);

        [$label, $code] = $member->listStatusDisplayForCurrentYear();

        $this->assertSame('Aktif', $label);
        $this->assertSame('aktif', $code);
    }

    public function test_list_status_display_returns_tidak_aktif_when_grace_expired(): void
    {
        $currentYear = (int) date('Y');
        $member = $this->makeMember();
        $this->createApprovedPayment($member, $currentYear - 2, $currentYear - 2, $currentYear - 2);

        [$label, $code] = $member->listStatusDisplayForCurrentYear();

        $this->assertSame('Tidak Aktif', $label);
        $this->assertSame('tidak_aktif', $code);
    }

    public function test_meninggal_status_overrides_grace_logic(): void
    {
        $meninggalStatus = MemberStatus::create([
            'name' => 'Meninggal Dunia',
            'code' => 'meninggal',
            'is_active' => false,
        ]);

        $currentYear = (int) date('Y');
        $member = $this->makeMember($meninggalStatus->id);
        $this->createApprovedPayment($member, $currentYear, $currentYear, $currentYear);

        [, $code] = $member->listStatusDisplayForCurrentYear();

        $this->assertSame('meninggal', $code);
    }

    public function test_aktif_this_year_exists_scope_agrees_with_per_row_method(): void
    {
        $currentYear = (int) date('Y');

        $withinGrace = $this->makeMember(null, '850101015555');
        $this->createApprovedPayment($withinGrace, $currentYear - 1, $currentYear - 1, $currentYear - 1);

        $lapsed = $this->makeMember(null, '850102015555');
        $this->createApprovedPayment($lapsed, $currentYear - 3, $currentYear - 3, $currentYear - 3);

        $members = Member::query()
            ->withAktifThisYearExists()
            ->orderBy('id')
            ->get()
            ->keyBy('id');

        $this->assertTrue((bool) $members[$withinGrace->id]->aktif_this_year);
        $this->assertFalse((bool) $members[$lapsed->id]->aktif_this_year);
    }

    public function test_aina_full_lifecycle_scenario(): void
    {
        $member = $this->makeMember();

        $this->createApprovedPayment($member, 2020, 2020, 2020, $this->newMembershipYuran);
        $this->assertTrueForYear($member, 2020, 'Aina must be Aktif in her join year.');
        $this->assertTrueForYear($member, 2021, 'Aina must be Aktif in 2021 (grace from 2020).');

        $this->createApprovedPayment($member, 2021, 2021, 2021, $this->renewalYuran);
        $this->assertTrueForYear($member, 2021);
        $this->assertTrueForYear($member, 2022, 'Aina must be Aktif in 2022 (grace from 2021).');

        $this->assertFalseForYear($member, 2023, 'Aina must be Tidak Aktif in 2023.');

        $this->assertFalseForYear($member, 2024, 'Aina must remain Tidak Aktif in 2024 before paying.');
    }

    private function makeMember(?int $statusId = null, string $noKp = '850101015555'): Member
    {
        return Member::create([
            'jabatan_id' => $this->jabatan->id,
            'jawatan_id' => $this->jawatan->id,
            'member_status_id' => $statusId ?? $this->aktifStatus->id,
            'nama' => 'Aina Binti Ujian',
            'no_kp' => $noKp,
            'jantina' => 'P',
            'tarikh_daftar' => now()->toDateString(),
        ]);
    }

    private function createApprovedPayment(
        Member $member,
        int $tahunBayar,
        int $tahunMula,
        int $tahunTamat,
        ?Yuran $yuran = null,
    ): Payment {
        return Payment::create([
            'member_id' => $member->id,
            'yuran_id' => ($yuran ?? $this->renewalYuran)->id,
            'tahun_bayar' => $tahunBayar,
            'tahun_mula' => $tahunMula,
            'tahun_tamat' => $tahunTamat,
            'status' => Payment::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    private function memberIsAktifInYear(Member $member, int $year): bool
    {
        $graceThreshold = $year - Member::GRACE_YEARS;

        return $member->payments()
            ->where('status', Payment::STATUS_APPROVED)
            ->where('tahun_mula', '<=', $year)
            ->where(function ($q) use ($graceThreshold) {
                $q->whereNull('tahun_tamat')
                    ->orWhere('tahun_tamat', '>=', $graceThreshold);
            })
            ->exists();
    }

    private function assertTrueForYear(Member $member, int $year, string $message = ''): void
    {
        $this->assertTrue(
            $this->memberIsAktifInYear($member, $year),
            $message !== '' ? $message : "Member should be Aktif in {$year}."
        );
    }

    private function assertFalseForYear(Member $member, int $year, string $message = ''): void
    {
        $this->assertFalse(
            $this->memberIsAktifInYear($member, $year),
            $message !== '' ? $message : "Member should be Tidak Aktif in {$year}."
        );
    }
}
