<?php
  ob_start();
   session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  include 'include/conn.php';	
	
  if(!isset($_SESSION['AdminId']))
  {
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


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  

    
    
  $id = trim($_POST["id"]);
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
  $status = trim($_POST["status"]);
  $update_stmt = $conn->prepare("UPDATE `orgunit-systemsetting` SET
     
    org_embargo_duration=:org_embargo_duration,
    embargo_duration_type=:embargo_duration_type,
    embargo_implementation_type=:embargo_implementation_type,
    unsubscription_type=:unsubscription_type,
    max_records_type=:max_records_type,
    max_records=:max_records,
    ipblack_allowed_type=:ipblack_allowed_type,
    ipblack_max_allowed_score=:ipblack_max_allowed_score , 
    ipblack_allowed_color=:ipblack_allowed_color , 
    domain_block_type=:domain_block_type,
    url_block_type=:url_block_type,
    data_loading_type=:data_loading_type,
    api_type= :api_type,
    api_key=:api_key , 
    url=:url ,  
    status=:status 
    WHERE org_settingid= $id");
    $update_stmt->bindValue(':org_embargo_duration', $org_embargo_duration);
    $update_stmt->bindValue(':embargo_duration_type', $embargo_duration_type);
    $update_stmt->bindValue(':embargo_implementation_type', $embargo_implementation_type);
    $update_stmt->bindValue(':unsubscription_type', $unsubscription_type);
    $update_stmt->bindValue(':max_records_type', $max_records_type);
    $update_stmt->bindValue(':max_records', $max_records);
    $update_stmt->bindValue(':ipblack_allowed_type', $ipblack_allowed_type);
    $update_stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);
    $update_stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);
    $update_stmt->bindValue(':domain_block_type', $domain_block_type);
    $update_stmt->bindValue(':url_block_type', $url_block_type);
    $update_stmt->bindValue(':data_loading_type', $data_loading_type);
    $update_stmt->bindValue(':api_type', $api_type);
    $update_stmt->bindValue(':api_key', $api_key);
    $update_stmt->bindValue(':url', $url);
    $update_stmt->bindValue(':status', $status);
   
    if($update_stmt->execute())
    {




    header("Location: org_settings.php");
      exit();


    }


    
         


                


        



}
