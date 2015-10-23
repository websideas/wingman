(function($){

    $('.param-icon-categories').change(function(){
        var $that = $(this),
            $val = $that.val(),
            $parram = $(this).closest('.wpb_el_type_kt_icons'),
            $search = $parram.find('.param-icon-search'),
            $icons = $parram.find('li');

        $icons.removeClass('active');
        $search.val('');


        if($val == ''){
            $icons.show().addClass('active');
        }else{
            $parram.find('li[data-source='+$val+']').addClass('active').show();
            $parram.find('li[data-source!='+$val+']').hide();
        }
    });


    $('.param-icon-search').keyup(function(){
        var $that = $(this),
            $val = $that.val(),
            $parram = $(this).closest('.wpb_el_type_kt_icons'),
            $categories = $parram.find('.param-icon-categories'),
            $icons = $parram.find('li');

        $icons.removeClass('active');
        $categories.val('');

        if($val == ''){
            $icons.show().addClass('active');
        }else{
            $parram.find('li[data-key*='+$val+']').addClass('active').show();
            $parram.find('li:not(.active)').hide();
        }

    });

    $('.wpb_el_type_kt_icons li').click(function(){
        var $that = $(this),
            $val = $that.data('key'),
            $parram = $(this).closest('.wpb_el_type_kt_icons'),
            $preview = $parram.find('.param-icon-preview'),
            $icons = $parram.find('li'),
            $input = $parram.find('.wpb_vc_param_value');

        $icons.removeClass('current');
        $that.addClass('current');
        $preview.addClass('active').find('i').attr('class', $val);
        $input.val($val);


    });


    $('.icon-preview-remove').click(function(){
        var $that = $(this),
            $parram = $(this).closest('.wpb_el_type_kt_icons'),
            $preview = $parram.find('.param-icon-preview');

        $preview.removeClass('active');
        $preview.find('i').attr('class', '');
        $input.val('');
    });

})(jQuery);