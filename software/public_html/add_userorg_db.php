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

if (isset($_SESSION['ADU'])) {
  if ($_SESSION['ADU'] == "NO") {

    //User not logged in. Redirect them back to the login page.
    header('Location: page-403.html');
    exit;
  }
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $user_id = trim($_POST["user_id"]);


  $orgunit_id = trim($_POST["orgunit"]);

  //Assigning roles to Users

  foreach ($_POST['role_list'] as $as_roles) {



    $ins_stmt = $conn->prepare("INSERT INTO  tbl_user_role_prev (role_prev_id, user_id) 
    VALUES (:role_prev_id, :user_id)");
    $ins_stmt->bindValue(':role_prev_id', $as_roles);
    $ins_stmt->bindValue(':user_id', $user_id);
    $ins_stmt->execute();
  }




  $ins_stmt = $conn->prepare("INSERT INTO  tbl_orgunit_user (orgunit_id, user_id) 
  VALUES (:orgunit_id, :user_id)");
  $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
  $ins_stmt->bindValue(':user_id', $user_id);
  if ($ins_stmt->execute()) {



    header("Location: add_user.php");
    exit();
  }
}
