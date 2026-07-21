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
        document.addEventListener('click', function(e) {
            var target = e.target.closest('[data-action]');
            if (!target) return;
            var action = target.getAttribute('data-action');
            if (action === 'close-modal') closeModal();
            else if (action === 'close-modal-backdrop') closeModal();
            else if (action === 'open-modal') openModal(target.getAttribute('data-target'));
        });
    </script>
</body>
</html>
