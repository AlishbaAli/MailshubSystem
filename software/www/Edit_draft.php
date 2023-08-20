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

                                    $sql = "SELECT COUNT(CampName) AS num , CampName, campaign.CampID, subscription_draft,draft_subject
                                    FROM campaign left join draft on draft.CampID = campaign.CampID
                                    WHERE campaign.CampID = :CampID";

                                    $stmt = $conn->prepare($sql);

                                    //Bind the provided username to our prepared statement.
                                    $stmt->bindValue(':CampID', $CampID);

                                    //Execute.
                                    $stmt->execute();

                                    //Fetch the row.
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

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