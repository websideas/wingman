$(document).ready(function() {
    setParallaxContent();
    init_scrolling();

    $(window).scroll(function() {

        setParallaxContent();
    });
});

/* ---------------------------------------------
 Parallax
 --------------------------------------------- */
function setParallaxContent() {

    var p = {
        scrollTop: $(window).scrollTop(),
        windowHeight: $(window).height(),
        contentTop: $('.cd-intro').position().top,
        contentHeight: $('.cd-intro').height()
    };

    // determine scrollTop's bounds where content enters & exits the window
    p.lowerBound = p.contentTop - p.windowHeight;
    p.upperBound = p.contentTop + p.contentHeight;

    // determine scrollTop's position percentage (x2) in relation to bounds
    p.percent = (p.scrollTop - p.lowerBound) / (p.upperBound - p.lowerBound) * 2;

    $('.cd-intro-content').animate({
        opacity: 1 - Math.abs(p.percent - 1)
    }, 1);
}

/* ---------------------------------------------
 Smooth Scrolling
 --------------------------------------------- */
function init_scrolling() {

    $('body')
        .on('click', 'a[href*=#]:not([href=#])', function (e) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        }).on('click', 'a:not([href*=mailto],[href*=tel],[href*=#])', function (e) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') || location.hostname === this.hostname) {
                $(".page-loader").fadeIn("slow");
                var href = $(this).attr("href");
                $("#page").fadeOut("slow", function () {
                    window.location = href;
                });
                return false;
            }
        });

}