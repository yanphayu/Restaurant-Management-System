$(document).ready(function () {
    loadFoods();
    loadCategoryOptions();
});

function loadFoods() {

    $.ajax({
        url: '../../api/food.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {

            var html = '';

            if (response.success && response.data.length > 0) {

                var totalItems = 0;
                var outOfStock = 0;
                var totalPrice = 0;
                var categoryCount = {};
                var maxCat = { name: '', count: 0 };

                $.each(response.data, function (i, food) {

                    totalItems++;
                    if (food.status === 'unavailable' || food.status === 'Unavailable') outOfStock++;
                    totalPrice += parseFloat(food.food_price) || 0;

                    var cat = food.category_name || 'Uncategorized';
                    var catIcon = food.category_icon || 'restaurant';
                    categoryCount[cat] = (categoryCount[cat] || 0) + 1;

                    var imageHtml = food.food_image
                        ? '<img src="../../uploads/' + food.food_image + '" alt="' + (food.food_name || '') + '" class="w-10 h-10 rounded-lg object-cover">'
                        : '<div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center"><span class="material-symbols-outlined text-on-surface-variant text-lg">image</span></div>';

                    html += '<tr>' +
                        '<td class="px-6 py-4">' + imageHtml + '</td>' +
                        '<td class="px-6 py-4">' + (food.food_name || '') + '</td>' +
                        '<td class="px-6 py-4">' + cat + '</td>' +
                        '<td class="px-6 py-4">$' + (parseFloat(food.food_price) || 0).toFixed(2) + '</td>' +
                        '<td class="px-6 py-4">' +
                        '<span class="' + (food.status === 'available' || food.status === 'Available' ? 'text-green-600' : 'text-red-600') + '">' +
                        (food.status || '') +
                        '</span></td>' +
                        '<td class="px-6 py-4 text-right">' +
                        '<div class="flex items-center justify-end gap-1">' +
                        '<button class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-primary-container hover:text-primary transition-colors" onclick="viewFood(' + food.food_id + ')" title="View">' +
                        '<span class="material-symbols-outlined text-lg">visibility</span>' +
                        '</button>' +
                        '<button class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-primary-container hover:text-primary transition-colors" onclick="editFood(' + food.food_id + ')" title="Edit">' +
                        '<span class="material-symbols-outlined text-lg">edit</span>' +
                        '</button>' +
                        '<button class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-error transition-colors" onclick="deleteFood(' + food.food_id + ')" title="Delete">' +
                        '<span class="material-symbols-outlined text-lg">delete</span>' +
                        '</button>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                });

                var avgPrice = totalItems > 0 ? (totalPrice / totalItems).toFixed(2) : '0.00';

                for (var c in categoryCount) {
                    if (categoryCount[c] > maxCat.count) {
                        maxCat = { name: c, count: categoryCount[c] };
                    }
                }

                $('#statTotalItems').text(totalItems);
                $('#statOutOfStock').text(outOfStock);
                $('#statAvgPrice').text('$' + avgPrice);
                $('#statTopCategory').text(maxCat.name || '--');

                $('#paginationInfo').text('Showing ' + totalItems + ' item' + (totalItems !== 1 ? 's' : ''));

            } else {

                html = '<tr><td colspan="6" class="text-center py-6">No foods found</td></tr>';
            }

            $('#foodsTableBody').html(html);
        },
        error: function () {
            $('#foodsTableBody').html('<tr><td colspan="6" class="text-red-500 text-center py-6">Failed to load foods</td></tr>');
        }
    });
}

function loadCategoryOptions() {

    $.ajax({
        url: '../../api/categories.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var opts = '<option value="">Select a category</option>';
                $.each(response.data, function (i, cat) {
                    opts += '<option value="' + cat.category_id + '">' + cat.category_name + '</option>';
                });
                $('#createFoodCategory').html(opts);
                $('#editFoodCategory').html(opts);
            }
        }
    });
}

function createFood() {

    var formData = new FormData();

    formData.append('food_name', $('#createFoodName').val());
    formData.append('category_id', $('#createFoodCategory').val());
    formData.append('food_price', $('#createFoodPrice').val());
    formData.append('food_description', $('#createFoodDescription').val());
    formData.append('status', $('input[name="createFoodStatus"]:checked').val());

    if ($('#createFoodImage')[0].files[0]) {
        formData.append('food_image', $('#createFoodImage')[0].files[0]);
    }

    $.ajax({
        url: '../../api/food.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            if (res.success) {
                closeModal();
                $('#foodCreateForm')[0].reset();
                $('#createImagePreview').addClass('hidden');
                $('#createImageUploadArea').removeClass('hidden');
                loadFoods();
            } else {
                alert(res.message || 'Failed to create food');
            }
        },
        error: function () {
            alert('Error creating food');
        }
    });
}

