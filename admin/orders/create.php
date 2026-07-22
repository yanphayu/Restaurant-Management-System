<?php
$pageTitle = 'Create Order';
$currentPage = 'orders';
$currentAction = 'create';
$pageDepth = 2;

include __DIR__ . '/../layouts/header.php';
?>

<!-- Breadcrumbs -->
<div class="flex items-center gap-2 text-body-sm text-on-surface-variant mb-6">
    <a href="<?= $prefix ?>admin/orders/index.php" class="hover:text-primary transition-colors">Orders</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <span class="text-on-surface font-semibold">Create New Order</span>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-12 gap-6">
    <!-- Left: Food Selection -->
    <div class="col-span-12 lg:col-span-7">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-6">
            <h2 class="text-display-md font-headline-md font-bold text-on-surface mb-4">Add Food Items</h2>

            <div class="relative mb-4">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xl">search</span>
                <input id="foodSearch" type="text" placeholder="Search food items..." class="w-full pl-10 pr-4 py-3 bg-surface-container border border-outline-variant/50 rounded-lg text-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
            </div>

            <div id="categoryChips" class="flex flex-wrap gap-2 mb-4">
                <button class="category-chip active px-4 py-1.5 rounded-full text-label-caps font-semibold bg-primary text-on-primary transition-colors" data-id="all">All</button>
            </div>

            <div id="foodResults" class="flex flex-col gap-3 max-h-[480px] overflow-y-auto pr-2">
                <div class="flex items-center justify-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl mr-3">restaurant_menu</span>
                    <p class="text-body-md">Search for food items to add</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Order Summary -->
    <div class="col-span-12 lg:col-span-5">
        <form id="orderForm" class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-6 sticky top-24">
            <h2 class="text-display-md font-headline-md font-bold text-on-surface mb-4">Current Selection</h2>

            <div class="mb-4">
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Select Table</label>
                <select id="tableSelect" required class="w-full py-3 px-4 bg-surface-container border border-outline-variant/50 rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    <option value="">Choose a table...</option>
                </select>
            </div>

            <div class="border-b border-outline-variant/50 mb-4"></div>

            <div id="orderItems" class="flex flex-col gap-3 max-h-[280px] overflow-y-auto pr-2 mb-4">
                <p class="text-on-surface-variant text-body-sm text-center py-8">No items added yet</p>
            </div>

            <div class="border-b border-outline-variant/50 mb-4"></div>

            <div class="space-y-2 mb-6">
                <div class="flex justify-between text-body-md text-on-surface-variant">
                    <span>Subtotal</span>
                    <span id="subtotal">$0.00</span>
                </div>
                <div class="flex justify-between text-body-md text-on-surface-variant">
                    <span>Tax (10%)</span>
                    <span id="tax">$0.00</span>
                </div>
                <div class="flex justify-between text-body-lg font-bold text-on-surface">
                    <span>Total</span>
                    <span id="total">$0.00</span>
                </div>
            </div>

            <button id="submitOrderBtn" type="submit" class="w-full py-3 px-4 bg-primary text-on-primary rounded-xl font-semibold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <span class="material-symbols-outlined">send</span>
                Submit Order
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<script>
    $('#orderForm').on('submit', function(e) {
        e.preventDefault();
        submitOrder();
    });
</script>