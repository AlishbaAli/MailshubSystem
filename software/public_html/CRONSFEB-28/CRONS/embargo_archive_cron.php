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


$curr_date = date('Y-m-d');
$skip_date="2017-06-05";
$last6month_date = date('Y-m-d', strtotime("-180 days"));

// from email_embargo
$sql1 = "SELECT * 
FROM email_embargo
WHERE use_date <= '$last6month_date' AND use_date <> '$skip_date'";
$stmt1 = $conn->prepare($sql1);
$res= $stmt1->execute();
$res = $stmt1->fetchAll();


$conn->beginTransaction();

foreach ($res as $res_row)
{
  //check if record already exists is email_embargo table
  $stmt_chk1 = $conn->prepare("SELECT email FROM email_embargo_archive WHERE email=:email");
  $stmt_chk1->bindValue(':email', $res_row["email"]);
  if ($stmt_chk1->execute()) 
  {
    $count_email1 = $stmt_chk1->rowCount();
  }

  if ($count_email1 < 1)
  {
    $insert = "INSERT INTO 	 email_embargo_archive(email_embargoid,email,use_date,CampID, mailserverid, ipaddress, hostname, emailaddress)
    VALUES (:email_embargoid,:email, :use_date,:CampID, :mailserverid, :ipaddress, :hostname, :emailaddress)";
    $stmt = $conn->prepare($insert);
    $stmt->bindValue(':email_embargoid', $res_row["email_embargoid"]);
    $stmt->bindValue(':email', $res_row["email"]);
    $stmt->bindValue(':use_date', $res_row["use_date"]);
    $stmt->bindValue(':CampID', $res_row["CampID"]);
    $stmt->bindValue(':mailserverid', $res_row["mailserverid"]);
    $stmt->bindValue(':ipaddress', $res_row["ipaddress"]);
    $stmt->bindValue(':hostname', $res_row["hostname"]);
    $stmt->bindValue(':emailaddress', $res_row["emailaddress"]);
    $stmt->execute();
  }
  else
  {

	  $sql = "UPDATE email_embargo_archive
	  SET 
    email_embargoid =:email_embargoid,
	  use_date =:use_date,
	  CampID= :CampID,
	  mailserverid= :mailserverid,
	  ipaddress= :ipaddress,
	  hostname= :hostname,
	  emailaddress= :emailaddress
	  WHERE email = :email";
	  $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email_embargoid', $res_row["email_embargoid"]);
	  $stmt->bindValue(':email', $res_row["email"]);
	  $stmt->bindValue(':use_date', $res_row["use_date"]);
	  $stmt->bindValue(':CampID', $res_row['CampID']);
	  $stmt->bindValue(':mailserverid', $res_row["mailserverid"]);
	  $stmt->bindValue(':ipaddress', $res_row["ipaddress"]);
	  $stmt->bindValue(':hostname', $res_row["hostname"]);
	  $stmt->bindValue(':emailaddress', $res_row["emailaddress"]);
	  $stmt->execute();

  } 

  $stmt_dlt= $conn->prepare("DELETE FROM email_embargo WHERE email=:email");
  $stmt_dlt->bindValue(':email', $res_row["email"]);
  $stmt_dlt->execute();

}
$conn->commit();


///////////////////////////////////////////////////////////

// from email_embargo organization
$sql2 = "SELECT * 
FROM email_embargorg
WHERE use_date <= '$last6month_date'";
$stmt2 = $conn->prepare($sql2);
$res2= $stmt2->execute();
$res2 = $stmt2->fetchAll();
$conn->beginTransaction();

foreach ($res2 as $res_row2) 
{

  $stmt_chk2 = $conn->prepare("SELECT email FROM email_embargorg_archive WHERE email=:email");
	$stmt_chk2->bindValue(':email', $res_row2["email"]);
	if($stmt_chk2->execute()) 
  {
		$count_emailorg = $stmt_chk2->rowCount();
	}
  if($count_emailorg < 1) 
  {
    	//Insert in email_embargorg_archive
		$insert = "INSERT INTO 	email_embargorg_archive(email_embargorg_id,email_embargoid,email,use_date, CampI
    VALUES (:email_embargorg_id, :email_embargoid, :email,:use_date, :CampID)";
    $stmt = $conn->prepare($insert);
    $stmt->bindValue(':email_embargorg_id',$res_row2["email_embargorg_id"]);
    $stmt->bindValue(':email_embargoid',$res_row2["email_embargoid"]);
    $stmt->bindValue(':email', $res_row2["email"]);
    $stmt->bindValue(':use_date',$res_row2["use_date"]);
    $stmt->bindValue(':CampID', $res_row2["CampID"]);
    $stmt->execute();

  }
  else
  {
    $sql = "UPDATE email_embargorg_archive
    SET
    email_embargorg_id =:email_embargorg_id,
    email_embargoid =:email_embargoid, 
    use_date =:use_date,
    CampID= :CampID,
    WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email_embargorg_id',$res_row2["email_embargorg_id"] );
    $stmt->bindValue(':email_embargoid',$res_row2["email_embargoid"] );
    $stmt->bindValue(':email', $res_row2["email"]);
    $stmt->bindValue(':use_date', $res_row2["use_date"]);
    $stmt->bindValue(':CampID', $res_row2['CampID']);
    $stmt->execute();

  }

$stmt_dlt2= $conn->prepare("DELETE FROM email_embargorg WHERE email=:email");
$stmt_dlt2->bindValue(':email', $res_row2s["email"]);
$stmt_dlt2->execute();

}

$conn->commit();  

$conn=null;
sem_release($semaphore);
?>