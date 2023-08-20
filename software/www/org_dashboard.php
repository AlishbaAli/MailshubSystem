<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
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


        <div id="main-content">
            <div class="container-fluid">
                <div class="">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h3>Organization Dashboard</h3>
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

                    <!---Add code here-->




                </div>
                <div><h4 class=" col-12 text-center">Organizational Information</h4> <hr></div>  
 <div class="row col-12">         
<?php include 'org_dashboard_org.php';?>
<?php include 'org_dashboard_role.php';?>
<?php include 'org_dashboard_user.php';?>
 </div>
<div><h4 class="text-center">Campaigns Information</h4> <hr></div> 
<div class="row col-12">     
<?php include 'org_dashboard_camp.php';?>
<?php include 'org_dashboard_rte.php';?>
<?php include 'org_dashboard_camptype.php';?>

</div>


<div class="row col-12">     

<?php include 'org_dashboard_unsub.php';?>

</div>

<div><h4 class="text-center">Blocked URLs and Domains</h4> <hr></div> 
<div class="row col-12">     
<?php include 'org_dashboard_urlB.php';?>
<?php include 'org_dashboard_domainB.php';?>
<?php // include 'org_dashboard_urlPB.php';?>
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

            <!-- ------------------- -->
            




    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>
   
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




  <!-- <div class="body table-responsive">
                                        <table class="table table-hover m-b-0">
                                            <thead>
                                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                    <th>Organziation</th>
                                                    <th>Mailservers</th>
                                                    <th>IPs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>

                                                    <td><h6 class="margin-0">Web</h6></td>
                                                    <td><h6 class="m-b-0">1</h6></td>
                                                    <td class="text-right">
                                                        <div class="text-success">
                                                            23 <i class="fa fa-long-arrow-up"></i>
                                                        </div>
                                                        <div class="text-muted">up</div>
                                                        <div class="text-danger">
                                                            9 <i class="fa fa-long-arrow-down"></i>
                                                        </div>
                                                        <div class="text-muted">down</div>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td> <h6 class="margin-0">Graphics</h6></td>
                                                    <td> <h6 class="m-b-0">1</h6></td>
                                                    <td class="text-right">
                                                        <div class="text-success">
                                                            23 <i class="fa fa-long-arrow-up"></i>
                                                        </div>
                                                        <div class="text-muted">up</div>
                                                        <div class="text-danger">
                                                            9 <i class="fa fa-long-arrow-down"></i>
                                                        </div>
                                                        <div class="text-muted">down</div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div> -->