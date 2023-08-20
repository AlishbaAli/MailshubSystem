<br><h4><b>Verified Campaigns</b></h4><br>
                                        <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>CAMPAIGN NAME</th>
                                                        <th>CAMPAIGN DATE</th>
                                                         <th>TOTAL EMAILS</th>

                                                        <th>ACTIONS</th>
                                                      
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php


                                                    $sql = "SELECT campaign.CampID, `CampName`,Camp_category, `CampDate`,`orgunit_id`, user_id as AdminID, `Camp_Status`, `rtemid` ,draft.subscription_draft,draft_subject,campaign.mailserverid
                                            FROM `campaign` left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  left join draft on draft.CampID=campaign.CampID
                                            Where Camp_Status = 'Verified' and crtem_status != 'In Active' and ou_status='Active'";


$u_role="SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role=$conn->prepare($u_role); $user_role->execute(); $user_role=$user_role->fetch();

 if($user_role['role_prev_id']!='1' && $user_role['role_prev_id']!='2' && $user_role['role_prev_id']!='8')
                                           
 { $sql.="AND user_id= '$admin_id'"; }

 if(!empty(trim($orgunit_id)) && $orgunit_id!= null )

 { $sql.="AND orgunit_id= '$orgunit_id'"; }

                                                    $stmt = $conn->prepare($sql);
                                                    //$stmt->bindValue(':AdminID', $admin_id);
                                                    $result = $stmt->execute();

                                                    if ($stmt->rowCount() > 0) {

                                                        $result = $stmt->fetchAll();

                                                        foreach ($result as $row) {



                                                            $mid=$row['mailserverid']; //echo $mid;
                                                            $ms="SELECT vmstatus from mailservers where mailserverid='$mid'";
                                                            $ms=$conn->prepare($ms);
                                                            $ms->execute();
                                                            $msrow=$ms->fetch();
                                                            $mstatus=$msrow['vmstatus'];
                                                           

                                                     // ---------------------------------------
                                                    // ---------------------------------------
                                                    $cmp_draft = html_entity_decode($row['subscription_draft']);
                                                    $cmp_draft_sub = html_entity_decode($row['draft_subject']);
                                                       // $cmp_draft=html_entity_decode($cmp_draft);
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
    } else{ $article_title ="{article_title}"; } 
    



if (!empty(trim($result_get['Journal_title']))) { 

                                                        $Journal_title = trim($result_get['Journal_title']);
} else{ $Journal_title ="{Journal_title}"; }  } else{ $article_title ="{article_title}";  $Journal_title ="{Journal_title}"; } 


                                                        $Draft_tags = ["{article_title}", "{Journal_title}"];


                                                        $DB_Rows   = [$article_title, $Journal_title];
                                                        $cmp_draft_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                                                        $cmp_draft_sub_new_app = str_replace($Draft_tags, $DB_Rows, $cmp_draft_sub);
                                                        $message_app = "<html>
                                                      </body>
                                                        <div>Subject:$cmp_draft_sub_new_app  </div>
                                                        <div style=' width:85%; padding:20px;text-align: justify;'>
                                                        <p>Dear System Admin,</p>
                                                        <p style='text-align:justify;'>$cmp_draft_new_app</p>
                                                        </div>
                                                        </body>
                                                      </html>";


                                                    $CampID=$row["CampID"]; //echo $CampID;
                                                    $sql_act = "SELECT *
                                                    FROM activity
                                                    Where CampID = :CampID ";

                                                    $stmt_act = $conn->prepare($sql_act);
                                                    $stmt_act->bindValue(':CampID',  $CampID);
                                                    // $stmt_act->bindValue(':AdminID', $admin_id);
                                                    $stmt_act->execute();
                                                    $result_act = $stmt_act->fetch();

                                                    ?>
                                                            <tr>
                                                                <td><?php echo $row["CampName"]; ?></td>

                                                                <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>
                                                                <?php
                                            $stmt = $conn->prepare('SELECT count(Email) as TotalEmail, user_id as AdminID FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID 
                                            left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id WHERE campaign.CampID = :CampID and ou_status="Active"');
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $TotalEmail = $result['TotalEmail'];
                                                                ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FFCC;" type='button'><?php echo $TotalEmail ?></button> </td>

                                                                <td>
                                                                <!-- value="<?php // echo $message_app ?>" -->

                                                               <button id="<?php echo $row['CampID']; ?>"  class="btn btn-info"  data-toggle="modal" data-target="#exampleModalCenter<?php echo $row['CampID']; ?>"> View Draft</button>

                                                                <div class="modal fade" id="exampleModalCenter<?php echo $row['CampID']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"      aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                           
                                                                            <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $row["CampName"]; ?> Draft </h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                   
                                                                                     <div id=""> <?php echo $message_app; ?></div>
                                                                                    
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    
<?php if($row['Camp_category']=='Manual' || $row['Camp_category']=='Manuel') { 
    if(trim($result_act['add_authordata']=='1')) {?>                     
        <!-- <a target="_blank" href='draftLandingpage.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button' class='btn btn-primary'>Check Draft</button></a> -->
         <!-- <a target="_blank" href='viewAuthor.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button'  class='btn btn-success'  data-toggle="modal" data-target="#example<?php echo $row['CampID']; ?>" >View Data</button></a> -->
         <button type='button'  class='btn btn-info'  data-toggle="modal" data-target="#example<?php echo $row['CampID']; ?>" >View Data</button>    
                                                    <div class="modal fade"   id="example<?php echo $row['CampID']; ?>"  role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " style="max-width: '100%';min-width: 80%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                    <!-- <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $row["CampName"]; ?> Draft </h5> -->
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                                     <div id=""> <?php include 'Author_modal.php'; ?></div>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                                                    
                <a target="_blank" href='addAuthor.php?CampID=<?php echo $row["CampID"]; ?>&orgunit_id=<?php echo $row["orgunit_id"]; ?>'><button type='button'  class='btn btn-primary'>Add More Data</button></a>
               <?php  if ($mstatus=='Blacklisted'){ ?>
                    <a href='cstatus_interrupt.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button' data-type="confirm" onclick='return ActivateCamp();' class='btn btn-success'>Activate Campaign</button></a>
              <?php  } else { ?>
                <a href='sendAlert.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button' data-type="confirm" onclick='return ActivateCamp();' class='btn btn-success'>Activate Campaign</button></a>
    <?php } } ?> 
    <?php if( trim($result_act['add_authordata']!='1') ) {?>
                <a target="_blank" href='addAuthor.php?CampID=<?php echo $row["CampID"]; ?>&orgunit_id=<?php echo $row["orgunit_id"]; ?>'>
                <button type='button'  class='btn btn-primary'>Add Data</button> </a>
    
              
                                                                        
    <?php } } ?>

    <?php if($row['Camp_category']=='Automatic') { 
    if($result_act['add_authordata']==1  ) {?>                     
       
                <a href='viewSendAlert.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button' class='btn btn-info'>Send Alert</button></a> --> 
    <?php } ?>

    <?php 
    if($result_act['add_authordata']==2) {                    
        $pendingMsg     =     " <span id=first_alertmsg class=' alert-info  btn ' role='alert'> <i class='fa fa-spinner fa-spin'></i> Awaiting Data</span>
                                   ";
                                            echo $pendingMsg;
   } ?>
    
    <?php if( ($result_act['add_authordata']!=1 && $result_act['add_authordata']!=2) ) {?>
                <a target="_blank" href='Select_data_auto.php?CampID=<?php echo $row["CampID"]; ?>'><button type='button'  class='btn btn-success'>Select Data and Activate</button></a>
               
                                                                        
    <?php } } ?>


                                                                    
                                                                 </td>
                                                               
                                                            </tr>
                                                    <?php }
                                                    } 

                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>