<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
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
                <div class="">
                    <div class="row">
                        <!-- <div class="col-lg-5 col-md-8 col-sm-12">
                            <h3>System Dashboard</h3>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div> -->
                    </div>
                </div>

                <!---Add code here-->
                <?php

                $MS = "SELECT Count(`mailserverid`) as totalms,
count(Case when vmstatus='In Active' then `mailserverid` end ) as Inactivems,
count(Case when vmstatus='Active' then `mailserverid` end ) as Activems,
count(Case when vmstatus='Blacklisted' then `mailserverid` end ) as Blackms
FROM `mailservers` WHERE 1";
                $MS = $conn->prepare($MS);
                $MS->execute();
                $ms = $MS->fetch();

                $total_ms = $ms['totalms'];
                $inactive_ms = $ms['Inactivems'];
                $active_ms = $ms['Activems'];
                $black_ms = $ms['Blackms'];

                ?>
                <div>
                    <h4 class="text-center">Mailserver Information</h4>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-sm-4">

                        <div class="card" style="border-color:black;">
                            <div class="header" style="cursor: pointer;" data-toggle="modal" data-target="#TotalMailservers">
                                <h2 class="text-center">Mailservers </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $total_ms; ?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">

                                    <div class="col-4 border-right border-bottom  ">
                                        <div id="Traffic1" class="carousel vert slide" style="cursor: pointer;" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning" data-toggle="modal" data-target="#InActiveMailservers">
                                                    <label class="mb-0">In Active </label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $inactive_ms; ?></h4>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" style="cursor: pointer;" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success" data-toggle="modal" data-target="#ActiveMailservers">
                                                    <label class="mb-0">Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $active_ms; ?> </h4>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-4 border-bottom  ">
                                        <div id="Traffic1" class="carousel vert slide" style="cursor: pointer;"  data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger" data-toggle="modal" data-target="#BlacklistedMailservers">
                                                    <label class="mb-0">Blacklisted </label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $black_ms; ?></h4>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php

                    $query = "SELECT vmname,vmstatus,added_by,vmdate,ipcount,blacklist,agency FROM mailservers 
inner  join (Select mailserverid,
                  count(distinct ipdetails.ipaddress) as ipcount, 
                  count(DISTINCT(case when ipstatus='BLACKLIST' then  ipdetails.ipaddress end ) ) as blacklist ,
                  group_concat( DISTINCT dnsbl_name,'|',dnsbl.dnsbl_id,'|',blacklist_score, '|',blacklist_color Separator ', ') as agency
           from ipdetails left join tbl_ipblacklist_log 
                          on ipdetails.ipdetailid=tbl_ipblacklist_log.ipdetailid 
                          left join dnsbl 
                          on tbl_ipblacklist_log.dnsbl_id =dnsbl.dnsbl_id 
            group by mailserverid) as ip 
