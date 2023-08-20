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
if (isset($_SESSION['RRTEE'])) {
  if ($_SESSION['RRTEE'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $flag=0;
  //Assigning reply_to_emails to org_unit

$request_rte_id= trim($_POST["id"]);
  $req_rep_to_email= trim($_POST["req_rep_to_email"]);



//dont allow permenant block URL 
$stmt_url=$conn->prepare("SELECT url FROM permanently_blocked_url WHERE status='Active'");
$stmt_url->execute();
$urls=$stmt_url->fetchAll(); 

foreach ($urls as $url) {
    
  if (stripos($req_rep_to_email,$url['url'])!==false) {
      //url found in req_rep_to_email
      $flag=1;
      break;
  }
}

if($flag==1){
  $blck= "true";
  header("Location: reply_to_email_req_edit.php?id={$request_rte_id}&blck={$blck}");
  exit();
  }
  //dont allow block URL
  if($_SESSION['url_block_type']=="sys-defined"){
    $stmt_url=$conn->prepare("SELECT url FROM blocked_url WHERE status='Active'");
    $stmt_url->execute();
    $urls=$stmt_url->fetchAll(); 
}
if($_SESSION['url_block_type']=="ou-dedicated" || $_SESSION['url_block_type']=="ou-hybrid"){
    $stmt_url=$conn->prepare("SELECT url FROM blocked_url_org WHERE status='Active'");
    $stmt_url->execute();
    $urls=$stmt_url->fetchAll(); 



}


foreach ($urls as $url) {
    
    if (stripos($req_rep_to_email,$url['url'])!==false) {
        //url found in req_rep_to_email
        $flag=1;
        break;
    }
}
if($flag==1){
$blck= "true";
header("Location: reply_to_email_req_edit.php?id={$request_rte_id}&blck={$blck}");
exit();
}

 //dont allow block domain
 if($_SESSION['domain_block_type']=="sys-defined"){
  $stmt_url=$conn->prepare("SELECT domain_name FROM blocked_domains WHERE domain_status='Active'");
  $stmt_url->execute();
  $urls=$stmt_url->fetchAll(); 
}
if($_SESSION['domain_block_type']=="ou-dedicated" || $_SESSION['domain_block_type']=="ou-hybrid"){
  $stmt_url=$conn->prepare("SELECT domain_name FROM blocked_domain_org WHERE domain_status='Active'");
  $stmt_url->execute();
  $urls=$stmt_url->fetchAll(); 
}

foreach ($urls as $url) {
  
  if (stripos($req_rep_to_email,$url['domain_name'])!==false) {
      //url found in req_rep_to_email
      $flag=1;
      break;
  }
}
if($flag==1){
  $blck= "true";
  header("Location: reply_to_email_req_edit.php?id={$request_rte_id}&blck={$blck}");
  exit();
  }

  




$sql = "UPDATE tbl_request_rte SET req_rep_to_email=:req_rep_to_email WHERE request_rte_id=:request_rte_id";    
$stmt = $conn->prepare($sql);
//Bind our variables.
$stmt->bindValue(':req_rep_to_email', $req_rep_to_email);
$stmt->bindValue(':request_rte_id', $request_rte_id);
 if($stmt->execute()){

  
    header("Location: reply_to_email_req.php");
      exit();


 
}
    
   
}
