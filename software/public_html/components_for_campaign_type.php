<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['CCT'])) {
	if ($_SESSION['CCT'] == "NO") {
  
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
                            <h2>Assign Components to Campaign Type</h2>
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
                <div class="card">
                <div class="card-body">
                <div class="row">
                <div class="col-12 row">
                <div class="col-3"></div>
                <div class="col-6">

 <?php 
if (isset($_POST['submit']))
{
     $ctype_id=$_POST['ctype_id'];
     $component_id=$_POST['component_id'];
     $requirement_status=$_POST['requirement_status'];

    $check="SELECT * from components_for_campaign_type where component_id='$component_id' and ctype_id='$ctype_id' ";
    $check=$conn->prepare($check);
    $check->execute();
if ($check->rowCount()>0){
    echo '<div><span style="color:red;">Record Already Exist</span></div><br>';
   

} else {
    
    $insert="INSERT INTO `components_for_campaign_type`(`component_id`, `ctype_id`, `requirement_status`) 
    VALUES (:component_id, :ctype_id, :requirement_status)";

    $insert=$conn->prepare($insert);
    $insert->bindParam(':component_id', $component_id);
    $insert->bindParam(':ctype_id', $ctype_id);
    $insert->bindParam(':requirement_status', $requirement_status);
    $insert->execute();
    echo '<div><span style="color:green;">Record Added</span></div><br>';
}
}


?>
                <form method="POST" action="components_for_campaign_type.php">

                     <div class="form-group ">
                        <label>Select Campaign Type *</label>

                        <select name="ctype_id" class="form-control" required>
                            <option value="" disabled selected>Please select Campaign Type</option>
                            <?php 
                            $ctype="SELECT * From Campaign_type ";
                            $ctype=$conn->prepare($ctype);
                            $ctype->execute();
                            $ctypes=$ctype->fetchAll();

                            foreach ($ctypes as $output) { 
                                ?>
                                <option value="<?php echo $output["ctype_id"]; ?>"> <?php echo $output["ctype_name"]; ?> </option>
                            <?php
                            } ?>
                        </select>


                        <br />


                    </div>

                    <div class="form-group ">
                        <label>Select Component *</label>

                        <select name="component_id" class="form-control" required>
                            <option value="" disabled selected>Please select Component </option>
                            <?php 
                           
                            $component="SELECT * From alert_components";
                            $component=$conn->prepare($component);
                            $component->execute();
                            $components=$component->fetchAll();
                            
                            foreach ($components as $output) { 
                                ?>
                                <option value="<?php echo $output["component_id"]; ?>"> <?php echo $output["component_name"]; ?> </option>
                            <?php
                            } ?>
                        </select>


                        <br />


                    </div>
                    <div class="form-group ">
                        <label>Requirement Status *</label>

                        <select name="requirement_status" class="form-control" required>
                            <option value="Required"  selected>Required</option>
                            <option value="Not Required"  selected>Not Required</option>
                            <option value="Optional"  selected>Optional</option>
                        </select>


                        <br />


                    </div>
                    <div class="form-group ">
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                    </div>
                    

                </form>

                </div>
                <div class="col-3"></div>
                </div>
                </div>
                </div>
                </div>

             <?php  
             
  


// date_default_timezone_set("Asia/Karachi");
//    $strStart = '2022-01-27 03:25:33';
//    $strEnd   =  date("Y-m-d H:i:s"); 
   
   
// $dteStart = new DateTime($strStart);
//    $dteEnd   = new DateTime($strEnd);





//    $dteDiff  = $dteStart->diff($dteEnd);
   
//      print $dteDiff->format("%Y-%m-%d-%H-%I");
   
   
  



             
             ?>

                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->

    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"></script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>

</body>

</html>