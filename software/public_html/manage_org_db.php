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


if(isset($_POST['orgunit_id'])){
    $orgunit_id = trim($_POST["orgunit_id"]);

if($orgunit_id=="none"){

    unset($_SESSION['orgunit_id']);
    $data['orgunit_name']="none";
    echo json_encode($data);

}
else{


//set session

   
    $_SESSION['orgunit_id'] = $orgunit_id;

    $stmt = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id = $orgunit_id");
    $stmt->execute();
    $orgname = $stmt->fetch();
    $data['orgunit_name']= $orgname['orgunit_name'];
    echo json_encode($data);
}

}

 
