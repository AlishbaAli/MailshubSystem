<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';



$admin_email_id = $_GET['id'];
$stmt2 = $conn->prepare("UPDATE admin_email
SET emails_status='Verified'
 WHERE admin_email_id = '$admin_email_id'");
 $stmt2->execute();

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}

header("Location: profile2.php");
exit;


?>