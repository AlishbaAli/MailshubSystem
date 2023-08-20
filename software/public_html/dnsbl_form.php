<?php

ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ADNSBL'])) {
    if ($_SESSION['ADNSBL'] == "NO") {
  
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
                            <h2>Add DNS BL</h2>
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
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Validation type</h4>
                                <p class="card-title-desc">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p> -->
                              
                               <!-- MY CODE -->
                                               
                        <div  id="DNS_BL_Name_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all mr-2"></i> DNS BL already exists!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                            </button>
                         </div>


                                                


                                               <!-- MY CODE  -->






                                <form id="advanced-form" action="dnsbl_db.php" method="post">

                                    <div class="form-group ">
                                        <label>DNS BL Name*</label>
                                        <input type="text" id="text-input3" name="dnsbl_name" class="form-control" required>

                                    </div>
                                    <div class="form-group ">
                                        <label>Priority Color *</label>
                                        <select name="priority_color" onchange="displayScore()" id="priority_color" class="form-control" required>
                                            <option value=""> </option>
                                            <option value="red"> Red </option>
                                            <option value="orange"> Orange </option>
                                            <option value="yellow"> Yellow </option>
                                            <option value="black"> Black </option>

                                        </select>
                                    </div>




                                    <div class="form-group ">
                                        <label>Priority Score *</label>
                                        <input readonly type="text" name="priority_score" id="priority_score" class="form-control" required>

                                    </div>
                                    <div class="form-group ">
                                        <label>Status *</label>
                                        <select name="status" class="form-control" required>

                                            <option value="Active"> Active </option>
                                            <option value="In Active"> In Active </option>

                                        </select>
                                    </div>












                                    <br>

                                    <div class="form-group mb-0">
                                        <div>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                Add
                                            </button>
                                            <button type="reset" class="btn btn-secondary waves-effect">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- end row -->


                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->




    <!-- Javascript -->
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script>
        function displayScore() {
        
            var priority_color = document.getElementById("priority_color").value;
          

            $.ajax({
                    url: "ajax_score.php",
                    method: "POST",
                    data: {
                        priority_color: priority_color
                    },
                    dataType: "JSON",
                    success: function(data) {


                        $('#priority_score').val(data.priority_score);





                    }


                }

            )

        }
    </script>



    <script src="assets/vendor/jquery/jquery.js"></script>
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

   
    if($_GET["DBLexist"]=="true") {
        
        jQuery('#DNS_BL_Name_exist_alert').show();
   
 
 
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


<!-- my code -->
</body>

</html>