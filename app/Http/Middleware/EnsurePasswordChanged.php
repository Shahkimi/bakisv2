<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsurePasswordChanged
{
    /**
     * Routes the user may still reach while a forced password change is pending.
     *
     * @var array<int, string>
     */
    private const ALLOWED_ROUTES = [
        'password.change.show',
        'password.change.update',
        'logout',
    ];

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user instanceof User && $user->must_change_password) {
            if (! in_array($request->route()?->getName(), self::ALLOWED_ROUTES, true)) {
                return redirect()->route('password.change.show');
            }
        }

        return $next($request);
    }
}
