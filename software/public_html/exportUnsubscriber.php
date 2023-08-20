<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
session_start();
include 'include/conn.php';
if (!isset($_SESSION['AdminId'])) {
	//User not logged in. Redirect them back to the login page.//
	header('Location: login.php');
	exit;
}
if (isset($_SESSION['EXUSUBM'])) {
	if ($_SESSION['EXUSUBM'] == "NO") {

		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
}
$sqlem = "SELECT count(UnsubscriberEmail) as NUM FROM unsubscriber ";
$em = $conn->query($sqlem);
$rowem = $em->fetch(PDO::FETCH_ASSOC);
$count = $rowem['NUM'];
if ($count > 0) {
	$sql = "SELECT FirstName, UnsubscriberEmail, UnsubscribeDateTime FROM unsubscriber";
	$sth = $conn->prepare($sql);
	$sth->execute();
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	$headers = array("FNAME", "UnsubscriberEmail", "UnsubscribeDateTime");
	$fp = fopen('php://output', 'w');
	if ($fp && $sth) {
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename=Unsubscriber-List.csv');
		header('Pragma: no-cache');
		header('Expires: 0');
		fputcsv($fp, $headers);

		do {
			$valuesArray = array();
			foreach ($row as $name => $value) {
				$valuesArray[] = $value;
			}
			fputcsv($fp, $valuesArray);
		} while ($row = $sth->fetch(PDO::FETCH_NUM));
	}
} else {
	echo "<script>alert('No records found!');</script>";
	echo "<script>window.location = 'unsubscriberList.php';</script>";
}
