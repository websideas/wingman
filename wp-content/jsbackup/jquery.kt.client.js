(function($){
	$.fn.extend({ 
		kt_client: function(options) {
            var defaults = {
                item_field : '.kt_client_col',
                last_row : 'lastrow',
                last_col : 'lastcol'
            }
            var o = $.extend(defaults,options);
    		return this.each(function() {
                var obj = $(this);
                
                function kt_changeSize(obj){
                    var desktop = obj.data('desktop'),
                        tablet = obj.data('tablet'),
                        mobile = obj.data('mobile'),
                        $width = $(window).width();
                        
                    if($width < 768){
                        kt_contentChange(mobile,obj);
                    }else if($width < 992){
                        kt_contentChange(tablet,obj);
                    }else{
                        kt_contentChange(desktop,obj);
                    }
                }
                
                function kt_contentChange(n,obj){
                    var $stt = obj.find(o.item_field).length,
                        $lastrow;
                    
                    obj.find(o.item_field).removeClass(o.last_row);
                    obj.find(o.item_field).removeClass(o.last_col);
                    
                    obj.find(o.item_field).each(function( index ) {
                        if((index+1) % n == 0){
                            $(this).addClass(o.last_col);
                        }                
                    });
                    
                    if($stt % n == 0){
                        $lastrow = $stt-n-1;
                    }else{
                        $lastrow = Math.floor($stt/n) * n - 1;
                    }
                    obj.find(o.item_field+":gt("+$lastrow+")" ).addClass(o.last_row);
                }
                
                kt_changeSize(obj);
                $(window).resize(function(){
                    kt_changeSize(obj);
                });
    		});
    	}
	});
})(jQuery);