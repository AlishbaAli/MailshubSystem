<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['AMA'])) {
    if ($_SESSION['AMA'] == "NO") {

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

                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h3>Add Articles</h3>
                        </div>

                        <div class=" col-12 element-box">
                            <h5 class="form-header">
                                <?php

                                if (isset($_GET['CampID'])) {
                                    //echo $_GET['CampID'];
                                    $CampID =  $_GET['CampID'];
                                    //$orgunit_id =  $_GET['orgunit_id'];


                                    $sql = "SELECT COUNT(CampName) AS num , CampName, CampID, rtemid, ctype_id
											FROM campaign 
											WHERE CampID = :CampID";

                                    $stmt = $conn->prepare($sql);

                                    //Bind the provided username to our prepared statement.
                                    $stmt->bindValue(':CampID', $CampID);

                                    //Execute.
                                    $stmt->execute();

                                    //Fetch the row.
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                    //If the provided username already exists - display error.

                                    if ($row['num'] > 0) {
                                        echo "Add Articles List for <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign";
                                        echo "<br>";
                                       
                                        echo "<br/>";
                                        $ctype = $row['ctype_id'];
                                       

                                        //die();
                                    } else
                                       
                                        echo "invalid Selection";
                                    //die();



                                }
                                ?>
                            </h5>
                            <form id="my-awesome-dropzone" class="dz-clickable" action="import_article.php" method="post" name="upload_excel" enctype="multipart/form-data">
                              
                                    <div class="control-group">
                                        <div class="control-label">
                                            <label style='color:#1740A5;'>CSV/Excel File:</label>
                                        </div>

                                        <div class="controls">
                                       
                                            <input type="hidden" name="CampID" value="<?php echo $_GET['CampID']; ?>">                                       
                                            <input type="hidden" name="ctype_id" value="<?php echo $row["ctype_id"]; ?>"> 
                                            <input type="file" name="Article_List" id="file" class="dropify" data-allowed-file-extensions="csv" accept=".csv" >


                                        </div>
                                    </div>
                                    <br />
                                    <div class="control-group">
                                        <?php if (!empty($CampID)) { // isset($_SESSION['orgunit_id']) 
                                        ?>
                                          
                                                <button type="submit" id="submit" name="submit2" class="btn btn-primary " >Upload File</button>
                                           
                                        <?php } else { ?>
                                            
                                                <button type="submit" id="submit" disabled name="submit2" class="btn btn-primary " >Upload File</button>
                                           
                                        <?php } ?>



                                
                            </form>
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

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"></script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
   


   

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