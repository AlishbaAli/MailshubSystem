<?php
ob_start();
session_start();
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
                            <!-- <h2>Dashboard</h2>-->
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

                <div class="block-header">
                    <div class="row">

                        <div class="col-lg-7 offset-lg-2">
                            <div class="card">
                                <form method="post" action="editCampaign.php" enctype="multipart/form-data">
                                    <div class="header">
                                        <h3>Edit Campaign</h3>

                                        <?php $CampHeading = "";
                                        $CampName = "";
                                        $AdminId = $_SESSION['AdminId'];
                                        echo $already = "";
                                        try {
                                            if (isset($_GET["CampID"])) {
                                                $CampID =  $_GET['CampID'];

                                                $sql = "SELECT `CampID`, `CampName`, `CampDate`, `AdminID`, `Camp_Status`  
                                                        FROM `campaign` 
                                                        WHERE CampID = :CampID";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindValue(':CampID', $CampID);

                                                $result = $stmt->execute();

                                                if ($stmt->rowCount() > 0) {
                                                    $result = $stmt->fetchAll();
                                                    foreach ($result as $row) {

                                                        $CampID = $row["CampID"];

                                                        $CampName = $row["CampName"];
                                                        $CampDate = $row["CampDate"];
                                                        $AdminID = $row["AdminID"];
                                                        $Camp_Status = $row["Camp_Status"];
                                                    }
                                                }
                                            }

                                            if (isset($_POST["updateCampaing"])) {

                                                $CampID =  $_POST['CampID'];

                                                $CampName = $_POST['CampName'];
                                                $CampDate = $_POST['CampDate'];
                                                $AdminID = $_SESSION['AdminId'];


                                                //Prepare our UPDATE statement.
                                                $sql = "UPDATE `campaign` 
                                                            SET 
                                                                `CampName`=:CampName,
                                                                `CampDate`=:CampDate,
                                                                `AdminID`=:AdminID
                                                                
                                                            WHERE CampID = :CampID";

                                                $stmt = $conn->prepare($sql);

                                                //Bind our variables.
                                                $stmt->bindValue(':CampID', $CampID);

                                                $stmt->bindValue(':CampName', $CampName);
                                                $stmt->bindValue(':CampDate', $CampDate);
                                                $stmt->bindValue(':AdminID', $AdminID);


                                                //Execute the statement and insert the new account.
                                                $result = $stmt->execute();

                                                if ($result > 0) {
                                                    $already = "<br/><br/><div class='alert alert-success'><strong>Campaign Updated Successfuly </strong>
                                                    </div>
                                                    <meta http-equiv='refresh' content='1;url=index.php'>
                                                    ";
                                                } else {
                                                    echo ("<br/><br/><div class='alert alert-danger' role='alert'><strong>This Campaign Not Updated!</strong></div>");
                                                    echo   "<div class='element-box-content'>
                                                                    <a href='Edit_campaign.php?CampID=" . $CampID . "'>
                                                                    <button class='mr-2 mb-2 btn btn-primary btn-md' type='button'>Please Update Again!</button></a>
                                                                </div>";
                                                }
                                            }
                                        }
                                        //catch exception
                                        catch (Exception $e) {
                                            echo 'Message: ' . $e->getMessage();
                                        }

                                        ?>


                                    </div>

                                    <div class="body">

                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="CampHeading" value="<?php echo $CampHeading; ?>" placeholder="Enter Draft Heading..." aria-label="Recipient's username" aria-describedby="basic-addon2">

                                        </div>
                                        <br />

                                        <div class="input-group mb-3">
                                            <input type="hidden" required="required" id="CampID" name="CampID" value="<?php echo $CampID; ?>">
                                            <input type="text" name="CampName" class="form-control" value="<?php echo $CampName; ?>" placeholder="Enter Campaign Name..." aria-label="Enter Campaign Name" aria-describedby="basic-addon2" required>

                                        </div>
                                        <br />

                                        <div class="input-group mb-3">
                                            <input type="date" name="CampDate" class="form-control" value="<?php echo $CampDate; ?>" placeholder="Enter Campaign Date..." aria-label="Enter Campaign Date" aria-describedby="basic-addon2">

                                        </div>
                                        <br />

                                        <button type="submit" name="updateCampaing" class="btn btn-primary"><i class="glyphicon glyphicon-refresh"></i> Update Campaign</button>
                                        <?php
                                        if (empty($already)) {
                                            echo $already = "";
                                        } else
                                            echo $already;
                                        //echo $already;

                                        ?>
                                    </div>

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



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
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