<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
// if (isset($_SESSION['SS'])) {
//     if ($_SESSION['SS'] == "NO") {

//         //User not logged in. Redirect them back to the login page.
//         header('Location: page-403.html');
//         exit;
//     }
// }

if (isset($_SESSION['r_level'])) {
    if ($_SESSION['r_level'] != "0") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
} 
?>

<?php //IP Pool Ajax
if (isset($_POST['mcs'])) {
    if (!empty($_POST['mcs'])) {
        $email_per_hour = $_POST['mcs'];
    } else {
        $email_per_hour = 0;
    }

    echo '<label>Max Allowed Campaigns Per Server(Value <=' . $email_per_hour . ') *</label>';
    echo '<input type="number" id="max_camp_per_server_percentage" name="max_camp_per_server_percentage" class="form-control" 
                                                    required min="1" max="' . $email_per_hour . '" data-parsley-max="' . $email_per_hour . '">';

    exit;
}

?>
<?php //IP Pool Ajax
if (isset($_POST['ead'])) {
    if (!empty($_POST['ead'])) {
        $embargo_A = $_POST['ead'] + 30;
    } else {
        $embargo_A = 0;
    }

    echo '<label> Embargo Archive Days(Days <=365) *</label>';
    echo '<input type="number" id="text-input3" name="embargo_archive_days" class="form-control" 
                                                    required min="1" max="' . $embargo_A . '" value="' . $embargo_A . '" data-parsley-max="' . $embargo_A . '">';

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

                <!---Add code here-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                    <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->

                                <form id="advanced-form" action="system_settings_db.php" method="post" data-parsley-validate novalidate>
                                    <div class="row">
                                        <div class=" card col-6">


    <!-- email_send_criteria
	email_send_criteria_code
	email_quantity_criteria
	email_quantity_criteria_code
	random_min_send_interval
	random_max_send_interval
	standard_send_interval
	email_qty_lower_random_limit
	email_qty_upper_random_limit 
	ip_switch_min_interval
	ip_switch_max_interval
	ip_switch_standard_interval-->

    <!-- 45 - 60
60 - 75
45 - 75
0.5 - 0.7
0.7 - 1 -->
                                            <div class="form-group ">
                                                <label>Email Send Criteria *</label>
                                                        <!-- <input type="text" id="email_send_criteria" name="email_send_criteria"
                                                         class="form-control" required data-parsley-max="365"> -->
                                                    <select name="email_send_criteria" id="email_send_criteria" class="form-control" required>
                                                    <option selected value="Standard-Send-Time"> Standard-Send-Time </option>
                                                    <option value="Random-Send-Time"> Random-Send-Time </option>
                                                    <!-- <option value="Automatic"> Automatic </option> -->

                                                </select>
                                            </div>
                                            <!-- <div class="form-group ">
                                                <label>Email Send Criteria Code *</label>
                                                        <input type="text" id="email_send_criteria_code" name="email_send_criteria_code"
                                                         class="form-control" required data-parsley-max="365">

                                                    <select name="email_send_criteria" id="email_send_criteria" class="form-control" required>
                                                    <option selected value="Standard-Send-Time"> Standard-Send-Time </option>
                                                    <option value="Random-Send-Time"> Random-Send-Time </option>
                                            </div> -->
                                            <div class="form-group ">
                                                <label>Email Quantity Criteria *</label>
                                                        <!-- <input type="text" id="email_quantity_criteria" name="email_quantity_criteria"
                                                         class="form-control" required data-parsley-max="365"> -->

                                                    <select name="email_quantity_criteria" id="email_quantity_criteria" class="form-control" required>
                                                    <option selected value="Standard-Qunatity"> Standard-Qunatity </option>
                                                    <option value="Random-Qunatity"> Random-Qunatity </option>
                                                    </select>
                                            </div>
                                            <!-- <div class="form-group ">
                                                <label>Email Quantity Criteria Code *</label>
                                                        <input type="text" id="email_quantity_criteria_code" name="email_quantity_criteria_code"
                                                         class="form-control" required data-parsley-max="365">
                                            </div> -->
                                            <div class="form-group ">
                                                <label>Random Min Send Interval *</label>
                                                        <input type="number" step="1" min="45" max="60" value="45" id="random_min_send_interval" name="random_min_send_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>Random Max Send Interval *</label>
                                                        <input type="number" step="1" min="60" max="75" value="75" id="random_max_send_interval" name="random_max_send_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>Standard Send Interval *</label>
                                                        <input type="number" step="0.1" min="45" max="75" value="60" id="standard_send_interval" name="standard_send_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>Email Qty Lower Random Limit *</label>
                                                        <input type="number" step="0.1" min="0.5" max="0.7" value="0.5" id="email_qty_lower_random_limit" name="email_qty_lower_random_limit"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>Email Qty Upper Random Limit *</label>
                                                        <input type="number" step="0.1" min="0.7" max="1.0" value="1.0" id="email_qty_upper_random_limit" name="email_qty_upper_random_limit"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>

                                            <div class="form-group ">
                                                <label>IP Switch Min Interval *</label>
                                                        <input type="number" step="1" min="45" max="60" value="45" id="ip_switch_min_interval" name="ip_switch_min_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>IP Switch Max Interval *</label>
                                                        <input type="number" step="1" min="60" max="75" value="75" id="ip_switch_max_interval" name="ip_switch_max_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>IP Switch Standard Interval *</label>
                                                        <input type="number" step="0.1" min="45" max="75" value="60" id="ip_switch_standard_interval" name="ip_switch_standard_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>

                                        </div>

                                        <div class="card col-6">
                                            <div class="form-group ">
                                                <label>Embargo Duration(Days <=365) *</label>
                                                        <input type="number" id="embargo_duration" name="embargo_duration" class="form-control" required data-parsley-max="365">

                                            </div>
                                            <div id="response3" class="form-group ">


                                            </div>
                                            <div class="form-group ">
                                                <label>Number of Emails Per Hour (Emails <=50) *</label>
                                                        <input type="number" id="instance_email_send" name="instance_email_send" min="1" max="50" class="form-control" required data-parsley-max="50">

                                            </div>
                                            <div id="response4" class="form-group ">


                                            </div>
                                            <div class="form-group ">
                                                <label> Mailservers Minimum allowed IPs (IPs >=04) *</label>
                                                <input type="text" name="mailserver_min_active_ips" class="form-control" required data-parsley-min="4">

                                            </div>

                                            <div class="form-group ">
                                                <label> Email Prefix *</label>
                                                <input type="text" name="ms_email_prefix" class="form-control">

                                            </div>

                                            <div class="form-group ">
                                                <label>IP Switch Criteria *</label>
                                                <select name="ip_switch_criteria" class="form-control" required>

                                                    <option value="Sequential-Switch"> Sequential-Switch </option>
                                                    <option value="Hours"> Hours </option>

                                                </select>
                                            </div>
                                            <div class="form-group ">
                                                <label>Data Loading Type *</label>
                                                <select name="data_loading_type" class="form-control" required>
                                                    <option value="Both"> Both </option>
                                                    <option value="Manual"> Manual </option>
                                                    <option value="Automatic"> Automatic </option>

                                                </select>
                                            </div>


                                            <div class="form-group ">
                                                <label>Maximum Records To Fetch (Records <=50000)* </label>
                                                        <input type="text" name="max_records" class="form-control" required data-parsley-max="50000">

                                            </div>
                                            <div class="form-group ">
                                                <label>Max Allowed Score(Score <=9) </label>
                                                        <input type="text" name="ipblack_max_allowed_score" class="form-control" required data-parsley-max="9">

                                            </div>
                                            <div class="form-group ">
                                                <label>Allowed Color</label>
                                                <input type="text" name="ipblack_allowed_color" class="form-control">

                                            </div>
                                            <div class="form-group ">
                                                <label>API Key</label>
                                                <input type="text" name="api_key" class="form-control" required>

                                            </div>
                                            <div class="form-group ">
                                                <label>URL</label>
                                                <input type="text" name="url" class="form-control" required>

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