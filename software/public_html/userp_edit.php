<?php
ob_start();
session_start();
error_reporting(0);
ini_set('display_errors', 0);
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
    <?php include 'include/left_side_bar.php';
    if (isset($_GET['userid'])) {
      $AdminId = $_GET['userid'];
    }


    $query_admin = "SELECT * FROM admin where AdminId=:AdminId";
    $sql = $conn->prepare($query_admin);
    $sql->bindParam(':AdminId', $AdminId);
    $sql->execute();
    $row = $sql->fetch();

    ?>


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
        <div class="row clearfix">
          <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="card">
              <div class="body">
                <!-- onsubmit="return Validate(this);"
 -->


                <form onsubmit="return Validate(this);" class="advanced-form custom-validation" action="userp_edit.php" method="post">


                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">User Name :</span></span>
                    </div>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?php $username = $row['username'];
                                                                                                                echo $username; ?>" required>
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">User Email :</span></span>
                    </div>
                    <input type="email" id='useremail' name="useremail" class="form-control" placeholder="Enter useremail" value="<?php $useremail = $row['email'];
                                                                                                                                  echo $useremail; ?>" required>
                    <!-- <div id='uname_response'></div> -->

                    <!-- <div id='uname_response'></div> -->
                  </div>

                  <div class="form-group ">

                    <input hidden type="text" id='userid' name="userid" class="form-control" placeholder="Enter userid" value="<?php echo $AdminId; ?>" readonly>
                    <!-- <div id='uname_response'></div> -->

                    <!-- <div id='uname_response'></div> -->
                  </div>

                  <div class="form-group input-group mb-3">
                    <div id="uname_response"></div>

                  </div>


                  <!--  <div class="form-group input-group mb-3">   
        <div class="input-group-prepend">
            <span class="input-group-text "><span style="font-size:13px;">Password*:</span></span>
         </div>                                             
      <input type="password" class="form-control" id="userpassword" placeholder="Enter password" name="userpassword" value="<?php $userpass = $row[''];
                                                                                                                            echo '$username'; ?>"required>
        </div> -->


                  <!--  ----------- -->
                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">First Name :</span></span>
                    </div>
                    <input type="text" id='Fname' name='Fname' class="form-control" placeholder="Enter First Name" value="<?php $Fname = $row['Fname'];
                                                                                                                          echo $Fname; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Last Name:</span></span>
                    </div>
                    <input type="text" id='Lastname' name='Lastname' class="form-control" placeholder="Enter Last Name" value="<?php $Lastname = $row['Lastname'];
                                                                                                                                echo $Lastname; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Journal Title:</span></span>
                    </div>
                    <input type="text" id='j_title' name='j_title' class="form-control" placeholder="Enter Journal Title" value="<?php $j_title = $row['Journal_title'];
                                                                                                                                  echo $j_title; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Role:</span></span>
                    </div>
                    <input type="text" id='Role' name='Role' class="form-control" placeholder="Enter Role" value="<?php $Role = $row['Role'];
                                                                                                                  echo $Role; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Affliation:</span></span>
                    </div>
                    <input type="text" id='Affliation' name='Affliation' class="form-control" placeholder="Enter Affliation" value="<?php $affliation = $row['affliation'];
                                                                                                                                    echo $affliation; ?>">
                  </div>

                  <!-- <div class="form-group input-group mb-3">   
         <div class="input-group-prepend">
            <span class="input-group-text "><span style="font-size:13px;">Article Title:</span></span>
         </div>                                                  
        <input type="text" id='Article' name='Article' class="form-control" placeholder="Enter Article Title" value="<?php $article = $row['article_title'];
                                                                                                                      echo $article; ?>">
        </div> -->

                  <!-- <div class="form-group input-group mb-3">
             <div class="input-group-prepend">
            <span class="input-group-text "><span style="font-size:13px;">Eurekaselect URL:</span></span>
         </div>     
        <input type="text" id='URL' name='URL' class="form-control" placeholder="https://(EurekaSelect URL)" value="<?php $url = $row['eurekaselect_url'];
                                                                                                                    echo $url; ?>">
        </div> -->

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Address Line 1:</span></span>
                    </div>
                    <input type="text" id='Add1' name='Add1' class="form-control" placeholder="Enter Address Line 1" value="<?php $add1 = $row['Add1'];
                                                                                                                            echo $add1; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Address Line 2:</span></span>
                    </div>
                    <input type="text" id='Add2' name='Add2' class="form-control" placeholder="Enter Address Line 2" value="<?php $add2 = $row['Add2'];
                                                                                                                            echo $add2; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Address Line 3:</span></span>
                    </div>
                    <input type="text" id='Add3' name='Add3' class="form-control" placeholder="Enter Address Line 3" value="<?php $add3 = $row['Add3'];
                                                                                                                            echo $add3; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Address Line 4:</span></span>
                    </div>
                    <input type="text" id='Add4' name='Add4' class="form-control" placeholder=" Enter Address Line 4" value="<?php $add4 = $row['Add4'];
                                                                                                                              echo $add4; ?>">
                  </div>

                  <div class="form-group input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text "><span style="font-size:13px;">Country:</span></span>
                    </div>
                    <input type="text" id='Country' name='Country' class="form-control" placeholder="Enter Your Country" value="<?php $Country = $row['Country'];
                                                                                                                                echo $Country; ?>">
                  </div>

                  <!--  ------------- -->





                  <br>

                  <div class="form-group mb-0">
                    <div>


                      <button type="submit" name='submit' class="btn btn-primary waves-effect waves-light mr-1 ">
                        Submit </button>
                      <button type="reset" class="btn btn-secondary waves-effect"> Cancel </button>
                    </div>
                  </div>




                </form>

                <?php
                if (isset($_POST['submit'])) {

                  //$_SERVER["REQUEST_METHOD"] == "POST"

                  $username = trim($_POST["username"]);
                  $useremail = trim($_POST["useremail"]);
                  $AdminId = trim($_POST["userid"]);
                  $Fname = !empty($_POST['Fname']) ? trim($_POST['Fname']) : null;
                  $Lastname = !empty($_POST['Lastname']) ? trim($_POST['Lastname']) : null;
                  $Journal_title = !empty($_POST['j_title']) ? trim($_POST['j_title']) : null;
                  $Role = !empty($_POST['Role']) ? trim($_POST['Role']) : null;
                  //$URL= !empty($_POST['URL']) ? trim($_POST['URL']) : null;
                  $affliation = !empty($_POST['Affliation']) ? trim($_POST['Affliation']) : null;
                  //$Article= !empty($_POST['Article']) ? trim($_POST['Article']) : null;
                  $Add1 = !empty($_POST['Add1']) ? trim($_POST['Add1']) : null;
                  $Add2 = !empty($_POST['Add2']) ? trim($_POST['Add2']) : null;
                  $Add3 = !empty($_POST['Add3']) ? trim($_POST['Add3']) : null;
                  $Add4 = !empty($_POST['Add4']) ? trim($_POST['Add4']) : null;
                  $Country = !empty($_POST['Country']) ? trim($_POST['Country']) : null;

                  $query = $conn->prepare("SELECT email from admin where AdminId=:AdminId ");
                  $query->bindParam(':AdminId', $AdminId);
                  $query->execute();
                  $email = $query->fetch();
                  $u_email = $email["email"];



                  //Construct the SQL statement and prepare it.
                  $sql = "SELECT *
   FROM admin 
   WHERE (username = :username OR email = :email )and AdminId != :AdminId";

                  $stmt = $conn->prepare($sql);

                  //Bind the provided username to our prepared statement.
                  $stmt->bindValue(':username', $username);
                  $stmt->bindValue(':email', $useremail);
                  $stmt->bindParam(':AdminId', $AdminId);

                  //Execute. 
                  $stmt->execute();

                  //Fetch the row.
                  $row = $stmt->fetch(PDO::FETCH_ASSOC);

                  if ($stmt->rowcount() > 0) {
                    echo "<meta http-equiv='refresh' content='2; url=userp_edit.php?userid= " . $AdminId . "'>
  <p><span style='color: red;'><b> Email Already Addigned to another user</b>.</span></p>
  ";
                  } else {
                    echo $u_email;
                    echo $useremail;
                    if (trim($useremail) != trim($u_email)) {
                      $sql = $conn->prepare("UPDATE admin SET email_status ='Not Verified' where AdminId =:AdminId");
                      $sql->bindValue(':AdminId', $AdminId);
                      $sql->execute();
                    }
                    //Prepare our INSERT statement.
                    $sql = "UPDATE admin SET username=:username,
 email=:email, 
 added_by=:added_by, 
 Fname=:Fname, 
 Lastname=:Lastname, 
 
 Journal_title=:Journal_title, 
 Role=:Role,  
 affliation=:affliation, 
 Add1=:Add1, 
 Add2=:Add2, 
 Add3=:Add3, 
 Add4=:Add4, 
 Country=:Country where AdminId=:AdminId";

                    $stmt = $conn->prepare($sql);

                    //Bind our variables.
                    $stmt->bindValue(':username', $username);
                    // $stmt->bindValue(':passwordHash', $passwordHash);
                    $stmt->bindValue(':email', $useremail);
                    $stmt->bindValue(':added_by', $_SESSION["username"]);
                    $stmt->bindValue(':Fname', $Fname);
                    $stmt->bindValue(':Lastname', $Lastname);
                    $stmt->bindValue(':Journal_title', $Journal_title);
                    $stmt->bindValue(':Role', $Role);
                    // $stmt->bindValue(':article_title', $Article);
                    $stmt->bindValue(':affliation', $affliation);
                    // $stmt->bindValue(':url', $URL);
                    $stmt->bindValue(':Add1', $Add1);
                    $stmt->bindValue(':Add2', $Add2);
                    $stmt->bindValue(':Add3', $Add3);
                    $stmt->bindValue(':Add4', $Add4);
                    $stmt->bindValue(':Country', $Country);
                    $stmt->bindValue(':AdminId', $AdminId);

                    //Execute the statement and insert the new account.
                    if ($stmt->execute()) {
                      header("Location: add_user.php");
                      exit();
                    }
                  }
                }


                ?>


              </div>
            </div>
          </div>
        </div>

        <!---Add code here-->




      </div>
    </div>

  </div>

  <!-- Javascript -->

  <script src="assets/js/jquery.min.js"></script>

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
  <!-- Javascript -->


  <script src="../assets/vendor/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js -->


  <script src="assets/bundles/morrisscripts.bundle.js"></script>
  <script src="assets/js/pages/ui/dialogs.js"></script>

  <script>
    $("#useremail").keyup(function() {

      var email = $(this).val().trim();
      var userid = $("#userid").val().trim();
      if (email != '') {

        $.ajax({
          url: 'ajax_email.php',
          type: 'post',
          data: {
            email: email,
            userid: userid
          },
          success: function(response) {

            // Show response

            $("#uname_response").html(response);
            if (response == "<span style='color: red;'>Email already exists.</span>") {
              document.getElementById('useremail').value = ''
            }

          }
        });
      } else {
        $("#uname_response").html("");
      }

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
</body>

</html>