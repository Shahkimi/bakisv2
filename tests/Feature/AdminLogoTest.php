<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class AdminLogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_upload_logo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('logo.png', 256, 256);

        $this->actingAs($user)
            ->postJson(route('admin.kawalan.favicon.logo.store'), ['logo' => $file])
            ->assertForbidden();
    }

    public function test_admin_can_upload_logo_and_setting_is_stored(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('logo.png', 256, 256);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.logo.store'), [
                'logo' => $file,
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('settings', [
            'key' => SiteSettingService::LOGO_KEY,
        ]);

        $path = Setting::query()->where('key', SiteSettingService::LOGO_KEY)->value('value');
        $this->assertIsString($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_remove_logo(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('logo.png', 256, 256);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.logo.store'), ['logo' => $file])
            ->assertOk();

        $path = Setting::query()->where('key', SiteSettingService::LOGO_KEY)->value('value');
        $this->assertIsString($path);

        $this->actingAs($admin)
            ->deleteJson(route('admin.kawalan.favicon.logo.destroy'))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('settings', [
            'key' => SiteSettingService::LOGO_KEY,
        ]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_logo_upload_rejects_ico_mime(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->create('logo.ico', 100, 'image/x-icon');

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.logo.store'), ['logo' => $file])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['logo']);
    }

    public function test_logo_upload_rejects_oversized_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('logo.png', 256, 256)->size(3000);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.logo.store'), ['logo' => $file])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['logo']);
    }
}
