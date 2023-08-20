<?php
ob_start();
session_start();
//  error_reporting(E_ALL);
//  ini_set('display_errors', 1);
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ASPC'])) {
    if ($_SESSION['ASPC'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

if (isset($_SESSION['orgunit_id'])) {
    //User not logged in. Redirect them back to the login page.
   $orgunit_id=$_SESSION['orgunit_id'];
   $o="SELECT orgunit_name from tbl_organizational_unit where orgunit_id='$orgunit_id'";
   $o=$conn->prepare($o);
   $o->execute();
   $o=$o->fetch(); $orgunit_name=$o['orgunit_name'];

//    $orgunit_id=$_SESSION['orgunit_id'];
   $o="SELECT * FROM `org_product_type` where orgunit_id='$orgunit_id'";
   $o=$conn->prepare($o);
   $o->execute();
   $ptype=$o->fetchAll(); 

//    $orgunit_id=$_SESSION['orgunit_id']; o_ct_id
   $o="SELECT * from tbl_orgunit_ctype inner join Campaign_type 
   on tbl_orgunit_ctype.ctype_id = Campaign_type.ctype_id where orgunit_id='$orgunit_id'";
   $o=$conn->prepare($o);
   $o->execute();
   $ctype=$o->fetchAll(); 

   
} else {
    $orgunit_name="Please Select Organization First";
}


?>
<html lang="en">

<!--head-->

<?php include 'include/head.php'; ?>
<!--head-->

<body class="theme-blue">

    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="assets/images/thumbnail.png" width="48" height="48" alt="Mplify"></div>
            <p>Please wait...</p>
        </div>
    </div> -->
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
                            <h2>Assign Product Type to Campaign Type</h2>
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
                                    <?php if($_GET['flag']==1) { echo "Record Already Exist."; }?>

                                    <div class="row">   <div class="col-3">  </div> <div class="col-6">
                                <form class="custom-validation" action="assign_ptype_ctype_db.php" method="post">

                                    <div class="form-group ">
                                        <label>Organization</label>
                                        <input type="text" hidden value="<?php echo $orgunit_id?>" name="orgunit_id" class="form-control" required>
                                        <input type="text" disabled value="<?php echo $orgunit_name?>" name="orgunit_name" class="form-control " required>

                                    </div>
                                    <div class="form-group ">
                                        <label>Product Type *</label>
                                        <select required name="ptype" class="form-control">
                                         <option disabled selected value="">Select Product Type</option> 
                                         <?php    foreach ($ptype as $ptypes) {
                                         $ou_pd_id=$ptypes['ou_pd_id']; $ptype_name=$ptypes['product_type_name'];
                                        echo'<option value="'.$ou_pd_id.'">'.$ptype_name.'</option>'; 
                                        } ?>
                                           
                                           
                                        </select>

                                    </div>
                                    <!-- <div class="form-group ">
                                        <label>Product Type Discription</label>
                                        <textarea type="text" name="discription" class="form-control" > </textarea>

                                    </div> -->

                                    <div class="form-group ">
                                        <label>Campaign Type *</label>
                                        <select required name="ctype" class="form-control">
                                        <option disabled selected value="">Select Role Type</option>
                                     <?php    foreach ($ctype as $ctypes) {
                                         $o_ct_id=$ctypes['o_ct_id'];
                                         $ctype_name=$ctypes['ctype_name'];
                                        echo'<option value="'.$o_ct_id.'">'.$ctype_name.'</option>'; 
                                        } ?>
                                          
                                           
                                        </select>

                                    </div>

                                    <div class="form-group ">
                                        <label>Status *</label>
                                        <select name="status" class="form-control">
                                        <!-- <option disabled selected value="">Select Role Type</option> -->
                                            <option selected value="Active">Active</option>
                                            <option value="In Active">In Active</option>
                                           
                                        </select>

                                    </div>
                                    
                                    <br>

                                    <div class="form-group mb-0">
                                        <div>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                Submit
                                            </button>
                                            <button type="reset" class="btn btn-secondary waves-effect">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                </div></div>

           <!----------table----------->
           <br> <div class =" card" >
   <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT orgunit_name, ctype_name, product_type_name, opct.status 
                                            FROM `org_ptype_ctype` as opct,tbl_organizational_unit as ou, org_product_type as pt, Campaign_type as ct 
                                            where opct.orgunit_id = ou.orgunit_id and opct.ou_pd_id=pt.ou_pd_id and opct.o_ct_id=ct.ctype_id and ctype_status='Active' 
                                            and pt.status='Active' and opct.status='Active'";
if (isset($orgunit_id)){
    $sql.=" and opct.orgunit_id = $orgunit_id ";
} // $sql.=" group by product_name";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organizational Unit Name</th>
                                                        <th>Campaign Type Name</th>
                                                        <th>Ptoduct Type Name</th>
                                                        <!-- <th>Ptoduct Type Discription</th> -->
                                                        <th>Status</th> 

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php

                                                    while ($row = $stmt->fetch()) {

                                                        // if($row['system_entityid']!=2){
                                                        //     continue;
                                                        //    }

                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["orgunit_name"] . " "; ?></td>


                                                            <td><?php
                                                                 echo $row["ctype_name"] . " "; ?></td>
                                                            
                                                            <td><?php
                                                                 echo $row["product_type_name"] . " "; ?></td>
                                                            
                                                            <!-- <td><?php
                                                                // echo $row["Discription"] . " "; ?></td> -->
                                                            
                                                            <td><?php
                                                                 echo $row["status"] . " "; ?></td>



                                                            <!-- <td><a class="btn btn-warning btn-sm" href="ctype_ou_edit.php?orgunit_id=<?php // echo $row["orgunit_id"]; ?>">Modify</a></td> -->

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>
                                            </div> </div> 
                                            <!--------------table---------->

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