<!-- Sidebar -->
<aside class="w-[280px] min-h-screen bg-surface-container-lowest border-r border-outline-variant/50 flex flex-col fixed top-0 left-0 h-full z-30">
    <!-- Brand -->
    <div class="px-6 py-5 border-b border-outline-variant/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center">
                <span class="material-symbols-outlined text-on-primary-container">restaurant_menu</span>
            </div>
            <div>
                <h1 class="text-title-sm font-title-sm text-on-surface">KitchenFlow</h1>
                <p class="text-body-sm text-on-surface-variant">Management System</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto custom-scrollbar">
        <a href="<?= $prefix ?>admin/dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $currentPage === 'dashboard' ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-highest hover:text-on-surface' ?> text-body-md transition-colors">
            <span class="material-symbols-outlined text-xl">dashboard</span>
            Dashboard
        </a>
        <a href="<?= $prefix ?>admin/categories/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $currentPage === 'categories' ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-highest hover:text-on-surface' ?> text-body-md transition-colors">
            <span class="material-symbols-outlined text-xl">category</span>
            Categories
        </a>
        <a href="<?= $prefix ?>admin/foods/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $currentPage === 'foods' ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-highest hover:text-on-surface' ?> text-body-md transition-colors">
            <span class="material-symbols-outlined text-xl">restaurant</span>
            Foods
        </a>
        <a href="<?= $prefix ?>admin/tables/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $currentPage === 'tables' ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-highest hover:text-on-surface' ?> text-body-md transition-colors">
            <span class="material-symbols-outlined text-xl">table_restaurant</span>
            Tables
        </a>
        <a href="<?= $prefix ?>admin/orders/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $currentPage === 'orders' ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-highest hover:text-on-surface' ?> text-body-md transition-colors">
            <span class="material-symbols-outlined text-xl">receipt_long</span>
            Orders
        </a>
    </nav>

    <!-- Quick Order Button -->
    <div class="px-3 pb-4">
        <a href="<?= $prefix ?>admin/orders/create.php" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-primary text-on-primary rounded-xl font-semibold text-body-md hover:bg-primary-container transition-colors active:scale-[0.98]">
            <span class="material-symbols-outlined text-xl">bolt</span>
            Quick Order
        </a>
    </div>
</aside>
