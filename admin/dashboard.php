<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
$pageDepth = 1;


include __DIR__ . '/layouts/header.php';
?>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-headline-md font-headline-md text-on-surface">Dashboard</h1>
    <p class="text-body-md text-on-surface-variant mt-1">Welcome back! Here's what's happening today.</p>
</div>

<!-- Metrics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-primary-container/60 flex items-center justify-center">
                <span class="material-symbols-outlined text-on-primary-container text-xl">receipt_long</span>
            </div>
            <span class="text-body-sm text-tertiary font-semibold bg-tertiary-container/20 px-2 py-0.5 rounded-full flex items-center gap-0.5">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                12%
            </span>
        </div>
        <p class="text-display-lg font-display-lg text-on-surface" id="metricTotalOrders">0</p>
        <p class="text-body-sm text-on-surface-variant mt-1">Total Orders</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-tertiary-container/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary text-xl">payments</span>
            </div>
            <span class="text-body-sm text-tertiary font-semibold bg-tertiary-container/20 px-2 py-0.5 rounded-full flex items-center gap-0.5">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                8%
            </span>
        </div>
        <p class="text-display-lg font-display-lg text-on-surface" id="metricTotalRevenue">$0</p>
        <p class="text-body-sm text-on-surface-variant mt-1">Total Revenue</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-secondary-fixed/60 flex items-center justify-center">
                <span class="material-symbols-outlined text-on-secondary-container text-xl">table_restaurant</span>
            </div>
            <span class="text-body-sm text-on-surface-variant font-semibold bg-surface-container-high px-2 py-0.5 rounded-full">Active</span>
        </div>
        <p class="text-display-lg font-display-lg text-on-surface" id="metricActiveTables">0</p>
        <p class="text-body-sm text-on-surface-variant mt-1">Active Tables</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-primary-container/60 flex items-center justify-center">
                <span class="material-symbols-outlined text-on-primary-container text-xl">star</span>
            </div>
            <span class="text-body-sm text-on-surface-variant font-semibold bg-surface-container-high px-2 py-0.5 rounded-full">Popular</span>
        </div>
        <p class="text-headline-md font-headline-md text-on-surface truncate" id="metricPopularFood">--</p>
        <p class="text-body-sm text-on-surface-variant mt-1">Most Popular Food</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-5">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-title-sm font-title-sm text-on-surface">Quick Summary</h2>
            <select class="text-body-sm bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-1.5 text-on-surface-variant outline-none focus:border-primary cursor-pointer">
                <option>This Week</option>
                <option>This Month</option>
                <option>This Year</option>
            </select>
        </div>
        <div class="flex items-end gap-3 h-48 px-2" id="barChart">
            <?php
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            foreach ($days as $day):
            ?>
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-primary/20 rounded-t-lg relative group">
                        <div class="absolute bottom-0 left-0 right-0 bg-primary rounded-t-lg transition-all duration-700" style="height: 0%" data-height="0%"></div>
                    </div>
                    <span class="text-xs text-on-surface-variant"><?= $day ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-5">
        <h2 class="text-title-sm font-title-sm text-on-surface mb-4">Top Performance</h2>
        <div class="space-y-3" id="topPerformance">
            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container">
                <div class="w-10 h-10 rounded-lg bg-primary-container/40 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-primary-container text-lg">restaurant</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-body-md font-semibold text-on-surface truncate">--</p>
                    <p class="text-body-sm text-on-surface-variant">-- orders</p>
                </div>
                <span class="text-body-sm font-semibold text-primary font-data-mono">--</span>
            </div>
            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container">
                <div class="w-10 h-10 rounded-lg bg-tertiary-container/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-tertiary text-lg">restaurant</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-body-md font-semibold text-on-surface truncate">--</p>
                    <p class="text-body-sm text-on-surface-variant">-- orders</p>
                </div>
                <span class="text-body-sm font-semibold text-primary font-data-mono">--</span>
            </div>
            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container">
                <div class="w-10 h-10 rounded-lg bg-secondary-fixed/40 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-secondary-container text-lg">restaurant</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-body-md font-semibold text-on-surface truncate">--</p>
                    <p class="text-body-sm text-on-surface-variant">-- orders</p>
                </div>
                <span class="text-body-sm font-semibold text-primary font-data-mono">--</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 overflow-hidden">
    <div class="flex items-center justify-between p-5 border-b border-outline-variant/50">
        <h2 class="text-title-sm font-title-sm text-on-surface">Recent Orders</h2>
        <a href="<?= $prefix ?>admin/orders/index.php" class="text-body-sm text-primary hover:text-primary-container font-medium flex items-center gap-1 transition-colors">
            View All
            <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
    </div>
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full">
            <thead>
                <tr class="border-b border-outline-variant/50 bg-surface-container">
                    <th class="text-left px-5 py-3 text-label-caps font-label-caps text-on-surface-variant uppercase tracking-wider">Order ID</th>
                    <th class="text-left px-5 py-3 text-label-caps font-label-caps text-on-surface-variant uppercase tracking-wider">Table</th>
                    <th class="text-left px-5 py-3 text-label-caps font-label-caps text-on-surface-variant uppercase tracking-wider">Items</th>
                    <th class="text-left px-5 py-3 text-label-caps font-label-caps text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-label-caps font-label-caps text-on-surface-variant uppercase tracking-wider">Total</th>
                    <th class="text-left px-5 py-3 text-label-caps font-label-caps text-on-surface-variant uppercase tracking-wider">Time</th>
                </tr>
            </thead>
            <tbody id="recentOrdersBody" class="divide-y divide-outline-variant/30">
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <span class="material-symbols-outlined animate-spin text-primary text-3xl">progress_activity</span>
                        <p class="text-body-sm text-on-surface-variant mt-2">Loading orders...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>