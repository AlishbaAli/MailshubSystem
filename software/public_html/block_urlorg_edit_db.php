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
if (isset($_SESSION['BUOE'])) {
    if ($_SESSION['BUOE'] == "NO") {
  
        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
  }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blocked_url_orgid = trim($_POST["blocked_url_orgid"]);
  
     $status = trim($_POST["status"]);  



        $update_stmt = $conn->prepare("UPDATE  blocked_url_org SET
    
        status=:status

        WHERE blocked_url_orgid= $blocked_url_orgid");
 
        $update_stmt->bindValue(':status', $status);
        if ($update_stmt->execute()) {

            header("Location: block_urlorg.php");
            exit();
        }

}
