<?php
  ob_start();
   session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  include 'include/conn.php';	
	
  if(!isset($_SESSION['AdminId']))
  {
      //User not logged in. Redirect them back to the login page.
      header('Location: logout.php');
      exit;
  }
  if (isset($_SESSION['ADIPM'])) {
    if ($_SESSION['ADIPM'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $flag=0;

$ipaddress = trim($_POST["ipaddress"]);
$ipadd=explode(",",$ipaddress); 
$ip_hostname_id= $ipadd[0];
$ipaddress=$ipadd[1];

$data="SELECT * FROM ip_addresses inner join ip_hostname on ip_addresses.ip_addresses_id=ip_hostname.ip_addresses_id 
where iphost_status='Active' and ip_hostname_id='$ip_hostname_id' and ip_address='$ipaddress'";
$data=$conn->prepare($data);
$data->execute();
$data=$data->fetchall();

 $ipsubnet = trim($data["subnet_mask"]);
 $ipgateway = trim($data["gateway_address"]);
 //$hostname = trim($data["hostname"]);
//$service_provider = trim($data["service_provider"]);
$emailaddress = trim($data["emailaddress"]);
$iphost=explode("@",$emailaddress); 
$hostname = $iphost[1];

$mailserver = trim($_POST["mailserver"]);
//$iphour = trim($_POST["iphour"]);

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
          header("Location: addip-mailserver.php?blck={$blck}");
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
        header("Location:addip-mailserver.php?R_alert={$R_alert}");
        exit();
            } else {




              $added_by= $_SESSION['AdminId'];
  
$sql = "INSERT INTO ipdetails (ipaddress, ipsubnet,ipgateway, hostname,emailaddress,  mailserverid, added_by, ip_hostname_id) 
VALUES (:ipaddress, :ipsubnet, :ipgateway, :hostname, :emailaddress, :mailserverid, :added_by, :ip_hostname_id)";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':ipaddress', $ipaddress);
$stmt->bindValue(':ipsubnet', $ipsubnet);
$stmt->bindValue(':ipgateway', $ipgateway);
$stmt->bindValue(':hostname', $hostname);
// $stmt->bindValue(':service_provider', $service_provider);
$stmt->bindValue(':emailaddress', $emailaddress);
$stmt->bindValue(':mailserverid', $mailserver);
$stmt->bindValue(':added_by', $added_by);
$stmt->bindValue(':ip_hostname_id', $ip_hostname_id);
//$stmt->bindValue(':iphour', $iphour);
if( $stmt->execute()){

    header("Location: addip-mailserver.php");
    exit();
}
}  
        
}
