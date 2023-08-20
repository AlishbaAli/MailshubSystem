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

  //POSTED VALUES
  $embargo_duration = trim($_POST["embargo_duration"]);
  $instance_email_send = trim($_POST["instance_email_send"]);
  $ip_switch_criteria = trim($_POST["ip_switch_criteria"]); 
  $max_records = trim($_POST["max_records"]);
  $ipblack_max_allowed_score = trim($_POST["ipblack_max_allowed_score"]);
  $ipblack_allowed_color = trim($_POST["ipblack_allowed_color"]);
  $api_key = trim($_POST["api_key"]);
  $url = trim($_POST["url"]);
  if ($ip_switch_criteria == "Sequential") {
    $ip_switch_criteria_code = "SS";
  } else if ($ip_switch_criteria == "Hours") {
    $ip_switch_criteria_code = "HS";
  }
  $data_loading_type= trim($_POST["data_loading_type"]);
//---------
	//mailserver_min_active_ips
	//max_camp_per_server_percentage
	//embargo_archive_days
	//random_switch_offset
	//ms_email_prefix
//---------------------
$mailserver_min_active_ips  = trim($_POST["mailserver_min_active_ips"]);
$max_camp_per_server_percentage  = trim($_POST["max_camp_per_server_percentage"]);
$embargo_archive_days  = trim($_POST["embargo_archive_days"]);
$random_switch_offset  = trim($_POST["random_switch_offset"]);
$ms_email_prefix  = trim($_POST["ms_email_prefix"]);

//----------
    $email_send_criteria  = trim($_POST["email_send_criteria"]);
    if ($email_send_criteria == "Standard-Send-Time") {
        $email_send_criteria_code = "SST";
      } else if ($email_send_criteria == "Random-Send-Time") {
        $email_send_criteria_code = "RST";
      }
	$email_send_criteria_code = trim($_POST["email_send_criteria_code"]);
	$email_quantity_criteria = trim($_POST["email_quantity_criteria"]);
    if ($email_quantity_criteria == "Standard-Qunatity") {
        $email_quantity_criteria_code = "SQ";
      } else if ($email_quantity_criteria == "Random-Quantity") {
        $email_quantity_criteria_code = "RQ";
      }
	$email_quantity_criteria_code = trim($_POST["email_quantity_criteria_code"]);
	$random_min_send_interval = trim($_POST["random_min_send_interval"]);
	$random_max_send_interval = trim($_POST["random_max_send_interval"]);
	$standard_send_interval = trim($_POST["standard_send_interval"]);
	$email_qty_lower_random_limit = trim($_POST["email_qty_lower_random_limit"]);
	$email_qty_upper_random_limit  = trim($_POST["email_qty_upper_random_limit"]);
	$ip_switch_min_interval = trim($_POST["ip_switch_min_interval"]);
	$ip_switch_max_interval = trim($_POST["ip_switch_max_interval"]);
	$ip_switch_standard_interval = trim($_POST["ip_switch_standard_interval"]);

  //Check for Already exisitng same row 
  $sql = "SELECT * 
  FROM system_setting 
  WHERE 
  embargo_duration = :embargo_duration AND
  instance_email_send= :instance_email_send AND
  ip_switch_criteria= :ip_switch_criteria AND
  max_records= :max_records AND
  ipblack_max_allowed_score= :ipblack_max_allowed_score AND 
  ipblack_allowed_color= :ipblack_allowed_color AND
  api_key= :api_key AND
  url = :url AND
  email_send_criteria= :email_send_criteria And
	email_send_criteria_code= :email_send_criteria_code And
	email_quantity_criteria= :email_quantity_criteria  And
	email_quantity_criteria_code= :email_quantity_criteria_code And
	random_min_send_interval= :random_min_send_interval And
	random_max_send_interval= :random_max_send_interval And
	standard_send_interval= :standard_send_interval And
	email_qty_lower_random_limit= :email_qty_lower_random_limit And
	email_qty_upper_random_limit = :email_qty_upper_random_limit And
	ip_switch_min_interval= :ip_switch_min_interval And
	ip_switch_max_interval= :ip_switch_max_interval And
	ip_switch_standard_interval= :ip_switch_standard_interval And
  data_loading_type =:data_loading_type";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':embargo_duration', $embargo_duration);     
$stmt->bindValue(':instance_email_send', $instance_email_send);  
$stmt->bindValue(':ip_switch_criteria', $ip_switch_criteria);  
$stmt->bindValue(':max_records', $max_records);  
$stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);  
$stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);  
$stmt->bindValue(':api_key', $api_key);     
$stmt->bindValue(':url', $url);    
//--------
$stmt->bindValue(':email_send_criteria', $email_send_criteria); 
$stmt->bindValue(':email_send_criteria_code', $email_send_criteria_code); 
$stmt->bindValue(':email_quantity_criteria', $email_quantity_criteria); 
$stmt->bindValue(':email_quantity_criteria_code', $email_quantity_criteria_code); 
$stmt->bindValue(':random_min_send_interval', $random_min_send_interval); 
$stmt->bindValue(':random_max_send_interval', $random_max_send_interval);
$stmt->bindValue(':standard_send_interval', $standard_send_interval); 
$stmt->bindValue(':email_qty_lower_random_limit', $email_qty_lower_random_limit); 
$stmt->bindValue(':email_qty_upper_random_limit', $email_qty_upper_random_limit); 
$stmt->bindValue(':ip_switch_min_interval', $ip_switch_min_interval); 
$stmt->bindValue(':ip_switch_max_interval', $ip_switch_max_interval); 
$stmt->bindValue(':ip_switch_standard_interval', $ip_switch_standard_interval); 

