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
  if (isset($_SESSION['ESS'])) {
    if ($_SESSION['ESS'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
} 
  // if (isset($_SESSION['r_level'])) {
  //   if ($_SESSION['r_level'] != "0") {
   
  //       //User not logged in. Redirect them back to the login page.
  //       header('Location: page-403.html');
  //       exit;
  //   }
  // }

 
   //IP Pool Ajax
 
  
  


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  
  
    $id = trim($_POST["id"]);

    $mail_execution_category = trim($_POST["mail_execution_category"]); 
    $customizable_org_embargo = trim($_POST["customizable_org_embargo"]);

    $embargo_duration = trim($_POST["embargo_duration"]);
    if (isset($_POST["embargo_archive_days"])) {
      $embargo_archive_days = trim($_POST["embargo_archive_days"]);
    } else {
      $embargo_archive_days = trim($_POST["embargo_duration"]) + 30;
    }

    $customizable_camp_embargo=trim($_POST['customizable_camp_embargo']);
    $mail_sending_execution=trim($_POST['mail_sending_execution']);


    if (isset($_POST["max_camp_per_server_percentage"]) && $_POST["max_camp_per_server_percentage"] > 0) {
      $max_camp_per_server_percentage = trim($_POST["max_camp_per_server_percentage"]);
    } else {
      $stmt = $conn->prepare("SELECT * FROM system_setting "); 
      $stmt->execute();
      $sys = $stmt->fetch();
      $max_camp_per_server_percentage = trim($sys["max_camp_per_server_percentage"]);
    }
  
  
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

    // $embargo_archive_days = trim($_POST["embargo_duration"]);
    // $max_camp_per_server_percentage = trim($_POST["max_camp_per_server_percentage"]);

    $status = trim($_POST["status"]);
  
    $update_stmt = $conn->prepare("UPDATE system_setting SET

    mail_execution_category = :mail_execution_category,
    customizable_org_embargo = :customizable_org_embargo,

     embargo_duration=:embargo_duration,
     embargo_archive_days=:embargo_archive_days,

     customizable_camp_embargo=:customizable_camp_embargo,
     mail_sending_execution=:mail_sending_execution,

     max_camp_per_server_percentage = :max_camp_per_server_percentage,
     mailserver_min_active_ips = :mailserver_min_active_ips,
     max_records = :max_records,
     data_loading_type =:data_loading_type,

     ipblack_max_allowed_score=:ipblack_max_allowed_score,  
     ipblack_allowed_color=:ipblack_allowed_color, 

     ip_switching_execution = :ip_switching_execution,
     ip_switch_criteria =:ip_switch_criteria,
     ip_switch_criteria_code = :ip_switch_criteria_code, 
     ip_switch_min_interval = :ip_switch_min_interval, 
     ip_switch_max_interval = :ip_switch_max_interval,
     ip_switch_standard_interval = :ip_switch_standard_interval,

     ip_selection_criteria = :ip_selection_criteria,
     ip_selection_criteria_code = :ip_selection_criteria_code,
     random_ip_selection_offset = :random_ip_selection_offset,

     ms_email_prefix=:ms_email_prefix,
     instance_email_send=:instance_email_send, 
     email_send_criteria =:email_send_criteria,
     email_send_criteria_code =:email_send_criteria_code,
     email_quantity_criteria =:email_quantity_criteria,
     email_quantity_criteria_code =:email_quantity_criteria_code,
     email_qty_lower_random_limit =:email_qty_lower_random_limit,
     email_qty_upper_random_limit =:email_qty_upper_random_limit,

     random_min_send_interval = :random_min_send_interval,
     random_max_send_interval = :random_max_send_interval,
     standard_send_interval = :standard_send_interval,

     domain_wise_email_send_filter = :domain_wise_email_send_filter AND
     domain_wise_email_send_offset = :domain_wise_email_send_offset AND

     api_key=:api_key, 
     url=:url,  
     status=:status 
     
    WHERE system_settingid= $id");

    $update_stmt->bindValue(':mail_execution_category', $mail_execution_category);
    $update_stmt->bindValue(':customizable_org_embargo', $customizable_org_embargo);

    $update_stmt->bindValue(':embargo_duration', $embargo_duration);
    $update_stmt->bindValue(':embargo_archive_days', $embargo_archive_days);

    $update_stmt->bindValue(':customizable_camp_embargo', $customizable_camp_embargo);
    $update_stmt->bindValue(':mail_sending_execution', $mail_sending_execution);

    $update_stmt->bindValue(':max_camp_per_server_percentage', $max_camp_per_server_percentage);
    $update_stmt->bindValue(':mailserver_min_active_ips', $mailserver_min_active_ips);
    $update_stmt->bindValue(':max_records', $max_records);
    $update_stmt->bindValue(':data_loading_type', $data_loading_type);

    $update_stmt->bindValue(':ipblack_max_allowed_score', $ipblack_max_allowed_score);
    $update_stmt->bindValue(':ipblack_allowed_color', $ipblack_allowed_color);

    $update_stmt->bindValue(':ip_switching_execution', $ip_switching_execution);
    $update_stmt->bindValue(':ip_switch_criteria', $ip_switch_criteria);
    $update_stmt->bindValue(':ip_switch_criteria_code', $ip_switch_criteria_code);
    $update_stmt->bindValue(':ip_switch_min_interval', $ip_switch_min_interval);
    $update_stmt->bindValue(':ip_switch_max_interval', $ip_switch_max_interval);
    $update_stmt->bindValue(':ip_switch_standard_interval', $ip_switch_standard_interval);

    $update_stmt->bindValue(':ip_selection_criteria', $ip_selection_criteria);
    $update_stmt->bindValue(':ip_selection_criteria_code', $ip_selection_criteria_code);
    $update_stmt->bindValue(':random_ip_selection_offset', $random_ip_selection_offset);

    $update_stmt->bindValue(':ms_email_prefix', $ms_email_prefix);
    $update_stmt->bindValue(':instance_email_send', $instance_email_send);
    $update_stmt->bindValue(':email_send_criteria', $email_send_criteria);
    $update_stmt->bindValue(':email_send_criteria_code', $email_send_criteria_code);
    $update_stmt->bindValue(':email_quantity_criteria', $email_quantity_criteria);
    $update_stmt->bindValue(':email_quantity_criteria_code', $email_quantity_criteria_code);
    $update_stmt->bindValue(':email_qty_lower_random_limit', $email_qty_lower_random_limit);
    $update_stmt->bindValue(':email_qty_upper_random_limit', $email_qty_upper_random_limit);

    $update_stmt->bindValue(':random_min_send_interval', $random_min_send_interval);
    $update_stmt->bindValue(':random_max_send_interval', $random_max_send_interval);
    $update_stmt->bindValue(':standard_send_interval', $standard_send_interval);

    $update_stmt->bindValue(':domain_wise_email_send_filter', $domain_wise_email_send_filter);
    $update_stmt->bindValue(':domain_wise_email_send_offset', $domain_wise_email_send_offset);
   
    $update_stmt->bindValue(':api_key', $api_key);
    $update_stmt->bindValue(':url', $url);
    $update_stmt->bindValue(':status', $status);
   
    if($update_stmt->execute())
    {
    header("Location: system_settings.php");
    exit();
    }


    
         


                


        



}
