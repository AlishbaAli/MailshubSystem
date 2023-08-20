<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
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
                                   
                                    <a class="btn btn-primary btn-lg waves-effect waves-light icon-settings" href="system_settings_form.php"> Add System Settings </a> 
                                </div>


                                <!--------------------
                      START - Table with actions
                      -------------------->
                                <div class="table-responsive  card card-body">
                                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                       
                                        <tbody class=""> 
                                            
                                                <?php

                                                $sql = "SELECT *
										FROM `system_setting` ";


                                                $stmt = $conn->prepare($sql);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {
                                                    $result = $stmt->fetchAll();



                                                    foreach ($result as $row) {    ?>


                                                        <tr> <td>Embargo Duration:</td> <td> <?php echo $row["embargo_duration"] ?> </td> 
                                                        <td>Customised Campaign Embargo:</td> <td> <?php echo $row["customizable_camp_embargo"] ?> </td></tr>

                                                        <tr> <td>Embargo Archive Days: </td> <td> <?php echo $row["embargo_archive_days"] ?> </td> 
                                                        <td>Email Prefix </td> <td> <?php echo $row["ms_email_prefix"] ?> </td></tr>

                                                        <tr> <td> Mail Sending Execution: </td> <td> <?php echo $row["mail_sending_execution"]; ?> </td> 
                                                        <td> Number Of Emails Per Instance: </td> <td> <?php echo $row["instance_email_send"]; ?> </td></tr>

                                                        <tr> <td> Max Campaign per Server: </td> <td> <?php echo $row["max_camp_per_server_percentage"]; ?> </td>
                                                        <td> Email Send Criteria: </td> <td> <?php echo $row["email_send_criteria"]; ?> </td> </tr>
 
                                                        <tr> <td> Mailserver Min Active IPs: </td> <td> <?php echo $row["mailserver_min_active_ips"]; ?> </td> 
                                                        <td> Email Send Criteria Code:</td> <td> <?php echo $row["email_send_criteria_code"]; ?> </td> </tr>

                                                        <tr> <td> Domain Wise Email Send Filter: </td> <td><?php echo $row["domain_wise_email_send_filter"]; ?> </td>
                                                        <td> Email Qty Criteria: </td> <td> <?php echo $row["email_quantity_criteria"]; ?> </td> </tr>

                                                        <tr> <td> Domain Wise Email Send Offset: </td> <td><?php echo $row["domain_wise_email_send_offset"]; ?> </td>
                                                        <td> Email Qty Criteria Code: </td> <td> <?php echo $row["email_quantity_criteria_code"]; ?> </td> </tr>

                                                        <tr> <td> Max records: </td> <td><?php echo $row["max_records"]; ?> </td>
                                                        <td> Email Qty Lower Random Limit: </td> <td> <?php echo $row["email_qty_lower_random_limit"]; ?> </td> </tr>

                                                        <tr> <td> Data Loading Type: </td> <td> <?php
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
                                                            <td> Email Qty Upper Random Limit:</td> <td><?php echo $row["email_qty_upper_random_limit"]; ?></td>
                                                             </tr>

                                                        <tr> <td> Max Allowed Score: </td> <td><?php echo $row["ipblack_max_allowed_score"]; ?></td>
                                                        <td> Random Min Send Interval: </td> <td><?php echo $row["random_min_send_interval"]; ?></td> </tr>

                                                        <tr>  <td> Allowed Color: </td> <td><?php echo $row["ipblack_allowed_color"]; ?>
                                                        <td> Random Max Send Interval: </td> <td><?php echo $row["random_max_send_interval"]; ?></td> </tr>

                                                        <tr> <td> IP Switching Execution: </td> <td><?php echo $row["ip_switching_execution"]; ?></td> 
                                                        <td> Standard Send Interval: </td> <td><?php echo $row["standard_send_interval"]; ?></td></tr>

                                                        <tr> <td> IP Switch Criteria: </td> <td><?php echo $row["ip_switch_criteria"]; ?></td> 
                                                        <td> IP Selection Criteria: </td> <td><?php echo $row["ip_selection_criteria"]; ?></td></tr>

                                                        <tr> <td> IP Switch Criteria Code: </td> <td><?php echo $row["ip_switch_criteria_code"]; ?></td> 
                                                        <td> Ip Selection Criteria Code: </td> <td><?php echo $row["ip_selection_criteria_code"]; ?></td></tr>

                                                        <tr> <td> IP Switch Min Interval: </td> <td><?php echo $row["ip_switch_min_interval"]; ?></td> 
                                                        <td> Random IP Selection Offset: </td> <td><?php echo $row["random_ip_selection_offset"]; ?></td></tr>

                                                        <tr> <td> IP Switch Max Interval:</td> <td><?php echo $row["ip_switch_max_interval"]; ?></td> 
                                                        <td> Status:</td> <td><?php echo $row["status"]; ?></td> </tr>

                                                        <tr> <td> IP Switch Standard Interval:</td> <td><?php echo $row["ip_switch_standard_interval"]; ?></td> 
                                                        <td> Mail Execution Category: </td> <td><?php echo $row["mail_execution_category"]; ?> </td>  </tr>


                                                        <tr> <td> API Key: </td> <td><?php echo $row["api_key"]; ?></td>
                                                        <td> URL: </td> <td> <?php echo $row["url"]; ?> </td> </tr>


                                                        <tr> <td>  </td> <td>Action: </td>
                                                        <td>  <a href="editsystem_setting.php?system_settingid=<?php echo $row["system_settingid"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a> </td>
                                                         <td>
                                                           
                                                            <!-- <a href="deletesystem_settingid.php?system_settingid=<?php echo $row["system_settingid"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a> -->
                                                        </td> </tr>

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