<?php
ob_start();
session_start();
 // error_reporting(E_ALL);
 // ini_set('display_errors', 1);
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['AC'])) {
    if ($_SESSION['AC'] == "NO") {

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

                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <br />




                            <h5 class="form-header">
                                <?php

                                if (isset($_GET['CampID'])) {
                                    //echo $_GET['CampID'];
                                    $CampID =  $_GET['CampID'];

                                    $sql = "SELECT COUNT(CampName) AS num ,  ou_id, CampName, campaign.CampID, subscription_draft,draft_subject
                                    FROM campaign left join draft on draft.CampID = campaign.CampID
                                    WHERE campaign.CampID = :CampID";
                                    $stmt = $conn->prepare($sql);
                                    //Bind the provided username to our prepared statement.
                                    $stmt->bindValue(':CampID', $CampID);
                                    //Execute.
                                    $stmt->execute();
                                    //Fetch the row.
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $ou_id= $row['ou_id'];
                                 
                                    $stmt_get_ouid= $conn->prepare("SELECT tbl_organizational_unit.orgunit_id, orgunit_code FROM 	tbl_orgunit_user INNER JOIN
                                    tbl_organizational_unit ON tbl_organizational_unit.orgunit_id= tbl_orgunit_user.orgunit_id AND  ou_id='$ou_id' LIMIT 1");
                                    $stmt_get_ouid->execute();
                                    $org_id=   $stmt_get_ouid->fetch();
                                    //If the provided username already exists - display error.
                                    if ($row['num'] > 0) {
                                        echo "<span class='badge badge-default'><h4>Campaign - <strong><span style='color:#007bfff0;'>" . $row["CampName"] . "</span></strong> </h4></span>";
                                        echo "<hr/>";
                                        echo "Draft Tag -  {Journal_title} , {article_title}";
                                        echo "<hr/>";
$cmp_draft = html_entity_decode($row['subscription_draft']);
                                                        $cmp_draft=html_entity_decode($cmp_draft);
                                                        $draft_sub = html_entity_decode($row['draft_subject']);
                                                        $draft_sub=html_entity_decode($draft_sub);
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

if (!empty(trim($result_get['article_title']))) { 
    $article_title = trim($result_get['article_title']);
} else{ $article_title ="{article_title}"; } if (!empty(trim($result_get['Journal_title']))) { 
    $Journal_title = trim($result_get['Journal_title']);
} else{ $Journal_title ="{Journal_title}"; } 

                                                        $Draft_tags = ["{article_title}", "{Journal_title}"];

                                                        $DB_Rows   = [$article_title, $Journal_title];
                                                        $cmp_draft_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                                                        $cmp_draft_sub_new_app = str_replace($Draft_tags, $DB_Rows, $draft_sub);
                                                        $message_app = "<html>
                                                      </body><div style=' width:85%; text-align: justify;'>
                                                    $cmp_draft_new_app
                                                        </div>
                                                        </body>
                                                      </html>";
                                                      $message_subject_app = $cmp_draft_sub_new_app;

                                             } else
                                        /* 										echo "<div class='alert alert-danger' role='alert'>";
                                echo "<strong>invalid Selection! </strong>";
                              echo "</div>"; */
                                        echo "invalid Selection";
                                    //die();
                                }
                                ?>
                            </h5>



                            <div class="body">
                                <h4>Add Draft Here</h4>
                                <?php if(isset($_SESSION['orgunit_id'] )){?>
                                <button id="add_new_banner" name="add_new_banner" class="btn btn-primary"
                                data-toggle="modal" data-target="#add_new_modal"><i class="fa fa-image"></i> Upload New Banner</button> 
                                <div class="modal fade"  id="add_new_modal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                 
                                 <div class="alert alert-dark" role="alert"> Add New Banner</div>
                             </div>
                             <div class="modal-body"> 
                             <div style="width:800px; margin:0 auto;" class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="demo-masked-input">
                                                <form  enctype="multipart/form-data" class="custom-validation" action="add_banner_db.php" method="post">
                                              
                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Upload Banner *</span></span>
                                                        </div>
                              
                                                    </div>

                                                  
                                                   <div class="card">
                                                       
                                                       <div class="body">
                                                       <input type="file" name='banner_image'class="dropify" data-allowed-file-extensions="jpg jpeg bmp gif png">
                                                       </div>
                                                   </div>
                                                    <div class="form-group input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text "><span style="font-size:13px;">Banner Heperlink (Optional) </span></span>
                                                        </div>
                                                        <input type="text" name="hyperlink" class="form-control" placeholder="Enter Hyperlink" >
                                                    </div>
                                                    <input hidden type="text" name="orgunit_id" value="<?php echo  $org_id['orgunit_id'];?>"class="form-control" >
                                                    <input hidden type="text" id="page_name" name="page_name" class="form-control" >

                                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                                Upload
                                                            </button>


                                                </form>
                                            </div>
                             </div>                    

                            </div>            
                            <div class="modal-footer">           
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                            </div>

                                </div>


                                </div>
                                </div>

                                <?php } else { ?>
                                        <button disabled id="add_new_banner" name="add_new_banner" class="btn btn-primary"><i class="fa fa-plus"></i> Upload New Banner</button> 
                               <?php }
                                         if(isset($_SESSION['orgunit_id'] )){
                               ?>
                                   <button id="add_existing_banner" name="add_existing_banner" class="btn btn-primary"
                                data-toggle="modal" data-target="#add_existing_modal"><i class="fa fa-image"></i> Add Existing Banner</button> 
                                <div class="modal fade"  id="add_existing_modal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg"  style="max-width: '100%';min-width: 80%;" role="document">
                                <div class="modal-content" style="overflow:scroll;">
                                <div class="modal-header">
                                 
                                 <div class="alert alert-dark" role="alert"> Add Existing Banner</div>
                             </div>
                             <div class="modal-body"> 
                            
                                         
                                                
                                            <div class="col-md-12">
                    <div class="card">
                        <div class="body">                          
                            <div class="table-responsive">
                                <table class="table m-b-0 table-hover">
                                    <thead>
                                        <tr>                                    
                                            <th>Banner</th>
                                            <th>URL</th>
                                            <th>Hyperlink</th>
                                           
                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        
                                        $stmt_banner= $conn->prepare("SELECT * FROM banners WHERE orgunit_id=:orgunit_id");
                                            $stmt_banner->bindValue(':orgunit_id', $org_id['orgunit_id']);
                                            $stmt_banner->execute();
                                            while ($row = $stmt_banner->fetch()) {
                                        ?>
                                        <tr>
                                            <td>
                                            <img src="banners/<?php echo $org_id['orgunit_code'] ?>/<?php echo $row['image_name']?>" alt=""  height=100 width=500> </img>
                                      
                                        </td>
                                            <td>
                                                <?php echo $row['image_url']?>
                                                <input hidden type="text" value=<?php echo $row['image_url']?> id="urlid">

                                              
                                            </td>
                                            <td>
                                            <?php echo $row['image_hyperlink']?>
                                            <input hidden type="text" value=<?php echo $row['image_hyperlink']?> id="hypid">
                                        
                                                
                                                </td>
                                       
                                        </tr>
                                        
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                                           

                                            
                                                

                            </div>            
                            <div class="modal-footer">           
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                            </div>

                                </div>


                                </div>
                                </div>

                                 <?php } else { ?>
                                    <button  disabled id="add_existing_banner" name="add_existing_banner" class="btn btn-primary"><i class="fa fa-plus"></i> Add Exisitng Banner</button>
                                    <?php } ?>
                                     
                                 <br> <br>
                                <form action="edit_draft_db.php" method="post" enctype="multipart/form-data">

                                <div class="input-group  mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Draft Subject:</span>
                                </div>
                                <input type="text" id="Camp_sub" name="Camp_sub" class="form-control" placeholder="Enter Campaign Subject" value="<?php echo $message_subject_app; ?>" aria-label="Small" aria-describedby="inputGroup-sizing-sm" required>
                                </div> 
                                    <textarea id="ckeditor" name="subscription_draft" rows="20" required><?php echo $message_app; ?></textarea>
                                    <input type="hidden" name="CampID" value="<?php echo $_GET['CampID']; ?>">
                                    <br />
                                    <button type="submit" id="submit" name="submitDraft" class="btn btn-primary">Submit</button>
                                </form>
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
    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>
    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"></script>
    <script src="assets/vendor/ckeditor/ckeditor.js"></script> <!-- Ckeditor -->
    <script src="assets/js/pages/forms/editors.js"></script>
    <script src="assets/vendor/dropify/js/dropify.min.js"></script>
    <script src="assets/js/pages/forms/dropify.js"></script>

    <script src="index.js"></script>

<!-- Session timeout js -->
<script>
    $(document).ready(function() {
        var str=window.location.href
                    const urlarray = str.split("/");
                    var lastItem = urlarray.pop();
                    // const ulrarray2= lastItem.split('?');
                    // var firstItem= ulrarray2[0];
                    document.getElementById("page_name").value= lastItem;

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