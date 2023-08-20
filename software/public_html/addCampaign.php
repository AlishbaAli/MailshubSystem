<?php
ob_start();
session_start();

error_reporting(0);
ini_set('display_errors', 0);
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['ADCAMP'])) {
    if ($_SESSION['ADCAMP'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

// echo "testing";
//  if (!empty($_POST['order_comp']))
// {
// $order = $_POST['order_comp'];
// echo $order;
// echo "testing";
// exit();
// }
  


?>
<html lang="en">

<!--head-->

<?php include 'include/head.php'; ?>
<!--head-->

<body class="theme-blue">

    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="assets/images/thumbnail.png" width="48" height="48" alt="Mplify"></div>
            <p>Please wait...</p>
        </div>
    </div> -->
    <!-- Overlay For Sidebars -->
    <div class="overlay" style="display: none;"></div>

    <div id="wrapper">

        <!--nav bar-->
        <?php include 'include/nav_bar.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->


        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">

                        <div class="col-12">
                            <div class="card">
                                <!-- <form method="post" action="addCampaign.php" enctype="multipart/form-data"> -->
                                <div class="header">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <h3>Add New Campaign</h3>
                                            <?php   include "banner_input_buttons.php"; ?>

                                        </div>
                                        <div class="col-lg-7">
                                            <!-- --------------------------------------------------------------------------------- -->
                                            <h3>Add Draft Here</h3>
                                           
                                            
                                            

                                        </div> <!-- col-7 for draft close -->

                                    </div>
                                    <?php
                                    echo $already = "";





                                    $sql = "SELECT * FROM products";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $products = $stmt->fetchAll();

                                    try {
                                        if (isset($_POST['addCampaign'])) {

                                            //Retrieve the field values from our registration form.
                                            $institutions =!empty($_POST['institutions']) ? trim($_POST['institutions']) : null;                            
                                            $rtemid = !empty($_POST['rep_to_emails']) ? trim($_POST['rep_to_emails']) : null;
                                            $mailserverid = !empty($_POST['mailservers']) ? trim($_POST['mailservers']) : null;
                                            $productid = !empty($_POST['products']) ? trim($_POST['products']) : null;
                                            $CampName = !empty($_POST['CampName']) ? trim($_POST['CampName']) : null;
                                            // $CampDate = !empty($_POST['CampDate']) ? trim($_POST['CampDate']) : null;
                                            $Camp_category = !empty($_POST['CampCat']) ? trim($_POST['CampCat']) : null;
                                            $CampDate = date('Y-m-d');
                                            $embargo_type = !empty($_POST['embargo_type']) ? trim($_POST['embargo_type']) : null;
                                            $campaign_embargo_days = !empty($_POST['CE']) ? trim($_POST['CE']) : null;
                                            $ctype_id = !empty($_POST['ctype_id']) ? trim($_POST['ctype_id']) : null;
                                            $campfor = !empty($_POST['CampFor']) ? trim($_POST['CampFor']) : null;

                                            $Campaign_Title = !empty($_POST['Campaign_Title']) ? trim($_POST['Campaign_Title']) : null;
                                            $Header_Banner = $_FILES["Header_Banner"]["name"] ;
                                            $Footer_Banner = $_FILES["Footer_Banner"]["name"] ;
                                            $Products = !empty($_POST['Products']) ? trim($_POST['Products']) : null;
                                            $campfor = !empty($_POST['CampFor']) ? trim($_POST['CampFor']) : null;

                                            $AdminID = $_SESSION['AdminId'];
                                            // ------------------------------------
                                            $check = "SELECT role_prev_id from tbl_user_role_prev where user_id='$AdminID' ";
                                            $check_super = $conn->prepare($check);
                                            $check_super->execute();
                                            $role_id = $check_super->fetch();
                                            // -----------------------------------------
                                            if ($role_id['role_prev_id'] == '1') {
                                                $orgunit_id = $_SESSION['orgunit_id'];
                                                $temp_id = "SELECT max(tbl_orgunit_user.user_id) as user_id1 from tbl_user_role_prev inner join tbl_orgunit_user on tbl_orgunit_user.user_id=tbl_user_role_prev.user_id
 where (role_prev_id='2' or role_prev_id='8') and orgunit_id='$orgunit_id' AND ou_status='Active'";
                                                $temp_uid = $conn->prepare($temp_id);
                                                $temp_uid->execute();
                                                $temp_user = $temp_uid->fetch();
                                                if (!empty(trim($temp_user['user_id1']))) {
                                                    $AdminID = $temp_user['user_id1'];
                                                }
                                            }
                                            // ------------------------------------
                                            // ------------------------------------

                                            $ouid = "SELECT ou_id from tbl_orgunit_user where user_id='$AdminID' and ou_status='Active'";
                                            $ouid = $conn->prepare($ouid);
                                            $ouid->execute();
                                            $ouid = $ouid->fetch();
                                            $ou_id = $ouid['ou_id'];

                                            $Camp_Status = "Inactive";


                                            if ($embargo_type == "global") {
                                                $campaign_embargo_days = $_POST['CED'];
                                            }



                                            $image_file = "";

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
                                                $sql = "INSERT INTO campaign ( CampName, Campaign_Title, Camp_category, ctype_id, CampFor, CampDate, ou_id, Camp_Status,  Camp_Created_Date, rtemid, mailserverid, productid ,embargo_type, campaign_embargo_days ) 
                                                        VALUES (:CampName, :Campaign_Title, :Camp_category, :ctype_id, :CampFor, :CampDate, :ou_id, :Camp_Status, Now(), :rtemid, :mailserverid, :productid, :embargo_type, :campaign_embargo_days )";


                                                $stmt = $conn->prepare($sql);

                                                //Bind our variables.
                                                $stmt->bindValue(':rtemid', $rtemid);
                                                $stmt->bindValue(':mailserverid', $mailserverid);
                                                $stmt->bindValue(':productid', $productid);
                                                $stmt->bindValue(':CampName', $CampName);
                                                $stmt->bindValue(':Campaign_Title', $Campaign_Title);
                                                $stmt->bindValue(':Camp_category', $Camp_category);
                                                $stmt->bindValue(':ctype_id', $ctype_id);
                                                $stmt->bindValue(':CampFor', $campfor);
                                                $stmt->bindValue(':CampDate', $CampDate);
                                                $stmt->bindValue(':ou_id', $ou_id);
                                                $stmt->bindValue(':Camp_Status', $Camp_Status);                                              
                                                $stmt->bindValue(':embargo_type', $embargo_type);
                                                $stmt->bindValue(':campaign_embargo_days', $campaign_embargo_days);




                                                //Execute the statement and insert the new account.
                                                $result = $stmt->execute();

                                                //If the signup process is successful.
                                                if ($result > 0) {


                                                    // Insert record in activity table
                                                    $CampID = $conn->lastInsertId();
                                                    //$AdminId = $_SESSION['AdminId'];
                                                    // ---------------------------------------------------

                                                  

                                                    $PK = $CampID;
                                                    $date=date('Ymd');
                                                     
                                                     
                                                    $path = pathinfo($Header_Banner);
                                                    $file = $_FILES["Header_Banner"]["tmp_name"];
                                                    // $imageType = $sourceProperties[2];
                                                    $ext = $path['extension'];
                                              
                                                    $full_path= "img/Header_Banner/HB".$CampID."-".$date.".".$ext;
                                                     //to store in db
                                                    $file_name_hb="HB".$CampID."-".$date.".".$ext;
                                              
                                                    move_uploaded_file($file, $full_path);

                                                    $path = pathinfo($Footer_Banner);
                                                    $file = $_FILES["Footer_Banner"]["tmp_name"];
                                                    // $imageType = $sourceProperties[2];
                                                    $ext = $path['extension'];
                                              
                                                    $full_path= "img/Footer_Banner/FB".$CampID."-".$date.".".$ext;
                                                     //to store in db
                                                    $file_name_fb="FB".$CampID."-".$date.".".$ext;
                                              
                                                    move_uploaded_file($file, $full_path);
                                              
                                                    $update_query = "UPDATE campaign SET Header_Banner=:hb , Footer_Banner=:fb  WHERE CampID= :CampID ";
                                                    $update_stmt = $conn->prepare($update_query);
                                                    $update_stmt->bindParam(":hb", $file_name_hb);
                                                    $update_stmt->bindParam(":fb",$file_name_fb);
                                                   
                                                    $update_stmt->bindParam(":CampID",$PK);
                                                    $update_stmt->execute();

                                                    // ---------------------------------------------------
                                                    $sql = "INSERT INTO Campaign_flow (CampID, Camp_Status) 
                                                        VALUES (:CampID, :Camp_Status)";

                                                    $stmt = $conn->prepare($sql);

                                                    $stmt->bindValue(':CampID', $CampID);
                                                    $stmt->bindValue(':Camp_Status', $Camp_Status);

                                                    $result = $stmt->execute();
                                                    if ($result > 0) {
                                                    }

                                                    // $sql = "INSERT INTO activity (author_activity, CampID, AdminId) 
                                                    //         VALUES (1, :CampID, :AdminId)";

                                                    // $stmt = $conn->prepare($sql);

                                                    // $stmt->bindValue(':CampID', $CampID);
                                                    // $stmt->bindValue(':AdminId', $AdminId);
                                                    // $result = $stmt->execute();
                                                    // if ($result > 0) {
                                                    // }

                                                                //add to institutions campaign
                                                               
                                                               if($institutions!=null)
                                                               {
                                                                $inst_camp= $conn->prepare("INSERT INTO campaign_institutes(CampID,ou_inst_id) VALUES(:CampID,:ou_inst_id)");
                                                                $inst_camp->bindValue(':CampID',$CampID);
                                                                $inst_camp->bindValue(':ou_inst_id',$institutions);
                                                                $inst_camp->execute();
                                                               }
                                                             
                                                    //-----------------------------[Article List] ----------------------------------------------------------//
                                                    include 'import_article.php';
                                                    // ------------------------------------------------ [ Draft Area ] ----------------------------------------------------- //

                                                    //Upload Draft Letter Data
                                                    if (isset($_POST["Draft"]) && !empty(trim($_POST["Draft"])) && isset($_POST["Camp_sub"]) && !empty(trim($_POST["Camp_sub"]))) {
                                                        //$CampID = $_POST['CampID'];
                                                        $sql = "SELECT COUNT(CampID) AS numm 
                        FROM draft 
                        WHERE CampID = :CampID";

                                                        $stmt = $conn->prepare($sql);

                                                        //Bind the provided username to our prepared statement.
                                                        $stmt->bindValue(':CampID', $CampID);

                                                        //Execute.
                                                        $stmt->execute();

                                                        //Fetch the row.
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                        //If the provided CampName already exists - display error.
                                                        if ($row['numm'] > 0) {
                                                            echo ("<br/><br/><div class='alert alert-danger' role='alert'><strong>Templete Draft already exists!</strong></div>");
                                                            echo   "<div class='element-box-content'>
                                <a href='viewDraft.php?CampID=" . $CampID . "'>
                                <button class='mr-2 mb-2 btn btn-primary btn-md' type='button'>Please Check Your Draft!</button></a>
                            </div>";
                                                            die();
                                                        }

                                                        $subscription_draft = !empty($_POST['Draft']) ? trim($_POST['Draft']) : null;



                                                        $templete_created_date = !empty($_POST['templete_created_date']) ? trim($_POST['templete_created_date']) : null;

                                                        $Camp_sub = !empty($_POST['Camp_sub']) ? trim($_POST['Camp_sub']) : null;



                                                        $subscription_draft = !empty($_POST['Draft']) ? trim($_POST['Draft']) : null;
                                                        $templete_created_date = !empty($_POST['templete_created_date']) ? trim($_POST['templete_created_date']) : null;

                                                        //url_block_type = "sys-defined"
                                                        if ($_SESSION['url_block_type'] == "sys-defined") {
                                                            $stmt_url = $conn->prepare("SELECT url FROM blocked_url WHERE status='Active'");
                                                            $stmt_url->execute();
                                                            $urls = $stmt_url->fetchAll();
                                                        }
                                                        if ($_SESSION['url_block_type'] == "ou-dedicated" || $_SESSION['url_block_type'] == "ou-hybrid") {
                                                            $stmt_url = $conn->prepare("SELECT url FROM blocked_url_org WHERE status='Active'");
                                                            $stmt_url->execute();
                                                            $urls = $stmt_url->fetchAll();
                                                        }




                                                        $flag = 0;



                                                        foreach ($urls as $url) {

                                                            if (stripos($subscription_draft, $url['url']) !== false) {
                                                                //url found in draft
                                                                $flag = 1;
                                                                break;
                                                            }
                                                        }

                                                        //permenantly blocked URLs

                                                        $stmt_perm = $conn->prepare("SELECT url FROM permanently_blocked_url WHERE status='Active'");
                                                        $stmt_perm->execute();
                                                        $purls = $stmt_perm->fetchAll();


                                                        foreach ($purls as $url) {

                                                            if (stripos($subscription_draft, $url['url']) !== false) {
                                                                //url found in draft
                                                                $flag = 1;
                                                                break;
                                                            }
                                                        }

                                                        if ($flag == 0) {


                                                            $insert_draft_query = "INSERT into draft 
                                        (   
                                            subscription_draft, 
                                            draft_subject,
                                            CampID, 
                                            templete_created_date 
                                            -- AdminID
                                        
                                        ) 
                                values (
                                            '" . htmlentities(addslashes($subscription_draft)) . "',
                                            '" . htmlentities(addslashes($Camp_sub)) . "',
                                            '" . $CampID . "', 
                                            NOW()
                                                
                                        )";


                                                            $stmtt = $conn->prepare($insert_draft_query);
                                                            $result1 = $stmtt->execute();

                                                            if ($result1 == true) {
                                                                //$CampID = $_POST['CampID'];
                                                                // Update record in activity table
                            //                                     $sql = "UPDATE `activity` SET `add_draft_activity` = 1 
                            // WHERE `CampID` = :CampID";

                            //                                     $stmt = $conn->prepare($sql);
                            //                                     $stmt->bindValue(':CampID', $CampID);
                            //                                     $result = $stmt->execute();


                                                                // $CampID = $_POST['CampID'];
                                                                // Update record in Campaign table
                                                                $sql = "UPDATE `campaign` SET `draft_status` = 'subscriptionDraft' 
                            WHERE `CampID` = :CampID";

                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->bindValue(':CampID', $CampID);
                                                                $result = $stmt->execute();



                        //                                         echo "<br/><br/><div class='alert alert-success'><strong>New Draft Successfuly Added</strong>
                        // </div><meta http-equiv='refresh' content='0;url=index.php''>";
                        // header('Location: index.php');
                        // exit;
                                                            }
                                                        } else {

                                                            echo "<div class='alert alert-danger' role='alert'>
                                            Yout draft has blocked URLs which are not allowed to use.
                                             Please remove them and try again.
                   
                                            </div>
                    <a href='edit_Campaign2.php?CampID=" . $CampID . "'>
                <button class='mr-2 mb-2 btn btn-info btn-md' style='float:right;' type='button'>Try Again</button></a>";
                                                            die();
                                                        }
                                                    }

                                                    //---------------------------------------- [ Draft end ] -------------------------------------------------------//



                                                    $already = "<br/><br/><div class='alert alert-success'><strong>New Campaign Successfuly Added</strong>
                                                    </div>
                                                
                                                    ";     //<meta http-equiv='refresh' content='2;url=index.php'>
                                                    //echo <a href='addCampaign.php'><button type='button' class='btn btn-primary' style='float:right;'>Back</button></a> 
                                                    header('Location: index.php');
                                                    exit;
                                                }
                                            }
                                        }
                                    } catch (PDOException $e) {
                                        echo $sql . "<br>" . $e->getMessage();
                                    }

                                    //$conn = null;
                                    ?>


                                </div>

                                <div class="body">
                                    <div class="row ">
                                    <div class="col-lg-12">
                                      

                                            <form method="post" action="addCampaign.php" enctype="multipart/form-data">
                                            <div class="row ">
                                            <div class="col-lg-5">

                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="CampType">Campaign Type:</label>
                                                    </div>
                                                    <select class="custom-select" id="ctype_id" name="ctype_id" required>
                                                        <!-- <option value="Other" selected> Other</option> -->
                                                        <option value="" disabled selected>Please Select Campaign Type</option>
                                                        <?php $orgunit_id = $_SESSION['orgunit_id']; $products=[];
                                                        $ctype_query = "SELECT * from Campaign_type inner join tbl_orgunit_ctype 
                                                         on tbl_orgunit_ctype.ctype_id = Campaign_type.ctype_id where orgunit_id='$orgunit_id' ";
                                                        $ctype = $conn->prepare($ctype_query);
                                                        $ctype->execute();
                                                        while ($row = $ctype->fetch()) {
                                                            echo ' <option value="' . $row["ctype_id"] . '">' . $row["ctype_name"] . '</option>';
                                                        }
                                                        ?>


                                                    </select>

                                                </div>
                                      
                                                <div class="input-group  mb-3">
                                                    <div class="input-group-prepend">

                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Campaign Name:</span>
                                                    </div>
                                                    <input type="text" id="CampName" name="CampName" class="form-control" placeholder="Enter Campaign Name" aria-label="Small" aria-describedby="inputGroup-sizing-sm" required>
                                                </div>


                                                <?php if ($_SESSION['data_loading_type'] == "Both") { ?>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="CampCat">Campaign Category:</label>
                                                        </div>
                                                        <select class="custom-select" id="CampCat" name="CampCat" required>
                                                            <option value="Manual" selected> Manual</option>
                                                            <option value="Automatic"> Automatic</option>


                                                        </select>
                                                    </div>
                                                <?php } else if ($_SESSION['data_loading_type'] == "Manual") { ?>
                                                    <div class="input-group  mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroup-sizing-sm">Campaign Category:</span>
                                                        </div>
                                                        <input readonly type="text" id="CampCat" name="CampCat" class="form-control" aria-label="Small" value="<?php echo "Manual" ?>" aria-describedby="inputGroup-sizing-sm">
                                                    </div>


                                                <?php

                                                } else if ($_SESSION['data_loading_type'] == "Automatic") { ?>

                                                    <div class="input-group  mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroup-sizing-sm">Campaign Category:</span>
                                                        </div>
                                                        <input readonly type="text" id="CampCat" name="CampCat" class="form-control" aria-label="Small" value="<?php echo "Automatic" ?>" aria-describedby="inputGroup-sizing-sm">
                                                    </div>


                                                <?php
                                                }
                                                $admin_id = $_SESSION['AdminId'];
                                                $check = "SELECT r.restriction_level AS r_level
                                    FROM admin AS u INNER JOIN tbl_user_role_prev AS ur INNER JOIN tbl_role_privilege AS r
                                    ON
                                        u.AdminId = ur.user_id AND u.AdminId ='$admin_id' AND r.role_prev_id = ur.role_prev_id";

                                                //$check="SELECT role_prev_id from tbl_user_role_prev where user_id='$admin_id' ";   
                                                $check_super = $conn->prepare($check);
                                                $check_super->execute();
                                                $role_id = $check_super->fetch();
                                                // -----------------------------------------
                                                if ($role_id['r_level'] == '0') {
                                                    $orgunit_id = $_SESSION['orgunit_id'];
                                                    $temp_id = "SELECT max(tbl_orgunit_user.user_id) as user_id from tbl_user_role_prev inner join tbl_orgunit_user on tbl_orgunit_user.user_id=tbl_user_role_prev.user_id
 where (role_prev_id='2' or role_prev_id='8') and orgunit_id='$orgunit_id' AND ou_status='Active'";
                                                    $temp_uid = $conn->prepare($temp_id);
                                                    $temp_uid->execute();
                                                    $temp_user = $temp_uid->fetch();
                                                    if (!empty(trim($temp_user['user_id']))) {
                                                        $adminID = $temp_user['user_id'];
                                                    }
                                                }
                                                if (isset($_SESSION['orgunit_id'])) {
                                                    $orgunit_id = $_SESSION['orgunit_id'];
                                                    $sql = "SELECT mailservers.mailserverid, vmname, COUNT(CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' THEN campaign.mailserverid END ) as cnt
FROM  mailservers left join campaign on campaign.mailserverid = mailservers.mailserverid  
where mailservers.mailserverid in  

(SELECT mailservers.mailserverid FROM `mailserver-orgunit` INNER JOIN mailservers 
ON `mailserver-orgunit`.`mailserverid`= mailservers.mailserverid AND  orgunit_id= '$orgunit_id' AND mailservers.vmstatus='Active') 

 group by mailserverid order by cnt asc";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $mailservers = $stmt->fetchAll();


                                                    $stmtrr = $conn->prepare("SELECT tbl_role_privilege.role_prev_id
FROM tbl_role_privilege INNER JOIN tbl_user_role_prev
ON tbl_role_privilege.role_prev_id = tbl_user_role_prev.role_prev_id AND restriction_level=0 AND tbl_user_role_prev.user_id = $admin_id  ");
                                                    $stmtrr->execute();
                                                    if ($stmtrr->rowCount() > 0) {
                                                        $sql = "SELECT
    ro.rtemid AS rtemid
FROM
    tbl_organizational_unit AS o
LEFT JOIN tbl_orgunit_rte AS ro
ON
    o.orgunit_id = ro.orgunit_id
WHERE
    o.orgunit_id = $orgunit_id AND ro.rtemid NOT IN(
    SELECT
        rtemid
    FROM
        campaign
    WHERE
        Camp_Status != 'Completed' && Camp_Status!='Archive')
   ";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->execute();
                                                        $rep_to_emails = [];
                                                        while ($rep_to_emails_u = $stmt->fetch()) {

                                                            $rep_to_emails[] .= $rep_to_emails_u["rtemid"];
                                                        }
                                                    } else {
                                                        //Select reply to emails that are assigned to user && are not in use by any other user of any organization
                                                        //one reply to email can be assigned to multiple org and multiple users but used only when no other org/user is using it.
                                                        $sql = "SELECT  r.rtemid AS rtemid FROM admin AS u LEFT JOIN tbl_user_rte AS r ON u.AdminId = r.user_id WHERE u.AdminId= $admin_id
 AND rtemid NOT IN(SELECT rtemid FROM campaign WHERE Camp_Status<>'Completed' AND Camp_Status<>'Archive')
";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->execute();

                                                        while ($rep_to_emails_u = $stmt->fetch()) {

                                                            $rep_to_emails[] .= $rep_to_emails_u["rtemid"];
                                                        }
                                                    }
                                                }



                                                ?>


                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="rep_to_emails">Reply-to Email:</label>
                                                    </div>
                                                    <select class="custom-select" name="rep_to_emails" id="rep_to_emails" required>
                                                        <option value="" disabled selected>Please Select Reply-To-Email</option>
                                                        <?php foreach ($rep_to_emails as $rtem) {

                                                            //reply to email must be active in system as well as org
                                                            $stmtr = $conn->prepare("SELECT rtem_status, reply_to_email FROM reply_to_emails INNER JOIN tbl_orgunit_rte ON
                                                     reply_to_emails.rtemid=tbl_orgunit_rte.rtemid AND reply_to_emails.rtemid = :rtemid AND rtem_status='Active' AND status='Active'");
                                                            $stmtr->bindValue(':rtemid', $rtem);
                                                            $stmtr->execute();
                                                            $rowr = $stmtr->fetch();
                                                            if ($rowr['reply_to_email'] == "") {
                                                                continue;
                                                            }
                                                        ?>
                                                            <option value="<?php echo $rtem ?>"> <?php echo $rowr['reply_to_email'] ?> </option>
                                                        <?php
                                                        } ?>
                                                    </select>
                                                </div>


                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="mailservers">Mail Server:</label>
                                                    </div>
                                                    <?php
                                                    $query = "SELECT instance_email_send,max_camp_per_server_percentage from system_setting";
                                                    $sql_hours = $conn->prepare($query);
                                                    $sql_hours->execute();
                                                    $sr = $sql_hours->fetch();
                                                    //$emails_per_mailserver = $sr['instance_email_send'];
                                                    $campaigns_allowed = $sr['max_camp_per_server_percentage'];
                                                    $campaigns_allowed = (int)$campaigns_allowed;
                                                    //echo $campaigns_allowed;
                                                    ?>
                                                    <select class="custom-select" name="mailservers" id="mailservers" required>
                                                        <option value="" disabled selected>Please Select Mail Server</option>

                                                        <?php foreach ($mailservers as $output) {
                                                            $mailserver_campaigns = $output['cnt'];
                                                            $MS_name="Mailserver-".$output["mailserverid"];
                                                            $percent = round(($mailserver_campaigns / $campaigns_allowed) * 100);

                                                            if ($mailserver_campaigns >= $campaigns_allowed) { ?>
                                                                <option disabled value="<?php echo $output["mailserverid"]; ?>"> <?php echo $MS_name . " (" . $percent . "% busy)"; ?> </option>
                                                            <?php } else { ?>
                                                                <option value="<?php echo $output["mailserverid"]; ?>"> <?php echo $MS_name . " (" . $percent . "% busy)"; ?> </option>
                                                        <?php  }
                                                        } ?>
                                                    </select>
                                                </div>

                                                <!-- --------------------------------------------->
                                                <!-- After selecting Campaign type , on change funtion is called and responce is formed by ctype_ajax file.
 All the required optional input fields are formed using 'Components for campaigns type table' -->
                                                <!-------------------------------------------- -->







                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" for="CampFor">Campaign Objective:</span>
                                                    </div>
                                                    <textarea placeholder="Campaign For *" id="CampFor" name="CampFor" class="form-control" required aria-label="With textarea"></textarea>
                                                </div>


                                                <?php
                                                //this customizable_camp_embargo is from org-settings table the other one is in sys-setting to use as 
                                                //switch in cron
                                                //if customizable_camp_embargo is YES then allow choice between global and campaign wise
                                                //if global then get value from settings(ou or sys whatever setting says)
                                                //if customizable_camp_embargo is NO then by default global(from settings (ou or sys)) 
                                                $stmts2 = $conn->prepare("SELECT customizable_camp_embargo FROM `orgunit-systemsetting` WHERE status='Active' AND
                                                orgunit_id=:orgunit_id");
                                                $stmts2->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
                                                $stmts2->execute();
                                                $customizable_camp_embargo = $stmts2->fetch();
                                                if ($stmts2->rowCount() > 0) {
                                                    if ($customizable_camp_embargo['customizable_camp_embargo'] == "YES") {
                                                ?>


                                                        <div class="input-group input-group-sm mb-3">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" for="embargo_type">Embargo Type:</label>
                                                            </div>
                                                            <select class="custom-select" id="embargo_type" onChange=showHide() name="embargo_type">
                                                                <option value="global"> Global</option>
                                                                <option value="campaign_embargo"> Campaign Wise Embargo</option>

                                                            </select>
                                                        </div>

                                                        <div class="input-group input-group-sm mb-3">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" style="display: none" for="CE" id="CET">Embargo Duration (Days):</label>
                                                            </div>
                                                            <?php
                                                            $stmt_embargo = $conn->prepare("SELECT allowed_days FROM `embargotype_org` INNER JOIN embargotype ON embargotype.embargotype_id =  embargotype_org.embargotype_id AND 
                                                            `embargotype_org_status`='Active' AND embargotype_status='Active' AND `orgunit_id`=:orgunit_id");
                                                            $stmt_embargo->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
                                                            $stmt_embargo->execute();
                                                            $allowed_days = $stmt_embargo->fetchAll();
                                                            ?>
                                                            <select class="custom-select" style="display: none" id="CE" name="CE">

                                                                <?php foreach ($allowed_days as $days) { ?>
                                                                    <option value="<?php echo $days['allowed_days'] ?>"> <?php echo $days['allowed_days'] ?></option>
                                                                <?php } ?>

                                                            </select>
                                                        </div>

                                                    <?php } else { ?>
                                                        <input hidden name="embargo_type" value="global">
                                                    <?php } ?>

                                                    <?php if ($_SESSION['settings_type'] == 'sys-defined') { ?>
                                                        <input hidden type="text" name="CED" value="<?php echo     $_SESSION['embargo_duration']; ?>">

                                                    <?php } else { ?>

                                                        <input hidden type="text" name="CED" value="<?php echo     $_SESSION['org_embargo_duration']; ?>">

                                                <?php }
                                                }

                                                ?>

                                                <br>
                                                <?php if (isset($_SESSION['orgunit_id'])) { ?>
                                                    <button type="submit" name="addCampaign" class="btn btn-primary"><i class="fa fa-plus"></i> Add Campaign</button>
                                                <?php } else { ?>
                                                    <button disabled type="submit" name="addCampaign" class="btn btn-primary"><i class="fa fa-plus"></i> Add Campaign</button>


                                                <?php  } ?>
                                                <?php
                                                if (empty($already)) {
                                                    echo $already = "";
                                                } else
                                                    echo $already;
                                                    
                                                //echo $already;



?>
</div> <!-- -----col-5--- -->
 <div class="col-7">
                                                 <div id="response3"> </div>
                                       



                                            <br />

                                            </div>    <!-- col-7 end -->
                                   
                                            </div> <!-- class="row "-->
                                </form>
                                      
                                
                                </div>
                                </div> <!-- ---row end-- -->

                                </div>

                         </div> 
                    </div>
                </div>

                        <div class="row">

                            <div class="col-12">
                                <div class="card">    


                                <div class="col-md-12">



                                  
                                    <?php
                                   
                                 
                                    $message = "<table width='799' height='430' border='0' align='center'>

<tr>		
<td>  <p class='order0'><img  id='order0' /></p> </td>
</tr>
<tr>		
<td>  <p class='order1' id='order1'>  </p> </td>
</tr>
<tr>		
<td>  <h2 class='order2' id='order2'>  </h2> </td>
</tr>

<tr>	
<td align='center'><span style='font-size:14px;text-align: justify;'>	  <p  class='order3' id=''> </p></span>	</td>
</tr>  
<tr>		
<td>  <p id='responselist'>  </p> 
<div  class='order4'  ></div>
</td>
</tr>

";
                                    $message_oaarticles = $message . "

<tr>
<td> <table width='801' height='10' border='0' valign='top'>
        <tr>
            <td> <p class='order5' > <img id=''  /></p>
                    <!--<img src='https://benthamarticlealerts.com/img/footer.jpg' />--> </td>
        </tr>
 </table>
</td>	
</tr>	
                          
</table>";


                                  //  echo $message_oaarticles;
                                   
                                    //echo $alerts;
                                    ?>
                                </div>


                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>

    <!-- Javascript -->
    <script>
        function showHide() {


            var embargo_type = document.getElementById("embargo_type").value;

            if (embargo_type == "campaign_embargo") {
                document.getElementById('CE').style.display = 'block'
                document.getElementById('CET').style.display = 'block'
            } else {
                document.getElementById('CE').style.display = 'none'
                document.getElementById('CET').style.display = 'none'
            }
        }
    </script>

    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>
    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"></script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>





   

   




    <!-- Javascript -->


    <script src="assets/vendor/ckeditor/ckeditor.js"></script> <!-- Ckeditor -->
    <script src="assets/js/pages/forms/editors.js"></script>
    <script src="assets/vendor/dropify/js/dropify.min.js"></script>
    <script src="assets/js/pages/forms/dropify.js"></script>


    <script>
        $('#ctype_id').on('change', function() {
            //alert("changed");
            var ead = $(this).val();
            //alert(ead);
            //             var e = document.getElementById("ddlViewBy");
            // var strUser = e.value;

            $.ajax({
                url: "ctype_ajax.php",
                type: "POST",
                data: {
                    ead: ead
                }

            }).done(function(data) {
//alert("done");
                $("#response3").html(data);
            });

        });
    </script>


   

    <script>
        var loadFile1 = function(event) {
            var image = document.getElementById('output1');
            image.src = URL.createObjectURL(event.target.files[0]);
        };

        var loadFile2 = function(event) {
            var image = document.getElementById('output2');
            image.src = URL.createObjectURL(event.target.files[0]);
        };

        var loadFile3 = function(event) {
            var image = document.getElementById('output3');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>

    <script src="index.js"></script>
    <!-- Session timeout js -->
    <script>
        $(document).ready(function() {
            var str = window.location.href
            const urlarray = str.split("/");
            var lastItem = urlarray.pop();
            // const ulrarray2= lastItem.split('?');
            // var firstItem= ulrarray2[0];
            document.getElementById("page_name").value = lastItem;

            $.sessionTimeout({
                keepAliveUrl: "pages-starter.html",
                logoutUrl: "logout.php",
                redirUrl: "logout.php",
                warnAfter: <?php echo $_SESSION['timeout']; ?>,
                redirAfter: <?php echo $_SESSION['timeout'] + 15000; ?>,
                countdownMessage: "Redirecting in {timer} seconds."
            });
        });
    </script>




</body>

</html>