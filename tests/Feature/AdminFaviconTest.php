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

final class AdminFaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_favicon_index(): void
    {
        $this->get(route('admin.kawalan.favicon.index'))->assertRedirect();
    }

    public function test_non_admin_cannot_access_favicon_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.kawalan.favicon.index'))->assertForbidden();
    }

    public function test_admin_can_view_favicon_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.kawalan.favicon.index'))
            ->assertOk()
            ->assertSee('Favicon', false);
    }

    public function test_admin_can_upload_favicon_and_setting_is_stored(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('favicon.png', 32, 32);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.store'), [
                'favicon' => $file,
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('settings', [
            'key' => SiteSettingService::FAVICON_KEY,
        ]);

        $path = Setting::query()->where('key', SiteSettingService::FAVICON_KEY)->value('value');
        $this->assertIsString($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_remove_favicon(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('favicon.png', 32, 32);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.store'), ['favicon' => $file])
            ->assertOk();

        $path = Setting::query()->where('key', SiteSettingService::FAVICON_KEY)->value('value');
        $this->assertIsString($path);

        $this->actingAs($admin)
            ->deleteJson(route('admin.kawalan.favicon.destroy'))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('settings', [
            'key' => SiteSettingService::FAVICON_KEY,
        ]);
        Storage::disk('public')->assertMissing($path);
    }
}
