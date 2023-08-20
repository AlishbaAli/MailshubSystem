<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
// if (isset($_SESSION['OS'])) {
//     if ($_SESSION['OS'] == "NO") {

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
                            <h2>Add Organizational Settings</h2>
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
                                <div class="row">
                                    <div class="col-lg-6">


                                        <?php
                                        //get system_settings
                                        $sstmt = $conn->prepare("SELECT * FROM system_setting");
                                        $sstmt->execute();
                                        $sys_settings = $sstmt->fetch(); ?>
                                        <form id="advanced-form" action="org_settings_db.php" method="post" data-parsley-validate novalidate>
                                            <div class="form-group ">
                                                <label>Please Select the organzation above *</label>
                                                <?php if ($_SESSION['orgunit_id']) { ?>
                                                    <input hidden type="text" name="orgunit_id" value="<?php echo $_SESSION['orgunit_id'] ?>" class="form-control">
                                                <?php } else { ?>
                                                    <input hidden type="text" name="orgunit_id" class="form-control" required>
                                                <?php } ?>

                                            </div>
                                            <div class="form-group ">
                                                <label>Embargo Duration Type *</label>
                                                <select name="embargo_duration_type" id="embargo_duration_type" onChange=showHideEmbargo(<?php echo $sys_settings['embargo_duration'];?>) class="form-control" required>


                                                    <option value="sys-defined"> System Defined </option>
                                                    <option value="ou-defined"> Organizational Defined </option>

                                                </select>
                                            </div>



                                            <div class="form-group ">
                                                <label id="org_embargo_durationl">Embargo Duration (Days <=365) *</label>
                                                        <input value="<?php echo  $sys_settings["embargo_duration"]; ?>" type="text" id="org_embargo_duration" name="org_embargo_duration" class="form-control" data-parsley-max="365" readonly>

                                            </div>

                                            <div class="form-group ">
                                                <label>Embargo Implementation Type *</label>
                                                <select name="embargo_implementation_type" class="form-control" required>


                                                    <option value="sys-defined"> System Defined </option>
                                                    <option value="ou-dedicated"> Organizational Dedicated </option>
                                                    <option value="ou-hybrid"> Organizational Hybrid </option>

                                                </select>
                                            </div>
                                            <div class="form-group ">
                                                <label>Unsubscription Type *</label>
                                                <select name="unsubscription_type" class="form-control" required>


                                                    <option value="sys-defined"> System Defined </option>
                                                    <option value="ou-dedicated"> Organizational Dedicated </option>
                                                    <option value="ou-hybrid"> Organizational Hybrid </option>


                                                </select>
                                            </div>

                                        
                                            <div class="form-group ">
                                                <label>Maximum Records To Fetch Type*</label>
                                                <select name="max_records_type" onChange=showHideMaxR(<?php echo $sys_settings['max_records'];?>) id="max_records_type" class="form-control" required>

                                                    <option value="sys-defined"> System Defined </option>
                                                    <option value="ou-defined"> Organizational Defined</option>

                                                </select>
                                            </div>
                                            <div class="form-group ">
                                                <label id="max_recordsl">Maximum Records To Fetch (Records <=50000)*</label>
                                                        <input value="<?php echo  $sys_settings["max_records"]; ?>" type="text" name="max_records" id="max_records" class="form-control" required data-parsley-range="[1,50000]"readonly>

                                            </div>
                                            <div class="form-group ">
                                            <label> IP Black Allowed Type*</label>
                                            <select name="ipblack_allowed_type" onChange="showHideScoreColor('<?php echo  $sys_settings['ipblack_max_allowed_score']; ?>','<?php echo  $sys_settings['ipblack_allowed_color']; ?>')" id="ipblack_allowed_type" class="form-control" required>

                                                <option value="sys-defined"> System Defined </option>
                                                <option value="ou-defined"> Organizational Defined </option>

                                            </select>
                                        </div>

                                        <div class="form-group ">
                                            <label id="ipblack_max_allowed_scorel">Max Allowed Score(Score <=9)</label>
                                             <input value="<?php echo  $sys_settings["ipblack_max_allowed_score"]; ?>" type="text" id="ipblack_max_allowed_score" name="ipblack_max_allowed_score" class="form-control" required data-parsley-range="[0,9]"readonly>

                                        </div>
                                        <div class="form-group ">
                                            <label id="ipblack_allowed_colorl">Allowed Color</label>

                                            <input value="<?php echo  $sys_settings["ipblack_allowed_color"]; ?>" type="text" id="ipblack_allowed_color" name="ipblack_allowed_color" class="form-control" required readonly>
                                            <div id="allowed_color" ></div>
                                        </div>

                                    </div>
                                    <!---col end  --->
                                    <div class="col-lg-6">
                                    <div class="form-group ">
                                                <label>Domain Block Type *</label>
                                                <select name="domain_block_type" class="form-control" required>

                                                    <option value="sys-defined"> System Defined</option>
                                                    <option value="ou-dedicated">Organizational Dedicated</option>
                                                    <option value="ou-hybrid">Organizational Hybrid</option>

                                                </select>
                                            </div>
                                            <div class="form-group ">
                                                <label>URL Block Type *</label>
                                                <select name="url_block_type" class="form-control" required>

                                                    <option value="sys-defined"> System Defined</option>
                                                    <option value="ou-dedicated">Organizational Dedicated</option>
                                                    <option value="ou-hybrid">Organizational Hybrid</option>

                                                </select>
                                            </div>


                                            <div class="form-group ">
                                                <label>Data Loading Type *</label>
                                                <select name="data_loading_type" class="form-control" required>

                                                    <option value="Manual"> Manual</option>
                                                    <option value="Automatic">Automatic</option>
                                                    <option value="Both">Both</option>

                                                </select>
                                            </div>

                                     
                                            <div class="form-group ">
                                                <label>Api Type*</label>
                                                <select name="api_type" onChange="showHideApiUrl('<?php echo $sys_settings['api_key'];?>','<?php echo $sys_settings['url'];?>')" id="api_type" class="form-control" required>

                                                    <option value="sys-defined"> System Defined </option>
                                                    <option value="ou-defined"> Organizational Defined</option>

                                                </select>
                                            </div>
                                        <div class="form-group ">
                                            <label id="api_keyl">API Key</label>
                                            <input value="<?php echo  $sys_settings["api_key"]; ?>" type="text" name="api_key" id="api_key" class="form-control" readonly>

                                        </div>
                                        <div class="form-group ">
                                            <label id="urll">URL</label>
                                            <input value="<?php echo  $sys_settings["url"]; ?>" type="text" name="url" id="url" class="form-control" readonly>

                                        </div>










                                        <br>

                                        <div class="form-group mb-0">
                                            <div>
                                                <button class="btn btn-primary btn-lg waves-effect waves-light icon-plus" type="submit" id="submit">
                                                    Add 
                                                </button>

                                            </div>
                                        </div>
                                        </form>
                                    </div>

                                </div>

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
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script>
            $(document).ready(function(){

$("#ipblack_allowed_color").change(function(){


    var ipblack_allowed_color = $(this).val().trim();

  

if((ipblack_allowed_color == 'green') || (ipblack_allowed_color == 'red') || (ipblack_allowed_color == 'yellow') || (ipblack_allowed_color == 'orange')){
    $("#allowed_color").html("");
 
       
}else{
    response= "<span style='color: red;'>Enter color in small letters from this list(green,yellow,red,orange).</span>";


$("#allowed_color").html(response);
document.getElementById("ipblack_allowed_color").value = "";

  
}

});

});
</script>
    
    <script>


