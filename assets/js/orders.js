var allOrders = [];

function loadOrders() {
    $.ajax({
        url: '../../api/order.php',
        method: 'GET',
        data: { action: 'get_all' },
        dataType: 'json',
        success: function(res) {
            if (!res.success) return;
            allOrders = res.orders;
            renderStats(res.stats);
            renderOrdersTable(res.orders);
            renderInsights(res.orders);
        }
    });
}

function renderStats(s) {
    $('#statActive').text(s.active);
    $('#statPreparing').text(s.preparing);
    $('#statRevenue').text('$' + parseFloat(s.revenue).toFixed(2));
}

function renderOrdersTable(orders) {
    var badgeMap = {
        Pending:   'bg-tertiary-container text-on-tertiary-container',
        Completed: 'bg-success-container text-on-success-container',
        Paid:      'bg-primary-container text-on-primary-container'
    };

    if (!orders.length) {
        $('#ordersTableBody').html('<tr><td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">No orders found</td></tr>');
        return;
    }

    var html = '';
    $.each(orders, function(_, o) {
        var badge = badgeMap[o.status] || badgeMap.Pending;
        var itemCount = o.items ? o.items.length : 0;
        var itemNames = o.items ? o.items.map(function(i) { return i.food_name + ' x' + i.quantity; }).join(', ') : '';
        if (itemNames.length > 50) itemNames = itemNames.substring(0, 50) + '...';

        html += '<tr class="hover:bg-surface-container-low/50 transition-colors">';
        html += '<td class="px-6 py-4 font-semibold text-on-surface">#' + o.order_id + '</td>';
        html += '<td class="px-6 py-4"><div class="text-body-md text-on-surface">' + o.user_name + '</div><div class="text-body-sm text-on-surface-variant">' + o.table_name + '</div></td>';
        html += '<td class="px-6 py-4 text-body-sm text-on-surface-variant">' + itemCount + ' items</td>';
        html += '<td class="px-6 py-4 font-semibold text-on-surface">$' + parseFloat(o.total_amount).toFixed(2) + '</td>';
        html += '<td class="px-6 py-4"><span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold ' + badge + '">' + o.status + '</span></td>';
        html += '<td class="px-6 py-4 text-right"><div class="flex items-center justify-end gap-2">';
        html += '<button onclick="viewOrder(' + o.order_id + ')" class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700 hover:bg-primary-container hover:text-primary transition-colors" title="View"><span class="material-symbols-outlined text-base">visibility</span></button>';
        html += '<button onclick="deleteOrder(' + o.order_id + ')" class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-700 hover:bg-error-container hover:text-error transition-colors" title="Delete"><span class="material-symbols-outlined text-base">delete</span></button>';
        html += '</div></td></tr>';
    });
    $('#ordersTableBody').html(html);
}

function renderInsights(orders) {
    var completed = orders.filter(function(o) { return o.status === 'Completed' || o.status === 'Paid'; });
    if (completed.length > 0) {
        $('#insightPrepTime').text('Based on ' + completed.length + ' completed orders');
    } else {
        $('#insightPrepTime').text('No completed orders yet');
    }

    var catCount = {};
    $.each(orders, function(_, o) {
        if (o.items) {
            $.each(o.items, function(_, item) {
                var name = item.food_name || 'Unknown';
                catCount[name] = (catCount[name] || 0) + 1;
            });
        }
    });
    var popular = '-';
    var maxCount = 0;
    $.each(catCount, function(name, count) {
        if (count > maxCount) { maxCount = count; popular = name; }
    });
    $('#insightPopular').text(popular !== '-' ? popular + ' (' + maxCount + ' orders)' : 'No data yet');

    $('#insightStaff').text(completed.length + ' orders completed today');
}

