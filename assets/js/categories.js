$(document).ready(function () {
    loadCategories();
    renderIconGrid('createIconGrid', 'createSelectedIcon');
    selectDefaultIcon('createIconGrid', 'createSelectedIcon');
});

var categoryIcons = [
    'restaurant', 'bakery_dining', 'ramen_dining', 'local_pizza', 'lunch_dining',
    'kebab_dining', 'tapas', 'local_bar', 'icecream', 'cake',
    'set_meal', 'dinner_dining', 'egg', 'cooking', 'breakfast_dining',
    'brunch_dining', 'outdoor_grill', 'chef', 'fastfood', 'soup_kitchen',
    'nutrition', 'egg_alt', 'flatware', 'liquor', 'wine_bar',
    'coffee', 'local_cafe', 'takeout_dining', 'delivery_dining', 'food_bank',
    'grains', 'mood', 'star', 'favorite', 'diamond',
    'sailing', 'forest', 'park', 'celebration', 'rocket_launch'
];

function renderIconGrid(containerId, inputId) {
    var container = $('#' + containerId);
    if (!container.length) return;
    container.empty();
    $.each(categoryIcons, function(i, icon) {
        var btn = $('<button>', {
            type: 'button',
            class: 'w-9 h-9 rounded-lg flex items-center justify-center border border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary transition-all',
            'data-icon': icon,
            html: '<span class="material-symbols-outlined text-lg">' + icon + '</span>'
        });
        btn.on('click', function() {
            container.find('button').removeClass('border-primary bg-primary-container text-primary');
            $(this).addClass('border-primary bg-primary-container text-primary');
            $('#' + inputId).val(icon);
        });
        container.append(btn);
    });
}

function selectDefaultIcon(containerId, inputId) {
    var defaultIcon = $('#' + inputId).val() || 'restaurant';
    var container = $('#' + containerId);
    container.find('button').removeClass('border-primary bg-primary-container text-primary');
    container.find('button[data-icon="' + defaultIcon + '"]').addClass('border-primary bg-primary-container text-primary');
}

