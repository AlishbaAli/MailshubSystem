<?php
// session_start();
include './include/conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// if(!isset($_SESSION['AdminId']))
// {
// 	//User not logged in. Redirect them back to the login page.
// 	header('Location: login.php');
// 	exit;
// }
$dates = date('F j Y');
?>

<!-- <div class="content-i">
				<div class="content-box" style="height:1000px;">
					<div class="element-wrapper" >
						<!-- <h6 class="element-header">
							Check Alerts
						</h6> -->
<!-- <div class="element-box">
							<h5 class="form-header"> -->

<?php

if (isset($CampID) && $ctype_article_list == 'Yes') {

	//$CampID =  $_GET['CampID'];								

	$sql = "SELECT `CampID`, `CampFor`,
													
														`ctype_id` 
																FROM `campaign` 
																WHERE CampID = :CampID";
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(':CampID', $CampID);

	$result = $stmt->execute();

	if ($stmt->rowCount() > 0) {
		$result = $stmt->fetchAll();
		foreach ($result as $row3) {
			$CampID = $row3["CampID"];
			//$Camp_Remarks = html_entity_decode($row3["Camp_Remarks"]);
			// $add_banner = html_entity_decode($row3["add_banner"]);
			//$altype = html_entity_decode($row3["altype"]);

		}
	}

	//Email Content Header Start...
	$message = "<table width='799' height='430' border='0' align='center'>
								
				<tr>		
				  <td> $cmp_draft_new </td>
				</tr>
			 
				<tr>	
				  <td align='center'><span style='font-size:14px;text-align: left;'>	<!--<p>Dear Dr. Admin,</p></span>-->	</td>
                </tr>     ";
}
?>


<?php

