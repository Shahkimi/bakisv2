<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    /**
     * Number of grace years granted after a payment's `tahun_tamat`.
     * A member is considered Aktif while current_year <= tahun_tamat + GRACE_YEARS.
     */
    public const int GRACE_YEARS = 1;

    public const string YURAN_CODE_PENDAFTARAN = 'pendaftaran_keahlian';

    public const string YURAN_CODE_PEMBAHARUAN = 'pembaharuan_tahunan';

    public const string YURAN_CODE_PEMBAHARUAN_2_TAHUN = 'pembaharuan_2_tahun';

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
        $code = $this->isWithinActiveGrace()
            ? self::YURAN_CODE_PEMBAHARUAN
            : self::YURAN_CODE_PENDAFTARAN;

        return Yuran::amountForCode($code);
    }

    public function isAktifThisYear(): bool
    {
        $currentYear = (int) date('Y');
        $graceThreshold = $currentYear - self::GRACE_YEARS;

        return $this->payments()
            ->where('status', 'approved')
            ->where('tahun_mula', '<=', $currentYear)
            ->where(function ($q) use ($graceThreshold) {
                $q->whereNull('tahun_tamat')
                    ->orWhere('tahun_tamat', '>=', $graceThreshold);
            })
            ->exists();
    }

    /**
     * Readable alias for {@see isAktifThisYear()} — true while any approved
     * payment's coverage (plus grace year) still includes the current year.
     */
    public function isWithinActiveGrace(): bool
    {
        return $this->isAktifThisYear();
    }

    /**
     * Relation constraints for `withExists` / `loadExists` alias `aktif_this_year`
     * (approved payment covering the current calendar year).
     *
     * @return array<string, \Closure(Builder): void>
     */
    public static function aktifThisYearExistsDefinition(): array
    {
        $year = (int) date('Y');
        $graceThreshold = $year - self::GRACE_YEARS;

        return [
            'payments as aktif_this_year' => function (Builder $q) use ($year, $graceThreshold): void {
                $q->where('status', 'approved')
                    ->where('tahun_mula', '<=', $year)
                    ->where(function (Builder $q2) use ($graceThreshold): void {
                        $q2->whereNull('tahun_tamat')
                            ->orWhere('tahun_tamat', '>=', $graceThreshold);
                    });
            },
        ];
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithAktifThisYearExists(Builder $query): void
    {
        $query->withExists(self::aktifThisYearExistsDefinition());
    }

    /**
     * Status label/code for member lists and summary badges: Aktif only when there is an
     * approved payment covering the current calendar year (unless DB status is meninggal or pending).
     *
     * @param  bool|null  $hasVerifiedPaymentThisYear  From eager `withExists` when set; otherwise uses {@see isAktifThisYear()}.
     * @return array{0: string, 1: string|null}
     */
    public function listStatusDisplayForCurrentYear(?bool $hasVerifiedPaymentThisYear = null): array
    {
        $code = $this->memberStatus?->code;
        $name = $this->memberStatus?->name;

        if (in_array($code, ['meninggal', 'pending'], true)) {
            return [$name ?? '—', $code];
        }

        $aktif = $hasVerifiedPaymentThisYear ?? $this->isAktifThisYear();

        if ($aktif) {
            return ['Aktif', 'aktif'];
        }

        return ['Tidak Aktif', 'tidak_aktif'];
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
