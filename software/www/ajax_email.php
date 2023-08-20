<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
   //User not logged in. Redirect them back to the login page.
   header('Location: login.php');
   exit;
}


if (isset($_POST['email'])) {
   $email = trim($_POST['email']);
   $AdminId = trim($_POST['userid']);;


   // echo $_POST['email'];
   // Check username
   $sql = "SELECT *
   FROM admin 
   WHERE  email = :email and AdminId != $AdminId";

   $stmt = $conn->prepare($sql);

   // $stmt = $pdo->prepare("SELECT trim(email) FROM tbl_board_member WHERE email=:email");
   $stmt->bindValue(':email', $email);
   $stmt->execute();

   if ($stmt->rowCount() > 0) {
      $count = $stmt->rowCount();
      echo "<span style='color: red;'>Email already exists.</span>";
   }



   exit;
}
