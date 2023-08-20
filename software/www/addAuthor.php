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
							<h3>Add Author</h3>
						</div>

						<div class="element-box">
							<h5 class="form-header">
								<?php

								if (isset($_GET['CampID'])) {
									//echo $_GET['CampID'];
									$CampID =  $_GET['CampID'];
									$orgunit_id =  $_GET['orgunit_id'];


									$sql = "SELECT COUNT(CampName) AS num , CampName, CampID, format_type, rtemid
											FROM campaign 
											WHERE CampID = :CampID";

									$stmt = $conn->prepare($sql);

									//Bind the provided username to our prepared statement.
									$stmt->bindValue(':CampID', $CampID);

									//Execute.
									$stmt->execute();

									//Fetch the row.
									$row = $stmt->fetch(PDO::FETCH_ASSOC);

									//If the provided username already exists - display error.

									if ($row["format_type"] == "format1") {
										$format = "(Journal_title,Role,Fname,Lastname,affiliation,Country,email,article_title,eurekaselect_url)";
									} else {
										$format = "(INI,FNAME,LNAME,Add1,Add2,Add3,Add4,Country,email)";
									}

									if ($row['num'] > 0) {
										echo "Add Author List for <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign";
										echo "<br>";
										// echo "You have selected " . $row["format_type"] . " " . $format;
										// echo "<br/>";
										//echo "Please Select Data Format And Upload File Accordingly";
										echo "<br/>";

										//die();
									} else
										/* echo "<div class='alert alert-danger' role='alert'>";
										echo "<strong>invalid Selection! </strong>";
									echo "</div>"; */
										echo "invalid Selection";
									//die();

								}
								?>
							</h5>
							<form id="my-awesome-dropzone" class="dz-clickable" action="import_file.php" method="post" name="upload_excel" enctype="multipart/form-data">
								<fieldset>
<label style='color:#1740A5;'>Please Select Data Format And Upload File Accordingly</label><br>
 <div class="input-group">

 <label class="fancy-radio" style="font-weight:700"><input name="format_type" value="format1" type="radio" checked><span><i></i>Format 1 (Journal_title, Role, Fname, Lastname, affiliation, Country, email, article_title, eurekaselect_url)</span></label>

 </div> <div class="input-group mb-3">
                                        
<label class="fancy-radio" style="font-weight:700"><input name="format_type" value="format2" type="radio" ><span><i></i>Format 2 (INI, FNAME, LNAME, Add1, Add2, Add3, Add4, Country, email)</span></label>
 </div>
									<div class="control-group">
										<div class="control-label">
											<label style='color:#1740A5;'>CSV/Excel File:</label>
										</div>
									
										<div class="controls">
											<!-- <input type="file" name="file" id="file" accept=".csv" class="input-large" required> -->
											<input type="hidden" name="CampID" value="<?php echo $_GET['CampID']; ?>">
											<input type="hidden" name="orgunit_id" value="<?php echo $_GET['orgunit_id']; ?>">
											<input type="hidden" name="rtemid" value="<?php echo $row["rtemid"]; ?>">


											<!-- <input type="hidden" name="format_type" value="<?php echo $row["format_type"]; ?>"> -->


											<input type="file" name="file" id="file" class="dropify" data-allowed-file-extensions="csv" required>


										</div>
									</div>
									<br />
									<div class="control-group">
										<?php if (!empty($orgunit_id)) { // isset($_SESSION['orgunit_id']) ?>
										<div class="controls">
											<button type="submit" id="submit" name="UploadAuthor" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload File</button>
										</div>
										<?php } else {?>
											<div class="controls">
											<button type="submit" id="submit" disabled name="UploadAuthor" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload File</button>
										</div>
										<?php } ?>



								</fieldset>
							</form>
						</div>
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

	<script src="index.js"></script>

<!-- Session timeout js -->
<script>
                $(document).ready(function() {
            $.sessionTimeout({keepAliveUrl:"pages-starter.html",logoutUrl:"logout.php",redirUrl:"logout.php",
warnAfter:<?php echo $_SESSION['timeout']; ?>,redirAfter:<?php echo $_SESSION['timeout']+15000; ?>,countdownMessage:"Redirecting in {timer} seconds."});
});
            </script>
</body>

</html>