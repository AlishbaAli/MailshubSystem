
<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}

$role = $role_tenure = $role_code = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = trim($_POST["role_prev_title"]);
    $r_level = trim($_POST["restriction_level"]);
    $role_type = trim($_POST["role_type"]);
    $role_desc = trim($_POST["role_prev_desc"]);

    if ($role_type=='Administrative') {$system_entityid =1;}
    if ($role_type=='Operations') {$system_entityid =2;}
    if ($role_type=='Technical') {$system_entityid =3;}

    $insert_query = "INSERT INTO tbl_role_privilege(role_prev_title, role_prev_desc, restriction_level,role_type, system_entityid, added_by)


   VALUES (:role_prev_title, :role_prev_desc,:restriction_level, :role_type, :system_entityid, :added_by)";

    $insert_stmt = $conn->prepare($insert_query);

    $insert_stmt->bindParam(":role_prev_title", $role);
    $insert_stmt->bindParam(":restriction_level", $r_level);
    $insert_stmt->bindParam(":role_type", $role_type);
    $insert_stmt->bindParam(":role_prev_desc", $role_desc);
    $insert_stmt->bindParam(":system_entityid", $system_entityid);
    $insert_stmt->bindParam(":added_by", $_SESSION['username']);

    if ($insert_stmt->execute()) {
        header("Location: role_and_activity.php");
        exit();
    }
}
?>