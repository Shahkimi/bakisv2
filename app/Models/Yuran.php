<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Yuran extends Model
{
    /** @var list<string> */
    public const array SYSTEM_CODES = [
        Member::YURAN_CODE_PENDAFTARAN,
        Member::YURAN_CODE_PEMBAHARUAN,
        Member::YURAN_CODE_PEMBAHARUAN_2_TAHUN,
    ];

    protected $fillable = [
        'jenis_yuran',
        'code',
        'jumlah',
        'tempoh_tahun',
        'is_active',
        'is_show',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tempoh_tahun' => 'integer',
            'is_active' => 'boolean',
            'is_show' => 'boolean',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isSystemDefined(): bool
    {
        return in_array($this->code, self::SYSTEM_CODES, true);
    }

    public static function findByCode(string $code): ?self
    {
        return self::query()->where('code', $code)->first();
    }

    public static function amountForCode(string $code): float
    {
        $yuran = self::findByCode($code);

        return $yuran !== null ? (float) $yuran->jumlah : 0.0;
    }
}
