@extends('layouts.app')

@section('title', 'Kutipan')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4 group">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Kutipan Yuran</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cari ahli untuk kutipan yuran pembaharuan.</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200">
            <svg class="h-5 w-5 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Search Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="flex-1">
                    <label for="memberSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Carian Ahli
                    </label>
                    <div class="mt-2 flex flex-col sm:flex-row gap-3">
                        <select id="memberSearch" class="w-full sm:flex-1" aria-label="Carian Ahli"></select>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Taip sekurang-kurangnya 3 aksara (No. KP / No. Ahli / Nama), pilih ahli, kemudian sistem akan bawa ke halaman kutipan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css"/>
<style>
    .select2-container { width: 100% !important; }
    .select2-container .select2-selection--single {
        min-height: 3.25rem;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid rgb(229 231 235);
        background: #fff;
        font-size: .875rem;
        color: rgb(17 24 39);
    }
    .select2-container--open .select2-selection--single,
    .select2-container--focus .select2-selection--single {
        border-color: rgb(99 102 241) !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15) !important;
    }
    .select2-container .select2-selection__rendered { line-height: 2.25rem; padding-left: 0 !important; }
    .select2-container .select2-selection__arrow { height: 3.25rem; right: .5rem; }
    .select2-dropdown { border-radius: .75rem; overflow: hidden; border: 1px solid rgb(229 231 235); }
    .select2-search__field { border-radius: .5rem; border: 1px solid rgb(229 231 235) !important; }

    .dark .select2-container .select2-selection--single { background: rgb(17 24 39 / .5); border-color: rgb(75 85 99); color: rgb(243 244 246); }
    .dark .select2-container .select2-selection--single .select2-selection__rendered { color: rgb(243 244 246); }
    .dark .select2-dropdown { background: rgb(31 41 55); border-color: rgb(75 85 99); }
    .dark .select2-results__option { color: rgb(243 244 246); }
    .dark .select2-search__field { background: rgb(55 65 81); border-color: rgb(75 85 99) !important; color: rgb(243 244 246); }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        const memberUrlTemplate = @json(route('admin.kutipan.member', ['encryptedNoKp' => '__ENCRYPTED__']));

        $('#memberSearch').select2({
            placeholder: 'Taip 3+ aksara untuk cari ahli...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: @json(route('admin.kutipan.autocomplete')),
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { search: params.term };
                },
                processResults: function (data) {
                    return { results: data.members || [] };
                }
            },
            language: {
                noResults: () => 'Tiada ahli sepadan',
                searching: () => 'Mencari...',
                inputTooShort: () => 'Sila taip sekurang-kurangnya 3 aksara'
            }
        });

        $('#memberSearch').on('select2:select', function (e) {
            const selected = e.params.data || {};
            const encrypted = selected.id || '';

            if (!encrypted) {
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Pilihan tidak sah.' });
                return;
            }

            const url = memberUrlTemplate.replace('__ENCRYPTED__', encodeURIComponent(encrypted));
            window.location.href = url;
        });
    });
</script>
@endpush
@endsection

