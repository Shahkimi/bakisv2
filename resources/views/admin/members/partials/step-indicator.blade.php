{{-- Step indicator: horizontal card layout (requires parent x-data with currentStep) --}}
<div class="flex-shrink-0 py-6 flex flex-col sm:flex-row justify-between items-stretch gap-3 w-full max-w-4xl mx-auto">
    <!-- Step 1: Maklumat Keanggotaan -->
    <div @click="currentStep = 1"
         :class="currentStep === 1 ? 'bg-blue-700 bg-gradient-to-br from-blue-700 via-indigo-600 to-sky-700 !text-white border-blue-600/90 dark:border-indigo-500 shadow-xl shadow-blue-900/30 ring-2 ring-white/20 transform scale-[1.02]' : (currentStep > 1 ? 'bg-white dark:bg-gray-800 border-blue-300 dark:border-indigo-600/80 text-gray-900 dark:text-white hover:bg-blue-50/90 dark:hover:bg-indigo-900/30 hover:border-blue-400/80' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:border-gray-400 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50')"
         class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-2xl border-2 cursor-pointer transition-all duration-500 ease-out flex-1 min-w-0 group relative overflow-hidden">
        
        <!-- Animated completion background fill -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50/90 via-indigo-50/80 to-sky-50/70 dark:from-blue-900/25 dark:via-indigo-900/20 dark:to-sky-900/15 transform origin-left transition-transform duration-500 ease-out"
             :class="currentStep > 1 ? 'scale-x-100' : 'scale-x-0'"></div>
             
        <div class="relative flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center transition-all duration-500"
             :class="currentStep === 1 ? 'bg-white/20 backdrop-blur-sm shadow-inner ring-1 ring-white/40' : (currentStep > 1 ? 'bg-blue-100 dark:bg-indigo-700/50 shadow-sm ring-1 ring-blue-200/50 dark:ring-indigo-500/30' : 'bg-gray-200/80 dark:bg-gray-600/50')">
            <div class="relative w-5 h-5 sm:w-6 sm:h-6">
                <!-- Original Icon -->
                <svg x-show="currentStep <= 1" 
                     x-transition:leave="transition duration-300 ease-in" 
                     x-transition:leave-end="opacity-0 scale-50 -rotate-45"
                     class="absolute inset-0 w-full h-full" :class="currentStep === 1 ? '!text-white' : '!text-gray-600 dark:!text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <!-- Checkmark Icon -->
                <svg x-show="currentStep > 1" x-cloak
                     x-transition:enter="transition duration-500 ease-out delay-150" 
                     x-transition:enter-start="opacity-0 scale-50 rotate-45" 
                     x-transition:enter-end="opacity-100 scale-100 rotate-0"
                     class="absolute inset-0 w-full h-full text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <div class="relative flex-1 min-w-0 z-10 transition-colors duration-300" :class="currentStep === 1 ? '!text-white' : (currentStep > 1 ? 'text-blue-800 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300')">
            <div class="font-semibold text-xs sm:text-sm tracking-tight">Keanggotaan</div>
        </div>
        <svg class="relative z-10 w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 transition-transform duration-500" :class="currentStep === 1 ? '!text-white translate-x-1 opacity-100' : (currentStep > 1 ? 'text-blue-500 dark:text-blue-400 opacity-100 translate-x-1' : 'text-gray-600 dark:text-gray-400 opacity-70 group-hover:translate-x-1 group-hover:opacity-100')" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    </div>

    <!-- Step 2: Maklumat Peribadi -->
    <div @click="currentStep = 2"
         :class="currentStep === 2 ? 'bg-blue-700 bg-gradient-to-br from-blue-700 via-indigo-600 to-sky-700 !text-white border-blue-600/90 dark:border-indigo-500 shadow-xl shadow-blue-900/30 ring-2 ring-white/20 transform scale-[1.02]' : (currentStep > 2 ? 'bg-white dark:bg-gray-800 border-blue-300 dark:border-indigo-600/80 text-gray-900 dark:text-white hover:bg-blue-50/90 dark:hover:bg-indigo-900/30 hover:border-blue-400/80' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:border-gray-400 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50')"
         class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-2xl border-2 cursor-pointer transition-all duration-500 ease-out flex-1 min-w-0 group relative overflow-hidden">
        
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50/90 via-indigo-50/80 to-sky-50/70 dark:from-blue-900/25 dark:via-indigo-900/20 dark:to-sky-900/15 transform origin-left transition-transform duration-500 ease-out"
             :class="currentStep > 2 ? 'scale-x-100' : 'scale-x-0'"></div>

        <div class="relative flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center transition-all duration-500"
             :class="currentStep === 2 ? 'bg-white/20 backdrop-blur-sm shadow-inner ring-1 ring-white/40' : (currentStep > 2 ? 'bg-blue-100 dark:bg-indigo-700/50 shadow-sm ring-1 ring-blue-200/50 dark:ring-indigo-500/30' : 'bg-gray-200/80 dark:bg-gray-600/50')">
            <div class="relative w-5 h-5 sm:w-6 sm:h-6">
                <!-- Original Icon -->
                <svg x-show="currentStep <= 2" 
                     x-transition:leave="transition duration-300 ease-in" 
                     x-transition:leave-end="opacity-0 scale-50 -rotate-45"
                     class="absolute inset-0 w-full h-full" :class="currentStep === 2 ? '!text-white' : '!text-gray-600 dark:!text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <!-- Checkmark Icon -->
                <svg x-show="currentStep > 2" x-cloak
                     x-transition:enter="transition duration-500 ease-out delay-150" 
                     x-transition:enter-start="opacity-0 scale-50 rotate-45" 
                     x-transition:enter-end="opacity-100 scale-100 rotate-0"
                     class="absolute inset-0 w-full h-full text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <div class="relative flex-1 min-w-0 z-10 transition-colors duration-300" :class="currentStep === 2 ? '!text-white' : (currentStep > 2 ? 'text-blue-800 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300')">
            <div class="font-semibold text-xs sm:text-sm tracking-tight">Peribadi</div>
        </div>
        <svg class="relative z-10 w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 transition-transform duration-500" :class="currentStep === 2 ? '!text-white translate-x-1 opacity-100' : (currentStep > 2 ? 'text-blue-500 dark:text-blue-400 opacity-100 translate-x-1' : 'text-gray-600 dark:text-gray-400 opacity-70 group-hover:translate-x-1 group-hover:opacity-100')" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    </div>

    <!-- Step 3: Maklumat Alamat -->
    <div @click="currentStep = 3"
         :class="currentStep === 3 ? 'bg-blue-700 bg-gradient-to-br from-blue-700 via-indigo-600 to-sky-700 !text-white border-blue-600/90 dark:border-indigo-500 shadow-xl shadow-blue-900/30 ring-2 ring-white/20 transform scale-[1.02]' : (currentStep > 3 ? 'bg-white dark:bg-gray-800 border-blue-300 dark:border-indigo-600/80 text-gray-900 dark:text-white hover:bg-blue-50/90 dark:hover:bg-indigo-900/30 hover:border-blue-400/80' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:border-gray-400 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50')"
         class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-2xl border-2 cursor-pointer transition-all duration-500 ease-out flex-1 min-w-0 group relative overflow-hidden">
        
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50/90 via-indigo-50/80 to-sky-50/70 dark:from-blue-900/25 dark:via-indigo-900/20 dark:to-sky-900/15 transform origin-left transition-transform duration-500 ease-out"
             :class="currentStep > 3 ? 'scale-x-100' : 'scale-x-0'"></div>

        <div class="relative flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center transition-all duration-500"
             :class="currentStep === 3 ? 'bg-white/20 backdrop-blur-sm shadow-inner ring-1 ring-white/40' : (currentStep > 3 ? 'bg-blue-100 dark:bg-indigo-700/50 shadow-sm ring-1 ring-blue-200/50 dark:ring-indigo-500/30' : 'bg-gray-200/80 dark:bg-gray-600/50')">
            <div class="relative w-5 h-5 sm:w-6 sm:h-6">
                <!-- Original Icon -->
                <svg x-show="currentStep <= 3" 
                     x-transition:leave="transition duration-300 ease-in" 
                     x-transition:leave-end="opacity-0 scale-50 -rotate-45"
                     class="absolute inset-0 w-full h-full" :class="currentStep === 3 ? '!text-white' : '!text-gray-600 dark:!text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <!-- Checkmark Icon -->
                <svg x-show="currentStep > 3" x-cloak
                     x-transition:enter="transition duration-500 ease-out delay-150" 
                     x-transition:enter-start="opacity-0 scale-50 rotate-45" 
                     x-transition:enter-end="opacity-100 scale-100 rotate-0"
                     class="absolute inset-0 w-full h-full text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <div class="relative flex-1 min-w-0 z-10 transition-colors duration-300" :class="currentStep === 3 ? '!text-white' : (currentStep > 3 ? 'text-blue-800 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300')">
            <div class="font-semibold text-xs sm:text-sm tracking-tight">Alamat</div>
        </div>
        <svg class="relative z-10 w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 transition-transform duration-500" :class="currentStep === 3 ? '!text-white translate-x-1 opacity-100' : (currentStep > 3 ? 'text-blue-500 dark:text-blue-400 opacity-100 translate-x-1' : 'text-gray-600 dark:text-gray-400 opacity-70 group-hover:translate-x-1 group-hover:opacity-100')" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    </div>

    <!-- Step 4: Maklumat Pembayaran -->
    <div @click="currentStep = 4"
         :class="currentStep === 4 ? 'bg-blue-700 bg-gradient-to-br from-blue-700 via-indigo-600 to-sky-700 !text-white border-blue-600/90 dark:border-indigo-500 shadow-xl shadow-blue-900/30 ring-2 ring-white/20 transform scale-[1.02]' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:border-gray-400 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50'"
         class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-2xl border-2 cursor-pointer transition-all duration-500 ease-out flex-1 min-w-0 group relative overflow-hidden">
        
        <div class="relative flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center transition-all duration-500"
             :class="currentStep === 4 ? 'bg-white/20 backdrop-blur-sm shadow-inner ring-1 ring-white/40' : 'bg-gray-200/80 dark:bg-gray-600/50'">
            <div class="relative w-5 h-5 sm:w-6 sm:h-6">
                <svg class="absolute inset-0 w-full h-full transition-colors duration-300" :class="currentStep === 4 ? '!text-white' : '!text-gray-600 dark:!text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
        </div>
        <div class="relative flex-1 min-w-0 z-10 transition-colors duration-300" :class="currentStep === 4 ? '!text-white' : 'text-gray-700 dark:text-gray-300'">
            <div class="font-semibold text-xs sm:text-sm tracking-tight">Pembayaran</div>
        </div>
    </div>
</div>
