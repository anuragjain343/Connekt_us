
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
    <link rel="icon" href="images/favicon.png" sizes="32x32" type="image/png"> 
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:800&display=swap" rel="stylesheet"> 
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        .template_box {
            background-image: linear-gradient(#fcac35, #f8ba5e);
            max-width: 650px;
            margin: 80px auto;
            border-radius: 35px 0 0 35px;
            padding: 10px 20px 50px;
        }
        .logo {
            max-width: 40px;
        }
        .whte_box {
            background-color: #f1f1f1;
            width: 100%;
            margin-top: 10px;
            padding: 0 15px 15px;
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
            font-size: 30px;
            font-weight: bold;
            letter-spacing: 0px;
            margin: 15px 0;
        }
        .billng_info h4 {
            font-size: 17px;
            font-weight: 600;
            margin:10px 0;
        }
        .bilng_info ul{
            margin:0;
            padding:0;
        }
        .bilng_info ul li {
            list-style-type: none;
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #ddd;
            padding: 12px 0;
        }
        .bilng_info ul li:last-child{
            border-bottom:none;
        }
        .billng_info h5 {
            font-size: 15px;
            font-weight: 500;
            margin: 10px 0;
            margin: 0px;
            color: #333;
            font-style: italic;
        }
        .bilng_info ul li p{
            margin: 0px;
            color: #333;
            margin-left: auto;
            font-size:15px;
        }
        .bilng_info {
            background-color: #fff;
            display: block;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .mt-20{
            margin-top:20px;
        }
    </style>
</head>
    <body>  
        <div class="template_box">
            <img src="<?php echo base_url();?>backend_asset/custom/images/logo_black.png" class="logo" />
            <table class="whte_box">
                <tr class="text-center">
                    <td>
                        <img class="job_img" src="<?php echo base_url();?>backend_asset/custom/images/image_upr.png" />
                    </td>
                </tr>
                <tr class="text-center">
                    <td>
                        <h2 class="helo_head"><p><?php echo ucfirst($msg); ?></p></h2>
                    </td>
                </tr>


                <tr class="billng_info">
                    <td>
                        <h4>Billing Information</h4>
                        <div class="bilng_info">
                            <ul>
                                <li>
                                    <h5>Profile Name</h5>
                                    <p><?php if(!empty($profile_name)) { echo $profile_name; } ?> </p>
                                </li>
                                <li>
                                    <h5>Business Name</h5>
                                    <p><?php if(!empty($business_name)) { echo $business_name; } ?></p>
                                </li>
                                <li>
                                    <h5>Billing Entity</h5>
                                    <p><?php if(!empty($billing_entity)) { echo $billing_entity; } ?></p>
                                </li>
                                <li>
                                    <h5>ABN/ACN Number</h5>
                                    <p>#<?php if(!empty($billing_abn)) { echo $billing_abn; }?></p>
                                </li>
                                <li>
                                    <h5>Billing Address</h5>
                                    <p><?php if(!empty($billing_address)) { echo $billing_address; }?></p>
                                </li>
                                <li>
                                    <h5>Billing Email Address</h5>
                                    <p><?php if(!empty($email)) { echo $email; }?></p>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr class="billng_info mt-20">
                    <td>
                        <h4>Job Information</h4>
                        <div class="bilng_info">
                            <ul>
                                <li>
                                    <h5>Job Title</h5>
                                    <p><?php if(!empty($job_title)) { echo $job_title; }?></p>
                                </li>
                                <li>
                                    <h5>Area of Speciality</h5>
                                    <p><?php if(!empty($job_area_of_spacility)) { echo $job_area_of_spacility; }?></p>
                                </li>
                                <li>
                                    <h5>Date of Posted</h5>
                                    <p> <?php if(!empty($job_posted_date)) { echo $job_posted_date; }?></p>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
