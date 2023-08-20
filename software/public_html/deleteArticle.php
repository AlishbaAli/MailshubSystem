<?php
ob_start();
session_start();
include 'include/conn.php';

$AdminId = $_SESSION['AdminId'];
if (isset($_SESSION['DLA'])) {
	if ($_SESSION['DLA'] == "NO") {
  
		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
  }

try {
	if (isset($_GET["CampID"])) {
		$CampID =  $_GET['CampID'];

		$sql = "DELETE FROM `articles_scopus` 
						WHERE CampID = :CampID";

		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':CampID', $CampID);

		$result = $stmt->execute();

		if ($result > 0) {

		// $sql = "UPDATE activity set add_articles_activity= null 
		// 				WHERE CampID = :CampID";

		// $stmt = $conn->prepare($sql);
		// $stmt->bindValue(':CampID', $CampID);

		// $result = $stmt->execute();

			header("Location:index.php");
		}
	}

	if (isset($_GET["art_scopus_id"])) {
		$art_scopus_id = $_GET['art_scopus_id'];

		$sql1 = "SELECT camp_id FROM `articles_scopus` 
						WHERE art_scopus_id = :art_scopus_id";

		$stmt1 = $conn->prepare($sql1);
		$stmt1->bindValue(':art_scopus_id', $art_scopus_id);

		$result1 = $stmt1->execute();

		if ($stmt1->rowCount() > 0) {
			$result1 = $stmt1->fetchAll();
			foreach ($result1 as $row) {
				echo $CampID = $row["camp_id"];
				$CampID = $row["camp_id"];
			}
		}

		$sql = "DELETE FROM `articles_scopus` 
						WHERE art_scopus_id = :art_scopus_id";

		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':art_scopus_id', $art_scopus_id);

		$result = $stmt->execute();
		//------------------------------------
		$sql2 = "SELECT * FROM `articles_scopus` 
						WHERE camp_id = :camp_id";

		$stmt2 = $conn->prepare($sql2);
		$stmt2->bindValue(':camp_id', $camp_id);

		$result2 = $stmt2->execute();

	// 	if (($stmt2->rowCount()) <= 0) {

	// 	$sql3 = "UPDATE activity set add_articles_activity= null 
	// 					WHERE CampID = :CampID";

	// 	$stmt3 = $conn->prepare($sql3);
	// 	$stmt3->bindValue(':CampID', $CampID);

	// 	$result3 = $stmt3->execute();
	// }
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
