<?php
ob_start();
session_start();
include 'include/conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
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


      

        <!---Add code here-->
        <div id="main-content">
            <div class="container-fluid">

                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Dashboard</h2>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="block-header">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h3>ACTIONS</h3>
                                </div>



                                <?php

                                    // ------------------------------------------------ [ Draft Area ] ----------------------------------------------------- //

                                    //Upload Draft Letter Data
                                    if (isset($_POST["submitDraft"])) {
                                        $CampID = $_POST['CampID'];
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
                            //                 echo ("<br/><br/><div class='alert alert-danger' role='alert'><strong>Templete Draft already exists!</strong></div>");
                            //                 echo   "<div class='element-box-content'>
                            //     <a href='viewDraft.php?CampID=" . $_POST['CampID'] . "'>
                            //     <button class='mr-2 mb-2 btn btn-primary btn-md' type='button'>Please Check Your Draft!</button></a>
                            // </div>";
                            //                 die();
                                        }

                                        $subscription_draft = !empty($_POST['subscription_draft']) ? trim($_POST['subscription_draft']) : null;

                                        $draft_subject = !empty($_POST['Camp_sub']) ? trim($_POST['Camp_sub']) : null;

                                        $templete_created_date = !empty($_POST['templete_created_date']) ? trim($_POST['templete_created_date']) : null;





                                        $subscription_draft = !empty($_POST['subscription_draft']) ? trim($_POST['subscription_draft']) : null;
                                        $draft_subject = !empty($_POST['Camp_sub']) ? trim($_POST['Camp_sub']) : null;
                                        $templete_created_date = !empty($_POST['templete_created_date']) ? trim($_POST['templete_created_date']) : null;

                                          //url_block_type = "sys-defined"
  if($_SESSION['url_block_type']=="sys-defined"){
    $stmt_url=$conn->prepare("SELECT url FROM blocked_url WHERE status='Active'");
    $stmt_url->execute();
    $urls=$stmt_url->fetchAll(); 




}
if($_SESSION['url_block_type']=="ou-dedicated" || $_SESSION['url_block_type']=="ou-hybrid"){
    $stmt_url=$conn->prepare("SELECT url FROM blocked_url_org WHERE status='Active'");
    $stmt_url->execute();
    $urls=$stmt_url->fetchAll(); 



}
$flag=0;

    

foreach ($urls as $url) {
    
    if (stripos($subscription_draft,$url['url'])!==false) {
        //url found in draft
        $flag=1;
        break;
    }
}
 //permenantly blocked URLs
                                     
 $stmt_perm=$conn->prepare("SELECT url FROM permanently_blocked_url WHERE status='Active'");
 $stmt_perm->execute();
 $purls=$stmt_perm->fetchAll(); 


foreach ($purls as $url) {

if (stripos($subscription_draft,$url['url'])!==false) {
    //url found in draft
    $flag=1;
    break;
}
}

                                        if ($flag==0) {


                                //             $insert_draft_query = "INSERT into draft 
                                //         (   
                                //             subscription_draft, 
                                //             CampID, 
                                //             templete_created_date 
                                //             -- AdminID
                                        
                                //         ) 
                                // values (
                                //             '" . htmlentities(addslashes($subscription_draft)) . "',
                                //             '" . $CampID . "', 
                                //             NOW()
                                                
                                //         )";

$htmldraft=htmlentities(addslashes($subscription_draft));
$htmldraft_sub=htmlentities(addslashes($draft_subject));
$date=date('Y-m-d'); echo $date;

                                        $insert_draft_query = "UPDATE draft 
                                        SET   
                                            
                                        subscription_draft= '$htmldraft',
                                        draft_subject= '$htmldraft_sub',
                                            CampID = '$CampID', 
                                            templete_created_date = Now()
                                    
                                            WHERE `CampID` = :CampID
                                        ";



                                            $stmtt = $conn->prepare($insert_draft_query);
                                            $stmtt->bindValue(':CampID', $CampID);
                                            $result1 = $stmtt->execute();

                                            if ($result1 == true) {
                                                $CampID = $_POST['CampID'];
                                                // Update record in activity table
                                                $sql = "UPDATE `activity` SET `add_draft_activity` = 1 
                            WHERE `CampID` = :CampID";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindValue(':CampID', $CampID);
                                                $result = $stmt->execute();


                                                $CampID = $_POST['CampID'];
                                                // Update record in Campaign table
                                                $sql = "UPDATE `campaign` SET `draft_status` = 'subscriptionDraft' 
                            WHERE `CampID` = :CampID";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindValue(':CampID', $CampID);
                                                $result = $stmt->execute();


                        //  echo "<br/><br/><div class='alert alert-success'><strong>New Draft Successfuly Added</strong>
                        // </div><meta http-equiv='refresh' content='1;url=viewDraft.php?CampID=" . $_POST['CampID'] . "''>";
                        echo "<br/><br/><div class='alert alert-success'><strong>Draft Successfuly Edited</strong>
                        </div><meta http-equiv='refresh' content='1;url=index.php''>";
                                            }
                                        } else {

                                            echo "<div class='alert alert-danger' role='alert'>
                                            Yout draft has blocked URLs which are not allowed to use.
                                            Please remove them and try again.
                 
              </div>
                    <a href='Edit_draft.php?CampID=" . $_POST['CampID'] . "'>
                <button class='mr-2 mb-2 btn btn-info btn-md' style='float:right;' type='button'>Try Again</button></a>";
                                            die();
                                        }
                                    }
                                


                                ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->

        <!---Add code here-->



        <!--      
        </div>
    </div> -->

    </div>

    <!-- Javascript -->
    <!-- Javascript -->


    <script src="assets/vendor/ckeditor/ckeditor.js"></script> <!-- Ckeditor -->
    <script src="assets/js/pages/forms/editors.js"></script>


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

</body>

</html>