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

    $request_rte_id=$_POST['request_rte_id'];
    $status="Rejected";
    $reason_select= $_POST['reason_select'];
    $reason= trim($_POST['reason']);

    if($reason_select=="other"){
        $reason_selected=$reason;

    }
    else{
        $reason_selected=$reason_select;
    }


$rejected_approved_by= $_SESSION['AdminId'];
    $stmt= $conn->prepare("UPDATE tbl_request_rte SET status=:status, reason=:reason , rejected_approved_date= NOW(), 
    rejected_approved_by=:rejected_approved_by WHERE request_rte_id=:request_rte_id");
        
    $stmt->bindValue(':request_rte_id', $request_rte_id);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':reason', $reason_selected);
    $stmt->bindValue(':rejected_approved_by', $rejected_approved_by);
    if($stmt->execute()){

        header("Location: reply_to_email_req_mngmt.php");
        exit();
    }


  



}
