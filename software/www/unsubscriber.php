<?php
    ob_start();
	require 'include/conn.php';	
	error_reporting(E_ALL);
ini_set('display_errors', 1);
								 											
	
	if (isset($_GET['CAID']) && (!empty($_GET['CAID']))) 
	{		

		$CampaingAuthorsID = $_GET['CAID'];
		
		//Find author email address
		$stmt= $conn->prepare("SELECT Fname, Lastname, email, CampID
								FROM campaingauthors 
								WHERE CampaingAuthorsID =  :CampaingAuthorsID");  
		$stmt->bindValue(':CampaingAuthorsID', $CampaingAuthorsID);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$FirstName = $result['Fname'];
		$LastName = $result['Lastname'];
		$UnsubscriberEmail = $result['email'];
		$CampID = $result['CampID'];

		//Get AdminID
		$stmta= $conn->prepare("SELECT AdminId
		FROM campaign 
		WHERE CampID =  :CampID");  
        $stmta->bindValue(':CampID', $CampID);
        $stmta->execute();
        $resulta = $stmta->fetch(PDO::FETCH_ASSOC);
        $AdminId = $resulta['AdminId'];

		//Get Org_id and org_settings
		
		// $stmts1 = $conn->prepare("SELECT * FROM system_setting WHERE status='Active'");
		// 		$stmts1->execute();
		// 		$sys_settings= $stmts1->fetch();
		$stmts1 = $conn->prepare("SELECT * FROM tbl_orgunit_user WHERE user_id='$AdminId'");
				$stmts1->execute();
				$orgunit_id= $stmts1->fetch();



		if (!empty($orgunit_id['orgunit_id'])) {
			$org = $orgunit_id['orgunit_id'];
			$stmt = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id = $org");
			$stmt->execute();
			$orgname = $stmt->fetch();

			//get its settinggs
			
			$stmts = $conn->prepare("SELECT system_setting FROM tbl_organizational_unit WHERE orgunit_id=:orgunit_id");
			$stmts->bindValue(':orgunit_id', $org);
			$stmts->execute();
			$settings= $stmts->fetch();
			$set_type= $settings['system_setting'];

			if(trim($set_type)=="sys-defined"){
				//system settings the above query
				
				$unsubscriber_type="sys-defined";
				
			}
			else if(trim($set_type)=="ou-defined"){
				$stmts2 = $conn->prepare("SELECT * FROM `orgunit-systemsetting` WHERE status='Active' AND
				 orgunit_id=:orgunit_id");
				$stmts2->bindValue(':orgunit_id', $org);
				$stmts2->execute();
				$org_settings= $stmts2->fetch();
			  
				$unsubscriber_type=$org_settings['unsubscription_type'];
				

			} else { $unsubscriber_type="sys-defined"; }

			//settings stored in session variables
		}


		
		
				// $org_id_stmt = $conn->prepare("SELECT orgunit_id FROM tbl_orgunit_user WHERE user_id=:user_id");
				// $org_id_stmt->bindValue(':user_id', $AdminId);
				// $org_id_stmt->execute();
				// $orgunit = $org_id_stmt->fetch();
				// $orgunit_id = $orgunit["orgunit_id"];
		
		
			    // //unsubscriber type from org_settings
				// $org_stmt= $conn->prepare("SELECT * FROM `orgunit-systemsetting`
				// WHERE status ='Active' and orgunit_id=:orgunit_id");
				// $org_stmt->bindValue(':orgunit_id', $orgunit_id);
				// $org_stmt->execute();
				// $org_settings = $org_stmt->fetch();
				// $unsubscription_type = $org_settings["unsubscription_type"];



		
		//Now, we need to check if the provided CampName already exists.
		$stmt1= $conn->prepare("SELECT * FROM  unsubscriber 
		WHERE UnsubscriberEmail = :UnsubscriberEmail");
	    $stmt1->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
	    $stmt1->execute();
		if ($stmt1->rowCount() < 1) {
			$Type= "Internal";

			$query_stmt1 = $conn->prepare("INSERT INTO `unsubscriber`
										(
											`FirstName`,
											`LastName`,
											`internal_add_date`,
											`UnsubscriberEmail`,
											`AdminId`,
											`CampID`,
											`Category`,
											`Type`
										)
										VALUES
										(
											:FirstName,
											:LastName,
											 NOW(),
											:UnsubscriberEmail,
											:AdminId,
											:CampID,
											:Category,
											:Type
										)");


		$query_stmt1->bindParam(':FirstName', $FirstName);
		$query_stmt1->bindParam(':LastName', $LastName);
		$query_stmt1->bindParam(':UnsubscriberEmail', $UnsubscriberEmail);
		$query_stmt1->bindParam(':AdminId', $AdminId);
		$query_stmt1->bindParam(':CampID', $CampID);
		$query_stmt1->bindParam(':Category', $unsubscriber_type);
		$query_stmt1->bindParam(':Type', $Type);
	
		$result = $query_stmt1->execute();
		
		if($query_stmt1->rowCount() > 0)
		{
			header("Location:unsubscribeMsg.php");
		}	
		}
		else {
			$stmt12= $conn->prepare("SELECT * FROM  unsubscriber 
		WHERE UnsubscriberEmail = :UnsubscriberEmail AND internal_add_date IS NULL AND external_add_date IS NOT NULL");
	    $stmt12->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
	    $stmt12->execute();
		if($stmt12->rowCount() > 0){
			$query_stmt2 = $conn->prepare("UPDATE unsubscriber SET internal_add_date= NOW(), `Type`='Both'  WHERE  UnsubscriberEmail = :UnsubscriberEmail");
			$query_stmt2->bindParam(':UnsubscriberEmail', $UnsubscriberEmail);
			$result2 = $query_stmt2->execute();
		
			if($query_stmt2->rowCount() > 0)
			{
				header("Location:unsubscribeMsg.php");
			}	
			

		}
		else {
			header("Location:already_unsubscribeMsg.php");
		}
			
		}

		
	}
		if (isset($_GET['CampID']) && (!empty($_GET['CampID']))) 
		{
			$CampID = $_GET['CampID'];
			header("Location:unsubscribeMsg.php?CampID=".$CampID."");
		}
