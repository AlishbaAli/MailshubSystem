<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['MISB'])) {
    if ($_SESSION['MISB'] == "NO") {

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
        <?php include 'include/nav_SA.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->


        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Modify Institute Status</h2>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php 

                $orgunit_id= $_GET['orgunit_id'];
                $ri_id= $_GET['ri_id'];

                
                $stmt = $conn->prepare("SELECT org_inst_status FROM organizational_institutes
                WHERE orgunit_id=$orgunit_id AND ri_id=$ri_id");
                $stmt->execute();
                $row = $stmt->fetch();





                ?>
                <!---Add code here-->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->

                                <form id="advanced-form" action="modify_org_inst_status_db.php" method="post">

                         
                                    <div class="form-group ">
                                        <label>Status *</label>
                                        <select id="status1" name="org_inst_status" class="form-control" value="<?php echo $row['org_inst_status'] ?>" required>

                                            <option value="Active" <?php if ($row['org_inst_status'] == "Active") {
                                                                      echo ' selected="selected"';
                                                                            } ?>>Active</option>
                                              <option value="In Active" <?php if ($row['org_inst_status'] == "In Active") {
                                                                        echo ' selected="selected"';
                                                                    } ?>>In Active</option>
                                           
                                       


                                        </select>
                                    </div>
                                    <input hidden type="text" name="orgunit_id"  value="<?php echo $orgunit_id ?>" >
                                    <input hidden type="text" name="ri_id"  value="<?php echo $ri_id ?>" >
                                
                                    <div class="form-group ">
<button type="submit" id="submit" name="submit" class="btn btn-primary">Update</button>
                                                                </div>

                                
                                    <br>

                                  
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- end row -->


                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->




    <!-- Javascript -->
    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/parsleyjs/js/parsley.min.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>

<!-- 
    ------------- -->

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>
    <script src="assets/js/index.js"></script>

    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>

    <!-- <script src="assets/bundles/morrisscripts.bundle.js"></script> -->


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