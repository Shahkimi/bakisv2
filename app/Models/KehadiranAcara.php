<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KehadiranAcara extends Model
{
    protected $table = 'kehadiran_acaras';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'acara_id',
        'member_id',
        'no_kp',
        'nama',
        'attended_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attended_at' => 'datetime',
        ];
    }

    public function acara(): BelongsTo
    {
        return $this->belongsTo(Acara::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
