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
if (isset($_SESSION['ETE'])) {
    if ($_SESSION['ETE'] == "NO") {

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
                            <h2>  Edit Campaign Wise Embargo </h2>
                        </div>
                      
                    </div>
                </div>

                <!---Add code here-->
                <div class="row clearfix">
                    <div class="col-lg-5 col-md-5 col-sm-10">
                        <div class="card">
<?php
if(isset($_GET['id'])) {
    $id=trim($_GET['id']);
}

$day="SELECT * From embargotype Where embargotype_id=:id ";
$day=$conn->prepare($day);
$day->bindParam(':id',$id);
$day->execute(); 
$row=$day->fetch();

?>
                            <div class="body">
                                 <form method="POST" action="embargotype_edit_db.php">
                                            <div class="form-group">
                                                <label>Embargo Days</label>
                                                        <input type="number" step="1" min="10" max="365" value="<?php echo $row['allowed_days'] ?>"
                                                          id="allowed_days" name="allowed_days" class="form-control" disabled >
                                            </div>
                                            <div class="form-group ">
                                            <label>Status*</label>
                                                <select name="status" class="form-control" required>

                                                    <option value="Active" <?php if ($row['embargotype_status'] == "Active") {
                                                                                echo ' selected="selected"';
                                                                            } ?>>Active</option>
                                                    <option value="In Active" <?php if ($row['embargotype_status'] == "In Active") {
                                                                                    echo ' selected="selected"';
                                                                                } ?>>In Active</option>

                                                </select>
                                            </div>

                                            <input type="number" value="<?php echo $id; ?>" hidden
                                                          id="id" name="id" class="form-control" >

                                            <div class="form-group ">
                                                
                                                    <button type="submit" name="submit1" class="btn btn-primary waves-effect waves-light mr-1">
                                                        Update
                                                    </button>

                                               
                                            </div>
                                      
                                            
                                 </form>   

                                

                        
                                   

                                  

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