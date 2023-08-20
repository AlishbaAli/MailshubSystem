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

// if (isset($_SESSION['r_level'])) {
//     if ($_SESSION['r_level'] != "0") {

//         //User not logged in. Redirect them back to the login page.
//         header('Location: page-403.html');
//         exit;
//     }
// }
if (isset($_SESSION['PBU']))  {
    if ($_SESSION['PBU']=="NO")  {

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
                            <h2>Permanently Block Domain </h2>
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

                          
                              <br>
                              <br>
                              <br>
                              <section id="Request" data-status="Request">
                              <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">
                                                <form action="perm_block_url_db.php" method="post">

                                        
                                               
                                               <div  id="Email_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> URL already blocked!
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>

                                               <div  id="not_allowed_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                    Http protocol or any forward slashes are not allowed!<br>
                                                    Only domain name is allowed.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>





                                <div class="form-group ">
                                                        <label>URL *</label>
                                                        <input name="url" type="text" class="form-control" placeholder="Ex:example.com" required>

                                                    </div>

                                                    <div class="form-group ">
                                                        <label>Status</label>

                                                        <select name="status" class="form-control" required>

                                                            <option value="Active"> Active</option>
                                                            <option value="In Active"> In Active</option>

                                                        </select>


                                                    </div>

                                                    
                                                    <div class="form-group ">

                                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                            Add
                                                        </button>


                                                    </div>








                                                    <br>


                                                </form>
                                            </div>
                                        </div>
                                        <!----------table----------->

                              <div class="table-responsive">
    <?php
    $sql = "SELECT * FROM permanently_blocked_url";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    ?>
    <!-- <table class="table center-aligned-table" > -->
    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
        <thead>
            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                <th>URL</th>
                <th> Status </th>
                <th>Added By</th>
                <th> Added On </th>
                <th> Action </th>




            </tr>
        </thead>
        <tbody>

            <?php






            while ($row = $stmt->fetch()) {
                                             



            ?>
                <tr>
                    <td><?php echo $row["url"] . " "; ?></td>
                    <td><?php echo $row["status"] . " "; ?></td>
                    <td><?php
                    if($row["added_by"]!=NULL){
                      $AdminId = $_SESSION["AdminId"];
                        $stmtu= $conn->prepare("SELECT username FROM admin WHERE AdminId=:AdminId");
                        $stmtu->bindValue(':AdminId', $AdminId);
                        $stmtu->execute();
                        $username= $stmtu->fetch();
                        echo $username['username'];


                    }
                    
                    ?></td>

                
                    <td><?php echo $row["system_date"] . " "; ?></td>



                    <td><a class="btn btn-warning btn-sm" href="perm_block_url_edit.php?id=<?php echo $row["permanently_blocked_urlid"]; ?>">Edit</a></td>

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

   
    if($_GET["pbu"]=="true") {
        
        jQuery('#Email_exist_alert').show();
   
 
 
    }
    if($_GET["not_allwd"]=="true") {
        
        jQuery('#not_allowed_alert').show();
   
 
 
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