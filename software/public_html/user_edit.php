<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['UM'])) {
    if ($_SESSION['UM'] == "NO") {

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


                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>Select Roles</h2>
                            </div>
                            <div class="body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-8 col-md-8">
                                        <label></label>
                                        <?php









                                        //Already existing roles
                                        if (isset($_GET['id'])) {
                                            $user_id = $_GET['id'];


                                            $stmt = $conn->prepare("SELECT tbl_role_privilege.role_prev_id, role_prev_title FROM tbl_role_privilege 
     INNER JOIN tbl_user_role_prev ON tbl_role_privilege.role_prev_id = tbl_user_role_prev.role_prev_id WHERE
      tbl_user_role_prev.user_id = $user_id");
                                            $stmt->execute();
                                            $row = $stmt->fetchAll();
                                        }

                                        ?>








                                        <form action="user_edit_db.php" method="post">
                                            <div class="multiselect_div">
                                                <select id="multiselect1" name="roles[]" class="multiselect" multiple="multiple">
                                                    <?php foreach ($row as $output) { ?>
                                                        <option value="<?php echo $output['role_prev_id']; ?>" <?php echo ' selected="selected"'; ?>> <?php echo $output['role_prev_title']; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    if ($_GET['org_id'] != NULL || $_GET['org_id'] != "") {

                                                        $admin_id = $_SESSION['AdminId'];
                                                        $org_id = $_GET['org_id'];

                                                        $stmt = $conn->prepare("SELECT
                                                        tbl_role_privilege.role_prev_id,
                                                         tbl_role_privilege.role_prev_title
                                                       FROM
                                                         tbl_role_privilege INNER JOIN orgunit_role_prev
                                                       ON
                                                         tbl_role_privilege.role_prev_id = orgunit_role_prev.role_prev_id
                                                         AND orgunit_role_prev.orgunit_id=$org_id AND   tbl_role_privilege.restriction_level!='0' AND
                                                         
                                                           tbl_role_privilege.restriction_level>=(SELECT restriction_level FROM tbl_role_privilege AS r INNER JOIN admin AS u INNER JOIN tbl_user_role_prev AS ur ON
                                                                 r.role_prev_id = ur.role_prev_id AND u.AdminId = ur.user_id WHERE u.AdminId = $admin_id) AND tbl_role_privilege.role_prev_id NOT IN(SELECT tbl_role_privilege.role_prev_id FROM tbl_role_privilege 
                                                            INNER JOIN tbl_user_role_prev ON tbl_role_privilege.role_prev_id = tbl_user_role_prev.role_prev_id WHERE
                                                             tbl_user_role_prev.user_id = $user_id)");
                                                        $stmt->execute();
                                                        $row2 = $stmt->fetchAll();

                                                        foreach ($row2 as $output) { ?>
                                                            <option value="<?php echo $output['role_prev_id']; ?>"> <?php echo $output['role_prev_title']; ?> </option>
                                                        <?php
                                                        }
                                                    } else {
                                                        $stmt = $conn->prepare("SELECT
    role_prev_id,
     role_prev_title
   FROM
     tbl_role_privilege WHERE restriction_level='0'");
                                                        $stmt->execute();
                                                        $row2 = $stmt->fetchAll();

                                                        foreach ($row2 as $output) { ?>
                                                            <option value="<?php echo $output['role_prev_id']; ?>"> <?php echo $output['role_prev_title']; ?> </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>



                                                </select>

                                                <input id="id" name="id" type="hidden" value="<?php echo $_GET['id']; ?>">

                                                <div>

                                                    <br><br>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                        Select
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