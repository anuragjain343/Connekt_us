<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Uconnekt</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">

body, html {
height: 100%;
background-repeat: no-repeat;
background: #fcac35; /* Old browsers */

}

.login_box{
    background:#f7f7f7;
    border:3px solid #F4F4F4;
    padding-left: 15px;
    margin-bottom:25px;
    }
.input_title{
    color:rgba(164, 164, 164, 0.9);
    padding-left:3px;
        margin-bottom: 2px;
    }

hr{
    width:100%;
}
    
.welcome{
    font-family: "myriad-pro",sans-serif;
    font-style: normal;
    font-weight: 100;
    color:#FFFFFF;
    margin-bottom:25px;
    margin-top:50px;

}

.login_title{
    font-family: "myriad-pro",sans-serif;
    font-style: normal;
    font-weight: 100;
    color:#fcac35;
}

.card-container.card {
    max-width: 350px;
    margin-top: 31px;
    height: 200px;

}

.btn {
    font-weight: 700;
    height: 36px;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    cursor: pointer;
    border-radius:50px;
    background:#fcac35;
    height: 48px;
    margin-bottom:10px;
}

/*
 * Card component
 */
.card {
    background-color: #FFFFFF;
    /* just in case there no content*/
    padding: 1px 25px 30px;
    margin: 0 auto 25px;
    margin-top: 15%x;
    /* shadows and rounded borders */
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}

.profile-img-card {
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}

.btn.btn-signin {
    /*background-color: #4d90fe; */
    background-color: rgb(104, 145, 162);
    /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
    padding: 0px;
    font-weight: 700;
    font-size: 14px;
    height: 36px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: none;
    -o-transition: all 0.218s;
    -moz-transition: all 0.218s;
    -webkit-transition: all 0.218s;
    transition: all 0.218s;
}


img{
    margin-left: 510px;
}

a{
    cursor:pointer;
    color: #fff !important;
}
	</style>
</head>
<body>

   

    <div class="container">
    <h1 class="welcome text-center"><img style="display:inline-block" src="" class="img-responsive" alt="" /></h1>

        <img  src="<?php echo base_url().ADMIN_THEME.'assets/img/white_nw.png';?>" class="user-image" alt="..">
        <div class="card card-container">
        <h2 class='login_title text-center'>Welcome</h2>
        <hr>

            <form class="form-signin text-center">
               
                <a class="btn btn-lg" href="<?php echo base_url(); ?>admin" >Admin Login</a>
            </form><!-- /form -->
        </div><!-- /card-container -->
    </div><!-- /container -->
</body>
</html>