<?php
//  error_reporting(E_ALL);
//  ini_set('display_errors', 1);
 error_reporting(0);
 ini_set('display_errors', 0);
 
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['URAM'])) {
    if ($_SESSION['URAM'] == "NO") {

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
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2> Draft Verification</h2>
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
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">

                            <div class="header">
                                <h2></h2>

                            </div>
                            <div class="body">
                                <!-- <div id="wizard_horizontal"> -->
                                <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Pending_Verification">Pending Verification</button> 
                                <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Approved">Approved</button>
                                <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Rejected">Rejected</button>

<br>                                    <!-- <h2>Pending Verification</h2> -->
                                    <section id="Pending_Verification" data-status="Pending_Verification">

                                        <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT
                                                                                 d.subscription_draft,
                                                                                 c.CampID,
                                                                                 c.CampName,
                                                                                 c.Camp_Status,
                                                                                 ou.user_id as AdminID,
                                                                                 ou.orgunit_id
                                                                                 FROM
                                                                                 campaign c
                                                                                 INNER JOIN 
                                                                                 draft d ON c.CampID = d.CampID 
                                                                                 LEFT JOIN
                                                                                 tbl_orgunit_user ou
                                                                                 
                                                                                 on ou.ou_id = c.ou_id
                                                                                 where TRIM(c.Camp_Status) = 'Pending Verification by Admin' 
                                                                                 and ou_status='Active' Group by c.CampID";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();


                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <!-- <table id="example" class="table table-bordered table-hover table-striped" style="width:100%"> -->
                                            <br>     <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Campaign</th>
                                                        <th>User Name</th>
                                                        <th>Organization</th>
                                                        <th>Status</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php while ($row = $stmt->fetch()) {
                                                        $cmp_draft = html_entity_decode($row['subscription_draft']);

                                                        ///////////

                                                        $sql_get = "SELECT DISTINCT
                                                        Journal_title,
                                                        article_title
                                                        FROM
                                                        campaingauthors 
                                                        WHERE
                                                        CampID=:CampID";
                                                        $camp_id = $row['CampID'];
                                                        $stmt_get = $conn->prepare($sql_get);
                                                        $stmt_get->bindValue(':CampID', $camp_id);
                                                        $stmt_get->execute();
                                                        $result_get = $stmt_get->fetch();



                                                        $article_title = trim($result_get['article_title']);


                                                        $Journal_title = trim($result_get['Journal_title']);


                                                        $Draft_tags = ["{article_title}", "{Journal_title}"];


                                                        $DB_Rows   = [$article_title, $Journal_title];
                                                        $cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                                                        $message = "<html>
                                                      </body>
                                                        
                                                        <div style=' width:85%; padding:20px;text-align: justify;'>
                                                        <p>Dear System Admin,</p>
                                                        <p style='text-align:justify;'>$cmp_draft_new</p>
                                                        </div>
                                                        </body>
                                                      </html>";
                                                    ?>
                                                        <tr>
                                                          
                                                          <td><?php echo $row['CampName']; ?></td> 

                                                          <td><?php
                                                                $id = $row['AdminID'];
                                                                $stmtu = $conn->prepare("SELECT username
                                                                                                FROM
                                                                                                admin 
                                                                                                WHERE
                                                                                                AdminId=:AdminId");
                                                                $stmtu->bindValue(':AdminId', $id);
                                                                $stmtu->execute();
                                                                $username = $stmtu->fetch();
                                                                echo $username['username'];

                                                            ?></td>
                                                            <td><?php 
                                                            if (!empty($row['orgunit_id']) || ($row['orgunit_id'] != NULL)){
                                                                $org_id = $row['orgunit_id'];// echo $org_id;
                                                                $stmtou = $conn->prepare("SELECT    orgunit_name
                                                                                                FROM
                                                                                                tbl_organizational_unit
                                                                                                WHERE
                                                                                                orgunit_id='$org_id'");
                                                                //$stmtou->bindValue(':orgunit_id',$org_id);
                                                                $stmtou->execute();
                                                                $orgname = $stmtou->fetch();
                                                                echo $orgname['orgunit_name'];
                                                            } else {echo 'None ';}?> </td>

                                                            <td> <span class="badge badge-warning"><?php echo $row["Camp_Status"] . " "; ?><i class="fa fa-history"></i> </span> 
                                                                <!-- <br> <br> -->

                                                            </td>
                                                            <td>
                                                           
                                                                <!-- <button  class="btn btn-success btn-sm" onclick="SendId(<?php echo $row['CampID']; ?>)" data-toggle="modal" data-target="#exampleModalCenter">
                                                                <i class="fa fa-envelope "></i> Verify Email</button> -->

                                                                <a class="btn btn-success btn-sm" href="Check_draft_alert.php?campid=<?php echo $row['CampID']; ?>"> <i class="fa fa-envelope "></i> View Draft</a>

                                                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" style="max-width: '100%';min-width: 80%;" role="document">
                                                                        <div class="modal-content" style="overflow-x: auto;">
                                                                           
                                                                            <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Draft Verification</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>

                                                                            <div class="modal-body " style="overflow-x: auto;">
                                                                                    <h5>Please check you mail box before approving the draft</h5>
                                                                                    <form method="post" action="verify_admin.php">
                                                                                        <div class="form-group">

                                                                                         <div >
                                                                                              <!-- id="show_comment" -->
                                                                                        <p> Email Draft: <?php echo $message; ?>  </p>
                                                                                        </div>
                                                                                        <label>Reason to Discard</label>
                                                                                        <textarea id="reason" name="reason" onchange="SendId(<?php echo $row['CampID']; ?>)" class="form-control" rows="5" cols="30"></textarea>

                                                                                        </div>
                                                                                        <input id="CampID" name="CampID" type="hidden" value="<?php echo $row['CampID']; ?>">
                                                                                        <button id="approve" type="submit" name="submit" value="Submit" class="btn btn-primary">Approve</button>
                                                                                        <button disabled="true" id="discard" type="submit" class="btn btn-secondary">Discard</button>
                                                                                    </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </section>


                                    <!-- <h2>Approved</h2> -->
                                    <section id="Approved" data-status="Approved">
                               <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT
                                                                                 cd.subscription_draft,
                                                                                 c.CampID,
                                                                                 c.CampName,
                                                                                 c.Camp_Status,
                                                                                 cd.status,
                                                                                 ou.user_id as AdminID,
                                                                                 ou.orgunit_id
                                                                                 FROM
                                                                                 campaign c
                                                                                 INNER JOIN 
                                                                                 draft d ON c.CampID = d.CampID 
                                                                                 LEFT JOIN
                                                                                 tbl_orgunit_user ou
                                                                                 
                                                                                 on ou.ou_id = c.ou_id
                                                                                 Right JOIN 
                                                                                 camp_draft cd ON c.CampID = cd.CampID 
                                                                                 where TRIM(cd.status) = 'Verified' and ou_status='Active' Group by c.CampID";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();


                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <!-- <table id="example" class="table table-bordered table-hover table-striped" style="width:100%"> -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Campaign</th>
                                                        <th>User Name</th>
                                                        <th>Organization</th>
                                                        <th>Status</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php while ($row = $stmt->fetch()) {
                                                        $cmp_draft = html_entity_decode($row['subscription_draft']);
                                                        $cmp_draft=html_entity_decode($cmp_draft);
                                                        ///////////

                                                        $sql_get = "SELECT DISTINCT
                                                        Journal_title,
                                                        article_title
                                                        FROM
                                                        campaingauthors 
                                                        WHERE
                                                        CampID=:CampID";
                                                        $camp_id = $row['CampID'];
                                                        $stmt_get = $conn->prepare($sql_get);
                                                        $stmt_get->bindValue(':CampID', $camp_id);
                                                        $stmt_get->execute();
                                                        $result_get = $stmt_get->fetch();



                                                        $article_title = trim($result_get['article_title']);


                                                        $Journal_title = trim($result_get['Journal_title']);


                                                        $Draft_tags = ["{article_title}", "{Journal_title}"];


                                                        $DB_Rows   = [$article_title, $Journal_title];
                                                        $cmp_draft_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                                                        $message_app = "<html>
                                                      </body>
                                                        
                                                        <div style=' width:85%; padding:20px;text-align: justify;'>
                                                        <p>Dear System Admin,</p>
                                                        <p style='text-align:justify;'>$cmp_draft_new_app</p>
                                                        </div>
                                                        </body>
                                                      </html>";
                                                    ?>
                                                        <tr>
                                                          
                                                          <td><?php echo $row['CampName']; ?></td> 

                                                          <td><?php
                                                                $id = $row['AdminID'];
                                                                $stmtu = $conn->prepare("SELECT username
                                                                                                FROM
                                                                                                admin 
                                                                                                WHERE
                                                                                                AdminId=:AdminId");
                                                                $stmtu->bindValue(':AdminId', $id);
                                                                $stmtu->execute();
                                                                $username = $stmtu->fetch();
                                                                echo $username['username'];

                                                            ?></td>
                                                            <td><?php 
                                                            if (!empty($row['orgunit_id']) || ($row['orgunit_id'] != NULL)){
                                                                $org_id = $row['orgunit_id'];// echo $org_id;
                                                                $stmtou = $conn->prepare("SELECT    orgunit_name
                                                                                                FROM
                                                                                                tbl_organizational_unit
                                                                                                WHERE
                                                                                                orgunit_id='$org_id'");
                                                                //$stmtou->bindValue(':orgunit_id',$org_id);
                                                                $stmtou->execute();
                                                                $orgname = $stmtou->fetch();
                                                                echo $orgname['orgunit_name'];
                                                            } else {echo 'None ';}?> </td>

                                                            <td> <span class="badge badge-success"><?php echo $row["status"] . " "; ?><i class="fa fa-check-square-o"></i> </span> 
                                                                <!-- <br> <br> -->

                                                            </td>
                                                            <td>
                                                                <button id="<?php echo $row['CampID']; ?>"  class="btn btn-success btn-sm"  data-toggle="modal" data-target="#exampleModalCenter<?php echo $row['CampID']; ?>"><i class="fa fa-envelope "></i> View Email</button>

                                                                <div class="modal fade" id="exampleModalCenter<?php echo $row['CampID']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" style="max-width: '100%';min-width: 80%;" role="document">
                                                                        <div class="modal-content" style="overflow-x: auto;">
                                                                           
                                                                            <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Draft Verification</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                    <h5>Aproved Draft</h5>
                                                                                     <div id=""> <?php echo $message_app; ?></div>
                                                                                    
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>

                                    </section>
                                    <!-- <h2>Rejected</h2> -->
                                    <section id="Rejected" data-status="Rejected">

<div class="table-responsive">
                                            <?php
                                            $sql = "SELECT
                                                                cd.subscription_draft,
                                                                cd.rejected_iteration,
                                                                c.CampID,
                                                                c.CampName,
                                                                c.Camp_Status,
                                                                cd.status,
                                                                ou.user_id as AdminID,
                                                                ou.orgunit_id
                                                                FROM
                                                                campaign c
                                                                INNER JOIN 
                                                                draft d ON c.CampID = d.CampID 
                                                                LEFT JOIN
                                                                tbl_orgunit_user ou
                                                                
                                                                on ou.ou_id = c.ou_id
                                                                Right JOIN 
                                                                camp_draft cd ON c.CampID = cd.CampID 
                                                                where TRIM(cd.status) = 'Rejected' and ou_status='Active' group by c.CampID";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();


                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <!-- <table id="example" class="table table-bordered table-hover table-striped" style="width:100%"> -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Campaign</th>
                                                        <th>User Name</th>
                                                        <th>Organization</th>
                                                        <th>Status</th>
                                                       <!--  <th>Action</th>
 -->
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php while ($row = $stmt->fetch()) {

                                                        
                                                    ?>
                                                        <tr>
                                                          <td> 


<?php $campid=$row['CampID']; $i=1;
$stmtcd = $conn->prepare("SELECT   rejected_iteration,subscription_draft,reason FROM camp_draft WHERE CampID='$campid' and status='Rejected'");
                                                                //$stmtou->bindValue(':orgunit_id',$org_id);
                                                                $stmtcd->execute();

                                                              //  

                                                                //echo $draft['rejected_iteration']; 
                                                                $aid=$row['CampID'].$i;  $i++;?>


                                <button  onclick="showDraft(<?php echo $aid; ?>)" style=" background: #293a4a; opacity: 0.9; color:white; " class="btn-xs rounded  width-sm waves-effect waves-light"><b>+</b></button>   <?php echo $row['CampName']; ?>
                                                        
                                                            <div  style="display:none" id="<?php echo $aid; ?>"> 
                                
                                <table id= "ren_table">
                                <thead>
                                            <tr>
                 <th>Rejected iteration</th>
                 <th>Reason</th>
                 <th>Draft</th>
                                             
                                            

              </tr>
           </thead>
            <?php  while($draft = $stmtcd->fetch()){ ?>
            <tr>
             
                          <td>  <?php echo $draft['rejected_iteration'];?> </td>
                            <td>  <?php echo $draft['reason'];?> </td>
<?php 
$cmp_draft = html_entity_decode($draft['subscription_draft']);
                                                         $cmp_draft = html_entity_decode($cmp_draft);

                                                        ///////////

                                                        $sql_get = "SELECT DISTINCT
                                                        Journal_title,
                                                        article_title
                                                        FROM
                                                        campaingauthors 
                                                        WHERE
                                                        CampID=:CampID";
                                                        $camp_id = $row['CampID'];
                                                        $stmt_get = $conn->prepare($sql_get);
                                                        $stmt_get->bindValue(':CampID', $camp_id);
                                                        $stmt_get->execute();
                                                        $result_get = $stmt_get->fetch();

                                                        $article_title = trim($result_get['article_title']);

                                                        $Journal_title = trim($result_get['Journal_title']);

                                                        $Draft_tags = ["{article_title}", "{Journal_title}"];


                                                        $DB_Rows   = [$article_title, $Journal_title];
                                                        $cmp_draft_new_rej = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                                                        $message_rej = "<html>
                                                      </body>
                                                        
                                                        <div style=' width:85%; padding:20px;text-align: justify;'>
                                                        <p>Dear System Admin,</p>
                                                        <p style='text-align:justify;'>$cmp_draft_new_rej</p>
                                                        </div>
                                                        </body>
                                                      </html>";
?>
                              <td>  <?php  echo $message_rej; ?> </td>

            </tr>
              <?php } //while loop within while loop ?>
          </table>
         </div>
    
</td>
                                                          <!-- <td><?php echo $row['CampName'].$cmp_draft; ?></td>  -->

                                                          <td><?php
                                                                $id = $row['AdminID'];
                                                                $stmtu = $conn->prepare("SELECT username
                                                                                                FROM
                                                                                                admin 
                                                                                                WHERE
                                                                                                AdminId=:AdminId");
                                                                $stmtu->bindValue(':AdminId', $id);
                                                                $stmtu->execute();
                                                                $username = $stmtu->fetch();
                                                                echo $username['username'];

                                                            ?></td>
                                                            <td><?php 
                                                            if (!empty($row['orgunit_id']) || ($row['orgunit_id'] != NULL)){
                                                                $org_id = $row['orgunit_id'];// echo $org_id;
                                                                $stmtou = $conn->prepare("SELECT    orgunit_name
                                                                                                FROM
                                                                                                tbl_organizational_unit
                                                                                                WHERE
                                                                                                orgunit_id='$org_id'");
                                                                //$stmtou->bindValue(':orgunit_id',$org_id);
                                                                $stmtou->execute();
                                                                $orgname = $stmtou->fetch();
                                                                echo $orgname['orgunit_name'];
                                                            } else {echo 'None ';}?> </td>

                                                            <td> <span class="badge badge-danger"><?php echo $row["status"] . " "; ?><i class="fa fa-times"></i> </span> 
                                                                <!-- <br> <br> -->

                                                            </td>



                                                           
                                                        </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>
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

    <script>
  function showDraft(id)
 {

   

var x = document.getElementById(id);

    
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
        </script>

    <script>
        function SendId(id) {




            //FINDING ELEMENTS OF ROWS AND STORING THEM IN VARIABLES
            // var a = document.getElementById(id).value;

            // document.getElementById('CampID').value = id;

            // var p = "";
            // CREATING DATA TO SHOW ON MODEL
            // p =
            //     "<p> Email Draft:  " +
            //     a + " </p>";


            //CLEARING THE PREFILLED DATA
            //$("#show_comment").empty();
            //WRITING THE DATA ON MODEL
            //$("#show_comment").append(p);
          
            setInterval(function() {

                var reason = document.getElementById("reason").value;

                if (reason.trim() != "") {
                    $('#discard').prop('disabled', false);
                    $('#approve').prop('disabled', true);

                } else {
                    $('#discard').prop('disabled', true);
                    $('#approve').prop('disabled', false);

                }
            }, 200);





        }
    </script>

    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/bundles/datatablescripts.bundle.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>

    <script src="assets/vendor/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js -->


    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>

    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
 
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
















<!--  <td> <button  onclick="showRenewals(<?php echo $row['board_member_journal_role_id'];?>)" class="btn btn-primary width-xs waves-effect waves-light">+</button><?php echo $row_journal["journal_code"] ;?>
                               <?php $Aid= $row["board_member_journal_role_id"] ; ?>
                               <div style="display:none" id=<?php echo "$Aid"?>> 
                                
                                <table id= "ren_table">
                                <thead>
                                            <tr>
                                                <th>Renewal Date</th>
                                                <th>Renewal Tenure</th>
                                                <th>Renewal Expiry</th>
                                                <th>Renewal Emails</th>
                                              


                                            </tr>
                                        </thead>

            <tr>
              <td>  <?php echo $row["renewal_date"] ;?> </td>
              <td>  <?php echo $row["renewal_tenure"] ;?> </td>
              <td> <?php echo $row["renewal_expiry"] ;?> </td>
              <td> <a class="btn btn-primary btn-sm" href="temp-record-file/renewals/<?php echo $row["renewal_file"] ;?>">Download</a> </td>
            </tr>
          </table>
                                
                                  </div>
                            </td>



 





                             -->