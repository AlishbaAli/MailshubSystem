<?php 
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'include/conn.php';
?>
<!doctype html>
<html lang="en">

<head>
<title>:: Mailshub :: Change Password</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Mplify Bootstrap 4.1.1 Admin Template">
<meta name="author" content="ThemeMakker, design by: ThemeMakker.com">

<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/vendor/animate-css/animate.min.css">
<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet" href="assets/css/color_skins.css">
</head>

<body class="theme-blue">
    <?php 
if (isset($_POST['submit'])) {


             
$username = !empty($_POST['username']) ? trim($_POST['username']) : null;
$email = !empty($_POST['email']) ? trim($_POST['email']) : null;           
$newpassword = !empty($_POST['newpassword']) ? trim($_POST['newpassword']) : null;
$confirmpassword = !empty($_POST['confirmpassword']) ? trim($_POST['confirmpassword']) : null;



if ($newpassword == $confirmpassword) {
                                                            //Retrieve the account information for the given username.
$sql = "SELECT  `AdminId`,`username`, `email`
FROM `admin`
WHERE username = :username";    
$stmt = $conn->prepare($sql);
$stmt->bindValue(':username', $username);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
 $email = $row["email"];
 $AdminId = $row["AdminId"];

$passwordHash = password_hash($newpassword, PASSWORD_BCRYPT, array("cost" => 12));

                                                         //Prepare our INSERT statement.

                                                         
$sql = "UPDATE admin
        SET password= :passwordHash WHERE
        AdminId= :AdminId
        ";

 $stmt1 = $conn->prepare($sql);
 $stmt1->bindValue(':passwordHash', $passwordHash);
 $stmt1->bindValue(':AdminId', $AdminId);
if( $stmt1->execute()){


$stmt2 = $conn->prepare("UPDATE admin
SET email_status='Verified', 
status='Active'
 WHERE AdminId = :AdminId");
  $stmt2->bindValue(':AdminId', $AdminId);

if($stmt2->execute()){
      //pass entry to admin_email table
        //pass entry to admin_email table

        $stmt3 = $conn->prepare("INSERT INTO admin_email (AdminId, emails, email_type, emails_status) 
        VALUES (:AdminId, :emails, 'Primary', 'Verified')");

        $stmt3->bindValue(':AdminId', $last_id);
        $stmt3->bindValue(':emails', $useremail);
        $stmt3->execute();

    header("Location: logout.php");
    exit();
 
}
}

 }
//  else{
//   echo "<p><span style='color: red;'><b>New password and Confirm Password doesn't match</b>.</span></p>";
//   }
 }
                                                                     ?>

    
    <!-- WRAPPER -->
    <div id="wrapper">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle auth-main">
                <div class="auth-box">
                    <div class="mobile-logo"><a href="index.php"><img src="assets/images/logo-icon.svg" alt="Mplify"></a></div>
                    <div class="auth-left">
                        <div class="left-top">
                            <a href="index.php">
                                <img src="assets/images/logo-icon.svg" alt="Mplify">
                                <span>Mailshub</span>
                            </a>
                        </div>
                        <div class="left-slider">
                            <img src="assets/images/security.jpg" width="300" height="250" alt="">
                        </div>
                    </div>
                    <div class="auth-right">
                        <div class="right-top">
                            <ul class="list-unstyled clearfix d-flex">
                                <li><a href="index.php"><i class="fa fa-home"></i></a></li>
                                <!-- <li><a href="javascript:void(0);">Help</a></li>
                                <li><a href="javascript:void(0);">Contact</a></li> -->
                            </ul>
                        </div>
                        <div class="card">
                            <div class="header">
                                <!-- <p class="lead">Error<span class="text">403</span></p>
                                <span>Forbiddon Error!</span> -->
                            </div>
                            <div class="body">
                             
                            <form id="advanced-form1" data-parsley-validate action="pw_reset.php" method="post" novalidate>
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12">
                                                            <h6>Change Password</h6>
                                                            <?php
    

                                                            $email= $_GET['email'];
                                                            $stmte = $conn->prepare( "SELECT `username`
                                                            FROM `admin`
                                                            WHERE email = :email");
                                                            $stmte->bindValue(':email', $email);
                                                            $stmte->execute();
                                                            $rowe = $stmte->fetch(PDO::FETCH_ASSOC);
                                                            $username = $rowe["username"];
                                                             ?>
                                                                                                   
                                                           
                                                                    <!-- <label for="newpassword"> New Password</label> -->
                                                                    <input  hidden  name="email"   value="<?php echo $email?>">

                                                                  

                                                           
                                                            <div class="form-group">
                                                                <div class="input-group mb-3">
                                                                    <!-- <label for="newpassword"> New Password</label> -->
                                                                    <input  readonly name="username"  class="form-control" value="<?php echo $username?>">

                                                                 

                                                                </div>
                                                            </div>
                                                        

                                                            <div class="form-group">
                                                                <div class="input-group mb-3">
                                                                    <!-- <label for="newpassword"> New Password</label> -->
                                                                    <input required type="password" class="form-control" id="newpassword" placeholder="Enter new password" name="newpassword" aria-describedby="basic-addon2">

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text " id="basic-addon2"> <i toggle="#newpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i> </span>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="input-group mb-3">
                                                                    <!--  <label for="confirmpassword"> Confirm Password</label> -->
                                                                    <input required type="password" class="form-control" id="confirmpassword" placeholder="Confirm new password" name="confirmpassword" aria-describedby="basic-addon3">

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text " id="basic-addon3"> <i toggle="#confirmpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></i> </span>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <button class="btn btn-primary " id="submit" type="submit" name="submit" value="true">Reset Password</button>

                                                            <!--   data-toggle="modal" data-target=".bd-example-modal-sm" -->
                                                        </div>



                                                    </div>
                                                </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
   <!-- END WRAPPER -->
    <script src="assets/js/jquery.min.js"></script>


<script>
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye-slash fa-eye ");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
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

