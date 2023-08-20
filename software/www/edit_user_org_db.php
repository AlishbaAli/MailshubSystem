<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["id"];
    $orgs_input = $_POST['orgs'];


    $stmt = $conn->prepare("SELECT  tbl_organizational_unit.orgunit_id, orgunit_name FROM 
tbl_organizational_unit INNER JOIN tbl_orgunit_user ON  tbl_organizational_unit.orgunit_id 
=  tbl_orgunit_user.orgunit_id WHERE tbl_orgunit_user.user_id = $user_id");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $org_values = []; //to store already assigned roles
    foreach ($row as $output) {

        $org_values[] = $output['orgunit_id'];
    }

    //Insert Newly added roles

    foreach ($orgs_input as $input_val) {
        if (!in_array($input_val, $org_values)) {

            // echo $input_val."insert here";
            $ins_stmt = $conn->prepare("INSERT INTO  tbl_orgunit_user (orgunit_id, user_id) 
        VALUES (:orgunit_id, :user_id)");
            $ins_stmt->bindValue(':orgunit_id', $input_val);
            $ins_stmt->bindValue(':user_id', $user_id);
            $ins_stmt->execute();
        }
    }
    //Delete assigned roles

    foreach ($org_values as $org_row) {
        if (!in_array($org_row, $orgs_input)) {


            $sql = "DELETE FROM tbl_orgunit_user WHERE orgunit_id= $org_row AND user_id= $user_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: role_and_activity.php");
    exit();
}











?>