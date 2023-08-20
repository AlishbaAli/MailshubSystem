<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}





if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orgunit_id = $_POST["id"];
    $grid_id_input = $_POST['grid_id'];


    $stmt = $conn->prepare("SELECT grid_id FROM organizational_institutes WHERE orgunit_id=$orgunit_id ");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $grid_id_already_assigned = []; //to store already assigned grid_id
    foreach ($row as $output) {

        $grid_id_already_assigned[] = $output['grid_id'];
    }



    //Insert Newly added grid_id

    foreach ($grid_id_input as $input_val) {
        if (!in_array($input_val, $grid_id_already_assigned)) {
            $stmt= $conn->prepare("SELECT Name FROM tbl_institutes WHERE ID='$input_val'");
            $stmt->execute();
            $Name= $stmt->fetch();
            $institute_name= $Name['Name'];
       
            $ins_stmt = $conn->prepare("INSERT INTO organizational_institutes(grid_id, orgunit_id,institute_name,system_date) 
        VALUES (:grid_id, :orgunit_id, :institute_name, NOW())");
            $ins_stmt->bindValue(':grid_id', $input_val);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->bindValue(':institute_name', $institute_name);
            $ins_stmt->execute();
        }
    }
    //Delete assigned grid_id

    foreach ($grid_id_already_assigned as $grid_id_already_assigned_row) {

        if (!in_array($grid_id_already_assigned_row, $grid_id_input)) {

            $sql = "DELETE FROM organizational_institutes WHERE grid_id= '$grid_id_already_assigned_row' AND orgunit_id= '$orgunit_id'";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: orgunit_institutes.php");
    exit();
}
