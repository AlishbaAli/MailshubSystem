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



$priority_color= $_POST['priority_color'];

//$priority_color="black";


    $sql = "SELECT priority_score FROM dnsbl WHERE priority_color= :priority_color";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":priority_color", $priority_color);







    if ($stmt->execute()) {


        $row = $stmt->fetch();

        $data['priority_score'] = $row['priority_score'];




        echo json_encode($data);
    }




?>