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
if (isset($_SESSION['CTYPEO'])) {
	if ($_SESSION['CTYPEO'] == "NO") {
  
		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
  }




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orgunit_id = $_POST["id"];
    $ctype_input = $_POST['ctype_name'];


    $stmt = $conn->prepare("SELECT Campaign_type.ctype_id, ctype_name FROM Campaign_type 
INNER JOIN  tbl_orgunit_ctype ON Campaign_type.ctype_id =  tbl_orgunit_ctype.ctype_id 
WHERE  tbl_orgunit_ctype.orgunit_id=$orgunit_id ");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $ctype_values = []; //to store already assigned ctypes
    foreach ($row as $output) {

        $ctype_values[] = $output['ctype_id'];
    }

    //Insert Newly added ctypes

    foreach ($ctype_input as $input_val) {
        if (!in_array($input_val, $ctype_values)) {


            $ins_stmt = $conn->prepare("INSERT INTO tbl_orgunit_ctype(ctype_id, orgunit_id) 
        VALUES (:ctype_id, :orgunit_id)");
            $ins_stmt->bindValue(':ctype_id', $input_val);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            $ins_stmt->execute();
        }
    }
    //Delete assigned ctypes

    foreach ($ctype_values as $ctype_values_row) {

        if (!in_array($ctype_values_row, $ctype_input)) {
            // echo $role_row."Delete this one";

            $sql = "DELETE FROM tbl_orgunit_ctype WHERE ctype_id= $ctype_values_row AND orgunit_id= $orgunit_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: ctype_form.php");
    exit();
}
