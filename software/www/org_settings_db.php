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

if (isset($_SESSION['r_level'])) {
  if ($_SESSION['r_level'] != "0") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {


  $orgunit_id = trim($_POST["orgunit_id"]);
  $org_embargo_duration = trim($_POST["org_embargo_duration"]);
  $embargo_duration_type = trim($_POST["embargo_duration_type"]);
  $embargo_implementation_type = trim($_POST["embargo_implementation_type"]);
  $unsubscription_type = trim($_POST["unsubscription_type"]);
  $max_records_type = trim($_POST["max_records_type"]);
  $max_records = trim($_POST["max_records"]);
  $ipblack_allowed_type = trim($_POST["ipblack_allowed_type"]);
  $ipblack_max_allowed_score = trim($_POST["ipblack_max_allowed_score"]);
  $ipblack_allowed_color = trim($_POST["ipblack_allowed_color"]);
  $domain_block_type = trim($_POST["domain_block_type"]);
  $url_block_type = trim($_POST["url_block_type"]);
  $data_loading_type = trim($_POST["data_loading_type"]);
  $api_type= trim($_POST["api_type"]);
  $api_key = trim($_POST["api_key"]);
  $url = trim($_POST["url"]);

  //check for already existng same settings for the same organization

  $sql = "SELECT * 
  FROM  `orgunit-systemsetting`
  WHERE 
  orgunit_id= :orgunit_id AND
  org_embargo_duration = :org_embargo_duration AND
  embargo_duration_type= :embargo_duration_type AND
  embargo_implementation_type= :embargo_implementation_type AND
  unsubscription_type= :unsubscription_type AND
  max_records_type= :max_records_type AND
  max_records= :max_records AND
  ipblack_allowed_type= :ipblack_allowed_type AND
  ipblack_max_allowed_score= :ipblack_max_allowed_score AND 
  ipblack_allowed_color= :ipblack_allowed_color AND
  domain_block_type= :domain_block_type AND
  url_block_type= :url_block_type AND
  data_loading_type= :data_loading_type AND
  api_type=:api_type AND
  api_key= :api_key AND
   url = :url";
 
 $stmt = $conn->prepare($sql);
 $stmt->bindValue(':orgunit_id', $orgunit_id);    
 $stmt->bindValue(':org_embargo_duration', $org_embargo_duration);     
 $stmt->bindValue(':embargo_duration_type', $embargo_duration_type);  
 $stmt->bindValue(':embargo_implementation_type', $embargo_implementation_type);  
 $stmt->bindValue(':unsubscription_type', $unsubscription_type);  
 $stmt->bindValue(':max_records_type', $max_records_type);  
 $stmt->bindValue(':max_records', $max_records);  
 $stmt->bindValue(':ipblack_allowed_type', $ipblack_allowed_type); 
 $stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);  
 $stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);  
 $stmt->bindValue(':domain_block_type', $domain_block_type);  
 $stmt->bindValue(':url_block_type', $url_block_type);  
 $stmt->bindValue(':data_loading_type', $data_loading_type);      
 $stmt->bindValue(':api_type', $api_type);  
 $stmt->bindValue(':api_key', $api_key);     
 $stmt->bindValue(':url', $url);    
       
 $stmt->execute();
 $stmt->fetch(PDO::FETCH_ASSOC);
 
 
 if( $stmt->rowCount() > 0)
 {
   echo "<div class='alert alert-danger' role='alert'>
           <b>Settings Already Exist</b>.
         </div>
         <meta http-equiv='refresh' content='2; url=org_settings.php'>
         ";                                    
   die();
 
 }
 else{

  //check for already existng different settings for the same organization
  $stmt = $conn->prepare("SELECT orgunit_id FROM `orgunit-systemsetting` WHERE  orgunit_id=:orgunit_id ");
  $stmt->bindValue(':orgunit_id', $orgunit_id);
  $stmt->execute();
  if($stmt->rowCount()>0){


    //Archive previous previous settings before adding new


  $stmt = $conn->prepare("INSERT INTO `orgunit-systemsetting_archive` 
  (
    org_settingid, 
  orgunit_id,
  embargo_duration_type,
  org_embargo_duration,
  embargo_implementation_type,
  unsubscription_type, 
  max_records_type, 
  max_records, 
  ipblack_allowed_type,
  ipblack_max_allowed_score,
  ipblack_allowed_color,
  domain_block_type, 
  url_block_type,
  data_loading_type,
  api_type,
  api_key,
  url,
  use_date )

  SELECT 
  org_settingid, 
  orgunit_id,
  embargo_duration_type,
  org_embargo_duration,
  embargo_implementation_type,
  unsubscription_type, 
  max_records_type, 
  max_records, 
  ipblack_allowed_type,
  ipblack_max_allowed_score,
  ipblack_allowed_color,
  domain_block_type, 
  url_block_type,
  data_loading_type,
  api_type,
  api_key,
  url,
  use_date
  FROM `orgunit-systemsetting`
  WHERE status = 'Active'");
  if($stmt->execute()){
$sql=  $conn->prepare("DELETE FROM `orgunit-systemsetting` WHERE orgunit_id = :orgunit_id");
$sql->bindValue(':orgunit_id', $orgunit_id);
$sql->execute();

  }
  }
  





  $ins_stmt = $conn->prepare("INSERT INTO `orgunit-systemsetting`
  (orgunit_id,
  embargo_duration_type,
  org_embargo_duration,
  embargo_implementation_type,
  unsubscription_type, 
  max_records_type, 
  max_records, 
  ipblack_allowed_type,
  ipblack_max_allowed_score,
  ipblack_allowed_color,
  domain_block_type, 
  url_block_type,
  data_loading_type,
  api_type,
  api_key,
  url) 
    
  VALUES(
  :orgunit_id,
  :embargo_duration_type,
  :org_embargo_duration,
  :embargo_implementation_type,
  :unsubscription_type, 
  :max_records_type, 
  :max_records, 
  :ipblack_allowed_type,
  :ipblack_max_allowed_score,
  :ipblack_allowed_color,
  :domain_block_type, 
  :url_block_type,
  :url_block_type,
  :data_loading_type,
  :api_type,
  :api_key,
  :url)");
  $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
  $ins_stmt->bindValue(':org_embargo_duration', $org_embargo_duration);
  $ins_stmt->bindValue(':embargo_duration_type', $embargo_duration_type);
  $ins_stmt->bindValue(':embargo_implementation_type', $embargo_implementation_type);
  $ins_stmt->bindValue(':unsubscription_type', $unsubscription_type);
  $ins_stmt->bindValue(':max_records_type', $max_records_type);
  $ins_stmt->bindValue(':max_records', $max_records);
  $ins_stmt->bindValue(':ipblack_allowed_type', $ipblack_allowed_type);
  $ins_stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);
  $ins_stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);
  $ins_stmt->bindValue(':domain_block_type', $domain_block_type);
  $ins_stmt->bindValue(':url_block_type', $url_block_type);
  $ins_stmt->bindValue(':data_loading_type', $data_loading_type);
  $ins_stmt->bindValue(':api_type', $api_type);
  $ins_stmt->bindValue(':api_key', $api_key);
  $ins_stmt->bindValue(':url', $url);

  if ($ins_stmt->execute()) {




    header("Location: org_settings.php");
    exit();
  }
}
}
