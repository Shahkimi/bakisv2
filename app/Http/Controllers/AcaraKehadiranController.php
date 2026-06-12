<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreKehadiranRequest;
use App\Models\Acara;
use App\Models\KehadiranAcara;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AcaraKehadiranController extends Controller
{
    /**
     * Public attendance page reached via the QR/short link (domain/{code}).
     */
    public function show(string $code): View
    {
        $acara = Acara::where('code', $code)->firstOrFail();

        if (! $acara->isOpen()) {
            return view('kehadiran.expired', ['acara' => $acara]);
        }

        return view('kehadiran.public', ['acara' => $acara]);
    }

    public function store(StoreKehadiranRequest $request, string $code): View|RedirectResponse
    {
        $acara = Acara::where('code', $code)->firstOrFail();

        if (! $acara->isOpen()) {
            return view('kehadiran.expired', ['acara' => $acara]);
        }

        $noKp = $request->validated('no_kp');

        $member = Member::where('no_kp', $noKp)->first();
        if ($member === null) {
            return redirect()
                ->route('acara.public', ['code' => $code])
                ->withInput()
                ->with('lookup_error', 'No. KP tidak berdaftar sebagai ahli. Sila semak semula.');
        }

        $kehadiran = KehadiranAcara::firstOrCreate(
            ['acara_id' => $acara->id, 'no_kp' => $noKp],
            [
                'member_id' => $member->id,
                'nama' => $member->nama,
                'attended_at' => now(),
            ],
        );

        return view('kehadiran.success', [
            'acara' => $acara,
            'kehadiran' => $kehadiran,
            'alreadyRecorded' => ! $kehadiran->wasRecentlyCreated,
        ]);
    }
}
