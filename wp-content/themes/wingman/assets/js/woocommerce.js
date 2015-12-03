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

        if( $('.single-product .main-class').hasClass('col-md-12') ){
            init_productslickwoo();
        }else{
            init_productcarouselwoo();
        }
        
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
                active: Math.ceil($count/2) - 1
            });
        });

    });

    /* ---------------------------------------------
     Product Quick View
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
                    removalDelay: 500,
                    items: {
                        src: '<div class="kt-product-popup woocommerce mfp-with-anim">' + response + '</div>',
                        type: 'inline'
                    },
                    callbacks: {
                        open: function() {
                            $('.single-product-quickview-images').waitForImages(function() {
                                $(this).owlCarousel({
                                    items: 1,
                                    itemsDesktop : [1199,1], //1 items between 1000px and 901px
                                    itemsDesktopSmall : [991,1], // betweem 992px and 769px
                                    itemsTablet: [768,1], //1 items between 768 and 480
                                    itemsMobile : [479, 1],
                                    theme: 'carousel-navigation-center',
                                    autoHeight: true,
                                    navigation: true,
                                    navigationText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                                    pagination: false
                                });
                            });
                            $('.kt-product-popup form').wc_variation_form();

                        },
                        close: function(){
                            objProduct.css('display','none');
                            setTimeout(function(){
                                objProduct.removeAttr('style');
                            }, 50 );
                        },
                        change: function() {
                            $('.kt-product-popup form').wc_variation_form();
                        }
                    }
                });
            });
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
            if(typeof options.navigation_pos === "undefined"){
                options.navigation_pos = '';
            }

            if(typeof options.navigation_icon === "undefined"){
                options.navigation_icon = 'fa fa-angle-left|fa fa-angle-right';
            }
            var owlNavigationIconArr = options.navigation_icon.split('|', 2);
            options.navigationText = ["<i class='"+owlNavigationIconArr[0]+"'></i>", "<i class='"+owlNavigationIconArr[1]+"'></i>"];

            if(typeof options.mobile !== "undefined"){
                options.itemsMobile = [479,options.mobile];
            }
            options.afterInit  = function(elem) {
                if(options.navigation_pos == "top" && options.navigation){
                    var $buttons = elem.find('.owl-buttons');

                    $buttons.prependTo(wooCarousel);
                }
            };


            objCarousel.waitForImages(function() {
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


    var sync1 = $("#sync1");
    var sync2 = $("#sync2");

    function init_productcarouselwoo(){

        sync1.owlCarousel({
            singleItem : true,
            slideSpeed : 1000,
            items : 1,
            navigation: true,
            pagination: false,
            afterAction : syncPosition,
            autoHeight: true,
            responsiveRefreshRate : 200,
            navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        });

        sync2.owlCarousel({
            theme : 'woocommerce-thumbnails',
            items : 3,
            itemsCustom : [[991,3], [768, 3], [480, 3]],
            navigation: true,
            navigationText: false,
            pagination:false,
            autoHeight: true,
            responsiveRefreshRate : 100,
            afterInit : function(el){
                el.find(".owl-item").eq(0).addClass("synced");
            }
        });

        $("#sync2").on("click", ".owl-item", function(e){
            e.preventDefault();
            var number = $(this).data("owlItem");
            sync1.trigger("owl.goTo", number);
        });

    }
    function syncPosition(el){
        var current = this.currentItem;
        $("#sync2")
            .find(".owl-item")
            .removeClass("synced")
            .eq(current)
            .addClass("synced")
        if($("#sync2").data("owlCarousel") !== undefined){
            center(current)
        }
    }
    function center(number){
        var sync2visible = sync2.data("owlCarousel").owl.visibleItems;

        var num = number;
        var found = false;

        for(var i in sync2visible){
            if(num === sync2visible[i]){
                var found = true;
            }
        }

        if(found===false){
            if(num>sync2visible[sync2visible.length-1]){
                sync2.trigger("owl.goTo", num - sync2visible.length+2)
            }else{
                if(num - 1 === -1){
                    num = 0;
                }
                sync2.trigger("owl.goTo", num);
            }
        } else if(num === sync2visible[sync2visible.length-1]){
            sync2.trigger("owl.goTo", sync2visible[1])
        } else if(num === sync2visible[0]){
            sync2.trigger("owl.goTo", num-1)
        }
    }


    function init_productslickwoo(){
        $('.single-product-main-images').slick({
            asNavFor: '.single-product-main-thumbnails',
            infinite: false,
        });
        $('.single-product-main-thumbnails').slick({
            slidesToShow: 4,
            vertical: true,
            verticalSwiping: true,
            asNavFor: '.single-product-main-images',
            infinite: false,
            focusOnSelect: true,
            responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                  }
                },
                {
                  breakpoint: 801,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                }
            ]
        });
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