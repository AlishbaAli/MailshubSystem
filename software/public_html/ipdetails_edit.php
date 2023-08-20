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
if (isset($_SESSION['IPE'])) {
    if ($_SESSION['IPE'] == "NO") {
  
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
                            <h2>Update IP Details</h2>
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

                            $ipdetailid = $_GET['id'];
                            $stmt = $conn->prepare("SELECT * FROM ipdetails WHERE ipdetailid=:ipdetailid");
                            $stmt->bindValue(':ipdetailid', $ipdetailid);
                            $stmt->execute();
                            $result = $stmt->fetch();

                            $ipaddress = $result["ipaddress"];
                            $ipsubnet = $result["ipsubnet"];
                            $ipgateway = $result["ipgateway"];
                            $hostname = $result["hostname"];
                            $service_provider = $result["service_provider"];
                            $emailaddress = $result["emailaddress"];
                            $mailserverid = $result["mailserverid"];
                            $iphour = $result["iphour"];
                            $ipstatus = $result["ipstatus"];

                            ?>



                            <div class="body">
                                <div class="demo-masked-input">
                                    <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                    <div  id="blck_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                    Email Address/Hostname blocked by system!<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>
                                        <form action="ipdetails_edit_db.php" method="post">
                                            <div class="row clearfix">




                                                <div class="col-lg-12 col-md-12">
                                                    <b>IP Address</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                        </div>
                                                        <input name="ipaddress" type="text" class="form-control" placeholder="Ex: 255.255.255.255" value="<?php echo $ipaddress; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <b>IP Subnet</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                        </div>
                                                        <input name="ipsubnet" type="text" class="form-control" placeholder="Ex: 255.255.255.255" value="<?php echo $ipsubnet; ?>" required>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <b>IP Gateway</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                        </div>
                                                        <input name="ipgateway" type="text" class="form-control" placeholder="Ex: 255.255.255.255" value="<?php echo $ipgateway; ?>" required>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <b>Host Name</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-bars"></i></span>
                                                        </div>
                                                        <input name="hostname" type="text" class="form-control" value="<?php echo $hostname; ?>" required>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <b>Service Provider</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-bars"></i></span>
                                                        </div>
                                                        <input name="service_provider" type="text" class="form-control" value="<?php echo $service_provider; ?>" required>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <b>Email Address</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-envelope-o"></i></span>
                                                        </div>
                                                        <input name="emailaddress" type="text" class="form-control email" placeholder="Ex: example@example.com" value="<?php echo $emailaddress; ?>" required>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <?php $sql = "SELECT * FROM mailservers";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $mailservers = $stmt->fetchAll();
                                                    ?>
                                                    <b>Mail Server</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-Desktop"></i></span>
                                                        </div>
                                                        <select name="mailserverid" class="form-control" required>

                                                            <?php $sql_mail = "SELECT vmname FROM mailservers WHERE mailserverid=$mailserverid";
                                                            $stmtms = $conn->prepare($sql_mail);
                                                            $stmtms->execute();
                                                            $mailserver = $stmtms->fetch();
                                                            ?>
                                                            <option value="<?php echo $mailserverid ?>" selected> <?php echo $mailserver["vmname"]; ?> </option>

                                                            <?php foreach ($mailservers as $output) { ?>
                                                                <option value="<?php echo $output["mailserverid"]; ?>"> <?php echo $output["vmname"]; ?> </option>
                                                            <?php
                                                            } ?>



                                                        </select>
                                                    </div>
                                                </div>


                                            </div>
                                            <input hidden name="id" type="text" value="<?php echo $ipdetailid; ?>">

                                            <!-- <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <b>IP Hour</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="icon-clock"></i></span>
                                                        </div>


                                                        <select name="iphour" class="form-control" value="<?php echo $iphour; ?>" required>

                                                            <option value="<?php echo $iphour ?>" selected> <?php echo $iphour; ?> </option>

                                                            <?php for ($i = 1; $i <= 24; $i++) {
                                                            ?>
                                                                <option value=<?php echo $i ?>> <?php echo $i ?> </option>
                                                            <?php } ?>

                                                        </select>





                                                    </div>
                                                </div>


                                            </div> -->



                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12">
                                                    <b>Status</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="icon-check"></i></span>
                                                        </div>
                                                        <select name="ipstatus" value="<?php echo $ipstatus; ?>" class="form-control" required>


                                                            <option value="Active" <?php if ($ipstatus == "Active") {
                                                                                        echo ' selected="selected"';
                                                                                    } ?>>Active</option>
                                                            <option value="In Active" <?php if ($ipstatus == "In Active") {
                                                                                            echo ' selected="selected"';
                                                                                        } ?>>In Active</option>

                                                        </select>

                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row clearfix">
                                                <div class="col-lg-3 col-md-3">

                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">

                                                        </div>
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                            Update
                                                        </button>

                                                    </div>
                                                </div>


                                            </div>




                                        </form>
                                    </div>
                                </div>
                            </div>







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

    <script>
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   
  
    if($_GET["blck"]=="true") {
        
        jQuery('#blck_alert').show();
   
 
 
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