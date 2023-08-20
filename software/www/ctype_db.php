<?php
  ob_start();
   session_start();
  // error_reporting(E_ALL);
  // ini_set('display_errors', 1);
  
  include 'include/conn.php';	
	
  if(!isset($_SESSION['AdminId']))
  {
      //User not logged in. Redirect them back to the login page.
      header('Location: login.php');
      exit;
  }


if ($_SERVER["REQUEST_METHOD"] == "POST")
{


  $ctype_name = trim($_POST["ctype_name"]);
  $ctype_status = trim($_POST["ctype_status"]);
  $ctype_product = trim($_POST["ctype_product"]);
  $ctype_questionare = trim($_POST["ctype_questionare"]);
  $ctype_article_list = trim($_POST["ctype_article_list"]);
  $ctype_TOC = trim($_POST["ctype_TOC"]);
    
    
  $sql = "SELECT COUNT(ctype_name) AS num 
  FROM  Campaign_type 
  WHERE ctype_name = :ctype_name";

$stmt = $conn->prepare($sql);

//Bind the provided username to our prepared statement.
$stmt->bindValue(':ctype_name', $ctype_name);                

//Execute.
$stmt->execute();

//Fetch the row.
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['num'] > 0)
{
  // echo "<div class='alert alert-danger' role='alert'>
  //         <b>Record Already Exist</b>.
  //       </div>
  //       <meta http-equiv='refresh' content='2; url=ctype_form.php'>
  //       ";                                    
  // die();


// my code

  //campaign already exist
  $campaign_exist= "true";

  header("Location:ctype_form.php?campaign_exist={$campaign_exist}");
  exit();

// my code 



}

else 
{  
   
 


$sql = "INSERT INTO  Campaign_type (ctype_name, ctype_status, ctype_product,ctype_questionare, ctype_article_list,ctype_TOC) 
       VALUES (:ctype_name, :ctype_status, :ctype_product, :ctype_questionare, :ctype_article_list, :ctype_TOC)";


$stmt = $conn->prepare($sql);

//Bind our variables.
$stmt->bindValue(':ctype_name', $ctype_name);
$stmt->bindValue(':ctype_status', $ctype_status);
$stmt->bindValue(':ctype_product', $ctype_product);
$stmt->bindValue(':ctype_questionare', $ctype_questionare);
$stmt->bindValue(':ctype_article_list', $ctype_article_list);
$stmt->bindValue(':ctype_TOC', $ctype_TOC);
 if($stmt->execute()){


  

    header("Location: ctype_form.php");
      exit();


 }


   

} 


   

}
