<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SITE_TITLE ?> | Verify your email address</title>
	
</head>

<body style="font-family: 'Source Sans Pro', sans-serif; padding:0; margin:0;">
    <table style="max-width: 750px; margin: 0px auto; width: 100% ! important; background: #F3F3F3; padding:30px 30px 30px 30px;" width="100% !important" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="text-align: center; background: #fcac35;">
                <table width="100%" border="0" cellpadding="30" cellspacing="0">	
                    <tr>
                        <td>
                            <img style="max-width: 125px; width: 100%;padding: 10px;" src="<?php echo base_url();?>backend_asset/custom/images/logo_image.png">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td style="text-align: center;">
                <table width="100%" border="0" cellpadding="30" cellspacing="0" bgcolor="#fff">
                    <tr>
                        <td>

                            <h3 style="color: #333; font-size: 22px; font-weight: normal; margin: 0; text-transform: capitalize;">Verify your email address</h3>
                            <p style="text-align: left; color: #333; font-size: 16px; line-height: 28px;">Hello <?php echo $full_name ?>,</p>
                            <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">Thank you for registering an account with ConnektUs!<br>
                            To continue, you will need to verify your email address. Please click on the link below.</p>
                            <a href="<?php echo $link;?>" style=" background-color: #FFA500;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;margin: 4px 2px;">Verify your email</a>
                            <!-- <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">This email is generated because you are new registered user on ConnektUs, Please verify your email to get complete access on ConnektUs.</p> -->
                             <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">Can't verify your account? Please contact our support team at &nbsp;<a href="mailto:support@connektus.com.au?subject=Unable%to%verify%my%email%address&body=Hello,
Tried%multiple%times%to%verify%email%address.%Nothing%comes%in.%Please fix Asap!" target="_top">support@connektus.com.au</a>.</p><!-- please dont move the position of this text this will impact on layout. -->  

                            <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">Thanks,<br><?php echo SITE_TITLE ?> team</p>  
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff">
                    <tr>
                        <td style="padding: 10px;background: #fcac35;color: #fff;"><?php echo COPYRIGHT; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>