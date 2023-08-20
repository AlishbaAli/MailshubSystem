<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ASRO'])) {
    if ($_SESSION['ASRO'] == "NO") {
  
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
                <?php






                $orgunit_id = $_GET['orgunit_id'];


                $stmt = $conn->prepare("SELECT reply_to_emails.rtemid, reply_to_email FROM reply_to_emails 
INNER JOIN tbl_orgunit_rte  ON reply_to_emails.rtemid = tbl_orgunit_rte.rtemid WHERE
tbl_orgunit_rte.orgunit_id=$orgunit_id ");
                $stmt->execute();
                $row = $stmt->fetchAll();

                $stmt = $conn->prepare("SELECT rtemid, reply_to_email FROM reply_to_emails WHERE rtem_status='Active'
                AND rtemid NOT IN(SELECT reply_to_emails.rtemid FROM reply_to_emails 
INNER JOIN tbl_orgunit_rte  ON reply_to_emails.rtemid = tbl_orgunit_rte.rtemid WHERE
tbl_orgunit_rte.orgunit_id=$orgunit_id)");
                $stmt->execute();
                $row2 = $stmt->fetchAll(); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>Select Reply To Emails for User</h2>
                            </div>
                            <div class="body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-8 col-md-8">
                                        <label></label>


                                        <form class="custom-validation" action="rtemid_dept_edit_db.php" method="post" enctype="multipart/form-data">

                                        <div  id="blck_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                   Your selection contains system blocked email(s)!<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>

                                            <div class="multiselect_div">
                                                <select id="multiselect1" name="reply_to_emails[]" class="multiselect" multiple="multiple">
                                                    <?php foreach ($row as $output) { ?>
                                                        <option value="<?php echo $output['rtemid']; ?>" <?php echo ' selected="selected"'; ?>> <?php echo $output['reply_to_email']; ?>
                                                        </option>
                                                    <?php
                                                    } ?>

                                                    <?php foreach ($row2 as $output) { ?>
                                                        <option value="<?php echo $output['rtemid']; ?>"> <?php echo $output['reply_to_email']; ?> </option>
                                                    <?php
                                                    } ?>

                                                </select>
                                                <input id="id" name="id" type="hidden" value="<?php echo $_GET['orgunit_id']; ?>">

                                                <div>
                                                    <br><br>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                        Submit
                                                    </button>

                                                </div>
                                        </form>
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

   
    if($_GET["rte"]=="true") {
        
        jQuery('#Email_exist_alert').show();
   
 
 
    }
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