<?php
$pageTitle = 'Food Inventory';
$currentPage = 'foods';
$pageDepth = 2;

include __DIR__ . '/../layouts/header.php';
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-headline-md font-headline-md text-on-surface">Food Inventory</h1>
    <button data-action="open-modal" data-target="modal-food-create" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-xl font-semibold text-sm hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined text-lg">add</span>
        Add New Food
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between">
            <span class="text-on-surface-variant font-body-sm">Total Items</span>
            <span class="material-symbols-outlined text-primary">inventory_2</span>
        </div>
        <span id="statTotalItems" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between">
            <span class="text-on-surface-variant font-body-sm">Out of Stock</span>
            <span class="material-symbols-outlined text-error">block</span>
        </div>
        <span id="statOutOfStock" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between">
            <span class="text-on-surface-variant font-body-sm">Avg Price</span>
            <span class="material-symbols-outlined text-tertiary">paid</span>
        </div>
        <span id="statAvgPrice" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col gap-1">
        <div class="flex items-center justify-between">
            <span class="text-on-surface-variant font-body-sm">Top Category</span>
            <span class="material-symbols-outlined text-secondary">star</span>
        </div>
        <span id="statTopCategory" class="text-headline-md font-headline-md font-bold text-on-surface">--</span>
    </div>
</div>

<!-- Foods Table -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container">
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Image</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Food</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Category</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Price</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm">Status</th>
                    <th class="px-6 py-4 font-semibold text-on-surface-variant text-sm text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="foodsTableBody" class="divide-y divide-outline-variant">
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">Loading foods...</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-6 py-4 border-t border-outline-variant bg-surface-container">
        <span id="paginationInfo" class="text-sm text-on-surface-variant">Showing 0 items</span>
        <div id="paginationControls" class="flex items-center gap-1"></div>
    </div>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Create Food Modal -->
<div id="modal-food-create" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary">restaurant</span>
                </div>
                <div>
                    <h2 class="text-headline-md font-headline-md font-bold text-on-surface">Add Food Item</h2>
                    <p class="text-label-caps font-label-caps text-on-surface-variant">Create a new menu item</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="foodCreateForm" class="p-6 space-y-5">
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Food Name</label>
                <input type="text" id="createFoodName" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Enter food name">
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Category</label>
                <select id="createFoodCategory" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    <option value="">Select a category</option>
                </select>
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Price ($)</label>
                <input type="number" id="createFoodPrice" step="0.01" min="0" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="0.00">
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Status</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="createFoodStatus" value="available" checked class="accent-primary w-4 h-4">
                        <span class="text-body-md text-on-surface">Available</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="createFoodStatus" value="unavailable" class="accent-primary w-4 h-4">
                        <span class="text-body-md text-on-surface">Unavailable</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Description</label>
                <textarea id="createFoodDescription" rows="3" class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant resize-none focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Describe the food item..."></textarea>
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Image</label>
                <div id="createImageUploadArea" class="border-2 border-dashed border-outline-variant rounded-xl p-8 text-center cursor-pointer hover:border-primary transition-colors">
                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant mb-2">cloud_upload</span>
                    <p class="text-body-md text-on-surface-variant">Click to upload or drag and drop</p>
                    <p class="text-body-sm text-on-surface-variant mt-1">PNG, JPG up to 5MB</p>
                </div>
                <input type="file" id="createFoodImage" accept="image/*" style="position:absolute;left:-9999px;">
                <div id="createImagePreview" class="hidden mt-3 relative">
                    <img id="createPreviewImg" class="w-full h-40 object-cover rounded-lg" alt="Preview">
                    <button type="button" data-action="clear-create-image" class="absolute top-2 right-2 w-7 h-7 rounded-full bg-error flex items-center justify-center text-on-error">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant">
                <button type="button" data-action="close-modal" class="py-2.5 px-6 bg-surface-container-high text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-highest transition-colors">Discard</button>
                <button type="submit" class="py-2.5 px-6 bg-primary text-on-primary rounded-lg font-semibold flex items-center gap-2 hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-xl">add</span>
                    <span class="text-label-caps font-label-caps">Create Item</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Food Modal -->
