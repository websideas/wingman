(function($){
    $('document').ready(function() {
        $('.kt-select-field').each(function(){
            var $this = $(this),
                $parents = $this.closest('.edit_form_line'),
                $input = $parents.find('.wpb_vc_param_value');
            $(this).chosen().change(function(event) {
                $input.val($(event.target).val());
            });
        });
    });
})(jQuery);