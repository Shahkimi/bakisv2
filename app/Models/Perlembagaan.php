<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Perlembagaan extends Model
{
    protected $fillable = [
        'tajuk',
        'file_path',
        'file_name',
        'file_size',
        'is_active',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'file_size' => 'integer',
        ];
    }

    /**
     * Public URL to the stored PDF, or null when the file is missing.
     */
    public function publicUrl(): ?string
    {
        if ($this->file_path === null || $this->file_path === '' || ! Storage::disk('public')->exists($this->file_path)) {
            return null;
        }

        $version = (string) ($this->updated_at?->getTimestamp() ?? 0);

        return asset('storage/'.$this->file_path).'?v='.$version;
    }

    /**
     * Human-readable file size, e.g. "1.2 MB".
     */
    public function humanFileSize(): string
    {
        $bytes = (int) ($this->file_size ?? 0);
        if ($bytes <= 0) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);
        $value = $bytes / (1024 ** $power);

        return ($power === 0 ? (string) $value : number_format($value, 1)).' '.$units[$power];
    }
}
