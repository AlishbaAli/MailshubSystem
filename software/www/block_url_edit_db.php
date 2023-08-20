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

  if (isset($_SESSION['url_block_type'])) {
    if ($_SESSION['url_block_type'] !="sys-defined") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  
    $id = trim($_POST["id"]);
    $url = trim($_POST["url"]);
    $status = trim($_POST["status"]);

     //Checl for incorrect url (containing http OR https OR // OR /)

if (stripos($url,"http")!==false || stripos($url,"https")!==false  || stripos($url,"//")!==false  || stripos($url,"/")!==false) {
  $not_allwd="true";
  header("Location:block_url_edit.php?id={$id}&not_allwd={$not_allwd}");
  exit();


}
    $update_stmt = $conn->prepare("UPDATE blocked_url SET
    url=:url,
    status=:status 
    WHERE blocked_url_id= $id");
    $update_stmt->bindValue(':url', $url);
    $update_stmt->bindValue(':status', $status);

   
    if($update_stmt->execute())
    {
      header("Location: block_url.php");
      exit();


    }


}
