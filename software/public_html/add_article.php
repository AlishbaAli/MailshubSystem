<?php
ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['ADAT'])) {
    if ($_SESSION['ADAT'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

if (isset($_GET['CampID'])) {
    //User not logged in. Redirect them back to the login page.
    $camp_id=$_GET['CampID'];
   $camp="SELECT campaign.CampID, `CampName`,orgunit_id, user_id, ctype_id, `CampDate`, `user_id`, `Camp_Status`, `rtemid` ,draft.subscription_draft,draft_subject
   FROM `campaign` left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id left join draft on draft.CampID=campaign.CampID
   Where campaign.CampID=:campid and ou_status='Active'";
   $camp=$conn->prepare($camp);
   $camp->bindParam(':campid',$camp_id);
   $camp->execute();
   $camp=$camp->fetch(); 

  $orgunit_id=$camp['orgunit_id'];

  
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

<?php //IP Pool Ajax
if (isset($_POST['ead'])) {
    if (!empty($_POST['ead'])) {

        $ptid=$_POST['ead'];
        $op="SELECT * FROM `products` where org_prod_type_id='$ptid'";
        $op=$conn->prepare($op);
        $op->execute();
        $pids=$op->fetchAll(); 

        
    } else {
        $embargo_A = 0;
    }

    echo '<label>Products *</label>';
   
    echo '<select required name="productid" class="form-control multiselect_div multiselect multiselect-custom">
     <option disabled selected value="">Select Product Type</option> ';
       foreach ($pids as $pid) {
     $productid=$pid['productid']; $product_name=$pid['product_name']; $disp=$pid['disp'];
    echo'<option value="'.$productid.'">'.$product_name.'"  "'.$disp.'</option>'; 
    } 
       
    echo '</select>';
    echo '<input type="text" name="product_name" hidden value="'.$product_name.'">';
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
                            <h2>Add Article  <?php echo $orgunit_id=$camp['orgunit_id'];?></h2>
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
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                    <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->
                                    <?php if($_GET['flag']==1) { echo "Record Already Exist."; }?>
                                <form class="custom-validation" onsubmit="return Validate(this);" action="add_article_db.php" enctype="multipart/form-data" method="post">
                               
                                    <div class="form-group ">
                                        <label>Organization</label>
                                        <input type="text" hidden value="<?php echo $orgunit_id?>" name="orgunit_id" class="form-control" required>
                                        <input type="text" disabled value="<?php echo $orgunit_name?>" name="orgunit_name" class="form-control " required>

                                    </div>

                                    <input type="text" hidden value="<?php echo $camp_id?>" name="camp_id" class="form-control" required>

                                    <div class="form-group ">
                                        <label>Product Type *</label>
                                        <select id="ptype" required name="ptype" class="form-control">
                                         <option disabled selected value="">Select Product Type</option> 
                                         <?php    foreach ($ptype as $ptypes) {
                                         $ou_pd_id=$ptypes['ou_pd_id']; $ptype_name=$ptypes['product_type_name']; 

                                        echo'<option value="'.$ou_pd_id.'">'.$ptype_name.'</option>'; 
                                        } ?>
                                           
                                           
                                        </select>
                                        <input type="text" name="product_type" hidden value="<?php echo $ptype_name; ?>">

                                    </div>

                                    <div class="form-group " id="response3" > </div>

                                    <div class="form-group ">
                                        <label>Article Name *</label>
                                        <input required type="text" name="title" class="form-control" > 

                                    </div>

                                    <div class="form-group ">
                                        <label>Article URL  *</label>
                                        <input required type="text" name="url" class="form-control" > 

                                    </div>
                                    <div class="form-group ">
                                        <label>DOI  *</label>
                                        <input required type="text" name="doi" class="form-control" > 

                                    </div>
                                    
                                    <div class="form-group ">
                                        <label>Article ID</label>
                                        <input type="text" name="article_id" class="form-control" > 

                                    </div>

                                    <div class="row">

                                    <div class="form-group col-4">
                                        <label>Volume *</label>
                                        <input type="text" required name="volume" class="form-control" > 

                                    </div>
                                    <div class="form-group col-4">
                                        <label>Issue *</label>
                                        <input type="text" required name="issue" class="form-control" > 

                                    </div>
                                    <div class="form-group col-4">
                                        <label>Year *</label>
                                        <input type="text" required name="year" class="form-control" > 

                                    </div>

                                    </div>
                                    <div class="form-group ">
                                        <label>Authors *</label>
                                        <textarea type="text" required name="authors" class="form-control" > </textarea>

                                    </div>
                                    <div class="form-group ">
                                        <label>Abstract *</label>
                                        <textarea type="text" required name="abstract" class="form-control" > </textarea>

                                    </div>

                                    <div class="form-group ">
                                        <label>Abstract URL *</label>
                                        <input type="text" required name="absurl" class="form-control" > 

                                    </div>
                                    <div class="form-group ">
                                        <label>Abstract URL Download *</label>
                                        <input type="text" required name="absurl_download" class="form-control" > 

                                    </div>
                                   
                                    
                                    <div class="form-group ">
                                        <label>Status *</label>
                                        <select name="status" required class="form-control">
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

                            </div>
                        </div>
                    </div>

                </div>

                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->


    <!-- -------------------------------------------------- -->
    <script src="assets/bundles/libscripts.bundle.js"></script>    
<script src="assets/bundles/vendorscripts.bundle.js"></script>

<script src="assets/bundles/datatablescripts.bundle.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>




<script src="assets/bundles/mainscripts.bundle.js"></script>
<script src="assets/bundles/morrisscripts.bundle.js"></script>
<script src="assets/js/pages/tables/jquery-datatable.js"></script>
<script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> <!-- Bootstrap Colorpicker Js --> 
<script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js --> 
<script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
<script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js --> 
<script src="assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js --> 
<script src="assets/js/pages/forms/advanced-form-elements.js"></script>



    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

  
    <script src="assets/js/index.js"></script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>

    <script>
        var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];

        function Validate(oForm) {
            var arrInputs = oForm.getElementsByTagName("input");
            for (var i = 0; i < arrInputs.length; i++) {
                var oInput = arrInputs[i];
                if (oInput.type == "file") {
                    var sFileName = oInput.value;
                    if (sFileName.length > 0) {
                        var blnValid = false;
                        for (var j = 0; j < _validFileExtensions.length; j++) {
                            var sCurExtension = _validFileExtensions[j];
                            if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                                blnValid = true;
                                break;
                            }
                        }

                        if (!blnValid) {
                            alert("Sorry, Allowed extensions for images are: " + _validFileExtensions.join(", "));
                            return false;
                        }
                    }
                }
            }

            return true;
        }
    </script>
 <script>
        $('#ptype').on('change', function() {
            var ead = $(this).val();
//             var e = document.getElementById("ddlViewBy");
// var strUser = e.value;

            $.ajax({
                url: "add_article.php",
                type: "POST",
                data: {
                    ead: ead
                }

            }).done(function(data) {

                $("#response3").html(data);
            });

        });
    </script>
    <!-- -------------------------------------------------- -->
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