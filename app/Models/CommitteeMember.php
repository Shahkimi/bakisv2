<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CommitteeMember extends Model
{
    protected $fillable = [
        'name',
        'jawatan',
        'photo_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function photoUrl(): ?string
    {
        $path = $this->photo_path;
        if (! is_string($path) || $path === '' || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $version = (string) ($this->updated_at?->getTimestamp() ?? 0);

        return asset('storage/'.$path).'?v='.$version;
    }
}
