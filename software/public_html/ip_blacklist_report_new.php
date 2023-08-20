<?php

ob_start();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['IPBR'])) {
    if ($_SESSION['IPBR'] == "NO") {

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

    <!-- Page Loader
    <div class="page-loader-wrapper">
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
                            <h2>IP Blacklist Report</h2>
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
                    <div class="col-lg-11 col-md-12 col-sm-12">
                        <div class="card">

                            <div class="body">
                                <!-- <div id="wizard_horizontal"> -->
                                    

                                <!-- my code -->
                                <h2>
                                <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Service_Provider">Service Provider Wise Report</button> 
                                <button type="button" id="triggerMS" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Mailserver">Mail Server Wise Report</button>
                                <button type="button" id="triggerAG" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Agency_Wise">Agency Wise Report</button>
                                <button type="button" id="triggerOW" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Org_Wise">Organization Wise</button>
                                <button type="button" id="triggerIP" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="IP_Wise">IP Wise</button>
                                <!-- my code -->
                                </h2>

                                <br>
                                <div class="col-lg-12 col-md-12">
                                <!-- <h2>Add Organizational Unit</h2> -->
<section id="Service_Provider" data-status="Service_Provider">
                                <?php    $stmtsp = $conn->prepare("SELECT *  FROM service_providers");
                                   $stmtsp->execute();
                                   $sps = $stmtsp->fetchAll();
                                ?>
                                  <div class="col-lg-12 col-md-12">
                                    <div class="multiselect_div col-3">
                                        <select id="single-selection" onChange="changesp();" name="single" class="multiselect multiselect-custom single">
                                        <option value="all" selected> All </option>
                                        <?php foreach ($sps as $sp) { ?>
                                       <option value="<?php echo $sp["sp_id"]; ?>"> <?php echo $sp["sp_name"]; ?> </option>

<?php

} ?>
                                        </select>
                                    </div> 
<br>

                                    <div id="response">
                                        <!--- display table-->
                                    </div>
                                       
</section>
 <section id="Mailserver" data-status="Mailserver">
                                                                   
                                   <?php    $stmtsp = $conn->prepare("SELECT * FROM mailservers");
                                   $stmtsp->execute();
                                   $sps = $stmtsp->fetchAll();
                                ?>
                                    <div class="multiselect_div col-3">
                                        <select id="single-selection" onChange="changems();" name="mswise" class="multiselect multiselect-custom mswise">
                                      
                                        <option value="all" selected> All </option>
                                        <?php foreach ($sps as $sp) { ?>

                                 <option value="<?php echo $sp["mailserverid"]; ?>"> <?php echo $sp["vmname"]; ?> </option>

<?php } ?>
                                        </select>
                                    </div> 

<br>

                                    <div id="response1">
                                        <!--- display table-->
                                    </div>
</section>
                                   
<section id="Agency_Wise" data-status="Agency_Wise">
                                <?php    $stmtsp = $conn->prepare("SELECT * FROM dnsbl");
                                   $stmtsp->execute();
                                   $sps = $stmtsp->fetchAll();
                                ?>
                                    <div class="multiselect_div col-3">
                                    <select id="single-selection" onChange="changerw();" name="agency" class="multiselect multiselect-custom agency">
                                        <option value="all" selected> All </option>
                                        <?php foreach ($sps as $sp) { ?>
                                        <option value="<?php echo $sp["dnsbl_id"]; ?>"> <?php echo $sp["dnsbl_name"]; ?> </option>
                                        <?php } ?>
                                    </select>
                                    </div>  
                                    <br>

                                    <div id="response2">  <!--- display table--> </div>
</section>
                                    
<section id="Org_Wise" data-status="Org_Wise">
<?php    $stmtsp = $conn->prepare("SELECT * FROM tbl_organizational_unit");
                                   $stmtsp->execute();
                                   $sps = $stmtsp->fetchAll();
                                ?>
                                    <div class="multiselect_div col-3">
                                    <select id="single-selection" onChange="changeow();" name="orgwise" class="multiselect multiselect-custom orgwise">
                                        <option value="all" selected> All </option>
                                        <?php foreach ($sps as $sp) { ?>
                                        <option value="<?php echo $sp["orgunit_id"]; ?>"> <?php echo $sp["orgunit_name"]; ?> </option>
                                        <?php } ?>
                                    </select>
                                    </div>  
                                    <br>

                                    <div id="response3">  <!--- display table--> </div>                                   
</section>

<section id="IP_Wise" data-status="IP_Wise">
<?php    $stmtsp = $conn->prepare("SELECT * FROM ipdetails");
                                   $stmtsp->execute();
                                   $sps = $stmtsp->fetchAll();
                                ?>
                                    <div class="multiselect_div col-3">
                                    <select id="single-selection" onChange="changeip();" name="ipwise" class="multiselect multiselect-custom ipwise">
                                        <option value="all" selected> All </option>
                                        <?php foreach ($sps as $sp) { ?>
                                        <option value="<?php echo $sp["ipdetailid"]; ?>"> <?php echo $sp["ipaddress"]; ?> </option>
                                        <?php } ?>
                                    </select>
                                    </div>  
                                    <br>

                                    <div id="response4">  <!--- display table--> </div>                                   
</section>

                            </div>  <!-- close col-12 -->
                                </div>  <!-- close body -->
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




<!-- Javascript -->


<!-- <script>
    $( document ).ready(function() {
       
            //var sp = document.getElementById("single-selection");
            var sp_id = "all";
           
          

            $.ajax({
                        url: "service_provider_ajax.php",
                        method: "POST",
                        data: {
                            sp_id:sp_id
                        }



                    }).done(function(data){
            $("#response").html(data);});

        });


        function changesp() {
            var sp = document.getElementById("single-selection");
            var sp_id = sp.value;
           
          

            $.ajax({
                        url: "service_provider_ajax.php",
                        method: "POST",
                        data: {
                            sp_id:sp_id
                        }



                    }).done(function(data){
            $("#response").html(data);});

        }
    
    </script> -->
 



<script src="assets/bundles/libscripts.bundle.js"></script>    
<script src="assets/bundles/vendorscripts.bundle.js"></script>

<script src="assets/bundles/datatablescripts.bundle.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
<script src="assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>




<script src="assets/bundles/mainscripts.bundle.js"></script>
<script src="assets/bundles/morrisscripts.bundle.js"></script>
<script src="assets/js/pages/tables/jquery-datatable.js"></script>
<script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> <!-- Bootstrap Colorpicker Js --> 
<script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js --> 
<script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
<script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js --> 
<script src="assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js --> 
<script src="assets/js/pages/forms/advanced-form-elements.js"></script>



    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

  
    <script src="assets/js/index.js"></script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
  
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

        var sp_id = "all";
            $.ajax({
                        url: "service_provider_ajax.php",
                        method: "POST",
                        data: {
                            sp_id:sp_id
                        }

                    }).done(function(data){
            $("#response").html(data);});
    });

    $('#triggerMS').on('click', function () {
        var ms_id = "all";
            $.ajax({
                url: "ip_blacklist_mailserver_ajax.php",
                       method: "POST",
                       data: {
                           ms_id:ms_id
                       }

                    }).done(function(data){
            $("#response1").html(data);});
  
        });
        $('#triggerAG').on('click', function () {
        var ms_id = "all";
            $.ajax({
                url: "ip_blacklist_agencywise_ajax.php",
                       method: "POST",
                       data: {
                           ms_id:ms_id
                       }

                    }).done(function(data){
            $("#response2").html(data);});
  
        });

        $('#triggerOW').on('click', function () {
        var ms_id = "all";
            $.ajax({
                url: "ip_blacklist_orgwise_ajax.php",
                       method: "POST",
                       data: {
                           ms_id:ms_id
                       }

                    }).done(function(data){
            $("#response3").html(data);});
  
        });

        $('#triggerIP').on('click', function () {
        var ms_id = "all";
            $.ajax({
                url: "ip_blacklist_ipwise_ajax.php",
                       method: "POST",
                       data: {
                           ms_id:ms_id
                       }

                    }).done(function(data){
            $("#response4").html(data);});
  
        });



