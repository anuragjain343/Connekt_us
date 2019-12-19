<!DOCTYPE html>
<html lang="en-US"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content=""> 
<meta name="keywords" content="">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
<meta name="format-detection" content="telephone=no">
<?php $front_home_assets = base_url().FRONT_THEME.'home_assets/'; ?>
<link rel="icon" type="image/png" href="<?php echo $front_home_assets ?>logo-simple.png"> 
<title>Page not found | <?php echo SITE_TITLE; ?></title>
<link rel="stylesheet" id="wp-block-library-css" href="<?php echo $front_home_assets ?>style.min.css" type="text/css" media="all">
<link rel="stylesheet" id="ms-aos-style-css" href="<?php echo $front_home_assets ?>aos.css" type="text/css" media="all">
<link rel="stylesheet" id="ms-fonts-style-css" href="<?php echo $front_home_assets ?>fonts.css" type="text/css" media="all">
<link rel="stylesheet" id="ms-fonts-icon-style-css" href="<?php echo $front_home_assets ?>style.css" type="text/css" media="all">
<link rel="stylesheet" id="ms-icon-style-css" href="<?php echo $front_home_assets ?>all.min.css" type="text/css" media="all">
<link rel="stylesheet" id="ms-font-awesome-style-css" href="<?php echo $front_home_assets ?>fontawesome.min.css" type="text/css" media="all">
<link rel="stylesheet" id="ms-slick-style-css" href="<?php echo $front_home_assets ?>slick.css" type="text/css" media="all">
<link rel="stylesheet" id="ms-main-style-css" href="<?php echo $front_home_assets ?>style(1).css" type="text/css" media="all">
<link rel="stylesheet" id="ms-main-responsive-css" href="<?php echo $front_home_assets ?>responsive.css" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo $front_home_assets ?>fontello.css" type="text/css"></head>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<body class="error404" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">
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

	<section class="error-page" style="background-image:url(&#39;http://connektus.testbeds.space/wp-content/uploads/2019/07/error-bg.jpg&#39;)">
		<div class="container">
			<div class="error-content">
				<h2>Oops!</h2>
				<p>It looks like the page you were looking for has been moved or deleted. Why not try some of the options below. </p>
				<ul class="site-menu">
					<li><a href="http://connektus.testbeds.space/">HOME</a></li>
					<li><a href="http://connektus.testbeds.space/contact/">CONTACT US</a></li>
				</ul>
				<ul class="app-list">
					<li>
						<a href="https://apps.apple.com/au/app/connektus/id1381341242" target="_blank">
							<img src="<?php echo $front_home_assets ?>app-store-btn.png" alt="Download Button">
						</a>
					</li>
					<li>
						<a href="https://play.google.com/store/apps/details?id=com.connektus" target="_blank">
							<img src="<?php echo $front_home_assets ?>play-store-btn.png" alt="Download Button">
						</a>
					</li>
				</ul>
			</div>
			<div class="error-img">
				<figure>
					<img src="<?php echo $front_home_assets ?>error-man.png">
				</figure>
			</div>
		</div>
	</section>

</body>
</html>