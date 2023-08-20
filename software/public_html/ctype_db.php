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
if (isset($_SESSION['CTYPE'])) {
	if ($_SESSION['CTYPE'] == "NO") {
  
		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
  }


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $ctype_name = trim($_POST["ctype_name"]);
  $data_format1 = trim($_POST["data_format1"]);
  $data_format2 = trim($_POST["data_format2"]);
  $data_scopus = trim($_POST["data_scopus"]);
  $data_wos = trim($_POST["data_wos"]);
  $data_automatic = trim($_POST["data_automatic"]);
  $ctype_domainwise_mail_send = trim($_POST["ctype_domainwise_mail_send"]);
  $ctype_status = trim($_POST["ctype_status"]);
    
    
  $sql = "SELECT COUNT(ctype_name) AS num 
  FROM  Campaign_type 
  WHERE ctype_name = :ctype_name";

  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':ctype_name', $ctype_name);                
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);


  if($row['num'] > 0)
  {
    //campaign already exist
    $campaign_exist= "true";
    header("Location:ctype_form.php?campaign_exist={$campaign_exist}");
    exit();
  
  }
  
  else 
  {  
     
  $sql ="INSERT INTO  Campaign_type (ctype_name, data_format1, data_format2,data_scopus, data_wos,data_automatic, ctype_domainwise_mail_send,ctype_status) 
        VALUES (:ctype_name, :data_format1, :data_format2, :data_scopus, :data_wos, :data_automatic,:ctype_domainwise_mail_send, :ctype_status)";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':ctype_name', $ctype_name);
  $stmt->bindValue(':data_format1', $data_format1);
  $stmt->bindValue(':data_format2', $data_format2);
  $stmt->bindValue(':data_scopus', $data_scopus);
  $stmt->bindValue(':data_wos', $data_wos);
  $stmt->bindValue(':data_automatic', $data_automatic);
  $stmt->bindValue(':ctype_domainwise_mail_send', $ctype_domainwise_mail_send);
  $stmt->bindValue(':ctype_status', $ctype_status);
    if($stmt->execute())
    {
      header("Location: ctype_form.php");
      exit();
    }   

  }    

}
