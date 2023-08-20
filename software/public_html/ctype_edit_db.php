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
if (isset($_SESSION['CTYPEE'])) {
	if ($_SESSION['CTYPEE'] == "NO") {
  
		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
  }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $id = $_POST['id'];
  $ctype_name = $_POST["ctype_name"];
  $data_format1 = trim($_POST["data_format1"]);
  $data_format2 = trim($_POST["data_format2"]);
  $data_scopus = trim($_POST["data_scopus"]);
  $data_wos = trim($_POST["data_wos"]);
  $data_automatic = trim($_POST["data_automatic"]);
  $ctype_domainwise_mail_send = trim($_POST["ctype_domainwise_mail_send"]);
  $ctype_status = trim($_POST["ctype_status"]);


  $update_stmt = $conn->prepare("UPDATE  Campaign_type SET ctype_name= :ctype_name, data_format1= :data_format1, data_format2= :data_format2,
  data_scopus= :data_scopus, data_wos= :data_wos, data_automatic= :data_automatic, ctype_domainwise_mail_send= :ctype_domainwise_mail_send, ctype_status= :ctype_status WHERE
  ctype_id=:ctype_id");
  $update_stmt->bindValue(':ctype_id', $id);
  $update_stmt->bindValue(':ctype_name', $ctype_name);
  $update_stmt->bindValue(':data_format1', $data_format1);
  $update_stmt->bindValue(':data_format2', $data_format2);
  $update_stmt->bindValue(':data_scopus', $data_scopus);
  $update_stmt->bindValue(':data_wos', $data_wos);
  $update_stmt->bindValue(':data_automatic', $data_automatic);
  $update_stmt->bindValue(':ctype_domainwise_mail_send', $ctype_domainwise_mail_send);
  $update_stmt->bindValue(':ctype_status', $ctype_status);
  if ($update_stmt->execute()) {

    header("Location: ctype_form.php");
    exit();
  }
}
