<?php
ob_start();
session_start();
//  error_reporting(E_ALL);
//  ini_set('display_errors', 1);


include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['CD'])) {
    if ($_SESSION['CD'] == "NO") {

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
        <?php include 'include/nav_bar.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->
      
       


        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <!-- <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Dashboard</h2>
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
                <button onclick="topFunction()" id="myBtn" class="btn btn-primary" title="Go to top">Top</button>

                <?php 
                 $admin_id = $_SESSION['AdminId'];
                $orgunit_id = " ";
                if (isset($_SESSION['orgunit_id'])) {
                    $orgunit_id = $_SESSION['orgunit_id'];
                    //echo $orgunit_id;
                } ?>

                <?php

                // Total Campaigns
                $newP = 'SELECT count(CampID) as TotalCamp from campaign 
                left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
                WHERE Camp_Status = "Inactive" and crtem_status != "In Active" and ou_status="Active"';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();
                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $newP .= "AND user_id= '$admin_id'";
                }

                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $newP .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($newP);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $totalcampaign = $result['TotalCamp'];
                ?>

                <?php //pending camps
                $pen = 'SELECT count(CampID) as PendingCamp from campaign 
                left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                WHERE (Camp_Status = "Pending Verification Alert" or Camp_Status = "Pending Verification by Admin")  
                and crtem_status != "In Active" and ou_status="Active"';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();

                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $pen .= "AND user_id= '$admin_id'";
                }
                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $pen .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($pen);
                // $stmt->bindValue(':AdminID', $admin_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $Pencampaign = $result['PendingCamp'];
                ?>

                <?php
                $cip = 'SELECT count(CampID) as TotalActiveCamp FROM `campaign`left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                WHERE Camp_Status = "Active" and crtem_status != "In Active" and ou_status="Active"';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();

                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $cip .= "AND user_id= '$admin_id'";
                }
                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $cip .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($cip);
                // $stmt->bindValue(':AdminID', $admin_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $TotalSucceedActivity = $result['TotalActiveCamp'];
                ?>
                <?php
                $ver = 'SELECT count(CampID) as verified from campaign left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                WHERE Camp_Status = "Verified" and crtem_status != "In Active"  and ou_status="Active" ';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id' ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();

                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $ver .= "AND user_id= '$admin_id'";
                }
                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $ver .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($ver);
                // $stmt->bindValue(':AdminID', $admin_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $verCamp = $result['verified'];
                ?>

                <!-- ------------------------------- -->
                <?php

                // Rejected Campaigns
                $newP = 'SELECT count(CampID) as Rejected from campaign 
                left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
                WHERE Camp_Status = "Rejected" and crtem_status != "In Active" and ou_status="Active"';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();
                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $newP .= "AND user_id= '$admin_id'";
                }

                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $newP .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($newP);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $rejectcampaign = $result['Rejected'];
                ?>
                <!-- ------------------------------------------ -->

                
                <!-- ------------------------------- -->
                <?php

                // Interupted Campaigns
                $newP = 'SELECT count(CampID) as interupt from campaign 
                left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
                WHERE ( Camp_Status = "Interuptted (Mail Server Unavailable)" or crtem_status = "In Active" ) and ou_status ="Active"';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();
                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $newP .= "AND user_id= '$admin_id'";
                }

                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $newP .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($newP);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $intcampaign = $result['interupt'];
                ?>
                <!-- ------------------------------------------ -->

                <!-- ------------------------------- -->
                <?php

                // Active/in progress Campaigns
                $newP = 'SELECT count(CampID) as active from campaign 
                left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
                WHERE Camp_Status = "Active" and crtem_status != "In Active" and ou_status ="Active"';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();
                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $newP .= "AND user_id= '$admin_id'";
                }

                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $newP .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($newP);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $activecampaign = $result['active'];
                ?>
                <!-- ------------------------------------------ -->

                 <!-- ------------------------------- -->
                 <?php

//Holded Campaigns
$newP = 'SELECT count(CampID) as hold from campaign 
left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
WHERE Camp_Status = "Stop" and crtem_status != "In Active"  and ou_status ="Active" ';

$u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role = $conn->prepare($u_role);
$user_role->execute();
$user_role = $user_role->fetch();
if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
    $newP .= "AND user_id= '$admin_id'";
}

if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
    $newP .= "AND orgunit_id= '$orgunit_id'";
}

$stmt = $conn->prepare($newP);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$holdcampaign = $result['hold'];
?>
<!-- ------------------------------------------ -->
                <!-- ------------------------------- -->
                <?php

                // completed Campaigns
                $newP = 'SELECT count(CampID) as complete from campaign 
                left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
                WHERE Camp_Status = "Completed" and ou_status ="Active" ';

                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                $user_role = $conn->prepare($u_role);
                $user_role->execute();
                $user_role = $user_role->fetch();
                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                    $newP .= "AND user_id= '$admin_id'";
                }

                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                    $newP .= "AND orgunit_id= '$orgunit_id'";
                }

                $stmt = $conn->prepare($newP);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $completecampaign = $result['complete'];
                ?>
                <!-- ------------------------------------------ -->
                  <!-- ------------------------------- -->
                  <?php

