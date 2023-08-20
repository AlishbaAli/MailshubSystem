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
                            <h2>Request Reply To Emails </h2>
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

                            <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Request">Requests</button> 
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Accepted">Accepted</button>
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Rejected">Rejected</button>
                              <br>
                              <br>
                              <br>
                            
                              <section id="Request" data-status="Request">
                              <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">
                                                <form action="reply_to_email_req_db.php" method="post">


                                             
                                               

                                                <div  id="rte_active_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                   Email is In Active in the Organization.<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>
                                               
                                               <div  id="Email_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> Reply To Email already requested!
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>

                                               <div  id="blck_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                   Email blocked by system!<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>
                                               <div  id="org_actv_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                   Email is already Active in the Organization.<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>


                                <div class="form-group ">
                                                        <label>Reply To Email *</label>
                                                        <input name="req_rep_to_email" type="text" class="form-control email" placeholder="Ex: example@example.com" required>

                                                    </div>


                                        <?php

if (isset($_SESSION['orgunit_id'])) { ?>
    <input hidden type="text" name="orgunit_id" class="form-control" value=<?php echo $_SESSION['orgunit_id']; ?> required>

<?php } else {

    $sql_dept = "SELECT *  from   tbl_organizational_unit WHERE orgunit_status='Active'";
    $stmt3 = $conn->prepare($sql_dept);
    $stmt3->execute();
    $org_units = $stmt3->fetchAll();
?>


    <div class="form-group ">
        <label>Select Organizational Unit *</label>




        <select name="orgunit_id" id="orgunit_id" class="form-control" required>
            <option value="" disabled selected></option>
            <?php foreach ($org_units as $output) { ?>
                <option value="<?php echo $output["orgunit_id"]; ?>"> <?php echo $output["orgunit_name"]; ?> </option>
            <?php
            } ?>
        </select>
    </div>
<?php }
?>
                                                    
                                                    <div class="form-group ">

                                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                            Request
                                                        </button>


                                                    </div>








                                                    <br>


                                                </form>
                                            </div>
                                        </div>
                                        <!----------table----------->

                              <div class="table-responsive">
    <?php
    $sql = "SELECT * FROM tbl_request_rte LEFT JOIN tbl_organizational_unit ON tbl_organizational_unit.orgunit_id=tbl_request_rte.orgunit_id
    WHERE tbl_request_rte.status='Pending'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    ?>
    <!-- <table class="table center-aligned-table" > -->
    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
        <thead>
            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                <th>Organization</th>
                <th>Reply to Email</th>
                <th> Status </th>
                <th> Action </th>




            </tr>
        </thead>
        <tbody>

            <?php






            while ($row = $stmt->fetch()) {
                                                //org specific users only
                                                $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id");

                                                $stmtd->bindValue(':orgunit_id', $row["orgunit_id"]);
                                                $stmtd->execute();
                                                $flago = 0;
                
                
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
                <tr>
                    <td><?php echo $row["orgunit_name"] . " "; ?></td>
                    <td><?php echo $row["req_rep_to_email"] . " "; ?></td>

                    <td><span class="badge badge-warning"><?php echo $row["status"] . " "; ?><i class="fa fa-history"></i> </span> </td>


                    <td>
                        <?php if( $row["status"]=="Pending" ){ ?>
   <a class="btn btn-warning btn-sm" href="reply_to_email_req_edit.php?id=<?php echo $row["request_rte_id"]; ?>">Edit</a>


                   <?php } else{ ?>

                    <a class="btn btn-warning btn-sm disabled" href="reply_to_email_req_edit.php?id=<?php echo $row["request_rte_id"]; ?>">Edit</a>


                  <?php }      ?>
                     
                    </td>

                </tr>
            <?php }


            ?>

        </tbody>
    </table>

    <!--------------table---------->

