<?php
ob_start();
session_start();
include 'include/conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['RI'])) {
	if ($_SESSION['RI'] == "NO") {
  
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
                            <h2>Dashboard</h2>
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
                <?php






                $ri_id = $_GET['ri_id'];


                $stmt = $conn->prepare("SELECT institute_name FROM registered_institutions
                WHERE ri_id='$ri_id' ");
                $stmt->execute();
                $row = $stmt->fetch();

                $stmtd = $conn->prepare("SELECT rid_id, domain FROM registered_inst_domains
                WHERE ri_id='$ri_id' ");
                $stmtd->execute();
                $rowd = $stmtd->fetchAll();
        


                
                ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>Enter Domain for Registered Institute</h2>
                            </div>
                            <div class="body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-10 col-md-10">
                                        <label></label>

                                      


                                        <form id="advanced-form" action="registered_institutes_domain_db.php" method="post"  data-parsley-validate novalidate> 

                                        <div class="row clearfix">
                                        <div class="col-lg-6 col-md-6">
                                        <label>Institute:</label>
                                                
                                                    <input type="text" name="institute_name" class="form-control" value="<?php echo  $row['institute_name']?>"  aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly data-parsley> 
                                            

                           <br>
                                                    
                                        <label>Select/Deselect Domain:</label>
                                                   
                                                    <div class="multiselect_div">
                                                    <select name="domain[]" class="multiselect" id="multi-select-demo" multiple="multiple">
                                                    <?php foreach ($rowd as $output) { ?>
                                                        <option value="<?php echo $output['domain']; ?>" <?php echo ' selected="selected"'; ?>> <?php echo $output['domain']; ?>
                                                        </option>
                                                        
                                                    <?php
                                                    } ?>

                                                  
                                                </select>
                                                </div>

                                                <br>

                                          
                                                <input  name="ri_id" type="hidden" value="<?php echo $ri_id ?>">

                                                    <br>
                                                    <br>

                                                    <div class="form-group mb-0">
                                                        <div>
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                                Modify
                                                            </button>

                                                        </div>
                                                    </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                        <label> Add New Domain to Select List:</label>
                                        <input id="new_domain" type="text" placeholder="domain.com"name="new_domain" class="form-control"   aria-label="Small" aria-describedby="inputGroup-sizing-sm"  data-parsley> 
                                        <br>
                                        <button type='button' onclick="addoption()" class="btn btn-primary waves-effect waves-light mr-1">
                                                                Add option
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
    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js -->
    <script src="assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>

    <script src="index.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

 $('#multi-select-demo').multiselect({
        maxHeight: 300,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });



        });
    </script>
    <script>
        function addoption() {
            var new_dom= document.getElementById('new_domain').value;
            $('#multi-select-demo').append($('<option></option>').attr('value', new_dom).attr('selected', 'selected').text(new_dom));
                            $("#multi-select-demo").multiselect('destroy');
$("#multi-select-demo").multiselect();

}
        </script>
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