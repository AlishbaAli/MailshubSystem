<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
} ?>
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
                <?php

                if ($_SERVER["REQUEST_METHOD"] == "POST") {





                    $username = trim($_POST["username"]);
                    $useremail = trim($_POST["useremail"]);
                    $userpassword = trim($_POST["userpassword"]);
                    $Fname = !empty($_POST['Fname']) ? trim($_POST['Fname']) : null;
                    $Lastname = !empty($_POST['Lastname']) ? trim($_POST['Lastname']) : null;
                    $Journal_title = !empty($_POST['j_title']) ? trim($_POST['j_title']) : null;
                    $Role = !empty($_POST['Role']) ? trim($_POST['Role']) : null;
                    // $URL= !empty($_POST['URL']) ? trim($_POST['URL']) : null;
                    $affliation = !empty($_POST['Affliation']) ? trim($_POST['Affliation']) : null;
                    // $Article= !empty($_POST['Article']) ? trim($_POST['Article']) : null;
                    $Add1 = !empty($_POST['Add1']) ? trim($_POST['Add1']) : null;
                    $Add2 = !empty($_POST['Add2']) ? trim($_POST['Add2']) : null;
                    $Add3 = !empty($_POST['Add3']) ? trim($_POST['Add3']) : null;
                    $Add4 = !empty($_POST['Add4']) ? trim($_POST['Add4']) : null;
                    $Country = !empty($_POST['Country']) ? trim($_POST['Country']) : null;






                    //Construct the SQL statement and prepare it.
                    $sql = "SELECT COUNT(username) AS num 
   FROM admin 
   WHERE username = :username OR email = :email";

                    $stmt = $conn->prepare($sql);

                    //Bind the provided username to our prepared statement.
                    $stmt->bindValue(':username', $username);
                    $stmt->bindValue(':email', $useremail);

                    //Execute. 
                    $stmt->execute();

                    //Fetch the row.
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row['num'] > 0) {
        //                 echo "<div class='alert alert-danger' role='alert'>
        //    <b>Record Already Exist</b>.
        //  </div>
        //  <meta http-equiv='refresh' content='2; url=add_user.php'>
        //  ";
        //                 die();

        //my code

        $UN_alert= "true";

        header("Location:add_user.php?UN_alert={$UN_alert}");
        exit();
      


                    } else {

                        //Prepare our INSERT statement.
                        // $sql = "UPDATE admin
                        //        SET Fname=:Fname, Lastname=:Lastname, article_title=:article_title,
                        //        Journal_title=:Journal_title, Role=:Role, affliation=:affliation,
                        //        eurekaselect_url=:url, Add1=:Add1, Add2=:Add2,
                        //        Add3=:Add3, Add4=:Add4, Country=:Country

                        //        WHERE AdminId = :username

                        //        ";

                        // //echo $sql."<br/>";
                        // //die();

                        // $stmt = $conn->prepare($sql);

                        // //Bind our variables.
                        // $stmt->bindValue(':username', $_SESSION['AdminId']);
                        // $stmt->bindValue(':Fname', $Fname);
                        // $stmt->bindValue(':Lastname', $Lastname);
                        // $stmt->bindValue(':Journal_title', $Journal_title);
                        // $stmt->bindValue(':Role', $Role);
                        // $stmt->bindValue(':article_title', $Article);
                        // $stmt->bindValue(':affliation', $affliation);
                        // $stmt->bindValue(':url', $URL);
                        // $stmt->bindValue(':Add1', $Add1);
                        // $stmt->bindValue(':Add2', $Add2);
                        // $stmt->bindValue(':Add3', $Add3);
                        // $stmt->bindValue(':Add4', $Add4);
                        // $stmt->bindValue(':Country', $Country);



                        //Execute the statement and insert the new account.
                        $result = $stmt->execute();
                        //Hash the password as we do NOT want to store our passwords in plain text.
                        $passwordHash = password_hash($userpassword, PASSWORD_BCRYPT, array("cost" => 12));

                        //Prepare our INSERT statement.
                        $sql = "INSERT INTO admin (username, password, email,added_by,Fname, Lastname, affliation, Journal_title, Role,  Add1, Add2, Add3, Add4, Country) 
       VALUES (:username, :passwordHash, :email, :added_by, :Fname, :Lastname, :affliation, :Journal_title, :Role, :Add1,:Add2,:Add3,:Add4,:Country)";

                        //echo $sql."<br/>";
                        //die();

                        $stmt = $conn->prepare($sql);

                        //Bind our variables.
                        $stmt->bindValue(':username', $username);
                        $stmt->bindValue(':passwordHash', $passwordHash);
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
                        //Execute the statement and insert the new account.
                        if ($stmt->execute()) {
                            $last_id = $conn->lastInsertId();
                          
                         


                            
                            ?>

                            


                            <form method="post" action="send_uemail.php" onsubmit="return submitUserForm();">

                                <input type="hidden" name="uid" id="uid" value="<?php echo $last_id; ?>">
                                <br>

                                <button hidden type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary">Send</button>

                            </form>
                            <script src="assets/js/jquery.min.js"></script>
                            <script>
                                $(document).ready(function() {
                                    $('#submit').trigger('click');
                                });
                            </script>



                <?php
                            // header("Location: add_user.php");
                            // exit();


                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Javascript -->
   

    <script>
        function deleteclick() {
            return confirm("Do you want to Delete Campaign?")
        }
    </script>
    <script>
        function SendId(id) {

            document.getElementById('uid').value = id;

        }
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        var recaptcha_response = '';

        function submitUserForm() {
            if (recaptcha_response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This field is required.</span>';
                return false;
            }
            return true;
        }

        function verifyCaptcha(token) {
            recaptcha_response = token;
            document.getElementById('g-recaptcha-error').innerHTML = '';
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




    <script src="assets/bundles/datatablescripts.bundle.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>

    <script src="assets/vendor/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js -->



    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>


    <script src="assets/vendor/editable-table/mindmup-editabletable.js"></script> <!-- Editable Table Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/editable-table.js"></script>
</body>

</html>