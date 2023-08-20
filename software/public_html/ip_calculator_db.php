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

if (isset($_SESSION['IPC'])) {
  if ($_SESSION['IPBR'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}
 if ($_SERVER["REQUEST_METHOD"] == "POST") {


  $jsonarray = $_POST['pool_array'];
  $pool = json_decode($jsonarray, true);
  



 

  //insert to ip_pool
  $stmt= $conn->prepare("INSERT INTO ip_pool(ip_pool,gateway_bit, network_address, mask_bits, host_bits, subnet_mask, broadcast_address, gateway_address,
  total_hosts, usable_hosts, network_ip2long,broadcast_ip2long, sp_id) VALUES(:ip_pool,:gateway_bit, :network_address, :mask_bits, :host_bits, :subnet_mask, :broadcast_address, :gateway_address,
  :total_hosts, :usable_hosts, :network_ip2long, :broadcast_ip2long, :sp_id)");
  $stmt->bindValue(':ip_pool',  $pool[0]['ip_pool']);
  $stmt->bindValue(':gateway_bit',  $pool[0]['gateway_bit']);
  $stmt->bindValue(':network_address',  $pool[0]['network_address']);
  $stmt->bindValue(':mask_bits',  $pool[0]['mask_bits']);
  $stmt->bindValue(':host_bits',  $pool[0]['host_bits']);
  $stmt->bindValue(':subnet_mask',  $pool[0]['subnet_mask']);
  $stmt->bindValue(':broadcast_address',  $pool[0]['broadcast_address']);
  $stmt->bindValue(':gateway_address',  $pool[0]['gateway_address']);
  $stmt->bindValue(':total_hosts',  $pool[0]['total_hosts']);
  $stmt->bindValue(':usable_hosts',  $pool[0]['usable_hosts']);
  $stmt->bindValue(':network_ip2long',  $pool[0]['network_ip2long']);
  $stmt->bindValue(':broadcast_ip2long',  $pool[0]['broadcast_ip2long']);
  $stmt->bindValue(':sp_id',  $pool[0]['sp_id']);
  $stmt->execute();

  $ip_pool_id=$conn->lastInsertId();


  $inc_ip= $_POST['inc_ip'];
  $inc_hostname= $_POST['inc_hostname'];



  $limit = sizeof($inc_ip);


  for($i=0;$i<$limit;$i++){


 if(!in_array($inc_ip[$i], $inc_hostname)){
 //hostname and email address null deliberately 
  
  $comment="Hostname deliberately excluded";
  $stmt1= $conn->prepare("INSERT INTO ip_addresses(ip_address, subnet_mask, gateway_address, ip_pool_id,comment) 
  VALUES(:ip_address, :subnet_mask, :gateway_address, :ip_pool_id, :comment)");
  $stmt1->bindValue(':ip_address', $pool[$inc_ip[$i]]['ip_address']);
  $stmt1->bindValue(':subnet_mask',$pool[$inc_ip[$i]]['subnet_mask'] );
  $stmt1->bindValue(':gateway_address',$pool[$inc_ip[$i]]['gateway_address'] );
  $stmt1->bindValue(':ip_pool_id', $ip_pool_id );
  $stmt1->bindValue(':comment', $comment );
  $stmt1->execute();




 }
 else if($pool[$inc_ip[$i]]['hostname']==NULL){
  $comment="Hostname Not Verified";
  $stmt1= $conn->prepare("INSERT INTO ip_addresses(ip_address, subnet_mask, gateway_address, ip_pool_id,comment) 
  VALUES(:ip_address, :subnet_mask, :gateway_address, :ip_pool_id, :comment)");
  $stmt1->bindValue(':ip_address', $pool[$inc_ip[$i]]['ip_address']);
  $stmt1->bindValue(':subnet_mask',$pool[$inc_ip[$i]]['subnet_mask'] );
  $stmt1->bindValue(':gateway_address',$pool[$inc_ip[$i]]['gateway_address'] );
  $stmt1->bindValue(':ip_pool_id', $ip_pool_id );
  $stmt1->bindValue(':comment', $comment );
  $stmt1->execute();


 }
 else{

//hostname and emailaddress not null
  $stmt1= $conn->prepare("INSERT INTO ip_addresses(ip_address, subnet_mask, gateway_address, ip_pool_id ) 
  VALUES(:ip_address, :subnet_mask, :gateway_address, :ip_pool_id)");
  $stmt1->bindValue(':ip_address', $pool[$inc_ip[$i]]['ip_address']);
  $stmt1->bindValue(':subnet_mask',$pool[$inc_ip[$i]]['subnet_mask'] );
  $stmt1->bindValue(':gateway_address',$pool[$inc_ip[$i]]['gateway_address'] );
  $stmt1->bindValue(':ip_pool_id', $ip_pool_id );
  $stmt1->execute();

  $ip_addresses_id= $conn->lastInsertId();

  //check if hostname exists
  //then add n get id otherwise update and get id of hostname info
  $stmt2 = $conn->prepare("SELECT hostname_id FROM hostname_info WHERE hostname=:hostname");
  $stmt2->bindValue(':hostname', $pool[$inc_ip[$i]]['hostname']);
  $stmt2->execute();
  $hostname_id=$stmt2->fetch();
  if($stmt2->rowCount()>0){

    $hostname_id= $hostname_id['hostname_id']; 
 

  }
  else{
    //insert new and get id
$stmt3 = $conn->prepare("INSERT INTO hostname_info(hostname) VALUES(:hostname)");
$stmt3->bindValue(':hostname', $pool[$inc_ip[$i]]['hostname']);
$stmt3->execute();
$hostname_id = $conn->lastInsertId();
  }

  // move to ip-hostname table
  //if the ip is the current ip then skip below steps

  $stmt4= $conn->prepare("SELECT ipaddress FROM current_mailserver_config WHERE ipaddress=:ipaddress");
  $stmt4->bindValue(':ipaddress', $pool[$inc_ip[$i]]['ip_address']);
  $stmt4->execute();
  if($stmt4->rowCount()>0){
    //update ip address status
    $comment="IP currently in use";
    $stmtu= $conn->prepare("UPDATE ip_addresses SET comment=:comment WHERE ip_address=:ip_address AND ip_addresses_status='Active'");
    $stmtu->bindValue(':comment', $comment);
    $stmtu->bindValue(':ip_address', $pool[$inc_ip[$i]]['ip_address']);
    $stmtu->execute();
    continue;

  }
  else{

    $stmt5= $conn->prepare("SELECT * FROM  ip_hostname WHERE hostname_id=:hostname_id AND iphost_status= 'Active'");
    $stmt5->bindValue(':hostname_id', $hostname_id);
    $stmt5->execute();
    $result5=$stmt5->fetch();
    $ip_hostname_id= $result5["ip_hostname_id"];
    



    //non unique + Active
    if($stmt5->rowCount()>0){
      //old into unverified and then insert new
$stmt6= $conn->prepare("UPDATE ip_hostname SET iphost_status='Hostname Unverified' WHERE  ip_hostname_id= '$ip_hostname_id'");
$stmt6->execute();
    }
  
    //insert
$stmt7= $conn->prepare("INSERT INTO ip_hostname(ip_addresses_id,hostname_id, emailaddress) VALUES(:ip_addresses_id, :hostname_id, :emailaddress)");
$stmt7->bindValue(':ip_addresses_id', $ip_addresses_id);
$stmt7->bindValue(':hostname_id', $hostname_id);
$stmt7->bindValue(':emailaddress', $pool[$inc_ip[$i]]['emailaddress']);
$stmt7->execute();



  }




 }








  }

 





  header("Location: ip_calculator.php");
  exit();




  



}
?>