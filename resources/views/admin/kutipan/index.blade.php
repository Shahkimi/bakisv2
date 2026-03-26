@extends('layouts.app')

@section('title', 'Kutipan Yuran')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4 group">
            <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2m-6-4a6 6 0 1112 0 6 6 0 01-12 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kutipan Yuran</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cari ahli dan proses kutipan yuran pembaharuan</p>
            </div>
        </div>

        <nav aria-label="Breadcrumb" class="text-sm text-gray-500 dark:text-gray-400">
            <ol class="flex items-center gap-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 hover:text-emerald-600 dark:hover:text-emerald-300 transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1V10.5z" />
                        </svg>
                        Utama
                    </a>
                </li>
                <li aria-hidden="true" class="text-gray-300 dark:text-gray-600">/</li>
                <li class="text-gray-700 dark:text-gray-200 font-medium">Kutipan Yuran</li>
            </ol>
        </nav>
    </div>

    <div class="flex justify-center">
        <section class="w-full max-w-2xl">
            <div class="rounded-2xl bg-white/80 dark:bg-gray-800/70 backdrop-blur border border-gray-200/70 dark:border-gray-700 shadow-sm overflow-visible">
                <div class="p-6 sm:p-7 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-2xl bg-white/60 dark:bg-gray-800/60 ring-1 ring-emerald-200/60 dark:ring-emerald-900/40 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="grow">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Cari Ahli</h2>
                                <span id="kbdHint" class="hidden sm:inline-flex items-center gap-1 rounded-lg bg-gray-100/80 dark:bg-gray-700/70 px-2 py-1 text-xs font-semibold text-gray-600 dark:text-gray-200 ring-1 ring-gray-200/70 dark:ring-gray-600/70">
                                    <span class="font-mono">Ctrl</span><span class="text-gray-400 dark:text-gray-500">+</span><span class="font-mono">K</span>
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Taip sekurang-kurangnya <span class="font-semibold text-gray-700 dark:text-gray-200">3 aksara</span> menggunakan No. KP, No. Ahli atau Nama.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-7">
                    <div class="rounded-xl border border-blue-200/60 dark:border-blue-900/60 bg-blue-50/60 dark:bg-blue-900/20 px-4 py-3 flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 100 2 1 1 0 000-2zm-1 3a1 1 0 012 0v5a1 1 0 11-2 0V9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-900/90 dark:text-blue-100 leading-relaxed">
                            Pilih ahli dan sistem akan bawa anda ke halaman kutipan secara automatik.
                        </div>
                    </div>

                    <div class="mt-5">
                        <label for="cari_ahli" class="flex flex-wrap items-center justify-between gap-3 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                            <span class="inline-flex flex-wrap items-center gap-2">
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Carian Ahli
                                <span id="resultCount" class="ks-result-count" aria-live="polite">
                                    <span id="resultCountNum">0</span> ahli dijumpai
                                </span>
                            </span>
                            <span id="searchStatus" class="hidden items-center gap-2 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                <span class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-emerald-300 border-t-transparent"></span>
                                <span>Mencari…</span>
                            </span>
                        </label>

                        <div class="mt-2 relative">
                            <select id="cari_ahli" class="w-full" aria-label="Carian ahli">
                                <option value=""></option>
                            </select>
                        </div>

                        <p class="mt-2 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z" />
                            </svg>
                            <span>Tekan <kbd class="font-mono text-xs rounded bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 text-gray-700 dark:text-gray-200 ring-1 ring-gray-200/70 dark:ring-gray-600/70">Esc</kbd> untuk kosongkan carian.</span>
                        </p>
                    </div>

                    {{-- Recent Searches --}}
                    <div id="recentWrap" class="mt-5 hidden border-t border-gray-100/80 dark:border-gray-700/70 pt-4 ks-fadeSlideIn">
                        <div class="flex items-center justify-between gap-3">
                            <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                </svg>
                                Carian Terkini
                            </p>
                            <button type="button" id="recentClear" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 hover:text-red-600 dark:hover:text-red-300 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0l1-2h6l1 2" />
                                </svg>
                                Padam semua
                            </button>
                        </div>
                        <div id="recentList" class="mt-2 flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    /* Choices.js Tailwind-ish skin (no jQuery needed) */
    .choices { margin-bottom: 0 !important; }
    .choices__inner {
        min-height: 52px !important;
        border-radius: 0.75rem !important;
        border: 1px solid rgba(229, 231, 235, 1) !important;
        background: rgba(255, 255, 255, 1) !important;
        padding: 0.55rem 0.75rem !important;
        font-size: 0.95rem !important;
        line-height: 1.25rem !important;
        box-shadow: none !important;
    }
    .dark .choices__inner {
        border-color: rgba(75, 85, 99, 1) !important;
        background: rgba(55, 65, 81, 1) !important;
        color: rgba(243, 244, 246, 1) !important;
    }
    .choices.is-focused .choices__inner,
    .choices.is-open .choices__inner {
        border-color: rgba(16, 185, 129, 1) !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
    }
    .choices__input {
        background: transparent !important;
        color: inherit !important;
        margin-bottom: 0 !important;
    }
    .choices__list--dropdown,
    .choices__list[aria-expanded] {
        border-radius: 0.75rem !important;
        border: 1.5px solid rgba(229, 231, 235, 1) !important;
        box-shadow: 0 18px 40px rgba(0,0,0,0.12) !important;
        overflow: hidden !important;
        z-index: 80 !important;
        margin-top: 6px !important;
    }
    .dark .choices__list--dropdown,
    .dark .choices__list[aria-expanded] {
        border-color: rgba(75, 85, 99, 1) !important;
        background: rgba(31, 41, 55, 1) !important;
        color: rgba(243, 244, 246, 1) !important;
    }
    .choices__list--dropdown .choices__item--selectable {
        padding: 0 !important;
        font-size: 0.9rem !important;
    }
    .choices__item--choice.is-highlighted,
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background: #f0fdf4 !important;
        color: inherit !important;
    }
    .dark .choices__item--choice.is-highlighted,
    .dark .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background: rgba(6, 78, 59, 0.35) !important;
    }
    .choices__placeholder { opacity: 1 !important; color: rgba(156, 163, 175, 1) !important; }

    .choices__list--dropdown .choices__list {
        max-height: 320px;
        overflow: auto;
        padding: 6px !important;
    }
    .choices__list--dropdown .choices__list::-webkit-scrollbar {
        width: 4px;
    }
    .choices__list--dropdown .choices__list::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.6);
        border-radius: 999px;
    }
    .dark .choices__list--dropdown .choices__list::-webkit-scrollbar-thumb {
        background: rgba(100, 116, 139, 0.7);
    }

    @keyframes ksFadeSlideIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .ks-fadeSlideIn { animation: ksFadeSlideIn 240ms ease-out both; }

    @keyframes ksScaleIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }
    .ks-scaleIn { animation: ksScaleIn 260ms cubic-bezier(0.34, 1.56, 0.64, 1) both; }

    /* Dropdown result rows (no Tailwind in JS — survives purging) */
    .ks-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 0.625rem;
        margin-bottom: 2px;
        transition: background-color 150ms ease, box-shadow 150ms ease;
    }
    .ks-avatar {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        line-height: 1;
    }
    .ks-info {
        flex: 1;
        min-width: 0;
    }
    .ks-name {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 2px;
        line-height: 1.3;
        word-break: break-word;
    }
    .ks-meta {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.3;
        word-break: break-word;
    }
    .ks-arrow {
        flex-shrink: 0;
        width: 16px;
        height: 16px;
        color: #9ca3af;
        opacity: 0;
        transition: opacity 150ms ease;
    }
    .choices__item--choice.is-highlighted .ks-arrow,
    .choices__list--dropdown .choices__item--selectable.is-highlighted .ks-arrow {
        opacity: 1;
    }
    .dark .ks-name {
        color: #f3f4f6;
    }
    .dark .ks-meta {
        color: #9ca3af;
    }
    .dark .ks-arrow {
        color: #6b7280;
    }

    .ks-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 24px;
        color: #6b7280;
        font-size: 13px;
        font-weight: 500;
    }
    .ks-empty svg {
        width: 32px;
        height: 32px;
        color: #d1d5db;
    }
    .dark .ks-empty {
        color: #9ca3af;
    }
    .dark .ks-empty svg {
        color: #4b5563;
    }

    .ks-result-count {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 9999px;
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        font-size: 11px;
        font-weight: 600;
        text-transform: none;
        letter-spacing: normal;
        opacity: 0;
        pointer-events: none;
        transition: opacity 200ms ease;
    }
    .ks-result-count.ks-show {
        opacity: 1;
    }
    .dark .ks-result-count {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }
