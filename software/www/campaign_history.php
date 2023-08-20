<?php
ob_start();
session_start();
include 'include/conn.php';
if (!isset($_SESSION['AdminId'])) {
	//User not logged in. Redirect them back to the login page.//
	header('Location: login.php');
	exit;
}
?>
<html>

<head>
	<?php include 'include/title.php'; ?>
	<meta charset="utf-8">
	<meta content="ie=edge" http-equiv="x-ua-compatible">
	<meta content="template language" name="keywords">
	<meta content="Tamerlan Soziev" name="author">
	<meta content="Admin dashboard html template" name="description">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="favicon.png" rel="shortcut icon">
	<link href="apple-touch-icon.png" rel="apple-touch-icon">
	<link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet" type="text/css">
	<link href="bower_components/select2/dist/css/select2.min.css" rel="stylesheet">
	<link href="bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<link href="bower_components/dropzone/dist/dropzone.css" rel="stylesheet">
	<link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
	<link href="bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" rel="stylesheet">
	<link href="bower_components/slick-carousel/slick/slick.css" rel="stylesheet">
	<link href="css/main.css?version=4.4.0" rel="stylesheet">
	<script>
		function activate() {
			return confirm("Do you want to Activate this Campaign!")
		}
	</script>
</head>

<body class="menu-position-side menu-side-left full-screen with-content-panel">
	<div class="all-wrapper with-side-panel solid-bg-all">
		<div class="layout-w">
			<!--------------------
        START - Mobile Menu
        -------------------->
			<?php include 'include/mobile-menu.php'; ?>
			<!--------------------
        END - Mobile Menu
        -------------------->
			<!--------------------
        START - Main Menu
        -------------------->
			<?php include 'include/main-menu.php'; ?>
			<!--------------------
        END - Main Menu
        -------------------->
			<div class="content-w">

				<!--------------------
		START - Top Bar
		--------------------->
				<?php include 'include/topbar.php'; ?>
				<!--------------------
		END - Top Bar
		--------------------->

				<!--------------------
		START - Breadcrumbs
		-------------------->

				<!--------------------
		END - Breadcrumbs
		-------------------->
				<div class="content-i">
					<div class="content-box">
						<div class="row">
							<div class="col-sm-12">
								<div class="element-wrapper">
									<h6 class="element-header">
										Campaign Reports
									</h6>
									<!--------------------
							START Multi-Books Campaign List
							-------------------->
									<div class="element-box" style="height:1000px;">
										<h5 class="form-header">
											Multi-Books Campaign List
										</h5>
										<!--------------------
								START - Table with actions
								-------------------->
										<div class="table-responsive">
											<table class="table table-bordered table-lg table-v2 table-striped">
												<thead class="text-center">
													<tr>
														<th>
															S.No.
														</th>
														<th>
															Campaign Name
														</th>
														<th>
															Campaign date
														</th>
														<th>
															Promo Code
														</th>
														<th>
															Created date
														</th>
														<th>
															Sent date
														</th>
														<!--<th>
												Total Emails
											  </th>
											  <th>
												Total Emails Sent
											  </th>-->
														<th>
															Report Details
														</th>
													</tr>
												</thead>
												<tbody class="text-center">
													<?php

													$sql = "SELECT `CampID`, `CampName`, `CampDate`, `Promo_Code`, `AdminID`, `Camp_Status`, `Camp_Remarks`, `Camp_Created_Date`, `Camp_Send_Date`  
													FROM
														`campaign`
													WHERE
														Camp_Status = 'Completed'
													AND book_status = 'MultiBook'    
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
															echo "<td class='text-center'>";
															echo "<button type='button' class='mr-2 mb-2 btn btn-outline-secondary'>" . $row['Promo_Code'] . "</button></a>";
															echo "</td>";
															echo "</td>";
															echo "<td class='text-center'>";
															echo date("j M Y", strtotime($row['Camp_Created_Date']));
															echo "</td>";
															echo "</td>";
															echo "<td class='text-center'>";
															echo date("j M Y", strtotime($row['Camp_Send_Date']));
															echo "</td>";
															/*  
														echo"<td class='text-center'>";
															$stmt= $conn->prepare('SELECT count(Email) as TotalEmail FROM campaingauthors WHERE `CampID` = :CampID');  
															$stmt->bindValue(':CampID', $row["CampID"]);
															$stmt->execute();
															$result = $stmt->fetch(PDO::FETCH_ASSOC);
															$TotalEmail = $result['TotalEmail']; 
															echo "".$TotalEmail;;
														echo"</td>";
														echo"<td class='text-center'>";
															$stmt= $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors WHERE `CampID` = :CampID AND Status = 'Sent'");  
															$stmt->bindValue(':CampID', $row["CampID"]);
															$stmt->execute();
															$result = $stmt->fetch(PDO::FETCH_ASSOC);
															$TotalEmail = $result['TotalEmail']; 
															echo "".$TotalEmail;;
														echo"</td>"; 
													*/
															echo "<td class='text-center'>
															<a class='badge badge-success-inverted' href='multi_books_report.php?CampID=" . $row["CampID"] . "' target='_blank'>View Report</a>
														</td>";
															/* 										
													echo"<td class='row-actions'>";
														echo"<a href='Edit_campaign.php?CampID=".$row["CampID"]."'><i class='os-icon os-icon-ui-49'></i></a>";
														
														echo"<a href='delete_campaign.php?CampID=".$row["CampID"]."' class='danger' onclick='return deleteclick();'><i class='os-icon os-icon-ui-15'></i></a>";
													echo"</td>"; */
															/* 										  
														echo"<td>";
															echo"<a class='btn btn-sm  btn-primary' href='Manage-campaign.php?CampID=".$row["CampID"]."'>UPDATE</a>"; 
														echo"</td>"; 
													*/
															/* echo"<td>";
													   echo"<a class='btn btn-success btn-sm' onclick='return activate();' href='Manage-campaign.php?CampID=".$row["CampID"]."'>Activate</a>";
													  echo"</td>";	 */
															echo "</tr>";

															//iteration
															$b++;
														}
													}

													?>
												</tbody>
											</table>
										</div>
										<!--------------------
								END Single Book Campaign List
								-------------------->
										<br />
										<br />
										<hr />
										<br />
										<br />

										<!--------------------
								START Single Book Campaign List
								-------------------->
										<h5 class="form-header">
											Single Book Campaign List
										</h5>
										<!--------------------
								START - Table with actions
								-------------------->
										<div class="table-responsive">
											<table class="table table-bordered table-lg table-v2 table-striped">
												<thead class="text-center">
													<tr>
														<th>
															S.No.
														</th>
														<th>
															Campaign Name
														</th>
														<th>
															Campaign date
														</th>
														<th>
															Promo Code
														</th>
														<th>
															Created date
														</th>
														<th>
															Sent date
														</th>
														<!--<th>
												Total Emails
											  </th>
											  <th>
												Total Emails Sent
											  </th>-->
														<th>
															Report Details
														</th>
													</tr>
												</thead>
												<tbody class="text-center">
													<?php

													$sql = "SELECT `CampID`, `CampName`, `CampDate`, `Promo_Code`, `AdminID`, `Camp_Status`, `Camp_Remarks`, `Camp_Created_Date`, `Camp_Send_Date`  
													FROM
														`campaign`
													WHERE
														Camp_Status = 'Completed'
													AND book_status = 'SingleBook'    
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
															echo "<td class='text-center'>";
															echo "<button type='button' class='mr-2 mb-2 btn btn-outline-secondary'>" . $row['Promo_Code'] . "</button></a>";
															echo "</td>";
															echo "</td>";
															echo "<td class='text-center'>";
															echo date("j M Y", strtotime($row['Camp_Created_Date']));
															echo "</td>";
															echo "</td>";
															echo "<td class='text-center'>";
															echo date("j M Y", strtotime($row['Camp_Send_Date']));
															echo "</td>";

															echo "<td class='text-center'>
															<a class='badge badge-success-inverted' href='single_books_report.php?CampID=" . $row["CampID"] . "' target='_blank'>View Report</a>
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
						<!--------------------
              START - Color Scheme Toggler
              -------------------->
						<!--------------------
              END - Color Scheme Toggler
              -------------------->
						<!--------------------
              START - Demo Customizer
              -------------------->
						<!--------------------
              END - Demo Customizer
              -------------------->
						<!--------------------
              START - Chat Popup Box
              -------------------->
						<!--------------------
              END - Chat Popup Box
              -------------------->
					</div>
				</div>
			</div>
		</div>
		<div class="display-type"></div>
	</div>
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="bower_components/popper.js/dist/umd/popper.min.js"></script>
	<script src="bower_components/moment/moment.js"></script>
	<script src="bower_components/chart.js/dist/Chart.min.js"></script>
	<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
	<script src="bower_components/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
	<script src="bower_components/ckeditor/ckeditor.js"></script>
	<script src="bower_components/bootstrap-validator/dist/validator.min.js"></script>
	<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script src="bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>
	<script src="bower_components/dropzone/dist/dropzone.js"></script>
	<script src="bower_components/editable-table/mindmup-editabletable.js"></script>
	<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script src="bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
	<script src="bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js"></script>
	<script src="bower_components/tether/dist/js/tether.min.js"></script>
	<script src="bower_components/slick-carousel/slick/slick.min.js"></script>
	<script src="bower_components/bootstrap/js/dist/util.js"></script>
	<script src="bower_components/bootstrap/js/dist/alert.js"></script>
	<script src="bower_components/bootstrap/js/dist/button.js"></script>
	<script src="bower_components/bootstrap/js/dist/carousel.js"></script>
	<script src="bower_components/bootstrap/js/dist/collapse.js"></script>
	<script src="bower_components/bootstrap/js/dist/dropdown.js"></script>
	<script src="bower_components/bootstrap/js/dist/modal.js"></script>
	<script src="bower_components/bootstrap/js/dist/tab.js"></script>
	<script src="bower_components/bootstrap/js/dist/tooltip.js"></script>
	<script src="bower_components/bootstrap/js/dist/popover.js"></script>
	<script src="js/demo_customizer.js?version=4.4.0"></script>
	<script src="js/main.js?version=4.4.0"></script>
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