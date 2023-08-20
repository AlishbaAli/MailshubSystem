<?php
	ob_start();
	session_start();
	include 'include/conn.php';	
								
	$AdminId = $_SESSION['AdminId'];	
	
	if(!isset($_SESSION['AdminId']))
	{
		//User not logged in. Redirect them back to the login page.//
		header('Location: logout.php');
		exit;
	}
	if (isset($_SESSION['RCB'])) {
		if ($_SESSION['RCB'] == "NO") {
	
			//User not logged in. Redirect them back to the login page.
			header('Location: page-403.html');
			exit;
		}
	}

	if (isset($_GET['CampID'])) 
	{	
		$CampID =  $_GET['CampID'];

		$sql = "UPDATE `campaign` 
				SET `Camp_Status` = 'Active'
				where CampID = :CampID";
				
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':CampID', $CampID);
		$result = $stmt->execute();	
		if($result > 0)
		{
			$sql = "INSERT INTO `Campaign_flow`( `CampID`, `Camp_Status`) VALUES (:CampID , 'Resumed')";

			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':CampID', $CampID);
			$result = $stmt->execute();

			header('Location: index.php');
		}		
	}
