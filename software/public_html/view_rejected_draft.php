<?php        ob_start();
        session_start();

//         error_reporting(E_ALL);
//   ini_set('display_errors', 1);
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


                                     //--------------------------------
 
                                 
                                    echo "<input hidden type='text' id= 'CampID' name='CampID' value=$CampID >";

                                    $sql = "SELECT COUNT(CampName) AS num , CampName, CampID 
												FROM campaign 
												WHERE CampID = :CampID";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindValue(':CampID', $CampID);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($row['num'] > 0) {

//---------------------------------
///------------------------------------    
                                        echo "Rejected Alert For <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign<br/><br/>";
                                          } else {
                                        echo "Invalid Selection";
                                    }

                                   
                                    // Script Rejected Draft Email

        // Update record in activity table
        // $sql = "UPDATE `activity` SET `verification_activity` = 1 
        //                 WHERE `CampID` = :CampID";

        // $stmt = $conn->prepare($sql);
        // $stmt->bindValue(':CampID', $CampID);
        // $result_1 = $stmt->execute();

        // record camp flow

        // $status='Pending Verification Alert';
        // $q="SELECT * FROM Campaign_flow WHERE CampID = :CampID and Camp_Status =:Camp_Status ";
        // $st = $conn->prepare($q);
            
        //                 $st->bindValue(':CampID', $CampID);
        //                 $st->bindValue(':Camp_Status', $status);
                        
        //                 $re = $st->execute();
        //                 if ($st->rowCount() > 0) {
        //                 } else {
        // $sql = "INSERT INTO Campaign_flow (CampID, Camp_Status) 
        //                     VALUES (:CampID, :Camp_Status)";
            
        //                 $stmt = $conn->prepare($sql);
            
        //                 $stmt->bindValue(':CampID', $CampID);
        //                 $stmt->bindValue(':Camp_Status', $status);
                        
        //                 $result = $stmt->execute();
        //                 if ($result > 0) {
        //                 } 
        //             }
    


    $article_title = '{article_title}';
    $Journal_title = '{Journal_title}';
    $first_name='{first_name}';
    $last_name='{last_name}';
                          

                                $sql_get = "SELECT campaign.CampID, CampName, rejected_iteration,  Campaign_type.ctype_id,
                                CampDate, reason, camp_draft.subscription_draft as rej_draft, camp_draft.draft_subject as cdsub
                                 
                                 FROM camp_draft 
                                 INNER JOIN campaign on camp_draft.CampID=campaign.CampID   

                                 and campaign.Camp_Status='Rejected' and rejected_iteration != '0'

                                 and campaign.CampID= :CampID and rejected_iteration = (SELECT max(rejected_iteration) 
                                                                                        from camp_draft where camp_draft.CampID= :CampID)

                                 left join Campaign_type on Campaign_type.ctype_id = campaign.ctype_id
                                 group by campaign.CampID";

                                    $stmt_get = $conn->prepare($sql_get);
                                    $stmt_get->bindValue(':CampID', $CampID);
                                    $result_get = $stmt_get->execute();
                                    if ($stmt_get->rowCount() > 0) {
                                        $result_get = $stmt_get->fetchAll();

                                        foreach ($result_get as $row_get) {

                                            $Draft_tags = ["{article_title}", "{Journal_title}","{first_name}","{last_name}"];
                                         
                                            $cmp_draft = html_entity_decode(htmlspecialchars_decode($row_get['rej_draft']));
                                            $DB_Rows   = [$article_title, $Journal_title, $first_name, $last_name];
                                            $cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);


$draftalert=$cmp_draft_new;

            

                                            $message = "<html>
                                                       </body>
                                                           
                                                           
                                                           <div style=' width:85%; padding:20px;text-align: justify;'>
                                                          
                                                          
                                                           <p style='text-align:justify;'>$draftalert</p>
                                                           </div>
                                                           </body>
                                                   </html>";

                                            echo "<br/>" . "$message" . "<br/>";
} 
                                    
                                        $get_status =  $conn->prepare("SELECT Camp_Status from campaign WHERE CampID  = :CampID");
                                        $get_status->bindValue(':CampID', $CampID);
                                        $get_status->execute();
                                        $status =      $get_status->fetch();
                                      
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