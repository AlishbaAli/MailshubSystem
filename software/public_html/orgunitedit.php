<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['EO'])) {
    if ($_SESSION['EO'] == "NO") {

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
                            <h2>Update Organizational Unit</h2>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php $id = $_GET['orgunit_id'];

                $stmt = $conn->prepare("SELECT * FROM  tbl_organizational_unit WHERE orgunit_id= $id");
                $stmt->bindValue(':orgunit_id', $id);
                $stmt->execute();
                $row = $stmt->fetch();


                $system_entityid=$row["system_entityid"];
                $stmtt = $conn->prepare("SELECT * FROM  system_entity WHERE system_entityid= :system_entityid");
                $stmtt->bindValue(':system_entityid', $system_entityid);
                $stmtt->execute();
                $row2 = $stmtt->fetch();



                ?>
                <!---Add code here-->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->

                                <form id="advanced-form" action="orgunitedit_db.php" method="post">

                                    <div class="form-group ">
                                        <label>Organization Name *</label>
                                        <input type="text" name="orgunit_name" class="form-control" value="<?php echo $row['orgunit_name'] ?>"  required>

                                    </div>
                                    <div class="form-group ">
                                        <label>Organization Code *</label>
                                        <input type="text" name="orgunit_code" class="form-control" value="<?php echo $row['orgunit_code'] ?>"  required>

                                    </div>


                                    <!-- <div class="form-group ">
                                        <label>System Entity *</label>
                                        <select name="system_entityid" value="<?php echo $row['system_entityid'] ?>" class="form-control"  required>
                                            <option value="2" <?php if ($row['system_entityid'] == "2") {
                                                                    echo ' selected="selected"';
                                                                } ?>>Operation</option>
                                            <option value="3" <?php if ($row['system_entityid'] == "3") {
                                                                    echo ' selected="selected"';
                                                                } ?>>Technical</option>


                                        </select>
                                    </div> -->

                                    <div class="form-group ">
                                        <label>System Entity *</label>
                                        <input type="text" name="system_entity_type" class="form-control" value="<?php echo $row2['system_entity_type'] ?>"  readonly required>

                                    </div>

                                    <?php if ($row['system_entityid'] != "3") { ?>
                                    <div class="form-group ">
                                        <label>System Setting *</label>
                                        <select name="system_setting" class="form-control" value="<?php echo $row['system_setting'] ?>" required>

                                            <option value="sys-defined" <?php if ($row['system_setting'] == "sys-defined") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>System Defined</option>
                                            <option value="ou-defined" <?php if ($row['system_setting'] == "ou-defined") {
                                                                        echo ' selected="selected"';
                                                                    } ?>>Organizational Defined</option>

 
                                        </select>
                                    </div>
                                <?php } ?>
                                    <div class="form-group ">
                                        <label>Status *</label>

                                        <?php if($row['orgunit_status']=="Terminated") {?>
                                            <input readonly type="orgunit_status"  name="orgunit_status" class="form-control" value="<?php echo $row['orgunit_status'];?>" Terminated>

                                            <?php }else {?>
                                        <select name="orgunit_status" id="status1" class="form-control" value="<?php echo $row['orgunit_status'] ?>" required>

                                            <option value="Active" <?php if ($row['orgunit_status'] == "Active") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Active</option>
                                            <option value="Suspended" <?php if ($row['orgunit_status'] == "Suspended") {
                                                                        echo ' selected="selected"';
                                                                    } ?>>Suspended</option>
                                             <option value="Terminated" <?php if ($row['orgunit_status'] == "Terminated") {
                                                                        echo ' selected="selected"';
                                                                    } ?>>Terminated</option>



                                        </select>
                                        <?php }?>
                                    </div>
                                    <input hidden type="text" name="id" class="form-control" value="<?php echo $id ?>" required>
                                    <input hidden type="text" name="system_entityid" class="form-control" value="<?php echo $row2['system_entityid'] ?>" required>
                                    <br>

                                    <div class="form-group mb-0">
                                        <div>
                                        <?php if($row['orgunit_status']=="Terminated") {?>
                                           

                                            <?php }else {?>
                                                <button type="submit" id="btn1" class="btn btn-primary waves-effect waves-light mr-1"> Update </button>
                                            <!-- ---------------------------------- -->
 <button  id="btn2" data-toggle="modal" data-target="#MPA" class="btn btn-danger waves-effect waves-light mr-1"> Update </button>
<!-- <div class="btn btn-danger btn-sm" id="btn2" data-toggle="modal" data-target="#MPA">
                        <span class="text-center">Update</span>
                    </div> -->
                <!-- MODEL -->
                <div class="modal fade" id="MPA" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                    <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 50%;" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <h6  style="color:red" align="center">Terminated Organization cannot be reverted.</h6>
                                <h6 align="center">Do you still want to Terminate Organization Permanently?</h6>
                                <h6 align="center">Type Word "Terminate" in the Textbox.</h6>
                                <form align="center" action="user_status_db.php" method="post">
                                    <div class="row">
                                        <i class="col-4"></i>
                                        <input type="text" align="center" id="ok" required class="form-control col-4" name="ok">
                                        <input hidden type="text" name="id" class="form-control" value="<?php echo $user_id ?>" required>
                                        <i class="col-4"></i>
                                        <input hidden type="text" name="status" class="form-control" value="Terminated" required>
                                        <i class="col-4"></i>

                                    </div>
                                    <div class="row d-flex justify-content-center pt-2">

                                        <button type="submit" align="center" id="submit" name="submit" class="btn btn-primary text-center ">Update</button>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
    <!-- --------------------------------------- --> <?php }?>
                                             

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
    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/parsleyjs/js/parsley.min.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>

<!-- 
    ------------- -->

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>
    <script src="assets/js/index.js"></script>

    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>

    <!-- <script src="assets/bundles/morrisscripts.bundle.js"></script> -->
    <script>
        $(document).ready(function() {

            var $status2 = $('#status1').find(":selected").text();
                
                if ($status2 != 'Terminated') {
                    $('#btn1').css('display', 'block');
                    $('#btn2').css('display', 'none');
                    
                } else {
                    $('#btn2').css('display', 'block');
                    $('#btn1').css('display', 'none');
                }
           
            $('#status1').on('change', function() {
               
                var $status2 = $('#status1').find(":selected").text();
                
                if ($status2 != 'Terminated') {
                    $('#btn1').css('display', 'block');
                    $('#btn2').css('display', 'none');
                    
                } else {
                    $('#btn2').css('display', 'block');
                    $('#btn1').css('display', 'none');
                }
            });

        //     var selectBox = document.getElementById("selectBox");
        // var orgunit_id = selectBox.options[selectBox.selectedIndex].value;
            
            // $("#triggerB").trigger("click");
//             $("input").change(function(){
//                alert("The text has been changed.");
// });
            $('#submit').prop('disabled', true);
                $('#ok').keyup(function() {
                if($(this).val() == 'Terminate') {
                $('#submit').prop('disabled', false);
        } else {  $('#submit').prop('disabled', true);}
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