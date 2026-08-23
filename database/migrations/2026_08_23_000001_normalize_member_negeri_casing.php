<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Legacy imports stored `members.negeri` in uppercase (e.g. "KEDAH"), which no
     * longer matches the canonical option values used by the edit forms. Normalize
     * every stored value to its canonical casing so exact matching works.
     */
    public function up(): void
    {
        $states = [
            'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang',
            'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor',
            'Terengganu', 'Wilayah Persekutuan Kuala Lumpur',
            'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya',
        ];

        foreach ($states as $state) {
            // Note: no `negeri != $state` guard — MySQL's ci collation would treat
            // "KEDAH" as equal to "Kedah" and skip every row.
            DB::table('members')
                ->whereRaw('UPPER(negeri) = ?', [mb_strtoupper($state)])
                ->update(['negeri' => $state]);
        }
    }

    public function down(): void
    {
        // The original legacy casing is not restored on rollback.
    }
};
