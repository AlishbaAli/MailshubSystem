<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
	//User not logged in. Redirect them back to the login page.
	header('Location: login.php');
	exit;
}
if (isset($_SESSION['AC'])) {
	if ($_SESSION['AC'] == "NO") {

		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
}
?>
<html lang="en">

<!--head-->

<?php include 'include/head.php'; ?>
<!--head-->
 
<body class="theme-blue">

	<!-- Page Loader -->
	<div class="page-loader-wrapper">
		<div class="loader">
			<div class="m-t-30"><img src="assets/images/thumbnail.png" width="48" height="48" alt="Mplify"></div>
			<p>Please wait...</p>
		</div>
	</div>
	<!-- Overlay For Sidebars -->
	<div class="overlay" style="display: none;"></div>

	<div id="wrapper">

		<!--nav bar-->
		<?php include 'include/nav_bar.php'; ?>

		<!--nav bar-->

		<!-- left side bar-->
		<?php include 'include/left_side_bar.php'; ?>


		<!-- left side bar-->


		<div id="main-content">
			<div class="container-fluid">
				<div class="block-header">
					<div class="row">
						<div class="col-lg-5 col-md-8 col-sm-12">
							<h2>Dashboard</h2>
						</div>
						<div class="col-lg-7 col-md-4 col-sm-12 text-right">
							<ul class="breadcrumb justify-content-end">
								<li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
								<li class="breadcrumb-item active">Dashboard</li>
							</ul>
						</div>
					</div>
				</div>

				<!---Add code here-->
				<div class="col-lg-12">
					<div class="card">
						<div class="header">
							<h3>SEND ALERT</h3>
						</div>
						<div class="element-box">
							<h5 class="form-header">
								<?php
								try {
									if (isset($_GET['CampID'])) {
										$CampID =  $_GET['CampID'];

										$sql = "UPDATE `campaign` 
						SET `Camp_Status` = 'Active', Camp_Send_Date = Now()
						where CampID = :CampID";

										$stmt = $conn->prepare($sql);
										$stmt->bindValue(':CampID', $CampID);
										$result = $stmt->execute();
										if ($result > 0) {


										$sql2 = "UPDATE `Campaign_flow` 
										SET `Camp_Status` = 'Active', Flow_activity_date = Now(),
										 CampID = :CampID";
				
														$stmt2 = $conn->prepare($sql);
														$stmt2->bindValue(':CampID', $CampID);
														$result2 = $stmt2->execute();
											
											$VerifyMsg = "<div class='alert alert-success' role='alert'>
										<strong>Campaign Alert Email's have been sent to all Authors.</strong>
									</div>";
										}


										/////////////////////
										//For Page Buttons;//
										$CampID =  $_GET['CampID'];

										$sql = "SELECT COUNT(CampName) AS num , CampName, CampID
						FROM campaign 
						WHERE CampID = :CampID";
										$stmt = $conn->prepare($sql);
										$stmt->bindValue(':CampID', $CampID);
										$stmt->execute();
										$row = $stmt->fetch(PDO::FETCH_ASSOC);
										if ($row['num'] > 0) {
											echo "Alert sent to Authors for <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign<br/>";
											echo "<br/>";
											if (empty($VerifyMsg)) {
												echo "<a href='index.php'><button type='button' style='float:right' class='mr-2 mb-2 btn btn-success' href='index.php?CampID=" . $row["CampID"] . "' class='btn btn-info' disabled >Finished</button></a>";
											} else {
												echo "<a href='index.php'><button type='button' style='float:right' class='mr-2 mb-2 btn btn-success'>Finished</button></a>";
											}

											//					echo "<br/>";
											//					echo "<br/>";
										} else
											echo "Invalid Selection";
									}
								}

								//catch exception
								catch (Exception $e) {
									echo 'Message: ' . $e->getMessage();
								}
								?>
							</h5>

							<label>
								<?php
								if (empty($VerifyMsg)) {
									echo $VerifyMsg = "<div class='alert alert-danger' role='alert'><strong>There is some error in sending E-mail.</strong></div>";
								} else
									echo $VerifyMsg;
								?>
							</label>
						</div>
					</div>


					<!---Add code here-->




				</div>
			</div>

		</div>

		<!-- Javascript -->

		<script src="assets/bundles/libscripts.bundle.js"></script>
		<script src="assets/bundles/vendorscripts.bundle.js"></script>

		<script src="assets/bundles/chartist.bundle.js"></script>
		<script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
		<script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
		<script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

		<script src="assets/bundles/mainscripts.bundle.js"></script>
		<script src="assets/js/index.js"></script>



		<script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
		<script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
		<script src="assets/js/pages/forms/form-wizard.js"></script>

</body>

</html>