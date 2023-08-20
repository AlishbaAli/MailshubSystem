<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ADPE'])) {
    if ($_SESSION['ADPE'] == "NO") {

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
                            <h2>Modify Product</h2>
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
                <div class="row clearfix">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <div class="card">

                            <div class="body">

                                <?php

                                // $id = $_GET['id'];
                                
                                // $stmt = $conn->prepare("SELECT ou.orgunit_name AS orgunit_name, ou.orgunit_id AS orgunit_id,ou_pd_id,product_type_name,product_type_code,Discription,status 
                                // FROM org_product_type AS ouct inner JOIN tbl_organizational_unit AS ou ON ou.orgunit_id = ouct.orgunit_id");
                                // $stmt->bindValue(':ou_pd_id', $id);
                                // $stmt->execute();
                                // $result = $stmt->fetch();
                                // $orgunit_name =  $result["orgunit_name"];
                                // $orgunit_id = $result["orgunit_id"];
                                // $product_type_name = $result["product_type_name"];
                                // $product_type_code =  $result["product_type_code"]; 
                                // $discription = $result["Discription"];  
                                // $status = $result["status"];
                                $id = $_GET['id'];

                                $sql = 'SELECT *, ou.orgunit_name AS orgunit_name, ou.orgunit_id AS orgunit_id, p.status
                                FROM products as p inner join org_product_type AS ouct ON p.org_prod_type_id = ouct.ou_pd_id 
                                inner JOIN tbl_organizational_unit AS ou ON ou.orgunit_id = ouct.orgunit_id
                                WHERE productid = :productid';
                                
                                $stmt = $conn->prepare($sql);
                                $stmt->bindValue(':productid', $id);             
                                $stmt->execute();
                                $result = $stmt->fetch();


                                $productId = $result["productid"];
                                $orgunit_name = $result["orgunit_name"];
                                $orgunit_id = $result["orgunit_id"];
                                $product_name = $result["product_name"];
                                $product_code = $result["product_code"];
                                $disp = $result["disp"];
                                $product_desc = $result["product_desc"];
                                $ISSN = $result["ISSN"];
                                $E_ISSN = $result["E-ISSN"];
                                $ISBN = $result["ISBN"];
                                $E_ISBN = $result["E_ISBN"];
                                $product_category = $result["product_category"];
                                $product_type = $result["product_type"];
                                $product_cover = $result["product_cover"];
                                $product_cover_url = $result["product_cover_url"];
                                $status = $result["status"];



                                ?>

                                    <div  style="margin:0 auto;" class="col-lg-12">
                                    <div class="demo-masked-input">
                                        

                                        <form class="custom-validation" onsubmit="return Validate(this);" action="add_product_edit_db.php" enctype="multipart/form-data" method="post">
<div class="row"> <div class="col-lg-6">
                                    <div class="form-group ">
                                        <label>Organization</label>
                                        <input type="text" hidden value="<?php echo $orgunit_id?>" name="orgunit_id" class="form-control" required>
                                        <input type="text" disabled value="<?php echo $orgunit_name?>" name="orgunit_name" class="form-control " required>

                                    </div>
                                    <div class="form-group ">
                                        <label>Product Type *</label>
                                        <select  name="ptype" class="form-control">
                                         <option disabled selected value="">Select Product Type</option> 
                                         <?php    foreach ($ptype as $ptypes) {
                                         $ou_pd_id=$ptypes['ou_pd_id']; $ptype_name=$ptypes['product_type_name'];
                                        echo'<option value="'.$ou_pd_id.'">'.$ptype_name.'</option>'; 
                                        } ?>
                                           
                                           
                                        </select>
                                        <input type="text" name="product_type" hidden value="<?php echo $ptype_name; ?>">

                                    </div>
                                    <div class="form-group ">
                                        <label>Product Name *</label>
                                        <input required type="text" value="<?php echo $product_name; ?>" name="product_name" class="form-control" > 

                                    </div>

                                    <div class="form-group ">
                                        <label>Product Code *</label>
                                        <input required type="text" value="<?php echo $product_code; ?>" name="product_code" class="form-control" > 

                                    </div>

                                    <div class="form-group ">
                                        <label>Product Discipline</label>
                                        <input type="text"  value="<?php echo $disp; ?>" name="disp" class="form-control" > 

                                    </div>

                                    <div class="form-group ">
                                        <label>Product Discription</label>
                                        <textarea type="text" value="<?php echo $product_desc; ?>"  name="product_desc" class="form-control" > </textarea>

                                    </div>

                                    <div class="form-group ">
                                        <label>Status *</label>
                                        <select name="status" value="<?php echo $status; ?>" class="form-control" required>


                                                    <option value="Active" <?php if ($status == "Active") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Active</option>
                                                    <option value="In Active" <?php if ($status == "In Active") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>In Active</option>

                                                </select>

                                    </div>
  </div> <div class="col-lg-6">
                                    <div class="form-group ">
                                        <label>Product ISSN</label>
                                        <input type="text" name="ISSN" value="<?php echo $ISSN; ?>" class="form-control" > 

                                    </div>
                                    <div class="form-group ">
                                        <label>Product E-ISSN</label>
                                        <input type="text" name="E_ISSN" value="<?php echo $E_ISSN; ?>" class="form-control" > 

                                    </div>
                                    <div class="form-group ">
                                        <label>Product ISBN</label>
                                        <input type="text" name="ISBN" value="<?php echo $ISBN; ?>" class="form-control" > 

                                    </div>
                                    <div class="form-group ">
                                        <label>Product E-ISBN</label>
                                        <input type="text" name="E_ISBN" value="<?php echo $E_ISBN; ?>" class="form-control" > 

                                    </div> 
                                    <!-- <div class="form-group ">
                                        <label>Product Category</label>
                                        <input type="text" name="product_category" class="form-control" > 

                                    </div> -->
                                    <!-- <div class="form-group ">
                                        <label>Product Cover *</label>
                                        <input type="file" ' . $required . ' name="'.$component_name.'" '.$show.'   class="dropify" data-height="70" accept="image/*" 
    data-allowed-file-extensions="jpg png jpeg gif">
                                    </div> -->
                                    <input type="text" hidden name="product_Id" value="<?php echo $productId; ?>" class="form-control" > 
                                    <input type="text" hidden name="product_cover_old" value="<?php echo $product_cover; ?>" class="form-control" > 
                                    <?php 
                                        $show="";
                                        
                                        if(!empty($product_cover)){
                                            $show=' id="dropify-event" data-default-file="product_cover/'. $product_cover .'" value="product_cover/'. $product_cover .'"';
                                           
                                        }
                            
                                        echo '<div class="input-group input-group-sm mb-3">';
                                        echo '<label>Upload ' . $product_cover . ':</label>';
                            
                                        echo '<input type="file" ' . " " . ' name="product_cover" '.$show.'   class="dropify" data-height="70" accept="image/*" 
                                        data-allowed-file-extensions="jpg png jpeg gif">';
                                        echo '</div>';
                                    ?>

                                    <div class="form-group ">
                                        <label>Product Cover Url *</label>
                                        <input  type="text" value="<?php echo $product_cover_url; ?>" name="product_coverurl" class="form-control" > 

                                    </div>

                                            <input hidden name="id" type="text" value="<?php echo $id; ?>">

                                            <div class="form-group ">

                                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                    Update
                                                </button>


                                            </div>








                                            <br>


                                        </form>
                                    </div>
                                </div>
                                <!----------table----------->









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
    <script src="assets/vendor/dropify/js/dropify.min.js"></script>
    <script src="assets/js/pages/forms/dropify.js"></script>



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