// Archieve Campaigns
$newP = 'SELECT count(CampID) as arch from campaign 
left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
WHERE Camp_Status = "Archive" and ou_status ="Active" ';

$u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role = $conn->prepare($u_role);
$user_role->execute();
$user_role = $user_role->fetch();
if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
    $newP .= "AND user_id= '$admin_id'";
}

if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
    $newP .= "AND orgunit_id= '$orgunit_id'";
}

$stmt = $conn->prepare($newP);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$arch = $result['arch'];
?>
<!-- ------------------------------------------ -->
 <!-- ------------------------------- -->
 <?php

// all Campaigns
$newP = 'SELECT count(CampID) as allcamp from campaign 
left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
WHERE ou_status ="Active" ';

$u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role = $conn->prepare($u_role);
$user_role->execute();
$user_role = $user_role->fetch();
if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
    $newP .= "AND user_id= '$admin_id'";
}

if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
    $newP .= "AND orgunit_id= '$orgunit_id'";
}

$stmt = $conn->prepare($newP);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$all = $result['allcamp'];
?>
<!-- ------------------------------------------ -->
  <!-- ------------------------------- -->
  <?php

// MS Interupted Campaigns
$newP = 'SELECT count(CampID) as interupt from campaign 
left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
WHERE Camp_Status = "Interuptted (Mail Server Unavailable)" and ou_status ="Active" ';

$u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role = $conn->prepare($u_role);
$user_role->execute();
$user_role = $user_role->fetch();
if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
    $newP .= "AND user_id= '$admin_id'";
}

if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
    $newP .= "AND orgunit_id= '$orgunit_id'";
}

$stmt = $conn->prepare($newP);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$MSintcampaign = $result['interupt'];
?>
<!-- ------------------------------------------ -->
  <!-- ------------------------------- -->
  <?php

//RTE Interupted Campaigns
$newP = 'SELECT count(CampID) as interupt from campaign 
left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  
WHERE  crtem_status = "In Active" and ou_status ="Active"';

$u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role = $conn->prepare($u_role);
$user_role->execute();
$user_role = $user_role->fetch();
if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
    $newP .= "AND user_id= '$admin_id'";
}

if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
    $newP .= "AND orgunit_id= '$orgunit_id'";
}

$stmt = $conn->prepare($newP);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$RTEintcampaign = $result['interupt'];
?>
<!-- ------------------------------------------ -->




                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div id="Summary1" class="row pb-4">

                        
                    <a href="#nc"  class="col-2 " >
                        <div class="card" style="background-color:#344453; color: white;"> 
                        <div class=" " style="background-color:#344453; color: white; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom"> New<br>Campaigns </span>
                                <span class="col-12  d-flex justify-content-center"><?php echo $totalcampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
                    </a>
                    <a href="#pv" class="col-2 " >
                        <div class="card" style="background-color:white; color: #344453;"> 
                        <div class=" " style="background-color:white; color: #344453; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom">  Pending<br>Verification </span>
                                <span class="col-12 d-flex justify-content-center"><?php echo $Pencampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
                    </a>
                    <a href="#rej" class="col-2 " >
                        <div class="card" style="background-color:#344453; color: white;"> 
                        <div class=" " style="background-color:#344453; color: white; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom">  Rejected<br>Campaigns </span>
                                <span class="col-12 d-flex justify-content-center  "><?php echo $rejectcampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
                    </a>
                    <a href="#ver" class="col-2 " >
                        <div class="card" style="background-color:white; color: #344453;"> 
                        <div class=" " style="background-color:white; color: #344453; padding:30px;">
                            <h6 class="" >
                                <span class="col-12   d-flex justify-content-center text-center border-bottom">Verified<br>Campaigns </span>
                                <span class="col-12  d-flex justify-content-center  "><?php echo $verCamp; ?> </span>
                            </h6>
                        </div>
                        </div>
                    </a>


                   
                    <a href="#cip" class="col-2 " >
                        <div class="card" style="background-color:#344453; color: white;"> 
                        <div class=" " style="background-color:#344453; color: white; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom">  Campaigns<br>In Progress </span>
                                <span class="col-12 d-flex justify-content-center"><?php echo $activecampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
                    </a>
                    <a href="#mu" class="col-2 " >
                        <div class="card" style="background-color:white; color: #344453;"> 
                        <div class=" " style="background-color:white; color: #344453; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom"> Total<br>Interrupted </span>
                                <span class="col-12  d-flex justify-content-center"><?php echo $intcampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
                    </a>
                   
                    <a href="#mu" class="col-2 " >
                        <div class="card" style="background-color:white; color: #344453;"> 
                        <div class=" " style="background-color:white; color: #344453; padding:30px;">
                            <h6 class="" >
                                <span class="col-12   d-flex justify-content-center text-center border-bottom">Mailserver<br>Unavailable</span>
                                <span class="col-12  d-flex justify-content-center  "><?php echo $MSintcampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
</a>
<a href="#rte" class="col-2 " >
                        <div class="card" style="background-color:#344453; color: white;"> 
                        <div class=" " style="background-color:#344453; color: white; padding:30px;">
                           <h6 class=" " > 
                                <span class="col-12 d-flex justify-content-center text-center border-bottom"> In Active<br>RTE </span>
                                 <span class="col-12 d-flex justify-content-center  "><?php echo $RTEintcampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
