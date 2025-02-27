(function($) {
    $(document).ready(function() {
        $('.geox-conditional-container, .geox-conditional-content').each(function() {
            var $element = $(this);
            var include = $element.data('include');
            var exclude = $element.data('exclude');

            $.ajax({
                url: geox_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'geox_check_location',
                    include: include,
                    exclude: exclude
                },
                success: function(response) {
                    if (response.success && response.data.should_display) {
                        $element.show().addClass('geox-visible');
                    } else {
                        $element.remove();
                    }
                },
                error: function() {
                    $element.remove();
                }
            });
        });
    });
})(jQuery);