function editFood(foodId) {

    openModal('modal-food-edit');
    $('#foodEditLoading').show();
    $('#foodEditForm').addClass('hidden');
    $('#foodEditFooter').addClass('hidden');

    $.ajax({
        url: '../../api/food.php?action=get&id=' + foodId,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data) {
                var food = response.data;
                $('#editFoodName').val(food.food_name);
                $('#editFoodCategory').val(food.category_id);
                $('#editFoodPrice').val(food.food_price);
                $('#editFoodDescription').val(food.food_description);
                $('input[name="editFoodStatus"][value="' + (food.status || 'available').toLowerCase() + '"]').prop('checked', true);
                $('#foodEditForm').data('food-id', food.food_id);
                $('#foodEditForm').data('food-image', food.food_image || '');

                if (food.food_image) {
                    $('#editPreviewImg').attr('src', '../../uploads/' + food.food_image);
                    $('#editImagePreview').removeClass('hidden');
                    $('#editImageUploadArea').addClass('hidden');
                } else {
                    $('#editImagePreview').addClass('hidden');
                    $('#editImageUploadArea').removeClass('hidden');
                }
                $('#foodEditLoading').hide();
                $('#foodEditForm').removeClass('hidden');
                $('#foodEditFooter').removeClass('hidden');
            } else {
                alert('Food not found');
                closeModal();
            }
        },
        error: function () {
            alert('Error loading food');
            closeModal();
        }
    });
}

function saveFood() {

    var foodId = $('#foodEditForm').data('food-id');
    var formData = new FormData();

    formData.append('food_id', foodId);
    formData.append('food_name', $('#editFoodName').val());
    formData.append('category_id', $('#editFoodCategory').val());
    formData.append('food_price', $('#editFoodPrice').val());
    formData.append('food_description', $('#editFoodDescription').val());
    formData.append('status', $('input[name="editFoodStatus"]:checked').val());

    if ($('#editFoodImage')[0].files[0]) {
        formData.append('food_image', $('#editFoodImage')[0].files[0]);
    }

    $.ajax({
        url: '../../api/food.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            if (res.success) {
                closeModal();
                loadFoods();
            } else {
                alert(res.message || 'Failed to update food');
            }
        },
        error: function () {
            alert('Error updating food');
        }
    });
}

function viewFood(foodId) {

    openModal('modal-food-view');
    $('#foodViewLoading').show();
    $('#foodViewContent').addClass('hidden');

    $.ajax({
        url: '../../api/food.php?action=get&id=' + foodId,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data) {
                var food = response.data;

                if (food.food_image) {
                    $('#viewFoodImage').attr('src', '../../uploads/' + food.food_image);
                    $('#viewFoodImage').removeClass('hidden');
                    $('#viewFoodNoImage').addClass('hidden');
                } else {
                    $('#viewFoodImage').addClass('hidden');
                    $('#viewFoodNoImage').removeClass('hidden');
                }

                $('#viewFoodName').text(food.food_name || '-');
                $('#viewFoodCategory').text(food.category_name || 'Uncategorized');
                $('#viewFoodPrice').text('$' + (parseFloat(food.food_price) || 0).toFixed(2));

                var statusClass = (food.status === 'available' || food.status === 'Available') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                $('#viewFoodStatus').html('<span class="px-3 py-1 rounded-full text-sm font-semibold ' + statusClass + '">' + (food.status || '-') + '</span>');

                $('#viewFoodDesc').text(food.food_description || 'No description provided.');

                $('#foodViewLoading').hide();
                $('#foodViewContent').removeClass('hidden');
            } else {
                alert('Food not found');
                closeModal();
            }
        },
        error: function () {
            alert('Error loading food');
            closeModal();
        }
    });
}

function deleteFood(foodId) {

    if (!confirm('Are you sure you want to delete this food item?')) return;

    $.ajax({
        url: '../../api/food.php',
        type: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({ food_id: foodId }),
        success: function (res) {
            if (res.success) {
                loadFoods();
            } else {
                alert(res.message || 'Failed to delete food');
            }
        },
        error: function () {
            alert('Error deleting food');
        }
    });
}
