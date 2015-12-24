(function($){
    "use strict"; // Start of use strict


    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    $(document).ready(function() {
        $('body').bind('wc_fragments_loaded wc_fragments_refreshed', function (){
            $('.mCustomScrollbar').mCustomScrollbar();
        });

        $( 'body' ).bind( 'added_to_cart', function( event, fragments, cart_hash ) {
            $('.mCustomScrollbar').mCustomScrollbar();
        });

        if ($.fn.easyZoom) {
            $('.easyzoom').easyZoom();
        }

        init_ProductQuickView();
        init_productslickwoo();
        init_kt_remove_cart();
        init_carouselwoo();
        init_woo_quantily();
        init_gridlistToggle();
        init_currency();
        
        $('.woocommerce-accordions').accordion({
            'heightStyle': 'content',
            'header': '.accordions-title' 
        });


        $('.woocommerce-category-products-tab').tabs();


        $('.woocommerce-products-carousel-tab').each(function(){
            var $this = $(this),
                $heading = $this.find('.block-heading-tabs'),
                $count = parseInt($heading.data('count'), 10);
            $this.tabs({
                active: Math.ceil($count/2) - 1,
                show: { effect: "fadeIn", duration: 1500 }
            });
        });

    });


    /* ---------------------------------------------
     QickView
     --------------------------------------------- */
    function init_ProductQuickView(){
        $('body').on('click', '.product-quick-view', function(e){
            e.preventDefault();
            var objProduct = $(this);
            objProduct.addClass('loading');

            var data = {
                action: 'frontend_product_quick_view',
                security : ajax_frontend.security,
                product_id: objProduct.data('id')
            };

            $.post(ajax_frontend.ajaxurl, data, function(response) {
                objProduct.removeClass('loading');
                $.magnificPopup.open({
                    mainClass : 'mfp-zoom-in',
                    showCloseBtn: false,
                    removalDelay: 500,
                    items: {
                        src: '<div class="themedev-product-popup woocommerce mfp-with-anim">' + response + '</div>',
                        type: 'inline'
                    },
                    callbacks: {
                        open: function() {
                            var obj = $('.single-product-quickview-images');
                            obj.imagesLoaded(function(){
                                obj.slick({
                                    slidesToShow: 1
                                });
                            });
                            var $popup = $('.themedev-product-popup');
                            $popup.imagesLoaded(function(){
                                setTimeout(function(){
                                    $popup.addClass('animate-width');
                                }, 500);
                                setTimeout(function(){
                                    $popup.addClass('add-content');
                                }, 1000);
                            });
                            $('.kt-product-popup form').wc_variation_form();
                        },
                        change: function() {
                            $('.kt-product-popup form').wc_variation_form();
                        }
                    }
                });
            });
            return false;
        });


    }



    /* ---------------------------------------------
     Owl carousel woo
     --------------------------------------------- */
    function init_carouselwoo(){

        $('.woocommerce-carousel-wrapper').each(function(){


            var wooCarousel = $(this),
                objCarousel = $(this).find('ul.shop-products'),
                options = wooCarousel.data('options') || {};
            options.theme = 'owl-kttheme';


            if(typeof options.desktop !== "undefined"){
                options.itemsDesktop = [1199,options.desktop];
                options.items = options.desktop;
            }
            if(typeof options.desktopsmall !== "undefined"){
                options.itemsDesktopSmall = [991,options.desktopsmall];
            }
            if(typeof options.tablet !== "undefined"){
                options.itemsTablet = [768,options.tablet];
            }
            if(typeof options.mobile !== "undefined"){
                options.itemsMobile = [480,options.mobile];
            }

            if(typeof options.navigation_pos === "undefined"){
                options.navigation_pos = '';
            }

            if(typeof options.navigation_icon === "undefined"){
                options.navigation_icon = 'fa fa-angle-left|fa fa-angle-right';
            }
            var owlNavigationIconArr = options.navigation_icon.split('|', 2);
            options.navigationText = ["<i class='"+owlNavigationIconArr[0]+"'></i>", "<i class='"+owlNavigationIconArr[1]+"'></i>"];


            options.afterInit  = function(elem) {
                if(options.navigation_pos == "top" && options.navigation){
                    var $buttons = elem.find('.owl-buttons');
                    $buttons.prependTo(wooCarousel);
                }
            };

            objCarousel.imagesLoaded(function() {
                objCarousel.owlCarousel(options);
            });


        });
    }


    /* ---------------------------------------------
     Woocommercer Quantily
     --------------------------------------------- */
    function init_woo_quantily(){
        $('body').on('click','.quantity-group .quantity-plus',function(){
            var obj_qty = $(this).closest('.quantity-group').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                min_qty = parseInt(obj_qty.attr('min')),
                max_qty = parseInt(obj_qty.attr('max')),
                step_qty = parseInt(obj_qty.attr('step'));
            val_qty = val_qty + step_qty;
            if(max_qty && val_qty > max_qty){ val_qty = max_qty; }
            obj_qty.val(val_qty);
        });
        $('body').on('click','.quantity-group .quantity-minus',function(){
            var obj_qty = $(this).closest('.quantity-group').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                min_qty = parseInt(obj_qty.attr('min')),
                max_qty = parseInt(obj_qty.attr('max')),
                step_qty = parseInt(obj_qty.attr('step'));
            val_qty = val_qty - step_qty;
            if(min_qty && val_qty < min_qty){ val_qty = min_qty; }
            if(!min_qty && val_qty < 0){ val_qty = 0; }
            obj_qty.val(val_qty);
        });
    }


    /* ---------------------------------------------
     Remove Cart Item
     --------------------------------------------- */
    function init_kt_remove_cart(){
        $( 'body' ).on('click','#header .bag-product a.remove',function(e){
            e.preventDefault();

            var product_id = $(this).attr('data-id'),
                item_key = $(this).attr('data-itemkey');

            $('.mini-cart .shopping-bag').append('<span class="loading_overlay"><i class="fa fa-spinner fa-pulse"></i></span>');

            var data = {
                action: 'fronted_remove_product',
                security : ajax_frontend.security,
                product_id : product_id,
                item_key : item_key
            };

            $.post(ajax_frontend.ajaxurl, data, function(response) {
                $('.mini-cart').html(response.content_product);
                $('.mini-cart .loading_overlay').remove();
                $('.mCustomScrollbar').mCustomScrollbar();
            }, 'json');

        });
    }


    function init_productslickwoo(){

        $('.single-product-main-images').slick({
            asNavFor: '.single-product-main-thumbnails',
            infinite: true,
            draggable: false
        });

        var options = {
            asNavFor: '.single-product-main-images',
            infinite: true,
            focusOnSelect: true,
            slidesToShow: 3
        };

        //1199, 991, 768, 480

        if( $('.main-class').hasClass('col-md-12') ){
            options.slidesToShow = 4;
            options.vertical = true;
            options.verticalSwiping = true;
            options.responsive = [
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3,
                        vertical : false,
                        verticalSwiping: false
                    }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 1
                  }
                }
            ];
        }else{

        }


        $('.single-product-main-thumbnails').slick(options);
    }
    
    /* ---------------------------------------------
     Grid list Toggle
     --------------------------------------------- */
    function init_gridlistToggle(){
        $('ul.gridlist-toggle a').on('click', function(e){
            e.preventDefault();
            var $this = $(this),
                $gridlist = $this.closest('.gridlist-toggle'),
                $products = $this.closest('#main').find('ul.shop-products');

            var data = {
                action: 'frontend_update_posts_layout',
                security : ajax_frontend.security,
                layout: $this.data('layout')
            };

            $.post(ajax_frontend.ajaxurl, data, function(response) { });

            $gridlist.find('a').removeClass('active');
            $this.addClass('active');
            $products
                .removeClass($this.data('remove'))
                .addClass($this.data('layout'));
                
        });
    }

    function init_currency(){
        if(typeof woocs_drop_down_view !== "undefined") {
            if(woocs_drop_down_view == 'no'){
                $('.menu-bars-currency a').on('click', function(e){
                    e.preventDefault();
                    woocs_redirect($(this).data('currency'));
                });

            }

        }
    }


})(jQuery); // End of use strict