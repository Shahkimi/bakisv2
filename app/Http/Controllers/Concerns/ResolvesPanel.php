<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Models\User;

trait ResolvesPanel
{
    protected function panelPrefix(): string
    {
        return $this->currentUser()->isAdmin() ? 'admin.' : 'user.';
    }

    protected function panelView(string $relativePath): string
    {
        $base = $this->currentUser()->isAdmin() ? 'admin' : 'user';

        return $base.'.'.$relativePath;
    }

    protected function panelRoute(string $name, mixed $parameters = []): string
    {
        return route($this->panelPrefix().$name, $parameters);
    }

    private function currentUser(): User
    {
        $user = auth()->user();
        assert($user instanceof User);

        return $user;
    }
}
