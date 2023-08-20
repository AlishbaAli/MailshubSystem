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
if (isset($_SESSION['ROS'])) {
  if ($_SESSION['ROS'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  
$id= $_POST['id'];
$rtemid= $_POST['rtemid'];
$status= $_POST['status'];
$reason= $_POST['reason'];
$orgunit_id= $_POST['orgunit_id'];
 
$updated_by=$_SESSION['AdminId'];
$update_stmt_o = $conn->prepare("UPDATE tbl_orgunit_rte SET status=:status, org_rtem_reason=:org_rtem_reason, updated_by=:updated_by WHERE
o_rte_id=:o_rte_id");
$update_stmt_o->bindValue(':o_rte_id', $id);
$update_stmt_o->bindValue(':status', $status);
$update_stmt_o->bindValue(':org_rtem_reason', $reason); 
$update_stmt_o->bindValue(':updated_by', $updated_by); 
  if($update_stmt_o->execute()){

    //GET campaigns where this reply_to_email is being used
 $stmt_get_cid= $conn->prepare("SELECT * FROM `tbl_orgunit_user` INNER JOIN campaign ON campaign.AdminID = tbl_orgunit_user.user_id
  AND orgunit_id = :orgunit_id AND rtemid= :rtemid AND ou_status='Active'");
 $stmt_get_cid->bindValue(':rtemid', $rtemid);
 $stmt_get_cid->bindValue(':orgunit_id', $orgunit_id);
 $stmt_get_cid->execute();

//SET ctre_status= In Active
While( $cid=$stmt_get_cid->fetch()){
 $CID = $cid["CampID"]; 
 $stmt_set_cid= $conn->prepare("UPDATE campaign SET crtem_status=:crtem_status WHERE CampID=:CampID");
 $stmt_set_cid->bindValue(':CampID', $CID);
 $stmt_set_cid->bindValue(':crtem_status', $status);
 $stmt_set_cid->execute();
  }
}
  header("Location: reply_to_email_form_users.php");
  exit();


}