</script>
<script>
   

        function changesp() {
           
            var sp = document.getElementsByClassName('single')[0];
          
            var sp_id = sp.value;
          
            $.ajax({
                        url: "service_provider_ajax.php",
                        method: "POST",
                        data: {
                            sp_id:sp_id
                        }



                    }).done(function(data){
            $("#response").html(data);});

        }

        function changems() {
           
           var ms = document.getElementsByClassName('mswise')[0];
        //  alert(document.getElementsByClassName('mswise')[0].value);
           var ms_id = ms.value;
        
           $.ajax({
                       url: "ip_blacklist_mailserver_ajax.php",
                       method: "POST",
                       data: {
                           ms_id:ms_id
                       }



                   }).done(function(data){
           $("#response1").html(data);});

       }
       function changerw() {
           
           var ag = document.getElementsByClassName('agency')[0];
        //    alert(document.getElementsByClassName('agency')[0].value);
           var ag_id = ag.value;
         
           $.ajax({
                       url: "ip_blacklist_agencywise_ajax.php",
                       method: "POST",
                       data: {
                        ms_id:ag_id
                       }



                   }).done(function(data){
           $("#response2").html(data);});

       }
       function changeow() {
           
           var ow = document.getElementsByClassName('orgwise')[0];
            // alert(document.getElementsByClassName('orgwise')[0].value);
           var ow_id = ow.value;
         
           $.ajax({
                       url: "ip_blacklist_orgwise_ajax.php",
                       method: "POST",
                       data: {
                        ms_id:ow_id
                       }



                   }).done(function(data){
           $("#response3").html(data);});

       }

       function changeip() {
           
           var ow = document.getElementsByClassName('ipwise')[0];
            // alert(document.getElementsByClassName('orgwise')[0].value);
           var ow_id = ow.value;
         
           $.ajax({
                       url: "ip_blacklist_ipwise_ajax.php",
                       method: "POST",
                       data: {
                        ms_id:ow_id
                       }



                   }).done(function(data){
           $("#response4").html(data);});

       }
    
    
    </script>

</body>
  






</html>