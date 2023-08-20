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


if (isset($_SESSION['RI'])) {
	if ($_SESSION['RI'] == "NO") {
  
		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
  }



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["register"]))                                 
    {
       
    $grid_id_input = $_POST['grid_id'];


    $stmt = $conn->prepare("SELECT grid_id FROM registered_institutions");
    $stmt->execute();
    $row = $stmt->fetchAll();
    

    $grid_id_already_registered = []; //to store already assigned grid_id
    foreach ($row as $output) {

        $grid_id_already_registered[] = $output['grid_id'];
    }



    //Insert Newly added grid_id

    foreach ($grid_id_input as $input_val) {
        if (!in_array($input_val, $grid_id_already_registered)) {
            $stmt= $conn->prepare("SELECT Name FROM tbl_institutes WHERE ID='$input_val'");
            $stmt->execute();
            $Name= $stmt->fetch();
            $institute_name= $Name['Name'];
       
            $ins_stmt = $conn->prepare("INSERT INTO registered_institutions(grid_id,institute_name,system_date) 
        VALUES (:grid_id, :institute_name, NOW())");
            $ins_stmt->bindValue(':grid_id', $input_val);
            $ins_stmt->bindValue(':institute_name', $institute_name);
            $ins_stmt->execute();
        }
    }
}

if (isset($_POST["del"]))                                 
{

    $reg_grid_id = $_POST['reg_grid_id'];
    foreach ($reg_grid_id as $reg_grid_id_row) {
    $sql = "DELETE FROM registered_institutions WHERE grid_id= '$reg_grid_id_row'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
      }

}
    // //Delete assigned grid_id

    // foreach ($grid_id_already_registered as $grid_id_already_registered_row) {

    //     if (!in_array($grid_id_already_registered_row, $grid_id_input)) {

    //         $sql = "DELETE FROM organizational_institutes WHERE grid_id= '$grid_id_already_registered_row' AND orgunit_id= '$orgunit_id'";

    //         $stmt = $conn->prepare($sql);

    //         $stmt->execute();
    //     }
    // }

    header("Location: registered_institutes.php");
    exit();
}
