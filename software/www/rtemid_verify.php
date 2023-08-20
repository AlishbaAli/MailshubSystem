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



$orgunit_id= $_GET['orgid'];
$request_rte_id=$_GET['id'];
$status="Accepted";
 

   //get requested reply to email

   $stmtr= $conn->prepare("SELECT req_rep_to_email FROM tbl_request_rte WHERE request_rte_id=:request_rte_id");
   $stmtr->bindValue(':request_rte_id', $request_rte_id);
   $stmtr->execute();
   $req_rep_to_email= $stmtr->fetch();
   
   // Check if already in the main reply to email pool

   $stmt_chk =$conn->prepare("SELECT rtemid,reply_to_email FROM reply_to_emails WHERE reply_to_email=:reply_to_email");
   $stmt_chk->bindValue(':reply_to_email', $req_rep_to_email['req_rep_to_email']);
   $stmt_chk->execute();
   $rtemid= $stmt_chk->fetch();
   $rejected_approved_by= $updated_by= $added_by= $assigned_by= $_SESSION['AdminId'];
   if($stmt_chk->rowCount()>0){
   
//accpeted

$rejected_approved_by= $updated_by= $added_by= $assigned_by= $_SESSION['AdminId'];
    $stmt= $conn->prepare("UPDATE tbl_request_rte SET status=:status, rejected_approved_date= NOW(), 
    rejected_approved_by=:rejected_approved_by WHERE request_rte_id=:request_rte_id");
        
    $stmt->bindValue(':request_rte_id', $request_rte_id);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':rejected_approved_by', $rejected_approved_by);
    if($stmt->execute()){

  //make it active (incase of system block)

  $stmtu =$conn->prepare("UPDATE reply_to_emails SET rtem_status='Active', updated_by=:updated_by WHERE rtemid=:rtemid");
  $stmtu->bindValue(':updated_by', $updated_by);
  $stmtu->bindValue(':rtemid', $rtemid['rtemid']);
  $stmtu->execute();

        //assign to that organization
        $ins_stmt = $conn->prepare("INSERT INTO  tbl_orgunit_rte (rtemid, orgunit_id, assigned_by) 
        VALUES (:rtemid, :orgunit_id, :assigned_by)");
            $ins_stmt->bindValue(':rtemid', $rtemid['rtemid']);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->bindValue(':assigned_by', $assigned_by);
            if($ins_stmt->execute()){
            header("Location: reply_to_email_req_mngmt.php");
            exit();

            }
          }
    }



   else{






//accpeted

    $stmt= $conn->prepare("UPDATE tbl_request_rte SET status=:status, rejected_approved_date= NOW(), 
    rejected_approved_by=:rejected_approved_by WHERE request_rte_id=:request_rte_id");
        
    $stmt->bindValue(':request_rte_id', $request_rte_id);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':rejected_approved_by', $rejected_approved_by);
    if($stmt->execute()){

   
       //add to the main pool
       $rtem_status="Active";
       $ins_stmt = $conn->prepare("INSERT INTO  reply_to_emails (reply_to_email, rtem_status, added_by) 
       VALUES (:reply_to_email, :rtem_status, :added_by)");
           $ins_stmt->bindValue(':reply_to_email', $req_rep_to_email['req_rep_to_email']);
           $ins_stmt->bindValue(':rtem_status', $rtem_status);
           $ins_stmt->bindValue(':added_by', $added_by);
           if($ins_stmt->execute()){
            $rtemid =  $conn->lastInsertId();

  

        //assign to that organization
        $ins_stmt = $conn->prepare("INSERT INTO  tbl_orgunit_rte (rtemid, orgunit_id, assigned_by) 
        VALUES (:rtemid, :orgunit_id, :assigned_by)");
            $ins_stmt->bindValue(':rtemid', $rtemid);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->bindValue(':assigned_by', $assigned_by);
            if($ins_stmt->execute()){
            header("Location: reply_to_email_req_mngmt.php");
            exit();

            }
          }
    }


  
  }



