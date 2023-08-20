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

if (isset($_SESSION['unsubscription_type']))  {
  if ($_SESSION['unsubscription_type']!="sys-defined")  {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    
    }
  }
  if (isset($_SESSION['EXTUSUBM'])) {
    if ($_SESSION['EXTUSUBM'] == "NO") { 

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $FirstName = trim($_POST["FirstName"]);
  $LastName = trim($_POST["LastName"]);
  $UnsubscriberEmail = trim($_POST["UnsubscriberEmail"]);
  $Type = trim($_POST["Type"]);

  $Category="sys-defined";

 //check if email exist in both the tables
 $stmt = $conn->prepare("SELECT * FROM unsubscriber WHERE UnsubscriberEmail=:UnsubscriberEmail and external_add_date IS not NULL");
 $stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
 $stmt->execute();
 if ($stmt->rowCount() > 0) {
  $unsub ="true";

        header("Location:external_unsubscribe.php?unsub={$unsub}");
        exit();
 }

 $stmt12= $conn->prepare("SELECT * FROM  unsubscriber 
 WHERE UnsubscriberEmail = :UnsubscriberEmail AND internal_add_date IS NOT NULL AND external_add_date IS NULL");
   $stmt12->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
   $stmt12->execute();
 if($stmt12->rowCount() > 0){
   $query_stmt2 = $conn->prepare("UPDATE unsubscriber SET external_add_date= NOW(), `Type`='Both'  WHERE  UnsubscriberEmail = :UnsubscriberEmail");
   $query_stmt2->bindParam(':UnsubscriberEmail', $UnsubscriberEmail);
   $result2 = $query_stmt2->execute();
 
   if($query_stmt2->rowCount() > 0)
   {
    header("Location: unsubscriberList.php");
    exit();
   }	
   

 }
 else {
  

  $ins_stmt = $conn->prepare("INSERT INTO  unsubscriber (FirstName, LastName,UnsubscriberEmail, Type, Category, AdminId, external_add_date) 
    VALUES (:FirstName, :LastName, :UnsubscriberEmail, :Type, :Category, :AdminId, NOW())");
  $ins_stmt->bindValue(':FirstName', $FirstName);
  $ins_stmt->bindValue(':LastName', $LastName);
  $ins_stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
  $ins_stmt->bindValue(':Type', $Type);
  $ins_stmt->bindValue(':Category', $Category);
  $ins_stmt->bindValue(':AdminId', $_SESSION['AdminId']);
  if ($ins_stmt->execute()) {




    header("Location: unsubscriberList.php");
    exit();
  }
}
}
