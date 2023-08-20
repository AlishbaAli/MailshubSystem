<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
	//User not logged in. Redirect them back to the login page.
	header('Location: login.php');
	exit;
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
						<div class="col-lg-5 col-md-12 col-sm-12">
						</div>
						<div class="col-lg-12">
							<div class="card">
								<div class="header">
									<h3>Campaign Histroy</h3>
								</div>
								<div class="element-box" style="height:1000px;">
									<h5 class="form-header">
										Subscription Campaign List
									</h5>
									<!--------------------
								START - Table with actions
								-------------------->
									<div class="table-responsive">
										<table class="table table-bordered table-lg table-v2 table-striped">
											<thead class="text-center">
												<tr>
													<th>
														Sno.
													</th>
													<th>
														Campaign Name
													</th>

													<th>
														Campaign Date
													</th>

													<th>
														Sent Date
													</th>
													<th>
														Report Details
													</th>
												</tr>
											</thead>
											<tbody class="text-center">
												<?php


												$sql = "SELECT `CampID`, `CampName`, `CampDate`, `AdminID`, `Camp_Status` `Camp_Created_Date`, `Camp_Send_Date`  
													FROM `campaign`
													Where 
													Camp_Status = 'Completed'
													AND 
													draft_status = 'subscriptionDraft'    
													ORDER BY
														CampID";

												$stmt = $conn->prepare($sql);
												$result = $stmt->execute();

												if ($stmt->rowCount() > 0) {
													$result = $stmt->fetchAll();
													//initialization for s.no.
													$a = 0;
													$b = 1;

													foreach ($result as $row) {
														echo "<tr>";
														/*echo"<td class='text-center'>";
														echo "CAMP#0".$row["CampID"];
													  echo"</td>";	 */
														echo "<td class='text-center'>";
														echo $c = $b + $a;
														echo "</td>";
														echo "<td class='text-center'>";
														echo $row["CampName"];
														echo "</td>";
														echo "<td class='text-center'>";
														echo date("j M Y", strtotime($row['CampDate']));
														echo "</td>";
														echo "</td>";

														echo "</td>";
														echo "<td class='text-center'>";
														echo date("j M Y", strtotime($row['Camp_Send_Date']));
														echo "</td>";

														echo "<td class='text-center'>
															<a class='badge badge-success-inverted' href='draftReport.php?CampID=" . $row["CampID"] . "' target='_blank'>View Report</a>
														</td>";
														echo "</tr>";
														$b++;
													}
												}

												?>
											</tbody>
										</table>
									</div>

								</div>

							</div>
						</div>

					</div>
				</div>
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