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
if (isset($_SESSION['IRM'])) {
    if ($_SESSION['IRM'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $req_id=$_POST['req_id'];
    $status="Rejected";
    $reason_select= $_POST['reason_select'];
    $reason= trim($_POST['reason']);

    if($reason_select=="other"){
        $reason_selected=$reason;

    }
    else{
        $reason_selected=$reason_select;
    }


$accept_rej_by= $_SESSION['AdminId'];

    $stmt= $conn->prepare("UPDATE request_institute SET status=:status, reason=:reason , accept_rej_date= NOW(), 
    accept_rej_by=:accept_rej_by WHERE req_id=:req_id");
        
    $stmt->bindValue(':req_id', $req_id);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':reason', $reason_selected);
    $stmt->bindValue(':accept_rej_by', $accept_rej_by);
    if($stmt->execute()){

        $stmt2= $conn->prepare("UPDATE  request_domain SET status=:status WHERE req_id=:req_id");
        $stmt2->bindValue(':req_id', $req_id);
        $stmt2->bindValue(':status', $status);
        if($stmt2->execute())
        {
        header("Location: institute_req_mngmnt.php");
        exit();
        }
    }


  



}
