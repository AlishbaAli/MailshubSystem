
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

    $user_id = $_POST["id"];
    $roles_input = $_POST['roles'];


    $stmt = $conn->prepare("SELECT tbl_role_privilege.role_prev_id, role_prev_title FROM tbl_role_privilege INNER JOIN tbl_user_role_prev ON tbl_role_privilege.role_prev_id = tbl_user_role_prev.role_prev_id WHERE tbl_user_role_prev.user_id = $user_id");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $role_values = []; //to store already assigned roles
    foreach ($row as $output) {

        $role_values[] = $output['role_prev_id'];
    }

    //Insert Newly added roles

    foreach ($roles_input as $input_val) {
        if (!in_array($input_val, $role_values)) {

            // echo $input_val."insert here";
            $ins_stmt = $conn->prepare("INSERT INTO  tbl_user_role_prev (role_prev_id, user_id) 
        VALUES (:role_prev_id, :user_id)");
            $ins_stmt->bindValue(':role_prev_id', $input_val);
            $ins_stmt->bindValue(':user_id', $user_id);
            $ins_stmt->execute();
        }
    }
    //Delete assigned roles

    foreach ($role_values as $role_row) {
        if (!in_array($role_row, $roles_input)) {


            $sql = "DELETE FROM tbl_user_role_prev WHERE role_prev_id= $role_row AND user_id= $user_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: role_and_activity.php");
    exit();
}











?>