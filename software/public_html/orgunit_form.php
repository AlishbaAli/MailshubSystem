<?php

ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['AO'])) {
    if ($_SESSION['AO'] == "NO") {

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

    <!-- Page Loader
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="assets/images/thumbnail.png" width="48" height="48" alt="Mplify"></div>
            <p>Please wait...</p>
        </div>
    </div> -->
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
                            <h2>Organizational Unit Management</h2>
                        </div>
                       <!-- <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div> -->
                    </div>
                </div>

                <!---Add code here-->
                <div class="row clearfix">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <div class="card">

                            <div class="body">
                                <!-- <div id="wizard_horizontal"> -->
                                    

                                <!-- my code -->
                                <h2>
                                <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Add_Organizational_Unit">Add Organizational Unit</button> 
                                <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Mail_Servers_For_Organizational_Unit">Mail Servers For Organizational Unit</button>
                                <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Roles_For_Organizational_Unit">Roles For Organizational Unit</button>
                                <!-- my code -->
                                </h2>

                                <br>
                                <br>
                                <br>
                                <!-- <h2>Add Organizational Unit</h2> -->
                                    <section id="Add_Organizational_Unit" data-status="Add_Organizational_Unit">
                                        <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">


                                            <!-- my code -->
                                                                       
                                           <div  id="Organization_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                           <i class="mdi mdi-check-all mr-2"></i> Organizaion already exists!
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">×</span>
                                           </button>
                                           </div>
                                             <!-- my code -->
                                                <form id="advanced-form" action="orgunit_db.php" method="post">

                                                    <div class="form-group ">
                                                        <label>Organization Name *</label>
                                                        <input type="text" name="orgunit_name" class="form-control" required>

                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Organization Code *</label>
                                                        <input type="text" name="orgunit_code" class="form-control" required>

                                                    </div>
                                                    <div class="form-group ">
                                                        <label>System Entity *</label>
                                                        <select name="system_entityid" id="system_entityid" onChange=showHide() class="form-control" required>


                                                            <option value="2"> Operation </option>
                                                            <option value="3"> Technical </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group " id="SS">
                                                        <label>System Setting *</label>
                                                        <select name="system_setting" class="form-control" required>

                                                            <option value="sys-defined"> System Defined </option>
                                                            <option value="ou-defined"> Organizational Defined </option>


                                                        </select>
                                                    </div>

                                                    <div class="form-group ">
                                                        <label>Status *</label>
                                                        <select name="orgunit_status" class="form-control" required>

                                                            <option value="Active"> Active </option>
                                                            <!-- <option value="In Active"> In Active </option> -->


                                                        </select>
                                                    </div>

                                                    <br>

                                                    <div class="form-group mb-0">
                                                        <div>
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                                Add
                                                            </button>

                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                        <!----------table----------->

                                        <div class="table-responsive">
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead class="text-center">

                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                        <th>
                                                            Organization Name
                                                        </th>
                                                        <th>
                                                            Organization Code
                                                        </th>
                                                        <th>
                                                            System Entity Type
                                                        </th>
                                                        <th>
                                                            System Settings
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
										FROM `tbl_organizational_unit` ";


                                                        $stmt = $conn->prepare($sql);
                                                        $result = $stmt->execute();

                                                        if ($stmt->rowCount() > 0) {
                                                            $result = $stmt->fetchAll();



                                                            foreach ($result as $row) {    ?>



                                                                <td><?php echo $row["orgunit_name"] ?></td>
                                                                <td><?php echo $row["orgunit_code"] ?></td>
                                                                <td><?php

                                                                    if ($row["system_entityid"] != NULL || $row["system_entityid"] != "") {
                                                                        $id = $row["system_entityid"];
                                                                        $stmtr = $conn->prepare("SELECT system_entity_type FROM system_entity WHERE system_entityid =$id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();


                                                                        echo $rowr["system_entity_type"];
                                                                    } ?>


                                                                </td>
                                                                <td><?php echo $row["system_setting"]; ?></td>
                                                                <td>     <?php if($row["orgunit_status"]=="Terminated"){?>
                                                
                                                <span class="badge badge-danger"><?php echo $row["orgunit_status"]; ?></span>
                                                <?php } else {
                                                    echo $row["orgunit_status"]; }?></td>



                                                                <td>

                                                                <?php if($row["orgunit_status"]=="Terminated"){?>
                                                
                                            
                                                <?php } else { ?>
                                                    <a href="orgunitedit.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                                    
                                                    
                                                  <?php  }?>
                                                                  
                                                                    <!-- <a href="orgunitdelete.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a>-->
                                                                </td>


                                                    </tr>
                                            <?php    }
                                                        }

                                            ?>
                                                </tbody>
                                            </table>

                                            <!--------------table---------->

                                        </div>
                                    </section>
                                    <!-- <h2>Mailservers For Organizational Unit </h2> -->
                                    <section id="Mail_Servers_For_Organizational_Unit" data-status="Mail_Servers_For_Organizational_Unit">

                                        <div class="table-responsive">

                                            <?php
                                            $sql = "SELECT o.orgunit_name, o.orgunit_id, GROUP_CONCAT(DISTINCT m.mailserverid SEPARATOR ',') AS 
                                     mailserverid FROM tbl_organizational_unit AS o LEFT JOIN `mailserver-orgunit` AS m 
                                     ON o.orgunit_id = m.orgunit_id GROUP BY o.orgunit_name";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organziation Name</th>
                                                        <th>Mailservers</th>
                                                        <th> Action </th>




                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php






                                                    while ($row = $stmt->fetch()) {



                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["orgunit_name"] . " "; ?></td>



                                                            <td>

                                                                <?php
                                                                if ($row["mailserverid"] != "") {
                                                                    $remail_ids = $row["mailserverid"];
                                                                    $remail_ids_arr = explode(",", $remail_ids);
                                                                    $res = "";
                                                                    foreach ($remail_ids_arr as $id) {
                                                                        $stmtr = $conn->prepare("SELECT vmname FROM mailservers WHERE mailserverid =$id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();

                                                                        $res .= $rowr["vmname"] . " ,";
                                                                    }
                                                                    $final = "";
                                                                    $res = substr_replace($res, "", -1);

                                                                    echo wordwrap($res, 50, "<br>\n");
                                                                }   ?>

                                                            </td>




                                                            <td><a class="btn btn-warning btn-sm" href="mailserver-orgunit_edit.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>">Modify</a></td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>



                                            <!----------table----------->



                                    </section>

                                    <!-- <h2>Roles For Organizational Unit </h2> -->
                                    <section id="Roles_For_Organizational_Unit" data-status="Roles_For_Organizational_Unit">

                                        <div class="table-responsive">

                                            <?php
                                            $sql = "SELECT
                                     o.orgunit_name,
                                     o.orgunit_id,
                                     o.system_entityid,
 
                                     GROUP_CONCAT(
                                         DISTINCT ro.role_prev_id SEPARATOR ','
                                     ) AS role_id
                                 FROM
                                     tbl_organizational_unit AS o
                                 LEFT JOIN `orgunit_role_prev` AS ro
                                 ON
                                     o.orgunit_id = ro.orgunit_id
                                 GROUP BY
                                     o.orgunit_name";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organziation Name</th>
                                                        <th>Roles</th>
                                                        <th> Action </th>




                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php






                                                    while ($row = $stmt->fetch()) {



                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["orgunit_name"] . " "; ?></td>



                                                            <td>

                                                                <?php
                                                                if ($row["role_id"] != "") {
                                                                    $remail_ids = $row["role_id"];
                                                                    $remail_ids_arr = explode(",", $remail_ids);
                                                                    $res = "";
                                                                    foreach ($remail_ids_arr as $id) {
                                                                        $stmtr = $conn->prepare("SELECT role_prev_title FROM  tbl_role_privilege WHERE role_prev_id =$id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();

                                                                        $res .= $rowr["role_prev_title"] . " ,";
                                                                    }
                                                                    $final = "";
                                                                    $res = substr_replace($res, "", -1);

                                                                    echo wordwrap($res, 50, "<br>\n");
                                                                }   ?>

                                                            </td>




                                                            <td><a class="btn btn-warning btn-sm" href="role_orgunit_edit.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>&sysid=<?php echo $row["system_entityid"]; ?>">Modify</a></td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>



                                            <!----------table----------->



                                    </section>


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
    <script>
        function showHide() {


            var sys_id = document.getElementById("system_entityid").value;

            if (sys_id == 2) {
                document.getElementById('SS').style.display = 'block'
                // document.getElementById('CET').style.display = 'block'
            } else {
                document.getElementById('SS').style.display = 'none'
                // document.getElementById('CET').style.display = 'none'
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
    
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>

    <script>
    $(document).ready(function () {
        $('.star').on('click', function () {
            $(this).toggleClass('star-checked');
        });

        $('.ckbox label').on('click', function () {
            $(this).parents('tr').toggleClass('selected');
        });

        $('.btn-filter').on('click', function () {
            var $target = $(this).data('target');
            if ($target != 'all') {
                $('.table tr').css('display', 'none');
                $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
            } else {
                $('.table tr').css('display', 'none').fadeIn('slow');
            }
        });
        $('.btn-filter2').on('click', function () {
            var $target = $(this).data('target');
            if ($target != 'all') {
                $('section').css('display', 'none');
                $('section[data-status="' + $target + '"]').fadeIn('slow');
            } 
        });
        $("#triggerB").trigger("click");
    });
</script>
    <script>
        function deleteclick() {
            return confirm("Do you want to Delete this?")
        }
    </script>

   <!-- my code -->
<script>
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   
    if($_GET["O_exist"]=="true") {
        
        jQuery('#Organization_exist_alert').show();
   
 
 
    }



});
</script>
<!-- my code -->

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