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
if (isset($_SESSION['RRM'])) {
    if ($_SESSION['RRM'] == "NO") {

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
                            <h2>Reply To Emails Request Management</h2>
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
                <th>Requested By</th>
                <th>Reply to Email</th>
                <th>Requested On</th>
                <th> Status </th>
                <th>System Status</th>
                <th> Action </th>




            </tr>
        </thead>
        <tbody>

            <?php






            while ($row = $stmt->fetch()) {

 //org specific users only
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id AND ou_status='Active'");

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

                    <td><span class="badge badge-warning"><?php echo $row["status"] . " "; ?><i class="fa fa-history"></i> </span> </td>
                    <td>

                    <?php

                    $sys_status="";
                    $req_rtem= $row['req_rep_to_email'];
                    $stmtr= $conn->prepare("SELECT rtem_status, reply_to_email FROM reply_to_emails WHERE reply_to_email=:reply_to_email");
                    $stmtr->bindValue(':reply_to_email', $req_rtem);
                    $stmtr->execute();
                    $rtem=$stmtr->fetch();

                    if($stmtr->rowCount()>0){
                        if($rtem['rtem_status']=='Active')
                        {
                            
                            $sys_status="Already in system";

                        }
                        else if($rtem['rtem_status']=='In Active'){
                            $sys_status= "System Blocked";


                        }


                    }
                    else{
                        //reply to email is not present in the system
                        $sys_status= "New";

                    }
                          echo $sys_status;
                  
                   /////////////// modal 
                
    $stmtrt = $conn->prepare("SELECT `req_rep_to_email` FROM `tbl_request_rte` WHERE `request_rte_id`=:request_rte_id");
    $stmtrt->bindValue(':request_rte_id',  $row['request_rte_id']);
    $stmtrt->execute();
    $req_rep_to_email= $stmtrt->fetch();
    $req_rep_to_email= $req_rep_to_email['req_rep_to_email'];

  
    $stmtb= $conn->prepare("SELECT reply_to_emails.rtemid as rtemid, status,`reply_to_email`, rtem_status, orgunit_name,tbl_organizational_unit.orgunit_id as orgid FROM reply_to_emails INNER JOIN
     tbl_organizational_unit INNER JOIN tbl_orgunit_rte ON
     reply_to_emails.rtemid=tbl_orgunit_rte.rtemid AND 
     tbl_organizational_unit.orgunit_id = tbl_orgunit_rte.orgunit_id AND 
     reply_to_emails.reply_to_email=:reply_to_email");
      $stmtb->bindValue(':reply_to_email', $req_rep_to_email);
      $stmtb->execute();


    


     ?>


                <button style="border-radius: 50%" id="<?php echo $row['request_rte_id']; ?>" 
                value="<?php echo $sys_status; ?>" class="btn btn-primary btn-sm" 
                onclick="SendIdRte(<?php echo $row['request_rte_id']?>)" 
                data-toggle="modal" data-target="#R<?php echo $row['request_rte_id']?>"><i class="fa fa-question-circle"></i> </button>

                    <!-- Large Size -->
<div class="modal fade"  id="R<?php echo $row['request_rte_id']?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
     
                <div class="alert alert-dark" role="alert"> Info about the selected reply to email</div>
            </div>
            <div class="modal-body"> 
            

<div id="show_info"></div>


<table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
<thead>
    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
      
        <th>Reply to email</th>
        <th> Organization </th>
        <th> Organization Status</th>
        <th> System Status </th>
        <th> Completed </th>
        <th> Archived </th>
        <th> In Progress </th>
        <th> Active </th>





    </tr>
    </thead>
<tbody>
  <?php  while ($rowb = $stmtb->fetch()) {

      //get all AdminID where orgunitid=orgid
    $stmtu= $conn->prepare("SELECT ou_id FROM tbl_orgunit_user WHERE orgunit_id=:orgunit_id AND ou_status='Active'");
    $stmtu->bindValue(':orgunit_id', $rowb['orgid']);
    $stmtu->execute();
    $AdminIDs= $stmtu->fetchAll();
 $admid_string="";
         foreach($AdminIDs as $admid){

            $admid_string.=$admid['ou_id'].",";

            
      }

   
      
   
      //get campaign count
      if( !empty($admid_string)){
        $admid_string =substr_replace($admid_string, "", -1);
      $stmtc= $conn->prepare("SELECT  Camp_Status, ou_id,
      COUNT(
    CASE WHEN Camp_Status = 'Completed' THEN 1
END
) AS Completed,
COUNT(
CASE WHEN Camp_Status= 'Archive' THEN 1
END
) AS Archive  ,
COUNT(
CASE WHEN Camp_Status!= 'Archive' AND Camp_Status!= 'Completed' AND Camp_Status!= 'Active' THEN 1
END
) AS InProgress ,
COUNT(
CASE WHEN Camp_Status='Active' THEN 1
END
) AS Active 


      FROM campaign WHERE rtemid=:rtemid AND ou_id IN($admid_string)");
      $stmtc->bindValue(':rtemid', $rowb['rtemid']);
      $stmtc->execute();
      $count_camp= $stmtc->fetch();

      }

    

      ?>

    
    <tr>
         <td> <?php  echo $rowb['reply_to_email']; ?></td>
         <td> <?php echo $rowb['orgunit_name']; ?></td>
             
       <td>  <?php if($rowb["status"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $rowb["status"] . " ";?> 
                                                           <?php } else if($rowb["status"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $rowb["status"] . " "?>

                                                           <?php }
                                                            
                                                            ?>

                                                           </td>
         
       <td>  <?php if($rowb["rtem_status"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $rowb["rtem_status"] . " ";?> 
                                                           <?php } else if($rowb["rtem_status"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $rowb["rtem_status"] . " "?>

                                                           <?php }
                                                            
                                                            ?>

                                                           </td>
                                                           <td> <?php echo $count_camp['Completed']?>      </td>
                                                           <td>   <?php echo $count_camp['Archive']?>     </td>
       
                                                           <td>    <?php echo $count_camp['InProgress']?>    </td>
       
                                                           <td>    <?php echo $count_camp['Active']?>    </td>

    </tr>

  <?php  } ?>

</tbody>
</table>



        </div>
            <div class="modal-footer">
           
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
                    </td>

                    <td>
                        
          

                    <a target="_blank" href='rtemid_verify.php?id=<?php echo $row["request_rte_id"]; ?>&orgid=<?php echo $row["orgunit_id"]; ?>' ><button type='button' class='btn btn-success'><i class="fa fa-check-circle"></i> Accept </button></a> 
                    <button id="<?php echo $row['request_rte_id']; ?>" value="" class="btn btn-danger btn-sm" onclick="SendId(<?php echo $row['request_rte_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-times-circle"></i> Reject</button>

                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                           
                                                                            <div class="modal-header">
                                                                       
                                                                                <div class="alert alert-dark" role="alert">  Please provide reason to reject the reply to email</div>
                                                                              
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                      
                                                                                    <form method="post" action="rtemid_reject.php">

                                                                                        <div class="input-group input-group-sm mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <label class="input-group-text" for="reason_select">Select Reason:</label>
                                                                                            </div>
                                                                                            <select class="custom-select" id="reason_select" onChange=showHide() name="reason_select">
                                                                                             <option value="Email is blacklisted"> Email is blacklisted</option>
                                                                                             <option value="other"> Other </option>
                                                            
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                        <label id="reasonT" style="display: none" >Reason to Reject</label>
                                                                                        <textarea style="display: none" id="reason" name="reason" class="form-control" rows="5" cols="30"></textarea>

                                                                                        </div>
                                                                                        <input id="request_rte_id" name="request_rte_id" type="hidden" value="">
                                                                                        <button id="reject" type="submit" class="btn btn-secondary">Reject</button>
                                                                                    </form>
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
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id AND ou_status='Active'");

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
 $stmtd = $conn->prepare("SELECT  orgunit_id FROM  tbl_orgunit_user WHERE orgunit_id =:orgunit_id AND ou_status='Active'");

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
        function SendIdRte(id) {
           
            var a = document.getElementById(id).value;
            var p="";
            var msg_new="This reply to email is new and has never been added <br> to system pool."
            var msg_already="This reply to email is already present in the system pool."
            var sys_blk='<span style="color:red;" > This reply to email is blocked in the system. Be careful while activating it. </span> ';
            if(a=="New"){
             
  
            p =
                "<p>" +
                msg_new+ " </p>";
            }
            if(a=="Already in system"){
   
  
   p =
       "<p>" +
       msg_already+ " </p>";
   }


   if(a=="System Blocked"){

    p =
                "<p>" +
                sys_blk + " </p>";

 


   }

            $("#show_info").empty();
            $("#show_info").append(p);
          }
    </script>
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
        function SendId(id) {
          document.getElementById('request_rte_id').value = id;
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
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   
    if($_GET["E_exist"]=="true") {
        
        jQuery('#Email_exist_alert').show();
   
 
 
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