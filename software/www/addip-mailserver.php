<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
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
                            <h2>Add IP Details</h2>
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
                <div class="body">

                    <div class="demo-masked-input">
                        <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                
                            
                                                                
                    <div  id="Record_already_exist" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all mr-2"></i> IP already exists!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                                                
                   
                            <div  id="blck_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                    Email Address/Hostname blocked by system!<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>


                            <form action="addip-mailserver_db.php" method="post">

                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6">

                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <?php $sql = "SELECT * FROM service_providers";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $sps = $stmt->fetchAll();
                                                ?>
                                                <b>Service Provider</b>
                                             
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-Desktop"></i></span>
                                                    </div>
                                                    <select name="sp" id="sps" class="form-control" required>
                                                    <option value="" disabled selected> Select Service Provider </option>
                                                        <?php foreach ($sps as $output) { ?>
                                                            <option value="<?php echo $output["sp_id"]; ?>"> <?php echo $output["sp_name"]; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                      
                                        <div id=""class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                                <b>IP Pool</b>
                                               <div class="input-group mb-3">
                                                   <div class="input-group-prepend">
                                                       <span class="input-group-text"><i class="fa fa-Desktop"></i></span>
                                                   </div>
                                                   <select name="ip_pool" id="response4" class="form-control" required>
                                                   <option value="" disabled selected> Select IP Pool <?php echo $service_pro ;?></option>
                                      
                                        
                                       
                                        </select>
                                                </div>
                                        </div> 
                                        </div> 

                                        <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                               
                                               <b>IP Address</b>
                                               <div class="input-group mb-3">
                                                   <div class="input-group-prepend">
                                                       <span class="input-group-text"><i class="fa fa-Desktop"></i></span>
                                                   </div>
                                                   <select name="ipaddress" id="ipaddress" class="form-control" required>
                                                   <option value="" disabled selected> Select IP Address <?php echo $ip_pool ;?></option>
                                                   </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>IP Address</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                    </div>
                                                    <input name="ipaddress" type="text" class="form-control" placeholder="Ex: 255.255.255.255" required>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>IP Subnet</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                    </div>
                                                    <input name="ipsubnet" type="text" class="form-control" placeholder="Ex: 255.255.255.255" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>IP Gateway</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                    </div>
                                                    <input name="ipgateway" type="text" class="form-control" placeholder="Ex: 255.255.255.255" required>
                                                </div>
                                            </div> -->


                                        <!-- </div>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>Host Name</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-bars"></i></span>
                                                    </div>
                                                    <input name="hostname" type="text" class="form-control" required>
                                                </div>
                                            </div>


                                        </div> -->
                                        <!-- <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>Service Provider</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-bars"></i></span>
                                                    </div>
                                                    <input name="service_provider" type="text" class="form-control" required>
                                                </div>
                                            </div>


                                        </div> -->
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <!-- <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>Email Address</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-envelope-o"></i></span>
                                                    </div>
                                                    <input name="emailaddress" type="text" class="form-control email" placeholder="Ex: example@example.com" required>
                                                </div>
                                            </div>


                                        </div> -->
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <?php $sql = "SELECT * FROM mailservers";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $mailservers = $stmt->fetchAll();
                                                ?>
                                                <b>Mail Server</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-Desktop"></i></span>
                                                    </div>
                                                    <select name="mailserver" class="form-control" required>

                                                        <?php foreach ($mailservers as $output) { ?>
                                                            <option value="<?php echo $output["mailserverid"]; ?>"> <?php echo $output["vmname"]; ?> </option>
                                                        <?php
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>

                                        <!-- <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>IP Hour</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="icon-clock"></i></span>
                                                    </div>


                                                    <select name="iphour" class="form-control" required>



                                                        <?php for ($i = 1; $i <= 24; $i++) {
                                                        ?>
                                                            <option value=<?php echo $i ?>> <?php echo $i ?> </option>
                                                        <?php } ?>

                                                    </select>





                                                </div>
                                            </div>


                                        </div> -->


                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3">

                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">

                                                    </div>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                        Add
                                                    </button>

                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>

                            </form>




                        </div>

                    </div>

                </div>
                <div class="table-responsive">
                    <?php
                    $sql = "SELECT * FROM ipdetails
                    inner join mailservers on mailservers.mailserverid = ipdetails.mailserverid 
                                                left join ip_hostname on ip_hostname.ip_hostname_id = ipdetails.ip_hostname_id
                                                left join ip_addresses on ip_addresses.ip_addresses_id = ip_hostname.ip_addresses_id
                                                left join ip_pool on ip_pool.ip_pool_id = ip_addresses.ip_pool_id
                                                left join service_providers on service_providers.sp_id=ip_pool.sp_id";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    ?>
                    <!-- <table class="table center-aligned-table" > -->
                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">

                        <thead>
                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                <th>IP Address</th>
                                <th>IP Subnet</th>
                                <th>IP Gateway</th>
                                <th>Hostname</th>
                                <th>Service Provider</th>
                                <th>Email Address</th>
                                <th>Added By</th>
                                <th>Mail Server</th>
                                <th>Status</th>
                                <th> Action </th>



                            </tr>
                        </thead>
                        <tbody>

                            <?php






                            while ($row = $stmt->fetch()) {


                                $id = $row["mailserverid"];

                                $sqlm = "SELECT vmname FROM mailservers WHERE mailserverid= :mailserverid";

                                $stmtm = $conn->prepare($sqlm);
                                $stmtm->bindValue(':mailserverid', $id);
                                $stmtm->execute();
                                $mailserver = $stmtm->fetch();

                                $adminid=$row["added_by"];
                                $add="SELECT username from admin where AdminId = '$adminid' ";
                                $add=$conn->prepare($add);
                                $add->execute();
                                $added_by=$add->fetch();

                            ?>
                                <tr>
                                    <td><?php echo $row["ipaddress"] . " "; ?></td>

                                    <td><?php echo $row["ipsubnet"] . " "; ?></td>

                                    <td><?php echo $row["ipgateway"] . " "; ?></td>
                                    <td><?php echo $row["hostname"] . " "; ?></td>
                                    <td>

                                    <?php 
                                                                // $sip=$row["service_provider"];
                                                                // $stmtsp = $conn->prepare("SELECT sp_name FROM service_providers WHERE sp_id= $sip");
                                                                // $stmtsp->execute();
                                                                // $sps = $stmtsp->fetch();
                                                               echo $row['sp_name'];
                                                                
                                                                ?>
                                    </td>

                                    <td><?php echo $row["emailaddress"] . " "; ?></td>

                                    <td><?php echo $added_by["username"] . " "; ?></td>
                                    <td><?php echo $mailserver["vmname"]; ?></td>
                                    <td>                                  <?php if($row["ipstatus"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $row["ipstatus"] . " ";?> 
                                                           <?php } else if($row["ipstatus"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $row["ipstatus"] . " "?>

                                                           <?php }
                                                            else if($row["ipstatus"]=="WHITELIST"){ ?>
                                                                <span class="badge badge-light">  <?php echo $row["ipstatus"] . " "?>
    
                                                               <?php }
                                                                else if($row["ipstatus"]=="BLACKLIST"){ ?>
                                                                    <span class="badge badge-dark">  <?php echo $row["ipstatus"] . " "?>
        
                                                                   <?php }
                                                            
                                                            ?></td>
                                    <td> <a href="ipdetails_edit.php?id=<?php echo $row["ipdetailid"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>

                                    </td>
                                </tr>
                            <?php }


                            ?>

                        </tbody>
                    </table>

                    <!---Add code here-->




                </div>
            </div>

        </div>

        <!-- Javascript -->




        <!-- Javascript -->
        <script>
            function deleteclick() {
                return confirm("Do you want to Delete this Reply To Email?")
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

<!-- <script>
$(document).ready(function(){

    $("#sps").on('change', function(){
        var pcs = $(this).val();
alert(pcs);
        $.ajax({
                type: "POST",
                url: "addip-mailserver.php",
                data: {pcs: pcs}
                // success: function(theResponse) {
                //     alert(pcs_val);
                //         $('#outputDiv').html(theResponse);                                              
                //     }                   
            });
    });

  


});     
</script> -->
<script>

$('#sps').on('change', function () {
    var pcs = $(this).val();
   
            $.ajax({
                url: "add_pool_mailserver_ajax.php",
                       type: "POST",
                       data: {
                        pcs: pcs
                       }

                    }).done(function(data){
                         
             $("#response4").html(data);
        });
  
        });
    </script>

<script>

$('#response4').on('change', function () {
    
    var ips = $(this).val();
 
            $.ajax({
                url: "add_ip_mailserver_ajax.php",
                       type: "POST",
                       data: {
                        ips: ips
                       }

                    }).done(function(data){
                         
             $("#ipaddress").html(data);
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

   
    if($_GET["R_alert"]=="true") {
        
        jQuery('#Record_already_exist').show();
   
 
 
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


</body>

</html>