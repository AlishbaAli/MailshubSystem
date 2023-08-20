<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['OS'])) {
    if ($_SESSION['OS'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

// if (isset($_SESSION['r_level'])) {
//     if ($_SESSION['r_level'] != "0") {
  
//         //User not logged in. Redirect them back to the login page.
//         header('Location: page-403.html');
//         exit;
//     }
//   }
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
                        <div class="col-lg-12 col-md-12 col-sm-12">
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h3>Organizational Settings</h3>
                                    <a class="btn btn-primary btn-lg waves-effect waves-light icon-settings" href="org_settings_form.php"> Add Organizational Settings </a> <br><br>
                                </div>


                                <!--------------------
                      START - Table with actions
                      -------------------->
                                <div class="table-responsive">
                                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">

                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th>
                                                    Organization
                                                </th>

                                                <th>
                                                    Embargo
                                                </th>

                                                <th>
                                                    Customizable Campaign Embargo
                                                </th>

                                                <th>
                                                    Embargo Implementation Type
                                                </th>
                                                <th>
                                                    Unsubscription Type
                                                </th>
                                                <th>
                                                    Domain Block Type
                                                </th>
                                                <th>
                                                    URL Block Type
                                                </th>
                                              
                                                <th>
                                                    Data Loading Type
                                                </th>
                                                <th>
                                                    Max records

                                                </th>
                                                <th>
                                                    IP Black Allowed

                                                </th>

                                                <th>
                                                    API
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
										FROM `orgunit-systemsetting` ";


                                                $stmt = $conn->prepare($sql);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {
                                                    $result = $stmt->fetchAll();



                                                    foreach ($result as $row) {
                                                        if (isset($_SESSION["orgunit_id"])) {

                                                            if ($_SESSION["orgunit_id"] != $row["orgunit_id"]) {
                                                                continue;
                                                            }
                                                        }
                                                ?>




                                                        <td><?php
                                                            if ($row["orgunit_id"] != NULL) {
                                                                $orgunit_id = $row["orgunit_id"];
                                                                $stmt = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id = $orgunit_id");
                                                                $stmt->execute();
                                                                $orgname = $stmt->fetch();
                                                                echo $orgname["orgunit_name"];
                                                            } ?></td>
                                                        <td style="text-align: left;"><?php
                                                                                        if ($row["embargo_duration_type"] == 'sys-defined') {
                                                                                            $oed = 'System Defined';
                                                                                        } 
                                                                                        elseif ($row["embargo_duration_type"] == 'ou-defined') {
                                                                                            $oed = 'Organizational Defined';
                                                                                        }
                                                                                      
                                                                                        else {
                                                                                            $oed = $row["embargo_duration_type"];
                                                                                        }


                                                                                        if ($row["embargo_implementation_type"] == 'sys-defined') {
                                                                                            $eit = 'System <br> Defined';
                                                                                        } else if ($row["embargo_implementation_type"] == 'ou-dedicated') {
                                                                                            $eit = 'Organizational <br> Dedicated';
                                                                                        } else if ($row["embargo_implementation_type"] == 'ou-hybrid') {
                                                                                            $eit = 'Organizational <br>Hybrid';
                                                                                        } else {
                                                                                            $eit = $row["embargo_implementation_type"];
                                                                                        }



                                                                                        echo "Duration: " . $row["org_embargo_duration"] . "<br><br>"; ?>
                                                           <span class="badge badge-info"> <?php echo "Type:<br> " . $oed . "<br>"; ?> </td>

                                                        <td> <?php echo $row['customizable_camp_embargo']; ?> </td>
                                                        <td><?php echo $eit; ?></td>


                                                        <td><?php
                                                            if ($row["unsubscription_type"] == 'sys-defined') {
                                                                $unt = 'System <br> Defined';
                                                            } else if ($row["unsubscription_type"] == 'ou-dedicated') {
                                                                $unt = 'Organizational <br> Dedicated';
                                                            } else if ($row["unsubscription_type"] == 'ou-hybrid') {
                                                                $unt = 'Organizational <br> Hybird';
                                                            } else {
                                                                $unt = $row["unsubscription_type"];
                                                            }

                                                            echo $unt; ?></td>

                                                        <td><?php $dbt = "";
                                                            if ($row["domain_block_type"] == 'sys-defined') {
                                                                $dbt = 'System <br> Defined';
                                                            } else if ($row["domain_block_type"] == 'ou-dedicated') {
                                                                $dbt = 'Organizational <br> Dedicated';
                                                            } else if ($row["domain_block_type"] == 'ou-hybrid') {
                                                                $dbt = 'Organizational <br> Hybird';
                                                            } else {
                                                                $dbt = $row["domain_block_type"];
                                                            }

                                                            echo $dbt; ?></td>
                                                               <td><?php $ubt = "";
                                                            if ($row["url_block_type"] == 'sys-defined') {
                                                                $ubt = 'System <br> Defined';
                                                            } else if ($row["url_block_type"] == 'ou-dedicated') {
                                                                $ubt = 'Organizational <br> Dedicated';
                                                            } else if ($row["url_block_type"] == 'ou-hybrid') {
                                                                $ubt = 'Organizational <br> Hybird';
                                                            } 

                                                            echo $ubt; ?></td>
                                                          
                                                                <td><?php
                                                            if ($row["data_loading_type"] == 'manual') {
                                                                $dlt = 'Manual';
                                                            } else if ($row["data_loading_type"] == 'automatic') {
                                                                $dlt = 'Automatic';
                                                            } else if ($row["data_loading_type"] == 'both') {
                                                                $dlt = 'Both';
                                                            } else {
                                                                $dlt = $row["data_loading_type"];
                                                            }

                                                            echo $dlt; ?></td>

                                                      

                                                                                      <td style="text-align: left;"><?php
                                                                                        if ($row["max_records_type"] == 'sys-defined') {
                                                                                            $mrt = 'System Defined';
                                                                                        } 
                                                                                        else if ($row["max_records_type"] == 'ou-defined') {
                                                                                            $mrt = 'Organizational Defined';
                                                                                        }
                                                                                      
                                                                                        else {
                                                                                            $mrt = $row["max_records_type"];
                                                                                        }


                                                                                  


                                                                                        echo "Max Records: " . $row["max_records"] . "<br> <br>"; ?>
                                                         <span class="badge badge-info">   <?php echo "Type:<br> " . $mrt . "<br>"; ?> </td>

                                                            <td style="text-align: left;"><?php
                                                                                        if ($row["ipblack_allowed_type"] == 'sys-defined') {
                                                                                            $iat = 'System Defined';
                                                                                        } 
                                                                                        else if ($row["ipblack_allowed_type"] == 'ou-defined') {
                                                                                            $iat = 'Organizational Defined';
                                                                                        }
                                                                                      
                                                                                        else {
                                                                                            $iat = $row["ipblack_allowed_type"];
                                                                                        }


                                                                                  


                                                                                        echo "Allowed score: " . $row["ipblack_max_allowed_score"] . "<br>"; 
                                                                                        echo "Allowed color: " . $row["ipblack_allowed_color"] . "<br> <br>"; ?>
                                                                                    
                                                                                     <span class="badge badge-info"> <?php echo "Type:<br> " . $iat . "<br>"; ?> </td>
                                                     
                                                      
                                                        <td><?php 
                                                          if ($row["api_type"] == 'sys-defined') {
                                                            $at = 'System Defined';
                                                        } 
                                                        else if ($row["api_type"] == 'ou-defined') {
                                                            $at = 'Organizational Defined';
                                                        }
                                                      
                                                       
                                                        
                                                        
 
                                                        
                                                        echo substr($row["api_key"], 0, 20);
                                                            echo "<br>"; 
                                                            echo substr($row["api_key"], 20, 40); 
                                                            echo "<br>";?>
                                                            <span class="badge badge-info"><?php echo "Type:<br>". $at ;
                                                            ?></td>
                                                        <td><?php echo substr($row["url"], 0, 20);
                                                            echo "<br>"; //length($row["api_key"])
                                                            echo substr($row["url"], 20, 40); ?></td>

                                                        <td><?php echo $row["status"]; ?></td>
                                                        <td>
                                                            <a href="editorg_setting.php?id=<?php echo $row["org_settingid"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                                            <!-- <a href="deleteorg_settingid.php?system_settingid=<?php echo $row["system_settingid"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a> -->
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

    <!-- <script>
        function deleteclick() {
            return confirm("Do you want to delete this?")
        }
    </script> -->
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