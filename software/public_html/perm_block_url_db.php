<?php
ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}
if (isset($_SESSION['PBU']))  {
  if ($_SESSION['PBU']=="NO")  {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
  }

// if (isset($_SESSION['r_level'])) {
//   if ($_SESSION['r_level'] != "0") {

//       //User not logged in. Redirect them back to the login page.
//       header('Location: page-403.html');
//       exit;
//   }
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $url = trim($_POST["url"]);
  $status = trim($_POST["status"]);  
//Checl for incorrect url (containing http OR https OR // OR /)

if (stripos($url,"http")!==false || stripos($url,"https")!==false  || stripos($url,"//")!==false  || stripos($url,"/")!==false) {
    $not_allwd="true";
    header("Location:perm_block_url_form.php?not_allwd={$not_allwd}");
    exit();


}
  //check for already existing URL
  $sql = "SELECT COUNT(url) AS num 
  FROM permanently_blocked_url 
  WHERE url = :url";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':url', $url);                
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row['num'] > 0)
{
  $pbu="true";
  header("Location:perm_block_url_form.php?pbu={$pbu}");
  exit();
}
else
{

$sql = "INSERT INTO permanently_blocked_url (url,status,added_by) 
       VALUES (:url,:status, :added_by)";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':url', $url);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':added_by',$_SESSION['AdminId'] );
 if($stmt->execute()){
    header("Location: perm_block_url_form.php");
    exit();


 }
}

}
