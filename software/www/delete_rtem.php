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


if (isset($_GET["id"])) {

    $sql = "DELETE FROM reply_to_emails
    WHERE rtemid = :rtemid";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':rtemid', $_GET["id"]);

    $result = $stmt->execute();

    if ($result > 0) {
        header("Location: reply_to_email_form.php");
        exit();
    }
}
