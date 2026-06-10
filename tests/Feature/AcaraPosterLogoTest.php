<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Acara;
use App\Models\User;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

final class AcaraPosterLogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_logo_base64_data_uri_returns_encoded_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('logo.png', 256, 256);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.logo.store'), ['logo' => $file])
            ->assertOk();

        $dataUri = app(SiteSettingService::class)->logoBase64DataUri();

        $this->assertIsString($dataUri);
        $this->assertStringStartsWith('data:image/', $dataUri);
        $this->assertStringContainsString(';base64,', $dataUri);
    }

    public function test_logo_base64_data_uri_returns_null_when_missing(): void
    {
        Storage::fake('public');

        $this->assertNull(app(SiteSettingService::class)->logoBase64DataUri());
    }

    public function test_poster_view_shows_logo_when_present(): void
    {
        $acara = $this->makeAcara();
        $html = View::make('admin.acara.poster-pdf', [
            'acara' => $acara,
            'qrBase64' => base64_encode('<svg></svg>'),
            'logoDataUri' => 'data:image/png;base64,abc123',
        ])->render();

        $this->assertStringContainsString('class="org-logo"', $html);
        $this->assertStringContainsString('data:image/png;base64,abc123', $html);
        $this->assertStringNotContainsString('event-date-box', $html);
        $this->assertStringNotContainsString('Sah Sehingga', $html);
    }

    public function test_poster_view_omits_logo_when_missing(): void
    {
        $acara = $this->makeAcara();
        $html = View::make('admin.acara.poster-pdf', [
            'acara' => $acara,
            'qrBase64' => base64_encode('<svg></svg>'),
            'logoDataUri' => null,
        ])->render();

        $this->assertStringNotContainsString('class="org-logo"', $html);
        $this->assertStringNotContainsString('Sah Sehingga', $html);
    }

    public function test_logo_upload_clears_poster_pdf_cache(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $acara = $this->makeAcara();
        $cacheKey = 'acara_poster_pdf_'.$acara->id;
        Cache::put($cacheKey, 'cached-poster', now()->addHour());

        $file = UploadedFile::fake()->image('logo.png', 256, 256);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.logo.store'), ['logo' => $file])
            ->assertOk();

        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_logo_delete_clears_poster_pdf_cache(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $acara = $this->makeAcara();
        $cacheKey = 'acara_poster_pdf_'.$acara->id;
        Cache::put($cacheKey, 'cached-poster', now()->addHour());

        $file = UploadedFile::fake()->image('logo.png', 256, 256);

        $this->actingAs($admin)
            ->postJson(route('admin.kawalan.favicon.logo.store'), ['logo' => $file])
            ->assertOk();

        Cache::put($cacheKey, 'cached-poster', now()->addHour());

        $this->actingAs($admin)
            ->deleteJson(route('admin.kawalan.favicon.logo.destroy'))
            ->assertOk();

        $this->assertFalse(Cache::has($cacheKey));
        $this->assertDatabaseMissing('settings', [
            'key' => SiteSettingService::LOGO_KEY,
        ]);
    }

    private function makeAcara(): Acara
    {
        return Acara::query()->create([
            'nama_acara' => 'Test Acara',
            'lokasi' => 'Auditorium HSB',
            'waktu' => '8:00 am',
            'code' => 'testcode01',
            'expires_at' => now()->addDay(),
            'is_active' => true,
        ]);
    }
}
