<?php
	ob_start();
	session_start();
	include 'include/conn.php';	
								
	$AdminId = $_SESSION['AdminId'];	
	
	if(!isset($_SESSION['AdminId']))
	{
		//User not logged in. Redirect them back to the login page.//
		header('Location: login.php');
		exit;
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
			header('Location: index.php');
		}		
	}
