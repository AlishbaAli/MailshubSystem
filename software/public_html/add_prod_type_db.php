
<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ADPT'])) {
    if ($_SESSION['ADPT'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

$role = $role_tenure = $role_code = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orgunit_id = trim($_POST["orgunit_id"]);
    $product_type_name = trim($_POST["product_type_name"]);
    $product_type_code = trim($_POST["product_type_code"]);
    $discription = trim($_POST["discription"]);
    $status = trim($_POST["status"]);

    $o="SELECT * FROM `org_product_type` where orgunit_id='$orgunit_id' and product_type_name = :product_type_name 
    and product_type_code = :product_type_code";
    $o=$conn->prepare($o);
    $o->bindParam(":product_type_name", trim($product_type_name));
    $o->bindParam(":product_type_code", trim($product_type_code));
    $o->execute();
    if($o->rowCount()<1){


    $insert_query = " INSERT INTO org_product_type(orgunit_id, product_type_name,product_type_code, discription,status)
       VALUES (:orgunit_id, :product_type_name,:product_type_code, :discription, :status) ";

    $insert_stmt = $conn->prepare($insert_query);

    $insert_stmt->bindParam(":orgunit_id", $orgunit_id);
    $insert_stmt->bindParam(":product_type_name", $product_type_name);
    $insert_stmt->bindParam(":product_type_code", trim($product_type_code));
    $insert_stmt->bindParam(":discription", $discription);
    $insert_stmt->bindParam(":status", $status);
  
    // $insert_stmt->bindParam(":system_entityid", $system_entityid);
    // $insert_stmt->bindParam(":added_by", $_SESSION['username']);

    if ($insert_stmt->execute()) {
        header("Location: add_product_type.php");
        exit();
    } } else {
        header("Location: add_product_type.php?flag=1");
        exit();
    }
}
?>