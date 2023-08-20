<?php
ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}
if (isset($_SESSION['ADPTE'])) {
  if ($_SESSION['ADPTE'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $orgunit_id = trim($_POST["orgunit_id"]);
    $product_type_name = trim($_POST["product_type_name"]);
    $product_type_code = trim($_POST["product_type_code"]);
    $discription = trim($_POST["discription"]); 
    $status = trim($_POST["status"]);


//   $update_stmt = $conn->prepare("UPDATE  Campaign_type SET ctype_name= :ctype_name, data_format1= :data_format1, data_format2= :data_format2,
//   data_scopus= :data_scopus, data_wos= :data_wos, data_automatic= :data_automatic, ctype_domainwise_mail_send= :ctype_domainwise_mail_send, ctype_status= :ctype_status WHERE
//   ctype_id=:ctype_id");
//   $update_stmt->bindValue(':ctype_id', $id);
//   $update_stmt->bindValue(':ctype_name', $ctype_name);
//   $update_stmt->bindValue(':data_format1', $data_format1);
//   $update_stmt->bindValue(':data_format2', $data_format2);
//   $update_stmt->bindValue(':data_scopus', $data_scopus);
//   $update_stmt->bindValue(':data_wos', $data_wos);
//   $update_stmt->bindValue(':data_automatic', $data_automatic);
//   $update_stmt->bindValue(':ctype_domainwise_mail_send', $ctype_domainwise_mail_send);
//   $update_stmt->bindValue(':ctype_status', $ctype_status);

$update_stmt = $conn->prepare("UPDATE `org_product_type` SET `product_type_name`=:product_type_name,
`product_type_code`=:product_type_code,`Discription`=:Discription,`status`=:status  WHERE ou_pd_id = :ou_pd_id");
$update_stmt->bindValue(':ou_pd_id', $id);
$update_stmt->bindValue(':product_type_name', $product_type_name);
$update_stmt->bindValue(':product_type_code', $product_type_code);
$update_stmt->bindValue(':Discription', $discription);
$update_stmt->bindValue(':status', $status);

  if ($update_stmt->execute()) {

    header("Location: add_product_type.php");
    exit();
  }
}
