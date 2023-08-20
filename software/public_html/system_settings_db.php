<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: logout.php');
  exit;
}
 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $mail_execution_category = trim($_POST["mail_execution_category"]);
  $customizable_org_embargo = trim($_POST["customizable_org_embargo"]);

  //POSTED VALUES
  $embargo_duration = trim($_POST["embargo_duration"]);
  if (isset($_POST["embargo_archive_days"])) {
    $embargo_archive_days = trim($_POST["embargo_archive_days"]);
  } else {
    $embargo_archive_days = trim($_POST["embargo_duration"]) + 30;
  }


  $customizable_camp_embargo=trim($_POST['customizable_camp_embargo']);
  $mail_sending_execution=trim($_POST['mail_sending_execution']);

  $max_camp_per_server_percentage = trim($_POST["max_camp_per_server_percentage"]);
  $mailserver_min_active_ips = trim($_POST["mailserver_min_active_ips"]);
  $max_records = trim($_POST["max_records"]);
  $data_loading_type = trim($_POST["data_loading_type"]);
  
  $ipblack_max_allowed_score = trim($_POST["ipblack_max_allowed_score"]);
  $ipblack_allowed_color = trim($_POST["ipblack_allowed_color"]);
  
  $ip_switching_execution = trim($_POST['ip_switching_execution']);
  $ip_switch_criteria = trim($_POST["ip_switch_criteria"]);
  if ($ip_switch_criteria == "Standard-Switch") {
    $ip_switch_criteria_code = "SSW";
  } else if ($ip_switch_criteria == "Random-Switch") {
    $ip_switch_criteria_code = "RSW";
  }
  //$ip_switch_criteria_code = trim($_POST["ip_switch_criteria_code"]);
  $ip_switch_min_interval = trim($_POST["ip_switch_min_interval"]);
  $ip_switch_max_interval = trim($_POST["ip_switch_max_interval"]);
  $ip_switch_standard_interval = trim($_POST["ip_switch_standard_interval"]);

  $ip_selection_criteria = trim($_POST["ip_selection_criteria"]);
  if ($ip_selection_criteria == "Sequential-Selection") {
    $ip_selection_criteria_code = "SSL";
  } else if ($ip_selection_criteria == "Random-Selection") {
    $ip_selection_criteria_code = "RSL";
  }
  //$ip_selection_criteria_code = trim($_POST["ip_selection_criteria_code"]);
  $random_ip_selection_offset = trim($_POST['random_ip_selection_offset']);

  $ms_email_prefix = trim($_POST["ms_email_prefix"]);
  $instance_email_send = trim($_POST["instance_email_send"]);
  $email_send_criteria  = trim($_POST["email_send_criteria"]);
  if ($email_send_criteria == "Standard-Send-Time") {
      $email_send_criteria_code = "SST";
    } else if ($email_send_criteria == "Random-Send-Time") {
      $email_send_criteria_code = "RST";
    }
  //$email_send_criteria_code = trim($_POST["email_send_criteria_code"]);
  $email_quantity_criteria = trim($_POST["email_quantity_criteria"]);
  if ($email_quantity_criteria == "Standard-Quantity") {
      $email_quantity_criteria_code = "SQ";
    } else if ($email_quantity_criteria == "Random-Quantity") {
      $email_quantity_criteria_code = "RQ";
    }
//$email_quantity_criteria_code = trim($_POST["email_quantity_criteria_code"]);
$email_qty_lower_random_limit = trim($_POST["email_qty_lower_random_limit"]);
$email_qty_upper_random_limit  = trim($_POST["email_qty_upper_random_limit"]);

$random_min_send_interval = trim($_POST["random_min_send_interval"]);
$random_max_send_interval = trim($_POST["random_max_send_interval"]);
$standard_send_interval = trim($_POST["standard_send_interval"]);

