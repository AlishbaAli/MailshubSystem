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
$sql = "SELECT grid_id, institute_name FROM registered_institutions WHERE institute_name = :institute_name";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":institute_name", $param_name );
$param_name = $_POST['str'];





if($stmt->execute()){
 if($stmt->rowCount() == 1){
    
     $row = $stmt->fetch();

     
  
         // echo $row['Country']." ";
         // echo $row['State']." ";
         // echo $row['City'];
         $data['grid_id']= $row['grid_id'];
         $data['institute_name']=$row['institute_name'];
       
        
     
 }
 echo json_encode($data);
}

?>