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

  $admin_email_id = $_POST['uid'];
  $stmt = $conn->prepare("SELECT * FROM admin_email WHERE admin_email_id ='$admin_email_id'");
  $stmt->execute();
  $row = $stmt->fetch();
  $email = $row["emails"];
  $AdminId= $row['AdminId'];

  $stmt1 = $conn->prepare("SELECT username FROM admin WHERE AdminId='$AdminId'");
  $stmt1->execute();
  $username= $stmt1->fetch();
  $username = $username['username'];
 

  $from = "root@mailshub.net";
  $draft = "You received this email because you wanted to verify your email address. Please click the link below to verify your email.. <br>
<a href='http://mailshub.net/verify_pemail.php?id=$admin_email_id'>Verify Email</a>

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




  $stmt2 = $conn->prepare("UPDATE admin_email
 SET emails_status='Pending'
  WHERE admin_email_id = '$admin_email_id'");
  if($stmt2->execute()){
    header("Location: profile2.php");
    exit;

  }

}
