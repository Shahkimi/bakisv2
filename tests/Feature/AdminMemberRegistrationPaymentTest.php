<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\Yuran;
use App\Services\FileUploadService;
use App\Services\MemberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminMemberRegistrationPaymentTest extends TestCase
{
    use RefreshDatabase;

    private function makeDependencies(): MemberService
    {
        return new MemberService(new FileUploadService);
    }

    private function seedBasicReferenceData(): array
    {
        $notActiveStatus = MemberStatus::create([
            'name' => 'Tidak Aktif',
            'code' => 'tidak_aktif',
            'is_active' => true,
        ]);

        MemberStatus::create([
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

        $pendaftaranYuran = Yuran::create([
            'jenis_yuran' => 'Pendaftaran Keahlian',
            'jumlah' => 12.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        $pembaharuanYuran10 = Yuran::create([
            'jenis_yuran' => 'Pembaharuan Keahlian',
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        return [
            'notActiveStatus' => $notActiveStatus,
            'jabatan' => $jabatan,
            'jawatan' => $jawatan,
            'pendaftaranYuran' => $pendaftaranYuran,
            'pembaharuanYuran10' => $pembaharuanYuran10,
        ];
    }

    public function test_registration_only_creates_single_payment_with_correct_coverage(): void
    {
        $service = $this->makeDependencies();
        $refs = $this->seedBasicReferenceData();

        $tahunBayar = 2024;
        $member = $service->createMemberByAdmin([
            'member_status_id' => $refs['notActiveStatus']->id,
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'nama' => 'Ahli Test 1',
            'no_kp' => '900101011234',
            'jantina' => 'L',
            'alamat1' => 'Alamat 1',
            'tarikh_daftar' => '2024-01-01',
            'approve_immediately' => 1,

            'tahun_bayar' => $tahunBayar,
            'yuran_id' => $refs['pendaftaranYuran']->id,
            'payment_combo' => 'registration_only',
            'tahun_mula' => $tahunBayar,
            'tahun_tamat' => $tahunBayar,

            'no_resit_transfer' => 'RESIT-REG-001',
            'no_resit_sistem' => null,
        ]);

        $payments = $member->payments()->orderBy('id')->get();

        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $this->assertSame($refs['pendaftaranYuran']->id, (int) $payment->yuran_id);
        $this->assertSame($tahunBayar, (int) $payment->tahun_mula);
        $this->assertSame($tahunBayar, (int) $payment->tahun_tamat);
        $this->assertSame(Payment::STATUS_APPROVED, $payment->status);
    }

    public function test_registration_advance_next_year_creates_two_payments_with_split_coverage(): void
    {
        $service = $this->makeDependencies();
        $refs = $this->seedBasicReferenceData();

        $tahunBayar = 2024;
        $member = $service->createMemberByAdmin([
            'member_status_id' => $refs['notActiveStatus']->id,
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'nama' => 'Ahli Test 2',
            'no_kp' => '900101011235',
            'jantina' => 'P',
            'alamat1' => 'Alamat 1',
            'tarikh_daftar' => '2024-01-01',
            'approve_immediately' => 1,

            'tahun_bayar' => $tahunBayar,
            'yuran_id' => $refs['pendaftaranYuran']->id,
            'payment_combo' => 'registration_advance_next_year',
            'tahun_mula' => $tahunBayar,
            'tahun_tamat' => $tahunBayar + 1,

            'no_resit_transfer' => 'RESIT-REGADV-001',
            'no_resit_sistem' => null,
        ]);

        $payments = $member->payments()->orderBy('id')->get();

        $this->assertCount(2, $payments);

        $registrationPayment = $payments->first(fn (Payment $p) => (int) $p->yuran_id === (int) $refs['pendaftaranYuran']->id);
        $advancePayment = $payments->first(fn (Payment $p) => (int) $p->yuran_id === (int) $refs['pembaharuanYuran10']->id);

        $this->assertNotNull($registrationPayment);
        $this->assertNotNull($advancePayment);

        $this->assertSame($tahunBayar, (int) $registrationPayment->tahun_mula);
        $this->assertSame($tahunBayar, (int) $registrationPayment->tahun_tamat);

        $this->assertSame($tahunBayar + 1, (int) $advancePayment->tahun_mula);
        $this->assertSame($tahunBayar + 1, (int) $advancePayment->tahun_tamat);

        $this->assertSame(Payment::STATUS_APPROVED, $registrationPayment->status);
        $this->assertSame(Payment::STATUS_APPROVED, $advancePayment->status);
    }

    public function test_backdated_registration_only_allows_custom_year_coverage_of_one_year(): void
    {
        $service = $this->makeDependencies();
        $refs = $this->seedBasicReferenceData();

        $tahunBayar = 2024;
        $tahunCoverage = 2022;

        $member = $service->createMemberByAdmin([
            'member_status_id' => $refs['notActiveStatus']->id,
            'jabatan_id' => $refs['jabatan']->id,
            'jawatan_id' => $refs['jawatan']->id,
            'nama' => 'Ahli Test 3',
            'no_kp' => '900101011236',
            'jantina' => 'L',
            'alamat1' => 'Alamat 1',
            'tarikh_daftar' => '2024-01-01',
            'approve_immediately' => 1,

            'tahun_bayar' => $tahunBayar,
            'yuran_id' => $refs['pendaftaranYuran']->id,
            'payment_combo' => 'registration_only',
            'tahun_mula' => $tahunCoverage,
            'tahun_tamat' => $tahunCoverage,

            'no_resit_transfer' => 'RESIT-REG-BACK-001',
            'no_resit_sistem' => null,
        ]);

        $payments = $member->payments()->orderBy('id')->get();

        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $this->assertSame($refs['pendaftaranYuran']->id, (int) $payment->yuran_id);
        $this->assertSame($tahunCoverage, (int) $payment->tahun_mula);
        $this->assertSame($tahunCoverage, (int) $payment->tahun_tamat);
        $this->assertSame(Payment::STATUS_APPROVED, $payment->status);
    }
}