function loadCategories() {

    $.ajax({
        url: '../../api/categories.php',
        type: 'GET',
        dataType: 'json',

        success: function(response) {

            var html = '';

            if (response.success && response.data.length > 0) {

                $.each(response.data, function(index, category) {

                    var icon = category.category_icon || 'restaurant';

                    html += '<tr>' +
                        '<td class="px-6 py-4">' + category.category_id + '</td>' +
                        '<td class="px-6 py-4">' +
                        '<div class="w-9 h-9 rounded-lg bg-primary-container flex items-center justify-center">' +
                        '<span class="material-symbols-outlined text-on-primary text-lg">' + icon + '</span>' +
                        '</div>' +
                        '</td>' +
                        '<td class="px-6 py-4">' +
                        '<span class="font-medium text-on-surface">' + category.category_name + '</span>' +
                        '</td>' +
                        '<td class="px-6 py-4">' + category.created_at + '</td>' +
                        '<td class="px-6 py-4 text-right">' +
                        '<div class="flex items-center justify-end gap-1">' +
                        '<button class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-primary-container hover:text-primary transition-colors" onclick="editCategory(' + category.category_id + ')" title="Edit">' +
                        '<span class="material-symbols-outlined text-lg">edit</span>' +
                        '</button>' +
                        '<button class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-error transition-colors" onclick="deleteCategory(' + category.category_id + ')" title="Delete">' +
                        '<span class="material-symbols-outlined text-lg">delete</span>' +
                        '</button>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                });

            } else {

                html = '<tr><td colspan="5" class="text-center py-6">No categories found</td></tr>';
            }

            $('#categoriesTableBody').html(html);
            loadPerformanceSummary(response.data);
        },

        error: function(xhr) {

            $('#categoriesTableBody').html(
                '<tr><td colspan="5" class="text-red-500 text-center py-6">Failed to load categories</td></tr>'
            );
        }
    });
}

function loadPerformanceSummary(data) {
    var total = data ? data.length : 0;
    var html = '<p><strong>Total Categories:</strong> ' + total + '</p>';
    if (total > 0) {
        var newest = data[0];
        html += '<p><strong>Newest:</strong> ' + newest.category_name + ' (' + newest.created_at + ')</p>';
    }
    $('#performanceSummary').html(html);
}

function createCategory() {

    var category_name = $('#createCatName').val().trim();
    var category_icon = $('#createSelectedIcon').val() || 'restaurant';

    if (!category_name) {
        alert('Please enter a category name');
        return;
    }

    $.ajax({
        url: '../../api/categories.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ category_name: category_name, category_icon: category_icon }),

        success: function(response) {
            if (response.success) {
                closeModal();
                $('#categoryCreateForm')[0].reset();
                renderIconGrid('createIconGrid', 'createSelectedIcon');
                selectDefaultIcon('createIconGrid', 'createSelectedIcon');
                loadCategories();
            } else {
                alert(response.message || 'Failed to create category');
            }
        },

        error: function(xhr) {
            alert('Error creating category');
        }
    });
}

function editCategory(categoryId) {

    openModal('modal-category-edit');
    $('#catEditLoading').show();
    $('#categoryEditForm').addClass('hidden');
    $('#catEditFooter').addClass('hidden');

    $.ajax({
        url: '../../api/categories.php',
        type: 'GET',
        dataType: 'json',

        success: function(response) {
            if (response.success) {
                var category = null;
                $.each(response.data, function(i, c) {
                    if (parseInt(c.category_id) === parseInt(categoryId)) {
                        category = c;
                        return false;
                    }
                });

                if (category) {
                    $('#editCatName').val(category.category_name);
                    $('#editCatPreviewName').text(category.category_name);

                    var icon = category.category_icon || 'restaurant';
                    $('#editCatPreviewIcon').text(icon);

                    $('#categoryEditForm').data('category-id', category.category_id);
                    $('#categoryEditForm').data('category-icon', icon);

                    renderIconGrid('editIconGrid', 'editSelectedIcon');
                    $('#editSelectedIcon').val(icon);
                    selectDefaultIcon('editIconGrid', 'editSelectedIcon');

                    $('#catEditLoading').hide();
                    $('#categoryEditForm').removeClass('hidden');
                    $('#catEditFooter').removeClass('hidden');
                } else {
                    alert('Category not found');
                    closeModal();
                }
            } else {
                alert('Failed to load category');
                closeModal();
            }
        },

        error: function() {
            alert('Error loading category');
            closeModal();
        }
    });
}

$('#editCatName').on('input', function () {
    var val = $(this).val().trim();
    $('#editCatPreviewName').text(val || 'Category Name');
});

$(document).on('click', '#editIconGrid button', function () {
    var icon = $(this).data('icon');
    $('#editCatPreviewIcon').text(icon);
});

function saveCategory() {

    var category_id = $('#categoryEditForm').data('category-id');
    var category_name = $('#editCatName').val().trim();
    var category_icon = $('#editSelectedIcon').val() || 'restaurant';

    if (!category_name) {
        alert('Please enter a category name');
        return;
    }

    $.ajax({
        url: '../../api/categories.php',
        type: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({
            category_id: category_id,
            category_name: category_name,
            category_icon: category_icon
        }),

        success: function(response) {
            if (response.success) {
                closeModal();
                loadCategories();
            } else {
                alert(response.message || 'Failed to update category');
            }
        },

        error: function() {
            alert('Error updating category');
        }
    });
}

function deleteCategory(categoryId) {

    if (!confirm('Are you sure you want to delete this category?')) return;

    $.ajax({
        url: '../../api/categories.php',
        type: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({ category_id: categoryId }),

        success: function(response) {
            if (response.success) {
                loadCategories();
            } else {
                alert(response.message || 'Failed to delete category');
            }
        },

        error: function() {
            alert('Error deleting category');
        }
    });
}