</a>

                   
<a href="#hc" class="col-2 " >
                        <div class="card" style="background-color:white; color: #344453;"> 
                        <div class=" " style="background-color:white; color: #344453; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom">  Holded<br>Campaigns </span>
                                <span class="col-12 d-flex justify-content-center"><?php echo $holdcampaign; ?> </span>
                            </h6>
                        </div>
                        </div>
</a>
<a href="#cc" class="col-2 " >
                        <div class="card" style="background-color:#344453; color: white;"> 
                        <div class=" " style="background-color:#344453; color: white; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom">  Completed<br>Campaigns </span>
                                <span class="col-12  d-flex justify-content-center"><?php echo  $completecampaign;?> </span>
                            </h6>
                        </div>
                        </div>
</a>
<a href="#ac" class="col-2 " >
                        <div class="card" style="background-color:white; color: #344453;"> 
                        <div class=" " style="background-color:white; color: #344453; padding:30px;">
                            <h6 class=" " >
                                <span class="col-12 d-flex justify-content-center text-center border-bottom"> Archieved<br>Campaigns  </span>
                                <span class="col-12 d-flex justify-content-center  "><?php echo $arch; ?> </span>
                            </h6>
                        </div>
                        </div>
</a>
<a href="#allc" class="col-2 " >
                        <div class="card" style="background-color:#344453;; color: white;"> 
                        <div class=" " style="background-color:#344453;; color: white; padding:30px;">
                            <h6 class="" >
                                <span class="col-12   d-flex justify-content-center text-center border-bottom">  All<br>Campaigns</span>
                                <span class="col-12  d-flex justify-content-center  "><?php echo $all; ?> </span>
                            </h6>
                        </div>
                        </div>
