<?php
//   error_reporting(E_ALL);
//   ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['EOUNSUB'])) {
    if ($_SESSION['EOUNSUB'] == "NO") { 

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}
if (isset($_SESSION['unsubscription_type']))  {
    if ($_SESSION['unsubscription_type']=="sys-defined")  {
  
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
                            <h2>Edit Unsubscriber Email</h2>
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

                $orgunit_unsubscriber_id = $_GET["id"];

                $sql = "SELECT *
FROM
`unsubscriber`
INNER JOIN orgunit_unsubscriber INNER JOIN tbl_organizational_unit ON
unsubscriber.UnsubscribeID = orgunit_unsubscriber.UnsubscribeID AND
tbl_organizational_unit.orgunit_id = orgunit_unsubscriber.orgunit_id AND
orgunit_unsubscriber.orgunit_unsubscriber_id=:orgunit_unsubscriber_id";

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':orgunit_unsubscriber_id', $orgunit_unsubscriber_id);
                $result = $stmt->execute();
                $row = $stmt->fetch();

                ?>

                <!---Add code here-->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                    <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->
                                <div class="demo-masked-input">
                                    <form class="custom-validation" action="editorgUnsubscriber_db.php" method="post">

                                        <div class="form-group ">
                                            <label>First Name *</label>
                                            <input type="text" name="FirstName" value="<?php echo $row["FirstName"] ?>" class="form-control" readonly required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Last Name *</label>
                                            <input type="text" name="LastName" value="<?php echo $row["LastName"] ?>" class="form-control" readonly required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Email *</label>
                                            <input type="text" name="UnsubscriberEmail" value="<?php echo $row["UnsubscriberEmail"] ?>" class="form-control email" readonly required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Status</label>

                                            <select name="Status" value="<?php echo $row['Status']; ?>" class="form-control" required>


                                                <option value="Enabled" <?php if ($row['Status'] == "Enabled") {
                                                                            echo ' selected="selected"';
                                                                        } ?>>Enabled</option>
                                                <option value="Disabled" <?php if ($row['Status'] == "Disabled") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Disabled</option>

                                            </select>
                                        </div>



                                        <div class="form-group ">

                                            <input hidden type="text" name="Type" class="form-control" value="External" required>
                                            <input hidden type="text" name="unsubid" class="form-control" value="<?php echo $row['UnsubscribeID']; ?>" required>




                                        </div>

                                        <br>

                                        <div class="form-group mb-0">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                    Update
                                                </button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
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
    <script src="assets/js/pages/forms/form-wizard.js"></script>

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
</body>

</html>