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
if (isset($_SESSION['CTYPEE'])) {
	if ($_SESSION['CTYPEE'] == "NO") {
  
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
                            <h2>Modify Campaign Type</h2>
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

                            <div class="body">

                                <?php

                                $id = $_GET['id'];
                                $stmt = $conn->prepare("SELECT * FROM  Campaign_type WHERE ctype_id=:ctype_id");
                                $stmt->bindValue(':ctype_id', $id);
                                $stmt->execute();
                                $result = $stmt->fetch();

                                $ctype_name = $result["ctype_name"];
                              
                                $data_format1 = $result["data_format1"];
                                $data_format2 = $result["data_format2"];
                                $data_scopus = $result["data_scopus"];
                                $data_wos = $result["data_wos"];
                                $data_automatic = $result["data_automatic"];
                                $ctype_domainwise_mail_send = $result["ctype_domainwise_mail_send"];
                                $ctype_status = $result["ctype_status"];

                                ?>

                                <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="demo-masked-input">
                                        <form action="ctype_edit_db.php" method="post">

                                            <div class="form-group ">
                                                <label>Name *</label>
                                                <input name="ctype_name" type="text" class="form-control" value="<?php echo $ctype_name; ?>" required>

                                            </div>
                                      
                                            <div class="form-group ">
                                                <label>Format1</label>

                                                <select name="data_format1" value="<?php echo $data_format1; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($data_format1 == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($data_format1 == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                             
                                            </div>
                                            <div class="form-group ">
                                                <label>Format2</label>

                                                <select name="data_format2" value="<?php echo $data_format2; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($data_format2 == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($data_format2 == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                       
                                            </div>
                                            <div class="form-group ">
                                                <label>Scopus</label>

                                                <select name="data_scopus" value="<?php echo $data_scopus; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($data_scopus == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($data_scopus == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                       
                                            </div>
                                            <div class="form-group ">
                                                <label>Wos</label>

                                                <select name="data_wos" value="<?php echo $data_wos; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($data_wos == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($data_wos == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                       
                                            </div>
                                            <div class="form-group ">
                                                <label>Automatic</label>

                                                <select name="data_automatic" value="<?php echo $data_automatic; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($data_automatic == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($data_automatic == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                       
                                            </div>
                                            <div class="form-group ">
                                                <label>Domain Wise Mail Send</label>

                                                <select name="ctype_domainwise_mail_send" value="<?php echo $ctype_domainwise_mail_send; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($ctype_domainwise_mail_send == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($ctype_domainwise_mail_send == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                       
                                            </div>
                       
                                          
                                            <div class="form-group ">
                                                <label>Status</label>

                                                <select name="ctype_status" value="<?php echo $ctype_status; ?>" class="form-control" required>


                                                    <option value="Active" <?php if ($ctype_status == "Active") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Active</option>
                                                    <option value="In Active" <?php if ($ctype_status == "In Active") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>In Active</option>

                                                </select>

                                             
                                            </div>

                                            <input hidden name="id" type="text" value="<?php echo $id; ?>">

                                            <div class="form-group ">

                                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                    Update
                                                </button>


                                            </div>








                                            <br>


                                        </form>
                                    </div>
                                </div>
                                <!----------table----------->









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
    <script src="assets/vendor/editable-table/mindmup-editabletable.js"></script> <!-- Editable Table Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/editable-table.js"></script>



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