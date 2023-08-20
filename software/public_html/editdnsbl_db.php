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
if (isset($_SESSION['EDNS'])) {
  if ($_SESSION['EDNS'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}
 

if ($_SERVER["REQUEST_METHOD"] == "POST") {




  $id = trim($_POST["id"]);
  $dnsbl_name = trim($_POST["dnsbl_name"]);
 echo  $priority_color_new = trim($_POST["priority_color"]);
 echo  $priority_score_new = trim($_POST["priority_score"]);
  $status = trim($_POST["status"]);

  // Fetch old record for score and color

  $old="SELECT * FROM `dnsbl` WHERE `dnsbl_id`= :dnsblid";
  $old=$conn->prepare($old);
  $old->bindParam(':dnsblid', $id);
  $old->execute();
  $old=$old->fetch();

  $priority_color_old=$old['priority_color'];
  $priority_score_old=$old['priority_score'];


  // Insert this data in dnsbl archive table
  $insert=" INSERT INTO `dnsbl_archive`( `dnsbl_id`, `dnsbl_name`, `priority_color`, `priority_score`, `status`) 
  SELECT `dnsbl_id`, `dnsbl_name`, `priority_color`, `priority_score`, `status` from dnsbl where dnsbl_id = :dnsbl_id";
   $insert=$conn->prepare($insert);
   $insert->bindParam(':dnsbl_id', $id);
   $insert->execute();

if(($priority_score_new==$priority_score_old) && ($priority_color_new==$priority_color_old))
{ /*  dont do anything if old and new scores are equal */ }
else{
// in ip blacklist log table change the value of score and color for all the entered ips for given dnsbl.
  $query="UPDATE `tbl_ipblacklist_log` SET `blacklist_color`=:blacklist_color ,`blacklist_score`=:blacklist_score WHERE dnsbl_id=:dnsblid";
    $query=$conn->prepare($query);
    $query->bindParam(':dnsblid', $id);
    $query->bindValue(':blacklist_color', $priority_color_new);
    $query->bindValue(':blacklist_score', $priority_score_new);
    $query->execute();

// fetch the ipdetail_id of all the above changed records
$ipid="SELECT GROUP_CONCAT(distinct `ipdetailid`) as ipid FROM `tbl_ipblacklist_log` WHERE `dnsbl_id`=:dnsblid";
$ipid=$conn->prepare($ipid);
$ipid->bindParam(':dnsblid', $id);
$ipid->execute();
$ipid=$ipid->fetch();
echo $ipdetailsids=$ipid['ipid'];

// fetch ip details of the changed ip ids.
 $ipdet="SELECT * from ipdetails where ipdetailid in (". $ipdetailsids .")" ;
 $ipdet=$conn->prepare($ipdet);
 $ipdet->execute();
 $ipdet=$ipdet->fetchAll();

 foreach ($ipdet as $ipdt) {
  $ipdet_id=$ipdt['ipdetailid'];
  // calculate new score for each ip detail record
  $new_score= $ipdt['ipblack_score'] - $priority_score_old + $priority_score_new;
 
  // calculate new color and status of ip 
  if($new_score > 0 && $new_score <=2) {
    $new_color = 'yellow';
    $new_status = 'WHITELIST';
  }
  else if($new_score > 2 && $new_score <=4) {
    $new_color = 'orange';
    $new_status = 'WHITELIST';
  }
  else if($new_score > 4 && $new_score <=9) {
    $new_color = 'red';
    $new_status = 'WHITELIST';
  }
  else if($new_score > 9) {
    $new_color = 'black';
    $new_status = 'BLACKLIST';
  } 
  else{
    $new_color = 'green';
    $new_status = 'WHITELIST';
  }

  $update_stmt = $conn->prepare("UPDATE ipdetails SET
 ipblack_score=:ipblack_score,
 ipstatus=:ipstatus, 
 ipblack_color=:ipblack_color  
 
 WHERE ipdetailid = $ipdet_id");
$update_stmt->bindValue(':ipblack_score', $new_score);
$update_stmt->bindValue(':ipblack_color', $new_color);
$update_stmt->bindValue(':ipstatus', $new_status);


if ($update_stmt->execute()) {

}



 }

}

// update the data in dnsbl tabel 
  $update_stmt = $conn->prepare("UPDATE dnsbl SET
     dnsbl_name=:dnsbl_name,
     priority_color=:priority_color, 
     priority_score=:priority_score,  
     status=:status 
    WHERE dnsbl_id= $id");
  $update_stmt->bindValue(':dnsbl_name', $dnsbl_name);
  $update_stmt->bindValue(':priority_color', $priority_color_new);
  $update_stmt->bindValue(':priority_score', $priority_score_new);
  $update_stmt->bindValue(':status', $status);

  if ($update_stmt->execute()) {




    header("Location: dnsbl.php");
    exit();
  }
}
