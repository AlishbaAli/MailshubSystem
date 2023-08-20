<?php
ob_start();
session_start();
include 'include/conn.php';

$AdminId = $_SESSION['AdminId'];
if (isset($_SESSION['DLAU'])) {
	if ($_SESSION['DLAU'] == "NO") {
  
		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
  }
try {
	if (isset($_GET["CampID"])) {
		$CampID =  $_GET['CampID'];

		$sql = "DELETE FROM `campaingauthors` 
						WHERE CampID = :CampID";

		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':CampID', $CampID);

		$result = $stmt->execute();

		if ($result > 0) {

		$sql = "UPDATE activity set add_authordata= null 
						WHERE CampID = :CampID";

		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':CampID', $CampID);

		$result = $stmt->execute();

			header("Location:index.php");
		}
	}

	if (isset($_GET["CampaingAuthorsID"])) {
		$CampaingAuthorsID = $_GET['CampaingAuthorsID'];

		$sql1 = "SELECT CampID FROM `campaingauthors` 
						WHERE CampaingAuthorsID = :CampaingAuthorsID";

		$stmt1 = $conn->prepare($sql1);
		$stmt1->bindValue(':CampaingAuthorsID', $CampaingAuthorsID);

		$result1 = $stmt1->execute();

		if ($stmt1->rowCount() > 0) {
			$result1 = $stmt1->fetchAll();
			foreach ($result1 as $row) {
				echo $CampID = $row["CampID"];
				$CampID = $row["CampID"];
			}
		}

		$sql = "DELETE FROM `campaingauthors` 
						WHERE CampaingAuthorsID = :CampaingAuthorsID";

		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':CampaingAuthorsID', $CampaingAuthorsID);

		$result = $stmt->execute();
		//------------------------------------
		$sql2 = "SELECT * FROM `campaingauthors` 
						WHERE CampID = :CampID";

		$stmt2 = $conn->prepare($sql2);
		$stmt2->bindValue(':CampID', $CampID);

		$result2 = $stmt2->execute();

		if (($stmt2->rowCount()) <= 0) {

		$sql3 = "UPDATE activity set add_authordata= null 
						WHERE CampID = :CampID";

		$stmt3 = $conn->prepare($sql3);
		$stmt3->bindValue(':CampID', $CampID);

		$result3 = $stmt3->execute();}
		//-------------------------------------

		if ($result > 0) {
			header("Location: index.php");
		}
	}
}
//catch exception
catch (Exception $e) {
	echo 'Message: ' . $e->getMessage();
}
