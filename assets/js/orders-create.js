var allFoods = [];
var cart = [];
var activeCategory = 'all';

function loadFormData() {
    $.ajax({
        url: '/api/order.php',
        method: 'GET',
        data: { action: 'get_foods' },
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                allFoods = res.foods;
                renderFoods(allFoods);
            }
        }
    });

    $.ajax({
        url: '/api/order.php',
        method: 'GET',
        data: { action: 'get_categories' },
        dataType: 'json',
        success: function(res) {
            if (res.success) renderCategoryChips(res.categories);
        }
    });

    $.ajax({
        url: '/api/order.php',
        method: 'GET',
        data: { action: 'get_tables' },
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                var html = '<option value="">Choose a table...</option>';
                $.each(res.tables, function(_, t) {
                    html += '<option value="' + t.table_id + '">' + t.table_name + '</option>';
                });
                $('#tableSelect').html(html);
            }
        }
    });
}

function renderCategoryChips(categories) {
    var html = '<button class="category-chip active px-4 py-1.5 rounded-full text-label-caps font-semibold bg-primary text-on-primary transition-colors" data-id="all">All</button>';
    $.each(categories, function(_, c) {
        html += '<button class="category-chip px-4 py-1.5 rounded-full text-label-caps font-semibold bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest transition-colors" data-id="' + c.category_id + '">' + c.category_name + '</button>';
    });
    $('#categoryChips').html(html);
}

function renderFoods(foods) {
    if (!foods.length) {
        $('#foodResults').html('<div class="flex items-center justify-center py-12 text-on-surface-variant"><p class="text-body-md">No food items available</p></div>');
        return;
    }

    var html = '';
    $.each(foods, function(_, f) {
        html += '<div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant/50 hover:border-primary/50 transition-colors">';
        if (f.food_image) {
            html += '<img src="/uploads/' + f.food_image + '" style="width:64px;height:64px;border-radius:8px;object-fit:cover" class="shrink-0" alt="' + f.food_name + '">';
        } else {
            html += '<div style="width:64px;height:64px;border-radius:8px" class="bg-surface-container-high flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-on-surface-variant">restaurant</span></div>';
        }
        html += '<div class="flex-1 min-w-0">';
        html += '<p class="text-body-md font-semibold text-on-surface">' + f.food_name + '</p>';
        html += '<p class="text-body-sm text-on-surface-variant">' + f.category_name + '</p>';
        html += '</div>';
        html += '<div class="text-right shrink-0">';
        html += '<p class="text-body-md font-bold text-on-surface">$' + parseFloat(f.food_price).toFixed(2) + '</p>';
        html += '<button onclick="addToCart(' + f.food_id + ')" class="mt-1 w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary hover:opacity-90 transition-opacity"><span class="material-symbols-outlined text-base">add</span></button>';
        html += '</div>';
        html += '</div>';
    });
    $('#foodResults').html(html);
}

function addToCart(foodId) {
    var food = null;
    $.each(allFoods, function(_, f) {
        if (f.food_id == foodId) { food = f; return false; }
    });
    if (!food) return;

    var existing = null;
    $.each(cart, function(_, item) {
        if (item.food_id == foodId) { existing = item; return false; }
    });

    if (existing) {
        existing.quantity++;
    } else {
        cart.push({
            food_id: food.food_id,
            food_name: food.food_name,
            price: parseFloat(food.food_price),
            quantity: 1
        });
    }
    renderCart();
}

function removeFromCart(foodId) {
    cart = cart.filter(function(item) { return item.food_id != foodId; });
    renderCart();
}

function updateCartQty(foodId, qty) {
    qty = parseInt(qty);
    if (qty < 1) { removeFromCart(foodId); return; }
    $.each(cart, function(_, item) {
        if (item.food_id == foodId) { item.quantity = qty; return false; }
    });
    renderCart();
}