</style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectEl = document.getElementById('cari_ahli');
    const searchStatusEl = document.getElementById('searchStatus');
    const recentWrapEl = document.getElementById('recentWrap');
    const recentListEl = document.getElementById('recentList');
    const recentClearEl = document.getElementById('recentClear');
    const kbdHintEl = document.getElementById('kbdHint');

    const recentKey = 'kutipan_recent_members_v2';
    const memberUrlTemplate = @json(route('admin.kutipan.member', ['encryptedNoKp' => '__ID__']));
    const autocompleteUrl = @json(route('admin.kutipan.autocomplete'));

    if (!selectEl || typeof Choices === 'undefined') {
        return;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = String(str ?? '');
        return div.innerHTML;
    }

    /** @type {Map<string, {id:string,text:string,no_kp?:string,no_ahli?:string}>} */
    const memberByEncryptedId = new Map();

    function officerNameFromLabel(text) {
        const s = String(text ?? '').trim();
        const idx = s.indexOf(' - ');
        if (idx > 0) {
            return s.slice(0, idx).trim();
        }
        return s;
    }

    function getInitials(name) {
        const parts = String(name || '').trim().split(/\s+/).filter(Boolean);
        if (parts.length >= 2) {
            return (parts[0][0] + parts[1][0]).toUpperCase();
        }
        if (parts.length === 1 && parts[0].length >= 2) {
            return parts[0].slice(0, 2).toUpperCase();
        }
        return (parts[0]?.[0] || '?').toUpperCase();
    }

    function getColorFromName(name) {
        const colors = [
            '#3b82f6',
            '#10b981',
            '#8b5cf6',
            '#f59e0b',
            '#ec4899',
            '#14b8a6',
            '#6366f1',
            '#ef4444',
        ];
        const str = String(name || '').toLowerCase();
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    }

    function setResultCountVisible(show, count) {
        const countEl = document.getElementById('resultCount');
        const countNumEl = document.getElementById('resultCountNum');
        if (!countEl || !countNumEl) return;
        if (show && count > 0) {
            countNumEl.textContent = String(count);
            countEl.classList.add('ks-show');
        } else {
            countEl.classList.remove('ks-show');
        }
    }

    const recentMaxItems = 3;

    function safeJsonParse(raw) {
        try { return JSON.parse(raw); } catch (e) { return null; }
    }


    function normalizeKp(kp) {
        return String(kp ?? '').replace(/\D/g, '');
    }

    /** IC/KP from label text: backend uses "Nama - No KP". */
    function extractKpFromText(text) {
        const s = String(text ?? '');
        const parts = s.split(/\s*-\s*/);
        if (parts.length >= 2) {
            const d = normalizeKp(parts[parts.length - 1]);
            if (d.length >= 6) return d;
        }
        const runs = s.match(/\d{6,}/g);
        if (runs && runs.length) {
            return normalizeKp(runs[runs.length - 1]);
        }
        return '';
    }

    /** Stable key per officer (survives different encrypted ids & missing fields). */
    function memberFingerprint(m) {
        if (!m) return '';
        const kp = normalizeKp(m.no_kp) || extractKpFromText(m.text);
        if (kp.length >= 6) return 'kp:' + kp;
        return 'id:' + String(m.id ?? '');
    }

    function dedupeRecentKeepOrder(arr) {
        const seen = new Set();
        const out = [];
        for (const item of arr) {
            if (!item) continue;
            const fp = memberFingerprint(item);
            if (seen.has(fp)) continue;
            seen.add(fp);
            out.push(item);
        }
        return out;
    }

    function setRecent(items) {
        const deduped = dedupeRecentKeepOrder(items);
        localStorage.setItem(recentKey, JSON.stringify(deduped.slice(0, recentMaxItems)));
    }

    function getRecent() {
        const parsed = safeJsonParse(localStorage.getItem(recentKey) || '[]');
        let arr = Array.isArray(parsed) ? parsed : [];
        arr = dedupeRecentKeepOrder(arr);
        if (arr.length > recentMaxItems) {
            arr = arr.slice(0, recentMaxItems);
        }
        const stored = localStorage.getItem(recentKey);
        const serialized = JSON.stringify(arr);
        if (stored !== serialized) {
            localStorage.setItem(recentKey, serialized);
        }
        return arr;
    }

    function renderRecent() {
        const items = getRecent();
        recentListEl.innerHTML = '';

        if (!items.length) {
            recentWrapEl.classList.add('hidden');
            return;
        }

        recentWrapEl.classList.remove('hidden');

        for (const item of items) {
            const label = officerNameFromLabel(item.text);
            const meta = String(item.no_kp ?? '').trim() || extractKpFromText(item.text);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'group inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:-translate-y-[1px] hover:shadow-sm hover:shadow-emerald-500/10 transition';
            btn.setAttribute('aria-label', `Buka ahli ${label}${meta ? ', No. KP ' + meta : ''}`);
            btn.innerHTML = `
                <svg class="h-4 w-4 text-emerald-600/70 dark:text-emerald-300/70" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
                <span class="truncate max-w-[16rem]">${escapeHtml(label)}</span>
                ${meta ? `<span class="text-xs text-gray-400 dark:text-gray-500">${escapeHtml(meta)}</span>` : ''}
            `;
            btn.addEventListener('click', () => {
                navigateToMember(item.id);
            });
            recentListEl.appendChild(btn);
        }
    }

    function pushRecent(member) {
        const fp = memberFingerprint(member);
        const items = getRecent().filter((x) => memberFingerprint(x) !== fp);
        items.unshift({
            id: member.id,
            text: member.text,
            no_kp: member.no_kp ?? '',
        });
        setRecent(items);
        renderRecent();
    }

    /** Brief pause so the redirect overlay / selection feels intentional before navigation. */
    const redirectDelayMs = 450;

    function showRedirectOverlay() {
        const overlay = document.getElementById('redirectOverlay');
        if (!overlay) return;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
    }

    function navigateToMember(encryptedId) {
        const url = memberUrlTemplate.replace('__ID__', encodeURIComponent(encryptedId));
        showRedirectOverlay();
        if (selectEl) selectEl.disabled = true;
        window.setTimeout(() => {
            window.location.href = url;
        }, redirectDelayMs);
    }

    function setSearching(isSearching) {
        if (!searchStatusEl) return;
        if (isSearching) {
            searchStatusEl.classList.remove('hidden');
            searchStatusEl.classList.add('inline-flex');
        } else {
            searchStatusEl.classList.add('hidden');
            searchStatusEl.classList.remove('inline-flex');
        }
    }

    function debounce(fn, waitMs) {
        let t = null;
        return function (...args) {
            if (t) window.clearTimeout(t);
            t = window.setTimeout(() => fn.apply(this, args), waitMs);
        };
    }

    let lastController = null;
    async function fetchMembers(query) {
        const q = String(query || '').trim();
        if (q.length < 3) return [];

        if (lastController) lastController.abort();
        lastController = new AbortController();

        setSearching(true);
        try {
            const url = new URL(autocompleteUrl, window.location.origin);
            url.searchParams.set('search', q);

            const res = await fetch(url.toString(), { signal: lastController.signal, headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            const members = Array.isArray(data?.members) ? data.members : [];

            return members.map((m) => ({
                id: m.id,
                text: m.text,
                no_kp: m.no_kp,
                no_ahli: m.no_ahli,
            }));
        } catch (e) {
            return [];
        } finally {
            setSearching(false);
        }
    }

    const noResultsHtml = `
        <div class="ks-empty">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span>Tiada ahli dijumpai</span>
        </div>
    `.trim();

    const choices = new Choices(selectEl, {
        searchEnabled: true,
        searchChoices: false,
        shouldSort: false,
        placeholder: true,
        placeholderValue: 'Taip No. KP / No. Ahli / Nama ahli...',
        itemSelectText: '',
        allowHTML: true,
        noResultsText: noResultsHtml,
    });

    const updateChoices = debounce(async (query) => {
        const q = String(query || '').trim();
        if (q.length < 3) {
            memberByEncryptedId.clear();
            choices.clearChoices();
            setResultCountVisible(false, 0);
            return;
        }

        const members = await fetchMembers(q);
        memberByEncryptedId.clear();
        members.forEach((m) => memberByEncryptedId.set(m.id, m));

        const choicesData = members.map((m) => {
            const initials = getInitials(m.text);
            const bgColor = getColorFromName(m.text);
            const metaLine = [m.no_ahli, m.no_kp].filter(Boolean).join(' • ');

            return {
                value: m.id,
                label: `
                    <div class="ks-item">
                        <div class="ks-avatar" style="background-color: ${bgColor};">
                            <span>${escapeHtml(initials)}</span>
                        </div>
                        <div class="ks-info">
                            <div class="ks-name">${escapeHtml(m.text)}</div>
                            <div class="ks-meta">${escapeHtml(metaLine)}</div>
                        </div>
                        <svg class="ks-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                `.trim(),
                customProperties: m,
            };
        });

        setResultCountVisible(members.length > 0, members.length);

        choices.clearChoices();
        choices.setChoices(choicesData, 'value', 'label', true);
    }, 300);

    selectEl.addEventListener('search', (e) => {
        const value = e?.detail?.value ?? '';
        if (!value || String(value).trim().length < 3) {
            setResultCountVisible(false, 0);
        }
        updateChoices(value);
    });

    selectEl.addEventListener('change', () => {
        const value = selectEl.value;
        if (!value) return;

        const fromMap = memberByEncryptedId.get(value);
        const store = fromMap
            ? { id: fromMap.id, text: fromMap.text, no_kp: fromMap.no_kp ?? '' }
            : { id: value, text: String(choices.getValue(true) || ''), no_kp: '' };
        pushRecent(store);

        navigateToMember(value);
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        const isK = (e.key || '').toLowerCase() === 'k';
        const isEsc = e.key === 'Escape';

        if ((e.ctrlKey || e.metaKey) && isK) {
            e.preventDefault();
            const input = document.querySelector('#cari_ahli + .choices .choices__input');
            input?.focus();
        }
        if (isEsc) {
            choices.removeActiveItems();
            choices.clearInput();
            memberByEncryptedId.clear();
            setResultCountVisible(false, 0);
        }
    });

    // Hint (first visit)
    if (kbdHintEl) {
        const key = 'kutipan_kbd_hint_dismissed_v1';
        if (!localStorage.getItem(key)) {
            kbdHintEl.classList.remove('hidden');
            localStorage.setItem(key, '1');
        }
    }

    // Recent clear
    recentClearEl?.addEventListener('click', () => {
        if (!confirm('Padam semua carian terkini?')) return;
        localStorage.removeItem(recentKey);
        renderRecent();
    });

    renderRecent();
});
</script>

{{-- Redirect overlay --}}
<div id="redirectOverlay" class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-900/40 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Mengalihkan ke halaman kutipan">
    <div class="w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-2xl p-7 ks-scaleIn">
        <div class="flex flex-col items-center text-center gap-4">
            <div class="h-16 w-16 rounded-2xl bg-emerald-600/10 ring-8 ring-emerald-50 dark:ring-emerald-900/20 flex items-center justify-center">
                <div class="h-8 w-8 animate-spin rounded-full border-[3px] border-emerald-400 border-t-transparent"></div>
            </div>
            <div>
                <div class="font-semibold text-gray-900 dark:text-white">Loading Data…</div>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sila tunggu sebentar.</div>
            </div>
        </div>
    </div>
</div>
@endpush