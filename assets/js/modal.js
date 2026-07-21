function openModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('modal-overlay-visible');
    document.body.style.overflow = 'hidden';
    var content = el.querySelector('.modal-content');
    if (content) {
        requestAnimationFrame(function() {
            content.classList.remove('modal-enter');
            content.classList.add('modal-enter-active');
        });
    }
}

function closeModal() {
    document.querySelectorAll('.modal-overlay-visible').forEach(function(el) {
        var content = el.querySelector('.modal-content');
        if (content) {
            content.classList.add('modal-enter');
            content.classList.remove('modal-enter-active');
        }
        setTimeout(function() {
            el.classList.add('hidden');
            el.classList.remove('modal-overlay-visible');
        }, 200);
    });
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
