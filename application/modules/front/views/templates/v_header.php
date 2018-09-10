<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $pageTitle;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="<?php echo APP_NAME;?>" />
        <meta name="author" content="sigit prayitno, cybergitt@gmail.com"/>
        <meta name="designer" content="sigit prayitno, cybergitt@gmail.com">
        <meta name="copyright" content="Diebold Nixdorf Indonesia"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/public/images/favicon.png">

        <!-- Plugins css-->
        <link href="<?php echo base_url();?>assets/public/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/public/plugins/switchery/switchery.min.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/public/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/public/plugins/typehead/css/typeahead.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/public/plugins/jquery-datetimepicker/css/jquery.datetimepicker.min.css" rel="stylesheet" />
        
        <!-- App css -->
        <link href="<?php echo base_url();?>assets/public/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/public/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/public/css/style.css" rel="stylesheet" type="text/css" />

        <script src="<?php echo base_url();?>assets/public/js/modernizr.min.js"></script>
        
        <!-- jQuery  -->
        <script src="<?php echo base_url();?>assets/public/js/jquery.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/js/popper.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>assets/public/js/waves.js"></script>
        <script src="<?php echo base_url();?>assets/public/js/jquery.slimscroll.js"></script>        

    </head>
    <body>

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid">

                    <!-- Logo container-->
                    <div class="logo">
                        <!-- Text Logo -->
                        <!-- <a href="index.html" class="logo">
                            <span class="logo-small"><i class="mdi mdi-radar"></i></span>
                            <span class="logo-large"><i class="mdi mdi-radar"></i> Highdmin</span>
                        </a> -->
                        <!-- Image Logo -->
                        <a href="<?php echo base_url();?>" class="logo">
                            <img src="<?php echo base_url();?>assets/public/images/logo_sm.png" alt="" height="48" class="logo-small">
                            <img src="<?php echo base_url();?>assets/public/images/logo.png" alt="" height="48" class="logo-large">
                        </a>

                    </div>
                    <!-- End Logo container-->


                    <div class="menu-extras topbar-custom">

                        <ul class="list-unstyled topbar-right-menu float-right mb-0">

                            <li class="menu-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle nav-link">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>

                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <img src="<?php echo base_url();?>assets/public/images/users/avatar-1.jpg" alt="user" class="rounded-circle"> <span class="ml-1 pro-user-name"><?php echo $name;?> <i class="mdi mdi-chevron-down"></i> </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h6 class="text-overflow m-0">Welcome !</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="<?php echo base_url('my-account');?>" class="dropdown-item notify-item">
                                        <i class="fi-head"></i> <span>My Account</span>
                                    </a>

                                    <!-- item-->
                                    <a href="<?php echo base_url('logout');?>" class="dropdown-item notify-item">
                                        <i class="fi-power"></i> <span>Logout</span>
                                    </a>

                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- end menu-extras -->

                    <div class="clearfix"></div>

                </div> <!-- end container -->
            </div>
            <!-- end topbar-main -->

            <div class="navbar-custom bg-primary">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">

                            <li class="has-submenu">
                                <a href="<?php echo base_url();?>"><i class="icon-speedometer"></i>Dashboard</a>
                            </li>
                            
                            <?php
                            if(($role == ROLE_SPV || $role == ROLE_AM)){
                            ?>
                            <li class="has-submenu">
                                <a href="#"><i class="icon-basket"></i>Transaction</a>
                                <ul class="submenu">
                                    <li>
                                        <a href="<?php echo base_url("incoming-trans");?>">Incoming Parts</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url("outgoing-trans");?>">Outgoing Parts</a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if(($role == ROLE_SPV)){
                            ?>
                            <li class="has-submenu">
                                <a href="#"><i class="fa fa-database"></i>Master</a>
                                <ul class="submenu">
                                    <li><a href="<?php echo base_url("data-spareparts");?>">Sparepart</a></li>
                                    <li><a href="<?php echo base_url("data-spareparts-sub");?>">Sparepart Subtitute</a></li>
                                    <li><a href="<?php echo base_url("data-spareparts-stock");?>">Stock</a></li>
                                    <li><a href="<?php echo base_url("data-warehouses");?>">Warehouse</a></li>
                                    <li><a href="<?php echo base_url("data-partners");?>">Service Partner</a></li>
                                    <li><a href="<?php echo base_url("data-engineers");?>">Engineers</a></li>
                                </ul>
                            </li>
                            <?php
                            }
                            ?>
                            <?php
                            if(($role == ROLE_AM)){
                            ?>
                            <li class="has-submenu">
                                <a href="#"><i class="fa fa-file-text"></i>Report</a>
                                <ul class="submenu">
                                    <li><a href="<?php echo base_url("report-consumed-parts");?>">Transacted Part</a></li>
                                    <li><a href="<?php echo base_url("report-used-parts");?>">Used Part</a></li>
                                    <li><a href="<?php echo base_url("report-replenish-plan");?>">Replenishment Plan</a></li>
                                    <li><a href="<?php echo base_url("data-spareparts-stock");?>">Stock</a></li>
                                </ul>
                            </li>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if(($role == ROLE_SU)){
                            ?>
                            <li class="has-submenu">
                                <a href="#"><i class="fa fa-gears"></i>Manage</a>
                                <ul class="submenu">
                                    <li><a href="<?php echo base_url("manage-users");?>">Users</a></li>
                                    <li><a href="<?php echo base_url("manage-groups");?>">Users Group</a></li>
                                    <li><a href="<?php echo base_url("manage-warehouses");?>">Warehouse</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#"><i class="fa fa-file-text"></i>History</a>
                                <ul class="submenu">
                                    <li>
                                        <a href="<?php echo base_url("history-outgoing");?>">Outgoing</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url("history-incoming");?>">Incoming</a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if(($role == ROLE_WA)){
                            ?>
                            <li class="has-submenu">
                                <a href="#"><i class="fa fa-gears"></i>Manage</a>
                                <ul class="submenu">
                                    <li><a href="<?php echo base_url("manage-spareparts");?>">Sparepart</a></li>
                                    <li><a href="<?php echo base_url("manage-spareparts-sub");?>">Sparepart Subtitute</a></li>
                                    <li><a href="<?php echo base_url("manage-spareparts-stock");?>">Stock</a></li>
                                    <li><a href="<?php echo base_url("manage-warehouses");?>">Warehouse</a></li>
                                    <li><a href="<?php echo base_url("manage-partners");?>">Service Partner</a></li>
                                    <li><a href="<?php echo base_url("manage-engineers");?>">Engineers</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#"><i class="icon-basket"></i>Transaction</a>
                                <ul class="submenu">
                                    <li>
                                        <a href="<?php echo base_url("delivery-note-trans");?>">Delivery Note</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#"><i class="fa fa-file-text"></i>Report</a>
                                <ul class="submenu">
                                    <li><a href="<?php echo base_url("report-consumed-parts");?>">Transacted Part</a></li>
                                    <li><a href="<?php echo base_url("report-used-parts");?>">Used Part</a></li>
                                    <li><a href="<?php echo base_url("report-replenish-plan");?>">Replenishment Plan</a></li>
                                </ul>
                            </li>
                            <?php
                            }
                            ?>

                        </ul>
                        <!-- End navigation menu -->
                    </div> <!-- end #navigation -->
                </div> <!-- end container -->
            </div> <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->

        <div class="wrapper">
            <div class="container-fluid">
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="btn-group pull-right">
                                <ol class="breadcrumb hide-phone p-0 m-0">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active"><?php echo $pageMenu;?></li>
                                </ol>
                            </div>
                            <h4 class="page-title">FSL - <span id="glob_fsl"><?php echo $repo." (".$repoName.")";?></span></h4>
                        </div>
                    </div>
                </div>
                <!-- end page title end breadcrumb -->