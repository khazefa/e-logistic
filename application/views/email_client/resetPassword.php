<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $pageTitle; ?></title>
	<link rel="shortcut icon" sizes="196x196" href="<?php echo base_url(); ?>assets/public/images/logo.png">

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/public/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/public/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/public/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/public/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/public/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/public/css/colors.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/public/css/custom.css" rel="stylesheet" type="text/css">
        
</head>
<body>
    <!-- Mail container -->
    <div class="mail-container-read">

        <!-- Email sample (demo) -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td>
                    <!-- Hero -->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td align="center" bgcolor="#4f97e2" style="background-image: url('http://demo.interface.club/limitless/assets/images/bg.png'); background-repeat: repeat;">
                                <table width="640" border="0" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td width="100%" height="15"></td>
                                    </tr>
                                    <tr>
                                        <td align="center">

                                                <!-- Nav -->
                                                <table width="600" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                                <td width="100%" valign="middle">

                                                                        <!-- Logo -->
                                                                        <table width="280" border="0" cellpadding="0" cellspacing="0" align="left">
                                                                                <tr>
                                                                                        <td height="60" valign="middle" width="100%" align="left">
                                                                                                <a href="#"><img width="72" src="https://s-media-cache-ak0.pinimg.com/originals/a6/a7/23/a6a72338f5aedbfad9f9107475365eb8.png" alt="Diebold Nixdorf"></a>
                                                                                        </td>
                                                                                </tr>
                                                                        </table>
                                                                        <!-- /logo -->


                                                                        <!-- View Online --> 
                                                                        <table width="280" border="0" cellpadding="0" cellspacing="0" align="right">	
                                                                                <tr>
                                                                                        <td height="60" valign="middle" width="100%" align="right">
                                                                                            <span style="color: #ffffff;">Just one more step.</span>
                                                                                        </td>
                                                                                </tr>
                                                                        </table>
                                                                        <!-- /view Online --> 

                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td width="100%" height="30"></td>
                                                        </tr>
                                                </table>
                                                <!-- /nav -->


                                                <!-- Title -->
                                                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                                                        <tr>
                                                                <td valign="middle" align="center" style="font-size: 40px; color: #ffffff; line-height: 50px; font-weight: 300;">
                                                                    Hi, <span style="font-weight: 400;"><?php echo $data["name"]; ?>.</span>
                                                                </td>
                                                        </tr>
                                                </table>
                                                <!-- /title -->


                                                <!-- Subtitle -->
                                                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                                                        <tr>
                                                                <td width="100%" height="30"></td>
                                                        </tr>
                                                        <tr>
                                                                <td valign="middle" width="100%">
                                                                        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                                                                                <tr>
                                                                                        <td width="30"></td>
                                                                                        <td width="540" align="center" style="font-size: 14px; color: #ffffff; line-height: 24px;">
                                                                                            <?php echo $data["message"]; ?>
                                                                                        </td>
                                                                                        <td width="30"></td>
                                                                                </tr>
                                                                        </table>
                                                                </td>
                                                        </tr>
                                                </table>
                                                <!-- /subtitle -->


                                                <!-- Button -->
                                                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                                                        <tr>
                                                                <td height="40"></td>
                                                        </tr>
                                                        <tr>
                                                                <td width="auto" align="center">
                                                                    <table border="0" cellpadding="0" cellspacing="0" align="center">
                                                                        <tr>
                                                                            <td width="auto" align="center" height="40" bgcolor="#344b61" style="border-radius: 20px; padding-left: 40px; padding-right: 40px; font-weight: 500;">
                                                                                <a href="<?php echo $data["reset_link"]; ?>" target="_blank" style="color: #ffffff; font-size: 12px; text-decoration: none; text-transform: uppercase; line-height: 34px;">Reset Password Link</a>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                        </tr>
                                                </table>
                                                <!-- /button -->

                                        </td>
                                    </tr>
                                        <tr>
                                                <td width="100%" height="50"></td>
                                        </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <!-- /hero -->

                        <!-- Footer -->
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                        <td align="center" width="100%" valign="top" bgcolor="#344b61">	

                                                <!-- Wrapper -->
                                                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                                                        <tr>
                                                                <td width="100%" height="40" align="center" valign="middle" style="font-size: 12px; color: #aebecd;">
                                                                        <a href="<?php echo base_url(); ?>" style="color: #ffffff;">DN-UG Storage Management</a>

                                                                        <!--<span style="color: #ffffff;">&nbsp;/&nbsp;</span>-->

                                                                        <!--<a href="#" style="color: #ffffff;">Send to a friend</a>-->
                                                                </td>
                                                        </tr>
                                                </table>
                                                <!-- /wrapper -->

                                        </td>
                                </tr>
                        </table>
                        <!-- /footer -->

                </td>
            </tr>
        </table>
        <!-- /email sample (demo) -->

    </div>
    <!-- /mail container -->
</body>
</html>