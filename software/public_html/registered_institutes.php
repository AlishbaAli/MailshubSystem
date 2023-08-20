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
if (isset($_SESSION['RI'])) {
	if ($_SESSION['RI'] == "NO") {
  
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
        <?php include 'include/nav_SA.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->


        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Institute Registration And Deletion</h2>
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

                
                ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>Search And Register Institutes</h2>
                            </div>
                            <div class="body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <label></label>

                                        <form class="custom-validation" action="registered_institutes_db.php" method="post" enctype="multipart/form-data">
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
                                                </select>
                                            </div>
                                        </div>
                                                    <br><br>
                                                  
                                        </div>
                                                

                                                <div>
                                                    <br><br>
                                                    <button type="submit" name="register" class="btn btn-primary waves-effect waves-light mr-1">
                                                        Register
                                                    </button>

                                                </div>
                                        </form>
                                    </div>
                                </div>









                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>Search And Delete Registered Institutes</h2>
                            </div>
                            <div class="body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                        <label></label>

                                        <form class="custom-validation" action="registered_institutes_db.php" method="post" enctype="multipart/form-data">
                                        <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4">
                                        <label>Search Institue</label>
                                                    <div class="search-box">
                                                        <input type="text" id="reg_uni" name="institute" placeholder="Search registered institute..." class="form-control" value="" >
                                                        <div class="reg_result"></div>
                                                    </div>
                                                </div>
                                        <div class="col-lg-8 col-md-8">
                                        <label>Select Institute</label>
                                            <div class="multiselect_div">
                                            

                                                <select name="reg_grid_id[]" class="multiselect" id="multi-select-demo1" multiple="multiple">
                                                </select>
                                            </div>
                                        </div>
                                                    <br><br>
                                                  
                                        </div>
                                                

                                                <div>
                                                    <br><br>
                                                    <button type="submit" name="del"class="btn btn-danger waves-effect waves-light mr-1">   Delete <i class="fa fa-trash-o"></i>
                                                    </button>

                                                </div>
                                        </form>
                                    </div>
                                </div>









                            </div>
                        </div>
                    </div>
                </div>





                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h2>Registered Institutes</h2>
                            </div>
                            <div class="body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12">
                                    <div class="table-responsive">
                                    <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Institute Name</th>
                                                        <th>Domain</th>
                                                        <th>Registration Date</th>
                                                        <th> Actiion </th>




                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php

                                            $sql = "SELECT registered_institutions.ri_id,institute_name, GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domain, registered_institutions.system_date FROM`registered_inst_domains` RIGHT JOIN registered_institutions ON 
                                            registered_institutions.ri_id = registered_inst_domains.ri_id     
                                          GROUP BY institute_name";
                                            
                                                  $stmt = $conn->prepare($sql);
                                                  $stmt->execute();




                                                    while ($row = $stmt->fetch()) {
                                                         


                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["institute_name"] . " "; ?></td>


                                                            <td><?php
                                                            $res= $row['domain'];
                                                          

                                                                    echo wordwrap($res, 50, "<br>\n");
                                                                  ?>
                                                                  </td>
                                                                  <td><?php echo $row["system_date"] . " "; ?></td>

                                                            <td>

                                                         
                                                                
                                                            <a class="btn btn-warning btn-sm" href="registered_institutes_domain.php?ri_id=<?php echo $row["ri_id"]; ?>">Modify Domain</a>
                                                    
                                                         
                                                        </td>

                                                        </tr>
                                                    <?php }


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



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>
    <script src="assets/vendor/editable-table/mindmup-editabletable.js"></script> <!-- Editable Table Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/editable-table.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>

    <script src="index.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

 $('#multi-select-demo').multiselect({
        maxHeight: 300,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });

    $('#multi-select-demo1').multiselect({
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

        
                            $('#multi-select-demo').append($('<option></option>').attr('value', data.ID).attr('selected', 'selected').text(data.Name));
                            $("#multi-select-demo").multiselect('destroy');
$("#multi-select-demo").multiselect();

                      

                        }


                    }

                )




            });


            $('.search-box input[type="text"]').on("keyup input", function() {
                /* Get input value on change */
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".reg_result");
                if (inputVal.length) {
                    $.get("ajax_reg_institute.php", {
                        term: inputVal
                    }).done(function(data) {

                        // Display the returned data in browser
                     
                        resultDropdown.html(data);


                    });
                } else {
                    resultDropdown.empty();
                }
            });

            $(document).on("click", ".reg_result p", function() {


$(this).parents(".search-box").find('input[type="text"]').val($(this).text());
$(this).parent(".reg_result").empty();

var str = document.getElementById("reg_uni").value;



$.ajax({
        url: "ajax_reg_inst_info.php",
        method: "POST",
        data: {
            str: str
        },
        dataType: "JSON",
        success: function(data) {


            $('#multi-select-demo1').append($('<option></option>').attr('value', data.grid_id).attr('selected', 'selected').text(data.institute_name));
            $("#multi-select-demo1").multiselect('destroy');
$("#multi-select-demo1").multiselect();

      

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