<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}
// if (isset($_SESSION['USUBM']))  {
//     if ($_SESSION['USUBM']=="NO")  {

//         //User not logged in. Redirect them back to the login page.
//         header('Location: page-403.html');
//         exit;
//     }
//     }
if (isset($_SESSION['unsubscription_type']))  {
    if ($_SESSION['unsubscription_type']=="sys-defined")  {
  
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
            <div class="col-lg-5 col-md-12 col-sm-12">
            </div>
            <div class="col-lg-12">
              <div class="card">
                <div class="header">
                  <h3>Organizational Unsubscriber List</h3>
                  <a class="btn btn-primary btn-lg waves-effect waves-light" href="orgexternal_unsubscribe.php"> Unsubscribe Email </a> <br><br>
                </div>
                <div class="element-box">



                  <!--------------------
                      START - Table with actions
                      -------------------->
                  <div class="table-responsive">
                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                      <thead class="text-center">

                        <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">

                          <th>
                            Full Name
                          </th>
                          <th>
                            Email Address
                          </th>
                          <th>
                            Organization
                          </th>
                          <th>
                            System Date
                          </th>
                          <th>
														Internal Date
													</th>
													<th>
														External Date
													</th>
                          <th>
                            Type
                          </th>
                          <th>
                            Status
                          </th>
                          <th>
                            Action
                          </th>
                        </tr>

                      </thead>
                      <tbody class="text-center">
                        <tr>
                          <?php

                          $sql = "SELECT
                                *

                            FROM
                                `unsubscriber`
                            INNER JOIN orgunit_unsubscriber INNER JOIN tbl_organizational_unit ON
                            unsubscriber.UnsubscribeID = orgunit_unsubscriber.UnsubscribeID AND
                            tbl_organizational_unit.orgunit_id = orgunit_unsubscriber.orgunit_id";

                          $stmt = $conn->prepare($sql);
                          $result = $stmt->execute();

                          if ($stmt->rowCount() > 0) {
                            $result = $stmt->fetchAll();

                            foreach ($result as $row) { 

                              //filter org specific
                              if(isset($_SESSION["orgunit_id"])){

                                if($_SESSION["orgunit_id"]!= $row["orgunit_id"]){
                                  continue;

                                }

                              }
                              
                              ?>



                              <td><?php echo $row["FirstName"] . " " . $row["LastName"]; ?></td>
                              <td><?php echo $row["UnsubscriberEmail"]; ?></td>
                              <td><?php echo $row["orgunit_name"]; ?></td>
                              <td><?php echo $row["UnsubscribeDateTime"]; ?></td>
                              <td><?php echo $row["internal_add_date"]; ?></td>
															<td><?php echo $row["external_add_date"]; ?></td>
                              <td><?php echo $row["Type"]; ?></td>
                              <td><?php echo $row["Status"]; ?></td>
                              <td>
                                <a href="editorgUnsubscriber.php?id=<?php echo $row["orgunit_unsubscriber_id"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                <?php if (isset($_SESSION['DU'])) {
                                  if ($_SESSION['DU'] == "YES") { ?>
                                    <a href="deleteorgUnsubscriber.php?id=<?php echo $row["orgunit_unsubscriber_id"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a>
                                <?php }
                                } ?>
                              </td>

                        </tr>
                    <?php  }
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

  </div>

  <!-- Javascript -->

  <script>
    function deleteclick() {
      return confirm("Do you want to delete this Unsubcriber Email?")
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