<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['ADSP'])) {
    if ($_SESSION['ADSP'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}

// if (isset($_SESSION['r_level'])) {
//     if ($_SESSION['r_level'] != "0") {

//         //User not logged in. Redirect them back to the login page.
//         header('Location: page-403.html');
//         exit;
//     }
// }
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
                            <h2>Add System Settings</h2>
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
                                    <?php 

if(isset($_POST['submit'])) {

$sp_name=!empty($_POST['sp_name']) ? trim($_POST['sp_name']) : null;
$cp_name=!empty($_POST['cp_name']) ? trim($_POST['cp_name']) : null;
$cp_email=!empty($_POST['cp_email']) ? trim($_POST['cp_email']) : null;
$cp_phone=!empty($_POST['cp_phone']) ? trim($_POST['cp_phone']) : null;
$sp_website=!empty($_POST['sp_website']) ? trim($_POST['sp_website']) : null;
$sp_status=!empty($_POST['sp_status']) ? trim($_POST['sp_status']) : null;

$sp="SELECT * from service_providers where sp_name ='$sp_name' ";
$sp=$conn->prepare($sp);
$sp->execute();
$spe=$sp->fetch();
if($sp->rowCount()>0){

echo "Service Provider ALerady Exist.";

}
else{
    $sp="INSERT INTO service_providers (`sp_name`, `Contact_person_name`, `Contact_person_email`, `Contact_person_phone`, `website`, `sp_status`)
    Values ('$sp_name','$cp_name','$cp_email','$cp_phone','$sp_website','$sp_status') ";
    $sp=$conn->prepare($sp);
    $sp->execute();
echo "Record Successfully Added.";
}
}
?>
                                <form id="advanced-form" action="add_service_provider.php" method="post" data-parsley-validate novalidate>

                                    <div class="form-group ">
                                        <label>Service Provider Name *</label>
                                        <input type="text" id="sp_name" name="sp_name" class="form-control" required >
                                    </div>
                                  
                                    <!-- </div> -->
                                    <div class="form-group ">
                                        <label>Contact Person Name</label>
                                        <input type="text" id="cp_name" name="cp_name"  class="form-control" >
                                    </div>
                                
                                    <!-- </div> -->
                                    <div class="form-group ">
                                        <label>Contact Person Email</label>
                                        <input type="text" name="cp_email" class="form-control" >
                                    </div>

                                    <div class="form-group ">
                                        <label>Contact Person Phone</label>
                                        <input type="text" name="cp_phone" class="form-control">
                                    </div>

                                    <div class="form-group ">
                                        <label>Service Provider Website</label>
                                        <input type="text" name="sp_website" class="form-control">
                                    </div>

                                    <div class="form-group ">
                                        <label>Service Provider Status</label>
                                       <select name="sp_status" class="form-control">
                                           <option selected value="Active">Active</option>
                                           <option  value="In Active">In Active</option>
                                       </select>

                                    </div>                                                                                                                                                                                                                                                                                                        
                                    <br>

                                    <div class="form-group mb-0">
                                        <div>
                                            <button type="submit" name="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                Add
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
                <!-- end row -->


                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->




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