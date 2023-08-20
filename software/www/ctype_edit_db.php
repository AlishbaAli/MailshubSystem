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


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $id = $_POST['id'];
  $ctype_name = $_POST["ctype_name"];
  $ctype_status = $_POST["ctype_status"];
  $ctype_product = $_POST["ctype_product"];
  $ctype_questionare = $_POST["ctype_questionare"];
  $ctype_article_list = $_POST["ctype_article_list"];
  $ctype_TOC = $_POST["ctype_TOC"];


  $update_stmt = $conn->prepare("UPDATE  Campaign_type SET ctype_name= :ctype_name, ctype_status=:ctype_status, ctype_product=:ctype_product,
  ctype_questionare=:ctype_questionare, ctype_article_list=:ctype_article_list, ctype_TOC=:ctype_TOC WHERE
    ctype_id=:ctype_id");
  $update_stmt->bindValue(':ctype_id', $id);
  $update_stmt->bindValue(':ctype_status', $ctype_status);
  $update_stmt->bindValue(':ctype_name', $ctype_name);
  $update_stmt->bindValue(':ctype_product', $ctype_product);
  $update_stmt->bindValue(':ctype_questionare', $ctype_questionare);
  $update_stmt->bindValue(':ctype_article_list', $ctype_article_list);
  $update_stmt->bindValue(':ctype_TOC', $ctype_TOC);
  if ($update_stmt->execute()) {

    header("Location: ctype_form.php");
    exit();
  }
}