on ip.mailserverid=mailservers.mailserverid";
                    $query = $conn->prepare($query);
                    $query->execute();
                    $rows = $query->fetchAll();

                    ?>
                    <div class="modal fade" id="TotalMailservers" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '80%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '80%';min-width: 50%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Total Mailservers</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> Mailserver </th>
                                                <th> Allocated IPs </th>
                                                <th> Blacklisted IPs </th>
                                                <th> Agency </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) { ?>
                                                <tr align="center">
                                                    <td><?php echo $row['vmname'] ?></td>
                                                    <td><?php echo $row['ipcount'] ?></td>
                                                    <td><?php echo $row['blacklist'] ?></td>
                                                    <td><?php

                                                        if ($row["agency"] != "") {
                                                            $dnsbl_ids = $row["agency"];
                                                            $dnsbl_arr = explode(",", $dnsbl_ids);
                                                            $res = "";
                                                            foreach ($dnsbl_arr as $dnsbl) {

                                                                $dnsbl_array = explode("|", $dnsbl);
                                                                $name = $dnsbl_array[0];
                                                                $id = $dnsbl_array[1];
                                                                $score = $dnsbl_array[2];
                                                                $color = $dnsbl_array[3];


                                                                $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                $stmtr->execute();
                                                                $rowr = $stmtr->fetch();

                                                                //  $res .= $rowr["dnsbl_name"] . ", ";

                                                                if ($color == "black") { ?>
                                                                    <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"] . " (score" . $score . ") " ?> </li>


                                                                <?php } else if ($color == "yellow") {
                                                                ?>
                                                                    <li style="list-style:none;"> <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ") " ?> </li>


                                                                <?php } else if ($color == "green") { ?>
                                                                    <li style="list-style:none;"> <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ") " ?> </li>

                                                                <?php   } else if ($color == "orange") { ?>
                                                                    <li style="list-style:none;"> <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ") " ?> </li>

                                                                <?php   } else if ($color == "red") { ?>
                                                                    <li style="list-style:none;"> <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ") " ?> </li>


                                                        <?php    }
                                                            }
                                                        }

                                                        ?>
                                                    </td>
                                                    <!-- <td><?php echo $row['agency'] ?></td> -->
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="ActiveMailservers" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 50%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Active Mailservers</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> Mailserver </th>
                                                <th> Allocated IPs </th>
                                                <th> Blacklisted IPs </th>
                                                <th> Agency </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if ($row['vmstatus'] == 'Active') { ?>
                                                    <tr align="center">
                                                        <td><?php echo $row['vmname'] ?></td>
                                                        <td><?php echo $row['ipcount'] ?></td>
                                                        <td><?php echo $row['blacklist'] ?></td>
                                                        <td><?php

                                                            if ($row["agency"] != "") {
                                                                $dnsbl_ids = $row["agency"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array = explode("|", $dnsbl);
                                                                    $name = $dnsbl_array[0];
                                                                    $id = $dnsbl_array[1];
                                                                    $score = $dnsbl_array[2];
                                                                    $color = $dnsbl_array[3];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                    //  $res .= $rowr["dnsbl_name"] . ", ";

                                                                    if ($color == "black") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $name . " (score" . $score . ")  " ?> </li>


                                                                    <?php } else if ($color == "yellow") {
                                                                    ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $name . " (score=" . $score . ")  " ?> </li>


                                                                    <?php } else if ($color == "green") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $name . " (score=" . $score . ")  " ?> </li>

                                                                    <?php   } else if ($color == "orange") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $name . " (score=" . $score . ")  " ?> </li>

                                                                    <?php   } else if ($color == "red") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $name . " (score=" . $score . ")  " ?> </li>


                                                            <?php    }
                                                                }
                                                            }

                                                            ?>
                                                        </td>

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="InActiveMailservers" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 50%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">In Active Mailservers</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> Mailserver </th>
                                                <th> Allocated IPs </th>
                                                <th> Blacklisted IPs </th>
                                                <th> Agency </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if ($row['vmstatus'] == 'In Active') {  ?>
                                                    <tr align="center">
                                                        <td><?php echo $row['vmname'] ?></td>
                                                        <td><?php echo $row['ipcount'] ?></td>
                                                        <td><?php echo $row['blacklist'] ?></td>
                                                        <td><?php

                                                            if ($row["agency"] != "") {
                                                                $dnsbl_ids = $row["agency"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array = explode("|", $dnsbl);
                                                                    $name = $dnsbl_array[0];
                                                                    $id = $dnsbl_array[1];
                                                                    $score = $dnsbl_array[2];
                                                                    $color = $dnsbl_array[3];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                    //  $res .= $rowr["dnsbl_name"] . ", ";

                                                                    if ($color == "black") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"] . " (score" . $score . ")  " ?> </li>


                                                                    <?php } else if ($color == "yellow") {
                                                                    ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>


                                                                    <?php } else if ($color == "green") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>

                                                                    <?php   } else if ($color == "orange") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>

                                                                    <?php   } else if ($color == "red") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>


                                                            <?php    }
                                                                }
                                                            }

                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="BlacklistedMailservers" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 50%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Blacklisted Mailservers</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> Mailserver </th>
                                                <th> Allocated IPs </th>
                                                <th> Blacklisted IPs </th>
                                                <th> Agency </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if ($row['vmstatus'] == 'Blacklisted') {  ?>
                                                    <tr align="center">
                                                        <td><?php echo $row['vmname'] ?></td>
                                                        <td><?php echo $row['ipcount'] ?></td>
                                                        <td><?php echo $row['blacklist'] ?></td>
                                                        <td><?php

                                                            if ($row["agency"] != "") {
                                                                $dnsbl_ids = $row["agency"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array = explode("|", $dnsbl);
                                                                    $name = $dnsbl_array[0];
                                                                    $id = $dnsbl_array[1];
                                                                    $score = $dnsbl_array[2];
                                                                    $color = $dnsbl_array[3];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                    //  $res .= $rowr["dnsbl_name"] . ", ";

                                                                    if ($color == "black") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"] . " (score" . $score . ")  " ?> </li>


                                                                    <?php } else if ($color == "yellow") {
                                                                    ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>


                                                                    <?php } else if ($color == "green") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>

                                                                    <?php   } else if ($color == "orange") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>

                                                                    <?php   } else if ($color == "red") { ?>
                                                                        <li style="list-style:none;"> <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"] . " (score=" . $score . ")  " ?> </li>


                                                            <?php    }
                                                                }
                                                            }

                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>


                    <?php

                    $MS = "SELECT  Count(distinct `mailservers`.`mailserverid`) as totalorg,
