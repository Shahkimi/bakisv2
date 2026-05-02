<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class KpPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_by_kp_sends_notification_when_kp_matches(): void
    {
        Notification::fake();

        $kp = '800101145022';
        $user = User::factory()->create([
            'no_kp' => $kp,
            'email' => 'member@test.example',
        ]);

        $response = $this->post(route('password.forgot-by-kp'), [
            'no_kp' => $kp,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('reset_link_notice');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_forgot_by_kp_unknown_kp_still_shows_generic_notice_and_sends_nothing(): void
    {
        Notification::fake();

        $response = $this->post(route('password.forgot-by-kp'), [
            'no_kp' => '990101010101',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('reset_link_notice');

        Notification::assertNothingSent();
    }

    public function test_user_can_reset_password_via_reset_form(): void
    {
        Notification::fake();

        $kp = '900215065088';
        $user = User::factory()->create([
            'no_kp' => $kp,
            'email' => 'reset@test.example',
            'password' => Hash::make('OldPass1!'),
        ]);

        $this->post(route('password.forgot-by-kp'), ['no_kp' => $kp]);

        $token = '';
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token): bool {
            $token = $notification->token;

            return true;
        });

        $newPassword = 'BrandNew1!x';

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');

        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function test_reset_password_form_requires_email_query(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'some-token']));

        $response->assertNotFound();
    }

    public function test_reset_password_form_displays_for_valid_query(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('password.reset', [
            'token' => 'tok-example',
            'email' => $user->email,
        ]));

        $response->assertOk();
        $response->assertSee('Tetapkan semula kata laluan', false);
    }
}
