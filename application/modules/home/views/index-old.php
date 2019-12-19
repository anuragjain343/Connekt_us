<?php if($this->session->flashdata('successfull')){?>
   <script type="text/javascript">
    swal({
      title: "Success",
      text: "You’ve successfully verified your email! Please login using your ConnektUs App",
      icon: "success",
      button: "ok",
    });
    </script>
    <?php }?>
    
    <?php if($this->session->flashdata('fail')){?>
    <script type="text/javascript">
    swal({
      title: "Oops!",
      text: "This verification link has been expired",
      icon: "error",
      button: "ok",
    });
    </script>
<?php }?>

<section id="home-area" class="bannerSec">
    <div class="container">
        <div class="bannerInner">
            <div class="row">
               <div class="col-md-6 col-sm-6">
                    <div class="hero-caption">
                        <h1 class="hero-title">ConnektUs</h1>
                        <h5>Hiring. Made. Simple.</h5>
                        <p>Our mission is to inspire people to discover happiness in the workplace by giving you more control of your future. Looking for a new opportunity that is right for you? Perhaps you need to hire someone that suits your business needs?</p>
                        <div class="hero-button-area">
                            <a href="#download-area" class="smoothscroll btn btn-default btn-outline">Download our free app</a>
                        </div>
                    </div>                    
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="bannerImg">
                        <img src="<?php echo base_url().UC_ASSETS_IMG;?>banner-phone.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="video-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="videoHidden">
                    <div style="display:none;" id="video1">
                        <video class="lg-video-object lg-html5" controls preload="none">
                            <source src="<?php echo base_url().UC_ASSETS_VDO;?>ConnektUs.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div id="allVideos1">
                    <div class="video-box">
                        <a href="" data-html="#video1">
                            <img src="<?php echo base_url().UC_ASSETS_IMG;?>play.svg" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="videoHidden">
                    <div style="display:none;" id="video2">
                        <video class="lg-video-object lg-html5" controls preload="none">
                            <source src="<?php echo base_url().UC_ASSETS_VDO;?>ConnektUs.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div class="video-txt">
                    <h4 class="about-caption-title">Learn More</h4>
                    <p>Learn more about ConnektUs and how we can help you discover your dream job or find suitable candidates that suit your business needs.</p>
                    <div id="allVideos2">
                        <div class="video-box-1">
                            <a href="" data-html="#video2" class="btn btn-send-green mt-15">Watch Video</a>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<section class="SubscribeSecs inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <h3 class="text-uppercase text-center">Subscribe to Our Newsletter</h3>
               <form  class="subscrie-form" id="newsLetter">
                        <div class="input-group">
                            <input type="email" class="form-control" id="mc-email" placeholder="Your email address here..." name="email_newsLetter">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info" id="newsletterSubscribe">Subscribe</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</section>


    <!-- Header menu Ends -->
    <!-- About Area Starts -->
    <div class="about-area inner-padding about-area-green" id="about-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-sm-12">
                    <div class="section-title wow fadeInDown">
                        <h2>About Us</h2>
                        <p>ConnektUs gives everyone more control of their future at their fingertips.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-sm-12">
                    <div class="row wow fadeIn">
                        <div class="col-xs-12 col-md-6 col-sm-6 prn">
                            <div class="about-caption">
                                <h4 class="about-caption-title">About ConnektUs</h4>
                                <p>Our mobile platform has been designed for simplicity. We make hiring simple by allowing Job Seekers and Employers to conveniently connekt to help you discover your future.</p>
                                <p>With our in-depth and specific search capabilities, you can search detailed profiles with relevant information to help you make the right decision for you or your business. Manage the entire process from searching for the right candidate to offering them a position.</p>
                                <div class="content-lists">
                                    <div class="list-item">
                                        <i class="icon_briefcase"></i>
                                        <div class="list-content">
                                            <h3>Hiring? Find talented candidates here.</h3>
                                            <p>Find potential candidates that meet your needs. Search for specific details to find the best fit candidate for you or your business.</p>
                                        </div>
                                    </div>
                                    <div class="list-item">
                                        <i class="icon_search-2"></i>
                                        <div class="list-content">
                                            <h3>Need a change? Connekt with people that can help.</h3>
                                            <p>Find employers and recruiters that can help you discover your next job. Search for people that suit your needs.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-sm-6 pln text-center">
                            <img src="<?php echo base_url().UC_ASSETS_IMG;?>about-us.png" alt="About Slide">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="features-area inner-padding about-area-green" id="features-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-sm-12">
                    <div class="section-title wow fadeInDown">
                        <h2>App Features</h2>
                        <p>ConnektUs has many exciting features to make the hiring process so simple.</p>
                    </div>
                </div>
                 <div class="about-feat text-left about-left">
            <div class="col-lg-6">
              <img class="img-responsive hidden-md hidden-xs hidden-sm" src="<?php echo base_url().UC_ASSETS_IMG;?>3mobiles.png" alt="2mobiles">
            </div>
            <div class="col-lg-6 about-right">
            <div class="col-xs-6">
                <div class="ftItem">
                    <i class="icon_search"></i>
                    <h3>Search Algorithms</h3>
                    <p>Find specific candidates that meet your requirements with our clever search capabilities.</p>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="ftItem">
              <i class="icon_search-2"></i>
                <h3>Elegant Design</h3>
              <p>Our elegant and user-friendly design makes this app so easy to use! Try it out for yourself.</p>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="ftItem">
              <i class="icon_pin_alt"></i>
              <h3>Custom MAP</h3>
              <p>Find the right candidate in more ways than one! Check out our custom MAP.
                It’s pretty cool.</p>
          </div>
            </div>
            <div class="col-xs-6">
                <div class="ftItem">
              <i class=" icon_chat_alt"></i>
              <h3>Connekt via chat</h3>
              <p>Chat with potential candidates before arranging  meetings/interviews. It’s also a great way to get to know someone.</p>
          </div>
            </div>
            <div class="col-xs-6">
                <div class="ftItem">
              <i class="icon_documents_alt"></i>
              <h3>Request Interview</h3>
              <p>Yes, you can even setup interviews within the Chat function! Everything in one convenient location.</p>
          </div>
            </div>
            <div class="col-xs-6">
                <div class="ftItem">
              <i class=" icon_datareport"></i>
              <h3>Track Progress</h3>
              <p>Candidates are kept informed as discussions progress. You can even let candidates know if they have been offered a position!</p>
          </div>
            </div>
          </div>
        </div>
            </div>
        </div>
    </div>
    <div class="screenshot-area inner-padding screenshot-area-green" id="screenshot-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-sm-12 text-center">
                    <div class="section-title wow fadeInDown">
                        <h2>Screenshots</h2>
                        <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. It has survived not only five centuries.</p> -->
                    </div>
                </div>
            </div>
            <!---slide-->
                        <div class="row">
              <div class="col-xs-12">
                <div id="gallery__slider" class="owl-carousel owl-theme">
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(9).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(3).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(4).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(5).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(6).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(7).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(8).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(10).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(11).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(12).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(13).jpg">
                  </div>
                  <div class="gallery__item text-center">
                    <img src="<?php echo base_url().UC_ASSETS_IMG;?>Screenshot(14).jpg">
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <!--End Screenshot Area Ends -->
    <!--Apps Download Area Starts -->
    <div class="app-download-area inner-padding" id="download-area">
        <div class="app-download-bg">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="section-title-white wow fadeIn text-left">
                        <h2>Download ConnektUs Today !</h2>
                        <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p> -->
                        <div class="downloadStore">
                            
                            <a href="https://play.google.com/store/apps/details?id=com.connektus" target="_blank" id="androidDownload">
                            <img src="<?php echo base_url().UC_ASSETS_IMG;?>appstore.png"></a>
                            <a href="https://itunes.apple.com/au/app/connektus/id1381341242?mt=8" target="_blank" id="iosDownload"><img src="<?php echo base_url().UC_ASSETS_IMG;?>playstore.png"></a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="appdownloadImg">
                        <a href="#"><img class="pulse" src="<?php echo base_url().UC_ASSETS_IMG;?>download-app.png"></a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!--Apps Download Area Ends -->

    <!-- Contact Us Area Starts -->
    <div class="contact-area inner-padding contact-area-green" id="contact-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-sm-12">
                    <div class="section-title wow fadeInDown">
                        <h2>Contact Us</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="contact-form wow fadeIn">
                    <form id="contactUs" >
                        <div class="col-xs-12 col-sm-6">
                            <div class="google_map" >
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d25216.75408670366!2d144.94564133038924!3d-37.811261054159594!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642b8c21cb29b%3A0x1c045678462e3510!2sMelbourne+VIC+3000%2C+Australia!5e0!3m2!1sen!2sin!4v1533893282270" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <p>
                                        <input type="text" class="form-control" name="name" placeholder="Your Name*">
                                    </p>
                                     <span style="color:red !important;" class="text-danger error font_12" id="name_error" ></span>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <p>
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email Address*">
                                    </p>
                                     <span style="color:red !important;" class="text-danger error font_12" id="email_error" ></span>
                                </div>
                                <div class="col-xs-12 col-sm-12">
                                    <p>
                                        <input type="text" class="form-control" name="subject" placeholder="Subject*">
                                    </p>
                                     <span style="color:red !important;" class="text-danger error font_12" id="subject_error" ></span>
                                </div>
                                <div class="col-xs-12 col-sm-12">
                                    <p>
                                        <textarea name="message" rows="8" class="form-control" placeholder="Your Message*"></textarea>
                                    </p>
                                     <span style="color:red !important;" class="text-danger error font_12" id="message_error" ></span>
                                </div>
                                <div class="col-xs-12 col-sm-12 text-right">
                                    <input type="submit" class="btn btn-default btn-send-green" value="SEND">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Us Area Ends -->
    <!-- Footer Area Starts -->
