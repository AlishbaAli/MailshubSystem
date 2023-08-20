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
  $id = $_POST['id'];
  $ipaddress = trim($_POST["ipaddress"]);
  $ipsubnet = trim($_POST["ipsubnet"]);
  $ipgateway = trim($_POST["ipgateway"]);
  $hostname = trim($_POST["hostname"]);
  $service_provider = trim($_POST["service_provider"]);
  $emailaddress = trim($_POST["emailaddress"]);
  $mailserverid = trim($_POST["mailserverid"]);
  //$iphour = trim($_POST["iphour"]);
  $ipstatus = trim($_POST["ipstatus"]);



        //dont allow permenant block URL 
        $stmt_url=$conn->prepare("SELECT url FROM permanently_blocked_url WHERE status='Active'");
        $stmt_url->execute();
        $urls=$stmt_url->fetchAll(); 
        
        foreach ($urls as $url) {
            
          if (stripos($emailaddress,$url['url'])!==false || stripos($hostname,$url['url'])!==false ) {
              //url found 
              $flag=1;
              break;
          }
        }
        
        if($flag==1){
          $blck= "true";
          header("Location: ipdetails_edit.php?id={$id}&blck={$blck}");
          exit();
          }


  $update_stmt = $conn->prepare("UPDATE ipdetails 
    SET ipaddress= :ipaddress, 
    ipsubnet=:ipsubnet, 
    ipgateway=:ipgateway, 
    hostname=:hostname ,
    service_provider=:service_provider,
    emailaddress=:emailaddress ,
    mailserverid=:mailserverid ,
    ipstatus=:ipstatus
    
    WHERE
    ipdetailid=:ipdetailid");
  $update_stmt->bindValue(':ipdetailid', $id);
  $update_stmt->bindValue(':ipaddress', $ipaddress);
  $update_stmt->bindValue(':ipsubnet', $ipsubnet);
  $update_stmt->bindValue(':ipgateway', $ipgateway);
  $update_stmt->bindValue(':hostname', $hostname);
  $update_stmt->bindValue(':service_provider', $service_provider);
  $update_stmt->bindValue(':emailaddress', $emailaddress);
  $update_stmt->bindValue(':mailserverid', $mailserverid);
//  $update_stmt->bindValue(':iphour', $iphour);
  $update_stmt->bindValue(':ipstatus', $ipstatus);
  if ($update_stmt->execute()) {

    header("Location: mail-ipdetails_form.php");
    exit();
  }
}
