<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo APP_NAME; ?></title>
    <link rel="shortcut icon" sizes="196x196" href="<?php echo base_url(); ?>assets/public/images/logo.png">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/intro/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?php echo base_url(); ?>assets/intro/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/intro/css/creative.min.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <header class="masthead">
      <div class="header-content">
        <div class="header-content-inner">
          <h1 id="homeHeading"><?php echo $greetings;?> and have a Great Day!</h1>
          <hr>
          <a class="btn btn-primary btn-xl js-scroll-trigger" href="#" onclick="location.href='<?php echo base_url('login');?>'">Manage your app</a>
        </div>
      </div>
    </header>

    <section id="contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-heading">Let's Get In Touch!</h2>
            <hr class="primary">
            <p>Ready to start your next project with us? That's great! Give us a call or send us an email and we will get back to you as soon as possible!</p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 ml-auto text-center">
            <i class="fa fa-phone fa-3x sr-contact"></i>
            <p>+62-21-25527997</p>
          </div>
          <div class="col-lg-4 mr-auto text-center">
            <i class="fa fa-envelope-o fa-3x sr-contact"></i>
            <p>
              <a href="mailto:info@dieboldnixdorf.com">info@dieboldnixdorf.com</a>
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Bootstrap core JavaScript -->
    <script src="<?php echo base_url(); ?>assets/intro/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/intro/vendor/popper/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/intro/vendor/bootstrap/js/bootstrap.min.js"></script>

  </body>

</html>