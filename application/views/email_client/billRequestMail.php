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
                                        <!-- The Best Prices for You -->
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
                                                <tr>
                                                        <td width="100%" height="5" bgcolor="#810000" style="font-size: 1px; line-height: 1px;">&nbsp;</td> 
                                                </tr>
                                                <tr>
                                                        <td align="center" width="100%" valign="top" bgcolor="#ffffff" style="background-color: #ffffff;">
                                                                <table width="640" border="0" cellpadding="0" cellspacing="0" align="center">
                                                                        <tr>
                                                                                <td width="100%" height="50"></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td align="center">

                                                                                        <!-- Nav -->
                                                                                        <table width="700" border="0" cellpadding="0" cellspacing="0">
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
                                                                                                                                    <span style="color: #333333;"><?php echo APP_NAME;?> System</span>
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

                                                                                        <!-- Header -->
                                                                                        <table width="700" border="0" cellpadding="0" cellspacing="0" align="center">
                                                                                                <tr>
                                                                                                        <td align="center">									
                                                                                                                <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" align="center" valign="middle" style="font-size: 24px; color: #444444; line-height: 32px; font-weight: 500;">
                                                                                                                                    <?php echo $contentHeader;?>
                                                                                                                                </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" width="100%" height="30"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" width="100%">
                                                                                                                                        <table width="100" border="0" cellpadding="0" cellspacing="0" align="center">
                                                                                                                                                <tr>
                                                                                                                                                        <td height="1" bgcolor="#f67b7c" style="font-size: 1px; line-height: 1px;">&nbsp;</td> 
                                                                                                                                                </tr>
                                                                                                                                        </table>
                                                                                                                                </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" width="100%" height="30"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" align="left" valign="middle" width="100%" style="font-size: 14px; color: #808080; line-height: 22px; font-weight: 400;">
                                                                                                                                    <table width="100%">
                                                                                                                                        <tr><td width="20%">Transaction</td><td>: #<?php echo $fappnum;?></td></tr>
                                                                                                                                        <tr><td>Service</td><td>: <?php echo $service;?></td></tr>
                                                                                                                                        <tr><td>Request By</td><td>: <?php echo $fullname;?></td></tr>
                                                                                                                                        <tr><td>Approval Date</td><td>: <?php echo $approvedate;?></td></tr>
                                                                                                                                        <tr><td>Due Date</td><td>: <?php echo $datedue;?></td></tr>
                                                                                                                                        <tr><td>Notes</td><td>: <?php echo $notes;?></td></tr>
                                                                                                                                    </table>
                                                                                                                                </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" width="100%" height="10"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2">
                                                                                                                                    <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                                                                                                                        <thead>
                                                                                                                                            <tr>
                                                                                                                                                <th>Service</th>
                                                                                                                                                <th>Volume</th>
                                                                                                                                            </tr>
                                                                                                                                        </thead>
                                                                                                                                        <tbody>
                                                                                                                                            <?php
                                                                                                                                            foreach($result as $r){
                                                                                                                                                $svc = strip_tags($r['req_service']);
                                                                                                                                                if($svc=="R"){
                                                                                                                                                    $service = "Regular";
                                                                                                                                                }elseif($svc=="U"){
                                                                                                                                                    $service = "Urgent";
                                                                                                                                                }elseif($svc=="VU"){
                                                                                                                                                    $service = "Very Urgent";
                                                                                                                                                }
                                                                                                                                                $volume = strip_tags($r['req_volume']);
                                                                                                                                                echo "<tr>";
                                                                                                                                                echo "<td>$service</td>";
                                                                                                                                                echo "<td>$volume</td>";
                                                                                                                                                echo "</tr>";
                                                                                                                                            }
                                                                                                                                            ?>
                                                                                                                                        </tbody>
                                                                                                                                    </table>
                                                                                                                                </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" width="100%" height="10"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td align="left" valign="middle" width="60%" style="font-size: 14px; color: #808080; line-height: 22px; font-weight: 400;">
                                                                                                                                    <table width="100%">
                                                                                                                                        <tr><td>Authorized Person</td></tr>
                                                                                                                                        <tr><td>&nbsp;</td></tr>
                                                                                                                                        <tr><td>&nbsp;</td></tr>
                                                                                                                                        <tr><td><?php echo $authorized;?></td></tr>
                                                                                                                                    </table>
                                                                                                                                </td>
                                                                                                                                <td align="right" valign="middle" width="100%" style="font-size: 14px; color: #808080; line-height: 22px; font-weight: 400;">
                                                                                                                                    <table width="100%">
                                                                                                                                        <?php
                                                                                                                                            $price = $result_sub['fprice'];
                                                                                                                                            $price_rp = format_rupiah($price);
                                                                                                                                            $subtotal = $result_sub['fsubtotal'];
                                                                                                                                            $subtotal_rp = format_rupiah($subtotal);
                                                                                                                                            $delivery = $result_sub['fdelivery'];
                                                                                                                                            $delivery_rp = format_rupiah($delivery);
                                                                                                                                            $min_price = $result_sub['fmin_price'];
                                                                                                                                            $min_price_rp = format_rupiah($min_price);
                                                                                                                                            $pridel = $result_sub['fpridel'];
                                                                                                                                            $pridel_rp = format_rupiah($pridel);
                                                                                                                                            $total = $result_sub['ftotal'];
                                                                                                                                            $total_rp = format_rupiah($total);
                                                                                                                                        ?>
                                                                                                                                        <tr>
                                                                                                                                            <td class="text-bold">Retrieve Price @doc:</td>
                                                                                                                                            <td class="text-right"><?php echo "Rp. ".$price_rp;?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td class="text-bold">Retrieve Cost:</td>
                                                                                                                                            <td class="text-right"><?php echo "Rp. ".$subtotal_rp;?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td class="text-bold">Minimum Payment:</td>
                                                                                                                                            <td class="text-right"><?php echo "Rp. ".$min_price_rp;?></td>
                                                                                                                                        </tr>
                                                                                                                                        <tr>
                                                                                                                                            <td class="text-bold">Estimation Bill:</td>
                                                                                                                                            <td class="text-right text-primary"><h5 class="text-semibold"><?php echo "Rp. ".$total_rp;?></h5></td>
                                                                                                                                        </tr>
                                                                                                                                    </table>
                                                                                                                                </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" width="100%" height="30"></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                                <td colspan="2" width="100%">
                                                                                                                                    <span style="font-size: 12px; font-style: italic;">Please keep this email as your request transaction backup. This page purpose is only for preview Estimation Bill.</span>
                                                                                                                                </td>
                                                                                                                        </tr>
                                                                                                                </table>
                                                                                                        </td>
                                                                                                </tr>
                                                                                        </table>
                                                                                        <!-- /header -->

                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td height="60"></td>
                                                                        </tr>
                                                                </table>
                                                        </td>
                                                </tr>
                                        </table>
                                        <!-- /the Best Prices for You -->

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