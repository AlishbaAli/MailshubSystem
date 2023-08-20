<?php
  ob_start();
   session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  include 'include/conn.php';	
  //User not logged in. Redirect them back to the login page.
      session_unset();
      session_destroy();
      $email=$_GET['email'];
      $first_pw=$_GET['token'];

 

      $stmt = $conn->prepare("SELECT password FROM admin WHERE email =:email");
      $stmt->bindValue(':email', $email);
      $stmt->execute();
      $row = $stmt->fetch();
      $password = $row["password"];

      if($password!=$first_pw){
        header("Location: logout.php");

      }
      else{
    
    
      header("Location: pw_reset.php?email=$email&token=$first_pw");
      }
      
  



