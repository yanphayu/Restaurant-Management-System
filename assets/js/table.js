function updateTablePreview() {
    $('#editTablePreviewName').text($('#editTableName').val() || 'Table');
    $('#editTablePreviewCap').text($('#editTableCapacity').val() || '0');
    var st = $('#editTableStatus').val();
    var sm = {Available:{l:'Available',c:'bg-success-container text-on-success-container'},Occupied:{l:'Occupied',c:'bg-error-container text-on-error-container'},Reserved:{l:'Reserved',c:'bg-tertiary-container text-on-tertiary-container'}};
    var s = sm[st] || sm.Available;
    var badge = $('#editTablePreviewStatus');
    badge.text(s.l);
    badge.attr('class', 'inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold ' + s.c);
}

var currentTableId = null;

function loadTables() {
    $.ajax({
        url: '/api/table.php',
        method: 'GET',
        data: { action: 'get_all' },
        dataType: 'json',
        success: function(res) {
            if (!res.success) return;
            renderStats(res.stats);
            renderTableBody(res.tables);
            renderFloorMap(res.tables);
        }
    });
}

function renderStats(s) {
    $('#statCapacity').text(s.total_capacity);
    $('#statOccupied').text(s.Occupied);
    $('#statReserved').text(s.Reserved);
    $('#statAvailable').text(s.Available);
}

function renderTableBody(tables) {
    var badgeMap = {
        Available: 'bg-success-container text-on-success-container',
        Occupied:  'bg-error-container text-on-error-container',
        Reserved:  'bg-tertiary-container text-on-tertiary-container'
    };
    var labelMap = { Available: 'Available', Occupied: 'Occupied', Reserved: 'Reserved' };

    if (!tables.length) {
        $('#tablesTableBody').html('<tr><td colspan="4" class="px-6 py-12 text-center text-on-surface-variant">No tables found</td></tr>');
        return;
    }

    var html = '';
    $.each(tables, function(_, t) {
        var badge = badgeMap[t.status] || badgeMap.available;
        var label = labelMap[t.status] || 'Available';
        html += '<tr class="hover:bg-surface-container-low/50 transition-colors">';
        html += '<td class="px-6 py-4 font-semibold text-on-surface">' + t.table_name + '</td>';
        html += '<td class="px-6 py-4 text-on-surface-variant">' + t.capacity + ' seats</td>';
        html += '<td class="px-6 py-4"><span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold ' + badge + '">' + label + '</span></td>';
        html += '<td class="px-6 py-4 text-right"><div class="flex items-center justify-end gap-2">';
        html += '<button onclick="editTable(' + t.table_id + ')" class="w-8 h-8 rounded-lg bg-primary-container/50 flex items-center justify-center text-on-primary hover:bg-primary-container transition-colors" title="Edit"><span class="material-symbols-outlined text-base">edit</span></button>';
        html += '<button onclick="deleteTable(' + t.table_id + ')" class="w-8 h-8 rounded-lg bg-error-container/50 flex items-center justify-center text-on-error hover:bg-error-container transition-colors" title="Delete"><span class="material-symbols-outlined text-base">delete</span></button>';
        html += '</div></td></tr>';
    });
    $('#tablesTableBody').html(html);
}

function renderFloorMap(tables) {
    var colorMap = {
        Available: 'bg-success-container border-success text-on-success-container',
        Occupied:  'bg-error-container border-error text-on-error-container',
        Reserved:  'bg-tertiary-container border-tertiary text-on-tertiary-container'
    };
    var labelMap = { Available: 'Available', Occupied: 'Occupied', Reserved: 'Reserved' };

    if (!tables.length) {
        $('#floorMap').html('<div class="col-span-full flex items-center justify-center text-on-surface-variant text-sm">No tables to display</div>');
        return;
    }

    var html = '';
    $.each(tables, function(_, t) {
        var c = colorMap[t.status] || colorMap.available;
        html += '<div class="border-2 rounded-xl p-4 flex flex-col items-center justify-center gap-2 cursor-pointer hover:shadow-md transition-shadow ' + c + '" onclick="editTable(' + t.table_id + ')">';
        html += '<span class="material-symbols-outlined text-3xl">table_restaurant</span>';
        html += '<span class="text-sm font-semibold">' + t.table_name + '</span>';
        html += '<span class="text-[10px] opacity-75">' + t.capacity + ' seats</span>';
        html += '</div>';
    });
    $('#floorMap').html(html);
}

function saveTable() {
    var isEdit = currentTableId !== null;
    var data = {
        action: isEdit ? 'update' : 'create',
        table_id:   currentTableId,
        table_name: isEdit ? $('#editTableName').val() : $('#createTableName').val(),
        capacity:   isEdit ? $('#editTableCapacity').val() : $('#createTableCapacity').val(),
        status:     isEdit ? $('#editTableStatus').val()  : $('#createTableStatus').val()
    };

    $.ajax({
        url: '/api/table.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                closeModal();
                loadTables();
                resetCreateForm();
                Swal.fire({ icon: 'success', title: 'Success', text: res.message, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message });
            }
        },
        error: function(xhr) {
            Swal.fire({ icon: 'error', title: 'Request Failed', text: xhr.responseText });
        }
    });
}

function editTable(id) {
    currentTableId = id;
    $.ajax({
        url: '/api/table.php',
        method: 'GET',
        data: { action: 'get', id: id },
        dataType: 'json',
        success: function(res) {
            if (!res.success) { Swal.fire({ icon: 'error', title: 'Error', text: res.message }); return; }
            var t = res.table;
            $('#editTableName').val(t.table_name);
            $('#editTableCapacity').val(t.capacity);
            $('#editTableStatus').val(t.status);
            updateTablePreview();
            $('#tableEditLoading').addClass('hidden');
            $('#tableEditForm').removeClass('hidden');
            $('#tableEditFooter').removeClass('hidden');
            openModal('modal-table-edit');
        }
    });
}

function deleteTable(id) {
    Swal.fire({
        title: 'Delete this table?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(function(result) {
        if (result.isConfirmed) {
            $.ajax({
                url: '/api/table.php',
                method: 'POST',
                data: { action: 'delete', table_id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted', text: res.message });
                        loadTables();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                    }
                }
            });
        }
    });
}

function resetCreateForm() {
    $('#tableCreateForm')[0].reset();
}

$(document).ready(function() {
    loadTables();

    $('#tableCreateForm').on('submit', function(e) {
        e.preventDefault();
        currentTableId = null;
        saveTable();
    });

    $('#tableEditForm').on('submit', function(e) {
        e.preventDefault();
        saveTable();
    });

    $('#addTableBtn').on('click', function() {
        resetCreateForm();
        openModal('modal-table-create');
    });

    $('#editTableName').on('input', updateTablePreview);
    $('#editTableCapacity').on('input', updateTablePreview);
    $('#editTableStatus').on('change', updateTablePreview);
});
