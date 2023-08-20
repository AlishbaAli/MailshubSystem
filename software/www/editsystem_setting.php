<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['SS'])) {
    if ($_SESSION['SS'] == "NO") {

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
                            <h2>Add System Settings</h2>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php $id = $_GET['system_settingid'];

                $stmt = $conn->prepare("SELECT * FROM system_setting WHERE system_settingid= $id");
                $stmt->bindValue(':system_settingid', $id);
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

                                <form id="advanced-form" action="editsystem_settings_db.php" method="post" data-parsley-validate novalidate>

                                    <div class="form-group ">
                                        <label> Embargo Duration(Days <=365) *</label>
                                                <input type="text" id="text-input3" name="embargo_duration" value="<?php echo $row['embargo_duration'] ?>" class="form-control" required data-parsley-max="365">

                                    </div>
                                    <div id="response3" class="form-group ">

                                    <div class="form-group ">
                                        <label>Number of Emails Per Hour (Emails <=200) *</label>
                                                <input type="text" name="hourly_email_send" class="form-control" value="<?php echo $row['hourly_email_send'] ?>" required data-parsley-max="200">

                                    </div>
                                    <div class="form-group ">
                                    <label>IP Switch Criteria *</label>
                                        <select name="ip_switch_criteria" class="form-control" required>

                                           
                                            <option value="Standard-Switch" <?php if ($row['ip_switch_criteria'] == "Standard-Switch") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Standard-Switch</option>
                                            <option value="Random-Switch" <?php if ($row['ip_switch_criteria'] == "Random-Switch") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Random-Switch</option>

                                        </select>
                                    </div>

                                    <div class="form-group ">
                                    <label>Data Loading Type  *</label>
                                    <select name="data_loading_type" class="form-control" required>
                                    
                                      
                                        <option value="Both" <?php if ($row['data_loading_type'] == "Both") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Both</option>
                                        <option value="Manual" <?php if ($row['data_loading_type'] == "Manual") {
                                                                            echo ' selected="selected"';
                                                                        } ?>>Manual</option>
                                         <option value="Automatic" <?php if ($row['data_loading_type'] == "Automatic") {
                                                                            echo ' selected="selected"';
                                                                        } ?>>Automatic</option>
                                    
                                    </select>
                                    </div>

                                    <div class="form-group ">
                                        <label>Maximum Records To Fetch (Records <=50000)*</label>
                                                <input type="text" name="max_records" class="form-control" value="<?php echo $row['max_records'] ?>" required data-parsley-max="50000">

                                    </div>

                                    <div class="form-group ">
                                        <label>Max Allowed Score(Score <=9)</label>
                                                <input type="text" name="ipblack_max_allowed_score" value="<?php echo $row['ipblack_max_allowed_score'] ?>" class="form-control" required data-parsley-max="9">

                                    </div>
                                    <div class="form-group ">
                                        <label>Allowed Color</label>
                                        <input type="text" name="ipblack_allowed_color" value="<?php echo $row['ipblack_allowed_color'] ?>" class="form-control">

                                    </div>
                                    <div class="form-group ">
                                        <label>API Key</label>
                                        <input type="text" name="api_key" value="<?php echo $row['api_key'] ?>" class="form-control" required>

                                    </div>
                                    <div class="form-group ">
                                        <label>URL</label>
                                        <input type="text" name="url" value="<?php echo $row['url'] ?>" class="form-control" required>

                                    </div>


                                    <select name="status" class="form-control" required>

                                        <label>Status*</label>
                                        <option value="Active" <?php if ($row['status'] == "Active") {
                                                                    echo ' selected="selected"';
                                                                } ?>>Active</option>
                                        <option value="In Active" <?php if ($row['status'] == "In Active") {
                                                                        echo ' selected="selected"';
                                                                    } ?>>In Active</option>

                                    </select>
                            </div>




                            <input hidden type="text" name="id" class="form-control" value="<?php echo $id ?>" required>



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
            <!-- end row -->


            <!---Add code here-->




        </div>
    </div>

    </div>

    <!-- Javascript -->




    <!-- Javascript -->
    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/parsleyjs/js/parsley.min.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script>
        $('#instance_email_send').on('keyup', function() {
            var mcs = $(this).val();

            $.ajax({
                url: "system_settings_form.php",
                type: "POST",
                data: {
                    mcs: mcs
                }

            }).done(function(data) {

                $("#response4").html(data);
            });

        });
    </script>

    <script>
        $('#embargo_duration').on('keyup', function() {
            var ead = $(this).val();

            $.ajax({
                url: "system_settings_form.php",
                type: "POST",
                data: {
                    ead: ead
                }

            }).done(function(data) {

                $("#response3").html(data);
            });

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