<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Program extends Model
{
    protected $fillable = [
        'nama_program',
        'tarikh',
        'waktu_mula',
        'waktu_tamat',
        'is_active',
        'acara_id',
    ];

    protected function casts(): array
    {
        return [
            'tarikh' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function acara(): BelongsTo
    {
        return $this->belongsTo(Acara::class);
    }

    public function hasKehadiran(): bool
    {
        return $this->acara_id !== null;
    }

    public function isPast(): bool
    {
        return $this->tarikh->isBefore(now()->startOfDay());
    }
}
