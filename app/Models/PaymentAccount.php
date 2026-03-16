<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PaymentAccount extends Model
{
    protected $fillable = [
        'account_name',
        'account_number',
        'qr_image_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: only active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Temporary signed URL for the QR image (for use in semak views only).
     * Expires in 60 minutes. Returns null if no qr_image_path.
     */
    public function getTemporaryQrUrl(int $expiresMinutes = 60): ?string
    {
        if (empty($this->qr_image_path)) {
            return null;
        }

        return URL::temporarySignedRoute(
            'semak.qr',
            now()->addMinutes($expiresMinutes),
            ['paymentAccount' => $this->id]
        );
    }

    /**
     * Check if the QR file exists on disk.
     */
    public function hasQrImage(): bool
    {
        if (empty($this->qr_image_path)) {
            return false;
        }

        return Storage::disk('local')->exists($this->qr_image_path);
    }
}
