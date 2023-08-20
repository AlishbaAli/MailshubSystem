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
$sql = "SELECT ID, Name FROM tbl_institutes WHERE Name = :Name";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":Name", $param_name );
$param_name = $_POST['str'];





if($stmt->execute()){
 if($stmt->rowCount() == 1){
    
     $row = $stmt->fetch();

     
  
         // echo $row['Country']." ";
         // echo $row['State']." ";
         // echo $row['City'];
         $data['ID']= $row['ID'];
         $data['Name']=$row['Name'];
       
        
     
 }
 echo json_encode($data);
}

?>