function viewOrder(id) {
    $('#orderDetailContent').html('<div class="flex items-center justify-center py-12"><span class="material-symbols-outlined animate-spin text-[32px] text-primary">progress_activity</span></div>');
    $('#orderDetailFooter').addClass('hidden');
    openModal('modal-order-detail');

    $.ajax({
        url: '../../api/order.php',
        method: 'GET',
        data: { action: 'get', id: id },
        dataType: 'json',
        success: function(res) {
            if (!res.success) { $('#orderDetailContent').html('<p class="text-center text-on-surface-variant py-8">' + res.message + '</p>'); return; }
            var o = res.order;
            var badgeMap = {
                Pending: 'bg-tertiary-container text-on-tertiary-container',
                Completed: 'bg-success-container text-on-success-container',
                Paid: 'bg-primary-container text-on-primary-container'
            };
            var badge = badgeMap[o.status] || badgeMap.Pending;

            var html = '<div class="space-y-5">';
            html += '<div class="flex items-center justify-between">';
            html += '<div><p class="text-label-caps font-label-caps text-on-surface-variant">Order #' + o.order_id + '</p><p class="text-body-sm text-on-surface-variant">' + o.order_date + '</p></div>';
            html += '<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold ' + badge + '">' + o.status + '</span>';
            html += '</div>';
            html += '<div class="grid grid-cols-2 gap-4">';
            html += '<div class="p-3 bg-surface-container-low rounded-xl"><p class="text-label-caps font-label-caps text-on-surface-variant mb-1">Customer</p><p class="text-body-md font-semibold text-on-surface">' + o.user_name + '</p></div>';
            html += '<div class="p-3 bg-surface-container-low rounded-xl"><p class="text-label-caps font-label-caps text-on-surface-variant mb-1">Table</p><p class="text-body-md font-semibold text-on-surface">' + o.table_name + '</p></div>';
            html += '</div>';

            html += '<div><p class="text-label-caps font-label-caps text-on-surface-variant mb-3">Items</p>';
            html += '<div class="space-y-2">';
            $.each(o.items, function(_, item) {
                html += '<div class="flex items-center justify-between p-3 bg-surface-container-low rounded-xl">';
                html += '<div><p class="text-body-md font-semibold text-on-surface">' + item.food_name + '</p><p class="text-body-sm text-on-surface-variant">Qty: ' + item.quantity + ' &times; $' + parseFloat(item.price).toFixed(2) + '</p></div>';
                html += '<span class="text-body-md font-semibold text-on-surface">$' + parseFloat(item.subtotal).toFixed(2) + '</span>';
                html += '</div>';
            });
            html += '</div></div>';

            html += '<div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant">';
            html += '<div class="flex justify-between text-body-md text-on-surface-variant mb-2"><span>Subtotal</span><span>$' + parseFloat(o.total_amount).toFixed(2) + '</span></div>';
            html += '<div class="flex justify-between text-body-md text-on-surface-variant mb-2"><span>Tax (10%)</span><span>$' + (o.total_amount * 0.1).toFixed(2) + '</span></div>';
            html += '<div class="flex justify-between text-headline-sm font-bold text-on-surface border-t border-outline-variant pt-2 mt-2"><span>Total</span><span>$' + (o.total_amount * 1.1).toFixed(2) + '</span></div>';
            html += '</div>';

            html += '<div><p class="text-label-caps font-label-caps text-on-surface-variant mb-2">Update Status</p>';
            html += '<div class="flex gap-2">';
            var statuses = ['Pending', 'Completed', 'Paid'];
            $.each(statuses, function(_, s) {
                var active = s === o.status ? 'ring-2 ring-primary' : '';
                html += '<button onclick="updateOrderStatus(' + o.order_id + ', \'' + s + '\')" class="px-4 py-2 rounded-xl text-sm font-semibold border border-outline-variant ' + active + ' hover:bg-surface-container-low transition-colors">' + s + '</button>';
            });
            html += '</div></div>';

            html += '</div>';

            $('#modalOrderTitle').text('Order #' + o.order_id);
            $('#orderDetailContent').html(html);
            $('#orderDetailFooter').removeClass('hidden');
        }
    });
}

function updateOrderStatus(id, status) {
    $.ajax({
        url: '../../api/order.php',
        method: 'POST',
        data: { action: 'update_status', order_id: id, status: status },
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'Updated', text: res.message, timer: 1500, showConfirmButton: false });
                loadOrders();
                viewOrder(id);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message });
            }
        }
    });
}

function deleteOrder(id) {
    Swal.fire({
        title: 'Delete this order?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(function(result) {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../api/order.php',
                method: 'POST',
                data: { action: 'delete', order_id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted', text: res.message });
                        loadOrders();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                    }
                }
            });
        }
    });
}

$(document).ready(function() {
    loadOrders();

    $(document).on('click', '[data-action="print-receipt"]', function() {
        window.print();
    });
});