<div id="modal-food-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary">edit</span>
                </div>
                <div>
                    <h2 class="text-headline-md font-headline-md font-bold text-on-surface">Edit Food Item</h2>
                    <p class="text-label-caps font-label-caps text-on-surface-variant">Update menu item details</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div id="foodEditLoading" class="p-12 flex items-center justify-center">
            <span class="material-symbols-outlined animate-spin text-[32px] text-primary">progress_activity</span>
        </div>
        <form id="foodEditForm" class="p-6 space-y-5 hidden">
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Food Name</label>
                <input type="text" id="editFoodName" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Enter food name">
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Category</label>
                <select id="editFoodCategory" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    <option value="">Select a category</option>
                </select>
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Price ($)</label>
                <input type="number" id="editFoodPrice" step="0.01" min="0" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="0.00">
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Status</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="editFoodStatus" value="available" checked class="accent-primary w-4 h-4">
                        <span class="text-body-md text-on-surface">Available</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="editFoodStatus" value="unavailable" class="accent-primary w-4 h-4">
                        <span class="text-body-md text-on-surface">Unavailable</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Description</label>
                <textarea id="editFoodDescription" rows="3" class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant resize-none focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Describe the food item..."></textarea>
            </div>
            <div>
                <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Image</label>
                <div id="editImageUploadArea" class="border-2 border-dashed border-outline-variant rounded-xl p-8 text-center cursor-pointer hover:border-primary transition-colors">
                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant mb-2">cloud_upload</span>
                    <p class="text-body-md text-on-surface-variant">Click to upload or drag and drop</p>
                    <p class="text-body-sm text-on-surface-variant mt-1">PNG, JPG up to 5MB</p>
                </div>
                <input type="file" id="editFoodImage" accept="image/*" style="position:absolute;left:-9999px;">
                <div id="editImagePreview" class="hidden mt-3 relative">
                    <img id="editPreviewImg" class="w-full h-40 object-cover rounded-lg" alt="Preview">
                    <button type="button" data-action="clear-edit-image" class="absolute top-2 right-2 w-7 h-7 rounded-full bg-error flex items-center justify-center text-on-error">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>
            </div>
            <div id="foodEditFooter" class="hidden flex items-center justify-end gap-3 pt-4 border-t border-outline-variant">
                <button type="button" data-action="close-modal" class="py-2.5 px-6 bg-surface-container-high text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-highest transition-colors">Discard</button>
                <button type="submit" class="py-2.5 px-6 bg-primary text-on-primary rounded-lg font-semibold flex items-center gap-2 hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-xl">save</span>
                    <span class="text-label-caps font-label-caps">Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== END MODALS ==================== -->

<!-- View Food Modal -->
<div id="modal-food-view" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-action="close-modal-backdrop"></div>
    <div class="relative bg-surface rounded-2xl border border-outline-variant w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl modal-content modal-enter">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary">visibility</span>
                </div>
                <div>
                    <h2 class="text-headline-md font-headline-md font-bold text-on-surface">Food Details</h2>
                    <p class="text-label-caps font-label-caps text-on-surface-variant">View food item information</p>
                </div>
            </div>
            <button data-action="close-modal" class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div id="foodViewLoading" class="p-12 flex items-center justify-center">
            <span class="material-symbols-outlined animate-spin text-[32px] text-primary">progress_activity</span>
        </div>
        <div id="foodViewContent" class="hidden">
            <div class="p-6">
                <div id="viewFoodNoImage" class="w-full h-48 rounded-xl bg-surface-container-high flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-[48px] text-on-surface-variant">image</span>
                </div>
                <img id="viewFoodImage" class="w-full h-48 rounded-xl object-cover mb-4 hidden" alt="Food Image">
                <div class="space-y-4">
                    <div>
                        <p class="text-label-caps font-label-caps text-on-surface-variant mb-1">Food Name</p>
                        <p id="viewFoodName" class="text-headline-sm font-headline-sm font-bold text-on-surface">-</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-label-caps font-label-caps text-on-surface-variant mb-1">Category</p>
                            <p id="viewFoodCategory" class="text-body-md text-on-surface">-</p>
                        </div>
                        <div>
                            <p class="text-label-caps font-label-caps text-on-surface-variant mb-1">Price</p>
                            <p id="viewFoodPrice" class="text-body-md font-semibold text-on-surface">-</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-label-caps font-label-caps text-on-surface-variant mb-1">Status</p>
                        <div id="viewFoodStatus">-</div>
                    </div>
                    <div>
                        <p class="text-label-caps font-label-caps text-on-surface-variant mb-1">Description</p>
                        <p id="viewFoodDesc" class="text-body-md text-on-surface-variant leading-relaxed">-</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end p-6 border-t border-outline-variant">
                <button type="button" data-action="close-modal" class="py-2.5 px-6 bg-surface-container-high text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-highest transition-colors">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<script src="../../assets/js/food.js"></script>

<script>
    $('#foodCreateForm').on('submit', function(e) {
        e.preventDefault();
        createFood();
    });

    $('#foodEditForm').on('submit', function(e) {
        e.preventDefault();
        saveFood();
    });

    $('#createImageUploadArea').on('click', function() {
        $('#createFoodImage').trigger('click');
    });
    $('#createFoodImage').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                $('#createPreviewImg').attr('src', ev.target.result);
                $('#createImagePreview').removeClass('hidden');
                $('#createImageUploadArea').addClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#editImageUploadArea').on('click', function() {
        $('#editFoodImage').trigger('click');
    });
    $('#editFoodImage').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                $('#editPreviewImg').attr('src', ev.target.result);
                $('#editImagePreview').removeClass('hidden');
                $('#editImageUploadArea').addClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $(document).on('click', '[data-action="clear-create-image"]', function() {
        $('#createFoodImage').val('');
        $('#createImagePreview').addClass('hidden');
        $('#createImageUploadArea').removeClass('hidden');
    });

    $(document).on('click', '[data-action="clear-edit-image"]', function() {
        $('#editFoodImage').val('');
        $('#editImagePreview').addClass('hidden');
        $('#editImageUploadArea').removeClass('hidden');
    });
</script>