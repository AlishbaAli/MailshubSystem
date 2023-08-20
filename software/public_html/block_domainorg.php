<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}
if (isset($_SESSION['BDO'])) {
  if ($_SESSION['BDO'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}
if (isset($_SESSION['domain_block_type']))  {
  if ($_SESSION['domain_block_type'] == "sys-defined")  {

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
                  <h3>Organizational Block Domain</h3>
                  <a class="btn btn-primary btn-lg waves-effect waves-light" href="block_domain_formorg.php"> Block Domain </a> <br><br>
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
                            Name
                          </th>
                          <th>
                            Organization
                          </th>
                          <th>
                            Owner
                          </th>
                          <th>
                            Type
                          </th>
                          <th>
                            Top Level Domain
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
                                `blocked_domains`
                            INNER JOIN blocked_domain_org INNER JOIN tbl_organizational_unit ON
                            blocked_domains.blocked_domain_id = blocked_domain_org.blocked_domain_id AND
                            tbl_organizational_unit.orgunit_id = blocked_domain_org.orgunit_id";

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



                              <td><?php echo $row["domain_name"]; ?></td>
                              <td><?php echo $row["orgunit_name"]; ?></td>
                              <td><?php echo $row["domain_owner"]; ?></td>
                              <td><?php echo $row["domain_type"]; ?></td>
                              <td><?php echo $row["top_level_domain"]; ?></td>
                              <td><?php echo $row["domain_status"]; ?></td>
                           
                              <td>
                                <a href="block_domainorg_edit.php?id=<?php echo $row["blocked_domain_orgid"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                               
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