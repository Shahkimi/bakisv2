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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

final class AdminKutipanTest extends TestCase
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

        $pembaharuanYuran10 = Yuran::create([
            'jenis_yuran' => 'Pembaharuan Keahlian',
            'jumlah' => 10.00,
            'tempoh_tahun' => 1,
            'is_active' => true,
        ]);

        return [
            'notActiveStatus' => $notActiveStatus,
            'activeStatus' => $activeStatus,
            'jabatan' => $jabatan,
            'jawatan' => $jawatan,
            'pembaharuanYuran10' => $pembaharuanYuran10,
        ];
    }

    private function makeUser(): User
    {
        return User::factory()->create();
    }

    private function makeRenewalMember(array $refs, string $nama, string $noKp, ?string $noAhli = null): Member
    {
        return Member::create([
            'no_ahli' => $noAhli,
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
    }

    public function test_guest_cannot_access_kutipan_autocomplete(): void
    {
        $this->get(route('admin.kutipan.autocomplete', ['search' => '900']))
            ->assertRedirect(route('login'));
    }

    public function test_autocomplete_by_no_kp_returns_member_option(): void
    {
        $refs = $this->seedBasicReferenceData();
        $member = $this->makeRenewalMember($refs, 'AHLI UJIAN', '900101011234');

        $user = $this->makeUser();

        $this->actingAs($user);

        $response = $this->getJson(route('admin.kutipan.autocomplete', [
            'search' => '900',
        ]));

        $response->assertOk()
            ->assertJsonCount(1, 'members')
            ->assertJsonPath('members.0.text', $member->nama.' - '.$member->no_kp)
            ->assertJsonStructure([
                'members' => [
                    ['id', 'text', 'nama', 'no_kp', 'no_ahli'],
                ],
            ]);
    }

    public function test_autocomplete_by_no_ahli_returns_member_option(): void
    {
        $refs = $this->seedBasicReferenceData();
        $member = $this->makeRenewalMember($refs, 'AHLI NOAHLI', '900101011235', 'AHL-99999');

        $user = $this->makeUser();

        $this->actingAs($user);

        $response = $this->getJson(route('admin.kutipan.autocomplete', [
            'search' => 'AHL-99999',
        ]));

        $response->assertOk()
            ->assertJsonPath('members.0.text', $member->nama.' - '.$member->no_kp);
    }

    public function test_autocomplete_by_name_returns_member_option(): void
    {
        $refs = $this->seedBasicReferenceData();
        $member = $this->makeRenewalMember($refs, 'NAMA AHLI PANJANG', '900101011236');

        $user = $this->makeUser();

        $this->actingAs($user);

        $response = $this->getJson(route('admin.kutipan.autocomplete', [
            'search' => 'PANJ',
        ]));

        $response->assertOk()
            ->assertJsonPath('members.0.text', $member->nama.' - '.$member->no_kp);
    }

    public function test_member_page_loads_with_encrypted_no_kp(): void
    {
        $refs = $this->seedBasicReferenceData();
        $member = $this->makeRenewalMember($refs, 'AHLI PAPAR', '900101011238');

        $user = $this->makeUser();
        $this->actingAs($user);

        $encrypted = Crypt::encryptString($member->no_kp);

        $this->get(route('admin.kutipan.member', ['encryptedNoKp' => $encrypted]))
            ->assertOk()
            ->assertSeeText('Maklumat Ahli')
            ->assertSeeText($member->nama)
            ->assertSeeText($member->no_kp);
    }

    public function test_member_page_invalid_encryption_redirects_to_search(): void
    {
        $refs = $this->seedBasicReferenceData();
        $this->makeRenewalMember($refs, 'AHLI PAPAR', '900101011239');

        $user = $this->makeUser();
        $this->actingAs($user);

        $this->get(route('admin.kutipan.member', ['encryptedNoKp' => 'invalid-token']))
            ->assertRedirect(route('admin.kutipan.index'))
            ->assertSessionHas('error', 'Pautan tidak sah.');
    }

    public function test_collect_payment_approves_payment_generates_receipt_and_activates_member(): void
    {
        $refs = $this->seedBasicReferenceData();
        $member = $this->makeRenewalMember($refs, 'AHLI KUTIPAN', '900101011237');

        $user = $this->makeUser();

        $this->actingAs($user);

        $tahun = (int) now()->year;
        $proof = UploadedFile::fake()->image('proof.jpg');

        $payload = [
            'member_id' => $member->id,
            'yuran_id' => $refs['pembaharuanYuran10']->id,
            'tahun_bayar' => $tahun,
            'tahun_mula' => $tahun,
            'tahun_tamat' => $tahun,
            'no_resit_transfer' => 'RESIT-KUT-001',
            'catatan_admin' => 'Test kutipan',
            'bukti_bayaran' => $proof,
        ];

        $response = $this->post(route('admin.kutipan.collect'), $payload, [
            'Accept' => 'application/json',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('receipt_no', 'RESIT-'.$tahun.'-00001');

        $this->assertDatabaseHas('payments', [
            'member_id' => $member->id,
            'yuran_id' => $refs['pembaharuanYuran10']->id,
            'status' => Payment::STATUS_APPROVED,
            'no_resit_sistem' => 'RESIT-'.$tahun.'-00001',
        ]);

        $member->refresh();
        $this->assertSame($refs['activeStatus']->id, (int) $member->member_status_id);
        $this->assertSame('AHL-'.str_pad((string) $member->id, 5, '0', STR_PAD_LEFT), (string) $member->no_ahli);

        $payment = Payment::query()
            ->where('member_id', $member->id)
            ->where('no_resit_sistem', 'RESIT-'.$tahun.'-00001')
            ->firstOrFail();

        $this->assertNotNull($payment->bukti_bayaran);

        // Second collect should be blocked because ahli is already active.
        $payload['no_resit_transfer'] = 'RESIT-KUT-002';
        $payload['bukti_bayaran'] = UploadedFile::fake()->image('proof2.jpg');

        $response2 = $this->post(route('admin.kutipan.collect'), $payload, [
            'Accept' => 'application/json',
        ]);

        $response2->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Ahli sudah aktif untuk tahun ini.');
    }
}
