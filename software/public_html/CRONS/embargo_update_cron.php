<?php
//code by Alishba Ali
$key = 123456;


$max = 1;
$permissions = 0666;
$autoRelease = 1;

$semaphore = sem_get($key, $max, $permissions, $autoRelease);

if(!$semaphore) {
    echo "Failed on sem_get().\n";
    exit;
}
sem_acquire($semaphore);




//**Connect to Application Server
$servername = "172.16.111.2";
$username = "mailshub_admin";
$password = "VFD1^*srYlH+"; 

try 
{
    $conn = new PDO("mysql:host=$servername; dbname=mailshub_db", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "Connected successfully"; 
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    exit;
}

$sql_ss = "SELECT update_embargo_cron_time, update_embargo_quantity FROM system_setting WHERE status = 'Active'";
$stmt_ss = $conn->prepare($sql_ss);
$stmt_ss->execute();
$system_settings = $stmt_ss->fetch(PDO::FETCH_ASSOC);
$update_embargo_cron_time= $system_settings['update_embargo_cron_time'];
$update_embargo_quantity= $system_settings['update_embargo_quantity'];

$stmt_update1= $conn->prepare("UPDATE cron_temp_email_embargo SET status='Pending' LIMIT $update_embargo_quantity ORDER BY temp_embargo_id ASC");
$stmt_update1->execute();

$stmt_select= $conn->prepare("SELECT * FROM cron_temp_email_embargo WHERE status='Pending'");
$stmt_select->execute();
$to_update1= $stmt_select->fetchAll();


//update embaro table
$conn->beginTransaction();
foreach($to_update1 as $to_upd)
{

$stmt_update2= $conn->prepare("UPDATE email_embargo SET 
use_date =:use_date,
CampID= :CampID,
mailserverid= :mailserverid,
ipaddress= :ipaddress,
hostname= :hostname,
emailaddress= :emailaddress
WHERE email=:email");
$stmt_update2->bindValue(':email', $to_upd['email']);
$stmt_update2->bindValue(':use_date', $to_upd['use_date']);
$stmt_update2->bindValue(':CampID', $to_upd['CampID']);
$stmt_update2->bindValue(':mailserverid', $to_upd["mailserverid"]);
$stmt_update2->bindValue(':ipaddress', $to_upd["ipaddress"]);
$stmt_update2->bindValue(':hostname', $to_upd["hostname"]);
$stmt_update2->bindValue(':emailaddress', $to_upd["emailaddress"]);
$stmt_update2->execute();

//set status to Updated
$stmt_update3= $conn->prepare("UPDATE cron_temp_email_embargo SET status ='Updated' WHERE status='Pending'");
$stmt_update3->bindValue(':email', $to_upd['email']);
$stmt_update3->execute();


//DELETE Updates from cron_temp_email_embargo
$stmt_dlt= $conn->prepare("DELETE * FROM cron_temp_email_embargo WHERE status='Updated'");
$stmt_dlt->execute();

}
$conn->commit();


$conn=null;
sem_release($semaphore);
?>