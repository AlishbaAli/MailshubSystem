<?php

ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ET'])) {
    if ($_SESSION['ET'] == "NO") {

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
                            <h2>  Campaign Wise Embargo </h2>
                        </div>
                      <!--  <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div> -->
                    </div>
                </div>

                <!---Add code here-->
                <div class="row clearfix">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <div class="card">

                            <div class="body">
                                <!-- <div id="wizard_horizontal"> -->
                                    

                                <!-- my code -->
                                <h2>
                                <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Add_CWE">Add Campaign Wise Embargo</button> 
                                <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Assign">Assign Campaign Wise Embargo to Organization</button>

                                <!-- my code -->
                                </h2>

                                <br>
                                <br>
                                <br>
                                <!-- <h2>Add Organizational Unit</h2> -->
                                    <section id="Add_CWE" data-status="Add_CWE">
                                        <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">


                                            <!-- my code -->
                                                                       
                                           <div  id="Organization_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                           <i class="mdi mdi-check-all mr-2"></i> Organizaion already exists!
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">×</span>
                                           </button>
                                           </div>
                                             <!-- my code -->
                                                <form id="advanced-form" action="embargotype_db.php" method="post"  data-parsley-validate novalidate> 

                                                    <div class="form-group ">
                                                    <div class="input-group  mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Allowed Days:</span>
                                                    </div>
                                                    <input type="text" name="allowed_days" step="1" min="10" max="365" class="form-control" placeholder="Enter number of days" aria-label="Small" aria-describedby="inputGroup-sizing-sm" required data-parsley>
                                                </div>

                                                    </div>
                                                   

                                                    <br>

                                                    <div class="form-group mb-0">
                                                        <div>
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                                Add
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

                                                        <th>
                                                           Allowed Days
                                                        </th>
                                                        <th>
                                                         Status
                                                        </th>
                                                        <th>
                                                        Date
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>


                                                    </tr>

                                                </thead>
                                                <tbody class="text-center">
                                                    <tr>
                                                        <?php

                                                        $sql = "SELECT *
										FROM `embargotype` ";


                                                        $stmt = $conn->prepare($sql);
                                                        $result = $stmt->execute();

                                                        if ($stmt->rowCount() > 0) {
                                                            $result = $stmt->fetchAll();



                                                            foreach ($result as $row) {    ?>



                                                                <td><?php echo $row["allowed_days"] ?></td>
                                                           
                                                                <td>
                                                            <?php if($row["embargotype_status"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $row["embargotype_status"] . " ";?> 
                                                           <?php } else if($row["embargotype_status"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $row["embargotype_status"] . " "?>

                                                           <?php }
                                                            
                                                            ?></td>
                                                                <td><?php echo $row["system_date"] ?></td>
                                                            <td>    <a href="embargotype_edit.php?id=<?php echo $row["embargotype_id"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
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
                                    <!-- <h2>Mailservers For Organizational Unit </h2> -->
                                    <section id="Assign" data-status="Assign">

                                        <div class="table-responsive">

                                            <?php
                                            $sql = "SELECT o.orgunit_name, o.orgunit_id, GROUP_CONCAT(DISTINCT eo.embargotype_id SEPARATOR ',')
                                            AS embargotype_id FROM tbl_organizational_unit AS o LEFT JOIN `embargotype_org` AS eo ON o.orgunit_id = eo.orgunit_id where o.system_entityid='2' GROUP BY o.orgunit_name ";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                            <thead class="text-center">
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organziation Name</th>
                                                        <th>Allowed days</th>
                                                        <th> Action </th>




                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">

                                                    <?php
                                                    while ($row = $stmt->fetch()) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row["orgunit_name"] . " "; ?></td>



                                                            <td>

                                                                <?php
                                                                if ($row["embargotype_id"] != "") {
                                                                    $remail_ids = $row["embargotype_id"];
                                                                    $remail_ids_arr = explode(",", $remail_ids);
                                                                    $res = "";
                                                                    foreach ($remail_ids_arr as $id) {
                                                                        $stmtr = $conn->prepare("SELECT allowed_days FROM embargotype WHERE embargotype_id =$id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();

                                                                        $res .= $rowr["allowed_days"] . " ,";
                                                                    }
                                                                    $final = "";
                                                                    $res = substr_replace($res, "", -1);

                                                                    echo wordwrap($res, 50, "<br>\n");
                                                                }   ?>

                                                            </td>




                                                            <td><a class="btn btn-warning btn-sm" href="embargotype_edit_org.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>">Modify</a></td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>



                                            <!----------table----------->



                                    </section>

                                  

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
        function showHide() {


            var sys_id = document.getElementById("system_entityid").value;

            if (sys_id == 2) {
                document.getElementById('SS').style.display = 'block'
                // document.getElementById('CET').style.display = 'block'
            } else {
                document.getElementById('SS').style.display = 'none'
                // document.getElementById('CET').style.display = 'none'
            }
        }
    </script>
    
    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/parsleyjs/js/parsley.min.js"></script>

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
            return confirm("Do you want to Delete this?")
        }
    </script>

   <!-- my code -->
<script>
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   
    if($_GET["O_exist"]=="true") {
        
        jQuery('#Organization_exist_alert').show();
   
 
 
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


</html>