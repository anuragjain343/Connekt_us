
(function($) {
    "use strict";

    // On Dom Load
    $(".embed-responsive iframe").addClass("embed-responsive-item");
    $(".carousel-inner .item:first-child").addClass("active");
    $('[data-toggle="tooltip"]').tooltip();

    // Adjust Header Menu On Scroll Down
    $(window).scroll(function() {
        var wScroll = $(this).scrollTop();
        // var wh = $(window).height();

        if (wScroll > 50) {
            $('.navbar-default').addClass('fixed-topbar');
        } else {
            $('.navbar-default').removeClass('fixed-topbar');
        }
    });

    // On click hide collapse menu
    $(".navbar-nav li a").on('click', function(event) {
        $(".navbar-collapse").removeClass("collapse in").addClass("collapse").removeClass('open');
        $(".navbar-toggle").removeClass('open');

    });
    $(".dropdown-toggle").on('click', function(event) {
        $(".navbar-collapse").addClass("collapse in").removeClass("collapse");
        $(".navbar-toggle").addClass('open');
    });
    $('.navbar-toggle').on('click', function() {
        $(this).toggleClass('open');
    });

    // Smooth Scrolling Effect
    $('.smoothscroll').on('click', function(e) {
        e.preventDefault();
        var target = this.hash;

        $('html, body').stop().animate({
            'scrollTop': $(target).offset().top - 90
        }, 1200);
    });


    // countdown
    $('[data-countdown]').each(function() {
        var $this = $(this),
            finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {
            $this.html(event.strftime('<span class="cdown days"><span class="time-count">%-D</span> <p>Days</p></span> <span class="cdown hour"><span class="time-count">%-H</span> <p>Hour</p></span> <span class="cdown minutes"><span class="time-count">%M</span> <p>Min</p></span> <span class="cdown second"> <span><span class="time-count">%S</span> <p>Sec</p></span>'));
        });
    });

/* ========================================================== */
/*   Owl Carousel For Gallery                                 */
/* ========================================================== */

    $("#gallery__slider").owlCarousel({
        loop: true,
        nav: true,
        center: true,
        dots: false,
        autoplay: true,
    autoplayTimeout: 3000,
        autoplayHoverPause:false,
    smartSpeed: 450,
        responsiveClass: true,
        responsive: {
            315:{
                items:1
            },
            510:{
                items:2
            },
            768:{
                items:3,
            },
            1199:{
                items:3,
            }
    }
    });


    // Hero Mockup Slider
    function hero_mockup_carousel() {
        var owl = $("#hero-mockup-slider");
        owl.owlCarousel({
            loop: true,
            margin: 10,
            smartSpeed: 1000,
            responsiveClass: true,
            navigation: true,
            items: 1,
            addClassActive: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true
        });
    }
    hero_mockup_carousel();

    // Adjusting the center position of mockup slider
    var wh = $(window).height(); // Getting the window height
    var sh = $('.hero-area-inner').height(); // Getting the hero-area-inner height
    var mt = (wh - sh) / 2;
    $('.hero-mockup-slider-area').css({
        'margin-top': mt + 'px'
    })

    // About Mockup Slider
    function about_mockup_carousel() {
        var owl = $("#about-mockup-slider");
        owl.owlCarousel({
            loop: true,
            margin: 10,
            smartSpeed: 2000,
            responsiveClass: true,
            navigation: true,
            items: 1,
            addClassActive: true,
            dots: false,
            autoplay: false,
            autoplayTimeout: 5000,
            autoplayHoverPause: false,
        });
        $('#about-next').on('click', function() {
            owl.trigger('next.owl.carousel');
        });
        $('#about-prev').on('click', function() {
            owl.trigger('prev.owl.carousel');
        });
    }
    about_mockup_carousel();

    // Testimonial Slick Carousel

    $('.testimonial-text-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        draggable: false,
        fade: true,
    });

    $('.testimonial-image-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.testimonial-text-slider',
        dots: false,
        arrows: true,
        centerMode: true,
        focusOnSelect: true,
        centerPadding: '10px',
        responsive: [{
            breakpoint: 450,
            settings: {
                dots: false,
                slidesToShow: 3,
                centerPadding: '0px',
            }
        }, {
            breakpoint: 420,
            settings: {
                autoplay: true,
                dots: false,
                slidesToShow: 1,
                centerMode: false,
            }
        }]
    });

    // Team Carousel Slider
    function team_carousel() {
        var owl = $("#team-carousel");
        owl.owlCarousel({
            loop: true,
            margin: 10,
            smartSpeed: 2000,
            responsiveClass: true,
            navigation: true,
            center: true,
            addClassActive: true,
            dots: false,
            autoplay: false,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                480: {
                    items: 1,
                    nav: false
                },
                768: {
                    items: 2,
                    nav: false
                },
                667: {
                    items: 2,
                    nav: false
                },
                1024: {
                    items: 3,
                    nav: false
                }
            }
        });
        $('#team-next').on('click', function() {
            owl.trigger('next.owl.carousel');
        });
        $('#team-prev').on('click', function() {
            owl.trigger('prev.owl.carousel');
        });
    }
    team_carousel();

    // Vertical News Ticker
    var nt_example1 = $('#vr-carousel').newsTicker({
        row_height: 150,
        max_rows: 3,
        duration: 400000,
        pauseOnHover: 3,
        center: false,
        prevButton: $('#vr-carousel-prev'),
        nextButton: $('#vr-carousel-next')
    });

    // Wow js Init
    var wow = new WOW({
        boxClass: 'wow', // animated element css class (default is wow)
        animateClass: 'animated', // animation css class (default is animated)
        offset: 0, // distance to the element when triggering the animation (default is 0)
        mobile: true, // trigger animations on mobile devices (default is true)
        live: true, // act on asynchronously loaded content (default is true)
        callback: function(box) {
            // the callback is fired every time an animation is started
            // the argument that is passed in is the DOM node being animated
        },
        scrollContainer: null // optional scroll container selector, otherwise use window
    });
    wow.init();

    // Counter Js
    $('.counter').counterUp({
        delay: 10,
        time: 2000
    });

}(jQuery));


// On Window Load
jQuery(window).load(function() {
    "use strict";
    // Preloader
    $('.preloader-area').fadeOut();
    $('.preloader-area').delay(350).fadeOut('slow');
    $('body').delay(550);
});


/*new Js*/
$('#allVideos1').lightGallery({
  selector : ".video-box a",
  thumbnail:true,
    autoplayControls:false,
    fullScreen:false,
    share:false,
    zoom:false,
    download:false
});

$('#allVideos2').lightGallery({
  selector : ".video-box-1 a",
  thumbnail:true,
    autoplayControls:false,
    fullScreen:false,
    share:false,
    zoom:false,
    download:false
});
