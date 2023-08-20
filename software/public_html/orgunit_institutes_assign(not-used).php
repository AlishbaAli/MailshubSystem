<?php
ob_start();
session_start();
include 'include/conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
                <?php






                $orgunit_id = $_GET['orgunit_id'];


                $stmt = $conn->prepare("SELECT institute_name, grid_id FROM organizational_institutes
                WHERE orgunit_id=$orgunit_id ");
                $stmt->execute();
                $row = $stmt->fetchAll();

                // $stmt = $conn->prepare("SELECT Name, ID FROM tbl_institutes WHERE  
                // ID NOT IN(SELECT  grid_id FROM organizational_institutes
                // WHERE orgunit_id=$orgunit_id) LIMIT 10");
                // $stmt->execute();
                // $row2 = $stmt->fetchAll();
                
                ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>Search And Select Institutes for Organziations</h2>
                            </div>
                            <div class="body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <label></label>

                                        <form class="custom-validation" action="orgunit_institutes_assign_db.php" method="post" enctype="multipart/form-data">
                                        <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4">
                                        <label>Search Institue</label>
                                                    <div class="search-box">
                                                        <input type="text" id="uni" name="institute" placeholder="Search institute..." class="form-control" value="" >
                                                        <div class="result"></div>
                                                    </div>
                                                </div>
                                        <div class="col-lg-8 col-md-8">
                                        <label>Select Institute</label>
                                            <div class="multiselect_div">
                                              


                                                        <select name="grid_id[]" class="multiselect" id="multi-select-demo" multiple="multiple">
                                                    <?php foreach ($row as $output) { ?>
                                                        <option value="<?php echo $output['grid_id']; ?>" <?php echo ' selected="selected"'; ?>> <?php echo $output['institute_name']; ?>
                                                        </option>
                                                        
                                                    <?php
                                                    } ?>

                                                  
                                                </select>
                                                </div>
                                                </div>
                                                    <br><br>
                                                  
                                                </div>
                                                <input id="id" name="id" type="hidden" value="<?php echo $_GET['orgunit_id']; ?>">

                                                <div>
                                                    <br><br>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                        Assign
                                                    </button>

                                                </div>
                                        </form>
                                    </div>
                                </div>









                            </div>
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

    <script type="text/javascript">
        $(document).ready(function() {

 $('#multi-select-demo').multiselect({
        maxHeight: 300,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });

        
            $('.search-box input[type="text"]').on("keyup input", function() {
                /* Get input value on change */
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if (inputVal.length) {
                    $.get("ajax_university.php", {
                        term: inputVal
                    }).done(function(data) {

                        // Display the returned data in browser
                     
                        resultDropdown.html(data);


                    });
                } else {
                    resultDropdown.empty();
                }
            });

            // Set search input value on click of result item
            $(document).on("click", ".result p", function() {


                $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                $(this).parent(".result").empty();

                var str = document.getElementById("uni").value;
       


                $.ajax({
                        url: "ajax_university_info.php",
                        method: "POST",
                        data: {
                            str: str
                        },
                        dataType: "JSON",
                        success: function(data) {

        
                            $('#multi-select-demo').append($('<option></option>').attr('value', data.ID).text(data.Name));
                            $("#multi-select-demo").multiselect('destroy');
$("#multi-select-demo").multiselect();

                      

                        }


                    }

                )




            });


        });
    </script>
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