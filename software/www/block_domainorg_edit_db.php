<?php
ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blocked_domain_orgid = trim($_POST["blocked_domain_orgid"]);
    // $blocked_domain_id = trim($_POST["blocked_domain_id"]);
    // $domain_name = trim($_POST["domain_name"]);
    // $domain_owner = trim($_POST["domain_owner"]);
    // $domain_type = trim($_POST["domain_type"]);
    // $top_level_domain = trim($_POST["top_level_domain"]);
     $domain_status = trim($_POST["domain_status"]);
    // $orgunit_id = trim($_POST["orgunit_id"]);
    // $update_stmt = $conn->prepare("UPDATE  blocked_domains SET
    // domain_name=:domain_name,
    // domain_owner=:domain_owner,
    // domain_type=:domain_type,
    // top_level_domain=:top_level_domain
    // WHERE blocked_domain_id= $blocked_domain_id");
    // $update_stmt->bindValue(':domain_name', $domain_name);
    // $update_stmt->bindValue(':domain_owner', $domain_owner);
    // $update_stmt->bindValue(':domain_type', $domain_type);
    // $update_stmt->bindValue(':top_level_domain', $top_level_domain);

  //  if ($update_stmt->execute()) {

        $update_stmt = $conn->prepare("UPDATE  blocked_domain_org SET
        -- domain_name=:domain_name,
        domain_status=:domain_status

        WHERE blocked_domain_orgid= $blocked_domain_orgid");
       // $update_stmt->bindValue(':domain_name', $domain_name);
        $update_stmt->bindValue(':domain_status', $domain_status);
        if ($update_stmt->execute()) {

            header("Location: block_domainorg.php");
            exit();
        }
  //  }
}
