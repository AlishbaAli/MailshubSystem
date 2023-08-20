
                                         <br><h4><b>Campaigns Inturrupted(In Active Reply to Email)</b></h4>
                                         <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
                                          <div class="d-flex justify-content-center" >  
                                           <i class="fas fa-square" style="font-size:15px; color: #AA9AAA; padding-left :25px;padding-right :5px;"></i> Embargo Email
                                             <i class="fas fa-square" style="font-size:15px; color: #AA9CCC; padding-left :25px;padding-right :5px;">  </i> Domain Block
                                             <i class="fas fa-square" style="font-size:15px; color: #AA9FFF; padding-left :25px; padding-right :5px;"> </i> unsubscriber Email </div>
                                        <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>CAMPAIGN NAME</th>
                                                        <th>CAMPAIGN DATE</th>
                                                        <th>TOTAL EMAILS</th>
                                                        <th>SENT EMAILS</th>
                                                        <th>PENDING EMAILS</th>
                                                        <th>REJECTED EMAILS</th>

                                                 <?php  if (isset($_SESSION['HC'])) {
                                                            if ($_SESSION['HC'] == "YES") {
                                                            echo" <th>ACTIONS</th>";
                                                            }
                                                        } ?>
                                                      


                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $sql = "SELECT `CampID`, `CampName`, `CampDate`, `user_id` as AdminID, `Camp_Status` 
                                                FROM `campaign` left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
                                                Where crtem_status = 'In Active'";

$u_role="SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'  ";
$user_role=$conn->prepare($u_role); $user_role->execute(); $user_role=$user_role->fetch();

 if($user_role['role_prev_id']!='1' && $user_role['role_prev_id']!='2' && $user_role['role_prev_id']!='8')
                                           
 { $sql.="AND AdminID= '$admin_id'"; }

 if(!empty(trim($orgunit_id)) && $orgunit_id!= null )

 { $sql.="AND orgunit_id= '$orgunit_id'"; }

                                                    $stmt = $conn->prepare($sql);
                                                    // $stmt->bindValue(':AdminID', $admin_id);
                                                    $result = $stmt->execute();

                                                    if ($stmt->rowCount() > 0) {

                                                        $result = $stmt->fetchAll();

                                                        foreach ($result as $row) {



                                                    ?>
                                                            <tr>
                                                                <td><?php echo $row["CampName"]; ?></td>

                                                                <td><?php echo date("j M Y", strtotime($row['CampDate'])); ?></td>
                                                                <?php
                                                                $stmt = $conn->prepare('SELECT count(Email) as TotalEmail, user_id as AdminID FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id  WHERE campaign.CampID = :CampID and ou_status="Active" ');
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $TotalEmail = $result['TotalEmail'];
                                                                ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FFCC;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                                <?php $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID  AND Status = 'Sent'");
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $TotalEmail = $result['TotalEmail'];
                                                                ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FF99;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                                <?php
                                                                $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors 
                                           INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                          AND Status = 'Not Sent'");
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $TotalEmail = $result['TotalEmail'];

                                                                ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#FF99FF;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                               <!-- ---------------------------------------------------------------------------------------------------- -->
                                                                
                                                               <?php
                                                                $stmt = $conn->prepare("SELECT count(case when  Status = 'Embargo lock' then Email end) as EmbargoEmail, 
                                                                 count(case when  Status = 'Domain lock' then Email end) as DBLEmail,
                                                                 count(case when  Status = 'Unsubscribed lock' then Email end) as UnsubEmail FROM campaingauthors 
                                            INNER JOIN campaign ON campaign.CampID = campaingauthors.CampID WHERE campaign.CampID  = :CampID 
                                           ");
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                // $stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                if ($stmt->rowCount()>0){
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $stmt->closeCursor();
                                                                $EmbargoEmail = $result['EmbargoEmail'];
                                                                $DBLEmail = $result['DBLEmail'];
                                                                $UnsubEmail = $result['UnsubEmail'];
                                                                } else {$EmbargoEmail = '0';
                                                                    $DBLEmail = '0';
                                                                    $UnsubEmail = '0';}
                                                                ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#AA9AAA;" type='button'><?php echo $EmbargoEmail ;?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9CCC;" type='button'><?php echo $DBLEmail ;?></button>
                                                                <button class='mr-2 mb-2 btn ' style="background-color:#AA9FFF;" type='button'><?php echo $UnsubEmail; ?></button> </td>
<!-- ------------------------------------------------------------------------------------------------------ -->  
                                                              
                                                              <?php  if (isset($_SESSION['HC'])) {
                                                            if ($_SESSION['HC'] == "YES") {
                                                            echo' <td> <a class="btn btn-warning btn-sm" href="holdCampaign.php?CampID='.$row["CampID"].'">Hold Campaign</a></td> ' ;
                                                            }
                                                        } ?>
                                                               

                                                            </tr>
                                                    <?php }
                                                    }

                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                   