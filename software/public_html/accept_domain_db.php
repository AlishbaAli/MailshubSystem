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

if (isset($_SESSION['IRM'])) {
    if ($_SESSION['IRM'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}


$req_id= $_GET['id'];
$orgunit_id= $_GET['orgid'];
$grid_id= $_GET['grid_id'];
//requested domains
$stmtd= $conn->prepare("SELECT domain FROM request_domain WHERE req_id='$req_id' ");
$stmtd->execute();
$requested_domains= $stmtd->fetchAll();


//register domains if not already registered
$stmt = $conn->prepare("SELECT  domain, registered_institutions.ri_id as ri_id FROM registered_institutions LEFT JOIN `registered_inst_domains`
ON registered_institutions.`ri_id`= registered_inst_domains.ri_id WHERE grid_id='$grid_id'");
$stmt->execute();
$row = $stmt->fetchAll();
$ri_id="";
$domains_already_assigned = []; //to store already assigned grid_id
foreach ($row as $output) 
{

    $domains_already_assigned[] = $output['domain'];
    $ri_id= $output['ri_id'];
}


//Insert Newly added domains
foreach ($requested_domains as $input_val) 
{
    if (!in_array($input_val['domain'], $domains_already_assigned)) 
    {       
        $ins_stmt = $conn->prepare("INSERT INTO  registered_inst_domains(ri_id,domain,system_date) 
        VALUES (:ri_id, :domain, NOW())");
        $ins_stmt->bindValue(':ri_id', $ri_id);
        $ins_stmt->bindValue(':domain', $input_val['domain']);
        $ins_stmt->execute();
    }
}
//assign registered domains to organization if not already assigned

$stmt2=$conn->prepare("SELECT domain, organizational_institutes.ou_inst_id as ou_inst_id FROM organizational_institutes LEFT JOIN `org_institute_maildomain`
ON org_institute_maildomain.`ou_inst_id`= organizational_institutes.ou_inst_id WHERE ri_id=$ri_id AND orgunit_id=$orgunit_id");
$stmt2->execute();
$row2 = $stmt2->fetchAll();

print_r($row2);
$domains_already_assigned = []; //to store already assigned grid_id
$ou_inst_id="";
foreach ($row2 as $output) 
{

    $domains_already_assigned[] = $output['domain'];
    $ou_inst_id= $output['ou_inst_id'];
}
//assign Newly added domains
foreach ($requested_domains as $input_val) 
{
    if (!in_array($input_val['domain'], $domains_already_assigned)) 
    {       
        $ins_stmt = $conn->prepare("INSERT INTO  org_institute_maildomain(ou_inst_id,domain,system_date) 
        VALUES (:ou_inst_id, :domain, NOW())");
        $ins_stmt->bindValue(':ou_inst_id', $ou_inst_id);
        $ins_stmt->bindValue(':domain', $input_val['domain']);
        $ins_stmt->execute();
    }
}




//change status in request tables
$status="Accepted";
$stmt_update=$conn->prepare("UPDATE  request_institute SET status=:status, accept_rej_by=:accept_rej_by, accept_rej_date=NOW() WHERE
req_id='$req_id'");
$stmt_update->bindValue(':status',$status);
$stmt_update->bindValue(':accept_rej_by',$_SESSION['AdminId']);
$stmt_update->execute();

$stmt_update=$conn->prepare("UPDATE  request_domain SET status=:status WHERE
req_id='$req_id'");
$stmt_update->bindValue(':status',$status);
$stmt_update->execute();

header("Location: institute_req_mngmnt.php");
exit();