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
if (isset($_SESSION['UP'])) {
	if ($_SESSION['UP'] == "NO") {
  
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

        <?php
        $user_id = $_SESSION['AdminId'];
        $stmt = $conn->prepare("SELECT tbl_organizational_unit.orgunit_name FROM `tbl_orgunit_user` INNER JOIN tbl_organizational_unit 
   ON `tbl_orgunit_user`.`user_id` = $user_id AND tbl_organizational_unit.orgunit_id = tbl_orgunit_user.orgunit_id AND ou_status='Active'");
        $stmt->execute();
        $row = $stmt->fetch();


        $stmt2 = $conn->prepare("SELECT * FROM tbl_user_activity inner join tbl_activity where tbl_user_activity.activity_id=tbl_activity.activity_id and tbl_user_activity.user_id=$user_id and tbl_user_activity.activity_id Not In 
    (SELECT tbl_role_prev_activity.activity_id FROM tbl_user_role_prev inner join tbl_role_privilege inner join tbl_role_prev_activity inner join tbl_activity where tbl_user_role_prev.role_prev_id=tbl_role_privilege.role_prev_id and tbl_role_privilege.role_prev_id=tbl_role_prev_activity.role_prev_id and tbl_role_prev_activity.activity_id=tbl_activity.activity_id and tbl_user_role_prev.user_id=$user_id )
    ");
        $stmt2->execute();

        $stmt3 = $conn->prepare("SELECT * FROM tbl_user_role_prev inner join tbl_role_privilege inner join tbl_role_prev_activity inner join tbl_activity where tbl_user_role_prev.role_prev_id=tbl_role_privilege.role_prev_id and tbl_role_privilege.role_prev_id=tbl_role_prev_activity.role_prev_id and tbl_role_prev_activity.activity_id=tbl_activity.activity_id and tbl_user_role_prev.user_id=$user_id ");
        $stmt3->execute();

        ?>

        <?php
        // query for Basic info 
        $user_id = $_SESSION['AdminId'];
        $stmt_a = $conn->prepare("SELECT * from admin WHERE AdminId =$user_id");
        $stmt_a->execute();
        $rowa = $stmt_a->fetch();

        ?>
        <!---Add code here-->


        <div id="main-content" class="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>User Profile</h2>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                 <li class="breadcrumb-item">Pages</li>
                                <li class="breadcrumb-item active">User Profile</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">

                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-6 ">
                                <div class="card profile-header">
                                    <div class="body align-center">
                                        <button id='pic_upload' class="btn btn-warning rounded-circle icon-camera float" style="position: relative; top: 115px ; left: 47px; "></button>
                                        <div class="profile-image">
                                            <img class="rounded-circle" src="user_images/<?php echo $rowa['image'];  ?>" onerror="this.onerror=null; this.src='user_images/avatar.jpg'" width="130" height="130" alt="">
                                        </div>

                                        <div style="padding-top: -40px;">

                                            <h4 class="m-b-0"><strong><?php echo $_SESSION['username'] ?></strong></h4>
                                            <span> <?php $email = $rowa['email'];
                                                    $org_name = $row['orgunit_name'];
                                                    echo $rowa['email'] . "<br>";
                                                    $e_status = $rowa['email_status'];
                                                    if ($rowa['email_status'] == 'Not Verified') {
                                                        // echo "<span style='color:#1183ca;'>Email not verified </span> <br>";
                                                        echo "<button class='btn btn-link' style='color:red;' >Email not verified. Click to Verify Email</button><br>";
                                                    }
                                                    if ($rowa['email_status'] == 'Pending') {
                                                        // echo "<span style='color:#1183ca;'>Email not verified </span> <br>";


                                                        echo "<span class='badge badge-warning'>Email Verification Pending <i class='fa fa-history'></i></span> ";
                                                    }
                                                    if ($rowa['email_status'] == 'Verified') {
                                                        // echo "<span style='color:#1183ca;'>Email not verified </span> <br>";
                                                        echo "<span class='badge badge-success'>Email Verified <i class='fa fa-check-circle'></i></span> ";
                                                    }


                                                    echo $row['orgunit_name'] . "<br>";
                                                    ?>

                                            </span>

                                        </div>

                                        <form method="post" hidden action="profile2.php" enctype="multipart/form-data" id="basic-form" novalidate>


                                            <input type="file" class="dropify" data-allowed-file-extensions="gif png jpg jpeg" onchange="loadFile(event)" name="imgupload" id="imgupload" style="display:none">
                                            <button type="submit" style="display:none" class="button" value="Upload" name="but_upload" id="but_upload"></button>
                                        </form>

                                        <?php
                                        if (isset($_POST["but_upload"])) {
                                            // code...



                                            $image = trim($_FILES["imgupload"]['name']);

                                            $date = date('Ymd');
                                            if (!empty($image)) {



                                                $PK = $_SESSION['AdminId'];
                                                $file = $image;
                                                $path = pathinfo($file);

                                                $ext = $path['extension'];
                                                $full_path = "user_images/" . "UP" . $PK . "-" . $date . "." . $ext;
                                                //to store in db
                                                $file_name = "UP" . $PK . "-" . $date . "." . $ext;

                                                move_uploaded_file($_FILES["imgupload"]["tmp_name"], $full_path);
                                                //move image path to db


                                                $update_query = "UPDATE admin SET image=:user_image WHERE AdminId= :user_id ";
                                                $update_stmt = $conn->prepare($update_query);
                                                $update_stmt->bindParam(":user_image", $file_name);
                                                $update_stmt->bindParam(":user_id", $PK);
                                                // $param_image = $full_path;
                                                // $param_user_id= $PK;
                                                $update_stmt->execute();

                                                //  $roww=$update_stmt->fetch();
                                            }
                                            //$update_stmt->execute();
                                            unset($_POST["but_upload"]);
                                            header('Location: profile2.php');
                                        } ?>
                                        <div> <span>
                                                <h6><?php

                                                    $rpt = "";
                                                    $array = [];
                                                    while ($row3 = $stmt3->fetch()) {
                                                        $res_level= $row3['restriction_level'];
                                                        if ($row3['role_prev_title'] != $rpt) {
                                                            echo " | " . $row3['role_prev_title'];
                                                            $rpt = $row3['role_prev_title'];
                                                        }
                                                    }
                                                    // $act=$row3['activity_name'];
                                                    //                                 $array=$array.' | '. $act.' ';}
                                                    //                                  while($row2=$stmt2->fetch())
                                                    //                                     { 
                                                    // $act=$row2['activity_name'];
                                                    //                                 $array=$array.' | '. $act.' ';}
                                                    //                                 $array=trim($array,',');
                                                    //                                 $arr=explode("|",$array);
                                                    //                                 $array=$arr;
                                                    ?>

                                                </h6>
                                            </span></div>

                                        <!--  <div class="align-center table border-top p-t-20 m-t-20 text-left">
                               
                               <?php for ($i = 1; $i < sizeof($array); $i = $i + 3) { ?>
                                    <div class="row align-center col-12">

                                        <?php if (isset($array[$i])) { ?>
                                        <div class="col-4 align-center">
                                            <?php echo $array[$i]; ?>
                                
                                 </div>
                             <?php }
                                        if (isset($array[$i + 1])) { ?>
                                 <div class="col-4 align-center">
                                   <?php echo $array[$i + 1]; ?>
                               
                                 </div>
                                  <?php }
                                        if (isset($array[$i + 2])) { ?>
                                 <div class="col-4 align-center"> <?php echo $array[$i + 2]; ?> </div>
                                  <?php } ?>
                                  </div>
                                  <?php  } ?>
                               
                            </div>
                             -->

                                    </div>
                                </div> <!-- card profile-header for profile pic and info end-->
                            </div><!-- div for 6 col end-->

                            <div class="col-6 ">
                                <div class="card profile-header">
                                    <div class="body ">


                                        <ul class="nav nav-tabs-new align-right float-right  ">

                                            <li class="nav-item toggle-settings "><a class="nav-link " data-toggle="modal" data-target=".bd-example-modal-lg" href="#Settings"><i class=" icon-settings"></i> Profile Settings</a></li>
                                        </ul>
                                        <!--   data-toggle="tab" -->


                                        <!--Basic Info Display only code-->
                                        <h5><b>Draft Information</b></h5>
                                        <table cellpadding="6px" class="table-striped">
                                            <tr>
                                                <td> First Name : </td>
                                                <td> <?php echo $rowa['fname'];  ?></td>
                                            </tr>

                                            <tr>
                                                <td> Last Name : </td>
                                                <td> <?php echo $rowa['Lastname'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Journal Title : </td>
                                                <td> <?php echo $rowa['Journal_title'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Role : </td>
                                                <td> <?php echo $rowa['Role'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Article Title : </td>
                                                <td> <?php echo $rowa['article_title'];  ?></td>
                                            </tr>
                                            <!-- <tr>
                           <td> Article Title : </td>
                            <td > <?php echo $rowa['article_title'];  ?></td> 
                        </tr>
                        <tr>
                           <td> EurekaSelect URL : </td>
                            <td > <?php echo $rowa['eurekaselect_url'];  ?></td> 
                        </tr> -->
                                            <tr>
                                                <td> Address Line 1 :</td>
                                                <td> <?php echo $rowa['Add1'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Address Line 2 : </td>
                                                <td> <?php echo $rowa['Add2'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Address Line 3 : </td>
                                                <td> <?php echo $rowa['Add3'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Address Line 4 : </td>
                                                <td> <?php echo $rowa['Add4'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Country : </td>
                                                <td> <?php echo $rowa['Country'];  ?></td>
                                            </tr>

                                        </table>

                                    </div>
                                </div> <!-- card profile-header for profile pic and info end-->
                            </div><!-- div for 6 col end-->
                        </div> <!-- row end for profile info -->

                        <div class="card">
                            <div class="header">
                                <h2><b>Campaign Info</b></h2>
                                <!--#4AACF4-->
                                <ul class="header-dropdown">
                                    <!-- <li> <a href="javascript:void(document.body.style.backgroundColor='#3b505c');" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i> </a> </li> -->
                                    <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                                    <!--  <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                                    <ul class="dropdown-menu dropdown-menu-right animated bounceIn">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another Action</a></li>
                                        <li><a href="javascript:void(0);">Something else</a></li>
                                    </ul>
                                </li> -->
                                </ul>
                            </div>
                            <div class="body ">
                                <?php $admin_id = $_SESSION['AdminId'] ?>
                                <!-- <div class="row clearfix">
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div id="Summary1" class="carousel slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="card text-center" style="background-color:#BDF3F5;">
                                                        <div class="body">
                                                            <?php
                                                            $stmt = $conn->prepare('SELECT count(CampID) as TotalCamp from campaign inner join tbl_orgunit_user 
                                                            on campaign.ou_id=tbl_orgunit_user.ou_id WHERE Camp_Status = "Inactive" AND user_id= :AdminID');
                                                            $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $totalcampaign = $result['TotalCamp'];
                                                            ?>
                                                            <h1 class="mt-4" style="color:#344453;">
                                                                <?php echo $totalcampaign; ?>
                                                            </h1>
                                                            <p style="color:#344453;">
                                                                New Campaign
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="card text-center" style="background-color:#344453;">
                                                        <div class="body">
                                                            <h1 class="mt-4" style="color:#BDF3F5;">
                                                                <?php echo $totalcampaign; ?>
                                                            </h1>
                                                            <p style="color:#BDF3F5;">
                                                                New Campaign
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div id="Summary2" class="carousel slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="card text-center" style="background-color:#EFEBF4;">
                                                        <div class="body">
                                                            <?php
                                                            $stmt = $conn->prepare('SELECT count(CampID) as TotalActiveCamp FROM `campaign` inner join tbl_orgunit_user 
                                                            on campaign.ou_id=tbl_orgunit_user.ou_id WHERE Camp_Status = "Active" AND user_id= :AdminID');
                                                            $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalSucceedActivity = $result['TotalActiveCamp'];
                                                            ?>
                                                            <h1 class="mt-4" style="color:#344453;">
                                                                <?php echo $TotalSucceedActivity; ?>
                                                            </h1>
                                                            <p style="color:#344453;">
                                                                Campaign In Progress
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="card text-center" style="background-color:#344453;">
                                                        <div class="body">
                                                            <h1 class="mt-4" style="color:#EFEBF4;">
                                                                <?php echo $TotalSucceedActivity; ?>
                                                            </h1>
                                                            <p style="color:#EFEBF4;">
                                                                Campaign In Progress
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div id="Summary2" class="carousel slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="card text-center" style="background-color:#FFD4C3;">
                                                        <div class="body">
                                                            <?php
                                                            $stmt = $conn->prepare('SELECT count(CampID) as TotalHoldCamp from campaign inner join tbl_orgunit_user 
                                                            on campaign.ou_id=tbl_orgunit_user.ou_id WHERE Camp_Status = "Stop"AND user_id= :AdminID');
                                                            $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalSucceedActivity = $result['TotalHoldCamp'];
                                                            ?>
                                                            <h1 class="mt-4" style="color:#344453;">
                                                                <?php echo $TotalSucceedActivity; ?>
                                                            </h1>
                                                            <p style="color:#344453;">
                                                                Holded Campaign
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="card text-center" style="background-color:#344453;">
                                                        <div class="body">
                                                            <h1 class="mt-4" style="color:#FFD4C3;">
                                                                <?php echo $TotalSucceedActivity; ?>
                                                            </h1>
                                                            <p style="color:#FFD4C3;">
                                                                Holded Campaign
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div id="Summary2" class="carousel slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="card  text-center" style="background-color:#ECEEEF;">
                                                        <div class="body">
                                                            <?php
                                                            $stmt = $conn->prepare('SELECT count(activity_succeed) as TotalSucceedActivity FROM `activity` WHERE activity_succeed = 1 AND AdminID= :AdminID');
                                                            $stmt->bindValue(':AdminID', $admin_id);
                                                            $stmt->execute();
                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            $TotalSucceedActivity = $result['TotalSucceedActivity'];
                                                            ?>
                                                            <h1 class="mt-4" style="color:#344453;">
                                                                <?php echo $TotalSucceedActivity; ?>
                                                            </h1>
                                                            <p style="color:#344453;">
                                                                Total Campaign Succeed
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="card text-center" style="background-color:#344453;">
                                                        <div class="body">
                                                            <h1 class="mt-4" style="color:#ECEEEF;">
                                                                <?php echo $TotalSucceedActivity; ?>
                                                            </h1>
                                                            <p style="color:#ECEEEF;">
                                                                Total Campaign Succeed
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div> -->
                                <?php
                                //---------------------------
                                $admin_id = $_SESSION['AdminId'];
                                $orgunit_id = "";
                                if (isset($_SESSION['orgunit_id'])) {
                                    $orgunit_id = $_SESSION['orgunit_id'];
                                    //echo $orgunit_id;
                                }

                                // $sql = "SELECT campaign.CampID, `CampName`, `CampDate`, `user_id`, `Camp_Status`, `rtemid` ,draft.subscription_draft,draft_subject
                                // FROM `campaign` left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id left join draft on draft.CampID=campaign.CampID
                                // Where Camp_Status = 'Inactive' and crtem_status != 'In Active' and ou_status='Active'";

                                //     $u_role = "SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
                                //     $user_role = $conn->prepare($u_role);
                                //     $user_role->execute();
                                //     $user_role = $user_role->fetch();

                                //     if ($user_role['role_prev_id'] != '1' && $user_role['role_prev_id'] != '2' && $user_role['role_prev_id'] != '8') {
                                //         $sql .= "AND user_id= '$admin_id'";
                                //     }

                                //     if (!empty(trim($orgunit_id)) && $orgunit_id != null) {
                                //         $sql .= "AND orgunit_id= '$orgunit_id'";
                                //     }
                                //     $stmt = $conn->prepare($sql);
                                //     // $stmt->bindValue(':AdminID', $admin_id);
                                //     $result = $stmt->execute();

                                //----------------------

                                $sql = "SELECT *, TIMESTAMPDIFF(DAY, Camp_Created_Date, Camp_Send_Date) as diff 
FROM campaign 
left join tbl_orgunit_user on  campaign.ou_id = tbl_orgunit_user.ou_id 
left join admin on tbl_orgunit_user.user_id = admin.AdminId 
left join reply_to_emails on campaign.rtemid=reply_to_emails.rtemid 
left join mailservers on campaign.mailserverid=mailservers.mailserverid 
left join products on campaign.productid = products.productid 

where 
Camp_Status != 'Completed' 
and crtem_status != 'In Active' 
and ou_status='Active'
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
$stmt = $conn->prepare($sql);
                                $sql = $conn->prepare($sql);
                                //$sql->bindParam(":AdminId", $_SESSION['AdminId']);
                                $sql->execute();

                                ?>
                                <!-- <div class="table-responsive"> -->
                                <table class="table table-responsive table-striped xl-pink">
                                    <thead>
                                        <tr>
                                            <!-- <th style="text-align: center;"><i class="btn-secondary icon-check" style="border-radius: 9px;">
                                        </i></th> -->
                                            <th>Campaign Name</th>
                                            <th>Campaign Creation Date</th>
                                            <th>Campaign Send Date</th>
                                            <th>Campaign Duration</th>
                                            <th>Campaign Type</th>
                                            <th>Campaign Status</th>
                                            <th>Campaign Objective</th>
                                            <th>Reply-to-Email</th>
                                            <!-- <th>Mail Server</th> -->
                                            <th>Campaign Catalogue Type</th>
                                            <!-- <th>Embargo Type</th> -->
                                            <!--  <th>Embargo Duration</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $sql->fetch()) {

                                            $draft = $conn->prepare("SELECT * from draft where CampID=:CampID");
                                            $draft->bindParam(':CampID', $row["CampID"]);
                                            $draft->execute();
                                            $draft_r = $draft->fetch();



                                        ?>
                                            <tr>
                                                <!-- <?php if (!empty($draft_r['subscription_draft']) && ($e_status == 'Verified')) { ?>
                                                    <td> <a id="<?php echo $row['CampID']; ?>" onclick="SendId(<?php echo $row['CampID']; ?>)" data-toggle="modal" data-target="#exampleModalCenter">
                                                            <button class="btn btn-outline-secondary">Check Alert </button> </a></td>




                                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Tell us you are not a robot!</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="post" action="Check_draft_alert.php" onsubmit="return submitUserForm();">
                                                                        <div class="g-recaptcha" data-sitekey="6LcJumMcAAAAAGnXEfApJKjq6W38czoFRTN7Hu9n" data-callback="verifyCaptcha"></div>
                                                                        <div id="g-recaptcha-error"></div>
                                                                        <input type="hidden" name="cid" id="cid">
                                                                        <br>

                                                                        <button type="submit" name="submit" value="Submit" class="btn btn-primary">Send</button>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </form>


                                                                </div>
                                                                <n!-- <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" value="Submit" class="btn btn-primary">Send</button>
                                        </div> --n>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <td> </td>
                                                <?php } ?> -->



                                                <td><?php echo $row['CampName']; ?></td>

                                                <td><?php $str1 = explode(" ", $row['Camp_Created_Date']);
                                                    echo $str1[0]; ?></td>

                                                <td><?php $str2 = explode(" ", $row['Camp_Send_Date']);
                                                    echo $str2[0]; ?></td>

                                                <td><?php
                                                    echo $row['diff']; ?></td>

                                                <td><?php echo $row['CampType']; ?></td>
                                                <td><?php echo $row['Camp_Status']; ?></td>
                                                <td><?php echo $row['CampFor']; ?></td>

                                                <td><?php echo $row['reply_to_email']; ?></td>
                                                <!-- <td><?php echo $row['vmname']; ?></td> -->
                                                <td><?php echo $row['product_name']; ?></td>
                                                <!-- <td><?php echo $row['embargo_type']; ?></td> -->
                                                <!-- <td><?php echo $row['campaign_embargo_days']; ?></td> -->
                                            </tr>
                                        <?php  } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div> <!-- col-lg-6 col-md-12 div for profile and chart end -->
                    <!-- </div> -->
                    <!-- row end -->
                    <!-- <div class="row clearfix"> -->
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title h4" id="myLargeModalLabel">Settings</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="col-lg-12 col-md-12">


                                        <div class="card">
                                            <div class="body">

                                                <form id="basic-form" action="profile2.php" method="post" novalidate>

                                                    <h6>Draft Information</h6>

                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">First Name :</span></span>
                                                                </div>
                                                                <input type="text" id='Fname' name='Fname' class="form-control" placeholder="First Name" value='<?php echo $rowa['fname'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Last Name:</span></span>
                                                                </div>
                                                                <input type="text" id='Lastname' name='Lastname' class="form-control" placeholder="Last Name" value='<?php echo $rowa['Lastname'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Journal Title:</span></span>
                                                                </div>
                                                                <input type="text" id='j_title' name='j_title' class="form-control" placeholder="Journal Title" value='<?php echo $rowa['Journal_title'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Role:</span></span>
                                                                </div>
                                                                <input type="text" id='Role' name='Role' class="form-control" placeholder="Role" value='<?php echo $rowa['Role'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Article Title:</span></span>
                                                                </div>
                                                                <input type="text" id='article' name='article' class="form-control" placeholder="Article Title" value='<?php echo $rowa['article_title'];  ?>'>
                                                            </div>

                                                            <!-- <div class="form-group input-group mb-3">   
         <div class="input-group-prepend">
            <span class="input-group-text "><span style="font-size:13px;">Article Title:</span></span>
         </div>                                                  
        <input type="text" id='Article' name='Article' class="form-control" placeholder="Article Title" value='<?php echo $rowa['article_title'];  ?>'>
        </div>

        <div class="form-group input-group mb-3">
             <div class="input-group-prepend">
            <span class="input-group-text "><span style="font-size:13px;">Eurekaselect URL:</span></span>
         </div>     
        <input type="text" id='URL' name='URL' class="form-control" placeholder="http://(EurekaSelect URL)" value='<?php echo $rowa['eurekaselect_url'];  ?>'>
        </div> -->

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Address Line 1:</span></span>
                                                                </div>
                                                                <input type="text" id='Add1' name='Add1' class="form-control" placeholder="Address Line 1" value='<?php echo $rowa['Add1'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Address Line 2:</span></span>
                                                                </div>
                                                                <input type="text" id='Add2' name='Add2' class="form-control" placeholder="Address Line 2" value='<?php echo $rowa['Add2'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Address Line 3:</span></span>
                                                                </div>
                                                                <input type="text" id='Add3' name='Add3' class="form-control" placeholder="Address Line 3" value='<?php echo $rowa['Add3'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Address Line 4:</span></span>
                                                                </div>
                                                                <input type="text" id='Add4' name='Add4' class="form-control" placeholder=" Enter Address Line 4" value='<?php echo $rowa['Add4'];  ?>'>
                                                            </div>

                                                            <div class="form-group input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text "><span style="font-size:13px;">Country:</span></span>
                                                                </div>
                                                                <input type="text" id='Country' name='Country' class="form-control" placeholder="Enter Your Country" value='<?php echo $rowa['Country'];  ?>'>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <button type="submit" id='update' name='update' class="btn btn-primary">Update Info</button> &nbsp;&nbsp;
                                                    <!--  <button type="button" class="btn btn-default">Cancel</button> -->
                                                </form>
                                            </div>
                                        </div>


                                        <div class="card">
                                            <div class="body">
                                                <!-- <form id="basic-form" action="profile2.php" method="post" novalidate>
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12">
                                                            <h6>Account Data</h6>
                                                            <div class="form-group"> <?php
                                                                                        echo '<input type="text" class="form-control" value="' . $_SESSION["username"] . '" disabled placeholder="Username">';
                                                                                        ?> </div>
                                                            <div class="form-group">
                                                                <?php
                                                                if($res_level==0){ 

                                                                echo '<input  type="email" id="email" name="email" class="form-control" value="' . $email . '" placeholder="Email">';
                                                                 } else {
                                                                echo '<input readonly type="email" id="email" name="email" class="form-control" value="' . $email . '" placeholder="Email">';
                                                                }
                                                                ?>
                                                            </div>
                                                          <?php  if($res_level==0){ ?>
                                                            <button type="submit" id='update_email' name='update_email' class="btn btn-primary">Update Email</button> &nbsp;&nbsp; 
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </form> -->
                                                <?php
                                                $stmt_email = $conn->prepare("SELECT * FROM `admin` INNER JOIN admin_email ON admin.AdminId=admin_email.AdminId WHERE 
                                                admin.AdminId=$user_id");
                                                $stmt_email->execute();
                                                // $emails=$stmt_email->fetchAll();

        ?>
                                                <div class="table-responsive">
      <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;text-align:center">
                            <th>Email</th>
                            <th> Status </th>
                            <th>Type</th>
                            <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                        <?php
                                  while ($rowe= $stmt_email->fetch()) { ?>
                            <tr>
                           
                            <td><?php echo $rowe['emails']; ?></td>
                            <td style="text-align:center">
                            <?php if($rowe["emails_status"]=="Not Verified") {?>    
                                                            <span class="badge badge-danger"><?php echo $rowe["emails_status"] . " ";?> 
                                                           <?php } else if($rowe["emails_status"]=="Verified"){ ?>
                                                            <span class="badge badge-success">  <?php echo $rowe["emails_status"] . " "?>

                                                           <?php }
                                                           else if($rowe["emails_status"]=="Pending"){ ?>
                                                            <span class="badge badge-warning">  <?php echo $rowe["emails_status"] . " "?>

                                                           <?php }
                                                            
                                                            ?>
                            
                            </td>
                            <td style="text-align:center"><?php echo $rowe['email_type']; ?></td>
                            <td style="text-align:center"><?php if($rowe['email_type']=='Alternate' && $rowe['emails_status']=='Verified'){ ?>
                               <a class="btn btn-danger btn-sm" href="remove_email.php?aeid=<?php echo $rowe["admin_email_id"]; ?>">Remove</a> 
                               <a class="btn btn-primary btn-sm" href="make_email_primary.php?aeid=<?php echo $rowe["admin_email_id"]; ?>">Make it Primary</a> 

                        <?php    }
                        
                        else if($rowe['email_type']=='Alternate' && $rowe['emails_status']=='Not Verified') { ?>
                           <a class="btn btn-danger btn-sm" href="remove_email.php?aeid=<?php echo $rowe["admin_email_id"]; ?>">Remove</a>   
                          

                           <a id="<?php echo $rowe['admin_email_id']; ?>" class="btn btn-success btn-sm" onclick="SendId2(<?php echo $rowe['admin_email_id']; ?>)" data-toggle="modal" data-target="#exampleModalCenter1"><i class="fa fa-envelope "></i> Verify Email</a>




<div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle1">Tell us you are not a robot!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="send_pemail.php" onsubmit="return submitUserForm();">
                    <div class="g-recaptcha" data-sitekey="6LcJumMcAAAAAGnXEfApJKjq6W38czoFRTN7Hu9n" data-callback="verifyCaptcha"></div>
                    <div id="g-recaptcha-error"></div>
                    <input type="hidden" name="uid" id="uid">
                    <br>

                    <button type="submit" name="submit" value="Submit" class="btn btn-primary">Send</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>


            </div>
        </div>
    </div>
</div>
                            
                           <?php } ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
      </table>
    

                                                </div>

                                                <!---------->
                                           
                                                <?php
                                                if (isset($_POST['update_email'])) {
                                                    //Retrieve the field values from our login form.
                                                    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;

                                                    $sql = "UPDATE admin
       SET email=:email,
email_status='Not Verified'

       WHERE AdminId = :username
       
       ";

                                                    //echo $sql."<br/>";
                                                    //die();

                                                    $stmt = $conn->prepare($sql);

                                                    //Bind our variables.
                                                    $stmt->bindValue(':username', $_SESSION['AdminId']);
                                                    $stmt->bindValue(':email', $email);



                                                    //Execute the statement and insert the new account.
                                                    $result = $stmt->execute();
                                                    header('Location: profile2.php');
                                                }

                                                ?>


                                                <!----------->
                                                <!---------------------------------------------->
                                                <!-- Edit info -->
                                                <!---------------------------------------------->
                                                <?php
                                                if (isset($_POST['update'])) {
                                                    //Retrieve the field values from our login form.
                                                    $Fname = !empty($_POST['Fname']) ? trim($_POST['Fname']) : null;

                                                    $Lastname = !empty($_POST['Lastname']) ? trim($_POST['Lastname']) : null;

                                                    $Journal_title = !empty($_POST['j_title']) ? trim($_POST['j_title']) : null;
                                                    $Role = !empty($_POST['Role']) ? trim($_POST['Role']) : null;
                                                    // $URL= !empty($_POST['URL']) ? trim($_POST['URL']) : null;
                                                    $Article = !empty($_POST['article']) ? trim($_POST['article']) : null;
                                                    // $Article= !empty($_POST['Article']) ? trim($_POST['Article']) : null;
                                                    $Add1 = !empty($_POST['Add1']) ? trim($_POST['Add1']) : null;
                                                    $Add2 = !empty($_POST['Add2']) ? trim($_POST['Add2']) : null;
                                                    $Add3 = !empty($_POST['Add3']) ? trim($_POST['Add3']) : null;
                                                    $Add4 = !empty($_POST['Add4']) ? trim($_POST['Add4']) : null;
                                                    $Country = !empty($_POST['Country']) ? trim($_POST['Country']) : null;





                                                    //Prepare our INSERT statement.
                                                    $sql = "UPDATE admin
       SET fname=:fname, Lastname=:Lastname,
       Journal_title=:Journal_title, Role=:Role, article_title=:article_title,
        Add1=:Add1, Add2=:Add2,
       Add3=:Add3, Add4=:Add4, Country=:Country

       WHERE AdminId = :username
       
       ";

                                                    //echo $sql."<br/>";
                                                    //die();

                                                    $stmt = $conn->prepare($sql);

                                                    //Bind our variables.
                                                    $stmt->bindValue(':username', $user_id);
                                                    $stmt->bindValue(':fname', $Fname);
                                                    $stmt->bindValue(':Lastname', $Lastname);
                                                    $stmt->bindValue(':Journal_title', $Journal_title);
                                                    $stmt->bindValue(':Role', $Role);
                                                    $stmt->bindValue(':article_title', $Article);
                                                    // $stmt->bindValue(':affliation', $affliation);
                                                    // $stmt->bindValue(':url', $URL);
                                                    $stmt->bindValue(':Add1', $Add1);
                                                    $stmt->bindValue(':Add2', $Add2);
                                                    $stmt->bindValue(':Add3', $Add3);
                                                    $stmt->bindValue(':Add4', $Add4);
                                                    $stmt->bindValue(':Country', $Country);



                                                    //Execute the statement and insert the new account.
                                                    $result = $stmt->execute();
                                                    header('Location: profile2.php');
                                                }


                                                ?>
                                                <!---------------------------------------------->
                                                <!-- Edit info code end  -->
                                                <!---------------------------------------------->
                                             

                                                <form id="advanced-form1" data-parsley-validate action="profile2.php" method="post" novalidate>
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12">
                                                        <hr style="width:100%">
                                                        <br>
                                                            <h6>Change Password</h6>

                                                            <div class="form-group">
                                                                <div class="input-group mb-3">
                                                                    <!-- <label for="currentpassword" > Current Password</label> -->
                                                                    <input type="password" class="form-control" id="currentpassword" placeholder="Enter current password" name="currentpassword" required aria-describedby="basic-addon1">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text " id="basic-addon1"> <i toggle="#currentpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i> </span>
                                                                    </div>


                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="input-group mb-3">
                                                                    <!-- <label for="newpassword"> New Password</label> -->
                                                                    <input required type="password" class="form-control" id="newpassword" placeholder="Enter new password" name="newpassword" aria-describedby="basic-addon2">

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text " id="basic-addon2"> <i toggle="#newpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i> </span>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="input-group mb-3">
                                                                    <!--  <label for="confirmpassword"> Confirm Password</label> -->
                                                                    <input required type="password" class="form-control" id="confirmpassword" placeholder="Confirm new password" name="confirmpassword" aria-describedby="basic-addon3">

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text " id="basic-addon3"> <i toggle="#confirmpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i> </span>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <button class="btn btn-primary " id="submit" type="submit" name="submit" value="true">Change Password</button>

                                                            <!--   data-toggle="modal" data-target=".bd-example-modal-sm" -->
                                                        </div>



                                                    </div>
                                                </form>

                                                <!---------------------------------------------->
                                                <!-- change password -->
                                                <!---------------------------------------------->
                                                <?php
                                                if (isset($_POST['submit'])) {
                                                    //Retrieve the field values from our login form.
                                                    $currentpassword = !empty($_POST['currentpassword']) ? trim($_POST['currentpassword']) : null;

                                                    $newpassword = !empty($_POST['newpassword']) ? trim($_POST['newpassword']) : null;

                                                    $confirmpassword = !empty($_POST['confirmpassword']) ? trim($_POST['confirmpassword']) : null;

                                                    $passwordHash1 = password_hash($currentpassword, PASSWORD_BCRYPT, array("cost" => 12));


                                                    //Retrieve the account information for the given username.
                                                    $sql = "SELECT `AdminId`, `username`, `password`, `email`,`image`
                                                FROM `admin`
                                                WHERE AdminId = :username
                                               
                                               ";

                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->bindValue(':username', $_SESSION['AdminId']);


                                                    $stmt->execute();

                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                    $email = $row["email"];
                                                    $image = $row["image"];
                                                    $validPassword = password_verify($currentpassword, $row['password']);

                                                    //If $validPassword is TRUE
                                                    if ($validPassword === true)
                                                    //if ($currentpassword = $row["password"])
                                                    {
                                                        //Provide the subscriber with a login session.
                                                        if ($newpassword == $confirmpassword) {
                                                            $passwordHash = password_hash($newpassword, PASSWORD_BCRYPT, array("cost" => 12));

                                                            //Prepare our INSERT statement.
                                                            $sql = "UPDATE admin
       SET password= :passwordHash
       WHERE AdminId = :username
       AND email = :email
       ";

                                                            //echo $sql."<br/>";
                                                            //die();

                                                            $stmt = $conn->prepare($sql);

                                                            //Bind our variables.
                                                            $stmt->bindValue(':username', $_SESSION['AdminId']);
                                                            $stmt->bindValue(':passwordHash', $passwordHash);
                                                            $stmt->bindValue(':email', $email);



                                                            //Execute the statement and insert the new account.
                                                            $result = $stmt->execute();
                                                            header('Location: logout.php');
                                                        } else {

                                                            echo "<p><span style='color: red;'><b>New password and Confirm Password doesn't match</b>.</span></p>";
                                                        }
                                                    } else {
                                                        echo "<p><span style='color: red;'><b>Current Password Incorrect</b>.</span></p>";
                                                    };
                                                } ?>
                                                <!---------------------------------------------->
                                                <!-- change password code end  -->
                                                <!---------------------------------------------->
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---------------- -->
                </div>
                <div id="response"> </div>
            </div> <!-- container fluid -->
        </div> <!-- main-content -->




        <!---Add code here-->




    </div>



    <!-- Javascript -->
    <!-- Javascript -->
    <script>
        function SendId(id) {



            document.getElementById('cid').value = id;



        }
        function SendId2(id) {



document.getElementById('uid').value = id;



}
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        var recaptcha_response = '';

        function submitUserForm() {
            if (recaptcha_response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This field is required.</span>';
                return false;
            }
            return true;
        }

        function verifyCaptcha(token) {
            recaptcha_response = token;
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
    </script>
    <script src="assets/js/jquery.min.js"></script>


    <script>
        $(".toggle-password").click(function() {

            $(this).toggleClass("fa-eye-slash fa-eye ");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
    <script>
        $(".toggle-settings").click(function() {
            // remove classes from all
            if ($('#Settings').hasClass('active')) {
                $("#Settings").removeClass("active");
            } else {
                // add class to the one we clicked
                $("#Settings").addClass("active");
            }
        });
    </script>

    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <!-- <script src="assets/bundles/flotscripts.bundle.js"></script> flot charts Plugin Js 
<script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script> -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"></script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <!-- dropify-->
    <script src="assets/vendor/dropify/js/dropify.min.js"></script>
    <script src="assets/js/pages/forms/dropify.js"></script>

    <!-- Javascript -->


    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/parsleyjs/js/parsley.min.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/editable-table.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>


    <script>
        $(document).ready(function() {
            $('input[type="file"]').change(function(e) {
                var fileName = e.target.files[0].name;
                // alert('The file "' + fileName +  '" has been selected.');
                $('#but_upload').trigger('click');
            });
        });
    </script>
    <script>
        $("#pic_upload").click(function() {
            $("#imgupload").trigger("click");
        });
    </script>



    <!-- <script type="text/javascript">
      //  $(document).ready(function() {
            // $('.search-box input[type="text"]').on("keyup input", function() {
            //     /* Get input value on change */
            //     var inputVal = $(this).val();
            //     var resultDropdown = $(this).siblings(".result");
            //     if (inputVal.length) {
            //         $.get("ajax_journalwise_expiry.php", {
            //             term: inputVal
            //         }).done(function(data) {

            //             // Display the returned data in browser

            //             resultDropdown.html(data);


            //         });
            //     } else {
            //         resultDropdown.empty();
            //     }
            // });

            // Set search input value on click of result item
         $("#submit").click(function(){
                // $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                // $(this).parent(".result").empty();
var submit=document.getElementById("submit").value;
                var currentpassword = document.getElementById("currentpassword").value;
                var newpassword = document.getElementById("newpassword").value;
                var confirmpassword = document.getElementById("confirmpassword").value;

                $.ajax({
                        url: "change_pass.php",
                        method: "POST",
                        data: {
                            submit:submit
                           currentpassword:currentpassword
                           newpassword:newpassword
                           confirmpassword:confirmpassword
                        }
                        


                    }).done(function(data){
            $("#response").html(data);});


            });

       // });
    </script>
 -->

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