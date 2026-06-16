<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Payment;
use App\Observers\PaymentObserver;
use App\Services\TurnstileSettingService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
    }
}
