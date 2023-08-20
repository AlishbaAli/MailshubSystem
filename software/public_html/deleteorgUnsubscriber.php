<?php
ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['DELUNSUBO'])) {
	if ($_SESSION['DELUNSUBO'] == "NO") {

		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
}

if (isset($_GET["id"])) {

    $sql = "DELETE FROM  orgunit_unsubscriber
    WHERE orgunit_unsubscriber_id = :orgunit_unsubscriber_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':orgunit_unsubscriber_id', $_GET["id"]);

    $result = $stmt->execute();

    if ($result > 0) {
        header("Location: orgunsubscriberList.php");
        exit();
    }
}
