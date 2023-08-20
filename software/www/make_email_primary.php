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
    $AdminId= $_SESSION['AdminId'];
    $admin_email_id=$_GET['aeid'];

    //update already primary email as 'Alternate'
    $stmt1 = $conn->prepare("UPDATE admin_email SET email_type='Alternate' WHERE email_type='Primary' AND AdminId='$AdminId'");
    $stmt1->execute();


    //Update selected email as  'Primary'
    $stmt2 = $conn->prepare("UPDATE admin_email SET email_type='Primary' WHERE admin_email_id='$admin_email_id' AND AdminId='$AdminId'");
    $stmt2->execute();


    //Select primary email to update
    $stmt3= $conn->prepare("SELECT emails FROM admin_email WHERE AdminId='$AdminId' AND email_type='Primary'");
    $stmt3->execute();
    $emails = $stmt3->fetch();
    $emails= $emails['emails'];

    //Update primary email from admin_email to admin table
    $stmt4= $conn->prepare("UPDATE admin SET email='$emails' WHERE AdminId='$AdminId'");
    if($stmt4->execute()){

        header("Location: profile2.php");
        exit();
    }
   
}
?>