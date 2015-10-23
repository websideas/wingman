/*
 * jQuery KT ktFooter - v1.0
 * Copyright (c) 2015 Cuongdv
 *
 * Dual licensed under the MIT and GPL licenses:
 *	http://www.opensource.org/licenses/mit-license.php
 *	http://www.gnu.org/licenses/gpl.html
 *
 
    $('#footer').ktFooter({
        onInit: function(){
            console.log('call');
        },
        start: function(){
            console.log('start');
        },
        end: function(){
            console.log('end');
        }
    });
    
    
    
    $('#footer')
        .on('footer.start', function(){
            console.log('footer.start');
        })
        .on('footer.end', function(){
            console.log('footer.end');
        });
    
    
 
 
 
 */


;(function ($, w) {
	"use strict";
    
    var methods = (function () {
        var _window = $(window),
            
            start = function (_footer, o) {
                var _page = $('#page');
                o.start.call(this);
                
                _footer.trigger("footer.start");
                
                _page.css({'padding-bottom': _footer.outerHeight()});
                _page.removeClass(o.className);
            },
            end = function (_footer, o) {
                var _page = $('#page');
                _page.addClass(o.className);
                
                o.end.call(this);
                _footer.trigger("footer.end");
            };
            
        return {
            // public methods
            start: function(){
                if (this.length) {
                    var $this = this, 
                        o = $this.data('footer-options');
                        
                    console.log(o);
                    start($this, o);
                }
                return this;
            },
            
            end: function(){
                if (this.length) {
                    var $this = this,
                        o = $this.data('footer-options');
                    end($this, o);
                }
                return this;
            },
            
            init: function (op) {
				return this.each(function () {
					var $this = $(this);
                    if ($this.data('footer-options')) {
						return false;
					}
                    var o = $.extend({}, $.fn.ktFooter.defaults, op);

                    $this.data('footer-options', o);
                    
                    if( _window.width() > o.width && $this.outerHeight() < _window.height() ){
                		start($this, o);
                	}else{
                		end($this, o);
                	}
                    
                    _window.on("resize", function( event ) {
                        if( _window.width() > o.width && $this.outerHeight() < _window.height()){
                    		start($this, o);
                    	}else{
                    		end($this, o);
                    	}
                    });
                    
                    o.onInit.call(this);
                    
                });
            }
        }
        
    })();
    
    $.fn.ktFooter = function (method, args) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		}
		else {
			return $.error('Method ' +  method + ' does not exist on jQuery.fn.ktFooter');
		}
	};
    
    $.fn.ktFooter.defaults = {
        className: 'static-footer',
        width: 992,
        onInit: $.noop,
        start: $.noop,
        end: $.noop
    };
    
})(jQuery, window);