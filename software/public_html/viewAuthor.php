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

 				<div class="row">
 					<div class="col-lg-5 col-md-12 col-sm-12">
 					</div>
 					<div class="col-lg-12">
 						<div class="card">
 							<div class="header">
 								<h3>Author List</h3>
 							</div>
 							<div class="element-box">
 								<h5 class="form-header">
 									<?php $CampID = "";

										if (isset($_GET['CampID'])) {
											$CampID =  $_GET['CampID'];

											$sql = "SELECT COUNT(CampName) AS num , CampName, CampID, Camp_Status
											FROM campaign 
											WHERE CampID = :CampID";

											$stmt = $conn->prepare($sql);
											$stmt->bindValue(':CampID', $CampID);
											$stmt->execute();
											$row = $stmt->fetch(PDO::FETCH_ASSOC);
$CampStatus=$row['Camp_Status'];
											if ($row['num'] > 0 ) {
												echo "All Authors For <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign<br/><br/>";

											if ($row['Camp_Status']=='Verified') {

												echo "<a style='float:right' class='mr-2 mb-2 btn btn-danger' onclick='return deleteall();' href='deleteAuthor.php?CampID=" . $row["CampID"] . "'>Delete All</a>";
												echo "<a style='float:right' class='mr-2 mb-2 btn btn-primary' href='addAuthor.php?CampID=" . $row["CampID"] . "'>Add More</a>";





												$CampID =  $_GET['CampID'];

												$sql = "SELECT COUNT(CampaingAuthorsID) AS num, CampID
													FROM campaingauthors 
													WHERE CampID = :CampID";

												$stmt = $conn->prepare($sql);
												$stmt->bindValue(':CampID', $CampID);
												$stmt->execute();
												$row = $stmt->fetch(PDO::FETCH_ASSOC);

												if ($row['num'] > 0) {
													echo "<a style='float:right' class='mr-2 mb-2 btn btn-success' href='viewSendAlert.php?CampID= ".$CampID. "'>Next Activity</a>";

													echo "<br/>";
													echo "<br/>";
												} else
													echo "<button type='button' style='float:right' class='mr-2 mb-2 btn btn-success' disabled href='index.php' class='btn btn-info' disabled >Next Activity</button>";
											} } else
												/* 										echo "<div class='alert alert-danger' role='alert'>";
										echo "<strong>invalid Selection! </strong>";
									  echo "</div>"; */
												echo "invalid Selection";
											//die();

										}
										?>
 								</h5>



 								<div class="table-responsive">
 									<!--------------------
      START - Basic Table
      -------------------->
 									<table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												<th align="center">
 													Sno.
 												</th>
 												<th>
 													Journal Title
 												</th>
 												<th>
 													Role
 												</th>
 												<th>
 													First Name
 												</th>

 												<th>
 													Last Name
 												</th>
 												<th>
 													Country
 												</th>
 												<th>
 													Email
 												</th>
<?php if($CampStatus == 'Verified') { ?>
 												<th>
 													Status
 												</th>
	<?php	} ?>
 											</tr>
 										</thead>

 										<tbody>
 											<?php

												$CampID =  $_GET['CampID'];

												$sql = "SELECT `CampaingAuthorsID`, `CampID`, `Journal_title`, `Role`, `Fname`, `Lastname`, `affiliation`, `Country`, `email`, `article_title`, `eurekaselect_url`, `Status`, `Sent_email_datetime` 
			FROM `campaingauthors`
			where CampID = :CampID 
			LIMIT 50
			";

												$stmt = $conn->prepare($sql);
												$stmt->bindValue(':CampID', $CampID);
												$result = $stmt->execute();

												if ($stmt->rowCount() > 0) {
													$result = $stmt->fetchAll();
													$a = 0;
													$b = 1;
													foreach ($result as $row) {

														echo "<tr>";
														echo "<td class='text-center'>";
														echo $c = $b + $a;
														echo "</td>";
														echo "<td>";
														echo html_entity_decode($row["Journal_title"]);
														echo "</td>";
														echo "<td>";
														echo html_entity_decode($row["Role"]);
														echo "</td>";
														echo "<td>";
														echo html_entity_decode($row["Fname"]);
														echo "</td>";

														echo "<td class='text-center'>";
														echo html_entity_decode($row["Lastname"]);
														echo "</td>";
														echo "<td class='text-center'>";
														echo html_entity_decode($row["Country"]);
														echo "</td>";
														echo "<td class='text-center'>";
														echo html_entity_decode($row["email"]);
														echo "</td>";
														if($CampStatus == 'Verified') {
														echo "<td class='text-center row-actions'>";
														
														echo "<a href='deleteAuthor.php?CampaingAuthorsID=" . $row["CampaingAuthorsID"] . "' class='danger' onclick='return deleteclick();'><button class='btn btn-sm btn-icon btn-pure btn-default on-default button-remove'
                        data-toggle='tooltip' data-original-title='Remove'><i class='icon-trash' aria-hidden='true'></i></button></a>"; 
														echo "</td>"; }
														/*   echo"<td>";
					   echo"<a class='btn btn-sm  btn-primary' href='Manage-Campaign.php?CampID=".$row["ebookID"]."'>UPDATE</a>"; 
					  echo"</td>";
					  echo"<td>";
					   echo"<a class='btn btn-success btn-sm' href='Manage-Campaign.php?CampID=".$row["ebookID"]."'>Actions</a>";
					  echo"</td>"; */
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

 				<!---Add code here-->




 			</div>
 		</div>

 	</div>

 	<!-- Javascript -->
 	<script>
 		function deleteclick() {
 			return confirm("Do you want to remove this author?")
 		}

 		function deleteall() {
 			return confirm("Do you want to Delete All Records?")
 		}
 	</script>
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
 	<script src="assets/js/pages/tables/jquery-datatable.js"></script>
 	<script src="assets/bundles/datatablescripts.bundle.js"></script>

 	<script>
 		(function(i, s, o, g, r, a, m) {
 			i['GoogleAnalyticsObject'] = r;
 			i[r] = i[r] || function() {
 				(i[r].q = i[r].q || []).push(arguments)
 			}, i[r].l = 1 * new Date();
 			a = s.createElement(o),
 				m = s.getElementsByTagName(o)[0];
 			a.async = 1;
 			a.src = g;
 			m.parentNode.insertBefore(a, m)
 		})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

 		ga('create', 'UA-XXXXXXX-9', 'auto');
 		ga('send', 'pageview');
 	</script>

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