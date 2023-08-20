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

//user status can be terminated/suspended/ or activated again after suspended
if ($_SERVER["REQUEST_METHOD"] == "POST")
{ 

    $user_id = $_POST["id"];
    $updated_status= $_POST["status"];
    //get current user_status before updation
    $stmt_current_status=$conn->prepare("SELECT status FROM admin WHERE AdminId=$user_id");
    $stmt_current_status->execute();
    $current_status=$stmt_current_status->fetch();
    if($updated_status=='Suspended' && $current_status['status']!='Suspended')
    { 
      //hold campaigns of this user which are Active
      //only active campaigns can be holded
      $stmt= $conn->prepare("SELECT CampID FROM `campaign` INNER JOIN tbl_orgunit_user  ON  tbl_orgunit_user.ou_id=campaign.ou_id AND `Camp_Status`='Active'
      AND user_id=:user_id AND ou_status='Active'");
      $stmt->bindValue(':user_id', $user_id);
      $stmt->execute();  
      while($row=$stmt->fetch())
      {
        $CampID= $row['CampID'];
        $u_stmt= $conn->prepare("UPDATE campaign SET Camp_Status='Stop' WHERE CampID=:CampID");
        $u_stmt->bindValue(':CampID', $CampID );
        $u_stmt->execute();
      }
      //now suspend the user by storing its old status in temp variable
      //store old user status in temp variable
      $stmt2= $conn->prepare("UPDATE admin SET temp_status=:temp_status, status=:status WHERE AdminId=:AdminId");
      $stmt2->bindValue(':temp_status', $current_status['status'] );
      $stmt2->bindValue(':status', $updated_status );
      $stmt2->bindValue(':AdminId', $user_id );
      $stmt2->execute();

      //suspend ou status 
      $stmt3=$conn->prepare("UPDATE tbl_orgunit_user SET ou_status='User Suspended' WHERE user_id=:user_id AND ou_status='Active'");
      $stmt3->bindValue(':user_id', $user_id);
      $stmt3->execute();

    }//suspended end
    
    if($updated_status=='Terminated' && $current_status['status']!='Terminated')
    {
        
      $stmt= $conn->prepare("SELECT CampID FROM `campaign` INNER JOIN tbl_orgunit_user  ON  tbl_orgunit_user.ou_id=campaign.ou_id AND `Camp_Status`='Active'
      AND user_id=:user_id AND ou_status='Active'");
      $stmt->bindValue(':user_id', $user_id);
      $stmt->execute();
  
      while($row=$stmt->fetch())
      {
        $CampID= $row['CampID'];
        //move campaign authours data of those campaigns to Archive table
       //Hold Archive
		    $sql_archive = "SELECT * FROM campaingauthors WHERE CampID='$CampID'";
		    $stmt_archive = $conn->prepare($sql_archive);
		    $stmt_archive->execute();
		    $To_archive_data =	$stmt_archive->fetchAll();
    
		    $conn->beginTransaction();
		    foreach ($To_archive_data as $data)
        {
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
        //store old user status in temp variable
      $stmt2= $conn->prepare("UPDATE admin SET temp_status=:temp_status, status=:status WHERE AdminId=:AdminId");
      $stmt2->bindValue(':temp_status', $current_status['status'] );
      $stmt2->bindValue(':status', $updated_status );
      $stmt2->bindValue(':AdminId', $user_id );
      $stmt2->execute();


      //Terminate ou status 
      $stmt3=$conn->prepare("UPDATE tbl_orgunit_user SET ou_status='User Terminated' WHERE user_id=:user_id AND ou_status='Active'");
      $stmt3->bindValue(':user_id', $user_id);
      $stmt3->execute();

    }//terminated end
     
    if($updated_status=='Active' && $current_status['status']!='Active')
    {
        // we want to keep stop campaigns as stop 
        //org_Admin/camp_manager will resume them
        //change user status from org_suspended to temp_status
        //nullify temp_status
      $stmt_u= $conn->prepare("SELECT temp_status FROM admin WHERE AdminId=:AdminId");
      $stmt_u->bindvalue(':AdminId', $user_id);
      $stmt_u->execute();
      $temp_status= $stmt_u->fetch();
      //store old user status in temp variable
      $stmt2= $conn->prepare("UPDATE admin SET status=:status, temp_status=NULL WHERE AdminId=:AdminId");
      $stmt2->bindValue(':status', $temp_status['temp_status']);
      $stmt2->bindValue(':AdminId', $user_id );
      $stmt2->execute();

        //Active ou_status
      $stmt3=$conn->prepare("UPDATE tbl_orgunit_user SET ou_status='Active' WHERE user_id=:user_id AND ou_status='User Suspended'");
      $stmt3->bindValue(':user_id', $user_id);
      $stmt3->execute();
    }//Active end
       
    header("Location: user_management.php");
    exit();



}
?>