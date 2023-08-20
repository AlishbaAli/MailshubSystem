<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['DBL'])) {
    if ($_SESSION['DBL'] == "NO") {

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
        <?php include 'include/nav_SA.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->

        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12">
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h3>DNS BL</h3>
                                    <a class="btn btn-primary btn-lg waves-effect waves-light icon-globe" href="dnsbl_form.php"> Add DSN BL </a> <br><br>
                                </div>


                                <!--------------------
                      START - Table with actions
                      -------------------->
                                <div class="table-responsive">
                                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">

                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th>
                                                    DNS BL Name
                                                </th>
                                                <th>
                                                    Priority Color
                                                </th>
                                                <th>
                                                    Priority Score
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
                                     
                                                <?php

                                                $sql = "SELECT *
										FROM `dnsbl` ";


                                                $stmt = $conn->prepare($sql);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {
                                                    $result = $stmt->fetchAll();



                                                    foreach ($result as $row) {   

                                                       if ($row["priority_color"] == "red") { ?>
                                                        <tr style="color: #000000; background: red;">

                                                        <?php }
                                                        if ($row["priority_color"] == "orange") { ?>
                                                        <tr style="color: #000000; background: #F28C28;">

                                                        <?php }?>
                                                        <?php 
                                                        if ($row["priority_color"] == "black") { ?>
                                                        <tr style="color: #FFFFFF; background: #000000;">

                                                        <?php }?>
                                                        <?php 
                                                        if ($row["priority_color"] == "yellow") { ?>
                                                        <tr style="color: #000000; background: #FAFA33;">

                                                        <?php }?>
                                                        <td><?php echo $row["dnsbl_name"] ?></td>
                                                        

                                                            <?php 

                                                            ?>
                                                               <td  > <?php echo $row["priority_color"] ?> </td>
                                                          
                                                         
                                                        
                                                        <td><?php echo $row["priority_score"]; ?></td>
                                                        <td><?php echo $row["status"]; ?></td>
                                                        <td>
                                                            <a href="editdnsbl.php?dnsbl_id=<?php echo $row["dnsbl_id"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                                            <?php if (isset($_SESSION['DDBL'])) {
                                                                if ($_SESSION['DDBL'] == "YES") {

                                                            ?>
                                                                    <a href="deletednsbl.php?dnsbl_id=<?php echo $row["dnsbl_id"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a>


                                                            <?php }
                                                            } 
                                                            ?>
                                                        </td>

                                            </tr>

                                    <?php   
                                     }
                                                }

                                    ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Javascript -->

    <script>
        function deleteclick() {
            return confirm("Do you want to delete this?")
        }
    </script>
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
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>

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