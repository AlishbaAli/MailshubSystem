
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

$role = $role_tenure = $role_code = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $component_name = trim($_POST["component_name"]);
    $component_type = trim($_POST["component_type"]);
    $discription = trim($_POST["discription"]);

    $o="SELECT * FROM `alert_components` where component_name = :component_name 
    and component_input_type = :component_type";
    $o=$conn->prepare($o);
    $o->bindParam(":component_name", $component_name);
    $o->bindParam(":component_type", $component_type);
    $o->execute();
    if($o->rowCount()<1){


    $insert_query = " INSERT INTO alert_components(component_name,component_input_type, component_discription)
       VALUES (:component_name,:component_input_type, :discription) ";

    $insert_stmt = $conn->prepare($insert_query);

    $insert_stmt->bindParam(":component_name", $component_name);
    $insert_stmt->bindParam(":component_input_type", $component_type);
    $insert_stmt->bindParam(":discription", $discription);
  
    // $insert_stmt->bindParam(":system_entityid", $system_entityid);
    // $insert_stmt->bindParam(":added_by", $_SESSION['username']);

    if ($insert_stmt->execute()) {
        header("Location: add_component.php");
        exit();
    } } else {
        header("Location: add_component.php?flag=1");
        exit();
    }
}
?>