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

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

$stmt_update1= $conn->prepare("UPDATE cron_temp_email_embargorg SET status='Pending' LIMIT $update_embargo_quantity ORDER BY temp_embargorg_id ASC");
$stmt_update1->execute();

$stmt_select= $conn->prepare("SELECT * FROM cron_temp_email_embargorg WHERE status='Pending'");
$stmt_select->execute();
$to_updateorg=$stmt_select->fetchAll();

$conn->beginTransaction();
foreach($to_updateorg as $to_upd)
{

$stmt_update= $conn->prepare("UPDATE email_embargorg SET 
email_embargoid =:email_embargoid,
use_date =:use_date,
CampID= :CampID,
WHERE email = :email
AND orgunit_id= :orgunit_id");
$stmt_update->bindValue(':email_embargoid', $to_upd['email_embargoid']);
$stmt_update->bindValue(':email', $to_upd['email']);
$stmt_update->bindValue(':use_date', $to_upd['use_date']);
$stmt_update->bindValue(':CampID', $to_upd['CampID']);
$stmt_update->bindValue(':orgunit_id', $to_upd['orgunit_id']);
$stmt_update->execute();

//set status to Updated
$stmt_update2= $conn->prepare("UPDATE cron_temp_email_embargorg SET status ='Updated' WHERE status='Pending'");
$stmt_update2->bindValue(':email', $to_upd['email']);
$stmt_update2->execute();


//DELETE Updates from cron_temp_email_embargorg
$stmt_dlt= $conn->prepare("DELETE * FROM cron_temp_email_embargorg WHERE status='Updated'");
$stmt_dlt->execute();
}

$conn->commit();

$conn=null;
sem_release($semaphore);
?>