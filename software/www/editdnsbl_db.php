<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {




  $id = trim($_POST["id"]);
  $dnsbl_name = trim($_POST["dnsbl_name"]);
  $priority_color = trim($_POST["priority_color"]);
  $priority_score = trim($_POST["priority_score"]);
  $status = trim($_POST["status"]);



  $update_stmt = $conn->prepare("UPDATE dnsbl SET
     dnsbl_name=:dnsbl_name,
     priority_color=:priority_color, 
     priority_score=:priority_score,  
     status=:status 
    WHERE dnsbl_id= $id");
  $update_stmt->bindValue(':dnsbl_name', $dnsbl_name);
  $update_stmt->bindValue(':priority_color', $priority_color);
  $update_stmt->bindValue(':priority_score', $priority_score);
  $update_stmt->bindValue(':status', $status);

  if ($update_stmt->execute()) {




    header("Location: dnsbl.php");
    exit();
  }
}
