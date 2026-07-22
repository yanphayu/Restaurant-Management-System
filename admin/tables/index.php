<?php
$pageTitle = 'Floor Plan & Tables';
$currentPage = 'tables';
$pageDepth = 2;

include __DIR__ . '/../layouts/header.php';
?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-headline-md font-headline-md text-on-surface">Floor Plan & Tables</h1>
                    <button id="addTableBtn" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-xl font-semibold text-sm hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined text-lg">add</span> Add New Table
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between"><span class="text-on-surface-variant font-body-sm">Total Capacity</span><span class="material-symbols-outlined text-primary">groups</span></div><span id="statCapacity" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between"><span class="text-on-surface-variant font-body-sm">Occupied Now</span><span class="material-symbols-outlined text-error">event_busy</span></div><span id="statOccupied" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between"><span class="text-on-surface-variant font-body-sm">Reserved Tonight</span><span class="material-symbols-outlined text-tertiary">calendar_month</span></div><span id="statReserved" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between"><span class="text-on-surface-variant font-body-sm">Available Now</span><span class="material-symbols-outlined text-secondary">check_circle</span></div><span id="statAvailable" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container">
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Table ID</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Capacity</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Status</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="tablesTableBody" class="divide-y divide-outline-variant">
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant">Loading tables...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6">
    <div class="flex items-center gap-3 mb-4"><span class="material-symbols-outlined text-primary">map</span>
        <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface">Floor Map Preview</h3>
    </div>
    <div id="floorMap" class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[200px]">
        <div class="col-span-full flex items-center justify-center text-on-surface-variant text-sm">Loading floor map...</div>
    </div>
</div>

<!-- Create Table Modal -->
<div id="modal-table-create" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary">table_restaurant</span></div>
                <div>
                    <h2 class="text-headline-md font-headline-md font-bold text-on-surface">Add Table</h2>
                    <p class="text-label-caps font-label-caps text-on-surface-variant">Create a new table</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="tableCreateForm" class="p-6 space-y-5">
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Table Name</label>
                <input type="text" id="createTableName" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="e.g. Table 1">
            </div>
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Capacity</label>
                <input type="number" id="createTableCapacity" min="1" max="50" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Number of seats">
            </div>
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Status</label>
                <select id="createTableStatus" class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Reserved">Reserved</option>
                </select>
            </div>
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant">
                <button type="button" data-action="close-modal" class="py-2.5 px-6 bg-surface-container-high text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-highest transition-colors">Cancel</button>
                <button type="submit" class="py-2.5 px-6 bg-primary text-on-primary rounded-lg font-semibold flex items-center gap-2 hover:opacity-90 transition-opacity"><span class="material-symbols-outlined text-xl">add</span> <span class="text-label-caps font-label-caps">Create Table</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Table Modal -->
<div id="modal-table-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary">table_restaurant</span></div>
                <div>
                    <h2 id="tableModalTitle" class="text-headline-md font-headline-md font-bold text-on-surface">Edit Table</h2>
                    <p id="tableModalSubtitle" class="text-label-caps font-label-caps text-on-surface-variant">Update table details</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div id="tableEditLoading" class="p-12 flex items-center justify-center"><span class="material-symbols-outlined animate-spin text-[32px] text-primary">progress_activity</span></div>
        <form id="tableEditForm" class="hidden p-6 space-y-5">
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Table Name</label>
                <input type="text" id="editTableName" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="e.g. Table 1">
            </div>
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Capacity</label>
                <input type="number" id="editTableCapacity" min="1" max="50" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Number of seats">
            </div>
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Status</label>
                <select id="editTableStatus" class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Reserved">Reserved</option>
                </select>
            </div>
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-3">Live Preview</label>
                <div class="p-5 bg-surface-container-low rounded-xl border border-outline-variant flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-primary-container flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-on-primary text-[28px]">table_restaurant</span></div>
                    <div class="flex-1 min-w-0">
                        <p id="editTablePreviewName" class="text-body-md font-semibold text-on-surface">Table 1</p>
                        <div class="flex items-center gap-3 mt-1"><span class="flex items-center gap-1 text-on-surface-variant text-body-sm"><span class="material-symbols-outlined text-base">group</span> <span id="editTablePreviewCap">2</span> seats</span>
                            <span id="editTablePreviewStatus" class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">Available</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tableEditFooter" class="hidden flex items-center justify-end gap-3 pt-4 border-t border-outline-variant">
                <button type="button" data-action="close-modal" class="py-2.5 px-6 bg-surface-container-high text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-highest transition-colors">Cancel</button>
                <button type="submit" class="py-2.5 px-6 bg-primary text-on-primary rounded-lg font-semibold flex items-center gap-2 hover:opacity-90 transition-opacity"><span class="material-symbols-outlined text-xl">check</span> <span class="text-label-caps font-label-caps">Confirm Update</span></button>
            </div>
        </form>
    </div>
</div>

<script src="<?= $prefix ?>assets/js/table.js"></script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>