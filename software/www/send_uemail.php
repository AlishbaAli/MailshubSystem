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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $user_id = $_POST['uid']; 



  $stmt = $conn->prepare("SELECT email,username, password FROM admin WHERE AdminId =$user_id");
  $stmt->execute();
  $row = $stmt->fetch();
  $email = $row["email"];
  $password = $row["password"];
  $username = strtoupper($row["username"]);
  $from = "root@mailshub.net";
  $draft = "Thank You for sigining up. Please click the link below to verify your email.. <br>
<a href='http://mailshub.net/verify_uemail.php?email=$email&token=$password'>Verify Email</a>

";


  $to = $email;
  $subject = "Email Verification Link";
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= "From:<$from>" . "\r\n";

  $message = "<html>
    </body>
        
        
        <div style=' width:85%; padding:20px;text-align: justify;'>
        <p>Dear $username,</p>
        <p style='text-align:justify;'>$draft</p>
        </div>
        </body>
</html>";

  echo "<br/>" . "$message" . "<br/>";



  mail($to, $subject, $message, $headers);




  $stmt = $conn->prepare("UPDATE admin
 SET email_status='Pending'
  WHERE AdminId = $user_id");

  if ($stmt->execute()) {
    $SA="SELECT role_prev_id FROM `tbl_user_role_prev`where user_id='$user_id' ";
    $SA=$conn->prepare($SA);
    $SA->execute();
    $SArid=$SA->fetch();
    
    if ($SArid==1)
    {
      header("Location: role_and_activity.php");
      exit();
    }
    else{
    header("Location: user_management.php");
    exit();
    }
  }
}
