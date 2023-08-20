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
if (isset($_SESSION['PBUE'])) {
  if ($_SESSION['PBUE'] == "NO") {

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
  $url =trim($_POST["url"]);
  $status = trim($_POST["status"]);  
  $id = $_POST["id"];  
//Checl for incorrect url (containing http OR https OR // OR /)

if (stripos($url,"http")!==false || stripos($url,"https")!==false  || stripos($url,"//")!==false  || stripos($url,"/")!==false) {
    $not_allwd="true";
    header("Location:perm_block_url_edit.php?id={$id}&not_allwd={$not_allwd}");
    exit();


}
  

$sql = "UPDATE permanently_blocked_url SET url=:url, status=:status WHERE permanently_blocked_urlid=:permanently_blocked_urlid";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':url', $url);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':permanently_blocked_urlid',$id );
 if($stmt->execute()){
    header("Location: perm_block_url_form.php");
    exit();


 
}

}
