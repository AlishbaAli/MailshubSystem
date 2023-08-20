<?php
ob_start();
session_start();
//   error_reporting(E_ALL);
//   ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}

if (isset($_GET["UnsubscribeID"])) {

    $sql = "DELETE FROM unsubscriber
    WHERE UnsubscribeID = :UnsubscribeID";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':UnsubscribeID', $_GET["UnsubscribeID"]);

    $result = $stmt->execute();

    if ($result > 0) {
        header("Location: unsubscriberList.php");
        exit();
    }
}
