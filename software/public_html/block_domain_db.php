<?php
ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}

if (isset($_SESSION['domain_block_type']))  {
  if ($_SESSION['domain_block_type'] != "sys-defined")  {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
  }
	if (isset($_SESSION['BDS'])) {
		if ($_SESSION['BDS'] == "NO") {

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

    //Checl for incorrect url (containing http OR https OR // OR /)

if (stripos($domain_name,"http")!==false || stripos($domain_name,"https")!==false  || stripos($domain_name,"//")!==false  || stripos($domain_name,"/")!==false) {
  $not_allwd="true";
  header("Location:block_domain_form.php?not_allwd={$not_allwd}");
  exit();


}

    //check if email exist in both the tables
    $stmt = $conn->prepare("SELECT * FROM blocked_domains WHERE domain_name=:domain_name");
    $stmt->bindValue(':domain_name', $domain_name);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $bdom= "true";

      header("Location:block_domain_form.php?bdom={$bdom}");
      exit();
    }

  $ins_stmt = $conn->prepare("INSERT INTO  blocked_domains (domain_name, domain_owner,domain_type, top_level_domain,domain_status, AdminId) 
    VALUES (:domain_name, :domain_owner, :domain_type, :top_level_domain, :domain_status, :AdminId)");
  $ins_stmt->bindValue(':domain_name', $domain_name);
  $ins_stmt->bindValue(':domain_owner', $domain_owner);
  $ins_stmt->bindValue(':domain_type', $domain_type);
  $ins_stmt->bindValue(':top_level_domain', $top_level_domain);
  $ins_stmt->bindValue(':domain_status', $domain_status);
  $ins_stmt->bindValue(':AdminId', $_SESSION['AdminId']);
  if ($ins_stmt->execute()) {




    header("Location: block_domain.php");
    exit();
  }
}
