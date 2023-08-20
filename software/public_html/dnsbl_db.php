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
  if (isset($_SESSION['ADNSBL'])) {
    if ($_SESSION['ADNSBL'] == "NO") {
  
      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
    }
  }


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  


   
    $dnsbl_name = trim($_POST["dnsbl_name"]);
    $priority_color = trim($_POST["priority_color"]);
    $priority_score = trim($_POST["priority_score"]);
    $status = trim($_POST["status"]);
   //my code
  
        $sql = "SELECT COUNT(dnsbl_name) AS num 
        FROM  dnsbl
        WHERE dnsbl_name = :dnsbl_name";

        $stmt = $conn->prepare($sql);

        //Bind the provided username to our prepared statement.
            $stmt->bindValue(':dnsbl_name', $dnsbl_name);                

        //Execute.
            $stmt->execute();

        //Fetch the row.
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['num'] > 0){

  $DNS_BL_Name_exist= "true";

  header("Location:dnsbl_form.php?DBLexist={$DNS_BL_Name_exist}");
  exit();


}


   // mycode
else{

    $ins_stmt = $conn->prepare("INSERT INTO   dnsbl (dnsbl_name, priority_color,priority_score, status) 
    VALUES (:dnsbl_name, :priority_color, :priority_score, :status)");
    $ins_stmt->bindValue(':dnsbl_name', $dnsbl_name);
    $ins_stmt->bindValue(':priority_color', $priority_color);
    $ins_stmt->bindValue(':priority_score', $priority_score);
    $ins_stmt->bindValue(':status', $status);

  
    if($ins_stmt->execute())
    {


    header("Location: dnsbl.php");
      exit();


    }


  }
         


                


        



}
