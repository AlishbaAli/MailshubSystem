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
if (isset($_SESSION['MISB'])) {
  if ($_SESSION['MISB'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}

//user status can be terminated/suspended/ or activated again after suspended
if ($_SERVER["REQUEST_METHOD"] == "POST")
{ 
    $orgunit_id= $_POST['orgunit_id'];
    $ri_id= $_POST['ri_id'];
    $org_inst_status= $_POST['org_inst_status'];

    $stmt= $conn->prepare("UPDATE organizational_institutes SET org_inst_status=:org_inst_status WHERE orgunit_id=:orgunit_id AND ri_id=:ri_id");
    $stmt->bindValue(':orgunit_id',$orgunit_id);
    $stmt->bindValue(':ri_id',$ri_id);
    $stmt->bindValue(':org_inst_status',$org_inst_status);
    if($stmt->execute())
    {
        header("Location: request_institute.php");
        exit();
    }

    
}