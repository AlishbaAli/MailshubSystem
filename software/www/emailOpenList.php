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


        <!-- <div id="main-content">
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
            </div> -->

        <!---Add code here-->
        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12">
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h3>Unique Email Open List</h3>
                                </div>
                                <div class="element-box">
                                    <h5 class="form-header" style="float:left">
                                        Total Unique Open
                                        <?php
                                        $CampID = $_GET['CampID'];
                                        //GET camp status

                                        $stmt_s = $conn->prepare("SELECT Camp_Status FROM campaign WHERE CampID= :CampID");
                                        $stmt_s->bindValue(':CampID', $CampID);
                                        $stmt_s->execute();
                                        $result = $stmt_s->fetch(PDO::FETCH_ASSOC);
                                        $Camp_Status = $result['Camp_Status'];

                                        if ($Camp_Status == 'Active') {
                                            $campauthourstable = "campaingauthors";
                                        } else if ($Camp_Status == 'Stop') {
                                            $campauthourstable = "campaingauthors_hold_archive";
                                        } else if ($Camp_Status == 'Completed') {
                                            $campauthourstable = "campaingauthors_comp_archive";
                                        }

                                        $stmt4 = $conn->prepare("SELECT count(DISTINCT cas.CampaingAuthorsID) as UniqueOpened
                                                            FROM campaign c, $campauthourstable ca, campaign_author_stats cas
                                                            WHERE c.CampID = ca.CampID
                                                            AND ca.CampaingAuthorsID = cas.CampaingAuthorsID
                                                            AND cas.Stats_Type = 'EmailOpened'
                                                            AND c.CampID = :CampID");
                                        $stmt4->bindValue(':CampID', $CampID);
                                        $stmt4->execute();
                                        $result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                                        $UniqueOpened = $result4['UniqueOpened'];
                                        echo "($UniqueOpened)";



                                        ?>

                                    </h5>
                                    <!--------------------
                      START - Table with actions
                      -------------------->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md table-v2 table-striped">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>
                                                        S.No.
                                                    </th>
                                                    <th>
                                                        Full Name
                                                    </th>
                                                    <th>
                                                        Email Address
                                                    </th>
                                                    <!-- <th>
                                Email Open Date
                              </th>
                              <th>
                                Email Open Time
                              </th> -->
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php


                                                /*   SELECT
                                        DISTINCT ca.email,
                                        ca.Fname,
                                        ca.Lastname,
                                        cas.EmailOpenedDateTime
                                    FROM
                                        campaign c,
                                        campaingauthors ca,
                                        campaign_author_stats cas
                                    WHERE
                                        c.CampID = ca.CampID 
                                        AND 
                                        ca.CampaingAuthorsID = cas.CampaingAuthorsID 
                                        AND cas.Stats_Type = 'EmailOpened' 
                                        AND c.CampID = :CampID*/

                                                $sql = "SELECT
                                ca.Fname,
                                ca.Lastname,
                                ca.email
                               
                            FROM
                                campaign c,
                                $campauthourstable ca,
                                campaign_author_stats cas
                            WHERE
                                c.CampID = ca.CampID AND ca.CampaingAuthorsID = cas.CampaingAuthorsID AND cas.Stats_Type = 'EmailOpened' AND c.CampID = :CampID
                                GROUP BY cas.CampaingAuthorsID";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindValue(':CampID', $CampID);
                                                $result = $stmt->execute();








                                                if ($stmt->rowCount() > 0) {
                                                    $result = $stmt->fetchAll();

                                                    //initialization for s.no.
                                                    $a = 0;
                                                    $b = 1;

                                                    foreach ($result as $row) {
                                                        echo "<tr>";
                                                        /*                           echo"<td class='text-center'>";
                                            echo "CAMP#0".$row["CampID"];
                                          echo"</td>";   */
                                                        echo "<td class='text-center'>";
                                                        echo $c = $b + $a;
                                                        echo "</td>";
                                                        echo "<td class='text-center'>";
                                                        echo $row["Fname"] . " " . $row["Lastname"];
                                                        echo "</td>";
                                                        echo "<td class='text-center'>";
                                                        echo $row["email"];
                                                        echo "</td>";

                                                        /*echo"<td class='text-center'>"; 
                                                echo date('d-m-Y', strtotime($row['EmailOpenedDateTime']));
                                            echo"</td>";
                                            
                                            echo"<td class='text-center'>"; 
                                                echo date("g:i:s a", strtotime($row['EmailOpenedDateTime']));
                                            echo"</td>";*/

                                                        /*                                      
                                            echo"<td class='row-actions'>";
                                                echo"<a href='Edit_campaign.php?UnsubscribeID=".$row["UnsubscribeID"]."'><i class='os-icon os-icon-ui-49'></i></a>";
                                                
                                                echo"<a href='delete_campaign.php?UnsubscribeID=".$row["UnsubscribeID"]."' class='danger' onclick='return deleteclick();'><i class='os-icon os-icon-ui-15'></i></a>";
                                            echo"</td>"; 
                                        */
                                                        echo "</tr>";

                                                        //iteration
                                                        $b++;
                                                    }
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>






        <!---Add code here-->






    </div>

    <!-- Javascript -->

    <!-- Javascript -->
    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"></script>

    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-XXXXXXX-9', 'auto');
        ga('send', 'pageview');
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