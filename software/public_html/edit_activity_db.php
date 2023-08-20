<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['EA'])) {
    if ($_SESSION['EA'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role_id = $_POST["id"];
    $activities_input = $_POST['activities'];


    $stmt = $conn->prepare("SELECT tbl_activity.activity_id, activity_name FROM tbl_activity INNER JOIN tbl_role_prev_activity ON tbl_activity.activity_id = tbl_role_prev_activity.activity_id WHERE tbl_role_prev_activity.role_prev_id=$role_id ");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $activity_values = []; //to store already assigned roles
    foreach ($row as $output) {

        $activity_values[] = $output['activity_id'];
    }

    //Insert Newly added roles

    foreach ($activities_input as $input_val) {
        if (!in_array($input_val, $activity_values)) {

            // echo $input_val."insert here";
            $ins_stmt = $conn->prepare("INSERT INTO   tbl_role_prev_activity (role_prev_id, activity_id) 
        VALUES (:role_prev_id, :activity_id)");
            $ins_stmt->bindValue(':activity_id', $input_val);
            $ins_stmt->bindValue(':role_prev_id', $role_id);
            $ins_stmt->execute();
        }
    }
    //Delete assigned roles

    foreach ($activity_values as $activity_row) {

        if (!in_array($activity_row, $activities_input)) {
            // echo $role_row."Delete this one";

            $sql = "DELETE FROM tbl_role_prev_activity WHERE activity_id= $activity_row AND role_prev_id= $role_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: role_and_activity.php");
    exit();
}
