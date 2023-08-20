<?php
ob_start();
session_start();
include 'include/conn.php';

$AdminId = $_SESSION['AdminId'];

if (!isset($_SESSION['AdminId'])) {
	//User not logged in. Redirect them back to the login page.//
	header('Location: login.php');
	exit;
}

if (isset($_GET['CampID']) &&($_GET['ok']=='Permanently')) {
	$CampID =  $_GET['CampID'];


	$sql = "SELECT * 
					FROM campaingauthors_tempnepacd
					WHERE CampID='$CampID'";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$count = $stmt->rowCount();

	if ($count < 1) {

 
		//Hold Archive
		$sql_archive = "SELECT * FROM campaingauthors WHERE CampID='$CampID'";
		$stmt_archive = $conn->prepare($sql_archive);
		$stmt_archive->execute();
		$To_archive_data =	$stmt_archive->fetchAll();

		$conn->beginTransaction();
		foreach ($To_archive_data as $data) {
			//Insert in archive
			$insert = "INSERT INTO 	campaingauthors_hold_archive(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
	affiliation, Country,
	email, article_title, eurekaselect_url, sending_IP, from_email, sending_hostname, Status, Sent_email_datetime)

	VALUES (:CampaingAuthorsID,:CampID, :rtemid, :Initials, :Journal_title, :Role, :Fname, :Lastname, :Add1, :Add2, :Add3, :Add4,:affiliation, :Country,
		:email, :article_title, :eurekaselect_url, :sending_IP, :from_email, :sending_hostname, :Status, :Sent_email_datetime)";
			$stmt = $conn->prepare($insert);
			$stmt->bindValue(':CampaingAuthorsID', $data["CampaingAuthorsID"]);
			$stmt->bindValue(':CampID', $data["CampID"]);
			$stmt->bindValue(':rtemid', $data["rtemid"]);
			$stmt->bindValue(':Initials', $data["Initials"]);
			$stmt->bindValue(':Journal_title', $data["Journal_title"]);
			$stmt->bindValue(':Role', $data["Role"]);
			$stmt->bindValue(':Fname', $data["Fname"]);
			$stmt->bindValue(':Lastname', $data["Lastname"]);
			$stmt->bindValue(':Add1', $data["Add1"]);
			$stmt->bindValue(':Add2', $data["Add2"]);
			$stmt->bindValue(':Add3', $data["Add3"]);
			$stmt->bindValue(':Add4', $data["Add4"]);
			$stmt->bindValue(':affiliation', $data["affiliation"]);
			$stmt->bindValue(':Country', $data["Country"]);
			$stmt->bindValue(':email', $data["email"]);
			$stmt->bindValue(':article_title', $data["article_title"]);
			$stmt->bindValue(':eurekaselect_url', $data["eurekaselect_url"]);
			$stmt->bindValue(':sending_IP', $data["ipaddress"]);
			$stmt->bindValue(':from_email', $data["emailaddress"]);
			$stmt->bindValue(':sending_hostname', $data["hostname"]);
			$stmt->bindValue(':Sent_email_datetime', $data["Sent_email_datetime"]);
			$stmt->bindValue(':Status', $data["Status"]);
			$stmt->execute();
		}
		$conn->commit();

		//DELETE archived data from campaginauthors
		$sql_delete = "DELETE FROM campaingauthors WHERE CampID='$CampID'";
		$stmt_delete = $conn->prepare($sql_delete);
		$stmt_delete->execute();

		//Camp Status to archive
 $cstatus="UPDATE campaign set Camp_Status ='Archive' where CampID='$CampID'";
 $camp_s=$conn->prepare($cstatus);$camp_s->execute();

 //Camp to Campaign flow
 $sql = "INSERT INTO Campaign_flow (CampID, Camp_Status) 
 VALUES (:CampID, 'Archive')";
$stmtf = $conn->prepare($sql); $stmtf->bindValue(':CampID', $CampID);
$resultf = $stmtf->execute();

		if ($To_archive_data > 0) {
			header('Location: index.php');
		}
	} else {
		echo '<script>alert("Campaign cannot be holded right now! Please try few minutes later..")</script>';

		header('Location: index.php');
	}
}
