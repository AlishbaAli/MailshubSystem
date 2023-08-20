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


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $flag=0;


  $reply_to_email = trim($_POST["reply_to_email"]);
  $rtem_status = trim($_POST["rtem_status"]);
  $rtem_reason= trim($_POST['reason']);

  //dont allow permenant block URL 
$stmt_url=$conn->prepare("SELECT url FROM permanently_blocked_url WHERE status='Active'");
$stmt_url->execute();
$urls=$stmt_url->fetchAll(); 

foreach ($urls as $url) {
    
  if (stripos($reply_to_email,$url['url'])!==false) {
      //url found in reply_to_email
      $flag=1;
      break;
  }
}

if($flag==1){
  $blck= "true";
  header("Location: reply_to_email_form.php?blck={$blck}");
  exit();
  }
 
 
    
    
  $sql = "SELECT COUNT(reply_to_email) AS num 
  FROM reply_to_emails 
  WHERE reply_to_email = :reply_to_email";

$stmt = $conn->prepare($sql);

//Bind the provided username to our prepared statement.
$stmt->bindValue(':reply_to_email', $reply_to_email);                

//Execute.
$stmt->execute();

//Fetch the row.
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['num'] > 0)
{
  // echo "<div class='alert alert-danger' role='alert'>
  //         <b>Record Already Exist</b>.
  //       </div>
  //       <meta http-equiv='refresh' content='2; url=reply_to_email_form.php'>
  //       ";                                    
  // die();


  $E_exist="true";

  header("Location:reply_to_email_form.php?E_exist={$E_exist}");
  exit();


}

else 
{  
   
 
  $added_by=$_SESSION['AdminId'];

$sql = "INSERT INTO reply_to_emails (reply_to_email, rtem_status, rtem_reason,added_by) 
       VALUES (:reply_to_email, :rtem_status, :rtem_reason, :added_by)";


$stmt = $conn->prepare($sql);

//Bind our variables.
$stmt->bindValue(':reply_to_email', $reply_to_email);
$stmt->bindValue(':rtem_status', $rtem_status);
$stmt->bindValue(':rtem_reason', $rtem_reason);
$stmt->bindValue(':added_by', $added_by);
 if($stmt->execute()){

  
    header("Location: reply_to_email_form.php");
      exit();


 }


   

} 


   

}