function renderCart() {
    if (!cart.length) {
        $('#orderItems').html('<p class="text-on-surface-variant text-body-sm text-center py-8">No items added yet</p>');
        updateTotals();
        return;
    }

    var html = '';
    $.each(cart, function(_, item) {
        var subtotal = item.price * item.quantity;
        html += '<div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">';
        html += '<div class="flex-1 min-w-0">';
        html += '<p class="text-body-md font-semibold text-on-surface truncate">' + item.food_name + '</p>';
        html += '<p class="text-body-sm text-on-surface-variant">$' + item.price.toFixed(2) + ' each</p>';
        html += '</div>';
        html += '<div class="flex items-center gap-2">';
        html += '<button onclick="updateCartQty(' + item.food_id + ', ' + (item.quantity - 1) + ')" class="w-7 h-7 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-surface-container-highest transition-colors"><span class="material-symbols-outlined text-base">remove</span></button>';
        html += '<span class="text-body-md font-semibold text-on-surface w-6 text-center">' + item.quantity + '</span>';
        html += '<button onclick="updateCartQty(' + item.food_id + ', ' + (item.quantity + 1) + ')" class="w-7 h-7 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-surface-container-highest transition-colors"><span class="material-symbols-outlined text-base">add</span></button>';
        html += '</div>';
        html += '<div class="text-right shrink-0">';
        html += '<p class="text-body-md font-semibold text-on-surface">$' + subtotal.toFixed(2) + '</p>';
        html += '<button onclick="removeFromCart(' + item.food_id + ')" class="text-body-sm text-error hover:underline mt-0.5">Remove</button>';
        html += '</div>';
        html += '</div>';
    });
    $('#orderItems').html(html);
    updateTotals();
}

function updateTotals() {
    var subtotal = 0;
    $.each(cart, function(_, item) {
        subtotal += item.price * item.quantity;
    });
    var tax = subtotal * 0.1;
    var total = subtotal + tax;
    $('#subtotal').text('$' + subtotal.toFixed(2));
    $('#tax').text('$' + tax.toFixed(2));
    $('#total').text('$' + total.toFixed(2));
    $('#submitOrderBtn').prop('disabled', cart.length === 0);
}

function submitOrder() {
    var tableId = $('#tableSelect').val();
    if (!tableId) {
        Swal.fire({ icon: 'warning', title: 'Select Table', text: 'Please choose a table first.' });
        return;
    }
    if (!cart.length) {
        Swal.fire({ icon: 'warning', title: 'Empty Order', text: 'Please add at least one item.' });
        return;
    }

    var $btn = $('#submitOrderBtn');
    $btn.prop('disabled', true).text('Submitting...');

    $.ajax({
        url: '/api/order.php',
        method: 'POST',
        data: {
            action: 'create',
            table_id: tableId,
            items: JSON.stringify(cart)
        },
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'Order Created', text: 'Order #' + res.order_id + ' has been placed.' }).then(function() {
                    window.location.href = 'index.php';
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                $btn.prop('disabled', false).html('<span class="material-symbols-outlined">send</span> Submit Order');
            }
        },
        error: function() {
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong.' });
            $btn.prop('disabled', false).html('<span class="material-symbols-outlined">send</span> Submit Order');
        }
    });
}

$(document).ready(function() {
    loadFormData();

    $('#foodSearch').on('input', function() {
        var query = $(this).val().toLowerCase();
        var filtered = allFoods.filter(function(f) {
            var matchSearch = f.food_name.toLowerCase().indexOf(query) !== -1;
            var matchCategory = activeCategory === 'all' || f.category_id == activeCategory;
            return matchSearch && matchCategory;
        });
        renderFoods(filtered);
    });

    $(document).on('click', '.category-chip', function() {
        $('.category-chip').removeClass('active bg-primary text-on-primary').addClass('bg-surface-container-high text-on-surface-variant');
        $(this).addClass('active bg-primary text-on-primary').removeClass('bg-surface-container-high text-on-surface-variant');
        activeCategory = $(this).data('id');
        $('#foodSearch').trigger('input');
    });

    $('#orderForm').on('submit', function(e) {
        e.preventDefault();
        submitOrder();
    });
});
