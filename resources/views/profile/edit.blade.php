@extends('layouts.app')

@section('title', 'Tetapan Profil')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profilePage', () => ({
            activeTab: 'profile',
            init() {
                const h = (window.location.hash || '').replace('#', '');
                if (h === 'security') {
                    this.activeTab = 'security';
                }
                if (h === 'email') {
                    this.activeTab = 'email';
                }
            },
            setTab(tab) {
                this.activeTab = tab;
                const frag = tab === 'profile' ? '' : '#' + tab;
                window.history.replaceState(null, '', window.location.pathname + window.location.search + frag);
            },
        }));
    });
</script>
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8" x-data="profilePage">
    {{-- Page header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="flex items-center gap-4 group">
            <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tetapan Profil</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus nama, kata laluan dan e-mel akaun anda.</p>
            </div>
        </div>
        <nav aria-label="Breadcrumb" class="text-sm text-gray-500 dark:text-gray-400 shrink-0">
            <ol class="flex flex-wrap items-center gap-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Utama</a>
                </li>
                <li aria-hidden="true" class="text-gray-300 dark:text-gray-600">/</li>
                <li class="text-gray-700 dark:text-gray-200 font-medium">Tetapan Profil</li>
            </ol>
        </nav>
    </div>

    {{-- Tabs --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-gray-800/70 backdrop-blur shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700 px-2 sm:px-4 pt-2 sm:pt-3">
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2" role="tablist" aria-label="Tetapan profil">
                <button type="button"
                        role="tab"
                        id="tab-profile"
                        :aria-selected="activeTab === 'profile'"
                        @click="setTab('profile')"
                        :class="activeTab === 'profile'
                            ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/80'"
                        class="flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil
                </button>
                <button type="button"
                        role="tab"
                        id="tab-security"
                        :aria-selected="activeTab === 'security'"
                        @click="setTab('security')"
                        :class="activeTab === 'security'
                            ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/80'"
                        class="flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Kata Laluan
                </button>
                <button type="button"
                        role="tab"
                        id="tab-email"
                        :aria-selected="activeTab === 'email'"
                        @click="setTab('email')"
                        :class="activeTab === 'email'
                            ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/80'"
                        class="flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    E-mel
                </button>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            {{-- Tab: Profile --}}
            <div x-show="activeTab === 'profile'" x-cloak role="tabpanel" aria-labelledby="tab-profile">
                <form method="POST" action="{{ route('profile.update') }}" class="profile-settings-form space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('name') border-red-500 ring-1 ring-red-500 @enderror">
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-mel semasa</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-mono bg-gray-50 dark:bg-gray-900/50 rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700">{{ $user->email }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Untuk menukar e-mel, gunakan tab <strong>E-mel</strong>.</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="profile-submit-btn inline-flex items-center justify-center gap-2 min-w-[11rem] rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800 disabled:opacity-90 disabled:cursor-wait">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan profil
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tab: Password --}}
            <div x-show="activeTab === 'security'" x-cloak id="panel-security" role="tabpanel" aria-labelledby="tab-security">
                <form method="POST" action="{{ route('profile.password') }}" class="profile-settings-form space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="current_password_pwd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kata laluan semasa</label>
                        <input type="password" name="current_password" id="current_password_pwd" required autocomplete="current-password"
                               class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kata laluan baharu</label>
                        <input type="password" name="password" id="password" required autocomplete="new-password"
                               class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sahkan kata laluan baharu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                               class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div class="rounded-xl border border-blue-200/60 dark:border-blue-900/60 bg-blue-50/60 dark:bg-blue-900/20 px-4 py-3 text-sm text-blue-900/90 dark:text-blue-100">
                        <p class="font-semibold mb-1">Syarat kata laluan</p>
                        <ul class="list-disc list-inside space-y-0.5 text-blue-800/90 dark:text-blue-200/90">
                            <li>Sekurang-kurangnya 8 aksara</li>
                            <li>Huruf besar dan huruf kecil</li>
                            <li>Sekurang-kurangnya satu nombor dan satu simbol</li>
                        </ul>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="profile-submit-btn inline-flex items-center justify-center gap-2 min-w-[11rem] rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800 disabled:opacity-90 disabled:cursor-wait">
                            Kemaskini kata laluan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tab: Email --}}
            <div x-show="activeTab === 'email'" x-cloak id="panel-email" role="tabpanel" aria-labelledby="tab-email">
                <form method="POST" action="{{ route('profile.email') }}" class="profile-settings-form space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-mel baharu</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="email"
                               class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="current_password_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kata laluan semasa (pengesahan)</label>
                        <input type="password" name="current_password" id="current_password_email" required autocomplete="current-password"
                               class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="rounded-xl border border-amber-200/60 dark:border-amber-900/60 bg-amber-50/60 dark:bg-amber-900/20 px-4 py-3 text-sm text-amber-900/90 dark:text-amber-100">
                        Pastikan e-mel baharu boleh diakses; ia digunakan untuk log masuk dan notifikasi penting sistem.
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="profile-submit-btn inline-flex items-center justify-center gap-2 min-w-[11rem] rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800 disabled:opacity-90 disabled:cursor-wait">
                            Kemaskini e-mel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const spinnerSvg = '<svg class="h-4 w-4 animate-spin shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    document.querySelectorAll('form.profile-settings-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            let btn = e.submitter;
            if (!btn || btn.type !== 'submit') {
                btn = form.querySelector('button.profile-submit-btn[type="submit"]');
            }
            if (!btn || !btn.classList.contains('profile-submit-btn')) {
                return;
            }
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            btn.classList.add('pointer-events-none');
            btn.innerHTML = spinnerSvg + '<span>Memproses...</span>';
        });
    });
});
</script>
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berjaya',
        text: @json(session('success')),
        confirmButtonText: 'OK',
        timer: 4500,
        timerProgressBar: true,
    });
});
</script>
@endif
@endpush
