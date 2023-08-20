<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
 <br><h4><b>Rejected Campaigns</b></h4><br>
                                        <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>CAMPAIGN NAME</th>
                                                        <th>CAMPAIGN DATE</th>
                                                        <th>REJECTION REASON</th>

                                                        <th>ACTIONS</th>
                                                       <!--  <th>ACTIVITY</th> -->

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
if (isset($_SESSION['orgunit_id'])) {
    $orgunit_id = $_SESSION['orgunit_id']; 
    // echo $orgunit_id;
 }
  
       
$sql = "SELECT campaign.CampID, max(`rejected_iteration`) as rejected_iteration
                                                    
FROM campaign  left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id left JOIN camp_draft  on camp_draft.CampID=campaign.CampID  
where campaign.Camp_Status='Rejected' and rejected_iteration != '0' and crtem_status != 'In Active' and ou_status='Active'

                                                     ";
$u_role="SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role=$conn->prepare($u_role); $user_role->execute(); $user_role=$user_role->fetch();

 if($user_role['role_prev_id']!='1' && $user_role['role_prev_id']!='2' && $user_role['role_prev_id']!='8')
                                           
 { $sql.="AND user_id= '$admin_id'"; }

 if(!empty(trim($orgunit_id)) && $orgunit_id!= null )

 { $sql.="AND orgunit_id= '$orgunit_id'"; }
 $sql.=" group by camp_draft.CampID";

                                                    $stmt = $conn->prepare($sql);
                                                    // $stmt->bindValue(':AdminID', $admin_id);
                                                    $stmt->execute();
                                                    
                                                    while ($row1=$stmt->fetch()){
                                                        if(!empty($row1['CampID'])){

                                                        $cid=$row1['CampID']; //echo $cid;
                                                        $rit=$row1['rejected_iteration']; //echo $rit;
                                                   
                                                        $sql2 = "SELECT campaign.CampID,CampName,rejected_iteration,  Campaign_type.ctype_id,
                                                        CampDate,reason, camp_draft.subscription_draft as rej_draft, camp_draft.draft_subject as cdsub
                                                         
                                                         FROM camp_draft 
                                                         INNER JOIN campaign on camp_draft.CampID=campaign.CampID  
                                                         and campaign.Camp_Status='Rejected' and rejected_iteration != '0'
                                                         and campaign.CampID= '$cid' and rejected_iteration= (SELECT max(rejected_iteration) from camp_draft where camp_draft.CampID= '$cid')
                                                         left join Campaign_type on Campaign_type.ctype_id = campaign.ctype_id
                                                         group by campaign.CampID";
    
                                                        $stmt2 = $conn->prepare($sql2);
                                                       // $stmt2->bindValue(':AdminID', $admin_id);
                                                        $result = $stmt2->execute();
    
                                                        if ($stmt2->rowCount() > 0) {
    
                                                            $resultcamp = $stmt2->fetchAll();
    
                                                            foreach ($resultcamp as $row) {
                                                        
    
                                                        $CampID=$row["CampID"];
                                                        // $sql_act = "SELECT *
                                                        // FROM activity
                                                        // Where CampID = :CampID";
    
                                                        // $stmt_act = $conn->prepare($sql_act);
                                                        // $stmt_act->bindValue(':CampID',  $CampID);
                                                        // // $stmt_act->bindValue(':AdminID', $admin_id);
                                                        // $stmt_act->execute();
                                                        // $result_act = $stmt_act->fetch();
                                                        // ---------------------------------------
                                                        // ---------------------------------------
                                                        $cmp_draft = html_entity_decode($row['rej_draft']);
                                                            $cmp_draft=html_entity_decode($cmp_draft);

                                                            $cmp_draft_sub = html_entity_decode($row['cdsub']);
                                                           
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
                                                            
    
    
                                                            if ($stmt_get->rowCount()>0) {
                                                                $result_get = $stmt_get->fetch();
             if (!empty(trim($result_get['article_title']))) { 
        
                                                                    $article_title = trim($result_get['article_title']);
            } else{ $article_title ="Article_title"; } 
            
        
        
        
        if (!empty(trim($result_get['Journal_title']))) { 
        
                                                                $Journal_title = trim($result_get['Journal_title']);
        } else{ $Journal_title ="Journal_title"; }  } else{ $article_title ="article_title";  $Journal_title ="Journal_title"; } 
    
                                                            $Draft_tags = ["{article_title}", "{Journal_title}"];
    
    
                                                            $DB_Rows   = [$article_title, $Journal_title];
                                                            $cmp_draft_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                                                            $cmp_draft_sub_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft_sub);
                                                            $message_app = "<html>
                                                          </body>
                                                          Subject: $cmp_draft_sub_new_app
                                                            <div style=' width:85%; padding:20px;text-align: justify;'>
                                                            <p>Dear System Admin,</p>
                                                            <p style='text-align:justify;'>$cmp_draft_new_app</p>
                                                            </div>
                                                            </body>
                                                          </html>";
    
    
                                                          // ---------------------------------------
                                                        // ---------------------------------------
    //                                                     $cmp_draft = html_entity_decode($row['sub_draft']);
    //                                                         $cmp_draft=html_entity_decode($cmp_draft);

    //                                                         $cmp_draft_sub = html_entity_decode($row['dsub']);
    //                                                         ///////////
    
    //                                                         $sql_get = "SELECT DISTINCT
    //                                                         Journal_title,
    //                                                         article_title
    //                                                         FROM
    //                                                         campaingauthors 
    //                                                         WHERE
    //                                                         CampID=:CampID";
    //                                                         $camp_id = $row['CampID'];
    //                                                         $stmt_get = $conn->prepare($sql_get);
    //                                                         $stmt_get->bindValue(':CampID', $camp_id);
    //                                                         $stmt_get->execute();
                                                          
    
    
    // if ($stmt_get->rowCount()>0) {
    //                                                         $result_get = $stmt_get->fetch();
    //      if (!empty(trim($result_get['article_title']))) { 
    
    //                                                             $article_title = trim($result_get['article_title']);
    //     } else{ $article_title ="{article_title}"; } 
        
    
    
    
    // if (!empty(trim($result_get['Journal_title']))) { 
    
    //                                                         $Journal_title = trim($result_get['Journal_title']);
    // } else{ $Journal_title ="{Journal_title}"; }  } else{ $article_title ="{article_title}";  $Journal_title ="{Journal_title}"; } 
    
    //                                                         $Draft_tags = ["{article_title}", "{Journal_title}"];
    
    
    //                                                         $DB_Rows   = [$article_title, $Journal_title];
    //                                                         $cmp_draft_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
    //                                                         $cmp_draft_sub_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft_sub);
    //                                                         $message_app_view= "
    //                                                       <div class=''>
    //                                                         Subject: $cmp_draft_sub_new_app 
    //                                                         <div style=' width:80%; padding:20px;text-align: justify;'>
                                                           
    //                                                         <p>Dear System Admin,</p>
    //                                                         <p >$cmp_draft_new_app</p>
    //                                                          </div></div>
    //                                                         ";
    
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $row["CampName"]; ?></td>
    
                                                                    <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>
    
                                                                    <td><?php echo $row["reason"]; ?></td>
                                                                    <td>
    
                                                                    <a href="edit_Campaign2.php?CampID=<?php echo $row["CampID"]; ?>"> <button type="button" class="btn btn-warning" title="Edit">Edit campaign  
                                                                    </button></a>
                                                                    <a target="_blank" href="components_order.php?ctype_id=<?php  echo $row["ctype_id"]; ?>&CampID=<?php  echo $row["CampID"]; ?>"> <button type="button" class="btn btn-warning" title="Edit">Assign Component Order 
                                                                </button></a> 
                                                                <a target="_blank" href="viewDraft.php?ctype_id=<?php  echo $row["ctype_id"]; ?>&CampID=<?php  echo $row["CampID"]; ?>"> <button type="button" class="btn btn-info" title="Edit">View Alert 
                                                                </button></a>
                                                                <a target="_blank" href="view_rejected_draft.php?ctype_id=<?php  echo $row["ctype_id"]; ?>&CampID=<?php  echo $row["CampID"]; ?>"> <button type="button" class="btn btn-info" title="Edit">View Rejected Draft
                                                                </button></a> 
                                                                       
    
   
    <?php // if($result_act['add_draft_activity']==1 && ($result_act['verification_activity']!=1)  ) { ?>
        <!-- value="<?php // echo  $message_app ?>" -->
                                                                  
                                                                 <!-- <button id="<?php // echo $row['CampID'];?>"  class="btn btn-info"  data-toggle="modal" data-target="#exampleModalCenter<?php // echo $row['CampID']; ?>"> View Draft</button>
    
                                                                    <div class="modal fade" id="exampleModalCenter<?php // echo $row['CampID']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: '100%';min-width: 80%;" role="document">
                                                                            <div class="modal-content" style="overflow:scroll;" >
                                                                               
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalCenterTitle"><?php // echo $row["CampName"]; ?> Draft </h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
    
                                                                                 <div class="modal-body"   style=" word-wrap:break-word; ">

                                                                                       
                                                                                         <div id="" class="row card card-body col-8" style=" word-wrap: break-word;"> <p   style=" word-wrap: break-word;"> <?php // echo $message_app_view; ?> </p> </div>
                                                                                        
                                                                                </div>
    
                                                                            </div>
                                                                        </div>    
                                                                    </div>
                                                                        
                                                                    <a target="_blank" href='Edit_draft.php?CampID=<?php // echo $row["CampID"]; ?>'><button type='button' class='btn btn-info'>Edit Draft</button></a> 
                                                                <a target="_blank" href='deleteDraft.php?CampID=<?php // echo $row["CampID"]; ?>'><button type='button' data-type="confirm" onclick='return deletedraft();' class='btn btn-warning'>Discard Draft</button></a>
     -->
    <?php // } ?>
    <?php // if( (trim($message_app)!=trim($message_app_view)) ) {?>
    
                                                                        <a href='verifyAlert.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button' data-type="confirm" onclick='return sendalert();' class='btn btn-secondary'>Verify Alert</button></a>
    <?php // } ?>

                                                                </tr>
                                                        <?php } }    } }

                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                      