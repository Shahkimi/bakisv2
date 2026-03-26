<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Yuran extends Model
{
    protected $fillable = [
        'jenis_yuran',
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
}
