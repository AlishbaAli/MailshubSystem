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

    $orgunit_id = $_POST["id"];
    $roles_input = $_POST['roles'];


    $stmt = $conn->prepare("SELECT tbl_role_privilege.role_prev_id, role_prev_title FROM tbl_role_privilege 
INNER JOIN  orgunit_role_prev ON tbl_role_privilege.role_prev_id =  orgunit_role_prev.role_prev_id 
WHERE  orgunit_role_prev.orgunit_id=$orgunit_id ");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $roles_values = []; //to store already assigned 
    foreach ($row as $output) {

        $roles_values[] = $output['role_prev_id'];
    }

    //Insert Newly added 

    foreach ($roles_input as $input_val) {
        if (!in_array($input_val, $roles_values)) {


            $ins_stmt = $conn->prepare("INSERT INTO orgunit_role_prev (role_prev_id, orgunit_id) 
        VALUES (:role_prev_id, :orgunit_id)");
            $ins_stmt->bindValue(':role_prev_id', $input_val);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->execute();
        }
    }
    //Delete assigned reply_to_emails

    foreach ($roles_values as $roles_values_row) {

        if (!in_array($roles_values_row, $roles_input)) {
            // echo $role_row."Delete this one";

            $sql = "DELETE FROM orgunit_role_prev WHERE role_prev_id= $roles_values_row AND orgunit_id= $orgunit_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: orgunit_form.php");
    exit();
}
