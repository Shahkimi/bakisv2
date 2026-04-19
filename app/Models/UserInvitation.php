<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    public const ROLE_USER = 0;

    public const ROLE_ADMIN = 1;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'token',
        'role',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
