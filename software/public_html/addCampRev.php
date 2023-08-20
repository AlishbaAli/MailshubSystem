<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
              <h2>Add Campaign</h2>
            </div>
            <div class="col-lg-7 col-md-4 col-sm-12 text-right">
              <ul class="breadcrumb justify-content-end">
                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ul>
            </div>
          </div>
        </div>

        <?php
        $sql = "SELECT * FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll();

        $smt_dec = $conn->prepare("SELECT * FROM `data_extraction_categories` where dec_status = 'active' and dec_journal_status = 'active'");
        $smt_dec->execute();
        $dec_count = $smt_dec->rowCount();
        $data_dec = $smt_dec->fetchAll();


        $stmts = $conn->prepare("SELECT system_wise_embargo, status, use_date FROM system_setting WHERE status ='Active' and use_date= (SELECT max(use_date) FROM system_setting)");
        $stmts->execute();
        $swe = $stmts->fetch();

        $admin_id = $_SESSION['AdminId'];
        if (isset($_SESSION['orgunit_id'])) {
          $orgunit_id = $_SESSION['orgunit_id'];
          $sql = "SELECT mailservers.mailserverid, vmname FROM `mailserver-orgunit` INNER JOIN mailservers 
                                  ON `mailserver-orgunit`.`mailserverid`= mailservers.mailserverid AND  orgunit_id= $orgunit_id";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $mailservers = $stmt->fetchAll();


          $stmtrr = $conn->prepare("SELECT tbl_role_privilege.role_prev_id
                                  FROM tbl_role_privilege INNER JOIN tbl_user_role_prev
                                  ON tbl_role_privilege.role_prev_id = tbl_user_role_prev.role_prev_id AND restriction_level=0 AND tbl_user_role_prev.user_id = $admin_id  ");
          $stmtrr->execute();
          if ($stmtrr->rowCount() > 0) {
            $sql = "SELECT
                                      ro.rtemid AS rtemid
                                  FROM
                                      tbl_organizational_unit AS o
                                  LEFT JOIN tbl_orgunit_rte AS ro
                                  ON
                                      o.orgunit_id = ro.orgunit_id
                                  WHERE
                                      o.orgunit_id = $orgunit_id AND ro.rtemid NOT IN(
                                  SELECT
                                          rtemid
                                  FROM
                                          campaign
                                 WHERE
                                 Camp_Status != 'Completed')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            while ($rep_to_emails_u = $stmt->fetch()) {

              $rep_to_emails[] .= $rep_to_emails_u["rtemid"];
            }
          } else {

            $sql = "SELECT  r.rtemid AS rtemid FROM admin AS u LEFT JOIN tbl_user_rte AS r ON u.AdminId = r.user_id WHERE u.AdminId= $admin_id
                                AND rtemid NOT IN(SELECT rtemid FROM campaign WHERE Camp_Status!='Completed')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            while ($rep_to_emails_u = $stmt->fetch()) {

              $rep_to_emails[] .= $rep_to_emails_u["rtemid"];
            }
          }
        }



        ?>

        <!---Add code here-->





        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <form class="custom-validation" action="addCampRev_db.php" method="post">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="CampName">Campaign Name:</label>
                      <div class="input-group mb-3">
                        <input type="text" id="CampName" name="CampName" class="form-control" placeholder="Enter Campaign Name" aria-label="Enter Campaign Name" aria-describedby="basic-addon2" required>
                      </div>
                      <label for="rep_to_emails">Reply-to Email:</label>
                      <div class="input-group mb-3">
                        <select id="rep_to_emails" name="rep_to_emails" class="form-control" required>
                          <option value="" disabled selected>Please Select Reply-To-Email</option>
                          <?php foreach ($rep_to_emails as $rtem) {
                            $stmtr = $conn->prepare("SELECT rtem_status, reply_to_email FROM reply_to_emails WHERE rtemid =:rtemid AND rtem_status='Active'");
                            $stmtr->bindValue(':rtemid', $rtem);
                            $stmtr->execute();
                            $rowr = $stmtr->fetch();
                            if ($rowr['reply_to_email'] == "") {
                              continue;
                            }
                          ?>
                            <option value="<?php echo $rtem ?>"> <?php echo $rowr['reply_to_email'] ?> </option>
                          <?php
                          } ?>
                        </select>
                      </div>
                      <label for="mailservers">Mail Server:</label>
                      <div class="input-group mb-3">
                        <select name="mailservers" id="mailservers" class="form-control" required>
                          <option value="" disabled selected>Please Select Mail Server</option>
                          <?php foreach ($mailservers as $output) { ?>
                            <option value="<?php echo $output["mailserverid"]; ?>"> <?php echo $output["vmname"]; ?> </option>
                          <?php
                          } ?>
                        </select>
                      </div>
                      <label for="products">Products:</label>
                      <div class="input-group mb-3">
                        <select name="products" class="form-control" required="required">
                          <option value="">Select Journal</option>
                          <?php

                          $smt = $conn->prepare("SELECT * FROM `journal`  WHERE 
                                                      journal_status='unexport' ORDER BY `journal`.`jcode` ASC");
                          $smt->execute();
                          $data = $smt->fetchAll();
                          foreach ($data as $row) : ?>
                            <option value="<?= $row["journal_id"] ?>"><?= $row["jcode"] . " - " . $row["journal_name"] ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                      <label for="CampCat">Campaign Category:</label>
                      <div class="input-group mb-3">
                        <select id="CampCat" name="CampCat" class="form-control">
                          <option value="" disabled selected>Please Select Category</option>
                          <?php foreach ($data_dec as $output) { ?>
                            <option value="<?php echo $output["dec_id"]; ?>"> <?php echo $output["dec_name"]; ?> </option>
                          <?php
                          } ?>

                        </select>
                      </div>
                      <label for="CampType">Campaign Type:</label>
                      <div class="input-group mb-3">
                        <select id="CampType" name="CampType" onChange="showHideType()" class="form-control">
                          <option value="manual" selected> Manual</option>
                          <option value="automatic"> Automatic</option>
                        </select>
                      </div>



                    </div>
                    <div class="col-lg-6">
                      <label for="CampFor">Campaign Objective:</label>
                      <div class="input-group mb-3">
                        <textarea placeholder="Campaign For *" id="CampFor" name="CampFor" class="form-control" required></textarea>
                      </div>
                      <label for="CampDate">Campaign Date:</label>
                      <div class="input-group mb-3">
                        <input type="date" id="CampDate" name="CampDate" class="form-control" placeholder="Enter Campaign Date" aria-label="Enter Campaign Date" aria-describedby="basic-addon2">
                      </div>
                      <div class="input-group mb-3">
                        <label class="fancy-radio" style="font-weight:700"><input name="format" value="format1" type="radio"><span><i></i>Format 1 (Journal_title, Role, Fname, Lastname, affiliation, Country, email, article_title, eurekaselect_url)</span></label>
                      </div>
                      <div class="input-group mb-3">
                        <label class="fancy-radio" style="font-weight:700"><input name="format" value="format2" type="radio" checked><span><i></i>Format 2 (INI, FNAME, LNAME, Add1, Add2, Add3, Add4, Country, email)</span></label>
                      </div>
                      <label for="embargo_type">Embargo Type:</label>
                      <div class="input-group mb-3">
                        <select id="embargo_type" onChange=showHide() name="embargo_type" class="form-control">
                          <option value="system_wise_embargo"> System Wise Embargo</option>
                          <option value="campaign_embargo"> Campaign Wise Embargo</option>
                        </select>
                      </div>
                      <label style="display: none" for="embargo_type" id="CET">Embargo Duration (Days):</label>
                      <div class="input-group mb-3">
                        <select style="display: none" id="CE" name="CE" class="form-control">
                          <option value="7"> 7</option>
                          <option value="15"> 15</option>
                          <option value="30" selected> 30</option>
                          <option value="45"> 45</option>
                          <option value="60"> 60</option>
                          <option value="90"> 90</option>
                        </select>
                      </div>
                      <input hidden type="text" name="CED" value="<?php echo $swe['system_wise_embargo'] ?>">
                    </div>





                    <div id="auto1" style="display:none">
                      <div class="card">
                        <div class="card-body">
                          <h5>Country Filter Selection: (<span style="color:red">Blocked lists</span> and <span style="color:green">Allowed lists</span>)</h5>
                          <table border="1" width="100%">
                            <tr>
                              <td style="padding:10px; vertical-align:top; width:60%;">
                                <?php
                                $smt_cf = $conn->prepare("SELECT * FROM `country_filter` where cf_status = 'active' and cf_journal_status = 'active'");
                                $smt_cf->execute();

                                $data_cf = $smt_cf->fetchAll();

                                $c = "0";
                                foreach ($data_cf as $cf) {
                                  $c++;
                                  if ($cf['cf_type'] == 'negative') {
                                    $color = "red";
                                  } else {
                                    $color = "green";
                                  }
                                  echo "<label class=" . chr(34) . "radio-inline" . chr(34) . " style=" . chr(34) . "color:$color" . chr(34) . ">";
                                  if ($c == 1) {
                                    echo "<input type=" . chr(34) . "radio" . chr(34) . " name=" . chr(34) . "countryfilter" . chr(34) . " value=" . chr(39) . "{ " . chr(34) . "cf_code" . chr(34) . ": " . chr(34) . $cf['cf_code'] . chr(34) . ", " . chr(34) . "cf_type" . chr(34) . ": " . chr(34) . $cf['cf_type'] . chr(34) . " }" . chr(39) . " checked=" . chr(34) . "checked" . chr(34) . " required=" . chr(34) . "required" . chr(34) . "/> " . $cf['cf_name'];
                                  } else {
                                    echo "<input type=" . chr(34) . "radio" . chr(34) . " name=" . chr(34) . "countryfilter" . chr(34) . " value=" . chr(39) . "{ " . chr(34) . "cf_code" . chr(34) . ": " . chr(34) . $cf['cf_code'] . chr(34) . ", " . chr(34) . "cf_type" . chr(34) . ": " . chr(34) . $cf['cf_type'] . chr(34) . " }" . chr(39) . " required=" . chr(34) . "required" . chr(34) . "/> " . $cf['cf_name'];
                                  }

                                  echo "</label>";
                                  echo "<br/>";

                                  $cf_id = $cf['cf_id'];
                                }
                                ?>
                              </td>

                            </tr>
                          </table>

                        </div>
                      </div>

                    </div>



                  </div>
                  <!---row end------->




                  <br><br>
                  <button type="submit" name="addCampaign" class="btn btn-primary"><i class="fa fa-plus"></i> Add Campaign</button>


              </div>
            </div>
          </div>


          <div id="auto2" style="display:none">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                </div>



                <?php
                echo "<table frame=" . chr(34) . "border" . chr(34) . " width=" . chr(34) . "100%" . chr(34) . ">";
                $smt_cf = $conn->prepare("SELECT * FROM `country_filter` where cf_status = 'active' and cf_journal_status = 'active'");
                $smt_cf->execute();

                $data_cf = $smt_cf->fetchAll();

                $c = "0";
                echo "<tr>";
                echo "<td style=" . chr(34) . "padding:10px; vertical-align:top; width:100%;" . chr(34) . ">";
                foreach ($data_cf as $cf) {
                  $c++;
                  $cf_id = $cf['cf_id'];
                  if ($c <> 1) {
                    echo "<div id=" . chr(34) . $cf['cf_code'] . chr(34) . " class=" . chr(34) . $cf['cf_code'] . " box" . chr(34) . " style=" . chr(34) . "display:none;" . chr(34) . ">";
                  } else {
                    echo "<div id=" . chr(34) . $cf['cf_code'] . chr(34) . " class=" . chr(34) . $cf['cf_code'] . " box" . chr(34) . ">";
                  }

                  echo "<h6>" . $cf['cf_name'] . "</h6>";

                  $smt_acf = $conn->prepare("SELECT apps_countries.id, apps_countries_filter.cf_id, country_filter.cf_code, country_filter.cf_type, apps_countries.country_name, apps_countries.country_code, apps_countries_filter.acf_select_status
                                    FROM apps_countries, country_filter, apps_countries_filter
                                    WHERE apps_countries.id = apps_countries_filter.id
                                    AND country_filter.cf_id = apps_countries_filter.cf_id
                                    AND acf_status = 'active'
                                    AND apps_countries_filter.cf_id = $cf_id
                                    ORDER BY apps_countries_filter.cf_id, apps_countries.country_code, apps_countries.country_name");
                  $smt_acf->execute();
                  $data_acf = $smt_acf->fetchAll();

                  $a = 0;
                  echo "<table style=" . chr(34) . "width:100%;" . chr(34) . ">";
                  echo "<tr>";
                  foreach ($data_acf as $row_acf) {
                    $a++;
                    echo "<td style=" . chr(34) . "padding-left:15px; padding-right:15px; padding-top:3px; padding-bottom:3px; vertical-align:top;" . chr(34) . ">";
                    echo "<label class=" . chr(34) . "radio-inline" . chr(34) . ">";
                    echo "<input type=" . chr(34) . "checkbox" . chr(34) . " id=" . chr(34) . $row_acf['cf_code'] . chr(34) . " name=" . chr(34) . $row_acf['cf_code'] . "[]" . chr(34) . " value=" . chr(34) . $row_acf['country_name'] . chr(34) . " " . $row_acf['acf_select_status'] . "=" . chr(34) . $row_acf['acf_select_status'] . chr(34) . ">";
                    echo " " . $row_acf['country_name'];
                    echo "</label>";
                    echo "</td>";
                    if ($a % 4 == 0) {
                      echo "<tr></tr>";
                    }
                  }
                  echo "</tr>";
                  echo "</table>";

                  echo "</div>";
                }
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                ?>


              </div>








            </div>
          </div>
















          </form>

        </div>
        <!--main row end-->







        <!-- end row -->






      </div>
    </div>

  </div>
  <script src="assets/vendor/jquery/jquery.js"></script>
  <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
  <script>
    function showHide() {


      var embargo_type = document.getElementById("embargo_type").value;

      if (embargo_type == "campaign_embargo") {
        document.getElementById('CE').style.display = 'block'
        document.getElementById('CET').style.display = 'block'
      } else {
        document.getElementById('CE').style.display = 'none'
        document.getElementById('CET').style.display = 'none'
      }
    }
  </script>
  <script type="text/javascript">
    $('#keyword').one('change', function() {
      $('#keyBtn').prop('disabled', false);
      $("#jouBtn").attr('disabled', 'disabled');
      //$('#jouBtn').addattr('disabled');
    });
  </script>
  <script>
    $('button[name="Reset"]').click(function() {
      location.reload();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('input[name="countryfilter"]').click(function() {
        var obj = JSON.parse($(this).attr("value"));
        var inputValue = obj.cf_code;
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
      });
    });
  </script>
  <script>
    function showHideType() {

      var type = document.getElementById("CampType").value;

      if (type == "automatic") {
        $('#auto1').show();
        $('#auto2').show();

      }
      if (type == "manual") {
        $('#auto1').hide();
        $('#auto2').hide();

      }

    }
  </script>

  <script src="assets/bundles/libscripts.bundle.js"></script>
  <script src="assets/bundles/vendorscripts.bundle.js"></script>

  <script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> <!-- Bootstrap Colorpicker Js -->
  <script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js -->
  <script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
  <script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
  <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
  <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js -->
  <script src="assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js -->

  <script src="assets/bundles/mainscripts.bundle.js"></script>
  <script src="assets/bundles/morrisscripts.bundle.js"></script>
  <script src="assets/js/pages/forms/advanced-form-elements.js"></script>

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