$domain_wise_email_send_filter = trim($_POST["domain_wise_email_send_filter"]);
$domain_wise_email_send_offset = trim($_POST["domain_wise_email_send_offset"]);


  $api_key = trim($_POST["api_key"]);
  $url = trim($_POST["url"]);

  $status = trim($_POST["status"]);

  //Check for Already exisitng same row 
  $sql = "SELECT * 
  FROM system_setting 
  WHERE 
      mail_execution_category = :mail_execution_category AND
      customizable_org_embargo = :customizable_org_embargo AND

     embargo_duration=:embargo_duration AND
     embargo_archive_days=:embargo_archive_days AND

     customizable_camp_embargo=:customizable_camp_embargo AND
     mail_sending_execution=:mail_sending_execution AND

     max_camp_per_server_percentage = :max_camp_per_server_percentage AND
     mailserver_min_active_ips = :mailserver_min_active_ips AND
     max_records = :max_records AND
     data_loading_type =:data_loading_type AND

     ipblack_max_allowed_score=:ipblack_max_allowed_score AND  
     ipblack_allowed_color=:ipblack_allowed_color AND 

     ip_switching_execution = :ip_switching_execution AND
     ip_switch_criteria =:ip_switch_criteria AND
     ip_switch_criteria_code = :ip_switch_criteria_code AND 
     ip_switch_min_interval = :ip_switch_min_interval AND 
     ip_switch_max_interval = :ip_switch_max_interval AND
     ip_switch_standard_interval = :ip_switch_standard_interval AND

     ip_selection_criteria = :ip_selection_criteria AND
     ip_selection_criteria_code = :ip_selection_criteria_code AND
     random_ip_selection_offset = :random_ip_selection_offset AND

     ms_email_prefix=:ms_email_prefix AND
     instance_email_send=:instance_email_send AND 
     email_send_criteria =:email_send_criteria AND
     email_send_criteria_code =:email_send_criteria_code AND

     email_quantity_criteria =:email_quantity_criteria AND
     email_quantity_criteria_code =:email_quantity_criteria_code AND
     email_qty_lower_random_limit =:email_qty_lower_random_limit AND
     email_qty_upper_random_limit =:email_qty_upper_random_limit AND

     random_min_send_interval = :random_min_send_interval AND
     random_max_send_interval = :random_max_send_interval AND
     standard_send_interval = :standard_send_interval AND

     domain_wise_email_send_filter = :domain_wise_email_send_filter AND
     domain_wise_email_send_offset = :domain_wise_email_send_offset AND

     api_key=:api_key AND 
     url=:url AND  
     status=:status
  
  ";

$stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':mail_execution_category', $mail_execution_category);
    $stmt->bindValue(':customizable_org_embargo', $customizable_org_embargo);

    $stmt->bindValue(':embargo_duration', $embargo_duration);
    $stmt->bindValue(':embargo_archive_days', $embargo_archive_days);

    $stmt->bindValue(':customizable_camp_embargo', $customizable_camp_embargo);
    $stmt->bindValue(':mail_sending_execution', $mail_sending_execution);

    $stmt->bindValue(':max_camp_per_server_percentage', $max_camp_per_server_percentage);
    $stmt->bindValue(':mailserver_min_active_ips', $mailserver_min_active_ips);
    $stmt->bindValue(':max_records', $max_records);
    $stmt->bindValue(':data_loading_type', $data_loading_type);

    $stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);
    $stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);

    $stmt->bindValue(':ip_switching_execution', $ip_switching_execution);
    $stmt->bindValue(':ip_switch_criteria', $ip_switch_criteria);
    $stmt->bindValue(':ip_switch_criteria_code', $ip_switch_criteria_code);
    $stmt->bindValue(':ip_switch_min_interval', $ip_switch_min_interval);
    $stmt->bindValue(':ip_switch_max_interval', $ip_switch_max_interval);
    $stmt->bindValue(':ip_switch_standard_interval', $ip_switch_standard_interval);

    $stmt->bindValue(':ip_selection_criteria', $ip_selection_criteria);
    $stmt->bindValue(':ip_selection_criteria_code', $ip_selection_criteria_code);
    $stmt->bindValue(':random_ip_selection_offset', $random_ip_selection_offset);

    $stmt->bindValue(':ms_email_prefix', $ms_email_prefix);
    $stmt->bindValue(':instance_email_send', $instance_email_send);
    $stmt->bindValue(':email_send_criteria', $email_send_criteria);
    $stmt->bindValue(':email_send_criteria_code', $email_send_criteria_code);
    $stmt->bindValue(':email_quantity_criteria', $email_quantity_criteria);
    $stmt->bindValue(':email_quantity_criteria_code', $email_quantity_criteria_code);
    $stmt->bindValue(':email_qty_lower_random_limit', $email_qty_lower_random_limit);
    $stmt->bindValue(':email_qty_upper_random_limit', $email_qty_upper_random_limit);

    $stmt->bindValue(':random_min_send_interval', $random_min_send_interval);
    $stmt->bindValue(':random_max_send_interval', $random_max_send_interval);
    $stmt->bindValue(':standard_send_interval', $standard_send_interval);

    $stmt->bindValue(':domain_wise_email_send_filter', $domain_wise_email_send_filter);
    $stmt->bindValue(':domain_wise_email_send_offset', $domain_wise_email_send_offset);
   
    $stmt->bindValue(':api_key', $api_key);
    $stmt->bindValue(':url', $url);
    $stmt->bindValue(':status', $status);

