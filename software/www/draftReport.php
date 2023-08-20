<?php
ob_start();
session_start();
include 'include/conn.php';

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
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="assets/images/thumbnail.png" width="48" height="48" alt="Mplify"></div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- Overlay For Sidebars -->
    <div class="overlay" style="display: none;"></div>

    <div id="wrapper">

        <!--nav bar-->
        <?php include 'include/nav_bar.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->


        <!-- <div id="main-content">
        <div class="container-fluid"> -->


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
                        <div class="col-lg-5 col-md-12 col-sm-12">
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h3>Detailed Report</h3>
                                </div>
                                <div class="element-box" style="height:1000px;">
                                    <h5 class="form-header">
                                        Campaign Report
                                    </h5>

                                    <!--------------------
                                          START - Table with actions
                                          -------------------->
                                    <!-- STARTTTTTTTTTTTTTTT  -->

                                    <?php

                                    require 'include/conn.php';

                                    //$_GET['CAID'] = 2;
                                    //$_SERVER['REMOTE_ADDR'] = '103.236.132.220';
                                    //$Country = "";

                                    if (isset($_GET['CampID']) && (!empty($_GET['CampID']))) {
                                        $CampID = $_GET['CampID'];
                                        //GET camp status

                                        $stmt_s = $conn->prepare("SELECT Camp_Status FROM campaign WHERE CampID= :CampID");
                                        $stmt_s->bindValue(':CampID', $CampID);
                                        $stmt_s->execute();
                                        $result = $stmt_s->fetch(PDO::FETCH_ASSOC);
                                        $Camp_Status = $result['Camp_Status'];
                                        if ($Camp_Status == 'Active') {
                                            $campauthourstable = "campaingauthors";
                                        } else if ($Camp_Status == 'Stop') {
                                            $campauthourstable = "campaingauthors_hold_archive";
                                        } else if ($Camp_Status == 'Completed') {
                                            $campauthourstable = "campaingauthors_comp_archive";
                                        }

                                        $stmt = $conn->prepare("SELECT CampName, Camp_Send_Date FROM `campaign` WHERE Camp_Status = 'Completed'  AND draft_status = 'subscriptionDraft' AND CampID = :CampID");
                                        $stmt->bindValue(':CampID', $CampID);
                                        $stmt->execute();
                                        $result = $stmt->fetch(PDO::FETCH_ASSOC);


                                        $stmt1 = $conn->prepare("SELECT count(Email) as TotalEmail FROM $campauthourstable WHERE `CampID` = :CampID");
                                        $stmt1->bindValue(':CampID', $CampID);
                                        $stmt1->execute();
                                        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);


                                        $stmt2 = $conn->prepare("SELECT count(Email) as TotalSentEmail FROM $campauthourstable WHERE `CampID` = :CampID AND Status = 'Sent'");
                                        $stmt2->bindValue(':CampID', $CampID);
                                        $stmt2->execute();
                                        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);


                                        $stmt3 = $conn->prepare("SELECT count(cas.CampaingAuthorsID) as EmailsOpened
                                                                    FROM campaign c, $campauthourstable ca, campaign_author_stats cas
                                                                    WHERE c.CampID = ca.CampID
                                                                    AND ca.CampaingAuthorsID = cas.CampaingAuthorsID
                                                                    AND cas.Stats_Type = 'EmailOpened'
                                                                    AND c.CampID = :CampID");

                                        $stmt3->bindValue(':CampID', $CampID);
                                        $stmt3->execute();
                                        $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);


                                        $stmt4 = $conn->prepare("SELECT count(DISTINCT cas.CampaingAuthorsID) as UniqueOpened
                                                                    FROM campaign c, $campauthourstable ca, campaign_author_stats cas
                                                                    WHERE c.CampID = ca.CampID
                                                                    AND ca.CampaingAuthorsID = cas.CampaingAuthorsID
                                                                    AND cas.Stats_Type = 'EmailOpened'
                                                                    AND c.CampID = :CampID");
                                        $stmt4->bindValue(':CampID', $CampID);
                                        $stmt4->execute();
                                        $result4 = $stmt4->fetch(PDO::FETCH_ASSOC);


                                        $stmt5 = $conn->prepare("SELECT count(cas.CampaingAuthorsID) as ClickOnLink
                                                                    FROM campaign c, $campauthourstable ca, campaign_author_stats cas
                                                                    WHERE c.CampID = ca.CampID
                                                                    AND ca.CampaingAuthorsID = cas.CampaingAuthorsID
                                                                    AND cas.Stats_Type = 'ClickOnLink'
                                                                    AND c.CampID = :CampID");
                                        $stmt5->bindValue(':CampID', $CampID);
                                        $stmt5->execute();
                                        $result5 = $stmt5->fetch(PDO::FETCH_ASSOC);


                                        $stmt6 = $conn->prepare("SELECT count(DISTINCT cas.CampaingAuthorsID) as UniqueClicked
                                                                    FROM campaign c, $campauthourstable ca, campaign_author_stats cas
                                                                    WHERE c.CampID = ca.CampID
                                                                    AND ca.CampaingAuthorsID = cas.CampaingAuthorsID
                                                                    AND cas.Stats_Type = 'ClickOnLink'
                                                                    AND c.CampID = :CampID");
                                        $stmt6->bindValue(':CampID', $CampID);
                                        $stmt6->execute();
                                        $result6 = $stmt6->fetch(PDO::FETCH_ASSOC);


                                        $stmt7 = $conn->prepare("SELECT count(Email) as PendingEmail FROM $campauthourstable WHERE `CampID` = :CampID AND Status = 'Not Sent'");
                                        $stmt7->bindValue(':CampID', $CampID);
                                        $stmt7->execute();
                                        $result7 = $stmt7->fetch(PDO::FETCH_ASSOC);

                                        $CampName = $result['CampName'];
                                        $Camp_Send_Date = date("j M Y", strtotime($result['Camp_Send_Date']));
                                        $TotalEmail = $result1['TotalEmail'];
                                        $TotalSentEmail = $result2['TotalSentEmail'];
                                        $EmailsOpened = $result3['EmailsOpened'];
                                        $UniqueOpened = $result4['UniqueOpened'];
                                        $ClickOnLink = $result5['ClickOnLink'];
                                        $UniqueClicked = $result6['UniqueClicked'];
                                        $PendingEmail = $result7['PendingEmail'];

                                        //die();


                                    }


                                    $dataPoints = array(
                                        array("label" => "TotalEmail", "symbol" => "TotalEmail", "y" => $TotalEmail),
                                        array("label" => "TotalSentEmail", "symbol" => "TotalSentEmail", "y" => $TotalSentEmail),
                                        array("label" => "UniqueOpened", "symbol" => "UniqueOpened", "y" => $UniqueOpened),
                                        array("label" => "EmailsOpened", "symbol" => "EmailsOpened", "y" => $EmailsOpened),

                                    )
                                    ?>

                                    <script type="text/javascript">
                                        window.onload = function() {

                                            var chart = new CanvasJS.Chart("chartContainer", {
                                                theme: "light2",
                                                animationEnabled: true,
                                                title: {
                                                    text: "Statistics Chart"
                                                },
                                                data: [{
                                                    type: "pie",
                                                    indexLabel: "{symbol} - {y}",
                                                    yValueFormatString: "#,##0\"\"",
                                                    showInLegend: false,
                                                    legendText: "{label} : {y}",
                                                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                                                }]
                                            });
                                            chart.render();

                                        }
                                    </script>

                                    <div class="col-sm-12 col-md-12">
                                        <div id="chartContainer" style="height: 400px;">
                                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12">
                                        <div class="element-wrapper">
                                            <div class="element-box">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-md table-v2 table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">
                                                                    <h5>campaign statistics <?php echo $campauthourstable ?></h5>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <strong>Campaign Title</strong>
                                                                </td>
                                                                <td>
                                                                    <?php echo $CampName; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong>Campaign Start On</strong>
                                                                </td>
                                                                <td>
                                                                    <?php echo $Camp_Send_Date; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong>Total Emails</strong>
                                                                </td>
                                                                <td>
                                                                    <?php echo $TotalEmail; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong>Total Emails Sent</strong>
                                                                </td>
                                                                <td>
                                                                    <?php echo $TotalSentEmail; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong>Emails Opened</strong>
                                                                </td>
                                                                <td>
                                                                    <?php echo $EmailsOpened; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong>Unique Opened</strong>
                                                                </td>
                                                                <td>
                                                                    <?php echo $UniqueOpened; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        echo "<a href='emailOpenList.php?CampID=" . $_GET['CampID'] . "'><button type='button' class='btn btn-info'>Unique Open Detail List</button></a>";
                                        ?>

                                    </div>
                                </div>


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

    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-XXXXXXX-9', 'auto');
        ga('send', 'pageview');
    </script>

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