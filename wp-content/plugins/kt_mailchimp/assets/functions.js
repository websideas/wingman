(function($){
    "use strict"; // Start of use strict

    $( 'body' ).on( 'submit', '.mailchimp-form', function ( e ){
        e.preventDefault();
        var $mForm = $(this),
            $button = $mForm.find('.mailchimp-submit'),
            $error = $mForm.find('.mailchimp-error').fadeOut(),
            $success = $mForm.find('.mailchimp-success').fadeOut();

        $button.addClass('loading').html($button.data('loading'));

        var data = {
            action: 'frontend_mailchimp',
            security : ajax_frontend.security,
            email: $mForm.find('input[name=email]').val(),
            firstname: $mForm.find('input[name=firstname]').val(),
            lastname: $mForm.find('input[name=lastname]').val(),
            list_id: $mForm.find('input[name=list_id]').val(),
            opt_in: $mForm.find('input[name=opt_in]').val()
        };

        $.post(ajax_frontend.ajaxurl, data, function(response) {
            $button.removeClass('loading').html($button.data('text'));

            if(response.error == '1'){
                $error.html(response.msg).fadeIn();
            }else{
                $success.fadeIn();
                $mForm.find('input[name=firstname], input[name=lastname], [name=email]').val('');
            }
        }, 'json');
    });

})(jQuery); // End of use strict