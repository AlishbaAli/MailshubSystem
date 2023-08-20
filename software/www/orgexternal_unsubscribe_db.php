<?php
ob_start();
session_start();
 error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit();
}
if (isset($_SESSION['unsubscription_type']))  {
    if ($_SESSION['unsubscription_type']=="sys-defined")  {
  
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
    $orgunit_id = trim($_POST["orgunit_id"]);


    $stmt12= $conn->prepare("SELECT * FROM  unsubscriber 
 WHERE UnsubscriberEmail = :UnsubscriberEmail AND internal_add_date IS NOT NULL AND external_add_date IS NULL");
   $stmt12->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
   $stmt12->execute();
 if($stmt12->rowCount() > 0){
   $query_stmt2 = $conn->prepare("UPDATE unsubscriber SET external_add_date= NOW(), `Type`='Both'  WHERE  UnsubscriberEmail = :UnsubscriberEmail");
   $query_stmt2->bindParam(':UnsubscriberEmail', $UnsubscriberEmail);
   $result2 = $query_stmt2->execute();
 

 }

    //check if email exist in both the tables
    $stmt = $conn->prepare("SELECT unsubscriber.UnsubscriberEmail FROM unsubscriber INNER JOIN orgunit_unsubscriber
     ON unsubscriber.UnsubscribeID= orgunit_unsubscriber.UnsubscribeID AND unsubscriber.UnsubscriberEmail=:UnsubscriberEmail
     AND orgunit_unsubscriber.orgunit_id=:orgunit_id");
    $stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
    $stmt->bindValue(':orgunit_id', $orgunit_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $unsubo ="true";

        header("Location:orgexternal_unsubscribe.php?unsubo={$unsubo}");
        exit();
    }


    // Get org settings to decide category 
     $category=$_SESSION['unsubscription_type'];
    //check if email exist in unsubscriber but not in current organization
   

    $stmt = $conn->prepare("SELECT
    UnsubscriberEmail
    FROM
    unsubscriber
    WHERE UnsubscriberEmail =:UnsubscriberEmail ");
    $stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
    $stmt->execute();
    $row = $stmt->fetch();


    $stmto = $conn->prepare("SELECT
    UnsubscriberEmail
    FROM
    orgunit_unsubscriber WHERE
     UnsubscriberEmail =:UnsubscriberEmail AND orgunit_id=:orgunit_id 
    ");
    $stmto->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
    $stmto->bindValue(':orgunit_id', $orgunit_id);
    $stmto->execute();
    $rowo = $stmto->fetch();

    if ($stmt->rowCount() > 0 && $stmto->rowCount() <1) {
        $stmt = $conn->prepare("SELECT
        unsubscriber.UnsubscribeID
        FROM
        unsubscriber WHERE UnsubscriberEmail=:UnsubscriberEmail");
        $stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
        $stmt->execute();
        $row = $stmt->fetch();
        $UnsubscribeID = $row["UnsubscribeID"];

        $ins_stmt = $conn->prepare("INSERT INTO  orgunit_unsubscriber (UnsubscribeID, UnsubscriberEmail)
        SELECT UnsubscribeID, UnsubscriberEmail FROM unsubscriber WHERE UnsubscribeID=:UnsubscribeID");
        $ins_stmt->bindValue(':UnsubscribeID', $UnsubscribeID);

        if ($ins_stmt->execute()) {
            $orgunit_unsubscriber_id = $conn->lastInsertId();
            $stmtu = $conn->prepare("UPDATE orgunit_unsubscriber  SET orgunit_id=:orgunit_id WHERE
            orgunit_unsubscriber_id= :orgunit_unsubscriber_id");
            $stmtu->bindValue(':orgunit_id', $orgunit_id);
            $stmtu->bindValue(':orgunit_unsubscriber_id', $orgunit_unsubscriber_id);
            if ($stmtu->execute()) {
                header("Location: orgunsubscriberList.php");
                exit();
            }
        }
    }
    //email is not present in both tables

    $stmt = $conn->prepare("SELECT unsubscriber.UnsubscriberEmail FROM unsubscriber INNER JOIN orgunit_unsubscriber
     ON unsubscriber.UnsubscribeID= orgunit_unsubscriber.UnsubscribeID AND unsubscriber.UnsubscriberEmail=:UnsubscriberEmail");
    $stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);

    $stmt->execute();
    if ($stmt->rowCount() < 1) {
        $ins_stmt = $conn->prepare("INSERT INTO  unsubscriber (FirstName, LastName,UnsubscriberEmail, Type, Category, AdminId, external_add_date)
        VALUES (:FirstName, :LastName, :UnsubscriberEmail, :Type, :Category, :AdminId, NOW())");
        $ins_stmt->bindValue(':FirstName', $FirstName);
        $ins_stmt->bindValue(':LastName', $LastName);
        $ins_stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
        $ins_stmt->bindValue(':Type', $Type);
        $ins_stmt->bindValue(':Category', $category);
        $ins_stmt->bindValue(':AdminId', $_SESSION['AdminId']);
        if ($ins_stmt->execute()) {
            $UnsubscribeID = $conn->lastInsertId();
            $ins_stmt = $conn->prepare("INSERT INTO  orgunit_unsubscriber (UnsubscribeID, UnsubscriberEmail, orgunit_id)
            VALUES(:UnsubscribeID,:UnsubscriberEmail,:orgunit_id)");
            $ins_stmt->bindValue(':UnsubscribeID', $UnsubscribeID);
            $ins_stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
            $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
            if ($ins_stmt->execute()) {
                header("Location: orgunsubscriberList.php");
                exit();
            }
        }
    }


}
