<?php
//   error_reporting(E_ALL);
//   ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['UM'])) {
    if ($_SESSION['UM'] == "NO") {

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

                            <h2>Add Users and Roles</h2>
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
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">

                            <div class="header">
                                <h2></h2>

                            </div>
                            <div class="body">


                                <a class="btn btn-primary btn-lg waves-effect waves-light icon-user-follow" href="add_user.php"> Add User</a> <br><br>
                                <div class="table-responsive">
                                    <?php
                                    $sql = "SELECT u.added_by AS added_by,
                                     u.status As status,
                                      u.username AS username,
                                       u.AdminId AS user_id, 
                                       u.email AS email,
                                       u.email_status AS email_status,
                                        GROUP_CONCAT( DISTINCT r.role_prev_id SEPARATOR ',' ) AS role FROM admin AS u
                                         LEFT JOIN tbl_user_role_prev AS r ON u.AdminId = r.user_id 
                                         GROUP BY u.username";

                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();

                                    ?>
                                    <!-- <table class="table center-aligned-table" > -->
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead>
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;text-align: center;">
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Email Status</th>
                                                <th>Role</th>
                                                <th>Organizational Unit</th>
                                                <th>Status</th>
                                                <th>Added By</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php






                                            while ($row = $stmt->fetch()) {

                                                //filter super admins
                                                $flag = 0;
                                                if ($row["role"] != "") {
                                                    $r_ids = $row["role"];

                                                    $stmtsr = $conn->prepare("SELECT restriction_level FROM tbl_role_privilege WHERE role_prev_id IN ($r_ids)");

                                                    $stmtsr->execute();
                                                 

                                                    while ($rowsr = $stmtsr->fetch()) {

                                                        if ($rowsr["restriction_level"] == '0') {
                                                            $flag = 1;
                                                        }
                                                    }
                                                    if ($flag == 1) {
                                                        continue;
                                                    }
                                                }

                                                //org specific users only
                                                $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE user_id =:user_id");

                                                $stmtd->bindValue(':user_id', $row["user_id"]);
                                                $stmtd->execute();
                                                $flago = 0;
                                                //dont show users with no org here
                                                if ($stmtd->rowCount() < 1) {
                                                    continue;

                                                }



                                                while ($rowd = $stmtd->fetch()) {
                                                    

                                                    if ($_SESSION['orgunit_id'] != NULL) {
                                                        if ($rowd["orgunit_id"] != $_SESSION['orgunit_id']) {
                                                            $flago = 1;
                                                        }
                                                    }
                                                }
                                                if ($flago == 1) {
                                                    continue;
                                                }



                                            ?>
                                                <tr style="text-align: center;">
                                                    <td><?php echo $row["username"] . " "; ?></td>


                                                    <td><?php echo $row["email"]; ?></td>
                                                    <td><?php

                                                        if ($row["email_status"] == "Not Verified") { ?>


                                                            <span class="badge badge-danger"><?php echo $row["email_status"]; ?></span> <br> <br>
                                                            <a id="<?php echo $row['user_id']; ?>" class="btn btn-success waves-effect waves-light" onclick="SendId(<?php echo $row['user_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-envelope "></i> Verify Email</a>




                                                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Tell us you are not a robot!</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form method="post" action="send_uemail.php" onsubmit="return submitUserForm();">
                                                                                <div class="g-recaptcha" data-sitekey="6LcJumMcAAAAAGnXEfApJKjq6W38czoFRTN7Hu9n" data-callback="verifyCaptcha"></div>
                                                                                <div id="g-recaptcha-error"></div>
                                                                                <input type="hidden" name="uid" id="uid">
                                                                                <br>

                                                                                <button type="submit" name="submit" value="Submit" class="btn btn-primary">Send</button>
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            </form>


                                                                        </div>
                                                                        <!-- <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" value="Submit" class="btn btn-primary">Send</button>
                                        </div> -->

                                                                    </div>
                                                                </div>
                                                            </div>

                                                    </td>

                                                <?php } else if ($row["email_status"] == "Verified") { ?>
                                                    <span class="badge badge-success"><?php echo $row["email_status"] . " "; ?><i class="fa fa-check-circle"></i> </span> <br> <br>


                                                <?php } else if ($row["email_status"] == "Pending") { ?>
                                                    <span class="badge badge-warning"><?php echo $row["email_status"] . " "; ?><i class="fa fa-history"></i> </span> <br> <br>

                                                <?php } ?>




                                                <td>
                                                    <?php if ($row["role"] != "") {
                                                        $role_ids = $row["role"];
                                                        $role_ids_arr = explode(",", $role_ids);
                                                        $res = "";

                                                        foreach ($role_ids_arr as $id) {
                                                            $stmtr = $conn->prepare("SELECT role_prev_title FROM tbl_role_privilege WHERE role_prev_id =$id
                                            ");
                                                            $stmtr->execute();
                                                            $rowr = $stmtr->fetch();
                                                            $res .= $rowr["role_prev_title"] . ",";
                                                        }
                                                        echo $res = substr_replace($res, "", -1);
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $ids = array();
                                                    $stmtd = $conn->prepare("SELECT orgunit_id FROM  tbl_orgunit_user WHERE user_id =:user_id");
                                                    $stmtd->bindValue(':user_id', $row["user_id"]);
                                                    $stmtd->execute();



                                                    $ids = $stmtd->fetch();
                                                    $id = $ids["orgunit_id"];




                                                    $resd = "";




                                                    $stmtdn = $conn->prepare("SELECT orgunit_name,system_entityid FROM tbl_organizational_unit WHERE orgunit_id =:orgunit_id");
                                                    $stmtdn->bindValue(':orgunit_id', $id);
                                                    $stmtdn->execute();
                                                    $rowdn = $stmtdn->fetch();
                                                    $resd .= $rowdn["orgunit_name"] . ",";
                                                    $sys_id = $rowdn["system_entityid"];





                                                    echo $resd = substr_replace($resd, "", -1);

                                                    ?>

                                                </td>





                                                <td>
                                                <?php if($row["status"]=="org_terminated" || $row["status"]=="Terminated" ){?>
                                                
                                                <span class="badge badge-danger"><?php echo $row["status"]; ?></span>
                                                <?php } 
                                                
                                                else if($row["status"]=="Active"){ ?>
  
                                                    <span class="badge badge-success"><?php echo $row["status"]; ?></span>
    
                                            <?php    }
                                                
                                                else {
                                                    echo $row["status"]; }?>
                                                    

                                                
                                                </td>

                                                <td><?php echo $row["added_by"]; ?></td>
                                                <td>
                                                    
                                                <?php if($row["status"]=="org_terminated"){
                                                                
                                                                continue;
                                                            }?>
                                                             <?php if($row["status"]=="Terminated"){
                                                                
                                                                continue;
                                                            }?>


                                                <a class="btn btn-warning btn-sm" href="user_edit.php?id=<?php echo $row["user_id"]; ?>&org_id=<?php echo $id ?>">Modify</a>
                                            
                                                <a class="btn btn-warning btn-sm" href="user_status.php?id=<?php echo $row["user_id"]; ?>">Modify Status</a>
                                            </td>



                                                </tr>
                                            <?php }


                                            ?>

                                        </tbody>
                                    </table>
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
            return confirm("Do you want to Delete Campaign?")
        }
    </script>

    <script>
        function SendId(id) {



            document.getElementById('uid').value = id;



        }
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        var recaptcha_response = '';

        function submitUserForm() {
            if (recaptcha_response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This field is required.</span>';
                return false;
            }
            return true;
        }

        function verifyCaptcha(token) {
            recaptcha_response = token;
            document.getElementById('g-recaptcha-error').innerHTML = '';
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




    <script src="assets/bundles/datatablescripts.bundle.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>

    <script src="assets/vendor/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js -->



    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>


    <script src="assets/vendor/editable-table/mindmup-editabletable.js"></script> <!-- Editable Table Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/editable-table.js"></script>
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