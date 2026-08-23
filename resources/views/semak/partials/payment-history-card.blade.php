{{--
    Payment history — server-side DataTable, scoped to the checked member's own no_kp.
    Expects (inherited from the including view's scope): $checkedNoKp (used by the DataTable
    init script in result.blade.php's @push('scripts') via route('semak.payments.data')).
    Only one copy of this partial renders per page load (branches are mutually exclusive), so
    the fixed element IDs below are always unique on the page.
--}}
<div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2.5">
        <span class="w-1 h-4 rounded-full bg-indigo-500 shrink-0"></span>
        <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Sejarah Pembayaran</h3>
    </div>

    {{-- Desktop table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table id="semak-payments-table" class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/70 dark:bg-slate-900/30">
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tahun</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Jumlah</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Jenis</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Status</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Resit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50"></tbody>
        </table>
    </div>

    {{-- Mobile list, rendered from the same DataTable page --}}
    <div id="semak-payments-mobile" class="block sm:hidden divide-y divide-slate-100 dark:divide-slate-700/50"></div>
</div>
