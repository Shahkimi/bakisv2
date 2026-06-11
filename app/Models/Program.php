<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'nama_program',
        'tarikh',
        'waktu_mula',
        'waktu_tamat',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tarikh' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function isPast(): bool
    {
        return $this->tarikh->isBefore(now()->startOfDay());
    }
}
