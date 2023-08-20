<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ARE'])) {
    if ($_SESSION['ARE'] == "NO") {

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
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Reply To Emails Management(Manual)</h2>
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
                            <button type="button" id="triggerB" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Add_Reply_To_Email">Add Reply To Email</button> 
                            <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Reply_To_Email_For_Organizational_Unit">Reply To Email For Organizational Unit</button>
                            <!-- <button type="button" class="btn  btn-simple  btn-filter2" style="background-color:#344453; color: white;" data-target="Reply_To_Emails_For_Users">Reply To Emails For Users</button> -->
                              <br>
                              <br>
                              <br>
                              
                                <!-- <div id="wizard_horizontal"> -->
                                    <!-- <h2>Add Reply To Email</h2> -->
                                    <section id="Add_Reply_To_Email" data-status="Add_Reply_To_Email">
                                        <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">
                                                <form action="reply_to_email_db.php" method="post">

                                               
                                               <div  id="Email_exist_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> Email  already exists!
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>

                                               <div  id="blck_alert" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="mdi mdi-check-all mr-2"></i> 
                                                   Email blocked by system!<br>
                                                   
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                               </div>



                                              



                                                    <div class="form-group ">
                                                        <label>Reply To Email *</label>
                                                        <input name="reply_to_email" type="text" class="form-control email" placeholder="Ex: example@example.com" required>

                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Status</label>

                                                        <select name="rtem_status"  id="rtem_status" onClick=showHide() class="form-control" required>

                                                            <option value="Active"> Active</option>
                                                            <option value="In Active"> In Active</option>

                                                        </select>


                                                    </div>
                                                    <div class="form-group "  style="display: none" id="reason">
                                                        <label >Reason*</label>
                                                        <textarea name="reason" id="reasonr" class="form-control" rows="5" cols="30" ></textarea>

                                                    </div>


                                                    <div class="form-group ">

                                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                            Add
                                                        </button>


                                                    </div>








                                                    <br>


                                                </form>
                                            </div>
                                        </div>
                                        <!----------table----------->

                                        <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT * FROM reply_to_emails";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Reply To Email</th>
                                                        <th>Status</th>
                                                        <th> Reason To In Active</th>
                                                        <th>Date</th>
                                                        <th>Action</th>


                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php






                                                    while ($row = $stmt->fetch()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["reply_to_email"] . " "; ?></td>

                                                            <td  style="text-align: center;">
                                                            <?php if($row["rtem_status"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $row["rtem_status"] . " ";?> 
                                                           <?php } else if($row["rtem_status"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $row["rtem_status"] . " "?>

                                                           <?php }
                                                            
                                                            ?></td>
<?php if($row["rtem_status"]=="Active") {?>
    <td></td>
    <?php }else {?>

                    <td style="text-align: center;">
                    

                        <button id="<?php echo $row['rtemid']; ?>" value="<?php echo $row['rtem_reason']; ?>" class="btn btn-info" onclick="SendIdReason(<?php echo $row['rtemid']; ?>)" data-toggle="modal" data-target="#exampleModalCenter1">View</button>
                        <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                               
                                                                                <div class="modal-header">
                                                                           
                                                                                    <div class="alert alert-dark" role="alert">  Reason to In Active </div>
                                                                                  
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
    
                                                                                <div class="modal-body">
                                     
                                                                                      
                                                                                <div id="show_comment"></div>
                                                                                        
                                                                                </div>
    
                                                                            </div>
                                                                        </div>
                                                                    </div>
                       
                    </td>
                    <?php }?>

                                                            <td><?php echo $row["rtem_include_date"] . " "; ?></td>
                                                            <td  style="text-align: center;">

                                                                <a href="reply_to_email_edit.php?id=<?php echo $row["rtemid"]; ?>"> <button type="button" class="btn btn-info" title="Edit"><i class="fa fa-edit"></i></button></a>
                                                                <!-- <a href="delete_rtem.php?id=<?php echo $row["rtemid"]; ?>"><button type="button" data-type="confirm" onclick='return deleteclick();' class="btn btn-danger js-sweetalert" title="Delete"><i class="fa fa-trash-o"></i></button></a> -->

                                                            </td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->

                                        </div>
                                    </section>

                                    <!-- <h2>Reply To Emails For Organizational Unit</h2> -->
                                    <section id="Reply_To_Email_For_Organizational_Unit" data-status="Reply_To_Email_For_Organizational_Unit">



                                        <!----------table----------->

                                        <div class="table-responsive">
                                            <?php
                                            $sql = "SELECT d.system_entityid AS system_entityid, d.orgunit_name AS orgunit_name, d.orgunit_id AS orgunit_id,
                                      GROUP_CONCAT( DISTINCT rtemid SEPARATOR ',' ) AS reply_to_email FROM tbl_organizational_unit AS d 
                                      LEFT JOIN  tbl_orgunit_rte AS dr ON d.orgunit_id = dr.orgunit_id GROUP BY d.orgunit_id";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();

                                            ?>
                                            <!-- <table class="table center-aligned-table" > -->
                                   

                                           

                            <div class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-info-circle"></i> Select organization from the above dropdown to enable "Modify" button for that particular organization
                            </div>
                       
                                                
                                            <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>Organizational Unit Name</th>
                                                        <th>Reply To emails</th>
                                                        <th> Actiion </th>




                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php






                                                    while ($row = $stmt->fetch()) {
                                                            if($row['system_entityid']!=2){
                                                             continue;
                                                            }


                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row["orgunit_name"] . " "; ?></td>


                                                            <td><?php
                                                                if ($row["reply_to_email"] != "") {
                                                                    $remail_ids = $row["reply_to_email"];
                                                                    $remail_ids_arr = explode(",", $remail_ids);
                                                                    $res = "";
                                                                    foreach ($remail_ids_arr as $id) {
                                                                        $stmtr = $conn->prepare("SELECT reply_to_email FROM reply_to_emails WHERE rtemid =$id");
                                                                        $stmtr->execute();
                                                                        $rowr = $stmtr->fetch();

                                                                        $res .= $rowr["reply_to_email"] . " ,";
                                                                    }
                                                                    $final = "";
                                                                    $res = substr_replace($res, "", -1);

                                                                    echo wordwrap($res, 50, "<br>\n");
                                                                }   ?></td>


                                                            <td>

                                                            <?php if(isset($_SESSION['orgunit_id']) && $_SESSION['orgunit_id']== $row["orgunit_id"]){ ?> 
                                                                
                                                            <a class="btn btn-warning btn-sm" href="rtemid_dept_edit.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>">Modify</a>
                                                            <?php } else {?>
                                                                <a class="btn btn-warning btn-sm disabled" href="rtemid_dept_edit.php?orgunit_id=<?php echo $row["orgunit_id"]; ?>">Modify</a>

                                                            <?php }?>
                                                        
                                                        </td>

                                                        </tr>
                                                    <?php }


                                                    ?>

                                                </tbody>
                                            </table>

                                            <!--------------table---------->

                                    </section>



                                    <!-- <h2> Reply To Emails For Users</h2> -->
                                   




                                       <!-- <h2> Reply To Emails For Users</h2> -->
                                 





<!----------table----------->




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
    <script>
        function SendIdReason(id) {
            var a = document.getElementById(id).value;
            var p = "";
  
            p =
                "<p>" +
                a + " </p>";

            $("#show_comment").empty();
            $("#show_comment").append(p);
          }
    </script>
    <script>
    $(document).ready(function () {
        $('.star').on('click', function () {
            $(this).toggleClass('star-checked');
        });

        $('.ckbox label').on('click', function () {
            $(this).parents('tr').toggleClass('selected');
        });

        $('.btn-filter').on('click', function () {
            var $target = $(this).data('target');
            if ($target != 'all') {
                $('.table tr').css('display', 'none');
                $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
            } else {
                $('.table tr').css('display', 'none').fadeIn('slow');
            }
        });
        $('.btn-filter2').on('click', function () {
            var $target = $(this).data('target');
            if ($target != 'all') {
                $('section').css('display', 'none');
                $('section[data-status="' + $target + '"]').fadeIn('slow');
            } 
        });
        $("#triggerB").trigger("click");
    });
</script>
<script>
        function showHide() {


            var rtem_status = document.getElementById("rtem_status").value;

          

            if (rtem_status.trim() == 'In Active') {
               
                document.getElementById('reason').style.display = 'block'
                $('#reasonr').prop('required',true);


            
               
            } else {
              
                 document.getElementById('reason').style.display = 'none'
                 $('#reasonr').prop('required',false);


            }
        }
    </script>

    <script>
        function deleteclick() {
            return confirm("Do you want to Delete this Reply To Email?")
        }
    </script>


<script>
jQuery(function(){
    var $_GET = {};

document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

   
    if($_GET["E_exist"]=="true") {
        
        jQuery('#Email_exist_alert').show();
   
 
 
    }
    if($_GET["blck"]=="true") {
        
        jQuery('#blck_alert').show();
   
 
 
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





</html>