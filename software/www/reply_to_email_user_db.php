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


  //Assigning reply_to_emails to Users
  if ($_POST["user"] != NULL) {

    $user_id = $_POST["user"];
    $user_rtem = $_POST["user_rtem"];





    //Assigning reply_to_emails to Users

    foreach ($user_rtem as $remails) {



      $ins_stmt = $conn->prepare("INSERT INTO  tbl_user_rte (rtemid, user_id) 
    VALUES (:rtemid, :user_id)");
      $ins_stmt->bindValue(':rtemid', $remails);
      $ins_stmt->bindValue(':user_id', $user_id);
      if ($ins_stmt->execute()) {

        header("Location: reply_to_email_form.php");
        exit();
      }
    }
  }
}
