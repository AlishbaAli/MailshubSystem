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
                            <h2>Manage Users</h2>
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
                                    <!-- <h2>Add User</h2> -->

                                    <!-- my code -->
                                    <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Add_User">Add User</button> 
                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Assign_Organization_and_Role">Assign Organization and Role</button>

                                <br>
                                <br>
                                <br>
                                    <!-- my code -->
                                    <section id="Add_User" data-status="Add_User">
                                        <div style="width:800px; margin:0 auto;" class="col-lg-8 col-md-8 col-sm-12">
                                            <div class="demo-masked-input">
                                                <form class="custom-validation" action="add_user_db.php" method="post">

                                                     <!-- MY CODE -->
                                               
                                               <div  id="UN_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> User already exists!
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>

                                               <!-- MY CODE  -->
                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">User Name </span></span>
                                                        </div>
                                                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">User Email </span></span>
                                                        </div>
                                                        <input type="text" name="useremail" class="form-control" placeholder="Enter useremail" required>
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Password*</span></span>
                                                        </div>
                                                        <input type="password" class="form-control" id="userpassword" placeholder="Enter password" name="userpassword" required>
                                                    </div>


                                                    <!--  ----------- -->
                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">First Name </span></span>
                                                        </div>
                                                        <input type="text" id='Fname' name='Fname' class="form-control" placeholder="Enter First Name">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Last Name</span></span>
                                                        </div>
                                                        <input type="text" id='Lastname' name='Lastname' class="form-control" placeholder="Enter Last Name">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Journal Title</span></span>
                                                        </div>
                                                        <input type="text" id='j_title' name='j_title' class="form-control" placeholder="Enter Journal Title">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Role</span></span>
                                                        </div>
                                                        <input type="text" id='Role' name='Role' class="form-control" placeholder="Enter Role">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Affiliation</span></span>
                                                        </div>
                                                        <input type="text" id='Affliation' name='Affliation' class="form-control" placeholder="Enter Affiliation">
                                                    </div>

                                                    <!-- <div class="form-group input-group mb-3">
         <div class="input-group-prepend">
            <span class="input-group-text "><span style="font-size:13px;">Article Title:</span></span>
         </div>
        <input type="text" id='Article' name='Article' class="form-control" placeholder="Enter Article Title" >
        </div>

        <div class="form-group input-group mb-3">
             <div class="input-group-prepend">
            <span class="input-group-text "><span style="font-size:13px;">Eurekaselect URL:</span></span>
         </div>
        <input type="text" id='URL' name='URL' class="form-control" placeholder="https://(EurekaSelect URL)" >
        </div>
 -->
                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Address Line 1</span></span>
                                                        </div>
                                                        <input type="text" id='Add1' name='Add1' class="form-control" placeholder="Enter Address Line 1">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Address Line 2</span></span>
                                                        </div>
                                                        <input type="text" id='Add2' name='Add2' class="form-control" placeholder="Enter Address Line 2">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Address Line 3</span></span>
                                                        </div>
                                                        <input type="text" id='Add3' name='Add3' class="form-control" placeholder="Enter Address Line 3">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Address Line 4</span></span>
                                                        </div>
                                                        <input type="text" id='Add4' name='Add4' class="form-control" placeholder=" Enter Address Line 4">
                                                    </div>

                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Country</span></span>
                                                        </div>
                                                        <input type="text" id='Country' name='Country' class="form-control" placeholder="Enter Your Country">
                                                    </div>

                                                    <!--  ------------- -->





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
                                        <!----------table----------->

                                        <div class="table-responsive">
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead class="text-center">

                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                        <th>User Name</th>
                                                        <th>Email</th>
                                                        <th>First Name </th>
                                                        <th>Last Name </th>
                                                        <th>Journal Title </th>
                                                        <th>Role </th>
                                                        <th>Affiliation </th>
                                                        <!-- <th>Article Title :</th>
                              <th>URL</th> -->
                                                        <th>Address</th>
                                                        <th>Country</th>
                                                        <th>Action</th>


                                                    </tr>

                                                </thead>
                                                <tbody class="text-center">
                                                    <tr>
                                                        <?php

                                                        $sql = "SELECT *
										FROM `admin` ";

                                                        $stmt = $conn->prepare($sql);
                                                        $result = $stmt->execute();

                                                        if ($stmt->rowCount() > 0) {
                                                            $result = $stmt->fetchAll();

                                                            foreach ($result as $row) { ?>



                                                                <td><?php echo $row["username"] ?></td>
                                                                <td><?php echo $row["email"]; ?></td>

                                                                <td><?php echo $row["Fname"]; ?></td>
                                                                <td><?php echo $row["Lastname"]; ?></td>
                                                                <td><?php echo $row["Journal_title"]; ?></td>
                                                                <td><?php echo $row["Role"]; ?></td>
                                                                <td><?php echo $row["affliation"]; ?></td>
                                                                <!--  <td><?php echo $row["article_title"]; ?></td>
                   <td><?php echo $row["eurekaselect_url"]; ?></td> -->
                                                                <td><?php echo $row["Add1"] . " " . $row['Add2'] . "<br> " . $row['Add3'] . " " . $row['Add4']; ?></td>
                                                                <td><?php echo $row["Country"]; ?></td>




                                                                <td>
                                                                    <a href="userp_edit.php?userid=<?php echo $row["AdminId"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                                                    <!-- <a href="orgunitdelete.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a>-->
                                                                </td>


                                                    </tr>
                                            <?php    }
                                                        }

                                            ?>
                                                </tbody>
                                            </table>

                                            <!--------------table---------->

                                        </div>
                                    </section>
                                    <!-- <h2>Assign Organization and Roles </h2> -->
                                    <section id="Assign_Organization_and_Role" data-status="Assign_Organization_and_Role">
                                        <?php

                                        if (isset($_SESSION['orgunit_id'])) {
                                            $sql_user = "SELECT username,AdminId FROM admin WHERE AdminId NOT IN(SELECT
            AdminId
        FROM
            admin AS u
        INNER JOIN tbl_user_role_prev AS ur
        ON
           ur.user_id=u.AdminId)";
                                            $stmtu = $conn->prepare($sql_user);
                                            $stmtu->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
                                            $stmtu->execute();
                                            $users = $stmtu->fetchAll();

                                            $stmt_o = $conn->prepare("SELECT system_entityid FROM tbl_organizational_unit WHERE orgunit_id= :orgunit_id");
                                            $stmt_o->bindValue(':orgunit_id', $_SESSION['orgunit_id']);

                                            $stmt_o->execute();
                                            $sys_id = $stmt_o->fetch();
                                            $sys_id = $sys_id['system_entityid'];

                                            $admin_id = $_SESSION["AdminId"];
                                            //   $sql_role = "SELECT role_prev_id, role_prev_title, system_entityid FROM tbl_role_privilege WHERE system_entityid=$sys_id AND
                                            //   restriction_level >=(SELECT restriction_level FROM tbl_role_privilege AS r INNER JOIN admin AS u INNER JOIN tbl_user_role_prev AS ur ON
                                            //   r.role_prev_id = ur.role_prev_id AND u.AdminId = ur.user_id WHERE u.AdminId = $admin_id)";
                                            $org_id = $_SESSION['orgunit_id'];
                                            $sql_role = "SELECT
          tbl_role_privilege.role_prev_id,
           tbl_role_privilege.role_prev_title
         FROM
           tbl_role_privilege INNER JOIN orgunit_role_prev
         ON
           tbl_role_privilege.role_prev_id = orgunit_role_prev.role_prev_id
           AND orgunit_role_prev.orgunit_id=$org_id AND
           tbl_role_privilege.restriction_level!=0 AND

           tbl_role_privilege.restriction_level>=(SELECT restriction_level FROM tbl_role_privilege AS r INNER JOIN admin AS u INNER JOIN tbl_user_role_prev AS ur ON
          r.role_prev_id = ur.role_prev_id AND u.AdminId = ur.user_id WHERE u.AdminId = $admin_id)";
                                            $stmt2 = $conn->prepare($sql_role);
                                            $stmt2->execute();
                                            $roles = $stmt2->fetchAll();
                                        }

                                        $sql_dept = "SELECT *  from   tbl_organizational_unit";
                                        $stmt3 = $conn->prepare($sql_dept);
                                        $stmt3->execute();
                                        $org_units = $stmt3->fetchAll();

                                        ?>
                                        <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">
                                                <form class="custom-validation" action="add_userorg_db.php" method="post">

                                                    <div class="form-group ">
                                                        <label>Select User *</label>




                                                        <select name="user_id" class="form-control" required>
                                                            <option value="" disabled selected></option>
                                                            <?php foreach ($users as $output) { ?>
                                                                <option value="<?php echo $output["AdminId"]; ?>"> <?php echo $output["username"]; ?> </option>
                                                            <?php
                                                            } ?>
                                                        </select>


                                                        <br />







                                                    </div>







                                                    <div class="form-group">

                                                        <label>Assign Roles *</label>
                                                        <select id="optgroup" name=role_list[] class=form-control class="ms" multiple="multiple" required>

                                                            <optgroup label="">
                                                                <?php foreach ($roles as $output) { ?>
                                                                    <option value="<?php echo $output["role_prev_id"]; ?>"> <?php echo $output["role_prev_title"]; ?> </option>
                                                                <?php
                                                                } ?>
                                                            </optgroup>


                                                        </select>
                                                    </div>




                                                    <input type="hidden" name="orgunit" id="orgunit" value=" <?php echo $org_id ?> ">



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


                                    </section>


                                    <!----------table----------->





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
            return confirm("Do you want to Delete this?")
        }
    </script>


    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script>
        function displayRoles() {
            var orgunit_id = document.getElementById("org_list").value;



            $.ajax({
                    url: "ajax_roles.php",
                    method: "POST",
                    data: {
                        orgunit_id: orgunit_id
                    },
                    dataType: "JSON",
                    success: function(data) {


                        $('#optgroup').empty();

                        for (var i in data) {


                            $("#optgroup").append('<option value="' + data[i].role_prev_id + '">' + data[i].role_prev_title + '</option>');
                            $('#optgroup').multiSelect('refresh');


                        }

                    }


                }

            )

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

     <!-- my code -->
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

   
    if($_GET["UN_alert"]=="true") {
        
        jQuery('#UN_exist_alert').show();
   
 
 
    }



});
</script>
     <!-- my code -->

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