</a>


                

                    </div>
                </div>

               
                <div class="row">
                <div class="col-3 "></div>
                <div class="col-lg-6 col-md-12 col-sm-12 ">
                    <div id="Summary2" class="row pb-4">
              
                    </div>
                </div>
                <div class="col-3 "></div>
                </div>
 

                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">



                            <div class="body">
                                <!--  <div id="wizard_horizontal"> -->
                                <br id="allc"><br><br>
                                <b>
                                    <h3>CAMPAIGN FLOW</h3>
                                  
                                </b>

                                <h2>
                                    <a href="index.php"><button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="All">All</button> </a>
                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="New_Campaign">New</button>
                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Pending_verification">Pending verification</button>
                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Verified">Verified</button>
                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Rejected">Rejected</button>
                                    <button type="button" class="btn  btn-simple btn-filter2" style="background-color:#344453; color: white;" data-target="Campaign_In_Progress">In Progress</button>
                                    <button type="button" class="btn  btn-simple btn-filter2" style="background-color:#344453; color: white;" data-target="IntCamp">Interrupted</button>

                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Holded_Campaign">Holded</button>
                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Total_Campaign_Succeed">Completed</button>
                                    <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Archive">Archived</button>
                                </h2>


                                <section id="New_Campaign" data-status="New_Campaign">
                                <br id="nc"><br> <br> 
                                    <h4><b>New Campaigns</b></h4><br>
                                    <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                            <thead>
                                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                    <th>CAMPAIGN NAME</th>
                                                    <th>CAMPAIGN DATE</th>
                                                    <th>ACTIONS</th>
                                                    <!--  <th>ACTIVITY</th> -->

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $orgunit_id = "";
                                                if (isset($_SESSION['orgunit_id'])) {
                                                    $orgunit_id = $_SESSION['orgunit_id'];
                                                    //echo $orgunit_id;
                                                }

                                                $sql = "SELECT campaign.CampID, `CampName`,  Campaign_type.ctype_id,GROUP_CONCAT(component_name separator ', ') as comp_name,
                                                `CampDate`, `user_id`, `Camp_Status`, `rtemid` ,draft.subscription_draft,draft_subject
                                               FROM `campaign` left join Campaign_type on Campaign_type.ctype_id = campaign.ctype_id
                                               left join components_for_campaign_type on Campaign_type.ctype_id = components_for_campaign_type.ctype_id
                                               left join alert_components on alert_components.component_id = components_for_campaign_type.component_id
                                                left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id left join draft on draft.CampID=campaign.CampID
                                               Where Camp_Status = 'Inactive' and crtem_status != 'In Active' and ou_status='Active' and ctype_status='Active' and ou_status='Active'
                                               
                                               ";

                                                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                                                $user_role = $conn->prepare($u_role);
                                                $user_role->execute();
                                                $user_role = $user_role->fetch();

                                                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                                                    $sql .= "AND user_id= '$admin_id'";
                                                }

                                                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                                                    $sql .= "AND orgunit_id= '$orgunit_id'";
                                                }
                                                $sql.=" group by CampID";
                                                $stmt = $conn->prepare($sql);
                                                // $stmt->bindValue(':AdminID', $admin_id);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {

                                                    $resultcamp = $stmt->fetchAll();

                                                    foreach ($resultcamp as $row) {


                                                        $CampID = $row["CampID"];
                                                        $ctype_id = $row["ctype_id"];
                                                        //-----------------CHECK CTYPE
                                                        $ctype_article_list="No";
                                                       
                                                        $component_list = $row["comp_name"];
                                                        $str=explode(", ",$component_list);
                                                        //print_r($str);
                                                       
                                                         for($i=0 ; $i < count($str) ; $i++) { 

                                                            // code...
                                                            if ($str[$i]=='Article_List'){
                                                               $ctype_article_list='Yes';
                                                            }
                                                        }
                                                    
                                                               //---------------------
                                                        //---------------- DRAFT -----------------------
                                                    //     $sql_act = "SELECT *
                                                    // FROM activity
                                                    // Where CampID = :CampID ";

                                                    //     $stmt_act = $conn->prepare($sql_act);
                                                    //     $stmt_act->bindValue(':CampID',  $CampID);
                                                    //     // $stmt_act->bindValue(':AdminID', $admin_id);
                                                    //     $stmt_act->execute();
                                                    //     $result_act = $stmt_act->fetch();
                                                        // ---------------------------------------
                                                        // ---------------------------------------
                                                     
                                                        // $cmp_draft=html_entity_decode($cmp_draft);
                                                        ///////////

                                                        
                                                ?>
                                                        <tr>
                                                            <td><?php echo $row["CampName"]; ?></td>

                                                            <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>
                                                            <td>

                                                                <a target="_blank" href="edit_Campaign2.php?CampID=<?php  echo $row["CampID"]; ?>"> <button type="button" class="btn btn-warning" title="Edit">Edit Campaign
                                                                </button></a>  <!-- <i class="fa fa-edit"></i> -->
                                                                <a target="_blank" href="components_order.php?ctype_id=<?php  echo $row["ctype_id"]; ?>&CampID=<?php  echo $row["CampID"]; ?>"> <button type="button" class="btn btn-warning" title="Edit">Assign Component Order 
                                                                </button></a> 
                                                                <a target="_blank" href="viewDraft.php?ctype_id=<?php  echo $row["ctype_id"]; ?>&CampID=<?php  echo $row["CampID"]; ?>"> <button type="button" class="btn btn-info" title="Edit">View Alert 
                                                                </button></a> 
                                                               
                                                                <a href="deleteCampaign.php?CampID=<?php echo $row["CampID"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete">Delete Campaign
                                                                        <!-- <i class="fa fa-trash-o"></i> -->
                                                                    </button></a>

                                                              <?php 
                                                              $tot_req = "SELECT count(component_id) as tr from components_for_campaign_type where ctype_id= '$ctype_id' and requirement_status='Required'";
                                                              $tot_req = $conn->prepare($tot_req);
                                                              $tot_req->execute();
                                                              $tot_req=$tot_req->fetch();
                                                              $tot_req=$tot_req['tr'];

                                                              $req_in_cmp = "SELECT count(component_id) as rc from components_for_campaign_type 
                                                                            where ctype_id= '$ctype_id' and requirement_status='Required' and component_id 
                                                                            in (SELECT component_id FROM `Campaigns_component_order` WHERE CampID= '$CampID')";

                                                                $req_in_cmp = $conn->prepare($req_in_cmp);
                                                                $req_in_cmp->execute();
                                                                $req_in_cmp=$req_in_cmp->fetch();
                                                                $req_in_cmp=$req_in_cmp['rc'];

                                                              if ($tot_req==$req_in_cmp) { ?>
                                                              

                                                                    <a href='verifyAlert.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button' data-type="confirm" onclick='return sendalert();' class='btn btn-success'>Verify Alert</button></a>
                                                              <?php } ?>
                                                               
                                                            </td>
                                                           <?php }                    }

                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </section>

                               

                                <section id="Pending Verification" data-status="Pending_verification">
                                <br id="pv"><br> <br>
                                    <h4><b>Pending Verification</b></h4><br>
                                    <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                            <thead>
                                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                    <th>CAMPAIGN NAME</th>
                                                    <th>CAMPAIGN DATE</th>
                                                    <th>CAMPAIGN STATUS</th>
                                                    <th>TOTAL EMAILS</th>

                                                    <th>ACTIONS</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                          
                                                <?php $sql = "SELECT campaign.CampID, `CampName`,  GROUP_CONCAT(component_name SEPARATOR ',') as comp_name , Campaign_type.ctype_id,
                                                 `CampDate`, `user_id`, `Camp_Status`, `rtemid` ,draft.subscription_draft,draft_subject
                                    FROM `campaign` left join Campaign_type on Campaign_type.ctype_id = campaign.ctype_id  
                                    left join components_for_campaign_type on Campaign_type.ctype_id = components_for_campaign_type.ctype_id
                                    left join alert_components on alert_components.component_id = components_for_campaign_type.component_id
                                    left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id left join draft on draft.CampID=campaign.CampID
                                    Where (Camp_Status = 'Pending Verification by Admin' or Camp_Status= 'Pending Verification Alert' ) and crtem_status != 'In Active' 
                                    and ou_status='Active'";
                                                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                                                $user_role = $conn->prepare($u_role);
                                                $user_role->execute();
                                                $user_role = $user_role->fetch();

                                                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                                                    $sql .= "AND user_id= '$admin_id'";
                                                }

                                                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                                                    $sql .= "AND orgunit_id= '$orgunit_id'";
                                                }
                                                $sql.=" group by CampName";
                                                $stmt = $conn->prepare($sql);
                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {

                                                    $resultcamp = $stmt->fetchAll();

                                                    foreach ($resultcamp as $row) {

                                   //-------------------------------
 $CampID = $row['CampID'];     
 $component_list = $row["comp_name"];
 $str=explode(" ",$component_list);
 $ctype_article_list="";
 for($i=0 ; $i < count($str) ; $i++) { 

     // code...
     if ($str[$i]=='Article_List'){
         $ctype_article_list='Yes';
     }
 }           
//  $ctype_article_list=$row['ctype_article_list'] ;                           
$message_oaarticles="";




                                                ?>
                                                        <tr>
                                                            <td><?php echo $row["CampName"]; ?></td>

                                                            <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>

                                                            <td><?php echo $row["Camp_Status"]; ?></td>
                                                            <?php
                                                            $stmt = $conn->prepare("SELECT count(Email) as TotalEmail, user_id as AdminID FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID
                                            left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  WHERE campaign.CampID = :CampID AND ou_status='Active'");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail'];
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FFCC;" type='button'><?php echo $TotalEmail ?></button> </td>

                                                            <td>
                                                            <!-- <a target="_blank" href="viewDraft.php?ctype_id=<?php  echo $row["ctype_id"]; ?>&CampID=<?php  echo $row["CampID"]; ?>"> <button type="button" class="btn btn-warning" title="Edit">View Alert 
                                                                </button></a>  -->

                                                               

                                                                <a href='verifyAlert.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button' class='btn btn-info'>View Alert</button></a>
                                                            </td>

                                                            <!--  <?php $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID AND Status = 'Sent'");
                                                                    $stmt->bindValue(':CampID', $row["CampID"]);
                                                                    // $stmt->bindValue(':AdminID', $admin_id);
                                                                    $stmt->execute();
                                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                    $TotalEmail = $result['TotalEmail'];
                                                                    ?> -->
                                                            <!--  <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FF99;" type='button'><?php echo $TotalEmail ?></button> </td> -->



                                                            <!-- <?php
                                                                    $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors 
                                           INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                          AND Status = 'Not Sent'");
                                                                    $stmt->bindValue(':CampID', $row["CampID"]);
                                                                    // $stmt->bindValue(':AdminID', $admin_id);
                                                                    $stmt->execute();
                                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                    $TotalEmail = $result['TotalEmail'];

                                                                    ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#FF99FF;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                                <td> <a class="btn btn-primary btn-sm" href="holdCampaign.php?CampID=<?php echo $row["CampID"]; ?>">Hold Campaign</a></td> -->

                                                        </tr>
                                                <?php }
                                                }

                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                   <!-- <?php //foreach ($resultcamp as $row) { 
                                        //$CampID = $row['CampID'];         
                                        // $ctype_article_list=$row['ctype_article_list'] ;     ?>
                                    <div class="modal fade" id="exampleModalCenter<?php //echo $row['CampID']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" style="max-width: '100%';min-width: 80%; " role="document">
                                                                        <div class="modal-content" style="overflow:scroll;">

                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalCenterTitle"><?php //echo $row["CampName"]; ?> Draft </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                             <h5 class="form-header"> 
                                                                             <?php //include "viewDraft.php"; ?>
                                                                                <!-- <div id=""> <?php // echo $message_app; ?></div> -->
<!-- </h5>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div> <?php // } ?> -->
                                </section>

                                <!-- --------Rejected Camp Section ----------------------------
------------------------------------ -->

                                <section id="Rejected" data-status="Rejected">
                                <br id="rej"><br>
                                    <?php  include 'rej.php'; ?>
                                </section>
                                <!--  ----------------------------------------
                                
                                    ---------------------------------------- -->
                                   
                                <section id="Verified" data-status="Verified">
                                <br id="ver"><br>
                                    <?php include 'verify_index.php'; ?>
                                </section>

                                
                                <!-- <h2>Campaign In Progress</h2> -->
                                <!--  <h2><button type="button" class="btn  btn-simple btn-sm btn-default btn-filter2" data-target="Campaign_In_Progress">Campaign In Progress</button></h2> -->
                                <section id="Campaign In Progress" data-status="Campaign_In_Progress">
                                <br id="cip"><br> <br>
                                    <h4><b>Campaigns In Progress</b></h4>
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
                                    <div class="d-flex justify-content-center">
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9AAA; padding-left :25px;padding-right :5px;"></i> Embargo Email
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9CCC; padding-left :25px;padding-right :5px;"> </i> Domain Block
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9FFF; padding-left :25px; padding-right :5px;"> </i> unsubscriber Email
                                    </div>
                                    <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                            <thead>
                                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                    <th>CAMPAIGN NAME</th>
                                                    <th>CAMPAIGN DATE</th>
                                                    <th>TOTAL EMAILS</th>
                                                    <th>SENT EMAILS</th>
                                                    <th>PENDING EMAILS</th>
                                                    <th>REJECTED EMAILS</th>

                                                    <?php if (isset($_SESSION['HC'])) {
                                                        if ($_SESSION['HC'] == "YES") {
                                                            echo " <th>ACTIONS</th>";
                                                        }
                                                    } ?>



                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $sql = "SELECT `CampID`, `CampName`, `CampDate`, user_id as AdminID, `Camp_Status` ,mailserverid
                                                FROM `campaign` left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                                                Where Camp_Status = 'Active' and crtem_status != 'In Active' and ou_status='Active'";

                                                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                                                $user_role = $conn->prepare($u_role);
                                                $user_role->execute();
                                                $user_role = $user_role->fetch();

                                                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                                                    $sql .= "AND user_id= '$admin_id'";
                                                }

                                                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                                                    $sql .= "AND orgunit_id= '$orgunit_id'";
                                                }

                                                $stmt = $conn->prepare($sql);
                                                // $stmt->bindValue(':AdminID', $admin_id);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {

                                                    $result = $stmt->fetchAll();

                                                    foreach ($result as $row) {
                                                        $mid = $row['mailserverid']; //echo $mid;
                                                        $ms = "SELECT vmstatus from mailservers where mailserverid='$mid'";
                                                        $ms = $conn->prepare($ms);
                                                        $ms->execute();
                                                        $msrow = $ms->fetch();
                                                        $mstatus = $msrow['vmstatus'];
                                                        if ($mstatus == 'Blacklisted') {
                                                        }



                                                ?>
                                                        <tr>
                                                            <td><?php echo $row["CampName"]; ?></td>

                                                            <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>
                                                            <?php
                                                            $stmt = $conn->prepare('SELECT count(Email) as TotalEmail, user_id as AdminID FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                                            WHERE campaign.CampID = :CampID and ou_status ="Active"');
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            //$stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail'];
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FFCC;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                            <?php $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID  AND Status = 'Sent'");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            //$stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail'];
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FF99;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                            <?php
                                                            $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors 
                                           INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                          AND Status = 'Not Sent'");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            //$stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail'];

                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#FF99FF;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                            <!-- ---------------------------------------------------------------------------------------------------- -->

                                                            <?php
                                                            $stmt = $conn->prepare("SELECT count(case when  Status = 'Embargo lock' then Email end) as EmbargoEmail, 
                                                                 count(case when  Status = 'Domain lock' then Email end) as DBLEmail,
                                                                 count(case when  Status = 'Unsubscribed lock' then Email end) as UnsubEmail FROM campaingauthors 
                                            INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                           ");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            if ($stmt->rowCount() > 0) {
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $stmt->closeCursor();
                                                                $EmbargoEmail = $result['EmbargoEmail'];
                                                                $DBLEmail = $result['DBLEmail'];
                                                                $UnsubEmail = $result['UnsubEmail'];
                                                            } else {
                                                                $EmbargoEmail = '0';
                                                                $DBLEmail = '0';
                                                                $UnsubEmail = '0';
                                                            }
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#AA9AAA;" type='button'><?php echo $EmbargoEmail; ?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9CCC;" type='button'><?php echo $DBLEmail; ?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9FFF;" type='button'><?php echo $UnsubEmail; ?></button>
                                                            </td>
                                                            <!-- ------------------------------------------------------------------------------------------------------ -->

                                                            <?php if (isset($_SESSION['HC'])) {
                                                                if ($_SESSION['HC'] == "YES") {
                                                                    echo ' <td> <a class="btn btn-warning btn-sm" href="holdCampaign.php?CampID=' . $row["CampID"] . '">Hold Campaign</a></td> ';
                                                                }
                                                            } ?>


                                                        </tr>
                                                <?php }
                                                }

                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </section>

                              
                                <section id="IntCamp" data-status="IntCamp">

                                <br id="mu"><br>
                                    <?php include 'index_intruptCamp.php'; ?>
                                    <br id="rte"><br>

                                    <?php include 'index_inactive_rte_camp.php'; ?>
                                </section>

                                <!--  <h2>Holded Campaign</h2> -->
                                <!--   <h2><button type="button" class="btn  btn-simple btn-sm btn-default btn-filter2" data-target="Holded_Campaign">Holded Campaign</button></h2> -->
                               
                                 <section id="Holded Campaign" data-status="Holded_Campaign">
                                 <br id="hc"><br> <br>
                                    <h4><b>Holded Campaigns</b></h4>

                                    <div class="d-flex justify-content-center">
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9AAA; padding-left :25px;padding-right :5px;"></i> Embargo Email
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9CCC; padding-left :25px;padding-right :5px;"> </i> Domain Block
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9FFF; padding-left :25px; padding-right :5px;"> </i> unsubscriber Email
                                    </div>
                                    <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                            <thead>
                                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                    <th>CAMPAIGN NAME</th>
                                                    <th>CAMPAIGN DATE</th>
                                                    <th>TOTAL EMAILS</th>
                                                    <th>SENT EMAILS</th>
                                                    <th>PENDING EMAILS</th>
                                                    <th>REJECTED EMAILS</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php


                                                $sql = "SELECT `CampID`, `CampName`, `CampDate`, user_id as AdminID, `Camp_Status` 
                                            FROM `campaign` left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                                            Where Camp_Status = 'Stop' and ou_status='Active'";

                                                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                                                $user_role = $conn->prepare($u_role);
                                                $user_role->execute();
                                                $user_role = $user_role->fetch();

                                                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                                                    $sql .= "AND user_id= '$admin_id'";
                                                }

                                                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                                                    $sql .= "AND orgunit_id= '$orgunit_id'";
                                                }

                                                $stmt = $conn->prepare($sql);
                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {

                                                    $result = $stmt->fetchAll();
                                                    foreach ($result as $row) {
                                                ?>
                                                        <tr>


                                                            <td><?php echo $row["CampName"]; ?></td>

                                                            <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>
                                                            <?php
                                                            $stmt = $conn->prepare('SELECT count(Email) as TotalEmail, user_id as AdminID FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                                            WHERE campaign.CampID = :CampID  and ou_status="Active"');
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail'];
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FFCC;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                            <?php $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID  AND Status = 'Sent'");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail'];
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FF99;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                            <?php
                                                            $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors 
                                            INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                           AND Status = 'Not Sent'");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail'];

                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#FF99FF;" type='button'><?php echo $TotalEmail ?></button> </td>

                                                            <!-- ---------------------------------------------------------------------------------------------------- -->

                                                            <?php
                                                            $stmt = $conn->prepare("SELECT count(case when  Status = 'Embargo lock' then Email end) as EmbargoEmail, 
                                                                 count(case when  Status = 'Domain lock' then Email end) as DBLEmail,
                                                                 count(case when  Status = 'Unsubscribed lock' then Email end) as UnsubEmail FROM campaingauthors 
                                            INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                           ");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            if ($stmt->rowCount() > 0) {
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $stmt->closeCursor();
                                                                $EmbargoEmail = $result['EmbargoEmail'];
                                                                $DBLEmail = $result['DBLEmail'];
                                                                $UnsubEmail = $result['UnsubEmail'];
                                                            } else {
                                                                $EmbargoEmail = '0';
                                                                $DBLEmail = '0';
                                                                $UnsubEmail = '0';
                                                            }
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#AA9AAA;" type='button'><?php echo $EmbargoEmail; ?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9CCC;" type='button'><?php echo $DBLEmail; ?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9FFF;" type='button'><?php echo $UnsubEmail; ?></button>
                                                            </td>
                                                            <!-- ------------------------------------------------------------------------------------------------------ -->
                                                            <td>


                    <a class="btn btn-primary btn-sm" href="resumeCampaign.php?CampID=<?php echo $row["CampID"]; ?>">Resume Campaign</a>
                    <!-- <a class="btn btn-danger btn-sm" data-type="confirm" onclick='return ArchiveCamp();' 
                    href="permanent_holdCamp.php?CampID=<?php // echo $row["CampID"]; ?>">Move Permanently to Archive</a> -->

                    <div class="btn btn-danger btn-sm" data-toggle="modal" data-target="#MPA">
                        <span class="text-center">Move Permanently to Archive</span>
                    </div>
                <!-- MODEL -->
                <div class="modal fade" id="MPA" role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';">
                    <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 50%;" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <h6  style="color:red" align="center">Archived campaign cannot be reverted.</h6>
                                <h6 align="center">Do you still want to Archive Campaign Permanently?</h6>
                                <h6 align="center">Type Word "Permanently" in the Textbox.</h6>
                                <form align="center" action="permanent_holdCamp.php" method="get">
                                    <div class="row">
                                        <i class="col-4"></i>
                                        <input type="text" align="center" id="ok" required class="form-control col-4" name="ok">
                                        <input type="text" align="center" id="CampID" required hidden class="form-control col-4" name="CampID" value="<?php echo $row["CampID"]; ?>">
                                        <i class="col-4"></i>

                                    </div>
                                    <div class="row d-flex justify-content-center pt-2">

                                        <button type="submit" align="center" id="submit" name="submit" class="btn btn-primary text-center ">ok</button>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- MODEL -->
                                                            </td>


                                                        </tr>

                                                <?php }
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                               
                                <section id="Total Campaign Succeed" data-status="Total_Campaign_Succeed">
                                <br id="cc"><br> <br>
                                    <h4><b>Total Campaigns Completed</b></h4>

                                    <div class="d-flex justify-content-center">
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9AAA; padding-left :25px;padding-right :5px;"></i> Embargo Email
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9CCC; padding-left :25px;padding-right :5px;"> </i> Domain Block
                                        <i class="fas fa-square" style="font-size:15px; color: #AA9FFF; padding-left :25px; padding-right :5px;"> </i> unsubscriber Email
                                    </div>
                                    <div class="table-responsive">


                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                            <thead>

                                                <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                    <th>CAMPAIGN NAME</th>
                                                    <th>CAMPAIGN DATE</th>
                                                    <th>CREATED DATE</th>
                                                    <th>SENT DATE</th>
                                                    <th>TOTAL EMAILS</th>
                                                    <th>SENT EMAILS</th>
                                                    <th>PENDING EMAILS</th>
                                                    <th>REJECTED EMAILS</th>


                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                // and DATE(Camp_Send_Date) > '$list_date'
                                                $list_date = date('Y-m-d', strtotime("-60 days")); // echo $list_date;
                                                $sql = "SELECT `CampID`, `CampName`, `CampDate`, user_id as AdminID, `Camp_Status`, `Camp_Created_Date`, `Camp_Send_Date`  
										FROM `campaign`  left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
										Where Camp_Status = 'Completed'  and DATE(Camp_Send_Date) > '$list_date' and ou_status ='Active'
										";
                                                $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                                                $user_role = $conn->prepare($u_role);
                                                $user_role->execute();
                                                $user_role = $user_role->fetch();

                                                if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                                                    $sql .= "AND user_id= '$admin_id'";
                                                }

                                                if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                                                    $sql .= "AND orgunit_id= '$orgunit_id'";
                                                }
                                                $sql .= "ORDER BY CampID";

                                                $stmt = $conn->prepare($sql);
                                                // $stmt->bindValue(':AdminID', $admin_id);
                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {
                                                    $result = $stmt->fetchAll();
                                                    foreach ($result as $row) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $row["CampName"];
                                                                echo $row["CampID"]; ?></td>

                                                            <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>
                                                            <td><?php echo date("j M Y", strtotime($row['Camp_Created_Date'])); ?></td>
                                                            <td><?php echo date("j M Y", strtotime($row['Camp_Send_Date'])); ?></td>
                                                            <?php
                                                            $stmt = $conn->prepare('SELECT count(Email) as TotalEmail, AdminID FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID = :CampID ');
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            //$stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail']; ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FFCC;" type='button'><?php echo $TotalEmail; ?></button> </td>
                                                            <?php $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID AND Status = 'Sent'");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            //$stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail']; ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FF99;" type='button'><?php echo $TotalEmail; ?></button> </td>
                                                            <?php
                                                            $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors 
                                            INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                           AND Status = 'Not Sent'");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalEmail = $result['TotalEmail']; ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#FF99FF;" type='button'><?php echo $TotalEmail; ?></button> </td>

                                                            <!-- ---------------------------------------------------------------------------------------------------- -->

                                                            <?php
                                                            $stmt = $conn->prepare("SELECT count(case when  Status = 'Embargo lock' then Email end) as EmbargoEmail, 
                                                                 count(case when  Status = 'Domain lock' then Email end) as DBLEmail,
                                                                 count(case when  Status = 'Unsubscribed lock' then Email end) as UnsubEmail FROM campaingauthors 
                                            INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                           ");
                                                            $stmt->bindValue(':CampID', $row["CampID"]);
                                                            // $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            if ($stmt->rowCount() > 0) {
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $stmt->closeCursor();
                                                                $EmbargoEmail = $result['EmbargoEmail'];
                                                                $DBLEmail = $result['DBLEmail'];
                                                                $UnsubEmail = $result['UnsubEmail'];
                                                            } else {
                                                                $EmbargoEmail = '0';
                                                                $DBLEmail = '0';
                                                                $UnsubEmail = '0';
                                                            }
                                                            ?>
                                                            <td> <button class='mr-2 mb-2 btn ' style="background-color:#AA9AAA;" type='button'><?php echo $EmbargoEmail; ?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9CCC;" type='button'><?php echo $DBLEmail; ?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9FFF;" type='button'><?php echo $UnsubEmail; ?></button>
                                                            </td>
                                                            <!-- ------------------------------------------------------------------------------------------------------ -->

                                                        </tr>
                                                <?php }
                                                }

                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </section>
                                <!--  </div> -->
                                <!---- index archive problem-->
                                <br id="ac"><br>

                                <?php include 'index_archive_camp.php'; ?>

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
        function deleteclick() {
            return confirm("Do you want to Delete Campaign?")
        };

        function deletedraft() {
            return confirm("Do you want to Delete Campaign Draft?")
        };

        function sendalert() {
            return confirm("Do you want to Send Verify Alert?")
        }

        function ActivateCamp() {
            return confirm("Do you want to Activate Campaign?")
        }

        function ArchiveCamp() {
            return confirm("Do you want to Archive Campaign Permanently?")
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
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>

    <!-- <script src="assets/bundles/morrisscripts.bundle.js"></script> -->
    <script>
        $(document).ready(function() {
            $('.star').on('click', function() {
                $(this).toggleClass('star-checked');
            });

            $('.ckbox label').on('click', function() {
                $(this).parents('tr').toggleClass('selected');
            });

            $('.btn-filter').on('click', function() {
                var $target = $(this).data('target');
                if ($target != 'all') {
                    $('.table tr').css('display', 'none');
                    $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
                } else {
                    $('.table tr').css('display', 'none').fadeIn('slow');
                }
            });
            $('.btn-filter2').on('click', function() {
                var $target = $(this).data('target');
                if ($target != 'all') {
                    $('section').css('display', 'none');
                    $('section[data-status="' + $target + '"]').fadeIn('slow');
                }
            });
            // $("#triggerB").trigger("click");
//             $("input").change(function(){
//                alert("The text has been changed.");
// });
            $('#submit').prop('disabled', true);
                $('#ok').keyup(function() {
                if($(this).val() == 'Permanently') {
                $('#submit').prop('disabled', false);
        } else {  $('#submit').prop('disabled', true);}
     });
        });
    </script>
    <script>
//Get the button
var mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
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