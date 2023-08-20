<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit();
}

if (isset($_SESSION['domain_block_type']))  {
    if ($_SESSION['domain_block_type'] == "sys-defined")  {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
    }
    if (isset($_SESSION['BDO'])) {
        if ($_SESSION['BDO'] == "NO") {
    
            //User not logged in. Redirect them back to the login page.
            header('Location: page-403.html');
            exit;
        }
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domain_name = trim($_POST["domain_name"]);
    $domain_owner = trim($_POST["domain_owner"]);
    $domain_type = trim($_POST["domain_type"]);
    $top_level_domain = trim($_POST["top_level_domain"]);
    $domain_status = trim($_POST["domain_status"]);
    $orgunit_id = trim($_POST["orgunit_id"]);
  //Checl for incorrect url (containing http OR https OR // OR /)

  if (stripos($domain_name,"http")!==false || stripos($domain_name,"https")!==false  || stripos($domain_name,"//")!==false  || stripos($domain_name,"/")!==false) {
    $not_allwd="true";
    header("Location:block_domain_formorg.php?not_allwd={$not_allwd}");
    exit();
  
  
  }
    //check if email exist in both the tables
    $stmt = $conn->prepare("SELECT blocked_domains.domain_name FROM blocked_domains INNER JOIN blocked_domain_org
     ON blocked_domains.blocked_domain_id= blocked_domain_org.blocked_domain_id AND blocked_domains.domain_name=:domain_name
     AND blocked_domain_org.orgunit_id=:orgunit_id");
    $stmt->bindValue(':domain_name', $domain_name);
    $stmt->bindValue(':orgunit_id', $orgunit_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $bdomo= "true";

        header("Location:block_domain_formorg.php?bdomo={$bdomo}");
        exit();
    }
     //check if email exist in block_domains but not in current organization

     $stmt = $conn->prepare("SELECT
     domain_name
     FROM
     blocked_domains
     WHERE domain_name =:domain_name");
     $stmt->bindValue(':domain_name', $domain_name);
   
     $stmt->execute();
     $row = $stmt->fetch();

     $stmto = $conn->prepare("SELECT
     domain_name
   
     FROM
     blocked_domain_org WHERE
      domain_name =:domain_name AND orgunit_id=:orgunit_id
     ");
     $stmto->bindValue(':domain_name', $domain_name);
     $stmto->bindValue(':orgunit_id', $orgunit_id);
     $stmto->execute();
     $rowo = $stmto->fetch();
 
     if ($stmt->rowCount() > 0 && $stmto->rowCount() < 1) {
         $stmt = $conn->prepare("SELECT
         blocked_domains.blocked_domain_id
         FROM
         blocked_domains WHERE domain_name=:domain_name");
         $stmt->bindValue(':domain_name', $domain_name);
         $stmt->execute();
         $row = $stmt->fetch();
         $blocked_domain_id = $row["blocked_domain_id"];
 
         $ins_stmt = $conn->prepare("INSERT INTO  blocked_domain_org (blocked_domain_id, domain_name)
         SELECT blocked_domain_id, domain_name FROM blocked_domains WHERE blocked_domain_id=:blocked_domain_id");
         $ins_stmt->bindValue(':blocked_domain_id', $blocked_domain_id);
 
         if ($ins_stmt->execute()) {
             $blocked_domain_orgid = $conn->lastInsertId();
             $stmtu = $conn->prepare("UPDATE blocked_domain_org  SET orgunit_id=:orgunit_id WHERE
             blocked_domain_orgid= :blocked_domain_orgid");
             $stmtu->bindValue(':orgunit_id', $orgunit_id);
             $stmtu->bindValue(':blocked_domain_orgid', $blocked_domain_orgid);
             if ($stmtu->execute()) {
                 header("Location: block_domainorg.php");
                 exit();
             }
         }
     }

    //email is not present in both tables

    $stmt = $conn->prepare("SELECT blocked_domains.domain_name FROM blocked_domains INNER JOIN blocked_domain_org
     ON blocked_domains.blocked_domain_id= blocked_domain_org.blocked_domain_id AND blocked_domains.domain_name=:domain_name");
    $stmt->bindValue(':domain_name', $domain_name);

    $stmt->execute();
    if ($stmt->rowCount() < 1) {

        //get block_domain type
        if( $_SESSION['domain_block_type']=='sys-defined'||  $_SESSION['domain_block_type']=='ou-hybrid'){
            $sys_domain_status='Active';

        }
        if( $_SESSION['domain_block_type']=='ou-dedicated' ){
            $sys_domain_status='In Active';

        }
        $ins_stmt = $conn->prepare("INSERT INTO  blocked_domains (domain_name, domain_owner,domain_type, top_level_domain, AdminId, domain_status)
        VALUES (:domain_name, :domain_owner, :domain_type, :top_level_domain, :AdminId, :domain_status)");
        $ins_stmt->bindValue(':domain_name', $domain_name);
        $ins_stmt->bindValue(':domain_owner', $domain_owner);
        $ins_stmt->bindValue(':domain_type', $domain_type);
        $ins_stmt->bindValue(':top_level_domain', $top_level_domain);
        $ins_stmt->bindValue(':AdminId', $_SESSION['AdminId']);
        $ins_stmt->bindValue(':domain_status', $sys_domain_status);
        if ($ins_stmt->execute()) {
            $blocked_domain_id = $conn->lastInsertId();
            $ins_stmt = $conn->prepare("INSERT INTO  blocked_domain_org (blocked_domain_id, domain_name, orgunit_id, domain_status)
            VALUES(:blocked_domain_id,:domain_name,:orgunit_id, :domain_status)");
            $ins_stmt->bindValue(':blocked_domain_id', $blocked_domain_id);
            $ins_stmt->bindValue(':domain_name', $domain_name);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->bindValue(':domain_status', $domain_status);
            if ($ins_stmt->execute()) {
                header("Location: block_domainorg.php");
                exit();
            }
        }
    }

   
}
