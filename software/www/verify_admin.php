<?php
 ini_set('display_errors', '1');
 ini_set('display_startup_errors', '1');
 error_reporting(E_ALL);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $CampID = $_POST["CampID"];

    $stmt1 = $conn->prepare("SELECT subscription_draft,draft_subject from draft WHERE CampID=:CampID");
    $stmt1->bindValue(':CampID', $CampID);
    $stmt1->execute();
    $subscription_draft = $stmt1->fetch();
    $subscription_draft = $subscription_draft["subscription_draft"];
    $subscription_draft = htmlentities(addslashes($subscription_draft));

    $draft_subject = $subscription_draft["draft_subject"];
    

    if (isset($_POST["reason"]) AND !empty($_POST["reason"])) {
        $reason = $_POST["reason"];
        $status = "Rejected";

       
        $rejected_approved_by = $_SESSION['AdminId'];

        $stmt2 = $conn->prepare("SELECT Count(CampID) as iteration from camp_draft WHERE CampID=:CampID");
        $stmt2->bindValue(':CampID', $CampID);
        $stmt2->execute();
        $rejected_iteration = $stmt2->fetch();
        $rejected_iteration = $rejected_iteration['iteration']+1;



        $stmt3 = $conn->prepare("INSERT INTO camp_draft(CampID,subscription_draft,draft_subject,rejected_iteration,status, reason, rejected_approved_by) 
        VALUES(:CampID, :subscription_draft,:draft_subject, :rejected_iteration, :status, :reason, :rejected_approved_by)");
        $stmt3->bindValue(':CampID', $CampID);
        $stmt3->bindValue(':subscription_draft', $subscription_draft);
        $stmt3->bindValue(':draft_subject', $draft_subject);
        $stmt3->bindValue(':rejected_iteration', $rejected_iteration);
        $stmt3->bindValue(':status', $status);
        $stmt3->bindValue(':reason', $reason);
        $stmt3->bindValue(':rejected_approved_by', $rejected_approved_by);

        if ($stmt3->execute()) {
            
            $stmt_update_status = $conn->prepare("UPDATE `campaign`
            SET `Camp_Status` = 'Rejected'
            WHERE CampID=:CampID");
            $stmt_update_status->bindValue(':CampID', $CampID);

            if ($stmt_update_status->execute()) {
                $stmt_update_activity = $conn->prepare("UPDATE `activity`
            SET `add_draft_activity` = null ,`verification_activity` = null 
            WHERE CampID=:CampID");
            $stmt_update_activity->bindValue(':CampID', $CampID);

                    if ($stmt_update_activity->execute()) {
                       
                        // $stmt_update_draft = $conn->prepare(" DELETE FROM `draft` WHERE  CampID=:CampID");
                        // $stmt_update_draft->bindValue(':CampID', $CampID);

                        // $stmt_update_draft->execute();

                            $sql = "INSERT INTO Campaign_flow (CampID, Camp_Status) 
                            VALUES (:CampID, :Camp_Status)";
            
                        $stmt = $conn->prepare($sql);
            
                        $stmt->bindValue(':CampID', $CampID);
                        $stmt->bindValue(':Camp_Status', $status);
                        
                        $result = $stmt->execute();
                        if ($result > 0) {
                        }

                            header("Location: verifyAlertDashboard.php");
                            exit();
                        
                       
            } }
        }
    }

    else{
        $status = "Verified";
        $rejected_approved_by = $_SESSION['AdminId'];
        $stmt3 = $conn->prepare("INSERT INTO camp_draft(CampID,subscription_draft,draft_subject,status,rejected_approved_by) 
        VALUES(:CampID, :subscription_draft, :draft_subject,:status, :rejected_approved_by)");
        $stmt3->bindValue(':CampID', $CampID);
        $stmt3->bindValue(':subscription_draft', $subscription_draft);
        $stmt3->bindValue(':draft_subject', $draft_subject);
        $stmt3->bindValue(':status', $status);
        $stmt3->bindValue(':rejected_approved_by', $rejected_approved_by);
        if ($stmt3->execute()) {
            $stmt_update_status = $conn->prepare("UPDATE `campaign`
            SET `Camp_Status` = 'Verified'
            WHERE CampID=:CampID");
            $stmt_update_status->bindValue(':CampID', $CampID);
            if ($stmt_update_status->execute()) {

                $sql = "INSERT INTO Campaign_flow (CampID, Camp_Status) 
                VALUES (:CampID, :Camp_Status)";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':CampID', $CampID);
            $stmt->bindValue(':Camp_Status', $status);
            
            $result = $stmt->execute();
            if ($result > 0) {
            }
                header("Location: verifyAlertDashboard.php");
                exit();
            }
        }


    }
}
