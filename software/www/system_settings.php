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
                        <div class="col-lg-5 col-md-12 col-sm-12">
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h3>System Settings</h3>
                                   
                                    <a class="btn btn-primary btn-lg waves-effect waves-light icon-settings" href="system_settings_form.php"> Add System Settings </a> <br><br>
                                </div>


                                <!--------------------
                      START - Table with actions
                      -------------------->
                                <div class="table-responsive">
                                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">

                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th>
                                                     Embargo Duration
                                                </th>
                                                <th>
                                                     Embargo Archive Days
                                                </th>
                                                <th>
                                                    Number Of Emails Per Hour
                                                </th>
                                                <th>
                                                    Max Campaign per Server
                                                </th>
                                                <th>
                                                   Email Prefix
                                                </th>
                                                <th>
                                                    IP Switch Criteria
                                                </th>
                                                <th>
                                                  Data Loading Type
                                                </th>
                                                <th>
                                                    Max records

                                                </th>
                                                <th>
                                                    Mailservers MIN allowed IPs

                                                </th>
                                                <th>
                                                    Max Allowed Score

                                                </th>
                                                <th>
                                                    Allowed Color

                                                </th>
                                                <th>
                                                    API Key
                                                </th>
                                                <th>
                                                    URL
                                                </th>
                                                <th>
                                                    Status

                                                </th>
                                                <th>
                                                    Action
                                                </th>
                                            </tr>

                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <?php

                                                $sql = "SELECT *
										FROM `system_setting` ";


                                                $stmt = $conn->prepare($sql);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {
                                                    $result = $stmt->fetchAll();



                                                    foreach ($result as $row) {    ?>



                                                        <td><?php echo $row["embargo_duration"] ?></td>
                                                        <td><?php echo $row["embargo_archive_days"] ?></td>
                                                        <td><?php echo $row["instance_email_send"]; ?></td>
                                                        <td><?php echo $row["max_camp_per_server_percentage"]; ?></td>
                                                        <td><?php echo $row["ms_email_prefix"]; ?></td>
                                                        <td><?php 
                                                        
                                                        
                                                        if ($row["ip_switch_criteria"] == 'Sequential-Switch') {
                                                            $code = 'SS';
                                                        } 
                                                        elseif ($row["ip_switch_criteria"] == 'Hours') {
                                                            $code = 'HS';
                                                        }
                                                      
                                                        echo $row["ip_switch_criteria"];
                                                        echo "<br>";
                                                        echo "Code: " . $code; 
                                                        
                                                        
                                                        ?></td>

                                                        <td> <?php
                                                            if ($row["data_loading_type"] == 'Manual') {
                                                                $dlt = 'Manual';
                                                            } elseif ($row["data_loading_type"] == 'Automatic') {
                                                                $dlt = 'Automatic';
                                                            } elseif ($row["data_loading_type"] == 'Both') {
                                                                $dlt = 'Both';
                                                            } else {
                                                                $dlt = $row["data_loading_type"];
                                                            }

                                                            echo $dlt; ?></td>
                                                        <td><?php echo $row["max_records"]; ?></td>
                                                        <td><?php echo $row["mailserver_min_active_ips"]; ?></td>
                                                        <td><?php echo $row["ipblack_max_allowed_score"]; ?></td>
                                                        <td><?php echo $row["ipblack_allowed_color"]; ?></td>
                                                        <td><?php echo $row["api_key"]; ?></td>
                                                        <td><?php echo $row["url"]; ?></td>
                                                        <td><?php echo $row["status"]; ?></td>
                                                        <td>
                                                            <a href="editsystem_setting.php?system_settingid=<?php echo $row["system_settingid"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                                            <!-- <a href="deletesystem_settingid.php?system_settingid=<?php echo $row["system_settingid"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a> -->
                                                        </td>

                                            </tr>
                                    <?php    }
                                                }

                                    ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Javascript -->

    <script>
        function deleteclick() {
            return confirm("Do you want to delete this?")
        }
    </script>
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
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>
    
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