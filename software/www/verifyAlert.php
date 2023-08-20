<?php        ob_start();
        session_start();

        error_reporting(E_ALL);
  ini_set('display_errors', 1);
        include 'include/conn.php';

        if (!isset($_SESSION['AdminId'])) {
            //User not logged in. Redirect them back to the login page.
            header('Location: login.php');
            exit;
        }
        if (isset($_SESSION['AC'])) {
            if ($_SESSION['AC'] == "NO") {

                //User not logged in. Redirect them back to the login page.
                header('Location: page-403.html');
                exit;
            }
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

                <!---Add code here-->

                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h3>ACTIONS</h3>
                        </div>
                        <div class="element-box">

                            <h5 class="form-header">

                                <?php

                                if (isset($_GET['CampID'])) {
                                     $CampID =  $_GET['CampID'];
                                    $stmt = $conn->prepare("SELECT draft_status as draft_type from campaign WHERE CampID  = :CampID");
                                    $stmt->bindValue(':CampID', $CampID);
                                    $stmt->execute();
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $draft_type = $result['draft_type'];

                                    //For Buttons;
                                    $CampID =  $_GET['CampID'];
                                    echo "<input hidden type='text' id= 'CampID' name='CampID' value=$CampID >";

                                    $sql = "SELECT COUNT(CampName) AS num , CampName, CampID
												FROM campaign 
												WHERE CampID = :CampID";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindValue(':CampID', $CampID);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($row['num'] > 0) {
                                        echo "Verify Alert For <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign<br/><br/>";
                                        // echo "	<a  style='float:right' href='deleteDraft.php?CampID=" . $row["CampID"] . "'><button id='discard' onclick='return deleteall();' disabled='true' class='mr-2 mb-2 btn btn-danger'>Discard</button> </a>";
                                        // echo "	<a  style='float:right' href='nextVerifyAlert.php?CampID=" . $row["CampID"] . "'><button id='activity' disabled='true' class='mr-2 mb-2 btn btn-success'>Next Activity</button> </a>";
                                    } else {
                                        echo "Invalid Selection";
                                    }


                                        //change status to pending verification alert

                                        // $stmt_update_status = $conn->prepare("UPDATE `campaign` 
										// SET `Camp_Status` = 'Pending Verification Alert'
										// WHERE CampID = $CampID AND `Camp_Status`!='Verified'");

                                    $stmt_update_status = $conn->prepare("UPDATE `campaign` 
                                        SET `Camp_Status` = 'Pending Verification Alert'
                                        WHERE CampID = $CampID AND (`Camp_Status`='Inactive' or `Camp_Status`='Rejected')");
                                   if( $stmt_update_status->execute()){
                                    // Script Subscription Draft Email

        // Update record in activity table
        $sql = "UPDATE `activity` SET `verification_activity` = 1 
                        WHERE `CampID` = :CampID";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':CampID', $CampID);
        $result_1 = $stmt->execute();

        // record camp flow

        $status='Pending Verification Alert';
        $q="SELECT * FROM Campaign_flow WHERE CampID = :CampID and Camp_Status =:Camp_Status ";
        $st = $conn->prepare($q);
            
                        $st->bindValue(':CampID', $CampID);
                        $st->bindValue(':Camp_Status', $status);
                        
                        $re = $st->execute();
                        if ($st->rowCount() > 0) {
                        } else {
        $sql = "INSERT INTO Campaign_flow (CampID, Camp_Status) 
                            VALUES (:CampID, :Camp_Status)";
            
                        $stmt = $conn->prepare($sql);
            
                        $stmt->bindValue(':CampID', $CampID);
                        $stmt->bindValue(':Camp_Status', $status);
                        
                        $result = $stmt->execute();
                        if ($result > 0) {
                        } 
                    }
    }


    $article_title = 'article_title';
    $Journal_title = 'Journal_title';
    $first_name='Fname';
    $last_name='Lastname';
                                //     $sql_get = "SELECT
                                //     ca.Fname,
                                //     ca.Lastname,
                                //     ca.Journal_title,
                                //     ca.article_title,
                                //     c.CampName,
                                //     c.mailserverid,
                                //     d.subscription_draft
                                // FROM
                                //      campaign c, draft d, campaingauthors ca
                                // WHERE
                                // ca.CampID = c.CampID AND d.CampID = c.CampID AND
                                //     c.CampID = :CampID";

                                $sql_get = "SELECT
                               
                                     c.CampName,
                                     c.mailserverid,
                                    d.subscription_draft
                                 FROM
                                      campaign c, draft d
                                 WHERE
                                  d.CampID = c.CampID AND
                                     c.CampID = :CampID";
                                    $stmt_get = $conn->prepare($sql_get);
                                    $stmt_get->bindValue(':CampID', $CampID);
                                    $result_get = $stmt_get->execute();
                                    if ($stmt_get->rowCount() > 0) {
                                        $result_get = $stmt_get->fetchAll();


                                        


                                        foreach ($result_get as $row_get) {
// if(!empty(trim($row_get['article_title']))) {
//                                             $article_title = trim($row_get['article_title']);
//                                             $Journal_title = trim($row_get['Journal_title']);
//                                             $first_name=$row_get['Fname'];
//                                             $last_name=$row_get['Lastname'];
//                                         } else {
//                                             $article_title = 'article_title';
//                                             $Journal_title = 'Journal_title';
//                                             $first_name='Fname';
//                                             $last_name='Lastname';
//                                         }
                                    

                                            $Draft_tags = ["{article_title}", "{Journal_title}"];
                                            echo "<div style='border: 1px solid black; width:70%; padding:20px; background-color:#bababb17;'>";

                                            echo "Dear Dr. " .$first_name  . " " .$last_name . ":<br/></br/>";

                                            $cmp_draft = html_entity_decode($row_get['subscription_draft']);
                                            $DB_Rows   = [$article_title, $Journal_title];
                                            $cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                                            echo $cmp_draft_new;


                                            echo "</div>";
                                            echo "<br/>";

                                            /* $AdminEmail = $_SESSION['email'];
										$to = $AdminEmail; */
                                            // $mailserverid = trim($row_get['mailserverid']);

                                            // //from_address
                                            // $sql_get = "SELECT
                                            // 		emailaddress
                                            // 	FROM
                                            // 	    current_email
                                            // 	WHERE mailserverid=$mailserverid";

                                            // $stmt_get = $conn->prepare($sql_get);
                                            // $row =  $stmt_get->execute();
                                            // // $to = $to_emails;
                                            // $subject = "Verify - " . $row_get['CampName'];
                                            // $headers = "MIME-Version: 1.0" . "\r\n";
                                            // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                            // //$headers .= 'From:Articles Contributions<editorial@specialissueeditor.net>'."\r\n";
                                            // $headers .= "From:Mailshub<" . $row['emailaddress'];
                                            // ">" . "\r\n";
                                            // //$headers .= 'Cc:qasit@benthamscience.net, faisal@benthamscience.net'."\r\n";

                                            $message = "<html>
                                                       </body>
                                                           
                                                           
                                                           <div style=' width:85%; padding:20px;text-align: justify;'>
                                                           <p>Dear System Admin,</p>
                                                           <p style='text-align:justify;'>$cmp_draft_new</p>
                                                           </div>
                                                           </body>
                                                   </html>";

                                            echo "<br/>" . "$message" . "<br/>";
}
                                    
                                        $get_status =  $conn->prepare("SELECT Camp_Status from campaign WHERE CampID  = :CampID");
                                        $get_status->bindValue(':CampID', $CampID);
                                        $get_status->execute();
                                        $status =      $get_status->fetch();
                                        if ($status["Camp_Status"] == "Pending Verification Alert") {

                                            $pendingMsg     =     "<div id=alertmsg > <div id=first_alertmsg class='alert alert-info alert-dismissible' role='alert'>
                                       <button  type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                       <i class='fa fa-spinner fa-spin'></i> Email Verification Pending.. Kindly Wait.. <br>
                                       Status will be changed with in 5 minutes
                                   </div>
                                   </div>";
                                            echo $pendingMsg;
                                        }
                                    }    
                                } ?>

                            </h5>
                        </div>

                    </div>
                </div>

                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function() {


            setInterval(function() {
                var campid = document.getElementById("CampID").value;

                $.ajax({
                        url: "ajax_status.php",
                        method: "POST",
                        data: {
                            campid: campid
                        },
                        dataType: "JSON",
                        success: function(data) {


                            var status = data.Camp_Status;
                            if (status == "Pending Verification by Admin") {



                                $("#first_alertmsg").hide();


                                $("#alertmsg").html("<div id=first class='alert alert-warning alert-dismissible' role='alert'><button  type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <i class='fa fa-history'></i> Verification Pending by Admin..  </div>");

                                $("#alertmsg").show();

                            }

                            if (status == "Verified") {

                                $('#discard').prop('disabled', false);
                                $('#activity').prop('disabled', false);

                                $("#first_alertmsg").hide();


                                $("#alertmsg").html("<div id=first class='alert alert-success alert-dismissible' role='alert'><button  type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <i class='fa fa-check-circle'></i> Verification Done! <br>  Please Proceed to next activity..  </div>");

                                $("#alertmsg").show();

                            }

                            if (status == "Rejected") {

                                $('#discard').prop('disabled', false);
                              

                                $("#first_alertmsg").hide();


                                $("#alertmsg").html("<div id=first class='alert alert-danger alert-dismissible' role='alert'><button  type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <i class='fa fa-times'></i> Your draft has been rejecred. Please Discard the draft and send new alert. </div>");

                                $("#alertmsg").show();

                            }





                        }


                    }

                )

            }, 500);

        });
    </script>



    <script src="assets/vendor/jquery/jquery.js"></script>
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

</html> -->