var searchTimer = null;
(function() {
    var $input = $('#globalSearchInput');
    var $dropdown = $('#searchDropdown');

    $input.on('input', function() {
        var q = $(this).val().trim();
        clearTimeout(searchTimer);
        if (q.length < 2) { $dropdown.addClass('hidden').empty(); return; }
        searchTimer = setTimeout(function() {
            $.ajax({
                url: '/api/search.php',
                method: 'GET',
                data: { q: q },
                success: function(res) {
                    if (!res.success || res.results.length === 0) {
                        $dropdown.html('<div class="px-4 py-6 text-center text-on-surface-variant text-body-sm">No results found</div>').removeClass('hidden');
                        return;
                    }
                    var icons = { order: 'receipt_long', food: 'restaurant', table: 'table_restaurant', category: 'category' };
                    var groups = {};
                    res.results.forEach(function(r) {
                        if (!groups[r.type]) groups[r.type] = [];
                        groups[r.type].push(r);
                    });
                    var html = '';
                    var typeLabels = { order: 'Orders', food: 'Foods', table: 'Tables', category: 'Categories' };
                    for (var type in groups) {
                        html += '<div class="px-3 pt-3 pb-1"><p class="text-label-caps font-label-caps text-on-surface-variant uppercase tracking-wider text-xs">' + (typeLabels[type] || type) + '</p></div>';
                        groups[type].forEach(function(r) {
                            html += '<a href="' + r.url + '" class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container transition-colors cursor-pointer">' +
                                '<span class="material-symbols-outlined text-on-surface-variant text-xl">' + (icons[r.type] || 'search') + '</span>' +
                                '<div class="flex-1 min-w-0">' +
                                    '<p class="text-body-md text-on-surface truncate">' + r.label + '</p>' +
                                    '<p class="text-body-sm text-on-surface-variant truncate">' + r.sub + '</p>' +
                                '</div>' +
                            '</a>';
                        });
                    }
                    $dropdown.html(html).removeClass('hidden');
                }
            });
        }, 300);
    });

    $input.on('focus', function() {
        if ($dropdown.children().length > 0) $dropdown.removeClass('hidden');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#globalSearch').length) {
            $dropdown.addClass('hidden');
        }
    });

    $input.on('keydown', function(e) {
        if (e.key === 'Escape') { $dropdown.addClass('hidden'); $(this).blur(); }
    });
})();