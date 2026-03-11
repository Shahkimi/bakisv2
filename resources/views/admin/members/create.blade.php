@extends('layouts.app')

@section('title', 'Tambah Ahli Baru')

@section('content')
<div class="h-[calc(100vh-4rem)] max-h-[calc(100vh-4rem)] flex flex-col max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 overflow-hidden bg-gray-50 dark:bg-gray-900" x-data="{ currentStep: 1, totalSteps: 4, approvePayment: {{ old('approve_immediately') ? 'true' : 'false' }} }">
    <!-- Page Header (dynamic title per step) -->
    <div class="flex-shrink-0 pt-4 pb-2">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="currentStep === 1 ? 'Maklumat Keanggotaan' : (currentStep === 2 ? 'Maklumat Peribadi' : (currentStep === 3 ? 'Maklumat Alamat' : 'Maklumat Pembayaran'))"></h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="currentStep === 1 ? 'Status ahli, no. ahli, tarikh daftar' : (currentStep === 2 ? 'Nama, no. KP, jabatan, jawatan' : (currentStep === 3 ? 'Alamat surat-menyurat' : 'Bayaran yuran keahlian'))"></p>
    </div>

    @include('admin.members.partials.step-indicator')

    @if($errors->any())
        <div class="flex-shrink-0 mb-3 rounded-xl bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-3 text-xs shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500 dark:text-red-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xs font-semibold text-red-800 dark:text-red-200">Sila betulkan ralat berikut</h3>
                    <ul class="mt-0.5 text-xs text-red-700 dark:text-red-300 list-disc list-inside space-y-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" id="memberForm" class="flex-1 min-h-0 flex flex-col items-center">
        @csrf

        <div class="w-full max-w-4xl mx-auto flex-1 min-h-0 flex flex-col relative pb-4">
        @include('admin.members.partials.form1')
        @include('admin.members.partials.form2')
        @include('admin.members.partials.form3')
        @include('admin.members.partials.form4')

        <!-- Step navigation -->
        <div class="flex-shrink-0 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3 w-full">
            <div>
                <button type="button"
                        x-show="currentStep > 1"
                        x-cloak
                        @click="currentStep--"
                        class="inline-flex items-center py-2.5 px-5 text-sm font-medium rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-1 focus:ring-gray-400 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Previous
                </button>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.members.index') }}" class="inline-flex items-center py-2.5 px-5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-1 focus:ring-gray-400 transition">
                    Batal
                </a>
                <button type="button"
                        x-show="currentStep < totalSteps"
                        @click="currentStep++"
                        class="inline-flex items-center py-2.5 px-6 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                    Next
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
                <button type="submit"
                        x-show="currentStep === totalSteps"
                        x-cloak
                        class="inline-flex items-center py-2.5 px-6 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Ahli
                </button>
            </div>
        </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Improved Select2 custom styling */
    .select2-container--default .select2-selection--single { height: 46px; padding: 0.625rem 0.75rem; border-radius: 0.5rem; border: 1px solid rgb(209 213 219); transition: all 0.2s; background-color: #ffffff; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.5; padding-left: 0; color: #111827; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px; right: 8px; }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single { border-color: rgb(99 102 241); outline: 0; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25); }
    .select2-dropdown { border-radius: 0.5rem; border: 1px solid rgb(209 213 219); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); overflow: hidden; z-index: 50; }
    .select2-container--default .select2-search--dropdown .select2-search__field { border-radius: 0.375rem; padding: 0.5rem 0.75rem; border: 1px solid rgb(209 213 219); outline: none; transition: all 0.2s; }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus { border-color: rgb(99 102 241); box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25); }
    .select2-results__option { padding: 0.5rem 1rem; transition: background-color 0.1s; }
    
    /* Dark mode enhancements - matching exact gray-800 background from the app */
    .dark .select2-container--default .select2-selection--single { background-color: rgb(31 41 55) !important; border-color: rgb(75 85 99); }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered { color: rgb(243 244 246) !important; }
    .dark .select2-dropdown { background-color: rgb(31 41 55); border-color: rgb(75 85 99); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5); }
    .dark .select2-container--default .select2-search--dropdown .select2-search__field { background-color: rgb(17 24 39); border-color: rgb(75 85 99); color: white; }
    .dark .select2-results__option { color: rgb(229 231 235); }
    .dark .select2-container--default .select2-results__option[aria-selected=true] { background-color: rgb(55 65 81); }
    .dark .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: rgb(99 102 241); color: white; }
    .dark .select2-container--default .select2-selection--single .select2-selection__arrow b { border-color: rgb(156 163 175) transparent transparent transparent; }
    .dark .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b { border-color: transparent transparent rgb(156 163 175) transparent; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') return;
    var $ = jQuery;
    $('.member-select2').select2({
        width: '100%',
        placeholder: 'Cari...',
        allowClear: true,
        language: {
            noResults: function() { return 'Tiada hasil'; },
            searching: function() { return 'Mencari...'; },
            inputTooShort: function() { return 'Sila taip untuk cari'; }
        }
    });
});
</script>
@endpush
@endsection
