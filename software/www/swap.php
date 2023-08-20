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


// die();

// if($_GET['rtemid']==""){
// die();
// //  header("Location: reply_to_email_form_users.php");
// //  exit();

// }

$new_rtemid= $_GET['rtemid'];
$CampID= $_GET['CID'];
//get user_id
$stmtu= $conn->prepare("SELECT AdminID, Camp_Status FROM campaign WHERE CampID=:CampID");
$stmtu->bindValue(':CampID', $CampID );
$stmtu->execute();
$res= $stmtu->fetch();
//check of the selected reply to email is already assigned to the user
//if yes then swap 
//if not then assign and swap
$user_id=$res['AdminID'];


$stmtr= $conn->prepare("SELECT * FROM `tbl_user_rte` WHERE `user_id`=:user_id AND `rtemid`=:rtemid");
$stmtr->bindValue(':user_id', $user_id);
$stmtr->bindValue(':rtemid', $new_rtemid );
$stmtr->execute();
//reply to email does not exist so assign then swap
if($stmtr->rowCount()<1){
   //assign to user


$ins_stmt = $conn->prepare("INSERT INTO   tbl_user_rte (rtemid, user_id) 
VALUES (:rtemid, :user_id)");
$ins_stmt->bindValue(':rtemid', $new_rtemid);
$ins_stmt->bindValue(':user_id', $user_id);
$ins_stmt->execute();


}

// Swapping
 //storing status in temp variable
 $original_status= $res['Camp_Status'];
// Stop campaign for a while

$stmt_Stop= $conn->prepare("UPDATE campaign SET Camp_Status='Stop' WHERE CampID=:CampID");
$stmt_Stop->bindValue(':CampID', $CampID );
$stmt_Stop->execute();


//update new reply to email

$stmt_update= $conn->prepare("UPDATE campaign SET rtemid=:rtemid WHERE CampID=:CampID ");
$stmt_update->bindValue(':CampID', $CampID );
$stmt_update->bindValue(':rtemid', $new_rtemid );
$stmt_update->execute();

//Restore campaign status
$stmt_restore= $conn->prepare("UPDATE campaign SET Camp_Status=:Camp_Status WHERE CampID=:CampID");
$stmt_restore->bindValue(':CampID', $CampID );
$stmt_restore->bindValue(':Camp_Status', $original_status );
$stmt_restore->execute();

//SET ctrem_status as Active
$stmt_restore= $conn->prepare("UPDATE campaign SET crtem_status='Active' WHERE CampID=:CampID");
$stmt_restore->bindValue(':CampID', $CampID );
if($stmt_restore->execute()){

    header("Location: reply_to_email_form_users.php");
    exit();
}




