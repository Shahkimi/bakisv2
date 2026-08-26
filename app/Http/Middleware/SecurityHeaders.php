<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SecurityHeaders
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // CSP can be disabled via env if a page is unexpectedly blocked.
        if ((bool) env('SECURITY_HEADERS_CSP', true) && ! $headers->has('Content-Security-Policy')) {
            $headers->set('Content-Security-Policy', $this->contentSecurityPolicy());
        }

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        $cloudflare = 'https://challenges.cloudflare.com';
        $cdns = 'https://code.jquery.com https://cdn.jsdelivr.net https://cdn.datatables.net';

        // Allow Vite's dev server / HMR websocket only in local development.
        $vite = '';
        $viteSocket = '';
        if (app()->environment('local')) {
            $origins = $this->viteDevServerOrigins();
            $vite = ' '.implode(' ', $origins);
            $viteSocket = ' '.implode(' ', array_map(
                static fn (string $origin): string => (string) preg_replace('#^http#', 'ws', $origin),
                $origins
            ));
        }

        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$cloudflare} {$cdns}{$vite}",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdn.datatables.net{$vite}",
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:{$vite}",
            "img-src 'self' data: blob:{$vite}",
            "connect-src 'self' {$cloudflare}{$vite}{$viteSocket}",
            "frame-src 'self' {$cloudflare}",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        return implode('; ', $directives);
    }

    /**
     * Origins the Vite dev server may be reached on.
     *
     * The hot file holds the origin Vite actually bound to, which is
     * http://[::1]:5173 when it resolves the loopback host to IPv6. Guessing
     * localhost/127.0.0.1 is not enough — the emitted asset URLs must match.
     *
     * @return list<string>
     */
    private function viteDevServerOrigins(): array
    {
        $origins = ['http://localhost:5173', 'http://127.0.0.1:5173'];

        $hotFile = public_path('hot');
        if (is_file($hotFile)) {
            $hot = trim((string) file_get_contents($hotFile));

            if ($hot !== '' && ! in_array($hot, $origins, true)) {
                $origins[] = $hot;
            }
        }

        return $origins;
    }
}
