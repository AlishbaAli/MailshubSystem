
<?php
ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}



try {
    if (isset($_POST['addCampaign'])) {

        //Retrieve the field values from our registration form.
        $CampName = !empty($_POST['CampName']) ? trim($_POST['CampName']) : null;
        $rtemid = !empty($_POST['rep_to_emails']) ? trim($_POST['rep_to_emails']) : null;
        $mailserverid = !empty($_POST['mailservers']) ? trim($_POST['mailservers']) : null;
        $productid = !empty($_POST['products']) ? trim($_POST['products']) : null;
        $camptype = !empty($_POST['CampCat']) ? trim($_POST['CampCat']) : null;
        $camptype = !empty($_POST['CampType']) ? trim($_POST['CampType']) : null;
        $campfor = !empty($_POST['CampFor']) ? trim($_POST['CampFor']) : null;
        $CampDate = !empty($_POST['CampDate']) ? trim($_POST['CampDate']) : null;
        $format_type = !empty($_POST['format']) ? trim($_POST['format']) : null;
        $embargo_type = !empty($_POST['embargo_type']) ? trim($_POST['embargo_type']) : null;
        $campaign_embargo_days = !empty($_POST['CE']) ? trim($_POST['CE']) : null;



        $AdminID = $_SESSION['AdminId'];
        $Camp_Status = "Inactive";


        if ($embargo_type == "system_wise_embargo") {
            $campaign_embargo_days = $_POST['CED'];
        }


        print_r($_POST);

        die();


        $sql = "SELECT COUNT(CampName) AS num 
                    FROM campaign 
                    WHERE CampName = :CampName";

        $stmt = $conn->prepare($sql);

        //Bind the provided username to our prepared statement.
        $stmt->bindValue(':CampName', $CampName);

        //Execute.
        $stmt->execute();

        //Fetch the row.
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        //If the provided CampName already exists - display error.
        if ($row['num'] > 0) {
            $already = "<br/><br/><div class='alert alert-danger'><strong>This Campaign already exists!</strong></div>
                <meta http-equiv='refresh' content='3;url=addCampaign.php'>";

            //header('Location: addCampaign.php');

            //die();
        }

        //if(!isset($errorMsg))
        //{ 
        //Prepare our INSERT statement.

        else {
            $sql = "INSERT INTO campaign (
                    CampName, 
                    CampType,
                    CampFor, 
                    CampDate,
                    AdminID, 
                    Camp_Status,
                    Camp_Created_Date,
                    rtemid,
                    mailserverid,
                    productid,
                    format_type,
                    embargo_type, 
                    campaign_embargo_days ) 
                    VALUES (
                    :CampName, 
                    :CampType, 
                    :CampFor,
                    :CampDate, 
                    :AdminID, 
                    :Camp_Status,
                    Now(), 
                    :rtemid,
                    :mailserverid, 
                    :productid, 
                    :format_type, 
                    :embargo_type, 
                    :campaign_embargo_days )";


            $stmt = $conn->prepare($sql);

            //Bind our variables.
            $stmt->bindValue(':rtemid', $rtemid);
            $stmt->bindValue(':mailserverid', $mailserverid);
            $stmt->bindValue(':productid', $productid);
            $stmt->bindValue(':CampName', $CampName);
            $stmt->bindValue(':CampType', $camptype);
            $stmt->bindValue(':CampFor', $campfor);
            $stmt->bindValue(':CampDate', $CampDate);
            $stmt->bindValue(':AdminID', $AdminID);
            $stmt->bindValue(':Camp_Status', $Camp_Status);
            $stmt->bindValue(':format_type', $format_type);
            $stmt->bindValue(':embargo_type', $embargo_type);
            $stmt->bindValue(':campaign_embargo_days', $campaign_embargo_days);




            //Execute the statement and insert the new account.
            $result = $stmt->execute();

            //If the signup process is successful.
            if ($result > 0) {
                $already = "<br/><br/><div class='alert alert-success'><strong>New Campaign Successfuly Added</strong>
                        </div>
                        <meta http-equiv='refresh' content='2;url=index.php'>
                        ";
                //echo <a href='addCampaign.php'><button type='button' class='btn btn-primary' style='float:right;'>Back</button></a> 

                // Insert record in activity table
                $CampID = $conn->lastInsertId();
                $AdminId = $_SESSION['AdminId'];

                $sql = "INSERT INTO activity (author_activity, CampID, AdminId) 
                                VALUES (1, :CampID, :AdminId)";

                $stmt = $conn->prepare($sql);

                $stmt->bindValue(':CampID', $CampID);
                $stmt->bindValue(':AdminId', $AdminId);
                $result = $stmt->execute();
            }
        }
    }
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

?>