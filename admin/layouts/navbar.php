<!-- Top Navbar -->
<header class="sticky top-0 z-20 bg-surface/80 backdrop-blur-xl border-b border-outline-variant/50">
    <div class="flex items-center justify-between px-6 py-3">
        <!-- Search -->
        <div class="relative w-80" id="globalSearch">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xl">search</span>
            <input
                type="text"
                id="globalSearchInput"
                placeholder="Search orders, foods, tables..."
                class="w-full pl-10 pr-4 py-2 bg-surface-container border border-outline-variant/50 rounded-lg text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                autocomplete="off"
            >
            <div id="searchDropdown" class="hidden absolute top-full left-0 right-0 mt-2 bg-surface-container-lowest rounded-xl border border-outline-variant/50 shadow-xl z-50 max-h-80 overflow-y-auto custom-scrollbar"></div>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-2">
            <!-- Settings -->
            <button class="p-2 rounded-lg hover:bg-surface-container-highest transition-colors" title="Settings">
                <span class="material-symbols-outlined text-on-surface-variant text-xl">settings</span>
            </button>
            <!-- Logout -->
            <button id="logoutBtn" data-action="logout" class="p-2 rounded-lg hover:bg-surface-container-highest transition-colors" title="Logout">
                <span class="material-symbols-outlined text-on-surface-variant text-xl">logout</span>
            </button>
            <!-- User Avatar -->
            <div class="flex items-center gap-2 ml-2 pl-2 border-l border-outline-variant/50">
                <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center">
                    <span class="text-on-primary-container font-semibold text-body-sm">A</span>
                </div>
                <div class="hidden sm:block">
                    <p class="text-body-sm font-semibold text-on-surface leading-tight">Admin</p>
                    <p class="text-xs text-on-surface-variant leading-tight">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</header>
