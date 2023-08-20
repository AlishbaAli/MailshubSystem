<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: logout.php');
  exit;
}
if (isset($_SESSION['CO'])) {
	if ($_SESSION['CO'] == "NO") {
  
		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
  }


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  

   echo $ctype_id = trim($_POST["ctype_id"]);

   echo  $CampID = trim($_POST["CampID"]);
   echo  $orgunit_id = trim($_POST["orgunit_id"]);

   //delete already existing records

      $id="DELETE FROM `Campaigns_component_order` WHERE CampID='$CampID'";
      $id=$conn->prepare($id);
      $id->execute();

   //Assigning new order to Components
$i=0;
     foreach ($_POST['sbTwo'] as $comp_order) {
     echo  $comp_order = str_replace("*","",$comp_order);
     $comp_order=trim($comp_order);

      $id="SELECT component_id from alert_components where component_name like '$comp_order' ";
      $id=$conn->prepare($id);
      $id->execute();
      $id=$id->fetch();

$ids=$id['component_id'];
     $ins_stmt = $conn->prepare("INSERT INTO  Campaigns_component_order (CampID, component_id, order_number) 
     VALUES (:CampID, :component_id, :order_number)");
     $ins_stmt->bindValue(':CampID', $CampID);
     $ins_stmt->bindValue(':component_id', $ids);
     $ins_stmt->bindValue(':order_number', $i);
     $ins_stmt->execute();

     $i++;
  }

///-----------------------------------------working for Alert-------------------

/// fetch draft
$article_title = 'article_title';
$Journal_title = 'Journal_title';
$first_name='Fname';
$last_name='Lastname';
                      
$sql_get = "SELECT c.CampName, c.mailserverid, d.subscription_draft
             FROM campaign c, draft d
              WHERE d.CampID = c.CampID AND
                     c.CampID = :CampID";
                            
                $stmt_get = $conn->prepare($sql_get);
                $stmt_get->bindValue(':CampID', $CampID);
                $result_get = $stmt_get->execute();
                               
                if ($stmt_get->rowCount() > 0) {
                    $result_get = $stmt_get->fetchAll();


foreach ($result_get as $row_get) {

$Draft_tags = ["{article_title}", "{Journal_title}"];
$cmp_draft = html_entity_decode($row_get['subscription_draft']);
$DB_Rows   = [$article_title, $Journal_title];
$cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);


$draftalert=$cmp_draft_new;

?>
<div id='Drafttest' hidden style='text-align: justify;' >

    
         <p>
         Dear System Admin, 
         </p>
         <p>
         <?php echo $draftalert; ?>
         </p>
     
</div> 
<?php 

} //foreach resultget
} // if rowcount

$Header_Banner=" "; $Footer_Banner=" ";  $Campaign_Title =" ";
                    
// $CampID=$_GET['CampID'];
//$CampID=89;

 $values = "SELECT * from campaign left join tbl_orgunit_user on tbl_orgunit_user.ou_id = campaign.ou_id where CampID='$CampID' ";
 $values = $conn->prepare($values);
 $values->execute();
 $values = $values->fetch();

 $ctype_idd = $values['ctype_id'];
 $camp_name = $values['CampName'];
 $camp_id = $values['CampID'];
 $rtemid = $values['rtemid'];
 $mailserverid = $values['mailserverid'];
 $Camp_category = $values['Camp_category'];
 $CampFor = $values['CampFor'];
 $Campaign_Title = $values['Campaign_Title'];
 $Header_Banner = $values['Header_Banner'];
 $Footer_Banner = $values['Footer_Banner'];
 $ou_id = $values['ou_id'];
 $orgunit_id = $values['orgunit_id'];
 $embargo_type= $values['embargo_type'];
 $campaign_embargo_days= $values['campaign_embargo_days'];

// This file upload.php fetch articles of a Campaign and form Article List in a variable names messagelist.            
include "upload.php";

$Article_List = $messagelist;


// for component list
$ctid=$ctype_idd;
$op="SELECT ctype_name,ct.ctype_id,component_name,ac.component_id,component_input_type ,requirement_status, cco.order_number, CampID
FROM `Campaign_type` as ct, components_for_campaign_type as cct, alert_components as ac , Campaigns_component_order as cco
where ct.ctype_id=cct.ctype_id and cct.component_id=ac.component_id and ct.ctype_id='16' and requirement_status != 'Not Required' and cco.component_id=ac.component_id 
and cco.CampID='$CampID' order by cco.order_number";
$op=$conn->prepare($op);
$op->execute();
$pids=$op->fetchAll(); 


$i=1; $products=[];
foreach ($pids as $pid) {
$ctype_name=$pid['ctype_name'];
$component_name=$pid['component_name'];
$requirement_status=$pid['requirement_status'];
$component_id=$pid['component_id'];

if($requirement_status=='Required'){
$component_name=$component_name.'*';
}

$products[].=trim($component_name);
}


// Creating Alert preview
$message2 = "<table width='799' height='430' border='0' align='center'>";                       
foreach ($products as $output) {

    if ($output=="Header_Banner" || $output=="Header_Banner*") {
        $Header_Ban= "<img  src='https://mailshub.net/img/Header_Banner/" . $Header_Banner."'>";
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Header_Ban ."</p> </td>
        </tr>";
    }
    if ($output=="Footer_Banner" || $output=="Footer_Banner*") {
        $Footer_Ban = "<img  src='https://mailshub.net/img/Footer_Banner/" .$Footer_Banner ."'>";
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Footer_Ban ."</p> </td>
        </tr>";
    }
    if ($output=="Article_List" || $output=="Article_List*") {
        
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Article_List ."</p> </td>
        </tr>";
    }
    if ($output=="Campaign_Title" || $output=="Campaign_Title*") {
        $Camp_Title = "<h2>".$Campaign_Title."</h2>";
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Camp_Title ."</p> </td>
        </tr>";
    }
    if ($output=="Draft" || $output=="Draft*") {
        
        $message2 = $message2 . " <tr>		
        <td>  <p class=''> <p>
        Dear {first_name} {last_name}, 
        </p>
        <p>". $draftalert . "</p> </p> </td>
        </tr>";
    }
 

 }

 echo $message2 = $message2 . "

<tr>
<td> <p class='' > </p> </td>	
</tr>	

</table>";

// Insert Alert Into table 
    $iteration=1;
                                    $count="SELECT max(alert_iteration) as itr from camp_alerts where CampID=:CampID ";
                                    $count=$conn->prepare($count);
                                    $count->bindParam(":CampID",$CampID);
                                    $count->execute();
                                    $counts=$count->fetch(); 
                                    if($count->rowCount()>0){
                                        $iteration = $counts['itr']+1;
                                    }
                                    
                                    
                                    $message_oaarticles23=htmlentities(htmlspecialchars($message2));
                                    $insert="INSERT INTO `camp_alerts`( `CampID`, `camp_alert`, `alert_iteration`) 
                                    VALUES (:CampID ,:camp_alert , :itr)";
                                    $insert=$conn->prepare($insert);
                                    $insert->bindParam(":CampID",$CampID);
                                    $insert->bindParam(":camp_alert",$message_oaarticles23);
                                    $insert->bindParam(":itr",$iteration);
                                   


///----------------------------------------Alert end----------------------------


//   $ins_stmt = $conn->prepare("INSERT INTO  tbl_orgunit_user (orgunit_id, user_id) 
//   VALUES (:orgunit_id, :user_id)");
//   $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
//   $ins_stmt->bindValue(':user_id', $user_id);
  if ( $insert->execute()) {



    header("Location: index.php");
     exit();
  }
}
