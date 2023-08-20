<?php
	//ob_start();
//	session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
	include 'include/conn.php';

	// if (!isset($_SESSION['AdminId'])) {
	// 	//User not logged in. Redirect them back to the login page.
	// 	header('Location: login.php');
	// 	exit;
	// }
	if (isset($_SESSION['UPA'])) {
		if ($_SESSION['UPA'] == "NO") {

			//User not logged in. Redirect them back to the login page.
			header('Location: page-403.html');
			exit;
		}
	}
	?>
 <html lang="en">


 <body>





 				<!---Add code here-->

 				<div class="row">
 					<div class="col-lg-5 col-md-12 col-sm-12">
 					</div>
 					<div class="col-lg-12">
 						<div class="card">
 							<div class="header">
 								<h3>Article List</h3>
 							</div>
 							<div class="element-box">
 								<h5 class="form-header">
 									<?php // $CampID = "20";

										if (isset($CampID )) {
										//	$CampID =  $_GET['CampID'];

											$sql22 = "SELECT *, COUNT(CampName) AS num 
											FROM campaign 
											WHERE CampID = :CampID";

											$stmt22 = $conn->prepare($sql22);
											$stmt22->bindValue(':CampID', $CampID);
											$stmt22->execute();
											$row = $stmt22->fetch(PDO::FETCH_ASSOC);
$CampStatus=$row['Camp_Status'];
											if ($row['num'] > 0 ) {
												echo "All Articles For <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign";

								
} else {
												echo "<div class='alert alert-danger' role='alert'>";
										echo "<strong> invalid Selection! </strong>";
									  echo "</div>"; 
											//	echo "invalid Selection";
											//die();

										} }
										?>

<a class="align-right float float-right" style="padding-right: 20px;" target="_blank" href="add_more_Articles.php?CampID=<?php echo $row["CampID"]; ?>">
                                                                     <button type="button" class="btn btn-primary" title="Article">Add More Articles</button>
                                                                    </a> 

<a class="align-right float float-right" onclick='return deleteall();' style="padding-right: 20px;" target="_blank" href="deleteArticle.php?CampID=<?php echo $row["CampID"]; ?>">
                                                                     <button type="button" class="btn btn-danger" title="Article">Delete All</button>
                                                                    </a> 

<a class="align-right float float-right" style="padding-right: 20px;" target="_blank" href="add_article.php?CampID=<?php echo $row["CampID"]; ?>">
                                                                     <button type="button" class="btn btn-primary" title="Article">Add Article </button>
                                                                    </a> 

 								</h5><br>

                                
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
                                                   Discipline
 												</th>
                                                 <th>
 													Product
 												</th>
                                                 <th>
 													Volume / Issue / Year
 												</th>
 												<th>
 													Article Title
 												</th>
 												<th>
                                                 Authors
 												</th>
                                                 <th>
 													DOI
 												</th>
 												<th>
                                                   Abstract
 												</th>
 												<th>
 													Abstract URL
 												</th>
                                                 <!-- <th>
 													Product Cover
 												</th> -->
                                                 <th>
 													Status
 												</th>
 												
         <!-- <?php if($CampStatus == 'Verified') { ?>
 												<th>
 													Status
 												</th>
	<?php	} ?> -->
 											</tr>
 										</thead>

 										<tbody>
 											<?php

												//$CampID =  $_GET['CampID'];

			$sql = "SELECT art_scopus_id, `title`, `absurl`, `abstract`, `disp`, `product_name`, `product_cover`, `doi`, `authors`, `abstract`, `volume`, 
            `issue`, `year`, articles_scopus.status 
            FROM `articles_scopus` left join products on articles_scopus.product_id=products.productid 
            where camp_id = '$CampID' LIMIT 50
			";

												$stmt = $conn->prepare($sql);
												$stmt->bindValue(':CampID', $CampID);
												$result = $stmt->execute();

												if ($stmt->rowCount() > 0) {
													$result = $stmt->fetchAll();
													$a = 0;
													$b = 1;
													foreach ($result as $roww) {

														echo "<tr>";
														echo "<td class='text-center'>";
														echo $c = $b + $a;
														echo "</td>";
														echo "<td>";
														echo html_entity_decode($roww["disp"]);
														echo "</td>";
														echo "<td  class='text-center' >";
														echo '<img src="product_cover/'.$roww["product_cover"].'" > <br> <br>';
														echo html_entity_decode($roww["product_name"]);
														echo "</td>";
														echo "<td>";
														echo html_entity_decode($roww["volume"]."/".$roww["issue"]."/".$roww["year"]);
														echo "</td>";

														echo "<td class='text-center'>";
							 							echo wordwrap(html_entity_decode($roww["title"]),40,"\n <br>");
														echo "</td>";
														echo "<td class='text-center'>";
														echo html_entity_decode($roww["authors"]);
														echo "</td>";
														echo "<td class='text-center'>";
														echo html_entity_decode($roww["doi"]);
														echo "</td>";

                                                        echo "<td class='text-center'>";
														echo wordwrap(html_entity_decode($roww["abstract"]),40,"<br> \n");
														echo "</td>";

                                                        echo "<td class='text-center'>";
														echo html_entity_decode($roww["absurl"]);
														echo "</td>";

                                                        // echo "<td class='text-center'>";
														
														// echo "</td>";

                                                       

													//	if($CampStatus == 'Verified') {
echo "<td class='text-center row-actions'>";
														
						 								echo "<a href='deleteArticle.php?art_scopus_id=" . $roww["art_scopus_id"] . "' class='danger' onclick='return deleteclick();'><button class='btn btn-sm btn-icon btn-pure btn-default on-default button-remove'
                     data-toggle='tooltip' data-original-title='Remove'><i class='icon-trash' aria-hidden='true'></i></button></a>"; 
													echo "</td>"; 
                                                //}
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






 	<!-- Javascript -->
 	<script>
 		function deleteclick() {
 			return confirm("Do you want to remove this article?")
 		}

 		function deleteall() {
 			return confirm("Do you want to Delete All Records?")
 		}
 	</script> 
 	<!-- <script src="assets/bundles/libscripts.bundle.js"></script>
 	<script src="assets/bundles/vendorscripts.bundle.js"></script>

 	<script src="assets/bundles/chartist.bundle.js"></script> -->
 	<!-- <script src="assets/bundles/knob.bundle.js"></script>  -->
      <!-- Jquery Knob-->
 	<!-- <script src="assets/bundles/flotscripts.bundle.js"></script>  -->
     <!-- flot charts Plugin Js -->
 	<!-- <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

 	<script src="assets/bundles/mainscripts.bundle.js"></script>
 	<script src="assets/js/index.js"></script>



 	<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>  Jquery Validation Plugin Css -->
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
 </body>

 </html>