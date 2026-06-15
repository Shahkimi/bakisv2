<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminKawalanPenggunaUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_set_and_clear_no_kp_on_user(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create([
            'email' => 'member@example.test',
            'no_kp' => null,
        ]);

        $kp = '850101015089';

        $this->actingAs($admin)
            ->putJson(route('admin.kawalan.pengguna.user.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'role' => User::ROLE_USER,
                'no_kp' => $kp,
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $target->refresh();
        $this->assertSame($kp, $target->no_kp);

        $this->actingAs($admin)
            ->putJson(route('admin.kawalan.pengguna.user.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'role' => User::ROLE_USER,
                'no_kp' => '',
            ])
            ->assertOk();

        $target->refresh();
        $this->assertNull($target->no_kp);
    }

    public function test_admin_cannot_assign_duplicate_no_kp(): void
    {
        $admin = User::factory()->admin()->create();
        $existingKp = '900101011234';
        User::factory()->create(['no_kp' => $existingKp]);
        $target = User::factory()->create(['no_kp' => null]);

        $this->actingAs($admin)
            ->putJson(route('admin.kawalan.pengguna.user.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'role' => User::ROLE_USER,
                'no_kp' => $existingKp,
            ])
            ->assertStatus(422);
    }

    public function test_admin_can_disable_and_reenable_user(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create([
            'email' => 'disable-me@example.test',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.pengguna.user.toggle-active', $target))
            ->assertOk()
            ->assertJson([
                'success' => true,
                'is_active' => false,
            ]);

        $target->refresh();
        $this->assertFalse($target->is_active);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.pengguna.user.toggle-active', $target))
            ->assertOk()
            ->assertJson([
                'success' => true,
                'is_active' => true,
            ]);

        $target->refresh();
        $this->assertTrue($target->is_active);
    }

    public function test_admin_cannot_disable_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.pengguna.user.toggle-active', $admin))
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Tidak boleh menyahaktifkan akaun sendiri.',
            ]);

        $admin->refresh();
        $this->assertTrue($admin->is_active);
    }

    public function test_disabled_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'disabled@example.test',
            'is_active' => false,
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertSessionHasErrors('email')
            ->assertRedirect();

        $this->assertGuest();
    }
}
