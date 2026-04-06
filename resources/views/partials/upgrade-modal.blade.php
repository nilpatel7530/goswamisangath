<!-- Global Upgrade Modal -->
<div id="upgrade-modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-[#271d1c] text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-200 dark:border-white/10">
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-outlined text-primary">workspace_premium</span>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-slate-900 dark:text-white" id="modal-title">Premium Feature</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-600 dark:text-gray-400">You cannot send interest with a Free Plan. Upgrade your membership to connect with other members.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-black/20 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <a href="{{ route('membership') }}" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-dark sm:w-auto transition-colors">Upgrade Now</a>
                    <button onclick="document.getElementById('upgrade-modal').classList.add('hidden')" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-surface-dark px-3 py-2 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/5 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
