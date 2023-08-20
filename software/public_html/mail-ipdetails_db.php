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
if (isset($_SESSION['ANM'])) {
    if ($_SESSION['ANM'] == "NO") {
  
        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
  }

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (trim($_POST["vmname"] != NULL) || trim($_POST["vmname"] != "")) {
        $vmname = trim($_POST["vmname"]);
        //$status = trim($_POST["status"]);
        $ethernet_name = trim($_POST["ethernet_name"]);
        $mac_address = trim($_POST["mac_address"]);

          //check if mailserver exist in both the tables
    $stmtm = $conn->prepare("SELECT vmname,mac_address FROM mailservers WHERE vmname=:vmname OR mac_address=:mac_address");
    $stmtm->bindValue(':vmname', $vmname);
    $stmtm->bindValue(':mac_address', $mac_address);
    $stmtm->execute();
    if ($stmtm->rowCount() > 0) {
//         echo "<div class='alert alert-danger' role='alert'>
//    <b>Record Already Exist</b>.
//    </div>
//    <meta http-equiv='refresh' content='2; url=mail-ipdetails_form.php'>
//  ";
       // die();
// my code
       $MS_alert= "true";

       header("Location:mail-ipdetails_form.php?MS_alert={$MS_alert}");
       exit();
//my code
     
    } else {

        $added_by= $_SESSION['AdminId'];
      

        $sql = "INSERT INTO mailservers (vmname, ethernet_name, mac_address,added_by) 
VALUES (:vmname, :ethernet_name, :mac_address, :added_by)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':vmname', $vmname);
       // $stmt->bindValue(':vmstatus', $status);
        $stmt->bindValue(':ethernet_name', $ethernet_name);
        $stmt->bindValue(':mac_address', $mac_address);
        $stmt->bindValue(':added_by', $added_by);

        if ($stmt->execute()) {

            header("Location: mail-ipdetails_form.php");
            exit();
        }

        $stmt->execute();
    }
    $mailserver =  $conn->lastInsertId();


     }




    if (trim($_POST["ipaddress"] != NULL) || trim($_POST["ipaddress"] != "")) {
$flag=0;
        $ipaddress = trim($_POST["ipaddress"]);
        $ipsubnet = trim($_POST["ipsubnet"]);
        $ipgateway = trim($_POST["ipgateway"]);
        $hostname = trim($_POST["hostname"]);
        $service_provider = trim($_POST["service_provider"]);
        $emailaddress = trim($_POST["emailaddress"]);
        $mailserver = trim($_POST["mailserver"]);
      //  $iphour = trim($_POST["iphour"]);

        //dont allow permenant block URL 
$stmt_url=$conn->prepare("SELECT url FROM permanently_blocked_url WHERE status='Active'");
$stmt_url->execute();
$urls=$stmt_url->fetchAll(); 

foreach ($urls as $url) {
    
  if (stripos($ipaddress,$url['url'])!==false || stripos($emailaddress,$url['url'])!==false ) {
      //url found 
      $flag=1;
      break;
  }
}

if($flag==1){
  $blck= "true";
  header("Location:  mail-ipdetails_form.php?blck={$blck}");
  exit();
  }


            //check if mailserver exist in both the tables
    $stmtm = $conn->prepare("SELECT ipaddress,hostname,emailaddress FROM ipdetails WHERE
    ipaddress=:ipaddress OR hostname=:hostname OR emailaddress=:emailaddress");
    $stmtm->bindValue(':ipaddress', $ipaddress);
    $stmtm->bindValue(':hostname', $hostname);
    $stmtm->bindValue(':emailaddress', $emailaddress);
    $stmtm->execute();
    if ($stmtm->rowCount() > 0) {
$R_alert= "true";
header("Location: mail-ipdetails_form.php?R_alert={$R_alert}");
exit();
    } else {
        $added_by= $_SESSION['AdminId'];

        $sql = "INSERT INTO ipdetails (ipaddress, ipsubnet,ipgateway, hostname,service_provider,emailaddress,  mailserverid, added_by) 
VALUES (:ipaddress, :ipsubnet, :ipgateway, :hostname, :service_provider, :emailaddress, :mailserverid, :added_by)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ipaddress', $ipaddress);
        $stmt->bindValue(':ipsubnet', $ipsubnet);
        $stmt->bindValue(':ipgateway', $ipgateway);
        $stmt->bindValue(':hostname', $hostname);
        $stmt->bindValue(':service_provider', $service_provider);
        $stmt->bindValue(':emailaddress', $emailaddress);
        $stmt->bindValue(':mailserverid', $mailserver);
        $stmt->bindValue(':added_by', $added_by);
       // $stmt->bindValue(':iphour', $iphour);
        if ($stmt->execute()) {

            header("Location: mail-ipdetails_form.php");
            exit();
        }
    }
}

}