</section>

                                    <section id="Accepted" data-status="Accepted">

                                    <div class="table-responsive">
    <?php
    $sql = "SELECT * FROM tbl_request_rte LEFT JOIN tbl_organizational_unit ON tbl_organizational_unit.orgunit_id=tbl_request_rte.orgunit_id
    WHERE tbl_request_rte.status='Accepted'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    ?>
    <!-- <table class="table center-aligned-table" > -->
    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
        <thead>
            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                <th>Organization</th>
                <th>Requested By</th>
                <th>Reply to Email</th>
                <th>Requested On</th>
                <th> Status </th>
                <th>Accepted By</th>
                <th>Accepted On </th>
                
          




            </tr>
        </thead>
        <tbody>

            <?php






            while ($row = $stmt->fetch()) {

 //org specific users only
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id");

 $stmtd->bindValue(':orgunit_id', $row["orgunit_id"]);
 $stmtd->execute();
 $flago = 0;


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
                <tr>
                    <td><?php echo $row["orgunit_name"] . " "; ?></td>
                    <td><?php  
                    $user_id= $row["requested_by"]; 
                    $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                    $stmtu->bindValue(':AdminId', $user_id);
                    $stmtu->execute();
                    $username= $stmtu->fetch();
                    echo $username['username'];
                    
                    ?></td>
                    <td><?php echo $row["req_rep_to_email"] . " "; ?></td>
                    <td><?php echo $row["system_date"] . " "; ?></td>

                    <td><span class="badge badge-success"><?php echo $row["status"] . " "; ?><i class="fa fa-check-circle"></i> </span> </td>
                    <td><?php  
                    $user_id= $row["rejected_approved_by"]; 
                    $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                    $stmtu->bindValue(':AdminId', $user_id);
                    $stmtu->execute();
                    $username= $stmtu->fetch();
                    echo $username['username'];
                    
                    ?></td>
                    <td><?php echo $row["rejected_approved_date"] . " "; ?></td>


                    

                </tr>
            <?php }


            ?>

        </tbody>
    </table>




                                    </section>



                                    <!-- <h2> Reply To Emails For Users</h2> -->
                                    <section id="Rejected" data-status="Rejected">
                                    <div class="table-responsive">
    <?php
    $sql = "SELECT * FROM tbl_request_rte LEFT JOIN tbl_organizational_unit ON tbl_organizational_unit.orgunit_id=tbl_request_rte.orgunit_id
    WHERE tbl_request_rte.status='Rejected'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    ?>
    <!-- <table class="table center-aligned-table" > -->
    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
        <thead>
            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                <th>Organization</th>
                <th>Requested By</th>
                <th>Reply to Email</th>
                <th>Requested On</th>
                <th> Status </th>
                <th>Rejected By</th>
                <th>Rejected On </th>
                <th> Reason </th>
          




            </tr>
        </thead>
        <tbody>

            <?php






            while ($row = $stmt->fetch()) {

 //org specific users only
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id");

 $stmtd->bindValue(':orgunit_id', $row["orgunit_id"]);
 $stmtd->execute();
 $flago = 0;


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
                <tr>
                    <td><?php echo $row["orgunit_name"] . " "; ?></td>
                    <td><?php  
                    $user_id= $row["requested_by"]; 
                    $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                    $stmtu->bindValue(':AdminId', $user_id);
                    $stmtu->execute();
                    $username= $stmtu->fetch();
                    echo $username['username'];
                    
                    ?></td>
                    <td><?php echo $row["req_rep_to_email"] . " "; ?></td>
                    <td><?php echo $row["system_date"] . " "; ?></td>

                    <td><span class="badge badge-danger"><?php echo $row["status"] . " "; ?><i class="fa fa-times-circle"></i> </span> </td>
                    <td><?php  
                    $user_id= $row["rejected_approved_by"]; 
                    $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                    $stmtu->bindValue(':AdminId', $user_id);
                    $stmtu->execute();
                    $username= $stmtu->fetch();
                    echo $username['username'];
                    
                    ?></td>
                    <td><?php echo $row["rejected_approved_date"] . " "; 
                    
                ?></td>


                    <td>
                        
                    <button id="<?php echo $row['request_rte_id']; ?>" value="<?php echo $row['reason']; ?>" class="btn btn-info" onclick="SendIdReason(<?php echo $row['request_rte_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenter1">View</button>
                    <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                           
                                                                            <div class="modal-header">
                                                                       
                                                                                <div class="alert alert-dark" role="alert">  Reason to Reject </div>
                                                                              
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                 
                                                                                  
                                                                            <div id="show_comment"></div>
                                                                                    
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                   
                </td>

                </tr>
            <?php }


            ?>

        </tbody>
    </table>




                                      

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
        function SendIdReason(id) {
            var a = document.getElementById(id).value;
            var p = "";
  
            p =
                "<p>" +
                a + " </p>";

            $("#show_comment").empty();
            $("#show_comment").append(p);
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
        function deleteclick() {
            return confirm("Do you want to Delete this Reply To Email?")
        }
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

   
    if($_GET["rte"]=="true") {
        
        jQuery('#Email_exist_alert').show();
   
 
 
    }
    if($_GET["blck"]=="true") {
        
        jQuery('#blck_alert').show();
   
 
 
    }
    if($_GET["rte_inactive"]=="true") {
        
        jQuery('#rte_inactive_alert').show();
   
 
 
      

    }
    if($_GET["org_actv"]=="true") {
        
        jQuery('#org_actv_alert').show();
   
 
 
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




</body>

</html>