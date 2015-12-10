(function($){
	$.fn.extend({ 
		kt_showhide: function(options) {   
            var defaults = {
				parent: '.rwmb-field'
            };
            var o = $.extend(defaults,options);
    		return this.each(function() {
                var obj = $(this);
                $('[data-id]').each(function(){
                    var id = $(this).data('id'),
                        compare = $(this).data('compare');
                    if( !compare ) { compare = '=';  }
                    if( $('[name="'+id+'"]').is(':radio') ){
                        defaultshowhide(compare, $(this).data('value'), $('[name="'+id+'"]:checked').val(), $(this) );
                    }else{
                        defaultshowhide(compare, $(this).data('value'), $('#'+$(this).data('id')).val(), $(this) );
                    }
                });
                
                $('select').change(function(){
                    var id = $(this).attr('id'),
                        val = $(this).val();
                    $('[data-id="'+id+'"]').each(function(){
                        var val_item = $(this).data('value'),
                            compare = $(this).data('compare');
                        if( !compare ) { compare = '=';  }
                        change_showhide( compare, $(this), val, val_item );
                    });
                });
                
                
                $('[type="checkbox"]').each(function(){
                    var id = $(this).attr('id');
                    if( $(this).is(':checked') ){
                        $('[data-id="'+id+'"]').closest(o.parent).show();
                    }                    
                });
                
                $('[type="checkbox"]').click(function(){
                    var id = $(this).attr('id');
                    if( $(this).is(':checked') ){
                        $('[data-id="'+id+'"]').closest(o.parent).show();
                    }else{
                        $('[data-id="'+id+'"]').closest(o.parent).hide();
                    }
                });
                
                $('[type="radio"]').change(function(){
                    var id = $(this).attr('name'),
                        val = $(this).val();
                    $('[data-id="'+id+'"]').each(function(){
                        var val_item = $(this).data('value'),
                            compare = $(this).data('compare');
                        if( !compare ) { compare = '=';  }
                        change_showhide( compare, $(this), val, val_item );
                    });
                });
                
                
                function change_showhide( compare, obj, val, val_item ){
                    switch (compare){
                        case "=":
                            if( val == val_item ){ obj.closest(o.parent).show(); }else{ obj.closest(o.parent).hide(); }
                        break;
                        case ">=":
                            if( val >= val_item ){ obj.closest(o.parent).show(); }else{ obj.closest(o.parent).hide(); }
                        break;
                        case "<=":
                            if( val <= val_item ){ obj.closest(o.parent).show(); }else{ obj.closest(o.parent).hide(); }
                        break;
                        case "!=":
                            if( val != val_item ){ obj.closest(o.parent).show(); }else{ obj.closest(o.parent).hide(); }
                        break;
                    }
                }
                function defaultshowhide(compare, value1, value2, obj ){
                    switch (compare) {
                        case "=":
                            if( value1 != value2 ){
                                obj.closest(o.parent).hide();
                            }
                        break;
                        case ">=":
                            if( value1 < value2 ){
                                obj.closest(o.parent).hide();
                            }
                        break;
                        case "<=":
                            if( value1 > value2 ){
                                obj.closest(o.parent).hide();
                            }
                        break;
                        case "!=":
                            if( value1 == value2 ){
                                obj.closest(o.parent).hide();
                            }
                        break;
                    }
                }
    		});
    	}
	});
})(jQuery);