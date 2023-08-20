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

  if (isset($_SESSION['domain_block_type']))  {
    if ($_SESSION['domain_block_type'] != "sys-defined")  {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
    }
    if (isset($_SESSION['BDSE'])) {
      if ($_SESSION['BDSE'] == "NO") {
  
        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
      }
    }
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  
    
    
    
    $id = trim($_POST["id"]);
    $domain_name = trim($_POST["domain_name"]);
    $domain_owner = trim($_POST["domain_owner"]);
    $domain_type = trim($_POST["domain_type"]);
    $top_level_domain = trim($_POST["top_level_domain"]);
    $domain_status = trim($_POST["domain_status"]);

    if (stripos($domain_name,"http")!==false || stripos($domain_name,"https")!==false  || stripos($domain_name,"//")!==false  || stripos($domain_name,"/")!==false) {
      $not_allwd="true";
      header("Location:block_domain_edit.php?id={$id}&not_allwd={$not_allwd}");
      exit();
    
    
    }
  
    $update_stmt = $conn->prepare("UPDATE blocked_domains SET
     domain_name=:domain_name,
     domain_owner=:domain_owner, 
     domain_type=:domain_type, 
     top_level_domain=:top_level_domain , 
     domain_status=:domain_status 
    WHERE blocked_domain_id= $id");
    $update_stmt->bindValue(':domain_name', $domain_name);
    $update_stmt->bindValue(':domain_owner', $domain_owner);
    $update_stmt->bindValue(':domain_type', $domain_type);
    $update_stmt->bindValue(':top_level_domain', $top_level_domain);
    $update_stmt->bindValue(':domain_status', $domain_status);
   
    if($update_stmt->execute())
    {




    header("Location: block_domain.php");
      exit();


    }


    
         


                


        



}
