<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Services\MailSettingService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class AdminMailSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_guest_cannot_access_mail_settings(): void
    {
        $this->get(route('admin.kawalan.emel.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_role_cannot_access_mail_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.kawalan.emel.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_mail_settings_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.kawalan.emel.index'))
            ->assertOk()
            ->assertViewIs('admin.kawalan.emel');
    }

    public function test_admin_can_update_settings_and_password_is_encrypted_at_rest(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.emel.update'), [
                'is_enabled' => '1',
                'host' => 'postmaster.mygovuc.gov.my',
                'port' => '587',
                'scheme' => 'auto',
                'username' => 'noreply-kdh@moh.gov.my',
                'password' => 'super-secret-relay-password',
                'from_address' => 'noreply-kdh@moh.gov.my',
                'from_name' => 'BAKIS',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'enabled' => true,
                'host' => 'postmaster.mygovuc.gov.my',
                'port' => 587,
                'has_password' => true,
            ]);

        $stored = Setting::query()->where('key', MailSettingService::PASSWORD_KEY)->value('value');
        $this->assertNotNull($stored);
        $this->assertStringNotContainsString('super-secret-relay-password', (string) $stored);

        $service = app(MailSettingService::class);
        $this->assertTrue($service->hasPassword());
        $this->assertSame('postmaster.mygovuc.gov.my', $service->host());
        $this->assertSame(587, $service->port());
    }

    public function test_blank_password_on_update_keeps_previously_stored_password(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->postJson(route('admin.kawalan.emel.update'), [
            'is_enabled' => '0',
            'password' => 'first-password',
        ])->assertOk();

        $firstStored = Setting::query()->where('key', MailSettingService::PASSWORD_KEY)->value('value');

        $this->actingAs($admin)->postJson(route('admin.kawalan.emel.update'), [
            'is_enabled' => '0',
            'host' => 'relay.example.test',
            'password' => '',
        ])->assertOk();

        $secondStored = Setting::query()->where('key', MailSettingService::PASSWORD_KEY)->value('value');

        $this->assertSame($firstStored, $secondStored);
        $this->assertSame('relay.example.test', app(MailSettingService::class)->host());
    }

    public function test_enabling_requires_host_port_and_from_address(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.emel.update'), ['is_enabled' => '1'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['host', 'port', 'from_address']);
    }

    public function test_disabled_mail_suppresses_delivery(): void
    {
        app(MailSettingService::class)->update([
            'is_enabled' => false,
            'host' => null, 'port' => null, 'scheme' => null, 'username' => null,
            'password' => null, 'from_address' => null, 'from_name' => null,
        ]);

        $transport = Mail::mailer('array')->getSymfonyTransport();
        $transport->flush();

        Mail::raw('Isi kandungan ujian.', function ($message): void {
            $message->to('ahli@example.test')->subject('Ujian');
        });

        $this->assertCount(0, $transport->messages());
    }

    public function test_enabled_mail_allows_delivery(): void
    {
        app(MailSettingService::class)->update([
            'is_enabled' => true,
            'host' => null, 'port' => null, 'scheme' => null, 'username' => null,
            'password' => null, 'from_address' => null, 'from_name' => null,
        ]);

        $transport = Mail::mailer('array')->getSymfonyTransport();
        $transport->flush();

        Mail::raw('Isi kandungan ujian.', function ($message): void {
            $message->to('ahli@example.test')->subject('Ujian');
        });

        $this->assertCount(1, $transport->messages());
    }

    public function test_test_email_endpoint_validates_address(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.emel.test'), ['test_email' => 'not-an-email'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['test_email']);
    }

    public function test_test_email_endpoint_sends_even_while_disabled(): void
    {
        app(MailSettingService::class)->update([
            'is_enabled' => false,
            'host' => null, 'port' => null, 'scheme' => null, 'username' => null,
            'password' => null, 'from_address' => null, 'from_name' => null,
        ]);

        $admin = User::factory()->admin()->create();

        $transport = Mail::mailer('array')->getSymfonyTransport();
        $transport->flush();

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.emel.test'), ['test_email' => 'ahli@example.test'])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertCount(1, $transport->messages());
    }
}
