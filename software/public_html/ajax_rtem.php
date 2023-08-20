<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}


//echo $_POST['str'];
$sql_assigned = "SELECT u.AdminId AS user_id,
 u.email AS email,
 r.rtemid  rtemid
 FROM
 admin AS u
LEFT JOIN tbl_user_rte AS r ON u.AdminId = r.user_id WHERE user_id=2";
$stmt_assigned = $conn->prepare($sql_assigned);
//$stmt_assigned->bindParam(":user_id", $_POST['user_id'] );
$stmt_assigned->execute();


while ($row = $stmt_assigned->fetch()) {

  $remail_ids[] = $row["rtemid"];
}




foreach ($remail_ids as $id) {
  $sql = "SELECT rtemid,reply_to_email FROM reply_to_emails WHERE rtemid=$id";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $remail = $stmt->fetch();

  $data['reply_to_email'] = $remail['reply_to_email'];
  $data_id['rtemid'] = $remail['rtemid'];
}

$sql_all = "SELECT * FROM reply_to_emails";
$stmt_all = $conn->prepare($sql_all);
$stmt_all->execute();

while ($all = $stmt_all->fetch()) {


  $all_remail['reply_to_email'] .=  $all["reply_to_email"];

  $all_remail["rtemid"] .=  $all["rtemid"];
}
echo "All emails <br>";

print_r($all_remail);
echo "<br>";

echo "alishba emails <br>";
print_r($data);

  
 //echo json_encode($data);
