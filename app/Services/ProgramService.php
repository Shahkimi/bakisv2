<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

final readonly class ProgramService
{
    public function create(array $data): Program
    {
        return Program::create($data);
    }

    public function update(Program $program, array $data): Program
    {
        $program->update($data);

        return $program;
    }

    public function delete(Program $program): void
    {
        $program->delete();
    }

    /**
     * Programs ordered upcoming-first (soonest date first), then past (most recent first).
     *
     * @return Collection<int, Program>
     */
    public function listOrdered(bool $onlyActive = false): Collection
    {
        $query = Program::query();

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        $programs = $query->get();
        $today = Carbon::today();

        return $programs
            ->sortBy(function (Program $program) use ($today): string {
                $isPast = $program->tarikh->lt($today);
                // Upcoming (group 0) ascending by date; past (group 1) descending by date.
                $group = $isPast ? '1' : '0';
                $ts = $program->tarikh->timestamp;
                $key = $isPast ? (PHP_INT_MAX - $ts) : $ts;

                return $group.'_'.str_pad((string) $key, 20, '0', STR_PAD_LEFT);
            })
            ->values();
    }

    /** @return Collection<int, Program> */
    public function listForPublic(int $limit = 3): Collection
    {
        $upcoming = $this->listOrdered(onlyActive: true)
            ->filter(fn (Program $program) => ! $program->isPast());

        if ($upcoming->isNotEmpty()) {
            return $upcoming->take($limit)->values();
        }

        // No upcoming — fall back to the 3 most recently held programs.
        return $this->listOrdered(onlyActive: true)
            ->filter(fn (Program $program) => $program->isPast())
            ->take($limit)
            ->values();
    }
}