if (isset($CampID) && $ctype_article_list == 'Yes') {


	$sql = "SELECT
					art.article_id AS ArticleID,
					-- art.Sno as Sno,
					pro.disp as discipline,
					pro.product_name as journalname,
					art.url as jurlname,
					art.volume as volume,
					art.issue as issue,
					art.year as year,
					art.title as title,
					art.authors as authors,
					LEFT(art.abstract, 130) as abstract,
					art.absurl as absurl,
				
					art.status as status,
					camp.CampID as camp_ID,
					camp.Camp_Status,
					camp.CampName AS CampName,
					pro.product_cover as product_cover,
					
					camp.CampFor AS Camp_Remarks,
					
					art.doi as doi
					
				FROM
					articles_scopus as art,
					products as pro,
					campaign as camp
				WHERE
					art.camp_id = camp.CampID 
				and art.product_id = pro.productid 
				AND art.status = 'Active'
				AND camp.CampID = :CampID";

	$stmt = $conn->prepare($sql);
	$stmt->bindValue(':CampID', $CampID);
	$result = $stmt->execute();
	if ($stmt->rowCount() > 0) {
		$result = $stmt->fetchAll();
		foreach ($result as $row3)
		//Journals Info Loop Start Here...	
		{
			$message = $message . "<tr>
										<td height='2'>";


			$message = $message . " 
									</td>
									</tr> 
									<tr valign='top'>
										<td height='145'>
											<table width='793' border='0' style='border: 2px solid #b4d1e3; padding-left: 6px;'>
												<tr>
													<td width='677'><span style='font-size:16px; font-family:Georgia, Times, serif; color:#000; '><strong>" . $row3['journalname'] . "</strong></span></td>
													<td width='108' rowspan='4' align='center' valign='middle'><img src='https://mailshub.net/product_cover/" . $row3['product_cover'] . "' style='width: 100px; padding: 11px 7px 0 10px;' /></td>
												</tr>
												<tr>
													<td><span style='font-family: Georgia,Times,serif';>Volume " . $row3['volume'] . ", ";

			if ($row3['issue'] == '') {

				$message = $message . "";
			} elseif ($row3['issue']) {

				$message = $message . "Issue " . $row3['issue'] . ", ";
			}


			$message = $message . " " . $row3['year'] . "</span></td>
												</tr>
												<tr>
													<td>
													<span><strong style='font-family: Georgia,Times,serif';>Title: </strong><br/>
													<span style='font-size:12px; font-family:Georgia; color:#1a1a1cc7;'><b style='font-family: Georgia,Times,serif';>" . $row3['title'] . "</b></span></td>
												</tr>
												<tr>
													<td> <span><strong style='font-family: Georgia,Times,serif';>Author: </strong><br/>
													<strong style='font-size:12px; color:#1a1a1cc7; font-family: Georgia,Times,serif';>" . $row3['authors'] . "</strong></span></td>
												</tr>
												<tr>
								<td><span><strong style='font-family: Georgia,Times,serif';>DOI:</strong>
														<br/> 
						<a style='font-size:12px;  font-family: Georgia,Times,serif'; href='http://dx.doi.org/" . $row3['doi'] . "' target='_blank' style='
    color: #15c;'> " . $row3['doi'] . "</a><br/>";



			$message = $message . "</td>
												</tr>
											</table>
										</td>
									</tr>";
		}


		//Journals Info Loop END Here//		
	}





	if (isset($CampID)) {

		// $CampID =  $_GET['CampID'];								

		//     $sql = "SELECT `CampID`,`CampName`, `Bottomtext` 
		// 			FROM `campaign` 
		// 			WHERE CampID = :CampID";
		// 			$stmt = $conn->prepare($sql);
		// 	$stmt->bindValue(':CampID', $CampID);

		// 	$result = $stmt->execute();

		// 	if ($stmt->row3Count() > 0) 
		// 	{ 
		// 		$result = $stmt->fetchAll();
		// 		foreach ($result as $row3) 
		// 		{	
		// 				$CampID = $row3["CampID"];
		// 				$Camp_Remarks = html_entity_decode($row3["Bottomtext"]);

		// 		}		
		// 	}




		//Email Content FOOTER Start Here...                           
		$message_oaarticles = $message . "
				                    
				                        <tr>
				                    
										<td>	
											<table width='801' height='10' border='0' valign='top'>
												<tr>
								        	<td>      
											
											
											<img src='http://mailshub.net/banners/GRP/10-20220228.jpg' />
											<!--<img src='https://benthamarticlealerts.com/img/footer.jpg' />-->
											</td>
								                
								                
								                </tr>
											</table>
										</td>	
									</tr>	
								  
									
							</table>";
		//Email Content FOOTER END Here//             				      
		// line 236---- <img src='https://benthamarticlealerts.com/img/footer.jpg' />    



	}
	// echo $message_oaarticles;     


	$headers = "";

	//  $to    = "urooj@benthamscience.net";
	//  $to    = "saeedayasmeen@benthamscience.net ";
	//  $subject = "Verify Alert - ".$row3['CampName']."";
	//$headers .= "MIME-Version: 1.0"."\r\n";
	//   $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
	//  $headers .= 'From: Open Access Articles Alert<alert@openaccessarticlesalert.com>'."\r\n";
	//  $headers .= 'Cc: saeedayasmeen@benthamscience.net '."\r\n";	









	//echo $message;
	//die();

	// mail($to, $subject, $message, $headers);


	///	$VerifyMsg = "<div class='alert alert-success' role='alert'><strong>Verify alert link has been sent. Kindly check your E-mail.</strong></div>";


	//Verify Alert Page code strat from here//
	// $CampID =  $_GET['CampID'];

	// $sql = "SELECT COUNT(CampName) AS num , CampName, CampID
	// 		FROM campaign 
	// 		WHERE CampID = :CampID";
	// $stmt = $conn->prepare($sql);
	// $stmt->bindValue(':CampID', $CampID);
	// $stmt->execute();
	// $row3 = $stmt->fetch(PDO::FETCH_ASSOC);
	// if($row3['num'] > 0)
	// {						
	// 	echo "Verify Alert For ".$row3["CampName"]." Campaign";

	// 	echo"<a style='float:right' class='mr-2 mb-2 btn btn-danger' onclick='return deleteall();'  href='delete_verify_alert.php?CampID=".$row3["CampID"]."'>Discard</a>";

	// 		if(empty($VerifyMsg))
	// 		{	
	// 			echo "<button type='button' style='float:right' class='mr-2 mb-2 btn btn-success' disabled href='Manage-campaign.php?CampID=".$row3["CampID"]."' class='btn btn-info' disabled >Next Activity</button>";
	// 		}
	// 		else
	// 			echo"<a  style='float:right' class='mr-2 mb-2 btn btn-success' href='next_verify_alert.php?CampID=".$row3["CampID"]."'>Next Activity</a>";		
	// 	echo "<br/>";
	// 	echo "<br/>";
	// }
	// else 
	// 	echo "Invalid Selection";

}
?>
<!-- </h5>
					
						</div>
					</div>
				</div>	  -->
<!-- <div class="display-type"></div> -->
<!-- </div> -->