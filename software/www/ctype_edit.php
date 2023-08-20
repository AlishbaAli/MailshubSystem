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
                                $ctype_status = $result["ctype_status"];
                                $ctype_product = $result["ctype_product"];
                                $ctype_questionare = $result["ctype_questionare"];
                                $ctype_article_list = $result["ctype_article_list"];
                                $ctype_TOC = $result["ctype_TOC"];

                                ?>

                                <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="demo-masked-input">
                                        <form action="ctype_edit_db.php" method="post">

                                            <div class="form-group ">
                                                <label>Name *</label>
                                                <input name="ctype_name" type="text" class="form-control" value="<?php echo $ctype_name; ?>" required>

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

                                                <input hidden name="id" type="text" value="<?php echo $id; ?>">
                                            </div>
                                            <div class="form-group ">
                                                <label>Product</label>

                                                <select name="ctype_product" value="<?php echo $ctype_product; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($ctype_product == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($ctype_product == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                                <input hidden name="id" type="text" value="<?php echo $id; ?>">
                                            </div>
                                            <div class="form-group ">
                                                <label>Questionare</label>

                                                <select name="ctype_questionare" value="<?php echo $ctype_questionare; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($ctype_questionare == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($ctype_questionare == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                                <input hidden name="id" type="text" value="<?php echo $id; ?>">
                                            </div>
                                            <div class="form-group ">
                                                <label>Article List</label>

                                                <select name="ctype_article_list" value="<?php echo $ctype_article_list; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($ctype_article_list == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($ctype_article_list == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                                <input hidden name="id" type="text" value="<?php echo $id; ?>">
                                            </div>
                                            <div class="form-group ">
                                                <label>TOC</label>

                                                <select name="ctype_TOC" value="<?php echo $ctype_TOC; ?>" class="form-control" required>


                                                    <option value="No" <?php if ($ctype_TOC == "No") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>No</option>
                                                    <option value="Yes" <?php if ($ctype_TOC == "Yes") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Yes</option>

                                                </select>

                                                <input hidden name="id" type="text" value="<?php echo $id; ?>">
                                            </div>


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