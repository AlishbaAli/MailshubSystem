<?php
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
                            <h2>Dashboard</h2>
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

             <?php  
             
  


date_default_timezone_set("Asia/Karachi");
$strStart = date("Y-m-d H:i:s"); 
		   
function microtime_float()
{
 list($usec, $sec) = explode(" ", microtime());
 return ((float)$usec + (float)$sec);
}

		   $cron="";
		   $dteStart = new DateTime($strStart);
          // echo "IP switching code start time";
           //echo 
           $time_start_ip_switching_execution= microtime_float();
          // echo "<br>"; 

           $cron.="IP switching code start time: $time_start_ip_switching_execution<br>";
   
   for($i=0 ;$i<=35555 ;$i++){
   $stmt= $conn->prepare("SELECT email, substring(email, locate('@',email)+1, length(email)-locate('@',email)) as domian_name,
    count(substring(email, locate('@',email)+1, length(email)-locate('@',email)))  FROM `campaingauthors`
     GROUP BY substring(email, locate('@',email)+1, length(email)-locate('@',email))");
     $stmt->execute();
   }

 
   //echo "IP switching code end time";
   //echo 
   $time_end_ip_switching_execution= microtime_float();
   //echo "<br>";
   //echo "total time in switching code<br>";
   //echo 
   $total_ip_switching_time = $time_end_ip_switching_execution - $time_start_ip_switching_execution;

   $cron.="IP switching code end time:$time_end_ip_switching_execution<br>total time in switching code:$total_ip_switching_time<br>";

   $dteEnd   = new DateTime($strEnd);
   $strEnd   =  date("Y-m-d H:i:s"); 
		   $dteDiff  = $dteStart->diff($dteEnd);


           
		$datedifference= explode("-",$dteDiff->format("%H-%I-%s"));
       // echo "<br>";
        //echo
         $datedifference[0]."Hours<br>";
        //echo
         $datedifference[1]."Minutes<br>";
        //echo 
        $datedifference[2]."Seconds<br>";

        $cron.="$datedifference[0] Hours<br>
        $datedifference[1] Minutes<br>
        $datedifference[2] Seconds<br>";


        echo $cron;
  



             
             ?>

                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->

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

</body>

</html>