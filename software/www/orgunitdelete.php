<?php
//   ob_start();
//    session_start();
//   error_reporting(E_ALL);
//   ini_set('display_errors', 1);
  
//   include 'include/conn.php';	
	
//   if(!isset($_SESSION['AdminId']))
//   {
//       //User not logged in. Redirect them back to the login page.
//       header('Location: login.php');
//       exit;
//   }


//   if(isset($_GET["orgunit_id"]))
// {

//     $sql = "DELETE FROM  tbl_organizational_unit
//     WHERE orgunit_id = :orgunit_id";
            
// $stmt = $conn->prepare($sql);
// $stmt->bindValue(':orgunit_id', $_GET["orgunit_id"]);

// $result = $stmt->execute();

// if($result > 0)
// {
//     header("Location: orgunit_form.php");
//     exit();

// } 
  
  
      
    
  
//}