function showHideApiUrl(api_key,url) {

          
          var api_type = document.getElementById("api_type").value;
          if (api_type == "ou-defined") {
              document.getElementById("api_key").readOnly = false;
              document.getElementById("url").readOnly = false;
            
          } else {
              document.getElementById("api_key").readOnly = true;
              document.getElementById("api_key").value = api_key;
              document.getElementById("url").readOnly = true;
             
             document.getElementById("url").value = url;
       
         
        
          }

        

      }

   

        function showHideScoreColor(score,color) {
          
            var ipblack_allowed_type = document.getElementById("ipblack_allowed_type").value;
            if (ipblack_allowed_type == "ou-defined") {
                document.getElementById("ipblack_max_allowed_score").readOnly = false;
                document.getElementById("ipblack_allowed_color").readOnly = false;
              
            } else {
                document.getElementById("ipblack_max_allowed_score").readOnly = true;
                document.getElementById("ipblack_max_allowed_score").value = score;
                document.getElementById("ipblack_allowed_color").readOnly = true;
               
               document.getElementById("ipblack_allowed_color").value = color;
         
           
          
            }

          

        }

      
        function showHideEmbargo(embargo) {

            var embargo_duration_type = document.getElementById("embargo_duration_type").value;

            if (embargo_duration_type == "ou-defined") {
                document.getElementById("org_embargo_duration").readOnly = false;
            } else {
                document.getElementById("org_embargo_duration").readOnly = true;
                document.getElementById("org_embargo_duration").value = embargo;
            }

        }
        function showHideMaxR(max_record) {

            var max_records_type = document.getElementById("max_records_type").value;
            if (max_records_type == "ou-defined") {
                document.getElementById("max_records").readOnly = false;
            } else {
                document.getElementById("max_records").readOnly = true;
                document.getElementById("max_records").value = max_record;
            }
        }
    </script>


    <!-- Javascript -->
    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/parsleyjs/js/parsley.min.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
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