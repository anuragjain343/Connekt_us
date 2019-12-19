/* AOS */
// AOS.init();
AOS.init({
   once: true
})


/* Sticky Header */
$(window).on("scroll", function() {
    if($(window).scrollTop() >100) {
        $(".header").addClass("sticky");
    } else {
        $(".header").removeClass("sticky");
    }
});
jQuery(document).ready(function(){
  	jQuery(".nav-icon").click(function () {
        jQuery(".mobile-menu").show(300).addClass("show-nav");
        jQuery("body").toggleClass("body-hide");
   	});
   	jQuery(".change-icon").click(function () {
        jQuery(".mobile-menu").hide(300).removeClass("show-nav");
        jQuery("body").toggleClass("body-hide");
        
    });
    

    /* menu */
    
      let touchEvent = 'ontouchstart' in window ? 'touchstart' : 'click';
      jQuery(".menu-item-has-children a" ).after( "<span class='menu_sub'></span>" );
      jQuery(document).on(touchEvent, '.menu_sub', function(){
      jQuery(this).toggleClass("open");
      jQuery(this).next('ul').slideToggle();
      });


    //---- Accordian ----- //
    jQuery('.single-faq.active .faq-content').slideDown();
    jQuery('.single-faq a').on( "click", function(e) {
        if(jQuery(this).parents('.single-faq').hasClass('active')){
            jQuery(this).next('.faq-content').stop(true,false).slideUp();
            jQuery(this).parents('.single-faq').removeClass('active')
        }
        else{
            jQuery('.single-faq').removeClass('active');
            jQuery('.faq-content').stop(true,false).slideUp();
            jQuery(this).parents('.single-faq').addClass('active');
            jQuery(this).next('.faq-content').stop(true,false).slideDown();
        }
        return false;
    }); 

    //*---- Tab ----//
    $(".tab_content").hide();
    $(".tab_content:first").show();
    $("ul.tabs li").click(function() {
      $(".tab_content").hide();
      var activeTab = $(this).attr("rel"); 
      $("#"+activeTab).fadeIn();    
      $("ul.tabs li").removeClass("active");
      $(this).addClass("active");
    $(".tab_drawer_heading").removeClass("d_active");
    $(".tab_drawer_heading[rel^='"+activeTab+"']").addClass("d_active");
    
    });
    $(".tab_drawer_heading").click(function() {
      $(".tab_content").hide();
      var d_activeTab = $(this).attr("rel"); 
      $("#"+d_activeTab).fadeIn();
    $(".tab_drawer_heading").removeClass("d_active");
      $(this).addClass("d_active");
    $("ul.tabs li").removeClass("active");
    $("ul.tabs li[rel^='"+d_activeTab+"']").addClass("active");
    });
    $('ul.tabs li').last().addClass("tab_last");
  
});
/* Slick Slider */
$('.iphone-slider').slick({
  infinite: true,
  arrows:false,
  dots:false,
  autoplay: true,
  autoplaySpeed:2000,
  fade: true,
});

$('.recruiter-process').slick({
  infinite: true,
  arrows:false,
  dots:false,
  autoplay: true,
  autoplaySpeed:3000,
   slidesToShow: 3,
  slidesToScroll: 1,
   responsive: [
    {
      breakpoint:991,
      settings: {
        slidesToShow:2,
      }
    },
    {
      breakpoint: 639,
      settings: {
        slidesToShow: 1,
      }
    }
  ]
});

$('.testimonial-slider').slick({
  infinite: true,
  arrows:false,
  dots:true,
  autoplay: true,
  autoplaySpeed:5000,
});

/* phone number */
$(document).ready(function(){
$('[id^=input_1_4]').keypress(validateNumber);
});
function validateNumber(event) {
var key = window.event ? event.keyCode : event.which;
if (event.keyCode === 8 || event.keyCode === 46) {
return true;
} else if ( key < 48 || key > 57 ) {
return false;
} else {
return true;
}
};

$(function () {
    $('#scroll-bottom').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top
        }, 1000, 'linear');
    });


/* load more blog list*/

var practiceList = jQuery('#arpList > ul');
    $('.arp-link-btn a').on('click', function() {
        
        var requestInProcess = false, 
            hasMore = true;
        var $this = $(this);
        
        // count for offset;
        var offset = practiceList.find('li').length;
        var cat  = practiceList.data('blog');
        if(!requestInProcess && hasMore) {
            requestInProcess = true;
            jQuery.ajax({
                url: scriptParams.ajaxurl,
                data: {action: "cc_load_more", offset: offset, cat: cat},
                beforeSend: function() {
                    $this.parent().addClass('running');
                },
                complete: function() {
                    $this.parent().removeClass('running');
                },
                success: function(data) {
                    if(data != "") {
                        practiceList.append(data);
                    } else {
                        hasMore = false;
                        $this.parent().hide();
                    }                    
                    requestInProcess = false;
                },
                dataType: 'html'
            });
        }
    });



});



/* Tab */
//*---- Tab ----//
$(document).ready(function(){
    $(".tab_content").hide();
    $(".tab_content:first").show();
    $("ul.tabs li").click(function() {
      $(".tab_content").hide();
      var activeTab = $(this).attr("rel"); 
      $("#"+activeTab).fadeIn();    
      $("ul.tabs li").removeClass("active");
      $(this).addClass("active");
    $(".tab_drawer_heading").removeClass("d_active");
    $(".tab_drawer_heading[rel^='"+activeTab+"']").addClass("d_active");
    
    });
    $(".tab_drawer_heading").click(function() {
      $(".tab_content").hide();
      var d_activeTab = $(this).attr("rel"); 
      $("#"+d_activeTab).fadeIn();
    $(".tab_drawer_heading").removeClass("d_active");
      $(this).addClass("d_active");
    $("ul.tabs li").removeClass("active");
    $("ul.tabs li[rel^='"+d_activeTab+"']").addClass("active");
    });
    $('ul.tabs li').last().addClass("tab_last");
  
});