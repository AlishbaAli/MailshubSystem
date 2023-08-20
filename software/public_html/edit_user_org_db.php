<?php
//Remove, Assign, change Organization
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId']))
{
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['EUO'])) {
    if ($_SESSION['EUO'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    $user_id = $_POST["id"];
    $orgunit_id = $_POST['org'];
    $stmt = $conn->prepare("SELECT  tbl_organizational_unit.orgunit_id, orgunit_name FROM 
    tbl_organizational_unit INNER JOIN tbl_orgunit_user ON  tbl_organizational_unit.orgunit_id 
    =  tbl_orgunit_user.orgunit_id WHERE tbl_orgunit_user.user_id = $user_id AND ou_status='Active'");
    $stmt->execute();
    $row = $stmt->fetch();
    $exisitig_org= $row['orgunit_id'];
    //update existing to left
    if($stmt->rowCount()>0)
    {
        $stmtupdate= $conn->prepare("UPDATE tbl_orgunit_user SET ou_status='Left Organization' WHERE orgunit_id=:orgunit_id AND user_id=:user_id");
        $stmtupdate->bindValue(':orgunit_id', $exisitig_org);
        $stmtupdate->bindValue(':user_id', $user_id);
        $stmtupdate->execute();
        //get ou_id

        $stmt_get_ouid= $conn->prepare("SELECT max(ou_id) FROM tbl_orgunit_user WHERE ou_status='Left Organization' AND  orgunit_id=:orgunit_id AND 
        user_id=:user_id");
        $stmt_get_ouid->bindValue(':orgunit_id', $exisitig_org);
        $stmt_get_ouid->bindValue(':user_id', $user_id);
        $stmt_get_ouid->execute();
        $ou_id=$stmt_get_ouid->fetch();
           
        $stmt= $conn->prepare("SELECT CampID FROM `campaign` WHERE ou_id='$ou_id' AND `Camp_Status`='Active'");
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




    }

    //Assign org(new OR change)
    if($orgunit_id!='none')
    {
        $ins_stmt = $conn->prepare("INSERT INTO  tbl_orgunit_user (orgunit_id, user_id) 
        VALUES (:orgunit_id, :user_id)");
        $ins_stmt->bindValue(':orgunit_id', $orgunit_id);
        $ins_stmt->bindValue(':user_id', $user_id);
        $ins_stmt->execute();
    }

    header("Location: role_and_activity.php");
    exit();
}











?>