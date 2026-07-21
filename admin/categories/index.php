<?php
$pageTitle = 'Categories';
$currentPage = 'categories';
$pageDepth = 2;

include __DIR__ . '/../layouts/header.php';
?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-headline-md font-headline-md text-on-surface">Categories Management</h1>
    <button data-action="open-modal" data-target="modal-category-create" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-xl font-semibold text-sm hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined text-lg">add</span> Add New Category
    </button>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container">
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">ID</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Category Name</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Created At</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="categoriesTableBody" class="divide-y divide-outline-variant">
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant">Loading categories...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-3"><span class="material-symbols-outlined text-primary">speed</span>
            <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface">Performance Summary</h3>
        </div>
        <div id="performanceSummary" class="text-sm text-on-surface-variant leading-relaxed">Loading performance data...</div>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-3"><span class="material-symbols-outlined text-tertiary">tips_and_updates</span>
            <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface">Optimization Tip</h3>
        </div>
        <p class="text-sm text-on-surface-variant leading-relaxed">Keep your menu organized by regularly reviewing and archiving unused categories. Grouping similar items improves kitchen workflow and speeds up order preparation. Aim for 5–12 categories to keep navigation intuitive for both staff and customers.</p>
    </div>
</div>

<!-- Create Category Modal -->
<div id="modal-category-create" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary">category</span></div>
                <div>
                    <h2 class="text-headline-md font-headline-md font-bold text-on-surface">Create Category</h2>
                    <p class="text-label-caps font-label-caps text-on-surface-variant">Add a new food category</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="categoryCreateForm" class="p-6 space-y-5">
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Category Name</label>
                <input type="text" id="createCatName" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Enter category name">
            </div>
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-3">Icon</label>
                <div id="createIconGrid" class="grid grid-cols-8 gap-2"></div>
                <input type="hidden" id="createSelectedIcon" value="restaurant">
            </div>
            <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Description</label>
                <textarea id="createCatDesc" rows="3" class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant resize-none focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Describe this category..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant">
                <button type="button" data-action="close-modal" class="py-2.5 px-6 bg-surface-container-high text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-highest transition-colors">Cancel</button>
                <button type="submit" class="py-2.5 px-6 bg-primary text-on-primary rounded-lg font-semibold flex items-center gap-2 hover:opacity-90 transition-opacity"><span class="material-symbols-outlined text-xl">add</span> <span class="text-label-caps font-label-caps">Create Category</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="modal-category-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary">edit</span></div>
                <div>
                    <h2 class="text-headline-md font-headline-md font-bold text-on-surface">Category Details</h2>
                    <p class="text-label-caps font-label-caps text-on-surface-variant">Edit category information</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div id="catEditLoading" class="p-12 flex items-center justify-center"><span class="material-symbols-outlined animate-spin text-[32px] text-primary">progress_activity</span></div>
        <form id="categoryEditForm" class="hidden">
            <div class="p-6 space-y-5">
                <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Category Name</label>
                    <input type="text" id="editCatName" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Enter category name">
                </div>
                <div><label class="text-label-caps font-label-caps text-on-surface-variant block mb-3">Preview</label>
                    <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                        <div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center"><span id="editCatPreviewIcon" class="material-symbols-outlined text-on-primary">restaurant</span></div>
                        <div>
                            <p id="editCatPreviewName" class="text-body-md font-semibold text-on-surface">Category Name</p>
                            <p class="text-body-sm text-on-surface-variant">Food Category</p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="catEditFooter" class="hidden flex items-center justify-end gap-3 p-6 border-t border-outline-variant">
                <button type="button" data-action="close-modal" class="py-2.5 px-6 bg-surface-container-high text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-highest transition-colors">Cancel</button>
                <button type="submit" class="py-2.5 px-6 bg-primary text-on-primary rounded-lg font-semibold flex items-center gap-2 hover:opacity-90 transition-opacity"><span class="material-symbols-outlined text-xl">check</span> <span class="text-label-caps font-label-caps">Confirm</span></button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<script>
    $('#categoryCreateForm').on('submit', function(e) {
        e.preventDefault();
        createCategory();
    });

    $('#categoryEditForm').on('submit', function(e) {
        e.preventDefault();
        saveCategory();
    });
</script>