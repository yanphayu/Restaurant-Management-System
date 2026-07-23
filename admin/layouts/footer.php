            </div>

            <!-- Footer -->
            <footer class="border-t border-outline-variant/50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-body-sm text-on-surface-variant">KitchenFlow v1.0</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-tertiary rounded-full animate-pulse"></span>
                        <span class="text-body-sm text-on-surface-variant">System Online</span>
                    </div>
                </div>
            </footer>

        </main>
    </div>

    <script src="<?= $prefix ?>assets/js/modal.js"></script>
    <script>
        (function() {
            var searchTimer = null;
            var $input = $('#globalSearchInput');
            var $dropdown = $('#searchDropdown');

            if (!$input.length) return;

            $input.on('keyup', function() {
                var q = $(this).val().trim();
                clearTimeout(searchTimer);
                if (q.length < 2) { $dropdown.addClass('hidden').empty(); return; }
                searchTimer = setTimeout(function() {
                    $.ajax({
                        url: '/api/search.php',
                        method: 'GET',
                        data: { q: q },
                        dataType: 'json',
                        success: function(res) {
                            if (!res.success) {
                                $dropdown.html('<div class="px-4 py-6 text-center text-error text-body-sm">' + (res.message || 'Search failed') + '</div>').removeClass('hidden');
                                return;
                            }
                            if (res.results.length === 0) {
                                $dropdown.html('<div class="px-4 py-6 text-center text-on-surface-variant text-body-sm">No results found</div>').removeClass('hidden');
                                return;
                            }
                            var icons = { order: 'receipt_long', food: 'restaurant', table: 'table_restaurant', category: 'category', page: 'description' };
                            var groups = {};
                            res.results.forEach(function(r) {
                                if (!groups[r.type]) groups[r.type] = [];
                                groups[r.type].push(r);
                            });
                            var html = '';
                            var typeLabels = { page: 'Pages', order: 'Orders', food: 'Foods', table: 'Tables', category: 'Categories' };
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
                        },
                        error: function(xhr, status, err) {
                            $dropdown.html('<div class="px-4 py-6 text-center text-error text-body-sm">Error: ' + xhr.status + ' ' + err + '</div>').removeClass('hidden');
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
    </script>
    <script>
        $(document).on('click', '[data-action]', function(e) {
            var action = $(this).data('action');
            if (action === 'close-modal') closeModal();
            else if (action === 'close-modal-backdrop') closeModal();
            else if (action === 'open-modal') openModal($(this).data('target'));
            else if (action === 'logout') {
                Swal.fire({
                    title: 'Logout?',
                    text: 'You will be signed out of your account.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, logout'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/auth/auth.php',
                            method: 'POST',
                            data: { action: 'logout' },
                            dataType: 'json',
                            success: function() {
                                window.location.href = '/admin/login/login.php';
                            }
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>
