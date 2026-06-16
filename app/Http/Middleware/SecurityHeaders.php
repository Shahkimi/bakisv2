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
        $scriptExtra = '';
        $connectExtra = '';
        if (app()->environment('local')) {
            $scriptExtra = ' http://localhost:5173 http://127.0.0.1:5173';
            $connectExtra = ' http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173';
        }

        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$cloudflare} {$cdns}{$scriptExtra}",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdn.datatables.net",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: blob:",
            "connect-src 'self' {$cloudflare}{$connectExtra}",
            "frame-src {$cloudflare}",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        return implode('; ', $directives);
    }
}
