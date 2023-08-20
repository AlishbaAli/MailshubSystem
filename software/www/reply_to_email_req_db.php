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
  $flag=0;
  //Assigning reply_to_emails to org_unit
  $orgunit_id = $_POST["orgunit_id"];
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
  header("Location: reply_to_email_req.php?blck={$blck}");
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
header("Location: reply_to_email_req.php?blck={$blck}");
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
  header("Location: reply_to_email_req.php?blck={$blck}");
  exit();
  }

  //check if already in pending request
  $status="Pending";
  $sql = "SELECT COUNT(req_rep_to_email) AS num 
  FROM tbl_request_rte 
  WHERE req_rep_to_email = :req_rep_to_email AND orgunit_id=:orgunit_id AND status=:status";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':req_rep_to_email', $req_rep_to_email);    
$stmt->bindValue(':orgunit_id', $orgunit_id);       
$stmt->bindValue(':status', $status);            
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row['num'] > 0)
{
  $rte="true";
  header("Location:reply_to_email_req.php?rte={$rte}");
  exit();
}
//check if already "in active" by org
$sql = "SELECT reply_to_emails.rtemid, reply_to_email, rtem_status, status FROM  reply_to_emails INNER JOIN tbl_orgunit_rte ON 
reply_to_emails.rtemid= tbl_orgunit_rte.rtemid WHERE status='In Active' AND orgunit_id=:orgunit_id AND reply_to_email=:reply_to_email";
$stmti = $conn->prepare($sql);
 
$stmti->bindValue(':orgunit_id', $orgunit_id);    
$stmti->bindValue(':reply_to_email', $req_rep_to_email);            
$stmti->execute();
//$rowi = $stmti->fetch(PDO::FETCH_ASSOC);
if($stmti->rowCount()> 0)
{
$rte_inactive="true";
header("Location:reply_to_email_req.php?rte_inactive={$rte_inactive}");
exit();
}

//check if already "active" in the org
$sql = "SELECT reply_to_emails.rtemid, reply_to_email, rtem_status, status FROM  reply_to_emails INNER JOIN tbl_orgunit_rte ON 
reply_to_emails.rtemid= tbl_orgunit_rte.rtemid WHERE status='Active' AND orgunit_id=:orgunit_id AND reply_to_email=:reply_to_email";
$stmti = $conn->prepare($sql);
 
$stmti->bindValue(':orgunit_id', $orgunit_id);    
$stmti->bindValue(':reply_to_email', $req_rep_to_email);            
$stmti->execute();
//$rowi = $stmti->fetch(PDO::FETCH_ASSOC);
if($stmti->rowCount()> 0)
{
$org_actv="true";
header("Location:reply_to_email_req.php?org_actv={$org_actv}");
exit();
}


$requested_by=$_SESSION['AdminId'];
$sql = "INSERT INTO tbl_request_rte (req_rep_to_email, orgunit_id,status, requested_by) 
     VALUES (:req_rep_to_email, :orgunit_id, :status, :requested_by)";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':req_rep_to_email', $req_rep_to_email);
$stmt->bindValue(':orgunit_id', $orgunit_id);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':requested_by', $requested_by);
 if($stmt->execute()){
    header("Location: reply_to_email_req.php");
      exit();
 }

    
   
}
