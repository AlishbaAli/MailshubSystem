<?php

 error_reporting(0);
  ini_set('display_errors', 0);
ob_start();
session_start();
include 'include/conn.php';

$AdminId = $_SESSION['AdminId'];

try {
	if (isset($_GET["CampID"])) {
		$CampID =  $_GET['CampID'];

		$sql = "DELETE FROM `draft` 
					WHERE CampID = :CampID";

		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':CampID', $CampID);

		$result = $stmt->execute();

		if ($result > 0) {
			$CampID =  $_GET['CampID'];

			// Update record in activity table
			$sql = "UPDATE `activity` SET `add_draft_activity` = 0 
						WHERE `CampID` = :CampID";

			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':CampID', $CampID);
			$result = $stmt->execute();

			if ($result > 0) {
				header("Location: addDraft.php?CampID=" . $CampID . "");
			}
		}
	}
}

//catch exception
catch (Exception $e) {
	echo 'Message: ' . $e->getMessage();
}
