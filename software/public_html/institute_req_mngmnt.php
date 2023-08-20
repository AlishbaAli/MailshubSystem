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
if (isset($_SESSION['IRM'])) {
    if ($_SESSION['IRM'] == "NO") {

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
                            <h2>Institutes And Domain Request Management</h2>
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
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Accepted">Accepted</button>
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Rejected">Rejected</button>
                              <br>
                              <br>
                              <br>
                              <section id="Request" data-status="Request">
                              <div class="table-responsive">
    <?php
    $sql = "SELECT * FROM request_institute WHERE status='Pending'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    ?>
    <!-- <table class="table center-aligned-table" > -->
    <table id="table1" class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
        <thead>
            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                <th>Organization</th>
                <th>Requested By</th>
                <th>Institute</th>
                <th class="col-lg-5 col-md-5 col-sm-5">Domain(s)</th>
                <th>Requested On</th>
                <th> Status </th>
                <th> Action </th>




            </tr>
        </thead>
        <tbody>

            <?php





$i=0;
            while ($row = $stmt->fetch()) {

                $req_id= $row['req_id'];

 //org specific users only
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id AND ou_status='Active'");

 $stmtd->bindValue(':orgunit_id', $row["orgunit_id"]);
 $stmtd->execute();
 $flago = 0;
$orgunit_id=$row["orgunit_id"];

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
                    <td>
                    <?php
                                                            
                                                           
                                                            $stmtr = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id =$orgunit_id");
                                                                     $stmtr->execute();
                                                                     $rowr = $stmtr->fetch();
                                                                     echo $rowr['orgunit_name'];
                                                                     ?>
                    </td>
                    <td><?php  
                    $user_id= $row["requested_by"]; 
                    $stmtu=$conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                    $stmtu->bindValue(':AdminId', $user_id);
                    $stmtu->execute();
                    $username= $stmtu->fetch();
                    echo $username['username'];
                    
                    ?></td>
                    <td><?php echo $row["req_institute"] . " "; ?></td>
                    <td>
                        <?php $stmtd= $conn->prepare("SELECT domain FROM request_domain WHERE req_id='$req_id' ");
                        $stmtd->execute();
                        $rowd= $stmtd->fetchAll();
                        
                        ?>

                    <div class="multiselect_div col-12">
                                                    <select  id="multi-select-demo<?php echo $i; ?>" name="domains[]"  multiple="multiple">
                                                    <?php foreach ($rowd as $output) { ?>
                                                        <option value="<?php echo $output['domain']; ?>" <?php echo ' selected="selected"'; ?>> <?php echo $output['domain']; ?>
                                                        </option>
                                                        
                                                    <?php
                                                    } ?>

                                                  
                                                </select>
                                                </div>
                    </td>
                    <td><?php echo $row["system_date"] . " "; ?></td>

                    <td><span class="badge badge-warning"><?php echo $row["status"] . " "; ?><i class="fa fa-history"></i> </span> </td>
              

                    <td>
                        
          
                  
                    <a   style="display:block"  id="<?php echo "accept".$i?>"  target="_blank" href='accept_domain_db.php?id=<?php echo $row["req_id"]; ?>&orgid=<?php echo $orgunit_id; ?> &grid_id=<?php echo $row['grid_id']; ?>' ><button type='button' class='btn btn-success'><i class="fa fa-check-circle"></i> Accept </button></a> <br>
                    <button  style="display:none" id="accept_selected<?php echo $i?>" value="" class="btn btn-info btn-sm" onclick="SendId2(<?php echo $row['req_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenterSelection<?php echo $row['req_id']; ?>"><i class="fa fa-check-circle"></i> Accept Selection</button> <br>
                    <button id="<?php echo $row['req_id']; ?>" value="" class="btn btn-danger btn-sm" onclick="SendId(<?php echo $row['req_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-times-circle"></i> Reject</button>

                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                           
                                                                            <div class="modal-header">
                                                                       
                                                                                <div class="alert alert-dark" role="alert">  Please provide reason to reject the request</div>
                                                                              
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                      
                                                                                    <form method="post" action="reject_domain_db.php">

                                                                                        <div class="input-group input-group-sm mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <label class="input-group-text" for="reason_select">Select Reason:</label>
                                                                                            </div>
                                                                                            <select class="custom-select" id="reason_select" onChange=showHide() name="reason_select">
                                                                                             <option value="Wrong Domains"> Wrong Domains</option>
                                                                                             <option value="other"> Other </option>
                                                            
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                        <label id="reasonT" style="display: none" >Reason to Reject</label>
                                                                                        <textarea style="display: none" id="reason" name="reason" class="form-control" rows="5" cols="30"></textarea>

                                                                                        </div>
                                                                                        <input id="req_id" name="req_id" type="hidden" value="">
                                                                                        <button id="reject" type="submit" class="btn btn-secondary">Reject</button>
                                                                                    </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                    <div class="modal fade" id="exampleModalCenterSelection<?php echo $row['req_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                           
                                                                            <div class="modal-header">
                                                                       
                                                                                <div class="alert alert-dark" role="alert">  Please provide reason to reject the deselected domains</div>
                                                                              
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                      
                                                                                    <form method="post" action="accept_selected_domain_db.php">

                                                                                        <div class="input-group input-group-sm mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <label class="input-group-text" for="reason_deselect">Select Reason:</label>
                                                                                            </div>
                                                                                            <?php 
                                                                                            $id= "reason_deselect".$row['req_id'];
                                                                                            ?>
                                                                                            <select class="custom-select" id="<?php echo $id;?>" onChange="showHide2('<?php echo $row['req_id'];?>')"  name="reason_deselect">
                                                                                             <option value="Wrong Domains"> Wrong Domains</option>
                                                                                             <option value="other"> Other </option>
                                                            
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                        <label id="<?php echo "reasonT2".$row['req_id'];?>" style="display: none" >Reason to Reject</label>
                                                                                        <textarea style="display: none" id="<?php echo "reason2".$row['req_id'];?>" name="reason2" class="form-control" rows="5" cols="30"></textarea>

                                                                                        </div>
                                                                                        <input value="<?php echo $row['req_id'];?>" id="req_id2" name="req_id2" type="hidden">
                                                                                       
                                                                                      
                                                                                       <input value="" id="<?php echo "grid_values".$i?>" name="grid_values" type="hidden">
                                                                                       <input value="<?php echo $orgunit_id;?>"  name="orgunit_id" type="hidden">
                                                                                       <input value="<?php echo $row['grid_id'];?>"  name="grid_id" type="hidden">
                                            
                                                                                        <button id="<?php echo "reject2".$row['req_id'];?>" type="submit" class="btn btn-secondary">Reject</button>
                                                                                    </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                
                </td>

                </tr>
            <?php $i++;}


            ?>

        </tbody>
    </table>

    <!--------------table---------->

</section>

                                    <section id="Accepted" data-status="Accepted">

                                    <h4>Accepted Requests</h4> <br>
                                    <div class="table-responsive">
                                            <?php

                                        
                                            
                                            
                                       
                                            $sql = "SELECT *, GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domains FROM`request_institute` 
                                            INNER JOIN request_domain ON request_domain.req_id= request_institute.req_id 
                                            WHERE  request_institute.status='Accepted' AND request_domain.status='Accepted' GROUP BY request_institute.`req_id`
                                            ";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                        
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

 //org specific users only
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id AND ou_status='Active'");

 $stmtd->bindValue(':orgunit_id', $row["orgunit_id"]);
 $stmtd->execute();
 $flago = 0;
$orgunit_id=$row["orgunit_id"];

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



                                    <!-- <h2> Reply To Emails For Users</h2> -->
                                    <section id="Rejected" data-status="Rejected">

                                    <h4>Rejected Requests</h4> <br>
                                    <div class="table-responsive">
                                            <?php

                                        
                                            
                                            
                                        
                                            $sql = "SELECT *, GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domains, request_domain.req_id as req_id FROM`request_institute` 
                                            INNER JOIN request_domain ON request_domain.req_id= request_institute.req_id 
                                            WHERE orgunit_id=$orgunit_id AND request_institute.status='Rejected' AND request_domain.status='Rejected' GROUP BY request_institute.`req_id`
                                            ";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                        
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

                                                 
 //org specific users only
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id AND ou_status='Active'");

 $stmtd->bindValue(':orgunit_id', $row["orgunit_id"]);
 $stmtd->execute();
 $flago = 0;
$orgunit_id=$row["orgunit_id"];

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









<!----------table----------->




                                </div>
                         
                                 



                                        <!----------table----------->

                                     
        




                                


                            </div>
                        </div>
                    </div>
                </div>


                <!---Add code here-->




            </div>
        </div>

    </div>



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
        function showHide() {


            var reason_select = document.getElementById("reason_select").value;

            if (reason_select == "other") {
                document.getElementById('reason').style.display = 'block'
                document.getElementById('reasonT').style.display = 'block'
            } else {
                document.getElementById('reason').style.display = 'none'
                document.getElementById('reasonT').style.display = 'none'
             
            }
        }
    </script>
      <script>
        function showHide2(id) {
          // alert(id)

            var reason_deselect = document.getElementById("reason_deselect"+id).value;
          

            if (reason_deselect == "other") {
                document.getElementById('reason2'+id).style.display = 'block'
                document.getElementById('reasonT2'+id).style.display = 'block'
            } else {
                document.getElementById('reason2'+id).style.display = 'none'
                document.getElementById('reasonT2'+id).style.display = 'none'
             
            }
        }
    </script>
    <script>
        function SendId(id) {
          document.getElementById('req_id').value = id;
            setInterval(function() {
                var reason_select = document.getElementById("reason_select").value;

                var reason = document.getElementById("reason").value;

                if (reason.trim() != "") {
                    $('#reject').prop('disabled', false);
                
                
                } else {
                    $('#reject').prop('disabled', true);
                  

                    }

                    if(reason_select!="other"){
                        $('#reject').prop('disabled', false);

                    }
               
            }, 200);
        }
    </script>
     <script>
        function SendId2(id) {
        
            setInterval(function() {
                var reason_deselect = document.getElementById("reason_deselect"+id).value;

                var reason2 = document.getElementById("reason2"+id).value;

                if (reason2.trim() != "") {
                    $('#reject2'+id).prop('disabled', false);
                
                
                } else {
                    $('#reject2'+id).prop('disabled', true);
                  

                    }

                    if(reason_deselect!="other"){
                        $('#reject2'+id).prop('disabled', false);

                    }
               
            }, 200);
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

    <script type="text/javascript">
  
    function getOptions(node, isFilter,i) {

  return {
    enableCaseInsensitiveFiltering: isFilter,
    includeSelectAllOption: true,
    filterPlaceholder: 'Search ...',
    //nonSelectedText: node,
    numberDisplayed: 1,
    buttonWidth: '100%',
    maxHeight: 400,
  
    onChange: function(element, checked) {
                if (checked === true) {
                    //action taken here if true
                }
                else if (checked === false) {
                    if (confirm('Do you wish to deselect the element?')) {

                        document.getElementById('accept_selected'+i).style.display = 'block' 
                     document.getElementById('accept'+i).style.display = 'none'
                     
                    }
                    else {
                        $(node).multiselect('select', element.val());
                    }
                }

                var grid_values = $('#'+node).val();
               // alert(grid_values.toString())

                grid= grid_values.toString();
                document.getElementById('grid_values'+i).value = grid;

// alert(i)
//                 $('#grid_values'+i).append($('<option></option>').attr('value',  grid_values).attr('selected', 'selected').text( grid_values));
//             $("#grid_values"+i).multiselect('destroy');
// $("#grid_values"+i).multiselect();

               
            },
    onSelectAll: function () {
        document.getElementById('accept_selected'+i).style.display = 'none' 
                     document.getElementById('accept'+i).style.display = 'block'
            }
         

  }
}
var table = document.getElementById("table1");
    var totalRowCount = table.rows.length; 
    totalRowCount=totalRowCount-1;
for (let i = 0; i < totalRowCount; i++) {

$('#multi-select-demo'+i).multiselect(getOptions('multi-select-demo'+i, true,i));
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