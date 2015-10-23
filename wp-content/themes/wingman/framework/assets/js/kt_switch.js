(function($){
    $('document').ready(function() {
        $( 'body' ).on( 'click', 'input.cmn-toggle', function ( ){
            var $this = $(this);
            if($this.prop( "checked" )){
                $this.val('true');
            }else{
                $this.val('false');
            }
        });
    });
})(jQuery);