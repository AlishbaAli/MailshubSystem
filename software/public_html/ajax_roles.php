
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




$orgunit_id = $_POST["orgunit_id"];
//$orgunit_id=1;



$stmt_o = $conn->prepare("SELECT system_entityid FROM tbl_organizational_unit WHERE orgunit_id= :orgunit_id");
$stmt_o->bindValue(':orgunit_id',  $orgunit_id);

$stmt_o->execute();
$sys_id = $stmt_o->fetch();
$sys_id = $sys_id['system_entityid'];



$sql_role = "SELECT
    role_prev_id,
    role_prev_title,
    system_entityid
FROM
    tbl_role_privilege
WHERE
    system_entityid =$sys_id";

$stmt2 = $conn->prepare($sql_role);
$stmt2->execute();





$data = array();
while ($row = $stmt2->fetch()) {






    $data[] = array('role_prev_title' => $row['role_prev_title'], 'role_prev_id' => $row['role_prev_id']);
}

echo json_encode($data);




?>