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

if (isset($_SESSION['url_block_type'])) {
  if ($_SESSION['url_block_type'] !="sys-defined") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {





  $url = trim($_POST["url"]);
  $status = trim($_POST["status"]);
  $AdminId = $_SESSION['AdminId'];

  //Checl for incorrect url (containing http OR https OR // OR /)

if (stripos($url,"http")!==false || stripos($url,"https")!==false  || stripos($url,"//")!==false  || stripos($url,"/")!==false) {
  $not_allwd="true";
  header("Location:block_url_form.php?not_allwd={$not_allwd}");
  exit();


}
    //check if email exist in both the tables
    $stmt = $conn->prepare("SELECT * FROM blocked_url WHERE url=:url");
    $stmt->bindValue(':url', $url);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
    $burl= "true";

  header("Location:block_url_form.php?burl={$burl}");
  exit();

    }


  $ins_stmt = $conn->prepare("INSERT INTO  blocked_url (url, status, AdminId) 
    VALUES (:url, :status, :AdminId)");
  $ins_stmt->bindValue(':url', $url);
  $ins_stmt->bindValue(':status', $status);
  $ins_stmt->bindValue(':AdminId', $AdminId);

  if ($ins_stmt->execute()) {




    header("Location: block_url.php");
    exit();
  }
}
