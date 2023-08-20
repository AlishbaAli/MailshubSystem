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
if (isset($_SESSION['url_block_type'])) {
    if ($_SESSION['url_block_type'] =="sys-defined") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}
if (isset($_SESSION['BUO'])) {
    if ($_SESSION['BUO'] == "NO") {
  
        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
  }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = trim($_POST["url"]);
    $status = trim($_POST["status"]);
    $orgunit_id = trim($_POST["orgunit_id"]);

      //Checl for incorrect url (containing http OR https OR // OR /)

if (stripos($url,"http")!==false || stripos($url,"https")!==false  || stripos($url,"//")!==false  || stripos($url,"/")!==false) {
    $not_allwd="true";
    header("Location:block_url_formorg.php?not_allwd={$not_allwd}");
    exit();
  
  
  }

    //check if email exist in both the tables
    $stmt = $conn->prepare("SELECT blocked_url.url FROM blocked_url INNER JOIN blocked_url_org
     ON blocked_url.blocked_url_id= blocked_url_org.blocked_url_id AND blocked_url.url=:url
     AND blocked_url_org.orgunit_id=:orgunit_id");
    $stmt->bindValue(':url', $url);
    $stmt->bindValue(':orgunit_id', $orgunit_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $burlo= "true";

        header("Location:block_url_formorg.php?burlo={$burlo}");
        exit();
    }
        //check if email exist in block_url but not in current organization
      
        $stmt = $conn->prepare("SELECT
        url
        FROM
        blocked_url
   WHERE url=:url");
        $stmt->bindValue(':url', $url);
        $stmt->execute();
        $row = $stmt->fetch();


        $stmto = $conn->prepare("SELECT
        url
        FROM
        blocked_url_org
   WHERE url=:url AND orgunit_id=:orgunit_id");
        $stmto->bindValue(':url', $url);
        $stmto->bindValue(':orgunit_id', $orgunit_id);
        $stmto->execute();
        $rowo= $stmto->fetch();
    
        if ($stmt->rowCount() > 0 && $stmto->rowCount() < 1) {
            $stmt = $conn->prepare("SELECT
            blocked_url.blocked_url_id
            FROM
            blocked_url WHERE url=:url");
            $stmt->bindValue(':url', $url);
            $stmt->execute();
            $row = $stmt->fetch();
            $blocked_url_id = $row["blocked_url_id"];
    
            $ins_stmt = $conn->prepare("INSERT INTO  blocked_url_org (blocked_url_id, url)
            SELECT blocked_url_id, url FROM blocked_url WHERE blocked_url_id=:blocked_url_id");
            $ins_stmt->bindValue(':blocked_url_id', $blocked_url_id);
    
            if ($ins_stmt->execute()) {
                $blocked_url_orgid = $conn->lastInsertId();
                $stmtu = $conn->prepare("UPDATE blocked_url_org  SET orgunit_id=:orgunit_id WHERE
                blocked_url_orgid= :blocked_url_orgid");
                $stmtu->bindValue(':orgunit_id', $orgunit_id);
                $stmtu->bindValue(':blocked_url_orgid', $blocked_url_orgid);
                if ($stmtu->execute()) {
                    header("Location: block_urlorg.php");
                    exit();
                }
            }
        }

      

    //email is not present in both tables

    $stmt = $conn->prepare("SELECT blocked_url.url FROM blocked_url INNER JOIN blocked_url_org
     ON blocked_url.blocked_url_id= blocked_url_org.blocked_url_id AND blocked_url.url=:url");
    $stmt->bindValue(':url', $url);

    $stmt->execute();
    if ($stmt->rowCount() < 1) {
        if( $_SESSION['domain_url_type']=='sys-defined'||  $_SESSION['domain_url_type']=='ou-hybrid'){
            $sys_status='Active';

        }
        if( $_SESSION['domain_url_type']=='ou-dedicated' ){
            $sys_status='In Active';

        }
        $ins_stmt = $conn->prepare("INSERT INTO  blocked_url (url, status, AdminId)
        VALUES (:url, :status, :AdminId)");
        $ins_stmt->bindValue(':url', $url);
        $ins_stmt->bindValue(':status', $sys_status);
        $ins_stmt->bindValue(':AdminId', $_SESSION['AdminId']);
        if ($ins_stmt->execute()) {
            $blocked_url_id = $conn->lastInsertId();
            $ins_stmt = $conn->prepare("INSERT INTO  blocked_url_org (blocked_url_id, url,status, orgunit_id)
            VALUES(:blocked_url_id,:url, :status, :orgunit_id)");
            $ins_stmt->bindValue(':blocked_url_id', $blocked_url_id);
            $ins_stmt->bindValue(':url', $url);
            $ins_stmt->bindValue(':status', $status);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            if ($ins_stmt->execute()) {
                header("Location: block_urlorg.php");
                exit();
            }
        }
    }


}