Count(distinct (Case when orgunit_id is not null then `mailservers`.`mailserverid` end )) as assign,
count(distinct(Case when orgunit_id is null  then `mailservers`.`mailserverid` end )) as notassign

FROM mailservers left join `mailserver-orgunit` on mailservers.mailserverid=`mailserver-orgunit`.mailserverid";
                    $MS = $conn->prepare($MS);
                    $MS->execute();
                    $ms = $MS->fetch();

                    $total_orgms = $ms['totalorg'];
                    $assign = $ms['assign'];
                    $notassign = $ms['notassign'];
                    //$susp_org=$ms['susporg'];
                    //$term_org=$ms['termorg'];

                    ?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header" style="cursor: pointer;" data-toggle="modal" data-target="#TotalMAs">
                                <h2 class="text-center">Mailservers Assignment
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $total_orgms; ?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">

                                    <div class="col-6  border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning" style="cursor: pointer;" data-toggle="modal" data-target="#Unassigned">
                                                    <label class="mb-0">Unassigned </label>
                                                    <h4 class="font-30 font-weight-bold   "><?php echo $notassign; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner ">
                                                <div class="carousel-item active text-success" style="cursor: pointer;" data-toggle="modal" data-target="#AssignMAs">
                                                    <label class="mb-0">Assigned </label>
                                                    <h4 class="font-30 font-weight-bold   "><?php echo $assign; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>

                    </div>

                    <?php
                    $query = "SELECT ms.vmname, ms.mailserverid,ms.ethernet_name,ms.mac_address, GROUP_CONCAT(DISTINCT om.orgunit_name SEPARATOR ',') AS org1 
