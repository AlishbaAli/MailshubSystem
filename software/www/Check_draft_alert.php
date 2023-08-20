<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
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
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="assets/images/thumbnail.png" width="48" height="48" alt="Mplify"></div>
            <p>Please wait...</p>
        </div>
    </div> -->
    <!-- Overlay For Sidebars -->
    <!-- <div class="overlay" style="display: none;"></div> -->

    <!-- <div id="wrapper"> -->

        <!--nav bar-->
        <?php //include 'include/nav_SA.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php // include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->


        <!-- <div id="main-content">
            <div class="container-fluid"> -->
                <!-- <div class="block-header">
                    <div class="row">
                         <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Add System Settings</h2>
                        </div> 
                        <div class="col-lg-9 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div> -->

                <!---Add code here-->
                <div class="row">
                    <div class="col-lg-12" style="padding: 100px;">
                        <div class="card" >
                            <div class="card-body">




<?php

$user_id = $_SESSION['AdminId'];

//get logged_in user email id
$stmt_to = $conn->prepare("SELECT *  FROM admin  WHERE AdminId=:AdminId
");
$stmt_to->bindParam(':AdminId', $user_id);
$stmt_to->execute();
$emails = $stmt_to->fetch();

$to = $emails['email'];
$CampID = $_GET['campid'] ;

$sql_get = "SELECT
a.Fname,
a.Lastname,
a.Journal_title,
a.article_title,
c.CampName,
c.mailserverid,
d.subscription_draft
FROM
admin a, campaign c, draft d, tbl_orgunit_user ou
WHERE
a.AdminID = ou.user_id and c.ou_id= ou.ou_id AND d.CampID = c.CampID AND
c.CampID = :CampID ";
  echo '<form method="post" action="verify_admin.php">
  <div class="form-group"> <div >     
  <p> Please check you mail box before approving the draft  </p>
  </div>
  <label>Reason to Discard</label>
  <textarea id="reason" name="reason" class="form-control" rows="5" cols="30"></textarea>
</div>
  <input id="CampID" name="CampID" type="hidden" value="'.$CampID.'">
  <button id="approve" type="submit" name="submit" onclick="return Approveclick();" value="Submit" class="btn btn-primary">Approve</button>
  <button disabled="true" id="discard" type="submit" onclick="return discardclick();" class="btn btn-secondary">Discard</button>
</form>';

$stmt_get = $conn->prepare($sql_get);

$stmt_get->bindValue(':CampID', $CampID);
//$stmt_get->bindParam('AdminId', $user_id);
$stmt_get->execute();
if ($stmt_get->rowCount() > 0) {
    $row_get = $stmt_get->fetch();

    $article_title = trim($row_get['article_title']);
    $Journal_title = trim($row_get['Journal_title']);
    $CampName = trim($row_get['CampName']);
    $Draft_tags = ["{article_title}", "{Journal_title}"];
    echo" <div style=' width:85%; padding:20px; text-align: justify;'>";
    echo" </div>";
    echo "Dear Verification Manager,<br> <p> Please approve the following email draft for the campaign entitled 
    '".$CampName."'. If you would like to change anything in below draft 
    then please write the changes in 'Reject
     Draft' dalogue box.
    Campaign Details are as follows: </p>
 ";
    echo "<div style='border: 1px solid black; width:70%; padding:20px; background-color:#bababb17;'>";
    
    echo "Dear Dr. " . $emails['Fname'] . " " . $emails['Lastname']. ":<br/></br/>";

    $cmp_draft = html_entity_decode($row_get['subscription_draft']);
    $DB_Rows   = [$article_title, $Journal_title];
    $cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
    echo $cmp_draft_new;


    echo "</div>";
    echo "<br/>";

    $mailserverid = trim($row_get['mailserverid']);
    // echo "nn".$mailserverid;

    //from_address
    $sqlm = "SELECT
emailaddress
FROM
current_email
WHERE mailserverid=$mailserverid";

    $stmtm = $conn->prepare($sqlm);
    $stmtm->execute();
    $row =  $stmtm->fetch();
    $from = $row['emailaddress'];

    //echo $CampID;
    //echo $from . "<br>";

    //echo $to;

    $from = "do-not-reply@mailshub.net";
    //$to = "alishbaali@benthamscience.net";

    $subject = "Verify - " . $row_get['CampName'];
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    //$headers .= 'From:Articles Contributions<editorial@specialissueeditor.net>'."\r\n";
    $headers .= "From:Mailshub<" . $from . ">" . "\r\n";
    //$headers .= 'Cc:qasit@benthamscience.net, faisal@benthamscience.net'."\r\n";

    $message = "<html>

        </body>
        <div style=' width:85%; padding:20px;text-align: justify;'>
        <p>Dear Admin,</p>
        <p style='text-align:justify;'>$cmp_draft_new</p>
        </div>
        </body>

        </html>";

    //echo "<br/>" . "$message" . "<br/>";

    //mail($to, $subject, $message, $headers);
}
?>

</div>
</div>
</div>

</div>
<!-- end row -->


<!---Add code here-->




<!-- </div> -->
<!-- </div> -->
<!-- 
</div> -->

<!-- Javascript -->
<script>
        function Approveclick() {
            return confirm("Do you want to Approve Campaign Draft?")
        };

        function discardclick() {
            return confirm("Do you want to Discard Campaign Draft?")
        };

       
    </script>
     


<!-- Javascript -->
<script src="assets/bundles/libscripts.bundle.js"></script>
<script src="assets/bundles/vendorscripts.bundle.js"></script>

<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="assets/vendor/parsleyjs/js/parsley.min.js"></script>

<script src="assets/bundles/mainscripts.bundle.js"></script>
<script src="assets/bundles/morrisscripts.bundle.js"></script>
<script>
       $(document).ready(function() {


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