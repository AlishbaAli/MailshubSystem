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
if (isset($_SESSION['AREE'])) {
    if ($_SESSION['AREE'] == "NO") {

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
                            <h2>Modify Reply To Emails</h2>
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
                                $stmt = $conn->prepare("SELECT * FROM reply_to_emails WHERE rtemid=:rtemid");
                                $stmt->bindValue(':rtemid', $id);
                                $stmt->execute();
                                $result = $stmt->fetch();

                                $reply_to_email = $result["reply_to_email"];
                                $rtem_status = $result["rtem_status"];

                                ?>

                                <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="demo-masked-input">
                                    <div  id="blck_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                   Email blocked by system!<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>
                                        <form action="reply_to_email_edit_db.php" method="post">

                                            <div class="form-group ">
                                                <label>Reply To Email *</label>
                                                <input name="reply_to_email" type="text" class="form-control email" value="<?php echo $reply_to_email; ?>" placeholder="Ex: example@example.com" required>

                                            </div>
                                            <div class="form-group ">
                                                <label>Status</label>

                                                <select name="rtem_status" id="rtem_status" onClick=showHide() value="<?php echo $rtem_status; ?>" class="form-control" required>


                                                    <option value="Active" <?php if ($rtem_status == "Active") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Active</option>
                                                    <option value="In Active" <?php if ($rtem_status == "In Active") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>In Active</option>

                                                </select>

                                                <input hidden name="id" type="text" value="<?php echo $id; ?>">
                                            </div>
                                            <div class="form-group "  style="display: none" id="reason">
                                                        <label >Reason*</label>
                                                        <textarea name="reason" id="reasonr" class="form-control" rows="5" cols="30" ></textarea>

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

    <script>
        function showHide() {


            var rtem_status = document.getElementById("rtem_status").value;
            if (rtem_status.trim() == 'In Active') {
               
                document.getElementById('reason').style.display = 'block'
                $('#reasonr').prop('required',true);
               
            } else {
              
                 document.getElementById('reason').style.display = 'none'
                 $('#reasonr').prop('required',false);
            }
        }
    </script>

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




</html>