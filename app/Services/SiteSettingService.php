<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

final readonly class SiteSettingService
{
    public const string FAVICON_KEY = 'site.favicon';

    public const string LOGO_KEY = 'site.logo';

    private const string CACHE_PREFIX = 'site_setting';

    public function faviconPublicUrl(): ?string
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.self::FAVICON_KEY,
            3600,
            function (): ?string {
                /** @var Setting|null $row */
                $row = Setting::query()->where('key', self::FAVICON_KEY)->first();
                $path = $row?->value;
                if (! is_string($path) || $path === '' || ! Storage::disk('public')->exists($path)) {
                    return null;
                }

                $version = (string) ($row->updated_at?->getTimestamp() ?? 0);

                return asset('storage/'.$path).'?v='.$version;
            }
        );
    }

    public function storeFavicon(UploadedFile $file): string
    {
        $this->forgetFaviconCache();
        $this->deleteStoredFaviconFile();

        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension === '') {
            $extension = match ($file->getMimeType()) {
                'image/jpeg', 'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/x-icon', 'image/vnd.microsoft.icon' => 'ico',
                default => 'png',
            };
        }

        $path = $file->storeAs('branding', 'favicon.'.$extension, 'public');
        if ($path === false) {
            throw new \RuntimeException('Failed to store favicon.');
        }

        Setting::query()->updateOrCreate(
            ['key' => self::FAVICON_KEY],
            ['value' => $path],
        );

        $this->forgetFaviconCache();

        return $path;
    }

    public function clearFavicon(): void
    {
        $this->forgetFaviconCache();
        $this->deleteStoredFaviconFile();
        Setting::query()->where('key', self::FAVICON_KEY)->delete();
        $this->forgetFaviconCache();
    }

    public function logoPublicUrl(): ?string
    {
        return Cache::remember(
            self::CACHE_PREFIX.'.'.self::LOGO_KEY,
            3600,
            function (): ?string {
                /** @var Setting|null $row */
                $row = Setting::query()->where('key', self::LOGO_KEY)->first();
                $path = $row?->value;
                if (! is_string($path) || $path === '' || ! Storage::disk('public')->exists($path)) {
                    return null;
                }

                $version = (string) ($row->updated_at?->getTimestamp() ?? 0);

                return asset('storage/'.$path).'?v='.$version;
            }
        );
    }

    public function storeLogo(UploadedFile $file): string
    {
        $this->forgetLogoCache();
        $this->deleteStoredLogoFile();

        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension === '') {
            $extension = match ($file->getMimeType()) {
                'image/jpeg', 'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                default => 'png',
            };
        }

        $path = $file->storeAs('branding', 'logo.'.$extension, 'public');
        if ($path === false) {
            throw new \RuntimeException('Failed to store organization logo.');
        }

        Setting::query()->updateOrCreate(
            ['key' => self::LOGO_KEY],
            ['value' => $path],
        );

        $this->forgetLogoCache();

        return $path;
    }

    public function clearLogo(): void
    {
        $this->forgetLogoCache();
        $this->deleteStoredLogoFile();
        Setting::query()->where('key', self::LOGO_KEY)->delete();
        $this->forgetLogoCache();
    }

    private function deleteStoredFaviconFile(): void
    {
        $path = Setting::query()->where('key', self::FAVICON_KEY)->value('value');
        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function forgetFaviconCache(): void
    {
        Cache::forget(self::CACHE_PREFIX.'.'.self::FAVICON_KEY);
    }

    private function deleteStoredLogoFile(): void
    {
        $path = Setting::query()->where('key', self::LOGO_KEY)->value('value');
        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function forgetLogoCache(): void
    {
        Cache::forget(self::CACHE_PREFIX.'.'.self::LOGO_KEY);
    }
}
