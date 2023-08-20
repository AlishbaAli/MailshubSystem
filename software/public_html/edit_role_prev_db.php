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
  if (isset($_SESSION['ER'])) {
    if ($_SESSION['ER'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  
    
    
    
    $id = trim($_POST["id"]);
    $role_prev_title = trim($_POST["role_prev_title"]);
    $role_prev_desc = trim($_POST["role_prev_desc"]);
    $role_prev_status = trim($_POST["role_prev_status"]);



  
    $update_stmt = $conn->prepare("UPDATE tbl_role_privilege SET
     role_prev_title=:role_prev_title,
     role_prev_desc=:role_prev_desc, 
     role_prev_status=:role_prev_status
    WHERE role_prev_id= $id");
    $update_stmt->bindValue(':role_prev_title', $role_prev_title);
    $update_stmt->bindValue(':role_prev_desc', $role_prev_desc);
    $update_stmt->bindValue(':role_prev_status', $role_prev_status);
   
    if($update_stmt->execute())
    {




    header("Location: role_and_activity.php");
      exit();


    }


    
         


                


        



}
