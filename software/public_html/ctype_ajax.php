<?php 
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';
include 'functions.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit();
}

//echo"here";

if (isset($_POST['ead'])) {
   // echo $_POST['ead'];
    if (!empty($_POST['ead'])) {
        $orgunit_id= $_SESSION['orgunit_id'];
        $camp_id="";
        if (isset($_POST['camp_id'])) {
            if (!empty($_POST['camp_id'])) {
               $camp_id=$_POST['camp_id'];
               $values = "SELECT * from campaign left join tbl_orgunit_user on tbl_orgunit_user.ou_id = campaign.ou_id where CampID='$camp_id' ";
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
               $orgunit_id = $values['orgunit_id'];
            }

        }

       

         $ctid=$_POST['ead'];
        $op="SELECT ctype_name,ct.ctype_id,component_name,ac.component_id,component_input_type ,requirement_status
        FROM `Campaign_type` as ct, components_for_campaign_type as cct, alert_components as ac 
        where ct.ctype_id=cct.ctype_id and cct.component_id=ac.component_id and ct.ctype_id='$ctid' and requirement_status != 'Not Required' order by input_type_order_id";
        $op=$conn->prepare($op);
        $op->execute();
        $pids=$op->fetchAll(); 

        
   
    $i=1; $products=[];
    foreach ($pids as $pid) {
        $ctype_name=$pid['ctype_name'];
        $component_name=$pid['component_name'];
        $products[].=trim($component_name);
    }

?>
                        
<?php
    
    echo"<div class='row' id='hide_show'>";
       foreach ($pids as $pid) {
           $value="";
           $ctype_name=$pid['ctype_name'];
           $component_name=$pid['component_name'];
           $products[].=trim($component_name);


           $component_id=$pid['component_id'];
           $component_input_type=$pid['component_input_type'];
           $requirement_status=$pid['requirement_status'];

if($component_name=="Draft"){
    

    if (!empty($camp_id)) {
        //echo $_GET['CampID'];
        $CampID =  $camp_id;

        $sql = "SELECT COUNT(CampName) AS num ,  ou_id, CampName, campaign.CampID, subscription_draft,draft_subject
        FROM campaign left join draft on draft.CampID = campaign.CampID
        WHERE campaign.CampID = :CampID";
        $stmt = $conn->prepare($sql);
        //Bind the provided username to our prepared statement.
        $stmt->bindValue(':CampID', $CampID);
        //Execute.
        $stmt->execute();
        //Fetch the row.
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $ou_id= $row['ou_id'];
     
        $stmt_get_ouid= $conn->prepare("SELECT tbl_organizational_unit.orgunit_id, orgunit_code FROM 	tbl_orgunit_user INNER JOIN
        tbl_organizational_unit ON tbl_organizational_unit.orgunit_id= tbl_orgunit_user.orgunit_id AND  ou_id='$ou_id' LIMIT 1");
        $stmt_get_ouid->execute();
        $org_id=   $stmt_get_ouid->fetch();
        //If the provided username already exists - display error.
        if ($row['num'] > 0) {
         
$cmp_draft = html_entity_decode($row['subscription_draft']);
                            $cmp_draft=html_entity_decode($cmp_draft);
                            $draft_sub = html_entity_decode($row['draft_subject']);
                            $draft_sub=html_entity_decode($draft_sub);
                            ///////////
                            $sql_get = "SELECT DISTINCT
                            Journal_title,
                            article_title
                            FROM
                            campaingauthors 
                            WHERE
                            CampID=:CampID";
                            $camp_id = $row['CampID'];
                            $stmt_get = $conn->prepare($sql_get);
                            $stmt_get->bindValue(':CampID', $camp_id);
                            $stmt_get->execute();
                            $result_get = $stmt_get->fetch();

if (!empty($result_get['article_title'])) { 
$article_title = trim($result_get['article_title']);
} else{ $article_title ="{article_title}"; } if (!empty($result_get['Journal_title'])) { 
$Journal_title = trim($result_get['Journal_title']);
} else{ $Journal_title ="{Journal_title}"; } 

                            $Draft_tags = ["{article_title}", "{Journal_title}"];

                            $DB_Rows   = [$article_title, $Journal_title];
                            $cmp_draft_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                            $cmp_draft_sub_new_app = str_replace($Draft_tags, $DB_Rows, $draft_sub);
                            $message_app = "<html>
                          </body><div style=' width:85%; text-align: justify;'>
                        $cmp_draft_new_app
                            </div>
                            </body>
                          </html>";
                          $message_subject_app = $cmp_draft_sub_new_app;

                          $value=$message_app.'explode@here'.$message_subject_app;

                 } else
          
            echo "invalid Selection";
        //die();
    }
    
}

echo"<div class='' >";
rich_textbox($component_name,$requirement_status,$component_input_type,$component_id,$value);

echo"</div>";

         echo"<div class='col-6'>";

         if (isset($values[$component_name])) {
            $value = $values[$component_name];
            }
     
           simple_textbox($component_name,$requirement_status,$component_input_type,$component_id, $value);
           selection_dropdown($component_name,$requirement_status,$component_input_type,$component_id,$orgunit_id,$camp_id) ;
          

          //  echo"</div> <div class='col-6'>";
          
           img_upload($component_name,$requirement_status,$component_input_type,$component_id,$value);
          
           
echo"</div>";
if (!isset($_POST['camp_id'])) {
    upload_csv($component_name,$requirement_status,$component_input_type,$component_id);

}

?>
     
    <?php  
    }  $i++; //foreach component 
    echo" </div>";

    ?>

                       

    
    <?php
    //  echo ' <script> ';
    // echo ' $( "#right" ).click(function() {
    //   var value = $("#sbTwo").find(":selected").text();
    //   alert("change");
    //   $( "#sbTwotest" ).text( value );
    // })
    // .change(); ';
    // echo '</script>';
 } // not empty ctypeid



   
} // isset ctypeid

?>

