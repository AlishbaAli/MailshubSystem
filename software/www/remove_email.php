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

if(isset($_GET['aeid'])){

    $admin_email_id=$_GET['aeid'];

    $stmt= $conn->prepare("DELETE FROM admin_email WHERE admin_email_id='$admin_email_id'");
    if($stmt->execute()){
        header("Location: profile2.php");
        exit();
    }

}

?>