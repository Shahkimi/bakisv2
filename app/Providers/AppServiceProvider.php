<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Payment;
use App\Observers\PaymentObserver;
use App\Services\MailSettingService;
use App\Services\TurnstileSettingService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton so the test-email bypass flag survives from the controller
        // through to the MessageSending listener within the same request.
        $this->app->singleton(MailSettingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply admin-configured SMTP relay settings over the .env defaults.
        // Guarded: on a fresh install the `settings` table may not be migrated yet.
        rescue(fn () => app(MailSettingService::class)->applyRuntimeConfig(), report: false);

        // Master kill-switch: when email is disabled, silently skip every outgoing
        // mail (notifications, password resets) instead of attempting delivery.
        Event::listen(MessageSending::class, function (): ?bool {
            $mailSetting = app(MailSettingService::class);

            if ($mailSetting->isOperational() || $mailSetting->consumeSendBypass()) {
                return null;
            }

            Log::info($mailSetting->enabled()
                ? 'E-mel belum dikonfigurasi: penghantaran e-mel dilangkau.'
                : 'E-mel dimatikan: penghantaran e-mel dilangkau.');

            return false;
        });

        RateLimiter::for('semak', function (Request $request) {
            if (app()->isLocal()) {
                return Limit::none();
            }

            // Turnstile already verifies every public semak submission, so when it is
            // active there is no need to rate limit — verified users can check freely.
            // When an admin disables Turnstile, fall back to an IP limit so the no_kp
            // lookup is never left open to scripted enumeration.
            if (app(TurnstileSettingService::class)->isActive()) {
                return Limit::none();
            }

            return Limit::perMinute(20)->by($request->ip());
        });

        Payment::observe(PaymentObserver::class);

        // Provide Turnstile widget state to the public forms that use it.
        View::composer(
            ['auth.login', 'auth.reset-password', 'semak.index', 'semak.result'],
            function ($view): void {
                $turnstile = app(TurnstileSettingService::class);
                $view->with([
                    'turnstileEnabled' => $turnstile->isActive(),
                    'turnstileSiteKey' => $turnstile->siteKey(),
                ]);
            }
        );

        // Let email-dependent views hide/disable features that can't work right now.
        View::composer(
            ['auth.login', 'admin.kawalan.pengguna'],
            function ($view): void {
                $view->with('mailOperational', app(MailSettingService::class)->isOperational());
            }
        );
    }
}
