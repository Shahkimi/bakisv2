<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UserKutipanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_user_kutipan_index(): void
    {
        $this->get(route('user.kutipan.index'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_can_view_user_kutipan_index(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $this->actingAs($user)
            ->get(route('user.kutipan.index'))
            ->assertOk();
    }

    public function test_regular_user_cannot_access_admin_kutipan_index(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $this->actingAs($user)
            ->get(route('admin.kutipan.index'))
            ->assertForbidden();
    }

    public function test_admin_cannot_access_user_kutipan_index(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('user.kutipan.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_guest_cannot_access_user_kutipan_autocomplete(): void
    {
        $this->get(route('user.kutipan.autocomplete', ['search' => '900']))
            ->assertRedirect(route('login'));
    }
}
