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
    $flag=0;
    $orgunit_id = $_POST["id"];
    $reply_to_emails_input = $_POST['reply_to_emails'];




    $stmt = $conn->prepare("SELECT reply_to_emails.rtemid, reply_to_email FROM reply_to_emails 
INNER JOIN  tbl_orgunit_rte ON reply_to_emails.rtemid =  tbl_orgunit_rte.rtemid 
WHERE  tbl_orgunit_rte.orgunit_id=$orgunit_id ");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $reply_to_emails_values = []; //to store already assigned reply_to_emails
    foreach ($row as $output) {

        $reply_to_emails_values[] = $output['rtemid'];
    }

    //Insert Newly added reply_to_emails

    foreach ($reply_to_emails_input as $input_val) {
        if (!in_array($input_val, $reply_to_emails_values)) {

         $rtem= $conn->prepare("SELECT reply_to_email FROM reply_to_emails WHERE rtemid=:rtemid");
         $rtem->bindValue(':rtemid', $input_val);

         $rtem->execute();
         $rtemail= $rtem->fetch();
   //checking

     //dont allow block URL
     if($_SESSION['url_block_type']=="sys-defined"){
        $stmt_url=$conn->prepare("SELECT url FROM blocked_url WHERE status='Active'");
        $stmt_url->execute();
        $urls=$stmt_url->fetchAll(); 
    }
    if($_SESSION['url_block_type']=="ou-dedicated" || $_SESSION['url_block_type']=="ou-hybrid"){
        $stmt_url=$conn->prepare("SELECT url FROM blocked_url_org WHERE status='Active'");
        $stmt_url->execute();
        $urls=$stmt_url->fetchAll(); 
    
    
    
    }
    
    
    
    
    foreach ($urls as $url) {
        
        if (stripos($rtemail['reply_to_email'],$url['url'])!==false) {
            //url found 
            $flag=1;
            break;
        }
    }
    if($flag==1){
    $blck= "true";
    header("Location: rtemid_dept_edit.php?orgunit_id={$orgunit_id}&blck={$blck}");
    exit();
    }
    
     //dont allow block domain
     if($_SESSION['domain_block_type']=="sys-defined"){
      $stmt_url=$conn->prepare("SELECT domain_name FROM blocked_domains WHERE domain_status='Active'");
      $stmt_url->execute();
      $urls=$stmt_url->fetchAll(); 
    }
    if($_SESSION['domain_block_type']=="ou-dedicated" || $_SESSION['domain_block_type']=="ou-hybrid"){
      $stmt_url=$conn->prepare("SELECT domain_name FROM blocked_domain_org WHERE domain_status='Active'");
      $stmt_url->execute();
      $urls=$stmt_url->fetchAll(); 
    }
    
    foreach ($urls as $url) {
      
      if (stripos($rtemail['reply_to_email'],$url['domain_name'])!==false) {
          //url found 
          $flag=1;
          break;
      }
    }
    if($flag==1){
      $blck= "true";
      header("Location: rtemid_dept_edit.php?orgunit_id={$orgunit_id}&blck={$blck}");
      exit();
      }


            /////checking ends ///////////////




            $ins_stmt = $conn->prepare("INSERT INTO    tbl_orgunit_rte (rtemid, orgunit_id, assigned_by) 
        VALUES (:rtemid, :orgunit_id, :assigned_by)");
            $ins_stmt->bindValue(':rtemid', $input_val);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->bindValue(':assigned_by', $assigned_by);
            $ins_stmt->execute();
        }
    }
    //Delete assigned reply_to_emails

    foreach ($reply_to_emails_values as $reply_to_emails_values_row) {

        if (!in_array($reply_to_emails_values_row, $reply_to_emails_input)) {
            // echo $role_row."Delete this one";

            $sql = "DELETE FROM tbl_orgunit_rte WHERE rtemid= $reply_to_emails_values_row AND orgunit_id= $orgunit_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: reply_to_email_form.php");
    exit();
}
