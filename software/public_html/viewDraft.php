<?php
// ob_start();
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'include/conn.php';

// if (!isset($_SESSION['AdminId'])) {
//     //User not logged in. Redirect them back to the login page.
//     header('Location: login.php');
//     exit;
// }
if (isset($_SESSION['AC'])) {
    if ($_SESSION['AC'] == "NO") {

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

   
    <!-- Overlay For Sidebars -->
    <!-- <div class="overlay" style="display: none;"></div> -->

    <!-- <div id="wrapper"> -->

        

        <!-- <div id="main-content"> -->
            <div class="container-fluid">
                <!-- <div class="block-header">
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
                </div> -->

                 <!---Add code here-->

                 <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <!-- <h3>ACTIONS</h3> -->
                        </div>
                        <div class="element-box">

                            <h5 class="form-header">

                                <?php

                                 if (isset($_GET['CampID'])) {
                                      $CampID =  $_GET['CampID']; }
                                    

                                     //--------------------------------
                                     if (isset($CampID)) {
                                    $stmt = $conn->prepare("SELECT draft_status as draft_type from campaign WHERE CampID  = :CampID");
                                    $stmt->bindValue(':CampID', $CampID);
                                    $stmt->execute();
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $draft_type = $result['draft_type'];

                                    //For Buttons;
                                   // $CampID =  $_GET['CampID'];
                                    echo "<input hidden type='text' id= 'CampID' name='CampID' value=$CampID >";

                                    $sql = "SELECT COUNT(CampName) AS num , CampName, CampID
												FROM campaign left join Campaign_type on Campaign_type.ctype_id = campaign.ctype_id
												WHERE CampID = :CampID";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindValue(':CampID', $CampID);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($row['num'] > 0) {
         
///------------------------------------    
                                        echo "Draft For <span style='color:#1740A5;'>" . $row["CampName"] . "</span> Campaign<br/><br/>";
                                        // echo "	<a  style='float:right' href='deleteDraft.php?CampID=" . $row["CampID"] . "'><button id='discard' onclick='return deleteall();' disabled='true' class='mr-2 mb-2 btn btn-danger'>Discard</button> </a>";
                                        // echo "	<a  style='float:right' href='nextVerifyAlert.php?CampID=" . $row["CampID"] . "'><button id='activity' disabled='true' class='mr-2 mb-2 btn btn-success'>Next Activity</button> </a>";
                                    } else {
                                        echo "Invalid Selection";
                                    }



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
//                                             $Draft = "
//                                                           <p id='Drafttest2' style='text-align: justify;'>
//                                                            Dear System Admin, <br>
// ".$draftalert."
//                                                            </p>
//                                                            ";

//                                             echo "<br/>" . "$Draft" . "<br/>";
                                            
 }
                                    
                                //         $get_status =  $conn->prepare("SELECT Camp_Status from campaign WHERE CampID  = :CampID");
                                //         $get_status->bindValue(':CampID', $CampID);
                                //         $get_status->execute();
                                //         $status =      $get_status->fetch();
                                //         if ($status["Camp_Status"] == "Pending Verification Alert") {

                                //             $pendingMsg     =     "<div id=alertmsg > <div id=first_alertmsg class='alert alert-info alert-dismissible' role='alert'>
                                //        <button  type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                //        <i class='fa fa-spinner fa-spin'></i> Email Verification Pending.. Kindly Wait.. <br>
                                //        Status will be changed with in 5 minutes
                                //    </div>
                                //    </div>";
                                //             echo $pendingMsg;
                                //         }
                                    }    

                                     //-------------------------------
$message_oaarticles="";


//==========================================================

$Header_Banner=" "; $Footer_Banner=" ";  $Campaign_Title =" ";
                    
// $CampID=$_GET['CampID'];
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

// This file upload.php fetch articles of a Campaign and form Article List in a variable names messagelist.            
include "upload.php";

$Article_List = $messagelist;
 ?>    
 <p id="test" hidden><?php  echo $Article_List;?></p> 
  <!-- id= test value is used in javascript to show article list .. do not remove it.-->        
                      <!-- my code -->
   
