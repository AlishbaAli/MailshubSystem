<?php
ob_start();
session_start();
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

                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="header">
                                <h3>DRAFT ACTIONS</h3>
                            </div>

                            <div class="element-box">
                                <h5 class="form-header">
                                    <?php

                                    if (isset($_GET['CampID'])) {
                                        //echo $_GET['CampID'];
                                        $CampID =  $_GET['CampID'];

                                        $sql = "SELECT COUNT(CampName) AS num , CampName, CampID
                                    FROM campaign 
                                    WHERE CampID = :CampID";

                                        $stmt = $conn->prepare($sql);

                                        //Bind the provided username to our prepared statement.
                                        $stmt->bindValue(':CampID', $CampID);

                                        //Execute.
                                        $stmt->execute();

                                        //Fetch the row.
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                        echo "Actions for <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Draft";

                                        echo "</h5>";

                                        echo "<div class='table-responsive'>";
                                        echo "<table class='table table-bordered table-lg table-v2 table-striped'>";
                                        echo "<tbody>";
                                        echo "<tr>";

                                        echo "<td class='text-center'>";
                                        echo "<a class='mr-2 mb-2 btn btn-primary' href='draftLandingpage.php?CampID=" . $row["CampID"] . "' target='_blank'>Check Your Draft</a>";
                                        echo "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<a class='mr-2 mb-2 btn btn-danger' onclick='return deleteall();' href='deleteDraft.php?CampID=" . $row["CampID"] . "'>Discard Draft</a>";
                                        echo "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<a class='mr-2 mb-2 btn btn-success' href='ManageCampaign.php?CampID=" . $row["CampID"] . "'>Next Activity</a>";
                                        echo "</td>";

                                        echo "</tr>";
                                        echo "</tbody>";
                                        echo "</table>";
                                        echo "</div>";
                                    }

                                    ?>



                            </div>


                        </div>
                    </div>
                </div>

                <!---Add code here-->




            </div>
        </div>

    </div>

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