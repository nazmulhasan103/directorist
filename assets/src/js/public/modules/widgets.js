window.addEventListener('DOMContentLoaded', () => {
    (function ($) {
        /* Category/Location expand */
        $('.atbdp_child_category').hide();
        $('.atbdp-widget-categories .atbdp_parent_category >li >span').on('click', function () {
            $(this).siblings('.atbdp_child_category').slideToggle();
        });
        $('.atbdp_child_location').hide();
        $('.atbdp-widget-categories .atbdp_parent_location >li >span').on('click', function () {
            $(this).siblings('.atbdp_child_location').slideToggle();
        });

    })(jQuery);
});