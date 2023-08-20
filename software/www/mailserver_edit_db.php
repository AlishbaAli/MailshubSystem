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

  $id = $_POST['id'];
  $vmname = $_POST["vmname"];
  $vmstatus = $_POST["vmstatus"];
  $ethernet_name = $_POST["ethernet_name"];
  $mac_address = $_POST["mac_address"];


  $update_stmt = $conn->prepare("UPDATE mailservers SET vmname= :vmname, vmstatus=:vmstatus, ethernet_name=:ethernet_name, mac_address=:mac_address WHERE
    mailserverid=:mailserverid");
  $update_stmt->bindValue(':mailserverid', $id);
  $update_stmt->bindValue(':vmstatus', $vmstatus);
  $update_stmt->bindValue(':vmname', $vmname);
  $update_stmt->bindValue(':ethernet_name', $ethernet_name);
  $update_stmt->bindValue(':mac_address', $mac_address);
  if ($update_stmt->execute()) {

    header("Location: mail-ipdetails_form.php");
    exit();
  }
}
