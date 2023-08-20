<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
                            <h2>Enter Mail Server & IP Detail</h2>
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
                <div class="row clearfix">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <div class="card">
                            <?php

                            $mailserverid = $_GET['id'];
                            $stmt = $conn->prepare("SELECT * FROM mailservers WHERE mailserverid=:mailserverid");
                            $stmt->bindValue(':mailserverid', $mailserverid);
                            $stmt->execute();
                            $result = $stmt->fetch();

                            $vmname = $result["vmname"];
                            $vmstatus = $result["vmstatus"];
                            $ethernet_name = $result["ethernet_name"];
                            $mac_address = $result["mac_address"];

                            ?>
                            <div class="body">

                                <h2>Update Mail Server</h2>

                                <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                    <form class="custom-validation" action="mailserver_edit_db.php" method="post">

                                        <div class="form-group ">
                                            <label>Mailsever Name *</label>
                                            <input type="text" name="vmname" class="form-control" value="<?php echo $vmname; ?>" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Ethernet Name *</label>
                                            <input type="text" name="ethernet_name" class="form-control" value="<?php echo $ethernet_name; ?>" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Mac Address *</label>
                                            <input type="text" name="mac_address" class="form-control" value="<?php echo $mac_address; ?>" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Status</label>

                                            <select name="vmstatus" value="<?php echo $vmstatus; ?>" class="form-control" required>


                                                <option value="Active" <?php if ($vmstatus == "Active") {
                                                                            echo ' selected="selected"';
                                                                        } ?>>Active</option>
                                                <option value="In Active" <?php if ($vmstatus == "In Active") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>In Active</option>

                                            </select>
                                            <input hidden name="id" type="text" value="<?php echo $mailserverid; ?>">

                                        </div>


                                        <div class="form-group ">

                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                Submit
                                            </button>


                                        </div>








                                        <br>


                                    </form>




                                </div>
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

    <script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> <!-- Bootstrap Colorpicker Js -->
    <script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js -->
    <script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
    <script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js -->
    <script src="assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js -->
    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>

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

</html>