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
if (isset($_SESSION['ET'])) {
  if ($_SESSION['ET'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $allowed_days = trim($_POST["allowed_days"]);
    $stmt = $conn->prepare("INSERT INTO embargotype(allowed_days) VALUES(:allowed_days)");
    $stmt->bindValue(':allowed_days', $allowed_days);

    if ($stmt->execute()) 
    {
      header("Location: embargotype.php");
      exit();
    }

}


?>