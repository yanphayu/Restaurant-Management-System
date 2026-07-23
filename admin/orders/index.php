<?php
$pageTitle = 'Order Management';
$currentPage = 'orders';
$pageDepth = 2;

include __DIR__ . '/../layouts/header.php';
?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-headline-md font-headline-md text-on-surface">Order Management</h1>
    <a href="<?= $prefix ?>admin/orders/create.php" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-xl font-semibold text-sm hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined text-lg">add</span> Create New Order
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between"><span class="text-on-surface-variant font-body-sm">Active Orders</span><span class="material-symbols-outlined text-primary">pending_actions</span></div><span id="statActive" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between"><span class="text-on-surface-variant font-body-sm">Preparing</span><span class="material-symbols-outlined text-tertiary">skillet</span></div><span id="statPreparing" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between"><span class="text-on-surface-variant font-body-sm">Daily Revenue</span><span class="material-symbols-outlined text-secondary">attach_money</span></div><span id="statRevenue" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container">
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Order ID</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Customer / Table</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Items</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Total Amount</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Status</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody" class="divide-y divide-outline-variant">
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">Loading orders...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-2"><span class="material-symbols-outlined text-primary">timer</span>
            <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface">Avg Prep Time</h3>
        </div><span id="insightPrepTime" class="text-on-surface-variant text-sm">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-2"><span class="material-symbols-outlined text-tertiary">trending_up</span>
            <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface">Popular Category</h3>
        </div><span id="insightPopular" class="text-on-surface-variant text-sm">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-2"><span class="material-symbols-outlined text-secondary">badge</span>
            <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface">Staff on Shift</h3>
        </div><span id="insightStaff" class="text-on-surface-variant text-sm">--</span>
    </div>
</div>

<!-- Order Detail Modal -->
<div id="modal-order-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary">receipt_long</span></div>
                <div>
                    <h2 id="modalOrderTitle" class="text-headline-md font-headline-md font-bold text-on-surface">Order Details</h2>
                    <p class="text-label-caps font-label-caps text-on-surface-variant">View order information</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div id="orderDetailContent" class="p-6">
            <div class="flex items-center justify-center py-12"><span class="material-symbols-outlined animate-spin text-[32px] text-primary">progress_activity</span></div>
        </div>
        <div id="orderDetailFooter" class="hidden flex items-center justify-between p-6 border-t border-outline-variant">
            <button data-action="close-modal" class="py-2.5 px-5 bg-surface-container-high text-on-surface-variant rounded-xl font-semibold flex items-center gap-2 hover:bg-surface-container-highest transition-colors"><span class="material-symbols-outlined text-xl">arrow_back</span> <span class="text-label-caps font-label-caps">Back</span></button>
            <div class="flex items-center gap-3">
                <button data-action="print-receipt" class="py-2.5 px-5 bg-surface-container-high text-on-surface-variant rounded-xl font-semibold flex items-center gap-2 hover:bg-surface-container-highest transition-colors"><span class="material-symbols-outlined text-xl">print</span> <span class="text-label-caps font-label-caps">Print Receipt</span></button>
                <button id="reorderBtn" class="py-2.5 px-5 bg-primary text-on-primary rounded-xl font-semibold flex items-center gap-2 hover:opacity-90 transition-opacity"><span class="material-symbols-outlined text-xl">replay</span> <span class="text-label-caps font-label-caps">Reorder</span></button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
<script src="<?= $prefix ?>assets/js/orders.js"></script>