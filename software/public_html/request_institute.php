<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['RQI'])) {
    if ($_SESSION['RQI'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}
$orgunit_id="";
if (isset($_SESSION['orgunit_id'])) {
    $orgunit_id= $_SESSION['orgunit_id'];

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
        <?php include 'include/nav_bar.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->
<?php 
$stmt= $conn->prepare("");
$rowd=$stmt->fetchAll();
?>

        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Request Institutes</h2>
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

                            <div class="body">

                            <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Request">Requests</button> 
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Accepted">Accepted Requests</button>
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="org_institutes">Organizational institutes</button>
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Rejected">Rejected Requests</button>
                            <br>
                            <br>
                            <br>
                            <section id="Request" data-status="Request">
                                <div  id="inst_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-check-all mr-2"></i>Institute and domain already requested! <br>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                        </button>
                                </div>

                                <div id="inserted_show" style="display:none"  class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-check-circle"></i> Institute and Domain(s) Requested
                            </div>                              
                            <h4>Make New Request</h4> <br>
                                           
                                <form id="advanced-form" action="request_institute_db.php" method="post"  data-parsley-validate novalidate> 

                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-6">
                                        <label>Institute:</label>
        
                                        <div class="search-box">
                                            <input type="text" id="uni" name="institute" placeholder="Search institute..." class="form-control" value="" required>
                                            <div class="result"></div>
                                        </div>
                                        <br>

                                        <input hidden type="text" id="institute_name"name="institute_name" class="form-control" value=""  aria-label="Small" aria-describedby="inputGroup-sizing-sm"  data-parsley> 
                                        <input hidden type="text" id="grid_id" value="" name="grid_id"> 
    

                                        <br>
            
                                        <label>Select/Deselect From Exisiting Domains:</label>
           
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

  
                                        <input  name="ou_inst_id" type="hidden" value="<?php echo $ou_inst_id ?>">

                                        <br>
                                        <br>

                                        <div class="form-group mb-0">
                                            <div>
                                                <button disabled name="sendreq" id="sendreq" type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                    Send Request
                                                </button>
                            
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <label> Add New Domain to Select List:</label>
                                            <input id="new_domain" type="text" placeholder="domain.com"name="new_domain" class="form-control"   aria-label="Small" aria-describedby="inputGroup-sizing-sm"  data-parsley> 
                                            <br>
                                            <button type='button'onclick="addoption()" class="btn btn-primary waves-effect waves-light mr-1">
                                            Add option
                                            </button>      
                                            
                                        </div>
                                                    
                                        </div>



                                    </form>
                                    <br><br> <br>
                                    <h4>Pending Requests</h4> <br>
                                    <div class="table-responsive">
                                            <?php

                                        
                                            
                                            
                                        if(isset($_SESSION['orgunit_id']))
                                        {
                                            $orgunit_id= $_SESSION['orgunit_id'];
                                            $sql = "SELECT *, GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domains FROM`request_institute` 
                                            INNER JOIN request_domain ON request_domain.req_id= request_institute.req_id 
                                            WHERE orgunit_id=$orgunit_id AND request_institute.status='Pending' GROUP BY request_institute.`req_id`
                                            ";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                        }
                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->

                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organization</th>
                                                        <th>Institute</th>
                                                        <th>Domain(s)</th>
                                                        <th>Requested By</th>
                                                        <th>Status</th>
                                                        <th>Requested On</th>
                                                    




                                                    </tr>
                                                </thead>
                                                <tbody">

                                                    <?php






                                                    while ($row = $stmt->fetch()) {

                                                     


                                                    ?>
                                                        <tr>
                                                            <td><?php
                                                            
                                                           
                                                               $stmtr = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id =$orgunit_id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();
                                                                        echo $rowr['orgunit_name'];
                                                                        ?>
                                                        </td>
                                                         <td>
                                                               <?php
                                                                   echo $row['req_institute']; ?>

                                                                </td>
                                                                <td><?php echo $row["domains"] . " "; ?></td>
                                                                <td>
                                                               <?php
                                                                   $row['requested_by'];
                                                                   $user_id= $row["requested_by"]; 
                                                                   $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                                                                   $stmtu->bindValue(':AdminId', $user_id);
                                                                   $stmtu->execute();
                                                                   $username= $stmtu->fetch();
                                                                   echo $username['username']; ?>

                                                                </td>
                                                               
                                                                <td><span class="badge badge-warning"><?php echo $row["status"] . " "; ?><i class="fa fa-history"></i> </span> </td>

                                                            
                                                                <td>
                                                               <?php
                                                                   echo $row['system_date']; ?>

                                                                </td>

                                                         
                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->
                                </div>
<br><br> <br>
                             
                         
                                   
                            </section>  
                            <section id="Accepted" data-status="Accepted">
                            <h4>Accepted Requests</h4> <br>
                                    <div class="table-responsive">
                                            <?php

                                        
                                            
                                            
                                        if(isset($_SESSION['orgunit_id']))
                                        {
                                            $orgunit_id= $_SESSION['orgunit_id'];
                                            $sql = "SELECT *, GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domains FROM`request_institute` 
                                            INNER JOIN request_domain ON request_domain.req_id= request_institute.req_id 
                                            WHERE orgunit_id=$orgunit_id AND request_institute.status='Accepted' AND request_domain.status='Accepted' GROUP BY request_institute.`req_id`
                                            ";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                        }
                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->

                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organization</th>
                                                        <th>Institute</th>
                                                        <th>Domain(s)</th>
                                                        <th>Requested By</th>
                                                        <th>Requested On</th>
                                                        <th>Status</th>
                                                        <th> Accepted By </th>
                                                        <th> Accepted On </th>
                                                    




                                                    </tr>
                                                </thead>
                                                <tbody">

                                                    <?php






                                                    while ($row = $stmt->fetch()) {

                                                     


                                                    ?>
                                                        <tr>
                                                            <td><?php
                                                            
                                                           
                                                               $stmtr = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id =$orgunit_id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();
                                                                        echo $rowr['orgunit_name'];
                                                                        ?>
                                                        </td>
                                                         <td>
                                                               <?php
                                                                   echo $row['req_institute']; ?>

                                                                </td>
                                                                <td><?php echo $row["domains"] . " "; ?></td>
                                                                <td>
                                                               <?php
                                                                   
                                                                   $user_id= $row["requested_by"]; 
                                                                   $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                                                                   $stmtu->bindValue(':AdminId', $user_id);
                                                                   $stmtu->execute();
                                                                   $username= $stmtu->fetch();
                                                                   echo $username['username']; ?>

                                                                </td>
                                                                <td>
                                                               <?php
                                                                   echo $row['system_date']; ?>

                                                                </td>
                                                               
                                                                <td><span class="badge badge-success"><?php echo $row["status"] . " "; ?><i class="fa fa-check-circle"></i> </span> </td>

                                                                <td>
                                                               <?php
                                                               
                                                                   
                                                                   $user_id= $row["accept_rej_by"]; 
                                                                   $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                                                                   $stmtu->bindValue(':AdminId', $user_id);
                                                                   $stmtu->execute();
                                                                   $accept_rej_by= $stmtu->fetch();
                                                                   echo $accept_rej_by['username']; ?>

                                                                </td>
                                                                <td>
                                                               <?php
                                                                   echo $row['accept_rej_date']; ?>

                                                                </td>
                                                                

                                                         
                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->
                                </div>   

                            </section>
                            <section id="org_institutes" data-status="org_institutes">

                            <div class="table-responsive">
                                            <?php

                                        
                                            
                                            
                                        if(isset($_SESSION['orgunit_id']))
                                        {
                                            $orgunit_id= $_SESSION['orgunit_id'];
                                            $sql = "SELECT  registered_institutions.ri_id as ri_id, org_inst_status, institute_name
                                              FROM`organizational_institutes` INNER JOIN registered_institutions
                                              ON registered_institutions.ri_id= organizational_institutes.ri_id
                                               WHERE orgunit_id=$orgunit_id";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                        }
                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organizational Unit Name</th>
                                                        <th>Institute(s)</th>
                                                        <th>Status</th>
                                                        <th> Actiion </th>




                                                    </tr>
                                                </thead>
                                                <tbody">

                                                    <?php






                                                    while ($row = $stmt->fetch()) {

                                                     


                                                    ?>
                                                        <tr>
                                                            <td><?php
                                                            
                                                           
                                                               $stmtr = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id =$orgunit_id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();
                                                                        echo $rowr['orgunit_name'];
                                                                        ?>
                                                        </td>
                                                         <td>
                                                               <?php echo wordwrap($row["institute_name"], 50, "<br>\n"); ?>

                                                                </td>
                                                                <td>
                                                            
                                                            <?php if($row["org_inst_status"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $row["org_inst_status"] . " ";?> 
                                                           <?php } else if($row["org_inst_status"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $row["org_inst_status"] . " "?>

                                                           <?php }
                                                            
                                                            ?>
                                                            </td>

                                                            <td><a class="btn btn-warning btn-sm" href="modify_org_inst_status.php?orgunit_id=<?php echo $orgunit_id; ?>&ri_id=<?php echo $row['ri_id']; ?>">Modify Status </a></td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->
                                </div>


<br><br> <br>


<div class="table-responsive">
                                            <?php

                                        
                                            //User not logged in. Redirect them back to the login page.
                                            
                                        if(isset($_SESSION['orgunit_id']))
                                        {
                                            $orgunit_id= $_SESSION['orgunit_id'];
                                            $sql = "SELECT organizational_institutes.ou_inst_id,ri_id, GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) 
                                            as domain FROM`org_institute_maildomain` RIGHT JOIN organizational_institutes ON 
                                            organizational_institutes.ou_inst_id = org_institute_maildomain.ou_inst_id WHERE 
                                            orgunit_id=$orgunit_id GROUP BY ri_id";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                        }
                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                      
                                                        <th>Institute</th>
                                                        <th>Domain</th>
                                                        <th> Actiion </th>




                                                    </tr>
                                                </thead>
                                                <tbody">

                                                    <?php






                                                    while ($row = $stmt->fetch()) {

                                                     


                                                    ?>
                                                        <tr>
                                                            <td><?php

                                                            $stmti= $conn->prepare("SELECT institute_name FROM registered_institutions WHERE ri_id='$row[ri_id]'");
                                                            $stmti->execute();
                                                            $institute_name= $stmti->fetch();
                                                            $institute_name=$institute_name['institute_name'];
                                                            echo $institute_name;
                                                                        ?>
                                                        </td>
                                                         <td>
                                                               <?php echo wordwrap($row["domain"], 50, "<br>\n"); ?>

                                                                </td>


                                                            <td><a class="btn btn-warning btn-sm" href="orgunit_institutes_domain.php?ou_inst_id=<?php echo $row['ou_inst_id']; ?>">Modify</a></td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->

              




                     



                                </div>
                            </section>


                            <section id="Rejected" data-status="Rejected">
                            <h4>Rejected Requests</h4> <br>
                                    <div class="table-responsive">
                                            <?php

                                        
                                            
                                            
                                        if(isset($_SESSION['orgunit_id']))
                                        {
                                            $orgunit_id= $_SESSION['orgunit_id'];
                                            $sql = "SELECT *, GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domains, request_domain.req_id as req_id FROM`request_institute` 
                                            INNER JOIN request_domain ON request_domain.req_id= request_institute.req_id 
                                            WHERE orgunit_id=$orgunit_id AND request_institute.status='Rejected' AND request_domain.status='Rejected' GROUP BY request_institute.`req_id`
                                            ";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                        }
                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->

                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organization</th>
                                                        <th>Institute</th>
                                                        <th>Domain(s)</th>
                                                        <th>Requested By</th>
                                                        <th>Requested On</th>
                                                        <th>Status</th>
                                                        <th>Rejected By</th>
                                                        <th>Rejected On</th>
                                                        <th>Reason</th>
                                                       
                                                    




                                                    </tr>
                                                </thead>
                                                <tbody">

                                                    <?php






                                                    while ($row = $stmt->fetch()) {

                                                     


                                                    ?>
                                                        <tr>
                                                            <td><?php
                                                            
                                                           
                                                               $stmtr = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id =$orgunit_id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();
                                                                        echo $rowr['orgunit_name'];
                                                                        ?>
                                                        </td>
                                                         <td>
                                                               <?php
                                                                   echo $row['req_institute']; ?>

                                                                </td>
                                                                <td><?php echo $row["domains"] . " "; ?></td>
                                                                <td>
                                                               <?php
                                                                  
                                                                   $user_id= $row["requested_by"]; 
                                                                   $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                                                                   $stmtu->bindValue(':AdminId', $user_id);
                                                                   $stmtu->execute();
                                                                   $username= $stmtu->fetch();
                                                                   echo $username['username']; ?>

                                                                </td>
                                                                <td>
                                                               <?php
                                                                   echo $row['system_date']; ?>

                                                                </td>
                                                                <td><span class="badge badge-danger"><?php echo $row["status"] . " "; ?><i class="fa fa-times-circle"></i> </span> </td>
                                                                <td>
                                                               <?php
                                                                 
                                                                   $user_id= $row["accept_rej_by"]; 
                                                                   $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                                                                   $stmtu->bindValue(':AdminId', $user_id);
                                                                   $stmtu->execute();
                                                                   $accept_rej_by= $stmtu->fetch();
                                                                   echo $accept_rej_by['username'];
                                                                   ?>

                                                                </td>
                                                                <td>
                                                               <?php
                                                                   echo $row['accept_rej_date']; ?>

                                                                </td>
                                                                <td>
                                                                <button id="<?php echo $row['req_id']; ?>" value="<?php echo $row['reason']; ?>" class="btn btn-info" onclick="SendIdReason(<?php echo $row['req_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenter1">View</button>
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

                                            <!--------------table---------->
                                </div>   

                            </section>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Javascript -->
 
   
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

    <script src="assets/js/pages/tables/editable-table.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>
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


<script src="index.js"></script>

<script type="text/javascript">
        $(document).ready(function() {

 $('#multi-select-demo').multiselect({
        maxHeight: 300,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
  
    });

        
            $('.search-box input[type="text"]').on("keyup input", function() {
                /* Get input value on change */
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if (inputVal.length) {
                    $.get("ajax_university.php", {
                        term: inputVal
                    }).done(function(data) {

                        // Display the returned data in browser
                     
                        resultDropdown.html(data);


                    });
                } else {
                    resultDropdown.empty();
                }
            });

            // Set search input value on click of result item
            $(document).on("click", ".result p", function() {
                $('#multi-select-demo').empty();
                $('#multi-select-demo').multiSelect('refresh');
                $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                $(this).parent(".result").empty();

                var str = document.getElementById("uni").value;

            


                $.ajax({
                        url: "ajax_university_info.php",
                        method: "POST",
                        data: {
                            str: str
                        },
                        dataType: "JSON",
                        success: function(data) {

                        document.getElementById("institute_name").value=data.Name;
                        document.getElementById("grid_id").value=data.ID;



                var grid_id =   document.getElementById("grid_id").value;
          
                $.ajax({
                        url: "ajax_domains.php",
                        method: "POST",
                        data: {
                           
                            grid_id:grid_id
                        },
                        dataType: "JSON",
                        success: function(data) {
                         
                            for (var i in data) {
                               
                            $('#multi-select-demo').append($('<option></option>').attr('value', data[i].domain).attr('selected', 'selected').text(data[i].domain));
                            $("#multi-select-demo").multiselect('destroy');
                            $("#multi-select-demo").multiselect();
                            }
                        }


                    }

                )
               
                   

                        }


                    }

                )




              




            });


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

   
    if($_GET["rinst"]=="true") {
        
        jQuery('#inst_exist_alert').show();
        window.history.replaceState({}, document.title, "/" + "request_institute.php");
 
 
    }
    if($_GET["inserted"]=="true") {
        
        jQuery('#inserted_show').show();
        window.history.replaceState({}, document.title, "/" + "request_institute.php");
 
 
    }
    
    



});
</script>
    <script> 
       $(document).ready(function() {


            setInterval(function() {


                let optionsLength = $("#multi-select-demo :selected").length;
                //let optionsLength = document.getElementById("multi-select-demo").length;
                var institute_name = document.getElementById("institute_name").value;
                var orgunitid="";
               orgunitid=<?php echo $orgunit_id; ?>
        
                if (institute_name.trim() != "" && optionsLength > 0 &&orgunitid!="") {
                    $('#sendreq').prop('disabled', false);
                  

                } else {
                    $('#sendreq').prop('disabled', true);
                    

                }
            }, 200);

 });
    </script>
   <script>
        function addoption() 
        {
            var new_dom= document.getElementById('new_domain').value;

            if(new_dom!="")
            {
            $('#multi-select-demo').append($('<option></option>').attr('value', new_dom).attr('selected', 'selected').text(new_dom));
            $("#multi-select-demo").multiselect('destroy');
            $("#multi-select-demo").multiselect();
            }
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