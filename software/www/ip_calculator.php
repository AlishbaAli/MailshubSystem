<?php

ob_start();
session_start();
//   error_reporting(E_ALL);
//   ini_set('display_errors', 1);
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
                            <h2>IP Range Calculator</h2>
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
                    <?php
                    if (isset($_POST["calculate"])) {


                        function ipRange($cidr)
                        {
                            $range = array();
                            $cidr = explode('/', $cidr);
                            $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
                            $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
                            return $range;
                        }

                        function ipAddresses($cidr, $ipgateway)
                        {
                            $range = array();
                            $ips = "";
                            $cidr = explode('/', $cidr);
                            $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
                            $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);

                            for ($ip = ip2long($range[0]) + 1; $ip < ip2long($range[1]); $ip++) {

                                if (long2ip($ip) == $ipgateway) {
                                    continue;
                                }


                                $ips .=  long2ip($ip) . ",";
                            }

                            $ips = substr_replace($ips, "", -1);

                            return $ips;
                        }

                        // function v4CIDRtoMask($cidr) {
                        //     $cidr = explode('/', $cidr);
                        //     return array($cidr[0], long2ip(-1 << (32 - (int)$cidr[1])));
                        // }

                        $cidr_input = !empty($_POST['cidr']) ? trim($_POST['cidr']) : null;
                        $ipgateway = !empty($_POST['ipgateway']) ? trim($_POST['ipgateway']) : null;
                        $serviceprovider = !empty($_POST['serviceprovider']) ? trim($_POST['serviceprovider']) : null;



                        //$cidr= '202.69.48.0/26';
                        $range = array();
                        $range = ipRange($cidr_input);



                        //find gateway bit

                        $networklastbit = explode(".", $range[0]);






                        $gatewaylastbit = explode(".", $ipgateway);
                        $gatewaybit = $gatewaylastbit[3] - $networklastbit[3];

                        //var_dump(ipRange($cidr_input));




                        //var_dump(v4CIDRtoMask($cidr));



                        $ips = ipAddresses($cidr_input, $ipgateway);

                        //echo $ips;

                        $res = explode(',', $ips);
                        $usable = sizeof($res);



                        $total = $usable + 3;




                        $cidr = explode('/', $cidr_input);

                        $subnet = long2ip(-1 << (32 - (int)$cidr[1]));
                        $mask_bits = $cidr[1];
                        $host_bits = 32 - $mask_bits;

                        $network_ip2long = ip2long($range[0]);
                        $broadcast_ip2long = ip2long($range[1]);

                        //verify CIDR

                        $twopowern = pow(2, $host_bits);

                        $networklastbitcheck = explode(".", $cidr[0]);


                        // echo $twopowern;
                        //  echo $networklastbitcheck[3];

                        //check validity of cidr

                        if (is_int($networklastbitcheck[3] / $twopowern) === false || $cidr[1] < 24 || $cidr[1] > 30) {

                            echo " <div  class='alert alert-danger alert-dismissible fade show' role='alert'>
    
    <i class='mdi mdi-check-all mr-2'></i> Invalid CIDR!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>×</span>
    </button>
    </div>";

                            die();
                        }

                        //test for already existing pool


                        //checking existence of new small chunks in existing pool
                        //checking existence of new big pool in existing small chunks
                        // if e(network) --- h(broadcast) is our range
                        //stop a(network) to c(broadcast)
                        //stop c(network) to  k(broadcast)
                        $stmt_chk = $conn->prepare("SELECT * FROM ip_pool WHERE
 (network_ip2long <= $network_ip2long AND broadcast_ip2long  >= $network_ip2long)
 OR (network_ip2long <= $broadcast_ip2long AND broadcast_ip2long  >= $broadcast_ip2long ) 
OR (network_ip2long <= $network_ip2long AND broadcast_ip2long >= $broadcast_ip2long)");
                        $stmt_chk->execute();

                        if ($stmt_chk->rowCount() > 0) {

                            echo " <div  class='alert alert-danger alert-dismissible fade show' role='alert'>
    
    <i class='mdi mdi-check-all mr-2'></i> Pool Already exists!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>×</span>
    </button>
    </div>";

                            die();
                        }
                    }

                    ?>

                    <div class="demo-masked-input">
                        <div style="width:900px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">


                            <form action="ip_calculator.php" method="post">

                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>CIDR</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                    </div>
                                                    <input name="cidr" type="text" class="form-control" placeholder="Ex: 255.255.255.255/26" required>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <?php $sql = "SELECT * FROM service_providers WHERE sp_status='Active'";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $serviceproviders = $stmt->fetchAll();
                                                ?>
                                                <b>Service Provider</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                    </div>
                                                    <select name="serviceprovider" class="form-control" required>

                                                        <?php foreach ($serviceproviders as $output) { ?>
                                                            <option value="<?php echo $output["sp_id"]; ?>"> <?php echo $output["sp_name"]; ?> </option>
                                                        <?php
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12">
                                                <b>IP Gateway</b>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                    </div>
                                                    <input name="ipgateway" type="text" class="form-control" placeholder="Ex: 255.255.255.255" required>
                                                </div>
                                            </div>


                                        </div>




                                        <br>
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3">

                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">

                                                    </div>
                                                    <button type="submit" name="calculate" class="btn btn-primary waves-effect waves-light mr-1">
                                                        Calculate
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


                <!------------2nd form---------------->

                <form action="ip_calculator_db.php" method="post">
                    <input hidden type="text" id="pool_array" name="pool_array">




                    <div class="table-responsive">
                        <?php
                        $sys = $conn->prepare("SELECT ms_email_prefix FROM system_setting");
                        $sys->execute();
                        $prefix = $sys->fetch();
                        $email_prefix = $prefix["ms_email_prefix"];

                        ?>
                        <!-- <table class="table center-aligned-table" > -->
                        <table class=" table display center-aligned-table table-bordered table-hover table-custom">
                            <thead>
                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                    <th>S.No</th>
                                    <th>Gateway Bit</th>
                                    <th>Network Address</th>
                                    <th>Mask Bits</th>
                                    <th>Host Bits</th>
                                    <th>Subnet Mask</th>
                                    <th>Broadcast Address</th>
                                    <th>Gateway Address</th>
                                    <th>Total Hosts</th>
                                    <th>Total Usable Hosts</th>
                                    <th>Service Provider</th>
                                    <th>IP Address</th>
                                    <th>Hostname</th>
                                    <th>Email Adress</th>
                                    <th>Included Hostname</th>
                                    <th>Included IP</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php




                                $ip_pool = array();

                                for ($i = 0; $i < $usable; $i++) {


                                    $ip_pool[$i]['ip_pool'] = $cidr_input;
                                    $ip_pool[$i]['network_ip2long'] = $network_ip2long;
                                    $ip_pool[$i]['broadcast_ip2long'] = $broadcast_ip2long;


                                ?>
                                    <tr id="<?php echo 'tr' . $i ?>">
                                        <td>
                                            <?php
                                            echo $i;
                                            $ip_pool[$i]['s.no'] = $i; ?>
                                        </td>
                                        <td><?php echo $gatewaybit;
                                            $ip_pool[$i]['gateway_bit'] = $gatewaybit;
                                            ?></td>
                                        <td><?php echo $range[0];
                                            $ip_pool[$i]['network_address'] = $range[0];
                                            ?></td>
                                        <td><?php echo  $cidr[1];

                                            $ip_pool[$i]['mask_bits'] = $cidr[1] ?> </td>
                                        <td><?php echo  $host_bits;
                                            $ip_pool[$i]['host_bits'] = $host_bits;
                                            ?></td>

                                        <td> <?php echo $subnet;
                                                $ip_pool[$i]['subnet_mask'] = $subnet;
                                                ?> </td>

                                        <td><?php echo $range[1];
                                            $ip_pool[$i]['broadcast_address'] = $range[1];
                                            ?></td>
                                        <td> <?php echo $ipgateway;
                                                $ip_pool[$i]['gateway_address'] = $ipgateway;
                                                ?> </td>
                                        <td> <?php echo $total;
                                                $ip_pool[$i]['total_hosts'] = $total;

                                                ?></td>
                                        <td> <?php echo $usable;
                                                $ip_pool[$i]['usable_hosts'] = $usable;


                                                ?> </td>
                                        <td> <?php

                                                $stmtsp = $conn->prepare("SELECT sp_name FROM service_providers WHERE sp_id= $serviceprovider");
                                                $stmtsp->execute();
                                                $sps = $stmtsp->fetch();
                                                echo $sps['sp_name'];
                                                $ip_pool[$i]['sp_id'] = $serviceprovider;


                                                ?> </td>
                                        <td> <?php echo $res[$i];
                                                $ip_pool[$i]['ip_address'] = $res[$i];

                                                ?> </td>
                                        <td id="<?php echo 'td' . $i ?>"><?php
                                                                        $hostname1 = shell_exec("dig +noall +answer -x $res[$i]");
                                                                        if ($hostname1 != NULL) {
                                                                            $hostname2 = explode('PTR', $hostname1);



                                                                            $hostname3 = substr_replace(trim($hostname2[1]), "", -1);




                                                                            $forwardentry = shell_exec("dig +short $hostname3");


                                                                            if (!empty($forwardentry)) {

                                                                                if (trim($forwardentry) == trim($res[$i])) {
                                                                                    $ip_pool[$i]['hostname'] = $hostname3;
                                                                                    echo $hostname3;
                                                                                }
                                                                            } else {
                                                                                $ip_pool[$i]['hostname'] = NULL;
                                                                            }
                                                                        } else {
                                                                            $ip_pool[$i]['hostname'] = NULL;
                                                                        }


                                                                        ?> </td>
                                        <td id="<?php echo "email" . $i ?>"> <?php

                                                                            $email_address = NULL;
                                                                            if ($ip_pool[$i]['hostname'] != NULL) {
                                                                                $email_address = $email_prefix . '@' . $hostname3;
                                                                                echo $email_address;
                                                                                $ip_pool[$i]['emailaddress'] = $email_address;
                                                                            } else {
                                                                                $ip_pool[$i]['emailaddress'] = NULL;
                                                                            }


                                                                            ?> </td>

                                        <td>

                                            <input onchange=changehostcolor(<?php echo $i ?>) id="<?php echo "host" . $i ?>" type="checkbox" name="inc_hostname[]" value="<?php echo $i ?>" checked>

                                        </td>
                                        <td><input onchange=changeiprowcolor(<?php echo $i ?>) id="<?php echo "ip" . $i  ?>" type="checkbox" name="inc_ip[]" value="<?php echo $i ?>" checked></td>

                                    </tr>

                                <?php }


                                ?>

                            </tbody>
                        </table>








                        <!--------------table---------->

                    </div>
                    <input hidden id="triggerB" type="submit" value="Submit" class="btn btn-primary waves-effect waves-light mr-1">
                </form>

                <button onclick="AddtoPool()" value="submit" class="btn btn-primary waves-effect waves-light mr-1">Add to Pool</button>

                <!------------2nd form---------------->




            </div>
        </div>

    </div>

    <!-- Javascript -->




    <!-- Javascript -->

    <script>
        function changehostcolor(id) {

            checkBox = document.getElementById('host' + id);
            if (checkBox.checked == true) {


                document.getElementById('td' + id).style.backgroundColor = '#FFFFFF';
                document.getElementById('email' + id).style.backgroundColor = '#FFFFFF';



            }
            if (checkBox.checked == false) {


                document.getElementById('td' + id).style.backgroundColor = '#DFFF00';
                document.getElementById('email' + id).style.backgroundColor = '#DFFF00';
            }

        }

        function changeiprowcolor(id) {



            checkBox = document.getElementById('ip' + id);

            if (checkBox.checked == true) {
                document.getElementById('tr' + id).style.backgroundColor = '#FFFFFF';


            }
            if (checkBox.checked == false) {

                document.getElementById('tr' + id).style.backgroundColor = '#DFFF00';
            }

        }



        function AddtoPool() {

            var pool = JSON.stringify(<?php echo json_encode($ip_pool); ?>);
            document.getElementById("pool_array").value = pool;

            $("#triggerB").trigger("click");

        }
    </script>
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


    <script>
        jQuery(function() {
            var $_GET = {};

            document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function() {
                function decode(s) {
                    return decodeURIComponent(s.split("+").join(" "));
                }

                $_GET[decode(arguments[1])] = decode(arguments[2]);
            });


            if ($_GET["ip_alert"] == "true") {

                jQuery('#Record_already_exist').show();



            }




        });
    </script>
    <Script>
        $(document).ready(function() {
            $('table.display').DataTable({
                dom: "<'row'<'col-sm-12 col-md-6'> <'right aligned col-sm-12 col-md-6'f>r>" + "<'row'<'col-sm-12 col-md-12'>>" +
                    "t" +
                    "<'row'<'col-sm-12 col-md-5'i><'right aligned col-sm-12 col-md-7'>>",
                buttons: [
                    'copy', 'csv', 'pdf', 'print'
                ],
                "pageLength": 256
            });
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