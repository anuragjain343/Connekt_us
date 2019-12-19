<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Page Needs  -->
    <meta charset="utf-8">
    <title><?php echo SITE_TITLE;?></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="<?php echo base_url().UC_ASSETS_IMG;?>3.png" sizes="32x32" type="image/png"> 
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700,800,900" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>elegant-font.css">
    <!--Owl Carousel CSS -->
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>owl.theme.default.css">
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>owl.transitions.css">
    <!-- Animated CSS -->
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>animate.min.css">
    <!-- Application CSS --> 
    <!-- 3D Coverflow CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().UC_ASSETS_CSS;?>jquery-flowgallery.css">
    <!-- Slick CSS -->
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>slick.css">
    <!-- Theme CSS-->
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>default.css?v=4">
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>typography.css">
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>style.css?v=5">
    <link rel="stylesheet" href="<?php echo base_url().UC_ASSETS_CSS;?>responsive.css?v=6">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().UC_ASSETS_CSS;?>notifIt.css">
     <link rel="stylesheet" type="text/css" href="<?php echo base_url().UC_ASSETS_CSS;?>lightgallery.min.css">
    <link href="<?php echo base_url().ADMIN_THEME; ?>plugins/tostar/toastr.min.css" rel="stylesheet"> <!-- toastr popup -->
    <script src="<?php echo base_url().UC_ASSETS_JS;?>jquery-1.11.3.min.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>jquery.counterup.min.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>wow.min.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>jquery.waypoints.min.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>jquery.newsTicker.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>bootstrap.min.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>lightgallery-all.min.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>owl.carousel.min.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>modernizr.custom.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>jquery.responsiveGallery.js"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>slick.min.js"></script>
   <!--  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> 
    <script type="text/javascript" src="<?php echo base_url().UC_ASSETS_JS;?>notifIt.js"></script>
    <script src="<?php echo base_url().ADMIN_THEME; ?>plugins/tostar/toastr.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php if(ENVIRONMENT == 'production'){ ?>
        <!-- Facebook Pixel Code Start -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '783197938714733');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=783197938714733&ev=PageView&noscript=1"
        /></noscript>
        <!-- Facebook Pixel Code End -->

        <!-- Global site tag (gtag.js) - Google Analytics Start -->
        <script async src="https://www.googletagmanager.com/gtag/jsid=UA-139142575-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-139142575-1');
        </script>
        <!-- Google Analytics End -->
    <?php } ?>
</head>
<body data-spy="scroll" data-target="#scroll-menu" data-offset="100">
    <!-- Preloader Starts -->
    
    <div class="preloader-area" >
        <div class="preloader-inner">
            <div class="preloader">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
<div class="topbar-area">
    <nav class="navbar navbar-default nav-green navbar-fixed-top" id="scroll-menu">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url().UC_ASSETS_IMG;?>logo.png"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <div class="nav navbar-nav navbar-right text-right">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#home-area" class="smoothscroll">Home</a></li>
                        <li><a href="#about-area" class="smoothscroll">About</a></li>
                        <li><a href="#features-area" class="smoothscroll">Features</a></li>
                        <li><a href="#screenshot-area" class="smoothscroll">Screenshot</a></li>
                        <li><a href="#download-area" class="smoothscroll">Download</a></li>
                        <li><a href="#contact-area" class="smoothscroll">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>
