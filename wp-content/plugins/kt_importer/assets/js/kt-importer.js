
/*
 *
 *	Admin $ Functions
 *	------------------------------------------------
 *
 */



(function($){
    "use strict"; // Start of use strict

    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    $(document).ready(function() {
        $('body').on('click','.kt-importer-button',function(e){

            e.preventDefault();

            var $this = $(this),
                $count = parseInt($this.data('count')),
                $dir = $this.data('dir'),
                $id = $this.data('id'),
                $opt_name = $this.data('opt_name'),
                $theme = $(this).closest('.theme').addClass('importing'),
                $progressbar = $theme.find('.demo-import-process span'),
                $imported = $theme.find('.kt-importer-imported'),
                $progress = 0,
                $progress_step = Math.ceil(100/$count),
                $last_step = false;

            $progressbar.css('width', '5%');
            $('.demo-import-loader').show();



            for( var i=1; i <= $count; i++ ){

                var $data = {
                    action: 'kt_importer_content',
                    dir: $dir,
                    demo: $id,
                    count: i
                };

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: $data,
                    success: function (data, textStatus, XMLHttpRequest) {

                        if ( $progress + $progress_step < 100 ){
                            $progress += $progress_step;
                            $progressbar.css('width', $progress+'%');
                        }else{
                            $last_step = true
                        }
                        if($last_step){
                            var $data = {
                                action: 'kt_importer_content',
                                demo: $id
                            };
                            $.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: $data,
                                success: function(data, textStatus, XMLHttpRequest){
                                    $progressbar.css('width', '100%');
                                    $('.demo-import-loader').hide();
                                    $imported.css('display', 'inline-block');
                                    $this.hide();
                                    $theme.addClass('active').removeClass('importing');
                                }
                            });
                        }
                    }
                });

            }

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: { action: 'kt_importer_options', demo: $id },
                success: function(data){

                }
            });

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: { action: 'kt_importer_widgets', demo: $id, opt_name: $opt_name },
                success: function(data){

                }
            });



        });





    });
})(jQuery);