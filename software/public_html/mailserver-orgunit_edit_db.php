<?php
ob_start();
session_start();
include 'include/conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}


if (isset($_SESSION['AMO'])) {
    if ($_SESSION['AMO'] == "NO") {
  
        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
  }


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orgunit_id = $_POST["id"];
    $mailservers_input = $_POST['mailservers'];


    $stmt = $conn->prepare("SELECT mailservers.mailserverid, vmname FROM mailservers INNER JOIN `mailserver-orgunit` 
ON mailservers.mailserverid = `mailserver-orgunit`.mailserverid WHERE `mailserver-orgunit`.orgunit_id=$orgunit_id ");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $mailservers_values = []; //to store already assigned mailservers
    foreach ($row as $output) {

        $mailservers_values[] = $output['mailserverid'];
    }

    //Insert Newly added mailservers

    foreach ($mailservers_input as $input_val) {
        if (!in_array($input_val, $mailservers_values)) {


            $ins_stmt = $conn->prepare("INSERT INTO   `mailserver-orgunit`(mailserverid, orgunit_id) 
        VALUES (:mailserverid, :orgunit_id)");
            $ins_stmt->bindValue(':mailserverid', $input_val);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->execute();
        }
    }
    //Delete assigned mailservers

    foreach ($mailservers_values as $mailservers_values_row) {

        if (!in_array($mailservers_values_row, $mailservers_input)) {


            $sql = "DELETE FROM  `mailserver-orgunit` WHERE mailserverid= $mailservers_values_row AND orgunit_id= $orgunit_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: orgunit_form.php");
    exit();
}
