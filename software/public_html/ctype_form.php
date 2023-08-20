<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['CTYPE'])) {
	if ($_SESSION['CTYPE'] == "NO") {
  
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
                            <h2>Campaign Type Management</h2>
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
                                <!-- <div id="wizard_horizontal"> -->
                                    <!-- <h2>Add Campaign Type</h2> -->

                                    <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Add_Compaign_Type">Add Compaign Type</button> 
                                <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Compaign_Type_For_Organizational_Unit">Compaign Type For Organizational Unit</button>

                                <br>
                                <br>
                                <br>
                                    <section id="Add_Compaign_Type" data-status="Add_Compaign_Type">
                                        <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">

                                               <form action="ctype_db.php" method="post">


                                      
                                               
                                               <div  id="campaign_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> Campaign  already exists!
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>


                                                



                                                    <div class="form-group ">
                                                        <label>Name*</label>
                                                        <input name="ctype_name" type="text" class="form-control" required>

                                                    </div>
                                                
                                                    <div class="form-group ">
                                                        <label>Format1</label>

                                                        <select name="data_format1" class="form-control" required>

                                                        <option value="No"> No</option>
                                                            <option value="Yes">Yes</option>

                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Format2</label>

                                                        <select name="data_format2" class="form-control" required>

                                                           <option value="No"> No</option>
                                                            <option value="Yes">Yes</option>

                                                        </select>


                                                    </div>
                                                   
                                                    <div class="form-group ">
                                                        <label>Scopus</label>

                                                        <select name="data_scopus" class="form-control" required>

                                                        <option value="No"> No</option>
                                                            <option value="Yes">Yes</option>

                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Wos</label>

                                                        <select name="data_wos" class="form-control" required>

                                                        <option value="No"> No</option>
                                                            <option value="Yes">Yes</option>

                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Automatic</label>

                                                        <select name="data_automatic" class="form-control" required>

                                                        <option value="No"> No</option>
                                                            <option value="Yes">Yes</option>

                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Domain Wise Mail Send</label>

                                                        <select name="ctype_domainwise_mail_send" class="form-control" required>

                                                        <option value="No"> No</option>
                                                            <option value="Yes">Yes</option>

                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Article list</label>

                                                        <select name="ctype_article_list" class="form-control" required>
                                                            
                                                            <option value="No"> No</option>
                                                            <option value="Yes">Yes</option>


                                                        </select>


                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Status</label>

                                                        <select name="ctype_status" class="form-control" required>

                                                            <option value="Active"> Active</option>
                                                            <option value="In Active"> In Active</option>

                                                        </select>


                                                    </div>


                                                    <div class="form-group ">

                                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                            Add
                                                        </button>


                                                    </div>








                                                    <br>


                                                </form>
                                            </div>
                                        </div>
                                        <!----------table----------->

                                        <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT * FROM Campaign_type";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Name</th>
                                                        <th>Format1</th>  
                                                        <th>Format2</th>
                                                        <th>Scopus</th>
                                                        <th>Wos</th>
                                                        <th>Automatic</th>
                                                        <th>Domain Wise Mail Send</th>
                                                        <th>Status</th>
                                                        <th>Action</th>


                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php






                                                    while ($row = $stmt->fetch()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["ctype_name"] . " "; ?></td>
                                                            <td><?php echo $row["data_format1"] . " "; ?></td>
                                                            <td><?php echo $row["data_format2"] . " "; ?></td>
                                                            <td><?php echo $row["data_scopus"] . " "; ?></td>
                                                            <td><?php echo $row["data_wos"] . " "; ?></td>
                                                            <td><?php echo $row["data_automatic"] . " "; ?></td>
                                                            <td><?php echo $row["ctype_domainwise_mail_send"] . " "; ?></td>
                                                            <td><?php echo $row["ctype_status"] . " "; ?></td>

                                                            <td>

                                                                <a href="ctype_edit.php?id=<?php echo $row["ctype_id"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                                              
                                                            </td>
                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->

                                        </div>
                                    </section>

                                    <!-- <h2>Campaign Type For Organizational Unit</h2> -->
                                    <section id="Compaign_Type_For_Organizational_Unit" data-status="Compaign_Type_For_Organizational_Unit">



                                        <!----------table----------->

                                        <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT ou.system_entityid AS system_entityid, ou.orgunit_name AS orgunit_name, ou.orgunit_id AS orgunit_id,
                                      GROUP_CONCAT( DISTINCT ctype_id SEPARATOR ',' ) AS camp_type FROM tbl_organizational_unit AS ou 
                                      LEFT JOIN  tbl_orgunit_ctype AS ouct ON ou.orgunit_id = ouct.orgunit_id GROUP BY ou.orgunit_id";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organizational Unit Name</th>
                                                        <th>Campaign Type</th>
                                                        <th> Actiion </th>




                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php






                                                    while ($row = $stmt->fetch()) {

                                                        if($row['system_entityid']!=2){
                                                            continue;
                                                           }


                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["orgunit_name"] . " "; ?></td>


                                                            <td><?php
                                                                if ($row["camp_type"] != "" AND $row["camp_type"] != NULL) {
                                                                    $ctype_ids = $row["camp_type"];
                                                                    $ctype_ids_arr = explode(",", $ctype_ids);
                                                                    $res = "";
                                                                    foreach ($ctype_ids_arr as $id) {
                                                                        $stmtr = $conn->prepare("SELECT ctype_name FROM Campaign_type WHERE ctype_id =$id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();

                                                                        $res .= $rowr["ctype_name"] . " ,";
                                                                    }
                                                                    $final = "";
                                                                    $res = substr_replace($res, "", -1);

                                                                    echo wordwrap($res, 50, "<br>\n");
                                                                }   ?></td>


                                                            <td><a class="btn btn-warning btn-sm" href="ctype_ou_edit.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>">Modify</a></td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->

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
        function deleteclick() {
            return confirm("Do you want to Delete this Campaign Type?")
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
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   
    if($_GET["campaign_exist"]=="true") {
        
        jQuery('#campaign_exist_alert').show();
   
 
 
    }



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







</html>