FROM mailservers AS ms 
LEFT JOIN (SELECT o.orgunit_id, m.mailserverid ,orgunit_name from `mailserver-orgunit` AS m 
inner join tbl_organizational_unit as o on o.orgunit_id = m.orgunit_id ) as om 
ON ms.mailserverid = om.mailserverid GROUP BY ms.vmname";
                    $query = $conn->prepare($query);
                    $query->execute();
                    $rows = $query->fetchAll();

                    ?>
                    <div class="modal fade" id="TotalMAs" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '80%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '80%';min-width: 50%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Mailservers</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> Mailserver </th>
                                                <th> Ethernet </th>
                                                <th> MAC Address </th>
                                                <th> Organizations </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) { ?>
                                                <tr align="center">
                                                    <td><?php echo $row['vmname'] ?></td>
                                                    <td><?php echo $row['ethernet_name'] ?></td>
                                                    <td><?php echo $row['mac_address'] ?></td>
                                                    <td><?php echo $row['org1'] ?></td>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="AssignMAs" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 50%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Assigned Mailservers</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> Mailserver </th>
                                                <th> Ethernet </th>
                                                <th> MAC Address </th>
                                                <th> Organizations </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if (!empty($row['org1'])) { ?>
                                                    <tr align="center">
                                                        <td><?php echo $row['vmname'] ?></td>
                                                        <td><?php echo $row['ethernet_name'] ?></td>
                                                        <td><?php echo $row['mac_address'] ?></td>
                                                        <td><?php echo $row['org1'] ?></td>

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="Unassigned" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 40%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Unassigned Mailservers</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> Mailserver </th>
                                                <th> Ethernet </th>
                                                <th> MAC Address </th>
                                                <!-- <th> Organizations </th> -->

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if (empty($row['org1'])) { ?>
                                                    <tr align="center">
                                                        <td><?php echo $row['vmname'] ?></td>
                                                        <td><?php echo $row['ethernet_name'] ?></td>
                                                        <td><?php echo $row['mac_address'] ?></td>

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php include 'sys_dashboard_msload.php'; ?>
                </div>
                <div>
                    <h4 class=" col-12 text-center">IPs Information</h4>
                    <hr>
                </div>
                <div class="row">
                    <!-- ----------------------------------------------------------------------------------------- -->
                    <?php

                    $MS = "SELECT ipstatus, Count(`ipdetailid`) as totalip,
Count(Case when ipstatus='In Active' then `ipdetailid` end ) as Inactiveip,
count(Case when ipstatus='Active' then `ipdetailid` end ) as Activeip,
count(Case when ipstatus='BLACKLIST' then `ipdetailid` end ) as Blackip,
count(Case when ipstatus='WHITELIST' then `ipdetailid` end ) as whiteip
FROM `ipdetails` WHERE 1";
                    $MS = $conn->prepare($MS);
                    $MS->execute();
                    $ms = $MS->fetch();

                    $total_ip = $ms['totalip'];
                    $inactive_ip = $ms['Inactiveip'];
                    $active_ip = $ms['Activeip'];
                    $black_ip = $ms['Blackip'];
                    $white_ip = $ms['whiteip'];

                    ?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <!-- background:#cadadf ; -->
                            <div class="header"  style="cursor: pointer;" data-toggle="modal" data-target="#TotalIPs">
                                <h2 class="text-center"> IPs
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $total_ip; ?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning"  style="cursor: pointer;" data-toggle="modal" data-target="#ActiveIPs">
                                                    <label class="mb-0">In Active </label>
                                                    <h4 class="font-30 font-weight-bold   "><?php echo $inactive_ip; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner ">
                                                <div class="carousel-item active text-success"  style="cursor: pointer;" data-toggle="modal" data-target="#WhiteIPs">
                                                    <label class="mb-0">Whitelisted</label>
                                                    <h4 class="font-30 font-weight-bold  "><?php echo $white_ip; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner ">
                                                <div class="carousel-item active text-danger"  style="cursor: pointer;" data-toggle="modal" data-target="#BlacklistedIPs">
                                                    <label class="mb-0">Blacklisted</label>
                                                    <h4 class="font-30 font-weight-bold   "><?php echo $black_ip; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>

                    <?php

                    $query = "SELECT * FROM ipdetails 
inner join mailservers on mailservers.mailserverid = ipdetails.mailserverid 
left join ip_hostname on ip_hostname.ip_hostname_id = ipdetails.ip_hostname_id
left join ip_addresses on ip_addresses.ip_addresses_id = ip_hostname.ip_addresses_id
left join ip_pool on ip_pool.ip_pool_id = ip_addresses.ip_pool_id
left join service_providers on service_providers.sp_id=ip_pool.sp_id

left join  (SELECT mailserverid,GROUP_CONCAT(orgunit_name Separator ', ')as orgunit_name 
            FROM `mailserver-orgunit` 
            left join tbl_organizational_unit 
            on `mailserver-orgunit`.`orgunit_id`=tbl_organizational_unit.orgunit_id
            group by mailserverid) as org 
on org.mailserverid=mailservers.mailserverid

left join  (SELECT ipdetailid,GROUP_CONCAT(DISTINCT dnsbl.dnsbl_id,'|',dnsbl_name,'|',blacklist_date,'|',blacklist_score, '|',blacklist_color SEPARATOR ',') AS dnsbl_idd
            FROM `tbl_ipblacklist_log` 
            left join dnsbl 
            on `tbl_ipblacklist_log`.`dnsbl_id`=dnsbl.dnsbl_id
            where blacklist_duration is null
            group by ipdetailid) as dns
on dns.ipdetailid=ipdetails.ipdetailid";
                    $query = $conn->prepare($query);
                    $query->execute();
                    $rows = $query->fetchAll();

                    ?>
                    <div class="modal fade" id="TotalIPs" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '80%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 80%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Total IPs</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                <th> IP </th>
                                                <th> Service Provider </th>
                                                <th> Mailserver </th>
                                                <th> IP Status </th>
                                                <th> IP Score </th>
                                                <th> Organization</th>
                                                <th> Agencies </th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) { ?>
                                                <tr align="center">
                                                    <td><?php echo $row['ipaddress'] ?></td>
                                                    <!-- <td><?php echo $row['ipgateway'] ?></td> -->
                                                    <td><?php echo $row['sp_name'] ?></td>
                                                    <td><?php echo $row['vmname'] ?></td>
                                                    <td> <?php if ($row["ipstatus"] == "In Active") { ?>
                                                            <span class="badge badge-danger"><?php echo $row["ipstatus"] . " "; ?>
                                                            <?php } else if ($row["ipstatus"] == "Active") { ?>
                                                                <span class="badge badge-success"> <?php echo $row["ipstatus"] . " " ?>

                                                                <?php } else if ($row["ipstatus"] == "WHITELIST") { ?>
                                                                    <span class="badge badge-light"> <?php echo $row["ipstatus"] . " " ?>

                                                                    <?php } else if ($row["ipstatus"] == "BLACKLIST") { ?>
                                                                        <span class="badge badge-dark"> <?php echo $row["ipstatus"] . " " ?>

                                                                        <?php } ?>
                                                    </td>
                                                    <?php if ($row['ipblack_color'] == 'black') { ?>
                                                        <td><span class="badge badge-dark">
                                                             <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'yellow') { ?>
                                                        <td><span class="badge badge-warning" style="background:yellow; color:black"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?>  </td>

                                                    <?php } else if ($row['ipblack_color'] == 'green') { ?>
                                                        <td><span class="badge badge-success" style="background:green; color:white">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'red') { ?>
                                                        <td><span class="badge badge-danger" style="background:red; color:white"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'orange') { ?>
                                                        <td> <span class="badge badge-danger" style="background:#F28C28; color:black;border-color:#F28C28">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } ?>

                                                    <td><?php echo wordwrap($row['orgunit_name'],35,"<br>\n") ?></td>
                                                    <td><?php  
                                                            
                                                            if ($row["dnsbl_idd"] != "") {
                                                                $dnsbl_ids = $row["dnsbl_idd"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array= explode("|",$dnsbl);
                                                                    $id=$dnsbl_array[0];
                                                                    $date= explode(" ",$dnsbl_array[2]);
                                                                    $score= $dnsbl_array[3];
                                                                    $color= $dnsbl_array[4];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                  //  $res .= $rowr["dnsbl_name"] . ", ";
                                                                  
                                                                  if($color=="black"){?>
                                                                   <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                                     <?php } 
                                                                     else if($color=="yellow"){
                                                                         ?>
                                                                  <li style="list-style:none;">  <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>
                                                                          
                                                                    
                                                                <?php } else if($color=="green"){ ?>
                                                                    <li style="list-style:none;">  <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                             <?php   } 
                                                             else if($color=="orange"){ ?>
                                                                <li style="list-style:none;">  <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                         <?php   }
                                                         else if($color=="red"){ ?>
                                                            <li style="list-style:none;">  <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                     <?php    } 
                                                             
                                                            }
                                                                 
                                                                
                                                            } 
                                                            
                                                           ?></td>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="ActiveIPs" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 80%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Active IPs</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                            <th> IP </th>
                                                <th> Service Provider </th>
                                                <th> Mailserver </th>
                                                <th> IP Status </th>
                                                <th> IP Score </th>
                                                <th> Organization</th>
                                                <th> Agencies </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if ($row['ipstatus'] == 'Active') { ?>
                                                    <tr align="center">
                                                    <td><?php echo $row['ipaddress'] ?></td>
                                                    <!-- <td><?php echo $row['ipgateway'] ?></td> -->
                                                    <td><?php echo $row['sp_name'] ?></td>
                                                    <td><?php echo $row['vmname'] ?></td>
                                                    <td> <?php if ($row["ipstatus"] == "In Active") { ?>
                                                            <span class="badge badge-danger"><?php echo $row["ipstatus"] . " "; ?>
                                                            <?php } else if ($row["ipstatus"] == "Active") { ?>
                                                                <span class="badge badge-success"> <?php echo $row["ipstatus"] . " " ?>

                                                                <?php } else if ($row["ipstatus"] == "WHITELIST") { ?>
                                                                    <span class="badge badge-light"> <?php echo $row["ipstatus"] . " " ?>

                                                                    <?php } else if ($row["ipstatus"] == "BLACKLIST") { ?>
                                                                        <span class="badge badge-dark"> <?php echo $row["ipstatus"] . " " ?>

                                                                        <?php } ?>
                                                    </td>
                                                    <?php if ($row['ipblack_color'] == 'black') { ?>
                                                        <td><span class="badge badge-dark">
                                                             <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'yellow') { ?>
                                                        <td><span class="badge badge-warning" style="background:yellow; color:black"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?>  </td>

                                                    <?php } else if ($row['ipblack_color'] == 'green') { ?>
                                                        <td><span class="badge badge-success" style="background:green; color:white">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'red') { ?>
                                                        <td><span class="badge badge-danger" style="background:red; color:white"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'orange') { ?>
                                                        <td> <span class="badge badge-danger" style="background:#F28C28; color:black;border-color:#F28C28">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } ?>

                                                    <td><?php echo wordwrap($row['orgunit_name'],35,"<br>\n") ?></td>
                                                    <td><?php  
                                                            
                                                            if ($row["dnsbl_idd"] != "") {
                                                                $dnsbl_ids = $row["dnsbl_idd"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array= explode("|",$dnsbl);
                                                                    $id=$dnsbl_array[0];
                                                                    $date= explode(" ",$dnsbl_array[2]);
                                                                    $score= $dnsbl_array[3];
                                                                    $color= $dnsbl_array[4];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                  //  $res .= $rowr["dnsbl_name"] . ", ";
                                                                  
                                                                  if($color=="black"){?>
                                                                   <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                                     <?php } 
                                                                     else if($color=="yellow"){
                                                                         ?>
                                                                  <li style="list-style:none;">  <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>
                                                                          
                                                                    
                                                                <?php } else if($color=="green"){ ?>
                                                                    <li style="list-style:none;">  <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                             <?php   } 
                                                             else if($color=="orange"){ ?>
                                                                <li style="list-style:none;">  <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                         <?php   }
                                                         else if($color=="red"){ ?>
                                                            <li style="list-style:none;">  <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                     <?php    } 
                                                             
                                                            }
                                                                 
                                                                
                                                            } 
                                                            
                                                           ?></td>

                                                </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="WhiteIPs" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 80%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Whitelisted IPs</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                            <th> IP </th>
                                                <th> Service Provider </th>
                                                <th> Mailserver </th>
                                                <th> IP Status </th>
                                                <th> IP Score </th>
                                                <th> Organization</th>
                                                <th> Agencies </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if ($row['ipstatus'] == 'WHITELIST') {  ?>
                                                    <tr align="center">
                                                    <td><?php echo $row['ipaddress'] ?></td>
                                                    <!-- <td><?php echo $row['ipgateway'] ?></td> -->
                                                    <td><?php echo $row['sp_name'] ?></td>
                                                    <td><?php echo $row['vmname'] ?></td>
                                                    <td> <?php if ($row["ipstatus"] == "In Active") { ?>
                                                            <span class="badge badge-danger"><?php echo $row["ipstatus"] . " "; ?>
                                                            <?php } else if ($row["ipstatus"] == "Active") { ?>
                                                                <span class="badge badge-success"> <?php echo $row["ipstatus"] . " " ?>

                                                                <?php } else if ($row["ipstatus"] == "WHITELIST") { ?>
                                                                    <span class="badge badge-light"> <?php echo $row["ipstatus"] . " " ?>

                                                                    <?php } else if ($row["ipstatus"] == "BLACKLIST") { ?>
                                                                        <span class="badge badge-dark"> <?php echo $row["ipstatus"] . " " ?>

                                                                        <?php } ?>
                                                    </td>
                                                    <?php if ($row['ipblack_color'] == 'black') { ?>
                                                        <td><span class="badge badge-dark">
                                                             <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'yellow') { ?>
                                                        <td><span class="badge badge-warning" style="background:yellow; color:black"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?>  </td>

                                                    <?php } else if ($row['ipblack_color'] == 'green') { ?>
                                                        <td><span class="badge badge-success" style="background:green; color:white">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'red') { ?>
                                                        <td><span class="badge badge-danger" style="background:red; color:white"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'orange') { ?>
                                                        <td> <span class="badge badge-danger" style="background:#F28C28; color:black;border-color:#F28C28">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } ?>

                                                    <td><?php echo wordwrap($row['orgunit_name'],35,"<br>\n") ?></td>
                                                    <td><?php  
                                                            
                                                            if ($row["dnsbl_idd"] != "") {
                                                                $dnsbl_ids = $row["dnsbl_idd"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array= explode("|",$dnsbl);
                                                                    $id=$dnsbl_array[0];
                                                                    $date= explode(" ",$dnsbl_array[2]);
                                                                    $score= $dnsbl_array[3];
                                                                    $color= $dnsbl_array[4];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                  //  $res .= $rowr["dnsbl_name"] . ", ";
                                                                  
                                                                  if($color=="black"){?>
                                                                   <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                                     <?php } 
                                                                     else if($color=="yellow"){
                                                                         ?>
                                                                  <li style="list-style:none;">  <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>
                                                                          
                                                                    
                                                                <?php } else if($color=="green"){ ?>
                                                                    <li style="list-style:none;">  <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                             <?php   } 
                                                             else if($color=="orange"){ ?>
                                                                <li style="list-style:none;">  <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                         <?php   }
                                                         else if($color=="red"){ ?>
                                                            <li style="list-style:none;">  <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                     <?php    } 
                                                             
                                                            }
                                                                 
                                                                
                                                            } 
                                                            
                                                           ?></td>

                                                </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="BlacklistedIPs" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                        <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 80%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h4 align="center">Blacklisted IPs</h4>
                                    <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                        <thead class="text-center">
                                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                            <th> IP </th>
                                                <th> Service Provider </th>
                                                <th> Mailserver </th>
                                                <th> IP Status </th>
                                                <th> IP Score </th>
                                                <th> Organization</th>
                                                <th> Agencies </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row) {
                                                if ($row['ipstatus'] == 'BLACKLIST') {  ?>
                                                   <tr align="center">
                                                    <td><?php echo $row['ipaddress'] ?></td>
                                                    <!-- <td><?php echo $row['ipgateway'] ?></td> -->
                                                    <td><?php echo $row['sp_name'] ?></td>
                                                    <td><?php echo $row['vmname'] ?></td>
                                                    <td> <?php if ($row["ipstatus"] == "In Active") { ?>
                                                            <span class="badge badge-danger"><?php echo $row["ipstatus"] . " "; ?>
                                                            <?php } else if ($row["ipstatus"] == "Active") { ?>
                                                                <span class="badge badge-success"> <?php echo $row["ipstatus"] . " " ?>

                                                                <?php } else if ($row["ipstatus"] == "WHITELIST") { ?>
                                                                    <span class="badge badge-light"> <?php echo $row["ipstatus"] . " " ?>

                                                                    <?php } else if ($row["ipstatus"] == "BLACKLIST") { ?>
                                                                        <span class="badge badge-dark"> <?php echo $row["ipstatus"] . " " ?>

                                                                        <?php } ?>
                                                    </td>
                                                    <?php if ($row['ipblack_color'] == 'black') { ?>
                                                        <td><span class="badge badge-dark">
                                                             <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'yellow') { ?>
                                                        <td><span class="badge badge-warning" style="background:yellow; color:black"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?>  </td>

                                                    <?php } else if ($row['ipblack_color'] == 'green') { ?>
                                                        <td><span class="badge badge-success" style="background:green; color:white">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'red') { ?>
                                                        <td><span class="badge badge-danger" style="background:red; color:white"> 
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } else if ($row['ipblack_color'] == 'orange') { ?>
                                                        <td> <span class="badge badge-danger" style="background:#F28C28; color:black;border-color:#F28C28">
                                                        <?php echo $row["ipblack_score"] . " "; ?> </td>

                                                    <?php } ?>

                                                    <td><?php echo wordwrap($row['orgunit_name'],35,"<br>\n") ?></td>
                                                    <td><?php  
                                                            
                                                            if ($row["dnsbl_idd"] != "") {
                                                                $dnsbl_ids = $row["dnsbl_idd"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array= explode("|",$dnsbl);
                                                                    $id=$dnsbl_array[0];
                                                                    $date= explode(" ",$dnsbl_array[2]);
                                                                    $score= $dnsbl_array[3];
                                                                    $color= $dnsbl_array[4];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                  //  $res .= $rowr["dnsbl_name"] . ", ";
                                                                  
                                                                  if($color=="black"){?>
                                                                   <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                                     <?php } 
                                                                     else if($color=="yellow"){
                                                                         ?>
                                                                  <li style="list-style:none;">  <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>
                                                                          
                                                                    
                                                                <?php } else if($color=="green"){ ?>
                                                                    <li style="list-style:none;">  <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                             <?php   } 
                                                             else if($color=="orange"){ ?>
                                                                <li style="list-style:none;">  <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                         <?php   }
                                                         else if($color=="red"){ ?>
                                                            <li style="list-style:none;">  <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                     <?php    } 
                                                             
                                                            }
                                                                 
                                                                
                                                            } 
                                                            
                                                           ?></td>

                                                </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php include 'sys_dashboard_dnsbl.php'; ?>
                    <!-- ---------------------------------------------------------------------------------- -->

                    <!---Add code here-->




                </div>
                <div>
                    <h4 class=" col-12 text-center">Organizational Information</h4>
                    <hr>
                </div>
                <div class="row col-12">
                    <?php include 'sys_dashboard_org.php'; ?>
                    <?php include 'sys_dashboard_role.php'; ?>
                    <?php include 'sys_dashboard_user.php'; ?>
                </div>
                <div class="row col-12">
                    <?php include 'sys_dashboard_userlogin.php'; ?>
                </div>


                <div>
                    <h4 class="text-center">Campaigns Information</h4>
                    <hr>
                </div>
                <div class="row col-12">
                    <?php include 'sys_dashboard_camp.php'; ?>
                    <?php include 'sys_dashboard_rte.php'; ?>
                    <?php include 'sys_dashboard_camptype.php'; ?>
                  

                </div>


                <div class="row col-12">

                    <?php include 'sys_dashboard_unsub.php'; ?>
                    <?php include 'sys_dashboard_embargo.php'; ?>

                </div>

                <div>
                    <h4 class="text-center">Blocked URLs and Domains</h4>
                    <hr>
                </div>
                <div class="row col-12">
                    <?php include 'sys_dashboard_urlB.php'; ?>
                    <?php include 'sys_dashboard_domainB.php'; ?>
                    <?php include 'sys_dashboard_urlPB.php'; ?>
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

            <!-- ------------------- -->





            <script src="assets/js/pages/tables/jquery-datatable.js"></script>
            <script src="assets/bundles/datatablescripts.bundle.js"></script>


</body>

</html>




<!-- <div class="body table-responsive">
                                        <table class="table table-hover m-b-0">
                                            <thead>
                                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                                                    <th>Organziation</th>
                                                    <th>Mailservers</th>
                                                    <th>IPs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>

                                                    <td><h6 class="margin-0">Web</h6></td>
                                                    <td><h6 class="m-b-0">1</h6></td>
                                                    <td class="text-right">
                                                        <div class="text-success">
                                                            23 <i class="fa fa-long-arrow-up"></i>
                                                        </div>
                                                        <div class="text-muted">up</div>
                                                        <div class="text-danger">
                                                            9 <i class="fa fa-long-arrow-down"></i>
                                                        </div>
                                                        <div class="text-muted">down</div>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td> <h6 class="margin-0">Graphics</h6></td>
                                                    <td> <h6 class="m-b-0">1</h6></td>
                                                    <td class="text-right">
                                                        <div class="text-success">
                                                            23 <i class="fa fa-long-arrow-up"></i>
                                                        </div>
                                                        <div class="text-muted">up</div>
                                                        <div class="text-danger">
                                                            9 <i class="fa fa-long-arrow-down"></i>
                                                        </div>
                                                        <div class="text-muted">down</div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div> -->