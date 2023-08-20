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




  $id = trim($_POST["id"]);
  $orgunit_name = trim($_POST["orgunit_name"]);
  $orgunit_code = trim($_POST["orgunit_code"]);
  $system_entityid = trim($_POST["system_entityid"]);
  if ($system_entityid==3){
    $system_setting ='sys-defined';
  } else { $system_setting = trim($_POST["system_setting"]); }
  
  $orgunit_status = trim($_POST["orgunit_status"]);

//get current orgunit_status before updation
$stmt_current_status=$conn->prepare("SELECT orgunit_status FROM tbl_organizational_unit WHERE orgunit_id=$id");
$stmt_current_status->execute();
$current_status=$stmt_current_status->fetch();


  $update_stmt = $conn->prepare("UPDATE  tbl_organizational_unit SET
  orgunit_name=:orgunit_name,
  orgunit_code=:orgunit_code,
  system_entityid=:system_entityid, 
  system_setting=:system_setting,
  orgunit_status=:orgunit_status
  WHERE orgunit_id= $id");
  $update_stmt->bindValue(':orgunit_name', $orgunit_name);
  $update_stmt->bindValue(':orgunit_code', $orgunit_code);
  $update_stmt->bindValue(':system_entityid', $system_entityid);
  $update_stmt->bindValue(':system_setting', $system_setting);
  $update_stmt->bindValue(':orgunit_status', $orgunit_status);


  if ($update_stmt->execute()) {

    if($orgunit_status=='Suspended' && $current_status['orgunit_status']!='Suspended'){

    //hold campaigns of this organization which are Active
    //only active campaigns can be holded
    $stmt= $conn->prepare("SELECT * FROM `campaign` INNER JOIN tbl_orgunit_user ON campaign.AdminID = tbl_orgunit_user.user_id AND 
    orgunit_id=:orgunit_id AND `Camp_Status`='Active'");
    $stmt->bindValue(':orgunit_id', $id);
    $stmt->execute();

    while($row=$stmt->fetch()){

      $CampID= $row['CampID'];
      $u_stmt= $conn->prepare("UPDATE campaign SET Camp_Status='Stop' WHERE CampID=:CampID");
      $u_stmt->bindValue(':CampID', $CampID );
      $u_stmt->execute();
    }


        //change user status to org_terminated and store old status of user in temp variable inside user table
        $stmt_u= $conn->prepare("SELECT DISTINCT admin.AdminId as AdminId, admin.status as user_status  FROM
        tbl_orgunit_user INNER JOIN admin  ON
         admin.AdminId=tbl_orgunit_user.user_id AND orgunit_id=:orgunit_id");
        $stmt_u->bindvalue(':orgunit_id', $id);
        $stmt_u->execute();
         
         while($rowu= $stmt_u->fetch()){
           $AdminID= $rowu['AdminId'];
          $old_status= $rowu['user_status'];
           //store old user status in temp variable
          $stmt2= $conn->prepare("UPDATE admin SET temp_status=:temp_status, status='org_suspended' WHERE AdminId=:AdminId");
          $stmt2->bindValue(':temp_status', $old_status );
          $stmt2->bindValue(':AdminId', $AdminID );
          $stmt2->execute();
         }

       
      } //suspended end



      if($orgunit_status=='Terminated'  && $current_status['orgunit_status']!='Terminated'){
        // change status of all campaigns to Archive
   
    $stmt= $conn->prepare("SELECT * FROM `campaign` INNER JOIN tbl_orgunit_user ON campaign.AdminID = tbl_orgunit_user.user_id AND 
    orgunit_id=:orgunit_id");
    $stmt->bindValue(':orgunit_id', $id);
    $stmt->execute();

    while($row=$stmt->fetch()){

      $CampID= $row['CampID'];
       //move campaign authours data of those campaigns to Archive table
       //Hold Archive
		$sql_archive = "SELECT * FROM campaingauthors WHERE CampID='$CampID'";
		$stmt_archive = $conn->prepare($sql_archive);
		$stmt_archive->execute();
		$To_archive_data =	$stmt_archive->fetchAll();

		$conn->beginTransaction();
		foreach ($To_archive_data as $data) {
			//Insert in archive
			$insert = "INSERT INTO 	campaingauthors_hold_archive(CampaingAuthorsID,CampID,Initials, rtemid,Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
	affiliation, Country,
	email, article_title, eurekaselect_url, sending_IP, from_email, sending_hostname, Status, Sent_email_datetime)

	VALUES (:CampaingAuthorsID,:CampID, :Initials, :rtemid,:Journal_title, :Role, :Fname, :Lastname, :Add1, :Add2, :Add3, :Add4,:affiliation, :Country,
		:email, :article_title, :eurekaselect_url, :sending_IP, :from_email, :sending_hostname, :Status, :Sent_email_datetime)";
			$stmt = $conn->prepare($insert);
			$stmt->bindValue(':CampaingAuthorsID', $data["CampaingAuthorsID"]);
			$stmt->bindValue(':CampID', $data["CampID"]);
      $stmt->bindValue(':rtemid', $data["rtemid"]);
			$stmt->bindValue(':Initials', $data["Initials"]);
			$stmt->bindValue(':Journal_title', $data["Journal_title"]);
			$stmt->bindValue(':Role', $data["Role"]);
			$stmt->bindValue(':Fname', $data["Fname"]);
			$stmt->bindValue(':Lastname', $data["Lastname"]);
			$stmt->bindValue(':Add1', $data["Add1"]);
			$stmt->bindValue(':Add2', $data["Add2"]);
			$stmt->bindValue(':Add3', $data["Add3"]);
			$stmt->bindValue(':Add4', $data["Add4"]);
			$stmt->bindValue(':affiliation', $data["affiliation"]);
			$stmt->bindValue(':Country', $data["Country"]);
			$stmt->bindValue(':email', $data["email"]);
			$stmt->bindValue(':article_title', $data["article_title"]);
			$stmt->bindValue(':eurekaselect_url', $data["eurekaselect_url"]);
			$stmt->bindValue(':sending_IP', $data["ipaddress"]);
			$stmt->bindValue(':from_email', $data["emailaddress"]);
			$stmt->bindValue(':sending_hostname', $data["hostname"]);
			$stmt->bindValue(':Sent_email_datetime', $data["Sent_email_datetime"]);
			$stmt->bindValue(':Status', $data["Status"]);
			$stmt->execute();
		}
		$conn->commit();

		//DELETE archived data from campaginauthors
		$sql_delete = "DELETE FROM campaingauthors WHERE CampID='$CampID'";
		$stmt_delete = $conn->prepare($sql_delete);
		$stmt_delete->execute();


       //change status to Archive
      $u_stmt= $conn->prepare("UPDATE campaign SET Camp_Status='Archive' WHERE CampID=:CampID");
      $u_stmt->bindValue(':CampID', $CampID );
      $u_stmt->execute();
    }

     //change user status to org_terminated and store old status of user in temp variable inside user table
     $stmt_u= $conn->prepare("SELECT DISTINCT admin.AdminId as AdminId, admin.status as user_status  FROM
     tbl_orgunit_user INNER JOIN admin  ON
      admin.AdminId=tbl_orgunit_user.user_id AND orgunit_id=:orgunit_id");
     $stmt_u->bindvalue(':orgunit_id', $id);
     $stmt_u->execute();
      
      while($rowu= $stmt_u->fetch()){
        $AdminID= $rowu['AdminId'];
       $old_status= $rowu['user_status'];
        //store old user status in temp variable
       $stmt2= $conn->prepare("UPDATE admin SET temp_status=:temp_status, status='org_terminated' WHERE AdminId=:AdminId");
       $stmt2->bindValue(':temp_status', $old_status );
       $stmt2->bindValue(':AdminId', $AdminID );
       $stmt2->execute();
      }

       





      }//terminated end




      if($orgunit_status=='Active'&& $current_status['orgunit_status']!='Active'){
   
        // we want to keep stop campaigns as stop 
        //org_Admin/camp_manager will resume them
        //change user status from org_suspended to temp_status
        //nullify temp_status

        $stmt_u= $conn->prepare("SELECT DISTINCT admin.AdminId as AdminId, admin.temp_status as temp_status FROM 
        tbl_orgunit_user INNER JOIN admin ON 
         admin.AdminId=tbl_orgunit_user.user_id AND orgunit_id= :orgunit_id");
        $stmt_u->bindvalue(':orgunit_id', $id);
        $stmt_u->execute();
         
         while($rowu= $stmt_u->fetch()){
           $AdminID= $rowu['AdminId'];
          $status= $rowu['temp_status'];
           //store old user status in temp variable
          $stmt2= $conn->prepare("UPDATE admin SET status=:status, temp_status=NULL WHERE AdminId=:AdminId");
          $stmt2->bindValue(':status', $status );
          $stmt2->bindValue(':AdminId', $AdminID );
          $stmt2->execute();
         }


      }//active end


    
      




    }


    header("Location: orgunit_form.php");
    exit();
  

    
  }

?>