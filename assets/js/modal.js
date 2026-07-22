function openModal(id) {
    var $el = $('#' + id);
    if (!$el.length) return;
    $el.removeClass('hidden').addClass('modal-overlay-visible');
    $('body').css('overflow', 'hidden');
    var $content = $el.find('.modal-content');
    if ($content.length) {
        requestAnimationFrame(function() {
            $content.removeClass('modal-enter').addClass('modal-enter-active');
        });
    }
}

function closeModal() {
    $('.modal-overlay-visible').each(function() {
        var $el = $(this);
        var $content = $el.find('.modal-content');
        if ($content.length) {
            $content.addClass('modal-enter').removeClass('modal-enter-active');
        }
        setTimeout(function() {
            $el.addClass('hidden').removeClass('modal-overlay-visible');
        }, 200);
    });
    $('body').css('overflow', '');
}

$(document).on('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
