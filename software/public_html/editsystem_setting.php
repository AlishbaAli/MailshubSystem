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
if (isset($_SESSION['ESS'])) {
    if ($_SESSION['ESS'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
} 
?> 

<?php
if (isset($_POST['mcs'])) {
    $stmt = $conn->prepare("SELECT * FROM system_setting "); 
 $stmt->execute();
 $row = $stmt->fetch();

      if (!empty($_POST['mcs'])) {
          $email_per_hour = $_POST['mcs'];
      } else {
          $email_per_hour = $row['max_camp_per_server_percentage'];
      }
  $camp= $row['max_camp_per_server_percentage'];
      echo '<label>Max Allowed Campaigns Per Server(Value <=' . $email_per_hour . ') *</label>';
      echo '<input type="number" id="max_camp_per_server_percentage" name="max_camp_per_server_percentage" class="form-control" 
             value="'.$camp.'"  required min="1" max="' . $email_per_hour . '" data-parsley-max="'. $email_per_hour .'">';
  
      exit;
  }
  ?>
  <?php
  //IP Pool Ajax
  if (isset($_POST['ead'])) {
    $stmt = $conn->prepare("SELECT * FROM system_setting ");
    $stmt->execute();
    $row = $stmt->fetch();

      if (!empty($_POST['ead'])) {
          $embargo_A = $_POST['ead'] + 30;
      } else {
          $embargo_A = $row['embargo_archive_days'];
      }
      $camp= $row['embargo_archive_days'];
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
                            <h2>Edit System Settings</h2>
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
                   <div class="card">
                        <div class="card-body row">
                            <div class="col-lg-6">
                                <!-- <h4 class="card-title">Validation type</h4>
                                <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->
     <!--++ embargo_duration=:embargo_duration, ++
     ++embargo_archive_days=:embargo_archive_days,++

     ++customizable_camp_embargo=:customizable_camp_embargo,
     ++mail_sending_execution=:mail_sending_execution,

     ++max_camp_per_server_percentage = :max_camp_per_server_percentage,
     ++mailserver_min_active_ips = :mailserver_min_active_ips,
     ++max_records = :max_records,
     ++data_loading_type =:data_loading_type,

     ++ipblack_max_allowed_score=:ipblack_max_allowed_score,  
     ++ipblack_allowed_color=:ipblack_allowed_color, 

     ++ip_switching_execution = :ip_switching_execution,
     ++ip_switch_criteria =:ip_switch_criteria,
     --ip_switch_criteria_code = :ip_switch_criteria_code, 
     ++ip_switch_min_interval = :ip_switch_min_interval, 
     ++ip_switch_max_interval = :ip_switch_max_interval,
     ++ip_switch_standard_interval = :ip_switch_standard_interval,

     ++ip_selection_criteria = :ip_selection_criteria,
     --ip_selection_criteria_code = :ip_selection_criteria_code,
     ++random_ip_selection_offset = :random_ip_selection_offset,

     ++ms_email_prefix=:ms_email_prefix,
     ++instance_email_send=:instance_email_send, 
     ++email_send_criteria =:email_send_criteria,
     --email_send_criteria_code =:email_send_criteria_code,
     ++email_qty_lower_random_limit =:email_qty_lower_random_limit,
     ++email_qty_upper_random_limit =:email_qty_upper_random_limit,

     ++random_min_send_interval = :random_min_send_interval,
     ++random_max_send_interval = :random_max_send_interval,
     ++standard_send_interval = :standard_send_interval,

     ++api_key=:api_key, 
     ++url=:url,  
     ++status=:status  --><?php
 
//  $stmt = $conn->prepare("SELECT * FROM system_setting ");
//  $stmt->execute();
//  $row = $stmt->fetch();
if (isset($_GET['system_settingid'])) {
$id = $_GET['system_settingid'];

$stmt = $conn->prepare("SELECT * FROM system_setting WHERE system_settingid= $id");
$stmt->bindValue(':system_settingid', $id);
$stmt->execute();
$row = $stmt->fetch();
}
?>
                                <form id="advanced-form" action="editsystem_settings_db.php" method="post" data-parsley-validate novalidate>

                                    <div class="form-group ">
                                        <label> Embargo Duration(Days <=365) *</label>
                                                <input type="text" id="embargo_duration" name="embargo_duration" 
                                                value="<?php echo $row['embargo_duration']; ?>" class="form-control" required data-parsley-max="365">
                                    </div>
                                    <div id="response3" class="form-group "> </div>

                                   

                                    <div class="form-group ">
                                    <label>Mail Sending Execution *</label>
                                        <select name="mail_sending_execution" class="form-control" required>  
                                            <option value="Enable" <?php if ($row['mail_sending_execution'] == "Enable") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Enable</option>
                                            <option value="Disable" <?php if ($row['mail_sending_execution'] == "Disable") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Disabled</option>

                                        </select>
                                    </div>

                                    <div class="form-group ">
                                        <label>Mailserver Min Active IPs *</label>
                                                <input type="text" name="mailserver_min_active_ips" class="form-control" 
                                                value="<?php echo $row['mailserver_min_active_ips']; ?>" required data-parsley-max="4">
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
                                        <label>Email Prefix *</label>
                                                <input type="text" name="ms_email_prefix" class="form-control" 
                                                value="<?php echo $row['ms_email_prefix'] ?>" required >
                                    </div>

                                    <div class="form-group ">
                                        <label>Number of Emails Per Instance (Emails <=200) *</label>
                                                <input type="text" id="instance_email_send" name="instance_email_send" class="form-control" 
                                                value="<?php echo $row['instance_email_send'] ?>" required data-parsley-max="200">
                                    </div>
                                    <div id="response4" class="form-group "> </div>

                                    <div class="form-group ">
                                                <label>Mail Execution Category *</label>
                                                      
                                                <select name="mail_execution_category" id="mail_execution_category" class="form-control" required>
                                                    
                                                    <option <?php if ($row['mail_execution_category'] == "Mailserver-Wise") {
                                                      echo 'selected="selected"'; } ?> value="Mailserver-Wise"> Mailserver-Wise</option>

                                                    <option <?php if ($row['mail_execution_category'] == "Campaign-Wise") {
                                                      echo 'selected="selected"'; } ?> value="Campaign-Wise"> Campaign-Wise </option>
                                                  
                                                </select>
                                    </div>
                                         
                                    <div class="form-group ">
                                                <label>Email Send Criteria *</label>
                                                      
                                                    <select name="email_send_criteria" id="email_send_criteria" class="form-control" required>

                                                    <option <?php if ($row['email_send_criteria'] == "Standard-Send-Time") {
                                                      echo ' selected="selected"'; } ?> value="Standard-Send-Time"> Standard-Send-Time </option>

                                                    <option <?php if ($row['email_send_criteria'] == "Random-Send-Time") {
                                                      echo ' selected="selected"'; } ?> value="Random-Send-Time"> Random-Send-Time </option>
                                                  
                                                </select>
                                            </div>
                                         
                                            <div class="form-group ">
                                                <label>Email Quantity Criteria *</label>
                                                        
                                                    <select name="email_quantity_criteria" id="email_quantity_criteria" class="form-control" required>
                                                    <option value="Standard-Quantity" <?php if ($row['email_quantity_criteria'] == "Standard-Quantity") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>> Standard-Quantity </option>
                                                    <option value="Random-Quantity" <?php if ($row['email_quantity_criteria'] == "Random-Quantity") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>> Random-Quantity </option>
                                                    </select>
                                            </div>

                                            <div class="form-group ">
                                                <label>Email Qty Lower Random Limit *</label>
                                                        <input type="number" step="0.1" min="0.5" max="0.7" value="<?php echo $row['email_qty_lower_random_limit'] ?>"
                                                          id="email_qty_lower_random_limit" name="email_qty_lower_random_limit"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>Email Qty Upper Random Limit *</label>
                                                        <input type="number" step="0.1" min="0.7" max="1.0" value="<?php echo $row['email_qty_upper_random_limit'] ?>" 
                                                         id="email_qty_upper_random_limit" name="email_qty_upper_random_limit"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            
                                            <div class="form-group ">
                                                <label>Random Min Send Interval *</label>
                                                        <input type="number" step="1" min="45" max="60" value="<?php echo $row['random_min_send_interval'] ?>" 
                                                         id="random_min_send_interval" name="random_min_send_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>Random Max Send Interval *</label>
                                                        <input type="number" step="1" min="60" max="75" value="<?php echo $row['random_max_send_interval'] ?>" 
                                                         id="random_max_send_interval" name="random_max_send_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>Standard Send Interval *</label>
                                                        <input type="number" step="1" min="45" max="75" value="<?php echo $row['standard_send_interval'] ?>" 
                                                         id="standard_send_interval" name="standard_send_interval"
                                                         class="form-control" required data-parsley-max="365">
                                                         
                                            </div>
                                            
              </div>      <div class="col-lg-6">

                                           <div class="form-group ">
                                                <label>Domain Wise Email Send Filter *</label>
                                                    <select name="domain_wise_email_send_filter" class="form-control" required>  
                                                        <option value="YES" <?php if ($row['domain_wise_email_send_filter'] == "YES") {
                                                                                    echo ' selected="selected"';
                                                                                } ?> >YES</option>
                                                        <option value="NO"  <?php if ($row['domain_wise_email_send_filter'] == "NO") {
                                                                                    echo ' selected="selected"';
                                                                                } ?> >NO</option>

                                                    </select> 
                                            </div>

                                           
                                            <div class="form-group ">
                                                <label>Domain Wise Email Send Offset (1-5) *</label>
                                                 <input type="number" value="<?php echo $row['domain_wise_email_send_offset'] ?>"  name="domain_wise_email_send_offset" class="form-control" min="1" max="5" required data-parsley-max="5">

                                            </div>

                                    <div class="form-group ">
                                        <label> Customizable Organization Embargo *</label>
                                                <!-- <input type="text" id="text-input3" name="customizable_camp_embargo" 
                                                value="<?php echo $row['customizable_org_embargo'] ?>" class="form-control" required data-parsley-max="365"> -->

                                                <select name="customizable_org_embargo" class="form-control" required>  
                                            <option value="YES" <?php if ($row['customizable_org_embargo'] == "YES") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>YES</option>
                                            <option value="NO" <?php if ($row['customizable_org_embargo'] == "NO") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>NO</option>

                                        </select>
                                    </div>
                                    <div class="form-group ">
                                        <label> Customizable Campaign Embargo *</label>
                                                <!-- <input type="text" id="text-input3" name="customizable_camp_embargo" 
                                                value="<?php echo $row['customizable_camp_embargo'] ?>" class="form-control" required data-parsley-max="365"> -->

                                                <select name="customizable_camp_embargo" class="form-control" required>  
                                            <option value="YES" <?php if ($row['customizable_camp_embargo'] == "YES") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>YES</option>
                                            <option value="NO" <?php if ($row['customizable_camp_embargo'] == "NO") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>NO</option>

                                        </select>
                                    </div>
                                    
                                    <div class="form-group ">
                                    <label>IP Switching Execution *</label>
                                        <select name="ip_switching_execution" class="form-control" required>  
                                            <option value="Enable" <?php if ($row['ip_switching_execution'] == "Enable") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>Enable</option>
                                            <option value="Disable" <?php if ($row['ip_switching_execution'] == "Disable") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Disable</option>

                                        </select>
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
                                                <label>IP Switch Min Interval *</label>
                                                        <input type="number" step="1" min="45" max="60" value="<?php echo $row['ip_switch_min_interval'] ?>" id="ip_switch_min_interval" name="ip_switch_min_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>IP Switch Max Interval *</label>
                                                        <input type="number" step="1" min="60" max="75" value="<?php echo $row['ip_switch_max_interval'] ?>" id="ip_switch_max_interval" name="ip_switch_max_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                            <div class="form-group ">
                                                <label>IP Switch Standard Interval *</label>
                                                        <input type="number" step="1" min="45" max="75" value="<?php echo $row['ip_switch_standard_interval'] ?>" id="ip_switch_standard_interval" name="ip_switch_standard_interval"
                                                         class="form-control" required data-parsley-max="365">
                                            </div>
                                   
                                            <div class="form-group ">
                                    <label>IP Selection Criteria *</label>
                                        <select name="ip_selection_criteria" class="form-control" required>  
                                            <option value="Sequential-Selection" <?php if ($row['ip_selection_criteria'] == "Sequential-Selection") {
                                                                                    echo 'selected="selected"';
                                                                                } ?>>Sequential-Selection</option>
                                            <option value="Random-Selection" <?php if ($row['ip_selection_criteria'] == "Random-Selection") {
                                                                                echo 'selected="selected"';
                                                                            } ?>>Random-Selection</option>

                                        </select>
                                    </div>
                                    
                                    <div class="form-group ">
                                                <label>Random IP Selection Offset *</label>
                                                        <input type="number" step="1" min="4" max="7" value="<?php echo $row['random_ip_selection_offset'] ?>" id="random_ip_selection_offset" name="random_ip_selection_offset"
                                                         class="form-control" required data-parsley-max="365">
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


                                    <label>Status*</label>
                                    <select name="status" class="form-control" required>

                                        <option value="Active" <?php if ($row['status'] == "Active") {
                                                                    echo ' selected="selected"';
                                                                } ?>>Active</option>
                                        <option value="In Active" <?php if ($row['status'] == "In Active") {
                                                                        echo ' selected="selected"';
                                                                    } ?>>In Active</option>

                                    </select>

                                    <input hidden type="text" name="id" class="form-control" value="<?php echo $id ?>" required>

                                    <br>

                                  
                            </div>

                            <div class="form-group mb-0 col-12 float-right">
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
                url: "editsystem_setting.php",
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
                url: "editsystem_setting.php",
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