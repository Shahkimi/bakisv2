<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Jawatan;
use App\Models\Member;
use App\Models\MemberStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Connection;
use Illuminate\Database\Seeder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class MigrateOldDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MemberStatusSeeder::class);

        if (! config('database.connections.old_database.database')) {
            $this->command->warn('Old database not configured. Set OLD_DB_* in .env to run migration.');

            return;
        }

        $oldDB = DB::connection('old_database');

        $this->importJabatans($oldDB);
        $this->importJawatans($oldDB);
        $this->importMembers($oldDB);
        $this->importPayments($oldDB);
    }

    private function importJabatans(Connection $oldDB): void
    {
        $rows = $oldDB->table('tbjabatan')->get();
        foreach ($rows as $row) {
            Jabatan::firstOrCreate(
                ['nama_jabatan' => $row->jabatan ?? 'Lain-lain'],
                ['is_active' => true]
            );
        }
    }

    private function importJawatans(Connection $oldDB): void
    {
        $rows = $oldDB->table('tbjawatan')->get();
        foreach ($rows as $row) {
            Jawatan::firstOrCreate(
                ['kod_jawatan' => $row->kodjawatan ?? (string) $row->id],
                [
                    'nama_jawatan' => $row->jawatan ?? 'Lain-lain',
                    'is_active' => true,
                ]
            );
        }
    }

    private function importMembers(Connection $oldDB): void
    {
        $pendingStatus = MemberStatus::where('code', 'pending')->first()
            ?? MemberStatus::where('code', 'tidak_aktif')->first();

        $rows = $oldDB->table('tbpersonal')->get();
        foreach ($rows as $row) {
            $jabatan = Jabatan::where('nama_jabatan', $row->jabatan)->first()
                ?? Jabatan::first();
            $jawatan = Jawatan::where('kod_jawatan', $row->jawatan)
                ->orWhere('nama_jawatan', $row->jawatan)
                ->first() ?? Jawatan::first();

            if (! $jabatan || ! $jawatan || ! $pendingStatus) {
                continue;
            }

            $statusMap = [
                'Aktif' => 'aktif',
                'Tidak Aktif' => 'tidak_aktif',
                'Meninggal' => 'meninggal',
            ];
            $code = $statusMap[$row->status ?? ''] ?? 'tidak_aktif';
            $memberStatus = MemberStatus::where('code', $code)->first() ?? $pendingStatus;

            $noKp = preg_replace('/\D/', '', (string) ($row->nokp ?? ''));
            if (strlen($noKp) !== 12) {
                continue;
            }

            $tarikhDaftar = $row->tdaftar
                ? date('Y-m-d', strtotime($row->tdaftar))
                : now()->toDateString();

            $noAhli = $row->noahli !== null && $row->noahli !== '' ? trim((string) $row->noahli) : null;
            if ($noAhli !== null && Member::where('no_ahli', $noAhli)->where('no_kp', '!=', $noKp)->exists()) {
                $noAhli = null;
            }

            try {
                Member::updateOrCreate(
                    ['no_kp' => $noKp],
                    [
                        'no_ahli' => $noAhli,
                        'jabatan_id' => $jabatan->id,
                        'jawatan_id' => $jawatan->id,
                        'member_status_id' => $memberStatus->id,
                        'nama' => $row->nama ?? 'Unknown',
                        'email' => null,
                        'jantina' => in_array($row->jantina ?? '', ['L', 'P'], true) ? $row->jantina : 'L',
                        'alamat1' => $row->alamat1 ?? null,
                        'alamat2' => $row->alamat2 ?? null,
                        'poskod' => $row->poskod ?? null,
                        'bandar' => $row->bandar ?? null,
                        'negeri' => $row->negeri ?? null,
                        'no_tel' => $row->notel ?? null,
                        'no_hp' => $row->hphone ?? null,
                        'gambar' => $row->gambar ?? null,
                        'catatan' => $row->catatan ?? null,
                        'tarikh_daftar' => $tarikhDaftar,
                    ]
                );
            } catch (UniqueConstraintViolationException $e) {
                if (str_contains($e->getMessage(), 'no_ahli')) {
                    $this->command->warn("Skipping duplicate no_ahli for no_kp {$noKp}, using null.");
                    Member::updateOrCreate(
                        ['no_kp' => $noKp],
                        [
                            'no_ahli' => null,
                            'jabatan_id' => $jabatan->id,
                            'jawatan_id' => $jawatan->id,
                            'member_status_id' => $memberStatus->id,
                            'nama' => $row->nama ?? 'Unknown',
                            'email' => null,
                            'jantina' => in_array($row->jantina ?? '', ['L', 'P'], true) ? $row->jantina : 'L',
                            'alamat1' => $row->alamat1 ?? null,
                            'alamat2' => $row->alamat2 ?? null,
                            'poskod' => $row->poskod ?? null,
                            'bandar' => $row->bandar ?? null,
                            'negeri' => $row->negeri ?? null,
                            'no_tel' => $row->notel ?? null,
                            'no_hp' => $row->hphone ?? null,
                            'gambar' => $row->gambar ?? null,
                            'catatan' => $row->catatan ?? null,
                            'tarikh_daftar' => $tarikhDaftar,
                        ]
                    );
                } else {
                    throw $e;
                }
            }
        }
    }

    private function importPayments(Connection $oldDB): void
    {
        $rows = $oldDB->table('tbakaun')->get();
        $firstUser = User::query()->orderBy('id')->first();
        $approvedBy = $firstUser?->id;
        $approvedAt = $approvedBy ? now() : null;
        $now = now();

        foreach ($rows as $row) {
            $personal = $oldDB->table('tbpersonal')->where('ID_personel', $row->id_personel)->first();
            if (! $personal || empty($personal->nokp)) {
                continue;
            }

            $noKp = preg_replace('/\D/', '', (string) $personal->nokp);
            $member = Member::where('no_kp', $noKp)->first();
            if (! $member) {
                continue;
            }

            $amount = (float) ($row->RM ?? 10);
            $jenis = $amount === 12.00 ? 'pendaftaran_baru' : 'pembaharuan';

            DB::table('payments')->insert([
                'member_id' => $member->id,
                'tahun_bayar' => (int) ($row->thnbayar ?? date('Y')),
                'jumlah' => $amount,
                'jenis' => $jenis,
                'no_resit_sistem' => $row->noresit ?? null,
                'status' => Payment::STATUS_APPROVED,
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
