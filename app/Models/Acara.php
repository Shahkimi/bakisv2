<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Acara extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_acara',
        'lokasi',
        'tarikh',
        'waktu_mula',
        'waktu_tamat',
        'code',
        'expires_at',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tarikh'     => 'date',
            'expires_at' => 'datetime',
            'is_active'  => 'boolean',
        ];
    }

    public function kehadirans(): HasMany
    {
        return $this->hasMany(KehadiranAcara::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Attendance is open only while the event is active and not past its expiry.
     */
    public function isOpen(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    /**
     * Public-facing attendance URL, e.g. https://domain/kuzne5u
     */
    public function publicUrl(): string
    {
        return url('/'.$this->code);
    }

    /**
     * Generate a unique lowercase alphanumeric code (e.g. "kuzne5u").
     */
    public static function generateUniqueCode(int $length = 7): string
    {
        do {
            $code = Str::lower(Str::random($length));
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }
}
