<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no_ahli',
        'jabatan_id',
        'jawatan_id',
        'member_status_id',
        'nama',
        'no_kp',
        'email',
        'jantina',
        'alamat1',
        'alamat2',
        'poskod',
        'bandar',
        'negeri',
        'no_tel',
        'no_hp',
        'gambar',
        'catatan',
        'tarikh_daftar',
    ];

    protected function casts(): array
    {
        return [
            'tarikh_daftar' => 'date',
        ];
    }

    public function getNamaAttribute(?string $value): string
    {
        return mb_strtoupper($value ?? '', 'UTF-8');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function jawatan(): BelongsTo
    {
        return $this->belongsTo(Jawatan::class);
    }

    public function memberStatus(): BelongsTo
    {
        return $this->belongsTo(MemberStatus::class, 'member_status_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getMembershipFee(): float
    {
        return $this->payments()->where('status', 'approved')->exists()
            ? 10.00
            : 12.00;
    }

    public function isAktifThisYear(): bool
    {
        $currentYear = (int) date('Y');

        return $this->payments()
            ->where('status', 'approved')
            ->where('tahun_mula', '<=', $currentYear)
            ->where(function ($q) use ($currentYear) {
                $q->whereNull('tahun_tamat')
                    ->orWhere('tahun_tamat', '>=', $currentYear);
            })
            ->exists();
    }

    protected static function booted(): void
    {
        static::saving(function (Member $member): void {
            if (isset($member->attributes['no_kp'])) {
                $normalized = preg_replace('/\D/', '', (string) $member->attributes['no_kp']);
                $member->attributes['no_kp'] = $normalized !== '' ? $normalized : $member->attributes['no_kp'];
            }
            if (isset($member->attributes['email']) && $member->attributes['email'] !== null && $member->attributes['email'] !== '') {
                $member->attributes['email'] = strtolower(trim((string) $member->attributes['email']));
            }
        });
    }
}
