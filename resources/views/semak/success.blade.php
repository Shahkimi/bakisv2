@extends('layouts.app')

@section('title', 'Pendaftaran Berjaya')

@section('content')
@php
    $member = $member ?? session('member');
@endphp
<div class="bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300 dark:bg-indigo-900 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative max-w-lg w-full animate-fade-in">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700 backdrop-blur-sm text-center">
            @if($member)
                <div class="mx-auto flex items-center justify-center w-20 h-20 rounded-2xl gradient-bg shadow-lg mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Berjaya Dihantar</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Terima kasih, <strong>{{ $member->nama }}</strong>. Permohonan ahli baru anda telah direkodkan.</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mb-2">No. KP: {{ substr($member->no_kp, 0, 6) }}******</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">Pembayaran anda (RM12.00) akan disemak oleh admin. Anda akan dimaklumkan selepas kelulusan. No. Ahli akan dijana upon approval.</p>
            @else
                <div class="mx-auto flex items-center justify-center w-20 h-20 rounded-2xl bg-gray-200 dark:bg-gray-700 mb-6">
                    <svg class="w-10 h-10 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Sesi tamat</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Sila gunakan Semak Status untuk maklumat lanjut.</p>
            @endif
            <a href="{{ route('semak.index') }}" class="inline-flex justify-center items-center py-3 px-6 border border-transparent text-sm font-semibold rounded-lg text-white gradient-bg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                Kembali ke Semak
            </a>
        </div>
    </div>
</div>
@endsection