//-------
$stmt->bindValue(':data_loading_type', $data_loading_type);            
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
  instance_email_send, 
  ip_switch_criteria, 
  ip_switch_criteria_code, 
  data_loading_type,
  max_records,
  ipblack_max_allowed_score,
  ipblack_allowed_color,
  api_key, 
  url,
    email_send_criteria
	email_send_criteria_code
	email_quantity_criteria
	email_quantity_criteria_code
	random_min_send_interval
	random_max_send_interval
	standard_send_interval
	email_qty_lower_random_limit
	email_qty_upper_random_limit 
	ip_switch_min_interval
	ip_switch_max_interval
	ip_switch_standard_interval
  use_date )
  SELECT 
  system_settingid,
  embargo_duration,
  instance_email_send, 
  ip_switch_criteria, 
  ip_switch_criteria_code,
  data_loading_type,
   max_records, 
   ipblack_max_allowed_score,
  ipblack_allowed_color, 
  api_key,
  url, 
    email_send_criteria
	email_send_criteria_code
	email_quantity_criteria
	email_quantity_criteria_code
	random_min_send_interval
	random_max_send_interval
	standard_send_interval
	email_qty_lower_random_limit
	email_qty_upper_random_limit 
	ip_switch_min_interval
	ip_switch_max_interval
	ip_switch_standard_interval
  use_date
  FROM system_setting
  WHERE status = 'Active'");
  if($stmt->execute()){
$sql=  $conn->prepare("DELETE FROM system_setting WHERE status = 'Active'");
$sql->execute();

  }


 

  $ins_stmt = $conn->prepare("INSERT INTO   system_setting (embargo_duration, instance_email_send,ip_switch_criteria, ip_switch_criteria_code,data_loading_type, max_records,
    ipblack_max_allowed_score, ipblack_allowed_color, api_key, url,
    email_send_criteria,
	email_send_criteria_code,
	email_quantity_criteria,
	email_quantity_criteria_code,
	random_min_send_interval,
	random_max_send_interval,
	standard_send_interval,
	email_qty_lower_random_limit,
	email_qty_upper_random_limit ,
	ip_switch_min_interval,
	ip_switch_max_interval,
	ip_switch_standard_interval) 
    VALUES (:embargo_duration, :instance_email_send, :ip_switch_criteria, :ip_switch_criteria_code, :data_loading_type, :max_records, :ipblack_max_allowed_score, :ipblack_allowed_color, :api_key,
     :url, :email_send_criteria,
	:email_send_criteria_code,
	:email_quantity_criteria,
	:email_quantity_criteria_code,
	:random_min_send_interval,
	:random_max_send_interval,
	:standard_send_interval,
	:email_qty_lower_random_limit,
	:email_qty_upper_random_limit ,
	:ip_switch_min_interval,
	:ip_switch_max_interval,
	:ip_switch_standard_interval)");
  $ins_stmt->bindValue(':embargo_duration', $embargo_duration);
  $ins_stmt->bindValue(':instance_email_send', $hourly_email_send);
  $ins_stmt->bindValue(':ip_switch_criteria', $ip_switch_criteria);
  $ins_stmt->bindValue(':ip_switch_criteria_code', $ip_switch_criteria_code);
  $ins_stmt->bindValue(':data_loading_type', $data_loading_type);
  $ins_stmt->bindValue(':max_records', $max_records);
  $ins_stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);
  $ins_stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);
  $ins_stmt->bindValue(':api_key', $api_key);
  $ins_stmt->bindValue(':url', $url);

  $stmt->bindValue(':email_send_criteria', $email_send_criteria); 
$stmt->bindValue(':email_send_criteria_code', $email_send_criteria_code); 
$stmt->bindValue(':email_quantity_criteria', $email_quantity_criteria); 
$stmt->bindValue(':email_quantity_criteria_code', $email_quantity_criteria_code); 
$stmt->bindValue(':random_min_send_interval', $random_min_send_interval); 
$stmt->bindValue(':random_max_send_interval', $random_max_send_interval);
$stmt->bindValue(':standard_send_interval', $standard_send_interval); 
$stmt->bindValue(':email_qty_lower_random_limit', $email_qty_lower_random_limit); 
$stmt->bindValue(':email_qty_upper_random_limit', $email_qty_upper_random_limit); 
$stmt->bindValue(':ip_switch_min_interval', $ip_switch_min_interval); 
$stmt->bindValue(':ip_switch_max_interval', $ip_switch_max_interval); 
$stmt->bindValue(':ip_switch_standard_interval', $ip_switch_standard_interval); 

  if ($ins_stmt->execute()) {




    header("Location: system_settings.php");
    exit();
  }
}
}
