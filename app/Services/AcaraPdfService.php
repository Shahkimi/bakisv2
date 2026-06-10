<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Acara;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

final readonly class AcaraPdfService
{
    public function __construct(
        private SiteSettingService $siteSettingService,
    ) {}

    /**
     * A4 event poster carrying the event details and a QR code to the public link.
     * Result is cached — repeat downloads return instantly.
     *
     * @return array{content: string, filename: string}
     */
    public function renderPoster(Acara $acara): array
    {
        $cacheKey = self::posterCacheKey($acara->id);

        $encoded = Cache::remember($cacheKey, now()->addHours(24), function () use ($acara): string {
            $qrBase64 = $this->qrBase64($acara);

            $output = Pdf::loadView('admin.acara.poster-pdf', [
                'acara' => $acara,
                'qrBase64' => $qrBase64,
                'logoDataUri' => $this->siteSettingService->logoBase64DataUri(),
            ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 96,
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                    'isFontSubsettingEnabled' => false,
                ])
                ->output();

            return base64_encode($output);
        });

        $content = base64_decode($encoded);

        return [
            'content' => $content,
            'filename' => sprintf('poster-acara-%s.pdf', $this->slug($acara)),
        ];
    }

    /**
     * A4 attendance report listing everyone who checked in.
     *
     * @return array{content: string, filename: string}
     */
    public function renderAttendance(Acara $acara): array
    {
        $kehadirans = $acara->kehadirans()
            ->orderBy('attended_at')
            ->get();

        $pdf = Pdf::loadView('admin.acara.kehadiran-pdf', [
            'acara' => $acara,
            'kehadirans' => $kehadirans,
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 96,
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => false,
            ]);

        return [
            'content' => $pdf->output(),
            'filename' => sprintf('kehadiran-acara-%s.pdf', $this->slug($acara)),
        ];
    }

    /**
     * Bust the poster cache when the event is edited or deleted.
     */
    public static function forgetPosterCache(int $acaraId): void
    {
        Cache::forget(self::posterCacheKey($acaraId));
    }

    public static function forgetAllPosterCaches(): void
    {
        Acara::query()->pluck('id')->each(
            fn (int $id) => self::forgetPosterCache($id)
        );
    }

    // ── Internals ────────────────────────────────────────────────────────────

    /**
     * Generate the QR code base64 — cached forever per event code
     * because the public URL is immutable.
     */
    private function qrBase64(Acara $acara): string
    {
        $qrKey = 'acara_qr_b64_'.$acara->code;

        return Cache::rememberForever($qrKey, function () use ($acara): string {
            $svg = QrCode::format('svg')
                ->size(320)
                ->margin(2)
                ->errorCorrection('M')
                ->generate($acara->publicUrl());

            return base64_encode((string) $svg);
        });
    }

    private static function posterCacheKey(int $id): string
    {
        return "acara_poster_pdf_{$id}";
    }

    private function slug(Acara $acara): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', trim($acara->nama_acara));
        $safe = trim((string) $safe, '-');

        return ($safe !== '' ? $safe : 'acara').'-'.$acara->code;
    }
}
