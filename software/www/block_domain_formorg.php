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
// if (isset($_SESSION['USUBM']))  {
//     if ($_SESSION['USUBM']=="NO")  {

//         //User not logged in. Redirect them back to the login page.
//         header('Location: page-403.html');
//         exit;
//     }
//     }

if (isset($_SESSION['domain_block_type']))  {
    if ($_SESSION['domain_block_type'] == "sys-defined")  {

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
                            <h2>Add Organizational Domain Block</h2>
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
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                    <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->
                                <div class="demo-masked-input">
                                <div  id="bdomo_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> Domain already exists!
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>

                                                <div  id="not_allowed_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                    Http protocol or any forward slashes are not allowed!<br>
                                                    Only domain name is allowed.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>
                                    <form class="custom-validation" action="block_domainorg_db.php" method="post">

                                        <div class="form-group ">
                                            <label> Name *</label>
                                            <input type="text" name="domain_name" class="form-control" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Owner *</label>
                                            <input type="text" name="domain_owner" class="form-control" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Type *</label>
                                            <input type="text" name="domain_type" class="form-control" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Top Level Domain *</label>
                                            <input type="text" name="top_level_domain" class="form-control" required>

                                        </div>
                               


                                        <?php

                                        if (isset($_SESSION['orgunit_id'])) { ?>
                                            <input hidden type="text" name="orgunit_id" class="form-control" value=<?php echo $_SESSION['orgunit_id']; ?> required>

                                        <?php } else {

                                            $sql_dept = "SELECT *  from   tbl_organizational_unit WHERE orgunit_status='Active' AND
                                            system_entityid=2";
                                            $stmt3 = $conn->prepare($sql_dept);
                                            $stmt3->execute();
                                            $org_units = $stmt3->fetchAll();
                                        ?>


                                            <div class="form-group ">
                                                <label>Select Organizational Unit *</label>




                                                <select name="orgunit_id" id="orgunit_id" class="form-control" required>
                                                    <option value="" disabled selected></option>
                                                    <?php foreach ($org_units as $output) { ?>
                                                        <option value="<?php echo $output["orgunit_id"]; ?>"> <?php echo $output["orgunit_name"]; ?> </option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                        <?php }
                                        ?>
                                                       <div class="form-group ">
                                                        <label>Status</label>

                                                        <select name="domain_status" class="form-control" required>

                                                            <option value="Active"> Active</option>
                                                            <option value="In Active"> In Active</option>

                                                        </select>


                                                    </div>

                                        <div class="form-group ">

                                            <input hidden type="text" name="Type" class="form-control" value="External" required>



                                        </div>









                                        <br>

                                        <div class="form-group mb-0">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                    Add
                                                </button>
                                                <button type="reset" class="btn btn-secondary waves-effect">
                                                    Cancel
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
    <script>
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   
    if($_GET["bdomo"]=="true") {
        
        jQuery('#bdomo_alert').show();
   
 
 
    }
    if($_GET["not_allwd"]=="true") {
        
        jQuery('#not_allowed_alert').show();
   
 
 
    }




});
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