//-------
         
$stmt->execute();
$stmt->fetch(PDO::FETCH_ASSOC);


if( $stmt->rowCount() > 0)
{
  echo "<div class='alert alert-danger' role='alert'>
          <b>Settings Already Exist</b>.
        </div>
        <meta http-equiv='refresh' content='2; url=system_settings.php'>
        ";                                    
  die();

}
else{


  //Archive previous previous settings before adding new


  $stmt = $conn->prepare("INSERT INTO system_setting_archive 
  (system_settingid, 
  embargo_duration,
     embargo_archive_days,

     mail_execution_category,
    customizable_org_embargo,  

     customizable_camp_embargo,
     mail_sending_execution,

     max_camp_per_server_percentage ,
     mailserver_min_active_ips ,
     max_records ,
     data_loading_type,

     ipblack_max_allowed_score ,
     ipblack_allowed_color,

     ip_switching_execution ,
     ip_switch_criteria ,
     ip_switch_criteria_code ,
     ip_switch_min_interval,
     ip_switch_max_interval ,
     ip_switch_standard_interval, 

     ip_selection_criteria, 
     ip_selection_criteria_code ,
     random_ip_selection_offset ,

     ms_email_prefix,
     instance_email_send,
     email_send_criteria ,
     email_send_criteria_code, 
     email_quantity_criteria ,
     email_quantity_criteria_code, 
     email_qty_lower_random_limit, 
     email_qty_upper_random_limit ,
     random_min_send_interval,
     random_max_send_interval ,
     standard_send_interval,

     domain_wise_email_send_filter ,
     domain_wise_email_send_offset ,

     api_key, 
     url,
     status,
  use_date )
  SELECT 
  system_settingid,
  embargo_duration,
     embargo_archive_days,

     mail_execution_category,
    customizable_org_embargo,  

     customizable_camp_embargo,
     mail_sending_execution,

     max_camp_per_server_percentage ,
     mailserver_min_active_ips ,
     max_records ,
     data_loading_type,

     ipblack_max_allowed_score ,
     ipblack_allowed_color,

     ip_switching_execution ,
     ip_switch_criteria ,
     ip_switch_criteria_code ,
     ip_switch_min_interval,
     ip_switch_max_interval ,
     ip_switch_standard_interval, 

     ip_selection_criteria, 
     ip_selection_criteria_code ,
     random_ip_selection_offset ,
     ms_email_prefix,
     instance_email_send,
     email_send_criteria ,
     email_send_criteria_code, 
     email_quantity_criteria ,
     email_quantity_criteria_code,
     email_qty_lower_random_limit, 
     email_qty_upper_random_limit ,
     random_min_send_interval,
     random_max_send_interval ,
     standard_send_interval,

     domain_wise_email_send_filter ,
     domain_wise_email_send_offset ,

     api_key, 
     url,
     status,
  use_date
  FROM system_setting
  WHERE status = 'Active'");
  if($stmt->execute()){
$sql=  $conn->prepare("DELETE FROM system_setting WHERE status = 'Active'");
$sql->execute();

  }


 

  $ins_stmt = $conn->prepare("INSERT INTO   system_setting (
     embargo_duration,
     embargo_archive_days,

     mail_execution_category,
    customizable_org_embargo,  

     customizable_camp_embargo,
     mail_sending_execution,

     max_camp_per_server_percentage ,
     mailserver_min_active_ips ,
     max_records ,
     data_loading_type,

     ipblack_max_allowed_score ,
     ipblack_allowed_color,

     ip_switching_execution ,
     ip_switch_criteria ,
     ip_switch_criteria_code ,
     ip_switch_min_interval,
     ip_switch_max_interval ,
     ip_switch_standard_interval, 

     ip_selection_criteria, 
     ip_selection_criteria_code ,
     random_ip_selection_offset ,

     ms_email_prefix,
     instance_email_send,
     email_send_criteria ,
     email_send_criteria_code, 

     email_quantity_criteria ,
     email_quantity_criteria_code,
     email_qty_lower_random_limit, 
     email_qty_upper_random_limit ,
     random_min_send_interval,
     random_max_send_interval ,
     standard_send_interval,

     domain_wise_email_send_filter ,
     domain_wise_email_send_offset ,

     api_key, 
     url,
     status) 

    VALUES (
      :embargo_duration,
     :embargo_archive_days,

     :mail_execution_category,
    :customizable_org_embargo,  

     :customizable_camp_embargo,
     :mail_sending_execution,

     :max_camp_per_server_percentage ,
     :mailserver_min_active_ips ,
     :max_records ,
     :data_loading_type,

     :ipblack_max_allowed_score ,
     :ipblack_allowed_color,

     :ip_switching_execution ,
     :ip_switch_criteria ,
     :ip_switch_criteria_code ,
     :ip_switch_min_interval,
     :ip_switch_max_interval ,
     :ip_switch_standard_interval, 

     :ip_selection_criteria, 
     :ip_selection_criteria_code ,
     :random_ip_selection_offset ,

     :ms_email_prefix,
     :instance_email_send,
     :email_send_criteria ,
     :email_send_criteria_code, 

     :email_quantity_criteria ,
     :email_quantity_criteria_code,
     :email_qty_lower_random_limit, 
     :email_qty_upper_random_limit ,
     :random_min_send_interval,
     :random_max_send_interval ,
     :standard_send_interval,

     :domain_wise_email_send_filter ,
     :domain_wise_email_send_offset ,

     :api_key, 
     :url,
     :status)");



$ins_stmt->bindValue(':embargo_duration', $embargo_duration);
$ins_stmt->bindValue(':embargo_archive_days', $embargo_archive_days);

$ins_stmt->bindValue(':mail_execution_category', $mail_execution_category);
$ins_stmt->bindValue(':customizable_org_embargo', $customizable_org_embargo);

$ins_stmt->bindValue(':customizable_camp_embargo', $customizable_camp_embargo);
$ins_stmt->bindValue(':mail_sending_execution', $mail_sending_execution);

$ins_stmt->bindValue(':max_camp_per_server_percentage', $max_camp_per_server_percentage);
$ins_stmt->bindValue(':mailserver_min_active_ips', $mailserver_min_active_ips);
$ins_stmt->bindValue(':max_records', $max_records);
$ins_stmt->bindValue(':data_loading_type', $data_loading_type);

$ins_stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);
$ins_stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);

