
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>ConnektUs</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="<?php echo base_url();?>backend_asset/custom/images/favicon.png" sizes="32x32" type="image/png"> 
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:800&display=swap" rel="stylesheet"> 
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        .template_box {
            background-image: linear-gradient(#fcac35, #f8ba5e);
            max-width: 100%;
            /*max-width: 650px;*/
            margin: 20px auto;
            border-radius: 35px 0 0 35px;
            padding: 10px 50px 50px;
            color: #222 !important;
        }
        .logo {
            max-width: 40px;
        }
        .whte_box{
            background-color:#f1f1f1;
            width:100%;
            margin-top:10px;
        }
        .text-center{
            text-align:center;
        }
        .job_img {
            max-width: 200px;
            margin-top: 35px;
        }
        .helo_head {
            font-family: 'Fira Sans', sans-serif;
            padding-top: 18px;
            font-size: 30px;
            font-weight: bold;
            letter-spacing: 0px;
        }
        .invitn {
            font-size: 16px;
            padding: 10px 0 10px;
            font-weight: 500;
            font-style: italic;
            line-height: 27px;
        }
        .dte_tme {
            display: block;
            font-weight: bolder;
        }
        .inlne_blck{
            display: inline-block;
        }
        .dte_tme p {
            margin: 0 0;
            font-weight: bold;
            /* font-family: fira-sans; */
            font-size: 18px;
        }
        .at_desgn {
            display: inline-block;
            border: 1px solid #000;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            line-height: 30px;
            color: #333;
            font-weight: bold;
        }
        .adres {
            margin-top: 0px;
            color: #f90;
            font-weight: 800;
            font-size: 18px;
            margin-bottom: 8px;
        }
        .invitn p {
            width: 82%;
            margin: 0 auto;
        }
        .invitn p span{
            font-style:normal;
            font-weight:bold;
        }
        .notfictn {
            margin: 0 0;
            width: 70%;
            margin: 0 auto 15px;
            line-height: 21px;
            font-size: 13px;
        }
        @media only screen and (max-width: 767px){
        .template_box {
            padding: 10px 20px 50px;
        }
        }
        img + div { display:none; }
    </style>
</head>
    <body>  
        <div class="template_box">
            <img src="<?php echo base_url();?>backend_asset/custom/images/logo_black.png" class="logo" />
            <table class="whte_box text-center">
                <tr>
                    <td><?php if(!empty($new_image)){?>
                        <img class="job_img" src="<?php echo base_url();?>backend_asset/custom/images/job_apply.png"/>
                    <?php }else{?>
                        <img class="job_img" src="<?php echo base_url();?>backend_asset/custom/images/job_image.png" />
                    <?php }?>
                    </td>
                </tr>
                <tr>
                    <td class="helo_head">Hello! <?php echo $fullName; ?></td>
                </tr>
                <tr>
                    <td class="invitn">
                        <p><?php echo $msg; ?></p>
                    </td>

                </tr>
                  <tr>
                    <td>
                        <p class="notfictn"><?php if(!empty($sub_msg)) { echo $sub_msg; } ?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dte_tme">
                            <div class="inlne_blck">
                                <p><?php if(!empty( $date)) { echo $date; } ?></p>
                            </div>
                            <div class="inlne_blck">
                                <p><?php if(!empty( $time)) { echo $time; } ?></p>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php if(!empty( $location)){ ?>
                <tr>
                    <td>
                        <p class="at_desgn">  at

                        </p>
                    </td>
                </tr>
                 <?php } ?>
                <tr>
                    <td>
                        <p class="adres"><?php if(!empty( $location)) { echo $location; } ?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="notfictn">Have any questions? Feel free to reach out to one of our lovely staff members at support@connektus.com.au</p>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
