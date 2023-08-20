<?php
ob_start();
session_start();
include 'include/conn.php';
if (isset($_SESSION['AC'])) {
	if ($_SESSION['AC'] == "NO") {

		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
}

$AdminId = $_SESSION['AdminId'];

try {
	if (isset($_GET["CampID"])) {
		$CampID =  $_GET['CampID'];


		$sql = "SELECT COUNT(CampID) FROM `campaingauthors` 
							WHERE CampID = :CampID";

		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':CampID', $CampID);
		$result = $stmt->execute();
		if ($result > 0) {
			$sql = "DELETE FROM `campaingauthors` 
						WHERE CampID = :CampID";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':CampID', $CampID);
			$result = $stmt->execute();
		}


	//Then We Delete "CampID Data" in (draft) Table
	$sql = "SELECT COUNT(CampID) FROM `draft` 
	WHERE CampID = :CampID";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':CampID', $CampID);
$result = $stmt->execute();

if ($result > 0) {
$sql = "DELETE FROM `draft` 
WHERE CampID = :CampID";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':CampID', $CampID);
$result = $stmt->execute();
		


		if ($result > 0) {

		
				//-----------------------


			
			}







			// 	//Then We Delete "CampID Data" in (Campaing Authors) Table
			// 	$sql = "SELECT COUNT(CampID) FROM `campaingauthors` 
			// 			WHERE CampID = :CampID";

			// 	$stmt = $conn->prepare($sql);
			// 	$stmt->bindValue(':CampID', $CampID);	
			// 	$result = $stmt->execute();
			// 	if($result > 0)
			// 	{
			// 		$sql = "DELETE FROM `campaingauthors` 
			// 		WHERE CampID = :CampID";
			// 		$stmt = $conn->prepare($sql);
			// 		$stmt->bindValue(':CampID', $CampID);
			// 		$result = $stmt->execute();						

			// 	}










			//Then We Delete "CampID Data" in (Activity) Table
			$sql = "SELECT COUNT(CampID) FROM `activity` 
							WHERE CampID = :CampID";

			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':CampID', $CampID);
			$result = $stmt->execute();
			if ($result > 0) {
				$sql = "DELETE FROM `activity` 
						WHERE CampID = :CampID";
				$stmt = $conn->prepare($sql);
				$stmt->bindValue(':CampID', $CampID);
				$result = $stmt->execute();
			}

			$sql = "DELETE FROM `campaign` 
			WHERE CampID = :CampID";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':CampID', $CampID);

$result = $stmt->execute();


			header("Location: index.php");
		}
	}
}
//catch exception
catch (Exception $e) {
	echo 'Message: ' . $e->getMessage();
}
