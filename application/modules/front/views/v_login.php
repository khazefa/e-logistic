<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo APP_NAME;?> - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="<?php echo APP_NAME;?>" />
        <meta name="author" content="sigit prayitno, cybergitt@gmail.com"/>
        <meta name="designer" content="sigit prayitno, cybergitt@gmail.com">
        <meta name="copyright" content="Diebold Nixdorf Indonesia"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/public/images/favicon.png">

        <!-- App css -->
        <link href="<?php echo base_url();?>assets/public/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/public/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/public/css/style.css" rel="stylesheet" type="text/css" />

        <script src="<?php echo base_url();?>assets/public/js/modernizr.min.js"></script>

        <script type="text/javascript"> //<![CDATA[ 
        var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
        document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
        //]]>
        </script>
        
    </head>
    <!-- Begin page -->
    <div class="container">
    <br>
    <h3 class="text-center"><?php echo APP_NAME;?> Diebold Nixdorf Indonesia</h3>
    <hr>
        <div class="row m-b-30">
        <aside class="col-md-4 offset-md-4">
            <div class="card">
            <article class="card-body">
                <h4 class="card-title text-center mb-4 mt-1">Sign in</h4>
                <hr>
                <p class="text-success text-center">
                    <?php
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                    ?>
                    <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $error; ?>                    
                    </div>
                    <?php
                    }
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                    ?>
                    <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $success; ?>                    
                    </div>
                    <?php } ?>
                </p>
                <form id="sign_in" action="<?php echo base_url('login/proccess'); ?>" method="POST" class="form-validate" ecntype="application/x-www-form-urlencoded">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                             </div>
                            <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                        </div> <!-- input-group.// -->
                    </div> <!-- form-group// -->
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                             </div>
                            <input type="password" class="form-control" name="password" placeholder="******" required>
                        </div> <!-- input-group.// -->
                    </div> <!-- form-group// -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Login  </button>
                    </div> <!-- form-group// -->
                    <p class="text-center"><a href="#" class="btn" data-toggle="modal" data-target="#modal-forgot">Forgot password?</a></p>
                </form>
            </article>
            </div> <!-- card.// -->
        </aside>
        </div>
        <div class="row">
                <div class="col-3 mx-auto">
                        <div class="text-center">
                                <script language="JavaScript" type="text/javascript">
                                TrustLogo("https://elogistic.id/assets/public/images/comodo_secure_seal_76x26_transp.png", "CL1", "none");
                                </script>
                                <a class="text-center" href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
                        </div>
                </div>
        </div>
    </div>
    <!-- End page -->
    
    <!-- APP MODAL -->
    <!-- Forgot password form -->
    <div id="modal-forgot" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content login-form">
            <!-- Form -->
            <form class="modal-body" action="<?php echo base_url('login/reset_pass'); ?>" method="POST" class="form-validate" ecntype="application/x-www-form-urlencoded">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                <div class="text-center">
                        <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
                        <h5 class="content-group">Forgot Password </h5>
                        <small class="display-block">We'll send you instructions in email</small>
                </div>

                <div class="form-group has-feedback">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                         </div>
                        <input type="email" class="form-control" name="femail" placeholder="user@example.com" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancel</button>
            </form>
            <!-- /form -->
        </div>
    </div>
    </div>
    <!-- /Forgot password form -->
    <!-- END APP MODAL -->
    
    <!-- jQuery  -->
    <script src="<?php echo base_url();?>assets/public/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/jquery.slimscroll.js"></script>

    <!-- App js -->
    <script src="<?php echo base_url();?>assets/public/js/jquery.core.js"></script>
    <script src="<?php echo base_url();?>assets/public/js/jquery.app.js"></script>

    </body>
</html>