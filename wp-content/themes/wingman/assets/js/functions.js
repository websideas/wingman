(function($){
    "use strict"; // Start of use strict


    /* --------------------------------------------
     Mobile detect
     --------------------------------------------- */
    var ktmobile;
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        ktmobile = true;
        $("html").addClass("mobile");
    }
    else {
        ktmobile = false;
        $("html").addClass("no-mobile");
    }


    /* ---------------------------------------------
     Scripts initialization
     --------------------------------------------- */
    
    $(window).load(function(){
        
        $(window).trigger("scroll");
        $(window).trigger("resize");
        init_ktCustomCss();



    });
    
    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    $(document).ready(function() {

        $(window).trigger("resize");

        setInterval(init_remove_space, 100);

        // Page Loader
        $("body").waitForImages(function(){
            $(".kt_page_loader").delay(200).fadeOut('slow');
        });

        init_shortcodes();
        init_carousel();
        init_popup();

        init_backtotop();
        init_parallax();
        init_MainMenu();
        init_MobileMenu();
        init_masonry();
        init_kt_image();
        init_SearchFull();
        init_kt_animation();
        init_loadmore();
        init_VCLightBox();
        init_mailchimp();
        //init_smooth_scrolling();

        kt_likepost();
        
        kt_widget_categories();
        
        if($('#wpadminbar').length){
            $('body').addClass('admin-bar');
        }

        setInterval(init_masonry, 1000);

        $('.button-toggle').click(function(e){
            e.preventDefault();
            $(this).closest('#nav').toggleClass('is-opened');
        });
        
        $('.widget-container .kt_widget_tabs').tabs();




        $('.kt_client .style2').kt_client();

    });
    
    $(window).resize(function(){
        init_ktCustomCss();

        if ($.fn.ktSticky) {
            /**==============================
             ***  Sticky header
             ===============================**/
            $('.header-container.sticky-header').ktSticky({
                //contentSticky : '.nav-container'
            });
        }

        if ($.fn.ktFooter) {
            /**==============================
             ***  Fixed footer
             ===============================**/
            $('body.footer_fixed #footer').ktFooter();
        }


        /**==============================
         ***  Height in js
         ===============================**/
        init_js_height();

        /**==============================
         ***  Disable mobile menu in desktop
         ===============================**/
        if ($(window).width() >= 992) {
            $('body').removeClass('menu-animate');
            $('.mobile-nav-bar').removeClass('active');
        }


        /**==============================
        ***  Equal height
        ===============================**/
        $('.equal_height').each(function(){
            var equal_height_element;
            if($(this).hasClass('equal_height_element')){
                equal_height_element = $(this).children('.wpb_column').children('.wpb_wrapper').children('*');
            }else{
                equal_height_element = $(this).children();
            }
            equal_height_element.matchHeight({ byRow: true });
        });
        
        

    });
    /**==============================
     ***  Smooth Scrolling
     ===============================**/
    function init_smooth_scrolling(){
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length && !target.hasClass('vc_tta-panel') && !target.hasClass('vc_icon_element-link')) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 2000);
                    return false;
                }
            }
        });
    }
    /* ---------------------------------------------
     Remove all space empty
     --------------------------------------------- */
    function init_remove_space() {

        $("p:empty").remove();
        $(".wpb_text_column:empty").remove();
        $(".wpb_wrapper:empty").remove();
        $(".wpb_column:empty").remove();
        $(".wpb_row:empty").remove();

    }

    /* ---------------------------------------------
     Masonry
     --------------------------------------------- */
    function init_masonry(){
        $(".blog-posts-masonry .row").waitForImages(function(){
            $(this).masonry();
        });
    }

    /* ---------------------------------------------
     VC Lightbox
     --------------------------------------------- */
    function init_VCLightBox(){
        $('.kt_lightbox').each(function(){

            var $type = $(this).data('type'),
                $effect = $(this).data('effect'),
                $iframe_width = $(this).data('contentwidth'),
                $removalDelay = 500;
                
            if(typeof $effect === "undefined" || $effect == ''){
                $effect = '';
                $removalDelay = 0;
            }
            $(this).find('.vc_icon_element-link').magnificPopup({
                type: $type,
                mainClass: $effect,
                removalDelay: $removalDelay,
                midClick: true,
                callbacks: {
                    beforeOpen: function() {
                        if($type == 'image'){
                            this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                            this.st.mainClass = $effect;
                        }else if($type == 'iframe'){
                            this.st.iframe.markup = this.st.iframe.markup.replace('mfp-iframe-scaler', 'mfp-iframe-scaler mfp-with-anim');
                            this.st.mainClass = $effect;
                        }
                    },
                    open: function() {
                        if($type == 'iframe'){
                            $('.mfp-iframe-holder .mfp-content').css('max-width', $iframe_width);
                        }
                    }
                }
            });
        });
    }


    /* ---------------------------------------------
     Google Map Short code
     --------------------------------------------- */
    function init_VCGoogleMap() {
        var styleMap = [];
        styleMap[0] = [];
        styleMap[1] = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#fcfcfc"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#fcfcfc"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]}],
        styleMap[2] = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#b4d4e1"},{"visibility":"on"}]}],
        styleMap[3] = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"administrative.country","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.country","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.country","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":"-100"},{"lightness":"30"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"},{"gamma":"0.00"},{"lightness":"74"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"lightness":"3"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}],
        styleMap[4] = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#0c0b0b"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#090909"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d4e4eb"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#fef7f7"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9b7f7f"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#fef7f7"}]}],
        styleMap[5] = [{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"on"},{"gamma":"1.82"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"gamma":"1.96"},{"lightness":"-9"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"},{"lightness":"25"},{"gamma":"1.00"},{"saturation":"-100"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#ffaa00"},{"saturation":"-43"},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"},{"hue":"#ffaa00"},{"saturation":"-70"}]},{"featureType":"road.highway.controlled_access","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"on"},{"saturation":"-100"},{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"saturation":"-100"},{"lightness":"40"},{"visibility":"off"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"gamma":"0.80"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"off"}]}],
        styleMap[6] = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"administrative","elementType":"labels","stylers":[{"saturation":"-100"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"gamma":"0.75"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":"-37"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f9f9f9"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"40"},{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"saturation":"-100"},{"lightness":"-37"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"saturation":"-100"},{"lightness":"100"},{"weight":"2"}]},{"featureType":"landscape.natural","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"80"}]},{"featureType":"poi","elementType":"labels","stylers":[{"saturation":"-100"},{"lightness":"0"}]},{"featureType":"poi.attraction","elementType":"geometry","stylers":[{"lightness":"-4"},{"saturation":"-100"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"},{"visibility":"on"},{"saturation":"-95"},{"lightness":"62"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road","elementType":"labels","stylers":[{"saturation":"-100"},{"gamma":"1.00"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"gamma":"0.50"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":"-100"},{"gamma":"0.50"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"},{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"lightness":"-13"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"lightness":"0"},{"gamma":"1.09"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"},{"saturation":"-100"},{"lightness":"47"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"lightness":"-12"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"},{"lightness":"77"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"lightness":"-5"},{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"saturation":"-100"},{"lightness":"-15"}]},{"featureType":"transit.station.airport","elementType":"geometry","stylers":[{"lightness":"47"},{"saturation":"-100"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"water","elementType":"geometry","stylers":[{"saturation":"53"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":"-42"},{"saturation":"17"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":"61"}]}],
        styleMap[7] = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#b4d4e1"},{"visibility":"on"}]}];
        styleMap[8] = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]

        $(".googlemap").each(function () {
            var mapObj = $(this),
                scrollwheel = (mapObj.data('scrollwheel') == '1') ? false : true,
                mapStyle = parseInt(mapObj.data('style'));
            mapObj.gmap3({
                marker: {values: [{address: mapObj.data('location'), options: {icon: mapObj.data('iconmap')}}]},
                map: {
                    options: {
                        zoom: mapObj.data('zoom'),
                        mapTypeId: mapObj.data('type').toLowerCase(),
                        scrollwheel: scrollwheel,
                        styles: styleMap[mapStyle]
                    }
                }
            });
        });
    }

    /* -------------------------------------------
     Parallax
     --------------------------------------------- */
    function init_parallax(){
        if (($(window).width() >= 1024) && (ktmobile == false)) {
            $(".kt-parallax").each(function(){
                $(this).parallax("50%", 0.1);
            });
        }
    }

    /* ---------------------------------------------
     Search
     --------------------------------------------- */
    function init_SearchFull(){
        $('.mini-search a, a.mobile-search').magnificPopup({
            type: 'inline',
            mainClass : 'mfp-zoom-in',
            items: { src: '#search-fullwidth' },
            focus : 'input[name=s]',
            removalDelay: 200
        });
    }

    /* ---------------------------------------------
     VC PieChart
     --------------------------------------------- */
    function init_VCPieChart(){
        $('.kt-piechart-wrapper').waypoint(function() {
            $(".chart").each(function() {
                var $chart = $(this),
                    $chart_label = $chart.data('label'),
                    $label_value;

                if($chart_label == '' ) $chart_label = $chart.data('percent');

                $chart.easyPieChart({
                    easing: 'easeInOutQuad',
                    barColor: $chart.data('fgcolor'),
                    animate: 2000,
                    trackColor: $chart.data('bgcolor'),
                    lineWidth: $chart.data('linewidth'),
                    lineCap: $chart.data('linecap'),
                    size: $chart.data('size'),
                    scaleColor: false,
                    onStep: function(from, to, percent) {
                        if(isNaN($chart_label)){
                            $label_value = $chart_label;
                        }else{
                            $label_value = Math.round(percent / to * $chart_label);
                        }
                        $(this.el).find('.percent span').text($label_value);

                    }
                });
            });
        }, { offset:'95%' });
    }

    /* ---------------------------------------------
     VC Coming Soon
     --------------------------------------------- */
    function init_VCComingSoon() {
        var coming_html = '<div class="wrap"><div class="value-time">%D</div><div class="title">' + ajax_frontend.days + '</div></div> <div class="wrap"><div class="value-time">%H</div><div class="title">' + ajax_frontend.hours + '</div></div><div class="wrap"><div class="value-time">%M</div><div class="title">' + ajax_frontend.minutes + '</div></div> <div class="wrap"><div class="value-time">%S</div><div class="title">' + ajax_frontend.seconds + '</div><div class="clearfix"></div></div>';
        $('.coming-soon').each(function () {
            var date = $(this).data('date');
            $(this).countdown(date, function (event) {
                $(this).html( event.strftime(coming_html) );
            });

        });
    }


    /* ---------------------------------------------
     Blog loadmore
     --------------------------------------------- */
    function init_loadmore(){

        var ajax_request;
        $('body').on('click','.blog-loadmore-button',function(e){
            e.preventDefault();
            var $loadmore = $(this),
                $loading = $loadmore.find('span'),
                $posts = $loadmore.closest('.blog-posts'),
                $content = $posts.children('.blog-posts-content'),
                $type = $posts.data('type'),
                $current = parseInt($posts.attr('data-current')),
                $paged = $current + 1,
                $total = parseInt($posts.data('total')),
                $query_vars = $posts.data('queryvars');

            if(typeof $query_vars === "undefined"){ $query_vars = ajax_frontend.query_vars; }

            var data = {
                action: 'fronted_loadmore_archive',
                security : ajax_frontend.security,
                settings: $posts.data('settings'),
                queryvars: $query_vars,
                paged : $paged
            };
            if(ajax_request && ajax_request.readystate != 4){
                ajax_request.abort();
            }
            $loading.addClass('fa-spin');
            ajax_request = $.post(ajax_frontend.ajaxurl, data, function(response) {
                $loading.removeClass('fa-spin');
                $posts.attr('data-current', $paged);

                if($paged == $total){
                    $loadmore.closest('.blog-posts-loadmore').hide();
                }

                if($type == 'grid' || $type == 'masonry'){
                    var $row = $content.children('.row');
                    if($type == 'masonry'){
                        var $elems = $(response.html);
                        $row.waitForImages(function() {
                            $row.append($elems).masonry( 'appended', $elems, true );
                            loadmore_append();
                        });
                    }else{
                        $row.append(response.html);
                        loadmore_append();
                    }
                }else{
                    $content.append(response.html);
                    loadmore_append();
                }
                $content.find('.post-item').removeClass('loadmore-item');
            }, 'json');
        });
    }


    /* ---------------------------------------------
     Loadmore append
     --------------------------------------------- */
    function loadmore_append(){
        $('[data-toggle="tooltip"]').tooltip();
        $('.loadmore-item .wp-audio-shortcode, .loadmore-item .wp-video-shortcode').mediaelementplayer( );
        init_carousel();
    }


    /* ---------------------------------------------
     KT custom css
     --------------------------------------------- */
    function init_ktCustomCss(){
        $('.kt_custom_css').each(function(){
            var $this = $(this);
            if(!$this.children('style').length){
                $this.html('<style>'+$this.data('css')+'</style>');
            }
        });
    }

    /* ---------------------------------------------
     Shortcodes
     --------------------------------------------- */
    function init_shortcodes(){
        "use strict";
        
        // Tooltips (bootstrap plugin activated)
        $('[data-toggle="tooltip"]').tooltip();

        // Skill bar
        if (typeof $.fn.waypoint !== 'undefined') {

            $('.kt-skill-wrapper').waypoint(function () {
                $(this).find('.kt-skill-item-wrapper').each(function( i ){
                    var $skill_bar = jQuery(this).find('.kt-skill-bar');
                    var time_out = i * 200;
                    setTimeout(function () {
                        $skill_bar.css({"width": $skill_bar.data('percent') + '%'});
                    }, time_out);

                });
            }, { offset:'85%' });


            $('.creative-left, .creative-right, .kt-heading-wrapper').each(function() {
                var $this = $(this);
                $this.waypoint(function () {
                    $this.addClass('animated');
                }, {offset: '90%'});
            });



        }

        //init_VCPieChart();
        init_VCComingSoon();
        init_VCGoogleMap();

        $('.gallery-grid').each(function(){
            $(this).photosetGrid({
                highresLinks: $(this).data('popup'),
                gutter: $(this).data('margin')+'px'
            });
        });
    }

    /* ---------------------------------------------
     Main Menu
    --------------------------------------------- */
    function init_MainMenu(){
        $("ul#main-navigation").superfish({
            hoverClass: 'hovered',
            popUpSelector: 'ul.sub-menu-dropdown,.kt-megamenu-wrapper',
            animation: {},
    		animationOut: {}
        });
    }
    
    /* ---------------------------------------------
     Mobile Menu
    --------------------------------------------- */
    function init_MobileMenu(){


    }
    
    /* ---------------------------------------------
     Back to top
     --------------------------------------------- */
    function init_backtotop(){
    	var backtotop = $('#backtotop').hide();
    	$(window).scroll(function() {
    		($(window).scrollTop() != 0) ? backtotop.fadeIn() : backtotop.fadeOut();
    	});
    	backtotop.click(function(e) {
            e.preventDefault();
    		$('html, body').animate({scrollTop:0},500);
    	});
    }

    /* ---------------------------------------------
     Owl carousel
     --------------------------------------------- */
    function init_carousel(){

        $('.kt-owl-carousel').each(function(){

            var objCarousel = $(this),
                options = $(objCarousel).data('options') || {},
                func_cb;
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

            func_cb =  window[options.callback];

            options.afterInit  = function(elem) {
                if(options.navigation_pos == "top" && options.navigation){
                    var $buttons = elem.find('.owl-buttons');
                    $buttons.prependTo(objCarousel.closest('.owl-carousel-kt'));
                }

                if(options.pagination && options.navigation){
                    var that = this;
                    that.paginationWrapper.appendTo(objCarousel.closest('.owl-carousel-kt'));
                }

                if( typeof func_cb === 'function'){
                    func_cb( 'afterInit',   elem );
                }

            };

            objCarousel.waitForImages(function() {
                objCarousel.owlCarousel(options);
            });

        });

    }




    /* ---------------------------------------------
     Mailchimp
     --------------------------------------------- */
    function init_mailchimp(){

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
                }
            }, 'json');
        });
    }
    
    /* ---------------------------------------------
     Kt Animation
     --------------------------------------------- */
    function init_kt_animation(){

        $('.animation-effect').each(function(){
            var window_width = $(window).width(),
                $animate_wrap = $(this),
                $class_animate = $animate_wrap.attr('data-animation'),
                $time = $animate_wrap.attr('data-timeeffect'),
                $count = 0;
            $animate_wrap.children().each(function(i){
                var $animate = $(this);
                
                if($animate.hasClass('first')){
                    $count = 0;
                }
                
                if( !$time ){ $time = '200'; }
                
                var animation_delay = $count * $time;
                $count++;
                if (window_width > 991) {
					$animate.css({
						"-webkit-animation-delay": animation_delay + "ms",
						"-moz-animation-delay": animation_delay + "ms",
						"-ms-animation-delay": animation_delay + "ms",
						"-o-animation-delay": animation_delay + "ms",
						"animation-delay": animation_delay + "ms"
					});
                    
                    $animate.css({'opacity':'0'});
                    $animate.waypoint(function() {
						$animate.addClass("animated").addClass($class_animate);
                        $animate.css({'opacity':'1'});
					}, {
						triggerOnce: true,
						offset: "90%"
					});
                    
				}else{
                    $animate.addClass("no-effect");
                }
            });
        });
    }

    /* ---------------------------------------------
     KT Image animation
     --------------------------------------------- */
    function init_kt_image() {
        // Image
        $('.kt-image-animation').each(function(){
            var $this = $(this);
            $this.css({'opacity':'0'});
            $this.waypoint(function () {
                $this.addClass("animated").addClass($this.data('animation'));
                $this.css({'opacity':'1'});
            }, { offset:'85%', triggerOnce:true });
        });
    }


    
    /* ---------------------------------------------
     Kt Client
     --------------------------------------------- */
     
    function kt_changeSize(field){
        $(field).each(function(){
            var desktop = $(this).data('desktop'),
                tablet = $(this).data('tablet'),
                mobile = $(this).data('mobile'),
                $width = $(window).width();
                
            if($width < 768){
                kt_contentChange(mobile,$(this));
            }else if($width < 992){
                kt_contentChange(tablet,$(this));
            }else{
                kt_contentChange(desktop,$(this));
            }
        });
    }


    function kt_contentChange(n,field){
        $(field).each(function(){
            var $stt = $(this).find('.kt_client_col').length,
                $lastrow;
            
            $(this).find('.kt_client_col').removeClass('lastrow');
            $(this).find('.kt_client_col').removeClass('lastcol'); 

            $(this).find('.kt_client_col').each(function( index ) {
                if((index+1) % n == 0){
                    $(this).addClass('lastcol');
                }                          
            });
            
            if($stt % n == 0){
                $lastrow = $stt-n-1;
            }else{
                $lastrow = Math.floor($stt/n) * n - 1;
            }
            $(this).find(".kt_client_col:gt("+$lastrow+")" ).addClass('lastrow');
        });
    }


    /* ---------------------------------------------
     Height 100%
     --------------------------------------------- */
    function init_js_height(){

        $(".item-height-window").css('height', $(window).height());
        $(".item-height-parent").each(function(){
            $(this).height($(this).parent().first().height());
        });

    }

    /**==============================
    ***  Sticky sidebar
    ===============================**/
    function kt_sidebar_sticky(){
        if(!ktmobile){
            var margin_sidebar_sticky = 20;
            if($('#wpadminbar').length > 0){
                margin_sidebar_sticky += parseInt( $('#wpadminbar').outerHeight() );
            }
            if($('.sticky-header.header-container').length > 0){
                margin_sidebar_sticky += parseInt( ajax_frontend.sticky_height );
            }

            $('.sidebar').theiaStickySidebar({
                additionalMarginTop: margin_sidebar_sticky
            });
        }

    }
    
    /**==============================
    ***  Like Post
    ===============================**/
    function kt_likepost(){
        $('body').on('click','a.kt_likepost',function(e){
            e.preventDefault();
            var objPost = $(this);
            if(objPost.hasClass('liked')) return false;
            var data = {
                action: 'fronted_likepost',
                security : ajax_frontend.security,
                post_id: objPost.data('id')
            };
            $.post(ajax_frontend.ajaxurl, data, function(response) {
                objPost
                    .text(response.count)
                    .addClass('liked')
                    .attr('title', objPost.data('already'));
            }, 'json');
        });
    }

    
    /**==============================
    *** Widget Categories Icon Toggle
    ===============================**/
    function kt_widget_categories(){
        $('.widget-container ul.children').closest('li').append('<span class="icon-toggle"></span>');
        $('.widget-container ul.children').hide();
        $('body').on('click','.widget-container .icon-toggle',function(){
            $(this).closest('li').find('.children').slideToggle();
        });
    }
    
    /**==============================
    *** Popup Content
    ===============================**/
    function init_popup(){
        if($('#popup-wrap').length > 0){
            var $disable_mobile = $('#popup-wrap').data('mobile'),
                time_show = $('#popup-wrap').data('timeshow');
            
                if(ktmobile == false || (ktmobile == true && $disable_mobile == 0)){
                    setTimeout(function(){
                        $.magnificPopup.open({
                            items: { src: '#popup-wrap' },
                            type: 'inline',
                            callbacks: {
                                beforeClose: function() {
                                    var data = {
                                        action: 'fronted_popup',
                                        security : ajax_frontend.security,
                                    };
                                    $.post(ajax_frontend.ajaxurl, data, function(response) { }, 'json');
                                }
                            }
                        });
                    }, time_show*1000);
                }
        }
    }

})(jQuery); // End of use strict



/* ---------------------------------------------
 Testimonial callback
 --------------------------------------------- */

function kt_testimonial_thumbnail( _type, elem ){
    "use strict"; // Start of use strict

    if( _type === 'afterInit' ) {
        var thumbnail_url = [];
        elem.find('.owl-item').each(function () {
            thumbnail_url.push(jQuery(this).find('.testimonial-item').data('thumbnail'));
        });

        var $pagination = elem.next('.owl-pagination');
        $pagination.prependTo(elem.closest('.owl-carousel-kt'));

        $pagination.find('.owl-page').each(function (index) {
            if (thumbnail_url[index] != '') {
                jQuery(this).find('span').html('<img src="' + thumbnail_url[index] + '" />');
            }
        });
    }
}