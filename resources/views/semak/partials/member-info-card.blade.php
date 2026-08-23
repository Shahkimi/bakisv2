{{--
    Officer/member info card — status notice + identity + copyable No. Ahli / Status Rekod.
    Expects (inherited from the including view's scope): $member, $status, $config,
    $checkedNoKp, $memberPhotoUrl, $showPayment.
--}}
@php
    $memberInitial = strtoupper(mb_substr($member->nama ?? 'A', 0, 1));
    $recordStatusCode = strtolower((string) ($member->memberStatus?->code ?? ''));
    $recordBadge = match($recordStatusCode) {
        'aktif'      => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
        'meninggal'  => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
        'pending'    => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
        default      => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
    };
@endphp

{{-- Maklumat Ahli --}}
<div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2.5">
        <span class="w-1 h-4 rounded-full bg-teal-500 shrink-0"></span>
        <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Maklumat Ahli</h3>
    </div>
    <div class="p-5 sm:p-6">
        <div class="flex items-center gap-4 pb-5 mb-5 border-b border-slate-100 dark:border-slate-700/60">
            <div class="relative h-16 w-16 shrink-0">
                @if($memberPhotoUrl)
                    <img src="{{ $memberPhotoUrl }}" alt="" width="64" height="64"
                         class="member-avatar-photo h-16 w-16 rounded-2xl object-cover shadow ring-2 ring-white dark:ring-slate-700"
                         onerror="this.classList.add('hidden'); document.getElementById('member-avatar-fallback-{{ $member->id ?? 'x' }}')?.classList.remove('hidden');">
                @endif
                <div id="member-avatar-fallback-{{ $member->id ?? 'x' }}"
                     class="{{ $memberPhotoUrl ? 'hidden' : '' }} h-16 w-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow ring-2 ring-white dark:ring-slate-700">
                    <span class="text-white font-bold text-xl" aria-hidden="true">{{ $memberInitial }}</span>
                </div>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-slate-900 dark:text-slate-50 text-lg leading-tight truncate">{{ $member->nama ?? '–' }}</p>
                @if($member->no_kp)
                    <button type="button" class="js-copy-value copy-inline mt-1" data-copy-value="{{ $member->no_kp }}" data-copy-label="No. Kad Pengenalan" aria-label="Salin No. Kad Pengenalan">
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-mono break-all">{{ $member->no_kp }}</span>
                        <span class="js-copy-icon shrink-0" aria-hidden="true">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                            </svg>
                        </span>
                    </button>
                @else
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 font-mono">–</p>
                @endif
            </div>
            <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $config['badge'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}" aria-hidden="true"></span>
                {{ strtoupper($status) }}
            </span>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <button type="button" class="js-copy-value text-left rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30 px-4 py-3 transition-colors hover:bg-teal-50 dark:hover:bg-teal-900/20 hover:border-teal-200 dark:hover:border-teal-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-400"
                    data-copy-value="{{ $member->no_ahli }}" data-copy-label="No. Ahli" aria-label="Salin No. Ahli">
                <span class="flex items-center justify-between gap-2 mb-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">No. Ahli</span>
                    <span class="js-copy-icon opacity-40" aria-hidden="true">
                        <svg class="w-3 h-3 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                        </svg>
                    </span>
                </span>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 font-mono">{{ $member->no_ahli ?? '–' }}</p>
            </button>
            <div class="rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30 px-4 py-3">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Status Rekod</p>
                <span class="inline-flex text-[11px] font-bold px-2.5 py-0.5 rounded-lg border {{ $recordBadge }}">
                    {{ strtoupper($member->memberStatus?->name ?? '–') }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Status notice --}}
<div class="mt-4 rounded-xl border {{ $config['noticeBox'] }} p-3.5 space-y-2">
    <p class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider {{ $config['noticeLabel'] }}">
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/>
        </svg>
        {{ $config['title'] }}
    </p>
    <p class="text-xs text-slate-700 dark:text-slate-300">{{ $config['summary'] }}</p>
    <p class="text-[11px] {{ $config['noticeSub'] }}">{{ $config['nextStep'] }}</p>
    @if($showPayment)
        <p class="text-[11px] font-semibold {{ $config['noticeSub'] }}">Yuran pembaharuan: RM10.00 / tahun</p>
    @endif
</div>