<?php 
$ctid=$ctype_idd;
$op="SELECT ctype_name,ct.ctype_id,component_name,ac.component_id,component_input_type ,requirement_status, cco.order_number, CampID
FROM `Campaign_type` as ct, components_for_campaign_type as cct, alert_components as ac , Campaigns_component_order as cco
where ct.ctype_id=cct.ctype_id and cct.component_id=ac.component_id and ct.ctype_id='16' and requirement_status != 'Not Required' and cco.component_id=ac.component_id 
and cco.CampID='$CampID' order by cco.order_number";
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

echo '<select hidden name= "sbTwo[]"  id= "sbTwo" class="form-control" multiple="multiple"  >';
           
foreach ($products as $output) { 
echo '<option selected value="'.$output.'"> '.$output.' </option>';

} 
echo '</select>';
///-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//===================================================================================================================================================================================

//---------------------------------

$message2 = "<table width='799' height='430' border='0' align='center'>";                       
foreach ($products as $output) {

    if ($output=="Header_Banner" || $output=="Header_Banner*") {
        $Header_Ban= "<img  src='https://mailshub.net/img/Header_Banner/" . $Header_Banner."'>";
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Header_Ban ."</p> </td>
        </tr>";
    }
    if ($output=="Footer_Banner" || $output=="Footer_Banner*") {
        $Footer_Ban = "<img  src='https://mailshub.net/img/Footer_Banner/" .$Footer_Banner ."'>";
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Footer_Ban ."</p> </td>
        </tr>";
    }
    if ($output=="Article_List" || $output=="Article_List*") {
        
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Article_List ."</p> </td>
        </tr>";
    }
    if ($output=="Campaign_Title" || $output=="Campaign_Title*") {
        $Camp_Title = "<h2>".$Campaign_Title."</h2>";
        $message2 = $message2 . " <tr>		
        <td>  <p class=''>" . $Camp_Title ."</p> </td>
        </tr>";
    }
    if ($output=="Draft" || $output=="Draft*") {
        
        $message2 = $message2 . " <tr>		
        <td>  <p class=''> <p>
        Dear {first_name} {last_name}, 
        </p>
        <p>". $draftalert . "</p> </p> </td>
        </tr>";
    }
 

 }

 echo $message2 = $message2 . "

<tr>
<td> <p class='' > </p> </td>	
</tr>	

</table>";
    // $iteration=1;
    //                                 $count="SELECT max(alert_iteration) as itr from camp_alerts where CampID=:CampID ";
    //                                 $count=$conn->prepare($count);
    //                                 $count->bindParam(":CampID",$CampID);
    //                                 $count->execute();
    //                                 $counts=$count->fetch(); 
    //                                 if($count->rowCount()>0){
    //                                     $iteration = $counts['itr']+1;
    //                                 }
                                    
                                    
    //                                 $message_oaarticles23=htmlentities(htmlspecialchars($message2));
    //                                 $insert="INSERT INTO `camp_alerts`( `CampID`, `camp_alert`, `alert_iteration`) 
    //                                 VALUES (:CampID ,:camp_alert , :itr)";
    //                                 $insert=$conn->prepare($insert);
    //                                 $insert->bindParam(":CampID",$CampID);
    //                                 $insert->bindParam(":camp_alert",$message_oaarticles23);
    //                                 $insert->bindParam(":itr",$iteration);
    //                                 $insert->execute();


} ?>

                            </h5>

                            <div class="row">

<div class="col-10">
    <div class="card">    


    <div class="col-md-12">

        <?php
        
       // echo"<br> <h1>DRAFT PREVIEW</h1><hr>";
    
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
  //echo $message_oaarticles; 
    
        //echo $alerts;
        ?>
       <a href="index.php"> <button id="OK" class="btn btn-primary float float-right" >OK</button> </a><br><br>
    </div>


</div>
</div>
</div>
                        </div>

                    </div>
                </div>

                <!---Add code here-->



            </div>
        <!-- </div> -->

    <!-- </div> -->

    <!-- Javascript -->

    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"> </script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>



    ----

    <script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js -->
    <script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
    <script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
    <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js -->



    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>





    <script>
  $(document).ready(function() { 

   $('#sbTwo option').prop('selected', true);
    var order_comp = $("#sbTwo").find(":selected").text();
 
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


});


// var conceptName = $('#aioConceptName').find(":selected").text();
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