$ins_stmt->bindValue(':ip_switching_execution', $ip_switching_execution);
$ins_stmt->bindValue(':ip_switch_criteria', $ip_switch_criteria);
$ins_stmt->bindValue(':ip_switch_criteria_code', $ip_switch_criteria_code);
$ins_stmt->bindValue(':ip_switch_min_interval', $ip_switch_min_interval);
$ins_stmt->bindValue(':ip_switch_max_interval', $ip_switch_max_interval);
$ins_stmt->bindValue(':ip_switch_standard_interval', $ip_switch_standard_interval);

$ins_stmt->bindValue(':ip_selection_criteria', $ip_selection_criteria);
$ins_stmt->bindValue(':ip_selection_criteria_code', $ip_selection_criteria_code);
$ins_stmt->bindValue(':random_ip_selection_offset', $random_ip_selection_offset);

$ins_stmt->bindValue(':ms_email_prefix', $ms_email_prefix);
$ins_stmt->bindValue(':instance_email_send', $instance_email_send);
$ins_stmt->bindValue(':email_send_criteria', $email_send_criteria);
$ins_stmt->bindValue(':email_send_criteria_code', $email_send_criteria_code);

$ins_stmt->bindValue(':email_quantity_criteria', $email_quantity_criteria);
$ins_stmt->bindValue(':email_quantity_criteria_code', $email_quantity_criteria_code);

$ins_stmt->bindValue(':email_qty_lower_random_limit', $email_qty_lower_random_limit);
$ins_stmt->bindValue(':email_qty_upper_random_limit', $email_qty_upper_random_limit);

$ins_stmt->bindValue(':random_min_send_interval', $random_min_send_interval);
$ins_stmt->bindValue(':random_max_send_interval', $random_max_send_interval);
$ins_stmt->bindValue(':standard_send_interval', $standard_send_interval);

$ins_stmt->bindValue(':domain_wise_email_send_filter', $domain_wise_email_send_filter);
$ins_stmt->bindValue(':domain_wise_email_send_offset', $domain_wise_email_send_offset);

$ins_stmt->bindValue(':api_key', $api_key);
$ins_stmt->bindValue(':url', $url);
$ins_stmt->bindValue(':status', $status);

  if ($ins_stmt->execute()) {

    header("Location: system_settings.php");
    exit();
  }
}
}
