<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserInvitation;
use App\Notifications\UserCredentialsNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class AdminKawalanPenggunaMailDisabledTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'log']);
    }

    public function test_invite_mode_is_blocked_when_mail_not_operational(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.pengguna.store'), [
                'name' => 'Pengguna Baharu',
                'email' => 'baharu@example.test',
                'role' => User::ROLE_USER,
                'mode' => 'invite',
            ])
            ->assertStatus(422)
            ->assertJson(['success' => false]);

        $this->assertDatabaseCount('user_invitations', 0);
        Notification::assertNothingSent();
    }

    public function test_resend_invitation_is_blocked_when_mail_not_operational(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $invitation = UserInvitation::create([
            'name' => 'Pengguna Jemputan',
            'email' => 'jemputan@example.test',
            'token' => 'original-token',
            'role' => User::ROLE_USER,
            'expires_at' => now()->addHours(24),
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.pengguna.invitation.resend', $invitation))
            ->assertStatus(422)
            ->assertJson(['success' => false]);

        $this->assertSame('original-token', $invitation->fresh()->token);
        Notification::assertNothingSent();
    }

    public function test_direct_user_creation_still_works_and_skips_email(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.kawalan.pengguna.store'), [
                'name' => 'Pengguna Terus',
                'email' => 'terus@example.test',
                'role' => User::ROLE_USER,
                'mode' => 'direct',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $response->assertJsonStructure(['temp_password', 'email']);
        $this->assertDatabaseHas('users', ['email' => 'terus@example.test']);
        Notification::assertNothingSent();
    }

    public function test_admin_password_reset_still_works_and_skips_email(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $target = User::factory()->create(['email' => 'reset-target@example.test']);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.kawalan.pengguna.user.reset-password', $target))
            ->assertOk()
            ->assertJson(['success' => true]);

        $response->assertJsonStructure(['temp_password', 'email']);
        Notification::assertNothingSent();
        Notification::assertNotSentTo($target, UserCredentialsNotification::class);
    }

    public function test_create_user_dialog_defaults_to_direct_mode_when_mail_not_operational(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.kawalan.pengguna.index'))
            ->assertOk()
            ->assertSee('penggunaMailOperational = false', false);
    }
}
