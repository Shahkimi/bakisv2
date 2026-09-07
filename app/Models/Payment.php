<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_WAIVED = 'waived';

    protected $fillable = [
        'member_id',
        'yuran_id',
        'tahun_bayar',
        'tahun_mula',
        'tahun_tamat',
        'no_resit_sistem',
        'bukti_bayaran',
        'status',
        'approved_by',
        'approved_at',
        'catatan_admin',
        'waiver_requested_by',
        'waiver_requested_at',
        'waiver_reason',
        'waived_by',
        'waived_at',
    ];

    protected function casts(): array
    {
        return [
            'tahun_bayar' => 'integer',
            'tahun_mula' => 'integer',
            'tahun_tamat' => 'integer',
            'approved_at' => 'datetime',
            'waiver_requested_at' => 'datetime',
            'waived_at' => 'datetime',
        ];
    }

    public function coversYear(int $year): bool
    {
        return $year >= $this->coverageStart() && $year <= $this->coverageEnd();
    }

    public function coverageStart(): int
    {
        return (int) ($this->tahun_mula ?? $this->tahun_bayar);
    }

    public function coverageEnd(): int
    {
        return (int) ($this->tahun_tamat ?? $this->tahun_bayar);
    }

    public function coverageLabel(): string
    {
        $start = $this->coverageStart();
        $end = $this->coverageEnd();

        return $start === $end ? (string) $start : $start.'–'.$end;
    }

    public function getJumlahAttribute(): float
    {
        return (float) ($this->yuran?->jumlah ?? 0);
    }

    public function getJenisAttribute(): string
    {
        if (! $this->relationLoaded('yuran') && $this->yuran_id) {
            $this->loadMissing('yuran');
        }

        $name = $this->yuran?->jenis_yuran ?? '';

        return match (true) {
            str_contains(strtolower($name), 'pendaftaran') => 'pendaftaran_baru',
            str_contains(strtolower($name), 'pembaharuan') => 'pembaharuan',
            default => $name ?: 'N/A',
        };
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function yuran(): BelongsTo
    {
        return $this->belongsTo(Yuran::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function waiverRequestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waiver_requested_by');
    }

    public function waivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waived_by');
    }

    public function hasPendingWaiverRequest(): bool
    {
        return $this->status === self::STATUS_APPROVED && $this->waiver_requested_at !== null;
    }
}
