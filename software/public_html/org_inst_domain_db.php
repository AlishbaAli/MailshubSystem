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

if (isset($_SESSION['OID'])) {
    if ($_SESSION['OID'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ou_inst_id = $_POST["ou_inst_id"];
    $domain_input = $_POST['domain'];


    $stmt = $conn->prepare("SELECT domain FROM org_institute_maildomain WHERE ou_inst_id='$ou_inst_id'");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $domain_already_assigned = []; //to store already assigned domain
    foreach ($row as $output) {

        $domain_already_assigned[] = $output['domain'];
    }

    //Insert Newly added domain

    foreach ($domain_input as $input_val) {
        if (!in_array($input_val, $domain_already_assigned)) {

            $ins_stmt = $conn->prepare("INSERT INTO org_institute_maildomain(ou_inst_id,domain, system_date) 
        VALUES (:ou_inst_id, :domain, NOW())");
            $ins_stmt->bindValue(':ou_inst_id', $ou_inst_id);
            $ins_stmt->bindValue(':domain', $input_val);
            $ins_stmt->execute();
        }
    }

  //Delete assigned domain

  foreach ($domain_already_assigned as $domain_already_assigned_row) {

    if (!in_array($domain_already_assigned_row, $domain_input))
    {

        $sql = "DELETE FROM org_institute_maildomain WHERE domain= '$domain_already_assigned_row' AND ou_inst_id= '$ou_inst_id'";
        $stmt = $conn->prepare($sql);

        $stmt->execute();
    }
}



header("Location: request_institute.php");
exit();

}

?>