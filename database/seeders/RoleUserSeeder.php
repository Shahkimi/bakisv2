<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds one panel pengguna account (role {@see User::ROLE_USER}).
 * Run alone: php artisan db:seed --class=RoleUserSeeder
 */
final class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Panel User',
                'no_kp' => '900215065088',
                'password' => $password,
                'role' => User::ROLE_USER,
                'email_verified_at' => now(),
            ]
        );
    }
}
