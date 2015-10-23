(function($){
    $('document').ready(function() {
        $( 'body' ).on( 'click', '.kt_radio_select_input', function ( ){
            var $this = $(this),
                $parent = $this.closest('.edit_form_line'),
                $image = $this.val(),
                $input = $parent.find('.wpb_vc_param_value');

            $input.val($image);
        });
    });
})(jQuery);