<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['domain_block_type']))  {
    if ($_SESSION['domain_block_type'] != "sys-defined")  {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
    }
    if (isset($_SESSION['BDSE'])) {
		if ($_SESSION['BDSE'] == "NO") {

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
                            <h2>Edit Block Domain</h2>
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

                $id = $_GET['id'];

                $stmt = $conn->prepare("SELECT * FROM blocked_domains WHERE blocked_domain_id= $id");
                $stmt->bindValue(':blocked_domain_id', $id);
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
                                <div class="demo-masked-input">
                                <div  id="not_allowed_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                    Http protocol or any forward slashes are not allowed!<br>
                                                    Only domain name is allowed.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>
                                    <form class="custom-validation" action="block_domain_edit_db.php" method="post">

                                        <div class="form-group ">
                                            <label> Name *</label>
                                            <input type="text" name="domain_name" value="<?php echo $row['domain_name']; ?>" class="form-control" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Owner *</label>
                                            <input type="text" name="domain_owner" value="<?php echo $row['domain_owner']; ?>" class="form-control" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Type *</label>
                                            <input type="text" name="domain_type" value="<?php echo $row['domain_type']; ?>" class="form-control" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Top Level Domain *</label>
                                            <input type="text" name="top_level_domain" value="<?php echo $row['top_level_domain']; ?>" class="form-control" required>

                                        </div>
                                        <div class="form-group ">
                                            <label>Status</label>

                                            <select name="domain_status" value="<?php echo $row['domain_status']; ?>" class="form-control" required>


                                                <option value="Active" <?php if ($row['domain_status'] == "Active") {
                                                                            echo ' selected="selected"';
                                                                        } ?>>Active</option>
                                                <option value="In Active" <?php if ($row['domain_status'] == "In Active") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>In Active</option>

                                            </select>
                                        </div>

                                        <div class="form-group ">

                                       
                                            <input hidden type="text" name="id" class="form-control" value="<?php echo $id ?>" required>



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

    <script>
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   

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