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
if (isset($_SESSION['CO'])) {
	if ($_SESSION['CO'] == "NO") {
  
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
                            <h2>Campaign Component Order</h2>
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
                                <!-- <div id="wizard_horizontal"> -->
                                    <!-- <h2>Add User</h2> -->

                                    
                                <br>
                                <?php
                               
                                                       $CampID=$_GET['CampID'];
                                                       //$CampID=89;

                                                        $values = "SELECT * from campaign left join tbl_orgunit_user on tbl_orgunit_user.ou_id = campaign.ou_id where CampID='$CampID' ";
                                                        $values = $conn->prepare($values);
                                                        $values->execute();
                                                        $values = $values->fetch();

                                                        $ctype_idd = $values['ctype_id'];
                                                        $camp_name = $values['CampName'];
                                                        $camp_id = $values['CampID'];
                                                        $rtemid = $values['rtemid'];
                                                        $mailserverid = $values['mailserverid'];
                                                        $Camp_category = $values['Camp_category'];
                                                        $CampFor = $values['CampFor'];
                                                        $Campaign_Title = $values['Campaign_Title'];
                                                        $Header_Banner = $values['Header_Banner'];
                                                        $Footer_Banner = $values['Footer_Banner'];
                                                        $ou_id = $values['ou_id'];
                                                        $orgunit_id = $values['orgunit_id'];
                                                        $embargo_type= $values['embargo_type'];
                                                        $campaign_embargo_days= $values['campaign_embargo_days'];
include "upload.php";

  $Article_List = $messagelist;
                                                        ?>    
                                                        <p id="test" hidden><?php  echo $Article_List;?></p> 
                                                         <!-- id= test value is used in javascript to show article list .. do not remove it.-->        
                                                                             <!-- my code -->


    <?php 
    $article_title = 'article_title';
    $Journal_title = 'Journal_title';
    $first_name='Fname';
    $last_name='Lastname';
                        

                                $sql_get = "SELECT
                                
                                    c.CampName,
                                    c.mailserverid,
                                    d.subscription_draft
                                FROM
                                    campaign c, draft d
                                WHERE
                                d.CampID = c.CampID AND
                                    c.CampID = :CampID";
                                    $stmt_get = $conn->prepare($sql_get);
                                    $stmt_get->bindValue(':CampID', $CampID);
                                    $result_get = $stmt_get->execute();
                                    if ($stmt_get->rowCount() > 0) {
                                        $result_get = $stmt_get->fetchAll();


                                        foreach ($result_get as $row_get) {

                                    

                                            $Draft_tags = ["{article_title}", "{Journal_title}"];


                                            $cmp_draft = html_entity_decode($row_get['subscription_draft']);
                                            $DB_Rows   = [$article_title, $Journal_title];
                                            $cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);

                                            $draftalert=$cmp_draft_new; 
                                            ?>
                                            <div id='Drafttest' hidden style='text-align: justify;' >
                                           
                                                   
                                                        <p>
                                                        Dear System Admin, 
                                                        </p>
                                                        <p>
                                                        <?php echo $draftalert; ?>
                                                        </p>
                                                    
                                           </div> 
                                                          
<?php 


   }
 }  // Loop for draft queries end here   

 $ctid=$_GET['ctype_id'];
 $op="SELECT ctype_name,ct.ctype_id,component_name,ac.component_id,component_input_type ,requirement_status
 FROM `Campaign_type` as ct, components_for_campaign_type as cct, alert_components as ac 
 where ct.ctype_id=cct.ctype_id and cct.component_id=ac.component_id and ct.ctype_id='$ctid' and requirement_status != 'Not Required' 
 and ct.ctype_id='$ctid' order by input_type_order_id";
 $op=$conn->prepare($op);
 $op->execute();
 $pids=$op->fetchAll(); 

 

$i=1; $products=[];
foreach ($pids as $pid) {
 $ctype_name=$pid['ctype_name'];
 $component_name=$pid['component_name'];
 $requirement_status=$pid['requirement_status'];
 $component_id=$pid['component_id'];
 
 if($requirement_status=='Required'){
    $component_name=$component_name.'*';
 }

 $products[].=trim($component_name);
}

?>
                                    <!-- <h2>Assign Organization and Roles </h2> -->
                                    <section id="Assign_Campaign_Components" data-status="Assign_Campaign_Components">
                             
                                        <div style="width:800px; margin:0 auto;" class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="demo-masked-input">
                                                <form class="custom-validation" action="components_order_db.php" method="post">

                                                    <div class="form-group ">
                                                        <label>Campaign Type *</label>

                                                      <input name="ctype_name" readonly type="text" class="form-control" value="<?php echo  $ctype_name; ?>">

                                                      <input name="ctype_id" hidden type="text" class="form-control" value="<?php echo  $ctid; ?>">
                                                      <input name="CampID" hidden type="text" class="form-control" value="<?php echo  $CampID; ?>">
                                                      <input name="orgunit_id" hidden type="text" class="form-control" value="<?php echo  $orgunit_id; ?>">
                                                        <br />
                                                    </div>
                        
                        <div class="form-group ">
                         <!-- <label>Assign Components Order *</label>  -->
                        <div class="row">
                        <div class="col-5">
                        <label>Available Components</label>

                        <select name= "sbOne"  id= "sbOne" class="form-control" multiple="multiple"  >
                      
                        <?php foreach ($products as $output) { ?>
                        <option value="<?php  echo $output; ?>"> <?php  echo $output; ?> </option>
                        <?php
                        } ?>
                        </select>

                        </div>
           
                        <div class="col-2">
                        <div class="col-4"></div>
                        <div class="col-4">
                        <label>.</label><br>
                 
                 <input type="button"  class="btn btn-secondary waves-effect" id="leftall" value="<" /> <br><br>
                 <input type="button"  class="btn btn-secondary waves-effect" id="rightall" value=">" /></br><br>

                        </div>
                        <div class="col-4"></div>
                       
   
                       </div>


                        <div class="col-5">
                        <label>Components by Order</label>

                       <select name= "sbTwo[]"  id= "sbTwo" class="form-control" multiple="multiple" required >
                       </select>

                        </div>

                        <div class="col-lg-1" >
                        <input type="button"  class="btn btn-primary waves-effect float float-left" id="preview" value="Set Order" /></br><br>
                        <input type="submit"  hidden class="btn btn-primary waves-effect float float-left" id="submit" name="submit" value="submit" /></br><br>
                        </div>
                      
                        </div>
                      
                        </div> 


                                                </form>

                                            </div>
                                        </div>


                                    </section>


                                    <!----------table----------->





                                </div>
                            </div>
                        </div>
                    </div>

                                            
                            <div class="row">

                            <div class="col-10">
                                <div class="card">    


                                <div class="col-md-12">

                                    <?php
                                    echo"<br> <h1>DRAFT PREVIEW</h1> <b> <i> Please select components and set order of components to preview Draft here. </i> </b> <hr>";
                                
                                    // $order1="Header_Banner";
                                    // $order2="B45";
                                    // $order3="Campaign_Title";
                                    $message = "<table width='799' height='430' border='0' align='center'>

                            <tr>		
                            <td>  <p class='order0'><img  id='order0' ></p> </td>
                            </tr>
                            <tr>		
                            <td>  <p class='order1' id='order1'>  </p> </td>
                            </tr>
                            <tr>		
                            <td>  <p class='order2' id='order2'>  </p> </td>
                            </tr>

                            <tr>	
                            <td align='center'><span style='font-size:14px;text-align: justify;'>	  <p  class='order3' id=''> </p>	</td>
                            </tr>  
                            <tr>		
                            <td>  <p class='order4' >  </p> </td>
                            </tr>

                            ";
                                    $message_oaarticles = $message . "

                            <tr>
                            <td> <p class='order5' > <img id=''  /></p>
                            <!--<img src='https://benthamarticlealerts.com/img/footer.jpg' />-->
                            </td>	
                            </tr>	

                            </table>";
                             if(empty(trim($message_oaarticles))){ echo "<b> <i> Please select components and set order of components to preview Draft here. </i> </b> "; } else { echo $message_oaarticles; }  
                                
                            
                                    ?>

                                    <button id="OK" class="btn btn-primary float float-right" >OK</button> <br><br>
                                    <!-- <?php  
                                    // $iteration=1;
                                    // $count="SELECT max(alert_iteration) as itr from camp_alerts where CampID=:CampID ";
                                    // $count=$conn->prepare($count);
                                    // $count->bindParam(":CampID",$CampID);
                                    // $count->execute();
                                    // $counts=$count->fetch(); 
                                    // if($count->rowCount()>0){
                                    //     $iteration = $counts['itr']+1;
                                    // }
                                    
                                    
                                    // $message_oaarticles=htmlentities($message_oaarticles);
                                    // $insert="INSERT INTO `camp_alerts`( `CampID`, `camp_alert`, `alert_iteration`) 
                                    // VALUES (:CampID ,:camp_alert , :itr)";
                                    // $insert=$conn->prepare($insert);
                                    // $insert->bindParam(":CampID",$CampID);
                                    // $insert->bindParam(":camp_alert",$message_oaarticles);
                                    // $insert->bindParam(":itr",$iteration);
                                    // $insert->execute();
                                    
                                    ?> -->
                                  
                                </div>


                            </div>
                            </div>
                            </div>






                </div> <!-- row clear fix-->


                <!---Add code here-->




            </div>
        </div>

    </div>

    <!-- Javascript -->
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
   

  
    <script>
        function deleteclick() {
            return confirm("Do you want to Delete this?")
        }
    </script>

    <script>
        $('#OK').on('click', function () {
          
           
            $("#submit").trigger("click");
        });
    </script>


 

    <!-- <script>
        function displayRoles() {
            var orgunit_id = document.getElementById("org_list").value;



            $.ajax({
                    url: "ajax_roles.php",
                    method: "POST",
                    data: {
                        orgunit_id: orgunit_id
                    },
                    dataType: "JSON",
                    success: function(data) {


                        $('#optgroup').empty();

                        for (var i in data) {


                            $("#optgroup").append('<option value="' + data[i].role_prev_id + '">' + data[i].role_prev_title + '</option>');
                            $('#optgroup').multiSelect('refresh');


                        }

                    }


                }

            )

        }
    </script> -->


    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>
    <script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js -->
    <script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
    <script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
    <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js -->
    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->


    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>
    <!-- <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script> -->
     <!-- my code -->

     <script>
        // $("#upload_csv_form").hide();
        $(document).ready(function() {
            $('#upload_csv_form').on("change", function(e) {

                // e.preventDefault(); //form will not submitted  
                $.ajax({
                    url: "upload.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false, // The content type used when sending data to the server.  
                    cache: false, // To unable request pages to be cached  
                    processData: false, // To send DOMDocument or non processed data file it is set to false  
                    success: function(data) {
                        if (data == 'Error1') {
                            alert("Invalid File");
                        } else if (data == "Error2") {
                            alert("Please Select File");
                        } else if (data == "Success") {
                            alert("CSV file data has been imported");
                            $('#upload_csv_form')[0].reset();
                        } else {
                            $('#Article_List').html(data);
                        }
                    }
                })
            });
        });
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
         $('#optgroup').multiSelect();
$('#select-all').click(function(){
  $('#optgroup').multiSelect('select_all');
  return false;
});
$('#deselect-all').click(function(){
  $('#optgroup').multiSelect('deselect_all');
  return false;
});
// List with handle
Sortable.create(listWithHandle, {
  handle: '.glyphicon-move',
  animation: 150
});
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

   
    if($_GET["UN_alert"]=="true") {
        
        jQuery('#UN_exist_alert').show();
   
 
 
    }


 
});
</script>
     <!-- my code -->
     <script src="assets/vendor/jquery/jquery.dragoptions.min.js" type="text/javascript" language="javascript"></script>
    <script>
        $(document).ready(function(){
            $("#optgroup, #select-single, #sbTwo, #sbOne").dragOptions({
                highlight: '=> ',
                onDrag: function(){
                    console.log('onDrag callback: ', this);
                },
                onChange: function(){
                    console.log('onChange callback: ', this);
                }
            });
        });
    </script>

     <script src="index.js"></script>
   

                         <script>
