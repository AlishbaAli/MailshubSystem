<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit; 
}
// if (isset($_SESSION['UM'])) {
//     if ($_SESSION['UM'] == "NO") {

//         //User not logged in. Redirect them back to the login page.
//         header('Location: page-403.html');
//         exit;
//     }
// }


   $o="SELECT distinct`component_input_type` FROM `alert_components` WHERE 1 order by input_type_order_id";
   $o=$conn->prepare($o);
   $o->execute();
   $comp_types=$o->fetchAll(); 


   



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
                            <h2>Add Product Type</h2>
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
                    <div class="col-lg-10">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                    <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->
<?php if($_GET['flag']==1) { 
    echo'<span style="color:red">';
    echo "Record Already Exist."; echo'</span>';
     }?>
                             <div class="row">  <div class="col-3"></div>  <div class="col-6">
                                  <form class="custom-validation" action="add_component_db.php" method="post">
 
                                    
                                    <div class="form-group ">
                                        <label>Component Name *</label>
                                        <input type="text" name="component_name" class="form-control" required>

                                    </div>
                                    <div class="form-group ">
                                        <label>Component Type *</label>
                                      
                                        <select name="component_type" required class="form-control">
                                         <option disabled selected value="">Select Component Type</option> 
                                         <?php foreach ($comp_types as $comp_type){ ?>
                                            <option  value="<?php echo $comp_type['component_input_type'] ; ?>"> <?php echo $comp_type['component_input_type'] ; ?> </option>
                                        <?php } ?>
                                           
                                        </select>

                                    </div>
                                    <div class="form-group ">
                                        <label>Component Discription</label>
                                        <textarea type="text" name="discription" class="form-control" > </textarea>

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
                                </form>  </div> <div class="col-3"></div> </div>
   <!----------table----------->
<br> <div class =" card" >
   <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT * FROM `alert_components`";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                      
                                                        <th>Alert Component Name</th>
                                                        <th>Component Type </th>
                                                        <th>Component Discription</th>
                                                       



                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php






                                                    while ($row = $stmt->fetch()) {

                                                       

                                                    ?>
                                                        <tr>
                                                           

                                                            <td><?php
                                                                 echo $row["component_name"] . " "; ?></td>
                                                            
                                                            <td><?php
                                                                 echo $row["component_input_type"] . " "; ?></td>
                                                            
                                                            <td><?php
                                                                 echo $row["component_discription"] . " "; ?></td>
                                                            
                                                            
                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->
                            </div> </div> 
                        </div>
                    </div>

                </div>

                <!---Add code here-->




            </div>
        </div>

    </div> </div>

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
<script src="index.js"></script>

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