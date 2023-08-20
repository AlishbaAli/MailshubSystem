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


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $flag=0;
  $id = $_POST['id'];
  $reply_to_email = $_POST["reply_to_email"];
  $rtem_status = $_POST["rtem_status"];
  $reason= $_POST["reason"];

  
//dont allow permenant block URL 
$stmt_url=$conn->prepare("SELECT url FROM permanently_blocked_url WHERE status='Active'");
$stmt_url->execute();
$urls=$stmt_url->fetchAll(); 
  foreach ($urls as $url) {
    
    if (stripos($reply_to_email,$url['url'])!==false) {
        //url found in reply_to_email
        $flag=1;
        break;
    }
  }
  
  if($flag==1){
    $blck= "true";
    header("Location: reply_to_email_edit.php?id={$id}&blck={$blck}");
    exit();
    }
   

    $updated_by=$_SESSION['AdminId'];
  $update_stmt = $conn->prepare("UPDATE reply_to_emails SET reply_to_email= :reply_to_email, rtem_status=:rtem_status, rtem_reason=:rtem_reason,
   updated_by=:updated_by WHERE
    rtemid=:rtemid");
  $update_stmt->bindValue(':rtemid', $id);
  $update_stmt->bindValue(':rtem_status', $rtem_status);
  $update_stmt->bindValue(':reply_to_email', $reply_to_email);
  $update_stmt->bindValue(':rtem_reason', $reason);
  $update_stmt->bindValue(':updated_by', $updated_by);
  if ($update_stmt->execute()) {
    //rtem_status=='In Active'

    if($rtem_status=='In Active'){
    $update_stmt_o = $conn->prepare("UPDATE tbl_orgunit_rte SET status='In Active', org_rtem_reason=:org_rtem_reason, updated_by=:updated_by WHERE
    rtemid=:rtemid");
  $update_stmt_o->bindValue(':rtemid', $id);
  $update_stmt_o->bindValue(':org_rtem_reason', $reason);
  $update_stmt_o->bindValue(':updated_by', $updated_by);

  
  if($update_stmt_o->execute()){

    //GET campaigns where this reply_to_email is being used
 $stmt_get_cid= $conn->prepare("SELECT `CampID` FROM campaign WHERE rtemid=:rtemid");
 $stmt_get_cid->bindValue(':rtemid', $id);
 $stmt_get_cid->execute();

//SET ctre_status= In Active
  While( $cid=$stmt_get_cid->fetch()){
    $CID = $cid["CampID"];
    
 $stmt_set_cid= $conn->prepare("UPDATE campaign SET crtem_status='In Active' WHERE CampID=:CampID");
 $stmt_set_cid->bindValue(':CampID', $CID);
 $stmt_set_cid->execute();

  }


  }


    }
   
  
  }
  header("Location: reply_to_email_form.php");
  exit();


}
