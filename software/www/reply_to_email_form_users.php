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
                            <h2>Reply To Emails Management</h2>
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
                            <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Reply_to_email_pool">Reply To Emails</button> 
                             <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Reply_To_Emails_For_Users">Reply To Emails For Users</button> 
                             <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Swap">Swap Reply To Email</button> 
                              <br>
                              <br>
                              <br>
                              
                                <!-- <div id="wizard_horizontal"> -->
                                    <!-- <h2>Add Reply To Email</h2> -->
                                    <section id="Reply_to_email_pool" data-status="Reply_to_email_pool">
                                    <div class="table-responsive">
                                    <?php
                    $sqlre = "SELECT * FROM reply_to_emails INNER JOIN tbl_orgunit_rte
                    ON tbl_orgunit_rte.rtemid = reply_to_emails.rtemid AND rtem_status='Active'";

                    $stmtre = $conn->prepare($sqlre);
                    $stmtre->execute(); ?>
             
                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;text-align: center;">
                                <th>Organization</th>
                                <th>Reply To emails</th>
                                <th>System Status</th>
                                <th>Organizational Status</th>
                                <th>Reason</th>
                                <th> Action </th>




                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            while ($rowre = $stmtre->fetch()) {

                                if ($_SESSION['orgunit_id'] != NULL) {
                                    if ($rowre["orgunit_id"] != $_SESSION['orgunit_id']){
                                       continue;

                                    }
                                }

                              

                            ?>
                                <tr style="text-align: center;">
                                    <td><?php 
                                    $id=$rowre["orgunit_id"]; 
                                           $stmtdn = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id =:orgunit_id");
                                           $stmtdn->bindValue(':orgunit_id', $id);
                                           $stmtdn->execute();
                                           $org = $stmtdn->fetch();
                                        
                                    
                                    
                                  echo  $org["orgunit_name"] ?></td>
                                    <td><?php echo $rowre["reply_to_email"] . " "; ?></td>
                                    <td >
                                                            <?php if($rowre["rtem_status"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $rowre["rtem_status"] . " ";?> 
                                                           <?php } else if($rowre["rtem_status"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $rowre["rtem_status"] . " "?>

                                                           <?php }
                                                            
                                                            ?></td>
                                    <td>
                                                            <?php if($rowre["status"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $rowre["status"] . " ";?> 
                                                           <?php } else if($rowre["status"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $rowre["status"] . " "?>

                                                           <?php }
                                                            
                                                            ?></td>
                               <?php if($rowre["status"]=="Active") {?>
    <td></td>
    <?php }else {?>

                    <td style="text-align: center;">
                    

                        <button id="<?php echo $rowre['o_rte_id']; ?>" value="<?php echo $rowre['org_rtem_reason']; ?>" class="btn btn-info" onclick="SendIdReason(<?php echo $rowre['o_rte_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenter1">View</button>
                        <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                               
                                                                                <div class="modal-header">
                                                                           
                                                                                    <div class="alert alert-dark" role="alert">  Reason to In Active </div>
                                                                                  
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
                    <?php }?>

                                 


<td>

<a href="rtem_org_status.php?id=<?php echo $rowre["o_rte_id"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>


</td>

                                </tr>
                            <?php }


                            ?>

                        </tbody>
                    </table>
                    </div>

                    
                                        
                                    </section>

                                    <!-- <h2>Reply To Emails For Organizational Unit</h2> -->
                                    <section id="Reply_To_Emails_For_Users" data-status="Reply_To_Emails_For_Users">

                                    <div class="table-responsive">
                    <?php
                    $sql = "SELECT u.username AS username, u.AdminId AS user_id, u.email AS email, 
                                     GROUP_CONCAT( DISTINCT r.rtemid SEPARATOR ',' ) AS reply_to_email FROM admin AS
                                      u LEFT JOIN tbl_user_rte AS r ON u.AdminId = r.user_id GROUP BY u.username";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    ?>
                    <!-- <table class="table center-aligned-table" > -->
                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                <th>Organization</th>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Reply To emails</th>
                                <th> Action </th>




                            </tr>
                        </thead>
                        <tbody>

                            <?php







                            while ($row = $stmt->fetch()) {






                                //Dont show super admin
                                $stmtsr = $conn->prepare("SELECT
                                        r.role_prev_id AS role,
                                        r.restriction_level AS r_level 
                                        FROM
                                            admin AS u
                                        INNER JOIN tbl_user_role_prev AS ur
                                        INNER JOIN tbl_role_privilege AS r
                                        ON
                                        u.AdminId = ur.user_id AND u.AdminId= :AdminId AND
                                        r.role_prev_id= ur.role_prev_id AND  r.restriction_level >0");
                                $stmtsr->bindValue(':AdminId', $row["user_id"]);
                                $stmtsr->execute();

                                if ($stmtsr->rowCount() < 1) {
                                    continue;
                                }



                                //org specific users only
                                $stmtd = $conn->prepare("SELECT  orgunit_name, tbl_organizational_unit.orgunit_id,system_entityid FROM tbl_organizational_unit 
                                INNER JOIN  tbl_orgunit_user ON
                                tbl_organizational_unit.orgunit_id=tbl_orgunit_user.orgunit_id
                                AND user_id =:user_id");

                                $stmtd->bindValue(':user_id', $row["user_id"]);
                                $stmtd->execute();
                              
                                $rowd=$stmtd->fetch();
                              


                        



                                    if ($_SESSION['orgunit_id'] != NULL) {
                                        if ($rowd["orgunit_id"] != $_SESSION['orgunit_id']){
                                           continue;

                                        }
                                    }

                                    if($rowd['system_entityid']!=2 ){
                                        continue;

                                    }
                           
                                


                            ?>
                                <tr>
                                    <td><?php echo  $rowd["orgunit_name"] ?></td>
                                    <td><?php echo $row["username"] . " "; ?></td>
                                    <td><?php echo $row["email"] . " "; ?></td>

                                    <td><?php
                                        if ($row["reply_to_email"] != "") {
                                            $remail_ids = $row["reply_to_email"];
                                            $remail_ids_arr = explode(",", $remail_ids);
                                            $res = "";
                                            foreach ($remail_ids_arr as $id) {
                                                $stmtr = $conn->prepare("SELECT reply_to_email FROM reply_to_emails WHERE rtemid =$id");
                                                $stmtr->execute();
                                                $rowr = $stmtr->fetch();

                                                $res .= $rowr["reply_to_email"] . " ,";
                                            }
                                            $final = "";
                                            $res = substr_replace($res, "", -1);

                                            echo wordwrap($res, 50, "<br>\n");
                                        }   ?></td>


                                    <td><a class="btn btn-warning btn-sm" href="rtemid_user_edit.php?user_id=<?php echo $row["user_id"]; ?>">Modify</a></td>

                                </tr>
                            <?php }


                            ?>

                        </tbody>
                    </table>

    <!--------------table---------->

</section>



                                    
                                                  
</section>


<!-- <h2>Reply To Emails For Organizational Unit</h2> -->

<section id="Swap" data-status="Swap">

<?php
if($_SESSION['r_level']=="0"){

?>
<div class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-info-circle"></i> Select organization from the above dropdown to enable "Swap" button for that particular organization
                            </div>

                           
<?php
}
$stmt = $conn->prepare("SELECT * FROM campaign WHERE Camp_Status<>'Completed' AND Camp_Status<>'Archive'");
$stmt->execute();


$org_rid=$_SESSION['orgunit_id'];
$stmtr=$conn->prepare("SELECT * FROM reply_to_emails INNER JOIN tbl_orgunit_rte
ON tbl_orgunit_rte.rtemid = reply_to_emails.rtemid AND rtem_status='Active' AND status='Active' AND orgunit_id=:orgunit_id

AND reply_to_emails.rtemid NOT IN(SELECT rtemid FROM campaign WHERE Camp_Status<>'Completed' AND Camp_Status<>'Archive')");
$stmtr->bindValue(':orgunit_id', $org_rid );
$stmtr->execute();
$rtems=$stmtr->fetchAll();


?>
<div class="table-responsive">
      <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                            <th>Organziation</th> 
                            <th>User</th>
                                <th>Campaign</th>
                                <th>Campaign Status</th>
                                <th>RTEM Status</th>
                                <th>Campaign Date</th>
                                <th>Current Reply to Email</th>
                                <th>Reply to Email Pool</th>
                                <th>Action</th>
                               




                            </tr>
                        </thead>
                        <tbody>

                            <?php







                            while ($row = $stmt->fetch()) {






                    



                                //org specific users only
                                $stmtd = $conn->prepare("SELECT  orgunit_name, tbl_organizational_unit.orgunit_id,system_entityid FROM tbl_organizational_unit 
                                INNER JOIN  tbl_orgunit_user ON
                                tbl_organizational_unit.orgunit_id=tbl_orgunit_user.orgunit_id
                                AND user_id =:user_id");

                                $stmtd->bindValue(':user_id', $row["AdminID"]);
                                $stmtd->execute();
                              
                                $rowd=$stmtd->fetch();
                              


                        



                                    if ($_SESSION['orgunit_id'] != NULL) {
                                        if ($rowd["orgunit_id"] != $_SESSION['orgunit_id']){
                                           continue;

                                        }
                                    }

                                    if($rowd['system_entityid']!=2 ){
                                        continue;

                                    }
                           
                                


                            ?>
                                <tr>
                                     <td><?php echo  $rowd["orgunit_name"] ?></td>
                                    <td><?php 
                                    
                                    
                                    
                                    $uid=$row["AdminID"];     
                                    $stmtu = $conn->prepare("SELECT username FROM admin WHERE AdminId =$uid");
                                    $stmtu->execute();
                                    $rowu = $stmtu->fetch();
                                    echo $rowu["username"];
                                    
                                    ?></td>
                                    <td><?php echo $row["CampName"]; ?></td>
                                    <td><?php echo $row["Camp_Status"]; ?></td>
                                    <td><?php echo $row['crtem_status'];?></td>
                                    <td><?php 
                                      $datec = explode(" ", $row["Camp_Created_Date"]);
                                    
                                    echo  $datec[0]; ?></td>
                            
                                 

                                    <td><?php
                                        if ($row["rtemid"] != "") {
                                            $id= $row["rtemid"];
                                        
                                                $stmtrr = $conn->prepare("SELECT reply_to_email FROM reply_to_emails WHERE rtemid =$id");
                                                $stmtrr->execute();
                                                $rowrr = $stmtrr->fetch();
                                            echo $rowrr["reply_to_email"];
                                               
                                        }   ?></td>


                                     <td>

                                     <div class="multiselect_div col-12">
                                        <select id="single-selection" name="single" class="multiselect multiselect-custom single">
                                        <option  value=""> None Selected </option>
                                        <?php foreach ($rtems as $rte) { 
                                            $id=$rte["rtemid"];
                                            $stmt1 = $conn->prepare("SELECT reply_to_email FROM reply_to_emails WHERE rtemid =$id");
                                                $stmt1->execute();
                                                $row1 = $stmt1->fetch();
                                            ?>


                                       <option value="<?php echo $id; ?>"> <?php echo  $row1["reply_to_email"]  ?> </option>

                                                    <?php
                                                    
                                                    } ?>
                                        </select>
                                    </div> 

                                     </td>
                                    <td>
                                    <?php if(isset($_SESSION['orgunit_id']) && $_SESSION['orgunit_id']== $rowd["orgunit_id"]){ ?> 
                                                                
                                        <a class="btn btn-warning btn-sm" href="swap.php?CID=<?php echo $row["CampID"]; ?>&rtemid=<?php echo $id; ?>">Swap</a>
                                                                <?php } else {?>
                                                                    <a class="btn btn-warning btn-sm disabled" href="swap.php?CID=<?php echo $row["CampID"]; ?>&&rtemid=<?php echo $id; ?>">Swap</a>
    
                                                                <?php }?>
                                
                                </td>

                                </tr>
                            <?php }


                            ?>

                        </tbody>
                    </table>

<!--------------table---------->
                                                             
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
<script>
        function showHide() {


            var rtem_status = document.getElementById("rtem_status").value;

          

            if (rtem_status.trim() == 'In Active') {
               
                document.getElementById('reason').style.display = 'block'
                $('#reasonr').prop('required',true);


            
               
            } else {
              
                 document.getElementById('reason').style.display = 'none'
                 $('#reasonr').prop('required',false);


            }
        }
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

   
    if($_GET["E_exist"]=="true") {
        
        jQuery('#Email_exist_alert').show();
   
 
 
    }
    if($_GET["blck"]=="true") {
        
        jQuery('#blck_alert').show();
   
 
 
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