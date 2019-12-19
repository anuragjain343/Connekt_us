<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ConnektUs | Admin</title>
  <link rel="icon" href="<?php echo base_url().ADMIN_THEME.'assets/img/logo_4.png';?>"  type="image/png" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>bootstrap/css/bootstrap.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME;  ?>plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>dist/css/AdminLTE.css?v=6">
  <!-- Material Design -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>dist/css/bootstrap-material-design.css">
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>dist/css/ripples.min.css">
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>dist/css/MaterialAdminLTE.min.css"><?php
   // echo base_url().ADMIN_THEME;
    ?>
  <!-- MaterialAdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>dist/css/skins/skin-purple-light.min.css">
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>dist/css/skins/all-md-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_THEME; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link href="<?php echo base_url().ADMIN_THEME; ?>plugins/tostar/toastr.min.css" rel="stylesheet"> <!-- toastr popup -->
  <link href="<?php echo base_url().ADMIN_THEME; ?>custom/css/admin_custom.css?v=264" rel="stylesheet">
  <!-- -new -->
   <link href="<?php echo base_url().ADMIN_THEME; ?>plugins/ionslider/ion.rangeSlider.css" rel="stylesheet">
   <link href="<?php echo base_url().ADMIN_THEME; ?>plugins/ionslider/ion.rangeSlider.skinNice.css" rel="stylesheet">
   <link href="<?php echo base_url().ADMIN_THEME; ?>plugins/bootstrap-slider/slider.css" rel="stylesheet">
  <!-- end new -->

  <script src="<?php echo base_url().ADMIN_THEME; ?>dist/js/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
   <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

  <?php if(!empty($admin_styles)) load_admin_css($admin_styles); //load required page styles ?>
  <style>
    a.btn.btn-primary.btn-raised.btn-flat.pull-right.btn-go {
    border-radius: 12px;
    margin-right: 10px;
    background: rgba(0,0,0,0.4);
    }
    ul.nav.nav-tabs.box-blue {
    /*border-radius: 6px;*/
    background-color: #fcac35;
    }

  
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini" id="tl_admin_main_body" data-base-url="<?php echo base_url(); ?>">
<div class="wrapper">

  <header class="main-header">  
    <!-- Logo --><?php
     // echo base_url().ADMIN_THEME; 
     ?>
   <a href="<?php echo base_url(); ?>admin/Dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img style="max-width:35px;"  src="<?php echo base_url().ADMIN_THEME.'assets/img/3.png';?>" class="user-image" alt=".."></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img style="max-width: 143px;position:  relative;bottom: 4px;"  src="<?php echo base_url().ADMIN_THEME.'assets/img/1.png';?>" class="user-image" alt=".."></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="color: white;">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               <?php 
              $imgUrl = base_url().ADMIN_DEFAULT_IMAGE;
              $image = $this->session->userdata('image');
              if(!empty($image)){
                  $imgUrl = base_url().ADMIN_IMAGE.$image;
              } ?>
              <img src="<?php echo $imgUrl; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs">Admin</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header col">
                <img src="<?php echo $imgUrl; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo !empty($this->session->userdata('name')) ? ucfirst($this->session->userdata('name')) : 'Admin'; ?> <br> 
                  <small><?php echo $this->session->userdata('email'); ?></small>
                </p>
              </li>
              
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url(); ?>admin/profile" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url(); ?>admin/Dashboard/logout" class="btn btn-default btn-flat" >Logout</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
  
    <aside class="main-sidebar">
    
    <section class="sidebar">
      
        <div class="user-panel">
            <div class="image" id="admin_logo" onclick="home()" style="text-align:center">
             <!--   <img style="max-width:70px;"  src="<?php echo base_url().ADMIN_THEME.'assets/img/logo_2.png';?>" class="user-image" alt=".."> -->
            </div>
        </div>
      
      <ul class="sidebar-menu">
       
        <li class="<?php echo (strtolower($this->uri->segment('2')) == "dashboard" || strtolower($this->uri->segment('2')) == "profile") ? "active" : "" ?> treeview">
          <a class="hvrclr" href="<?php echo base_url(); ?>admin/Dashboard">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li>


        <li class="treeview  <?php echo ( $this->router->fetch_class() == "users" && ($this->router->fetch_method() == "businessDetail" || $this->router->fetch_method() == "individualDetail" || $this->router->fetch_method() == "allBusiness" || $this->router->fetch_method() == "allIndividual")) ? "active" : "" ; ?>">
          <a href="#">
            <i class="fa fa-users"></i> <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          
          <ul class="treeview-menu">
             <li class="detl <?php echo ($this->router->fetch_method() == "businessDetail" || $this->router->fetch_method() == "allBusiness") ? "active" : "" ; ?>">
                <a href="<?php echo site_url('admin/users/allBusiness'); ?>"><i class="fa fa-briefcase"></i>Business</a>
             </li>

            <li class="detl <?php echo ($this->router->fetch_method() == "individualDetail" || $this->router->fetch_method() == "allIndividual") ? "active" : "" ; ?>">
                <a href="<?php echo site_url('admin/users/allIndividual'); ?>"><i class="fa fa-user"></i>Individual</a>
            </li>
          
          </ul>


        </li>

         <li class ="<?php echo ($this->uri->segment('3') == "allSpecialization") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/users/allSpecialization'); ?>">
            <i class="fa fa-certificate"></i> <span>Specializations</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li>

        <li class ="<?php echo ($this->uri->segment('3') == "allStrength") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/users/allStrength'); ?>">
            <i class="fa fa-rss"></i> <span>Strengths</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li>

        <li class ="<?php echo ($this->uri->segment('3') == "allValue") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/users/allValue'); ?>">
            <i class="fa fa-sitemap valyes"></i> <span>Values</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li>

        <li class ="<?php echo ($this->uri->segment('3') == "allJobTitle") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/users/allJobTitle'); ?>">
            <i class="fa fa-briefcase"></i> <span>Job Title</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li> 

         <li class ="<?php echo ($this->uri->segment('2') == "jobs") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/jobs'); ?>">
            <i class="fa fa-tasks "></i> <span>Jobs</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li> 

        <!-- <li class ="<?php echo ($this->uri->segment('3') == "content") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/users/requests'); ?>">
            <i class="fa fa-calendar"></i> <span>Interview requests</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li> -->

        <li class ="<?php echo ($this->uri->segment('3') == "contactsList") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/users/contactUs'); ?>">
            <i class="fa fa-comments"></i> <span>Contact Feedback</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li>
        <li class="treeview <?php echo ($this->uri->segment('2') == "termCondition") ? "active" : "";?> <?php echo ($this->uri->segment('2') == "privacyPolicy") ? "active" : "";?>">
          <a href="#">
            <i class="fa fa-cogs"></i> <span>Content</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="detl">
                <a href="<?php echo site_url('admin/termCondition'); ?>"><i class="fa fa-info"></i>Terms & Conditions</a>
             </li>

            <li class="detl" > <a href="<?php echo site_url('admin/privacyPolicy'); ?>"><i class="fa fa-user-secret"></i>Privacy Policy</a>
            </li>
          
          </ul>


        </li>

         <li class ="<?php echo ($this->uri->segment('3') == "sendNotification") ? "active" : ""; ?> treeview">
          <a class="hvrclr" href="<?php echo base_url('admin/users/sendNotification'); ?>">
            <i class="fa fa-bell"></i> <span>Send Notification</span>
            <span class="pull-right-container">
              <i class=""></i>
            </span>
          </a>  
        </li>
      

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  