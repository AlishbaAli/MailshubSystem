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


if (isset($_GET["dnsbl_id"])) {

    $sql = "DELETE FROM dnsbl
    WHERE dnsbl_id = :dnsbl_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':dnsbl_id', $_GET["dnsbl_id"]);

    $result = $stmt->execute();

    if ($result > 0) {
        header("Location: dnsbl.php");
        exit();
    }
}
