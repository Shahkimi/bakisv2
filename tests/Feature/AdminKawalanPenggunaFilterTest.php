<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class AdminKawalanPenggunaFilterTest extends TestCase
{
    use RefreshDatabase;

    private function fetchTable(User $admin, string $filter = 'all'): array
    {
        $response = $this->actingAs($admin)
            ->getJson(route('admin.kawalan.pengguna.data').'?'.http_build_query([
                'draw' => 1,
                'start' => 0,
                'length' => 50,
                'filter' => $filter,
            ]), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk();

        return $response->json();
    }

    public function test_summary_endpoint_returns_expected_counts(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->admin()->create();
        User::factory()->create();
        User::factory()->create(['is_active' => false]);

        UserInvitation::create([
            'name' => 'Pending Invite',
            'email' => 'pending@example.test',
            'token' => Str::random(64),
            'role' => User::ROLE_USER,
            'expires_at' => now()->addHours(24),
        ]);
        UserInvitation::create([
            'name' => 'Expired Invite',
            'email' => 'expired@example.test',
            'token' => Str::random(64),
            'role' => User::ROLE_USER,
            'expires_at' => now()->subHour(),
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.kawalan.pengguna.summary'))
            ->assertOk()
            ->assertJson([
                'totalUsers' => 4,
                'totalAdmins' => 2,
                'totalInactive' => 1,
                'pendingInvitations' => 1,
            ]);
    }

    public function test_admin_filter_includes_admin_users_and_admin_invitations_only(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create();

        UserInvitation::create([
            'name' => 'Admin Invite',
            'email' => 'admin-invite@example.test',
            'token' => Str::random(64),
            'role' => User::ROLE_ADMIN,
            'expires_at' => now()->addHours(24),
        ]);
        UserInvitation::create([
            'name' => 'User Invite',
            'email' => 'user-invite@example.test',
            'token' => Str::random(64),
            'role' => User::ROLE_USER,
            'expires_at' => now()->addHours(24),
        ]);

        $data = $this->fetchTable($admin, 'admin');

        $this->assertSame(2, $data['recordsFiltered']);
        $names = array_column($data['data'], 'name');
        $this->assertContains($admin->name, $names);
        $this->assertContains('Admin Invite', $names);
        $this->assertNotContains('User Invite', $names);
    }

    public function test_invitations_filter_excludes_all_users(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create();

        UserInvitation::create([
            'name' => 'Some Invite',
            'email' => 'some-invite@example.test',
            'token' => Str::random(64),
            'role' => User::ROLE_USER,
            'expires_at' => now()->addHours(24),
        ]);

        $data = $this->fetchTable($admin, 'invitations');

        $this->assertSame(1, $data['recordsFiltered']);
        $this->assertSame('invitation', $data['data'][0]['row_type']);
    }

    public function test_inactive_filter_excludes_all_invitations(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['is_active' => false]);

        UserInvitation::create([
            'name' => 'Some Invite',
            'email' => 'some-invite2@example.test',
            'token' => Str::random(64),
            'role' => User::ROLE_USER,
            'expires_at' => now()->addHours(24),
        ]);

        $data = $this->fetchTable($admin, 'inactive');

        $this->assertSame(1, $data['recordsFiltered']);
        $this->assertSame('user', $data['data'][0]['row_type']);
    }

    public function test_unknown_filter_value_behaves_like_all(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create();

        $data = $this->fetchTable($admin, 'not-a-real-filter');

        $this->assertSame(2, $data['recordsFiltered']);
    }
}