$(function () { function moveItems(origin, dest) {
    $(origin).find(':selected').appendTo(dest);
}
 
function moveAllItems(origin, dest) {
    $(origin).children().appendTo(dest);
}
 
$('#left').click(function () {
    moveItems('#sbTwo', '#sbOne');
});
 
$('#right').on('click', function () {
    moveItems('#sbOne', '#sbTwo');
   
});

$('#sbTwo').dblclick(function () {
    moveItems('#sbTwo', '#sbOne');
});
 
$('#sbOne').on('dblclick', function () {
    moveItems('#sbOne', '#sbTwo');
   
});
// $("#hide_show").hide();

$('#preview').click(function () {

   $('#sbTwo option').prop('selected', true);
    var order_comp = $("#sbTwo").find(":selected").text();
    //alert(order_comp);
      $( "#sbTwotest" ).text( order_comp );
      const myArray = order_comp.split("  ");
// THIS loop removes the predefined inner html in the order classes
      for (let i = 0; i < 6; i++) {
        document.getElementsByClassName("order"+i)[0].innerHTML = "";

      }
// this loop makes inner html in all the order classes orderwise.. arraa has the components name in order as enteres by user.
for (let i = 0; i < myArray.length; i++) {
var order="order"+i; //alert(order);
const element = document.getElementById(myArray[i].trim().replace('*',''));
//alert(element); /* this part remove the element if it already exist with component name id */
if(element){
    element.remove();
}

    if ( myArray[i].trim() == "Header_Banner" || myArray[i].trim() == "Footer_Banner" || myArray[i].trim() == "Footer_Banner*" || myArray[i].trim() == "Header_Banner*" ) {
        bannername=myArray[i].trim().replace('*','');
        if(bannername=="Header_Banner"){
            var  banner="<?php echo $Header_Banner; ?>";   
        } else {
       var  banner="<?php echo $Footer_Banner; ?>";
        }
        g = document.createElement('img');
        g.setAttribute("id", ""+myArray[i].trim().replace('*','')+"");
        g.setAttribute("src", "img/"+myArray[i].trim().replace('*','')+"/"+banner);
        document.getElementsByClassName("order"+i)[0].innerHTML = "";
        document.getElementsByClassName("order"+i)[0].appendChild(g);
       

} else if (myArray[i].trim() == "Campaign_Title") {
    var title="<?php echo $Campaign_Title; ?>";
    // alert(title);
    f = document.createElement('h2');
        f.setAttribute("id", myArray[i].trim());
        document.getElementsByClassName("order"+i)[0].innerHTML = "";
        document.getElementsByClassName("order"+i)[0].appendChild(f);
        $( "#"+myArray[i].trim() ).text(title);
 

} else if (myArray[i].trim().replace('*','') == "Article_List") {
    // const node="<?php // echo $Article_List; ?>";
    let html = document.getElementById("test").innerHTML;
    
        f = document.createElement('p');
        f.setAttribute("id", myArray[i].trim());
        document.getElementsByClassName("order"+i)[0].appendChild(f);
        // document.getElementById(myArray[i].trim()).appendChild(node);
document.getElementById(myArray[i].trim()).innerHTML = html;

} else if (myArray[i].trim().replace('*','') == "Draft") {
   
   let html = document.getElementById("Drafttest").innerHTML;
  
       f = document.createElement('p');
       f.setAttribute("id", myArray[i].trim());
       document.getElementsByClassName("order"+i)[0].appendChild(f);
       // document.getElementById(myArray[i].trim()).appendChild(node);
document.getElementById(myArray[i].trim()).innerHTML = html;

}
 
else {
    f = document.createElement('p');
        f.setAttribute("id", myArray[i].trim());
        document.getElementsByClassName("order"+i)[0].appendChild(f);
}
 
};




$("#hide_show").show();
$("#upload_csv_form").show();




});

$('#leftall').on('click', function () {
    moveAllItems('#sbTwo', '#sbOne');
});
 
$('#rightall').on('click', function () {
    moveAllItems('#sbOne', '#sbTwo');
});
});


// var conceptName = $('#aioConceptName').find(":selected").text();
</script> 
    <?php
     echo ' <script> ';
    echo ' $( "#right" ).click(function() {
      var value = $("#sbTwo").find(":selected").text();
      alert("change");
      $( "#sbTwotest" ).text( value );
    })
    .change(); ';
    echo '</script>';



?>



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