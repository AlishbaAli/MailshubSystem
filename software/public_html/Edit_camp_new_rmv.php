<?php
ob_start();
session_start();
//  error_reporting(E_ALL);
//  ini_set('display_errors', 1);

error_reporting(0);
ini_set('display_errors', 0);


include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['AC'])) {
    if ($_SESSION['AC'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}
$CampID = $_GET['CampID'];
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
                                <form method="post" action="Edit_camp_new.php" enctype="multipart/form-data">
                                    <div class="header">
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <h3>Edit Campaign</h3>
                                            </div>
                                            <!-- <div class="col-lg-7"> <h3>Add Draft Here</h3> </div> -->
                                        </div>
                                        <?php
                                        echo $already = "";








                                        $sql = "SELECT * FROM products";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $products = $stmt->fetchAll();


                                        $stmts = $conn->prepare("SELECT embargo_duration FROM `system_setting`");
                                        $stmts->execute();
                                        $embargo_duration = $stmts->fetch();
                                        $embargo_duration = $embargo_duration["embargo_duration"];


                                        if (isset($_SESSION['orgunit_id'])) {
                                            $orgunit_id = $_SESSION['orgunit_id'];
                                            $stmts = $conn->prepare("SELECT * FROM tbl_organizational_unit WHERE orgunit_id=:orgunit_id");
                                            $stmts->bindValue(':orgunit_id', $orgunit_id);
                                            $stmts->execute();
                                            $org = $stmts->fetch();

                                            if (trim($org["system_setting"]) == "self") {
                                                $stmts = $conn->prepare("SELECT org_embargo_duration FROM `orgunit-systemsetting` WHERE orgunit_id=:orgunit_id");
                                                $stmts->bindValue(':orgunit_id', $orgunit_id);
                                                $stmts->execute();
                                                $embargo_duration = $stmts->fetch();
                                                $embargo_duration = $embargo_duration["org_embargo_duration"];
                                            }
                                        }









                                        try {
                                            if (isset($_POST['editCampaign'])) {

                                                //Retrieve the field values from our registration form.
                                                $format_type = !empty($_POST['format']) ? trim($_POST['format']) : null;
                                                $rtemid = !empty($_POST['rep_to_emails']) ? trim($_POST['rep_to_emails']) : null;
                                                $mailserverid = !empty($_POST['mailservers']) ? trim($_POST['mailservers']) : null;
                                                $productid = !empty($_POST['products']) ? trim($_POST['products']) : null;
                                                $CampName = !empty($_POST['CampName']) ? trim($_POST['CampName']) : null;
                                                // $CampDate = !empty($_POST['CampDate']) ? trim($_POST['CampDate']) : null;
                                                $Camp_category = !empty($_POST['CampCat']) ? trim($_POST['CampCat']) : null;
                                                $CampDate = date('Y-m-d');
                                                $embargo_type = !empty($_POST['embargo_type']) ? trim($_POST['embargo_type']) : null;
                                                $campaign_embargo_days = !empty($_POST['CE']) ? trim($_POST['CE']) : null;
                                                $camptype = !empty($_POST['CampType']) ? trim($_POST['CampType']) : null;
                                                $campfor = !empty($_POST['CampFor']) ? trim($_POST['CampFor']) : null;
                                                $CampID = !empty($_POST['CampID']) ? trim($_POST['CampID']) : null;
                                                $AdminID = !empty($_POST['AdminID']) ? trim($_POST['AdminID']) : null;

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
                                                $Camp_Status = "Inactive";


                                                if ($embargo_type == "global") {
                                                    $campaign_embargo_days = $_POST['CED'];
                                                }



                                                $image_file = "";



                                                //echo $CampID;

                                                $sql = "UPDATE campaign SET  CampName='$CampName',
                                                     Camp_category = '$Camp_category',
                                                      CampType= :CampType,
                                                       CampFor=:CampFor,
                                                        CampDate=:CampDate,
                                                        
                                                          Camp_Status=:Camp_Status,
                                                            Camp_Created_Date=  Now(), 
                                                             rtemid= :rtemid,  
                                                             mailserverid=:mailserverid,
                                                              productid=  :productid, 
                                                              format_type=:format_type,
                                                              embargo_type=:embargo_type,
                                                               campaign_embargo_days= :campaign_embargo_days 
                                                    where CampID=:CampID";


                                                $stmt = $conn->prepare($sql);

                                                //Bind our variables.
                                                $stmt->bindValue(':rtemid', $rtemid);
                                                $stmt->bindValue(':mailserverid', $mailserverid);
                                                $stmt->bindValue(':productid', $productid);
                                                // $stmt->bindValue(':CampName', $CampName);
                                                //$stmt->bindValue(':Camp_category', $Camp_category);
                                                $stmt->bindValue(':CampType', $camptype);
                                                $stmt->bindValue(':CampFor', $campfor);
                                                $stmt->bindValue(':CampDate', $CampDate);
                                                // $stmt->bindValue(':AdminID', $AdminID);
                                                $stmt->bindValue(':Camp_Status', $Camp_Status);
                                                $stmt->bindValue(':format_type', $format_type);
                                                $stmt->bindValue(':embargo_type', $embargo_type);
                                                $stmt->bindValue(':campaign_embargo_days', $campaign_embargo_days);
                                                $stmt->bindValue(':CampID', $CampID);




                                                //Execute the statement and insert the new account.
                                                $result = $stmt->execute();

                                                //If the signup process is successful.
                                                if ($result > 0) {


                                                    // Insert record in activity table
                                                    // $CampID = $conn->lastInsertId();
                                                    // $AdminId = $_SESSION['AdminId'];

                                                    $sql = "INSERT INTO Campaign_flow (CampID, Camp_Status) 
                                                        VALUES (:CampID, 'Edit Campaign')";

                                                    $stmt = $conn->prepare($sql);

                                                    $stmt->bindValue(':CampID', $CampID);
                                                    // $stmt->bindValue(':Camp_Status', $Camp_Status);

                                                    $result = $stmt->execute();
                                                    if ($result > 0) {
                                                    }

                                                    // $sql = "INSERT INTO activity (author_activity, CampID, AdminId) 
                                                    //     VALUES (1, :CampID, :AdminId)";

                                                    // $stmt = $conn->prepare($sql);

                                                    // $stmt->bindValue(':CampID', $CampID);
                                                    // $stmt->bindValue(':AdminId', $AdminId);
                                                    // $result = $stmt->execute();
                                                    // if ($result > 0) {
                                                    // }




                                                    $already = "<br/><br/><div class='alert alert-success'><strong>Campaign Successfuly Edited</strong>
                                                    </div>
                                                    <meta http-equiv='refresh' content='2;url=index.php'>
                                                    ";
                                                    //echo <a href='addCampaign.php'><button type='button' class='btn btn-primary' style='float:right;'>Back</button></a> 

                                                }
                                            }
                                        } catch (PDOException $e) {
                                            echo $sql . "<br>" . $e->getMessage();
                                        }

                                        //$conn = null;

                                        ?>
                                        <?php
                                        $org = [];
                                        if (isset($_GET['CampID'])) {
                                            $CampID = $_GET['CampID'];
                                        }

                                        $con = "SELECT *, ou.user_id as AdminID FROM campaign c, tbl_orgunit_user ou where c.ou_id=ou.ou_id and  CampID ='$CampID' AND ou_status='Active'";
                                        $val = $conn->prepare($con);
                                        $val->execute();
                                        $valc = $val->fetch();
                                        $Camp_name = $valc['CampName'];
                                        $campcat = $valc['Camp_category'];
                                        $CampID = $valc['CampID'];
                                        $AdminID = $valc['AdminID'];

                                        ?>

                                    </div>

                                    <div class="body">
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="input-group  mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Campaign Name:</span>
                                                    </div>
                                                    <input type="text" id="CampName" name="CampName" class="form-control" placeholder="Enter Campaign Name" value="<?php echo $Camp_name; ?>" aria-label="Small" aria-describedby="inputGroup-sizing-sm" required>
                                                </div>
                                                <input type="text" hidden id="CampID" name="CampID" class="form-control" value="<?php echo $CampID; ?>" required>
                                                <input type="text" hidden id="AdminID" name="AdminID" class="form-control" value="<?php echo $AdminID; ?>" required>
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="CampCat">Campaign Category:</label>
                                                    </div>
                                                    <select class="custom-select" id="CampCat" name="CampCat" required>
                                                        <?php if ($campcat == 'Manual') { ?>
                                                            <option value="Manual" selected> Manual</option>
                                                            <option value="Automatic"> Automatic</option>
                                                        <?php }
                                                        if ($campcat == 'Automatic') { ?>
                                                            <option value="Manual"> Manual</option>
                                                            <option value="Automatic" selected> Automatic</option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                                <?php

                                                $admin_id = $_SESSION['AdminId'];

                                                if (isset($_SESSION['orgunit_id'])) {
                                                    $orgunit_id = $_SESSION['orgunit_id'];
                                                    $sql = "SELECT mailservers.mailserverid, vmname, COUNT(CASE WHEN Camp_Status='Active' or Camp_Status= 'Stop' THEN campaign.mailserverid END ) as cnt
FROM  mailservers left join campaign on campaign.mailserverid = mailservers.mailserverid  
where mailservers.mailserverid in  

(SELECT mailservers.mailserverid FROM `mailserver-orgunit` INNER JOIN mailservers 
ON `mailserver-orgunit`.`mailserverid`= mailservers.mailserverid AND  orgunit_id= $orgunit_id) 

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
        Camp_Status != 'Completed')
   ";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->execute();
                                                        $rep_to_emails = [];
                                                        while ($rep_to_emails_u = $stmt->fetch()) {

                                                            $rep_to_emails[] .= $rep_to_emails_u["rtemid"];
                                                        }
                                                    } else {

                                                        $sql = "SELECT  r.rtemid AS rtemid FROM admin AS u LEFT JOIN tbl_user_rte AS r ON u.AdminId = r.user_id WHERE u.AdminId= $admin_id
 AND rtemid NOT IN(SELECT rtemid FROM campaign WHERE Camp_Status!='Completed')
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

                                                        <?php $rtemid =  $valc['rtemid'];
                                                        $rte = "SELECT * from reply_to_emails where rtemid = '$rtemid' ";
                                                        $rtem = $conn->prepare($rte);
                                                        $rtem->execute();
                                                        $rtemm = $rtem->fetch();
                                                        ?>
                                                        <option value="<?php echo $rtemid; ?>" selected><?php echo $rtemm['reply_to_email']; ?></option>
                                                        <?php foreach ($rep_to_emails as $rtem) {


                                                            $stmtr = $conn->prepare("SELECT rtem_status, reply_to_email FROM reply_to_emails WHERE rtemid =:rtemid AND rtem_status='Active'");
                                                            $stmtr->bindValue(':rtemid', $rtem);
                                                            $stmtr->execute();
                                                            $rowr = $stmtr->fetch();
                                                            if ($rowr['reply_to_email'] == "") {
                                                                continue;
                                                            }
                                                        ?>
                                                            <option value="<?php echo $rtem; ?>"> <?php echo $rowr['reply_to_email'] ?> </option>
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
                                                    $emails_per_mailserver = $sr['instance_email_send'];
                                                    $campaigns_allowed = $sr['max_camp_per_server_percentage'];
                                                    $campaigns_allowed = (int)$campaigns_allowed;
                                                    //echo $campaigns_allowed;
                                                    ?>
                                                    <select class="custom-select" name="mailservers" id="mailservers" required>

                                                        <?php $msid =  $valc['mailserverid'];
                                                        $rte = "SELECT * from mailservers where mailserverid = '$msid' ";
                                                        $rtem = $conn->prepare($rte);
                                                        $rtem->execute();
                                                        $msrid = $rtem->fetch();
                                                        ?>
                                                        <option selected value="<?php echo $msid; ?>" selected><?php echo $msrid['vmname']; ?></option>

                                                        <?php foreach ($mailservers as $output) {
                                                            $mailserver_campaigns = $output['cnt'];
                                                            $percent = round(($mailserver_campaigns / $campaigns_allowed) * 100);

                                                            if ($mailserver_campaigns >= $campaigns_allowed) {

                                                                if ($msid == $output["mailserverid"]) { ?>
                                                                    <option disabled selected value="<?php echo $output["mailserverid"]; ?>"> <?php echo $output["vmname"] . " (" . $percent . "% busy)"; ?> </option>
                                                                <?php  } else { ?>
                                                                    <option disabled value="<?php echo $output["mailserverid"]; ?>"> <?php echo $output["vmname"] . " (" . $percent . "% busy)"; ?> </option>
                                                                <?php }
                                                            } else {
                                                                if ($msid == $output["mailserverid"]) { ?>
                                                                    <option selected value="<?php echo $output["mailserverid"]; ?>"> <?php echo $output["vmname"] . " (" . $percent . "% busy)"; ?> </option>
                                                                <?php  } else { ?>
                                                                    <option value="<?php echo $output["mailserverid"]; ?>"> <?php echo $output["vmname"] . " (" . $percent . "% busy)"; ?> </option>
                                                        <?php }
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>








                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="CampType">Campaign Type:</label>
                                                    </div>
                                                    <select class="custom-select" id="ctype_id" name="ctype_id" required>
                                                        <!-- <option value="Other" selected> Other</option> -->

                                                        <?php
                                                        $cctn =  $valc['ctype_id'];
                                                        $ctype_query = "SELECT ctype_name from Campaign_type where ctype_status='Active' AND ctype_id='$cctn'";
                                                        $ctype = $conn->prepare($ctype_query);
                                                        $ctype->execute();
                                                        $ctype->fetch();
                                                        echo ' <option selected value="' . $cctn . '">' . $ctype['ctype_name'] . '</option>';
                                                        // while ($row = ) {
                                                        //     if ($cctn == $row["ctype_name"]) {
                                                        //     } else {
                                                        //         echo ' <option value="' . $row["ctype_name"] . '">' . $row["ctype_name"] . '</option>';
                                                        //     }
                                                        // }
                                                        ?>
                                                        <!-- <option value="Subscriber"> Subscriber</option>
                                                <option value="Trial"> Trial</option>
                                                <option value="Journal General Articles"> Journal General Articles</option>
                                                <option value="Journal Promotion"> Journal Promotion</option>
                                                <option value="Journal Thematic Issues"> Journal Thematic Issues</option>
                                                <option value="Book Authors"> Book Authors</option>
                                                <option value="Book editors"> Book editors</option>
                                                <option value="Reviewer"> Reviewer</option>
                                                <option value="Journal Board Members"> Journal Board Members</option>
                                                <option value="Journal Executive Guest Editors"> Journal Executive Guest Editors</option>
                                                <option value="Journal Section Editors"> Journal Section Editors</option>
                                                <option value="Journal Editors"> Journal Editors</option>
                                                <option value="Journal Associate Editors"> Journal Associate Editors</option> -->

                                                    </select>
                                                </div>



                                                <!-- <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="products">Products:</label>
                                </div>
                                <select class="custom-select" name="products" id="products" required>
                                       <option value="" disabled selected>Please Select Product</option>
                                                <?php foreach ($products as $output) { ?>
                                                    <option value="<?php echo $output["productid"]; ?>"> <?php echo $output["product_name"]; ?> </option>
                                                <?php
                                                } ?>
                                </select>
                            </div> -->


                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" for="CampFor">Campaign Objective:</span>
                                                    </div>
                                                    <textarea placeholder="Campaign For *" id="CampFor" name="CampFor" class="form-control" required aria-label="With textarea"><?php echo $valc['CampFor']; ?></textarea>
                                                </div>


                                                <?php if (isset($_SESSION['ET'])) {
                                                    if (($_SESSION['ET'] == "YES") and  ($org != NULL) and ($org["system_setting"] == "self")) { ?>


                                                        <div class="input-group input-group-sm mb-3">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" for="embargo_type">Embargo Type:</label>
                                                            </div>
                                                            <select class="custom-select" id="embargo_type" onChange=showHide() name="embargo_type">
                                                                <?php if ($valc['embargo_type'] == 'global') {  ?>
                                                                    <option selected value="global"> Global</option>
                                                                    <option value="campaign_embargo"> Campaign Wise Embargo</option>
                                                                <?php } ?>
                                                                <?php if ($valc['embargo_type'] == 'campaign_embargo') {  ?>
                                                                    <option selected value="global"> Global</option>
                                                                    <option value="campaign_embargo"> Campaign Wise Embargo</option>
                                                                <?php } ?>

                                                            </select>
                                                        </div>


                                                        <div class="input-group input-group-sm mb-3">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" style="display: none" for="CE" id="CET">Embargo Duration (Days):</label>
                                                            </div>
                                                            <select class="custom-select" style="display: none" id="CE" name="CE">
                                                                <option value="7"> 7</option>
                                                                <option value="15"> 15</option>
                                                                <option value="30" selected> 30</option>
                                                                <option value="45"> 45</option>
                                                                <option value="60"> 60</option>
                                                                <option value="90"> 90</option>

                                                            </select>
                                                        </div>

                                                        <input hidden type="text" name="CED" value="<?php echo $embargo_duration; ?>">

                                                    <?php             } else {
                                                    ?> <input hidden name="embargo_type" value="global">
                                                        <input hidden type="text" name="CED" value="<?php echo $embargo_duration; ?>">

                                                <?php                                        }
                                                } ?>
                                                <br> <button type="submit" name="editCampaign" class="btn btn-primary">Edit Campaign</button>
                                                <?php
                                                if (empty($already)) {
                                                    echo $already = "";
                                                } else
                                                    echo $already;
                                                //echo $already;

                                                ?>
                                            </div> <!-- -----col-5--- -->

                                            <!-- <div class="col-7">
<div class="input-group  mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Draft Subject:</span>
                                </div>
                               <input type="text" id="Camp_sub" name="Camp_sub" class="form-control" placeholder="Enter Campaign Subject" aria-label="Small" aria-describedby="inputGroup-sizing-sm" required>
                            </div> 
                                    <textarea id="ckeditor" name="subscription_draft" rows="20" required></textarea>
                                 
                                    <br />
                                 
</div> -->
                                        </div> <!-- ---row end-- -->

                                    </div>

                                </form>
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
    <script src="index.js"></script>

    <!-- Session timeout js -->
    <script>
        $(document).ready(function() {
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