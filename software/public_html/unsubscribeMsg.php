<?php
session_start();
include 'include/conn.php';

/* 	
	if(!isset($_SESSION['AdminId']))
	{
		//User not logged in. Redirect them back to the login page.
		header('Location: login.php');
		exit;
	} 
*/
?>
<html lang="en">

<head>
	<title>::<?php include 'include/title.php'; ?> :: Home</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="description" content="Mplify Bootstrap 4.1.1 Admin Template">
	<meta name="author" content="ThemeMakker, design by: ThemeMakker.com">

	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/vendor/animate-css/animate.min.css">
	<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css">
	<link rel="stylesheet" href="assets/vendor/chartist/css/chartist.min.css">
	<link rel="stylesheet" href="assets/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

	<!-- MAIN CSS -->
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/color_skins.css">
</head>

<body class="theme-blue">

	<div class="overlay" style="display: none;"></div>

	<div id="wrapper">

		<nav class="navbar navbar-fixed-top">
			<div class="container-fluid" style="padding:10px;">


				<h3 style="color:#007bfff0; font-family: Helvetica, Arial, sans-serif; padding-top:12px;"> <strong> MAILSHUB <?php echo $dates = date('Y'); ?></strong></h3>

			</div>
		</nav>


		<div id="content">
			<div class="container">



				<div style="text-align:center; width:60%; padding-top:30px;">
					<div class="well">

						<?php
						if (isset($_GET['CampID']) && (!empty($_GET['CampID']))) {
							echo "<br/><br/><h1>Dear Admin! <br/>You Cannot Unsubscribe By Your Self.</h1>";
						} else {
							echo "<br/><br/><h1>You have successfully unsubscribed.</h1>";
						}
						?>
					</div>
				</div>


			</div>
		</div>
	</div> <!-- Javascript -->
	<script src="assets/bundles/libscripts.bundle.js"></script>
	<script src="assets/bundles/vendorscripts.bundle.js"></script>
	<script src="assets/bundles/chartist.bundle.js"></script>
	<script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
	<script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
	<script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>
	<script src="assets/bundles/mainscripts.bundle.js"></script>
	<script src="assets/js/index.js"></script>
	<script src="assets/vendor/ckeditor/ckeditor.js"></script> <!-- Ckeditor -->
	<script src="assets/js/pages/forms/editors.js"></script>
	<script src="index.js"></script>

<!-- Session timeout js -->
<script>
    $(document).ready(function() {
        $.sessionTimeout({
            keepAliveUrl: "pages-starter.html",
            logoutUrl: "logout.php",
            redirUrl: "logout.php",
            warnAfter: <?php echo $_SESSION['timeout']; ?>,
            redirAfter: <?php echo $_SESSION['timeout'] + 15000; ?>,
            countdownMessage: "Redirecting in {timer} seconds."
        });
    });
</script>
</body>

</html>