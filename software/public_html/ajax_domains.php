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


$grid_id = $_POST['grid_id'];
//$grid_id="grid.1024.7";


$stmt=$conn->prepare("SELECT domain FROM  `registered_inst_domains` LEFT JOIN registered_institutions ON 
registered_inst_domains.ri_id=registered_institutions.ri_id WHERE grid_id=:grid_id");
$stmt->bindValue(':grid_id', $grid_id);
$stmt->execute();



       $data = array();
while ($row = $stmt->fetch()) {

    $data[] = array('domain' => $row['domain']);
}
   
        
    // print_r($dom);
 
 echo json_encode($data);


?>