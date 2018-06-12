<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>404 Page Not Found</title>
	<!-- Bootstrap core CSS -->
        <link href="assets/intro/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="assets/intro/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

        <!-- Custom styles for this template -->
        <link href="assets/intro/css/creative.min.css" rel="stylesheet">
</head>
    <body id="page-top">

    <header class="masthead">
      <div class="header-content">
        <div class="header-content-inner">
          <h1 id="homeHeading"><?php echo $heading;?></h1>
          <hr>
          <p>
              <?php echo $message; ?><br>
            <a class="btn btn-primary btn-xl js-scroll-trigger" href="#" onclick="window.history.back();">BACK</a>
          </p>
        </div>
      </div>
    </header>

    <!-- Bootstrap core JavaScript -->
    <script src="assets/intro/vendor/jquery/jquery.min.js"></script>
    <script src="assets/intro/vendor/popper/popper.min.js"></script>
    <script src="assets/intro/vendor/bootstrap/js/bootstrap.min.js"></script>

  </body>

</html>
