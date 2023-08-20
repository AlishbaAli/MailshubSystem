<?php
  ob_start();
   session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  include 'include/conn.php';	
	
  if(!isset($_SESSION['AdminId']))
  {
      //User not logged in. Redirect them back to the login page.
      header('Location: login.php');
      exit;
  }
  if (isset($_SESSION['ESUNSUB'])) {
    if ($_SESSION['ESUNSUB'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
} 

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  
    
    
    
    $id = trim($_POST["id"]);
    $FirstName = trim($_POST["FirstName"]);
    $LastName = trim($_POST["LastName"]);
    $UnsubscriberEmail = trim($_POST["UnsubscriberEmail"]);
    $Type = trim($_POST["Type"]);
    $Status = trim($_POST["Status"]);
  
    $update_stmt = $conn->prepare("UPDATE unsubscriber SET
     FirstName=:FirstName,
      LastName=:LastName, 
      UnsubscriberEmail=:UnsubscriberEmail, 
    Type=:Type , 
    Status=:Status 
    WHERE UnsubscribeID= $id");
    $update_stmt->bindValue(':FirstName', $FirstName);
    $update_stmt->bindValue(':LastName', $LastName);
    $update_stmt->bindValue(':UnsubscriberEmail', $UnsubscriberEmail);
    $update_stmt->bindValue(':Type', $Type);
    $update_stmt->bindValue(':Status', $Status);
   
    if($update_stmt->execute())
    {




    header("Location: unsubscriberList.php");
      exit();


    }


    
         


                


        



}