<!-- <script>

hide_loader();
function show_loader(){
    $('#tl_home_loader').show();
}

function hide_loader(){
    $('#tl_home_loader').hide();
}

$(document).ready(function() {
$("#contactUs").validate({
        rules: {
            name: {
                required: true,
                maxlength: 20
            },
            email: {
                required: true,
                email:true,
 
            },
            subject: {
                required: true,
                maxlength: 20
            },
            message: {
                required: true,
                minlength: 10,
                maxlength: 100
            }
        }, 

        messages: {
        name: {
            required: "Name field is required.", 
            maxlength:"Max characters should be 20.",
        },
        subject: { 
            required: "Subject field is required.",
            maxlength:"Max characters should be 20."
        },
        
        email: {
                required: "Email field is required.",
                email: "Please enter a valid email address.",
        },
        message:{
                required:"Message field is required.",
                minlength:"Please enter atleast 10 characters.",
                maxlength:"Max characters should be 100.",
        }
    } 
    });

});

$("#contactUs").submit(function(e){
  e.preventDefault();
  if ($('#contactUs').valid()==false) {
        //toastr.error('');
        return false;
}
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"home/contactUs",
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this), 
    beforeSend: function() {
            show_loader();
             //return false;
            //$(btn).buttonLoader('start');                               
    },
    success:function(res){
        hide_loader();
        var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
           });
          }
          if(obj.messages.success){
            toastr.success(obj.messages.success);
            var surl = 'home'; 
            //window.setTimeout(function() { window.location = surl; }, 2000);
          } 
          if(obj.messages.unsuccess){
            toastr.error(obj.messages.unsuccess);
          }
          
    }
  });
});


</script>
 -->