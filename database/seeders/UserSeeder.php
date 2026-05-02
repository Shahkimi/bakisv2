<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds application users (admin and regular accounts for development).
 * Do not rely on these credentials in production.
 */
final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'no_kp' => '800101145022',
                'password' => $password,
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'no_kp' => '900215065088',
                'password' => $password,
                'role' => User::ROLE_USER,
                'email_verified_at' => now(),
            ]
        );
    }
}
