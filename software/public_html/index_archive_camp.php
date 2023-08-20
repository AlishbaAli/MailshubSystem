<section id="Archive" data-status="Archive">
                                        <br><h4><b>Archived Campaigns</b></h4><br>
                                      
                                        <div class="table-responsive">
                                        <table class=" table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
                                                <thead>
                                                    <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                                        <th>CAMPAIGN NAME</th>
                                                        <th>CAMPAIGN DATE</th>
                                                        <th>CREATED DATE</th>
                                                        <th>SENT DATE</th>
                                                        <th>TOTAL EMAILS</th>
                                                        <th>SENT EMAILS</th>
                                                        <th>PENDING EMAILS</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php 
                                                    $admin_id=trim($admin_id);
                                                    $u_role="SELECT user_id , role_prev_id from tbl_user_role_prev where user_id='$admin_id'";
                                                    $user_rol=$conn->prepare($u_role);
                                                    $user_rol->execute(); 
                                                    $user_role= $user_rol->fetch(PDO::FETCH_ASSOC);

                                                    $list_date= date('Y-m-d', strtotime("-30 days"));// echo $list_date;
                                                    $sql = "SELECT `CampID`, `CampName`, `CampDate`, `user_id` as AdminID, `Camp_Status`, `Camp_Created_Date`, `Camp_Send_Date`  
										FROM `campaign`  left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id 
										Where Camp_Status = 'Archive'  and DATE(Camp_Send_Date) > '$list_date' AND ou_status='Active'
										";
           
            
             if($user_role['role_prev_id']!='1' && $user_role['role_prev_id']!='2' && $user_role['role_prev_id']!='8')
                                                       
             { $sql.="AND user_id= '$admin_id'"; }
            
             if(!empty(trim($orgunit_id)) && $orgunit_id!= null )
            
             { $sql.="AND orgunit_id= '$orgunit_id'"; }
             $sql.="ORDER BY CampID";

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
                                                                <td><?php echo date("j M Y", strtotime($row['Camp_Created_Date'])); ?></td>
                                                                <td><?php echo date("j M Y", strtotime($row['Camp_Send_Date'])); ?></td>
                                                                <?php
                                                                $stmt = $conn->prepare('SELECT count(Email) as TotalEmail, user_id as AdminID FROM campaingauthors_hold_archive INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors_hold_archive.CampID 
                                            left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id WHERE campaign.CampID = :CampID and ou_status="Active"');
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $TotalEmail = $result['TotalEmail']; ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FFCC;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                                <?php $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors_hold_archive INNER JOIN 
                                            campaign ON campaign.CampID = campaingauthors_hold_archive.CampID WHERE campaign.CampID  = :CampID AND Status = 'Sent'");
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                //$stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $TotalEmail = $result['TotalEmail']; ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#99FF99;" type='button'><?php echo $TotalEmail ?></button> </td>
                                                                <?php
                                                                $stmt = $conn->prepare("SELECT count(Email) as TotalEmail FROM campaingauthors_hold_archive 
                                            INNER JOIN campaign ON campaign.CampID = campaingauthors_hold_archive.CampID WHERE campaign.CampID  = :CampID 
                                           AND Status = 'Not Sent'");
                                                                $stmt->bindValue(':CampID', $row["CampID"]);
                                                                // $stmt->bindValue(':AdminID', $admin_id);
                                                                $stmt->execute();
                                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                $TotalEmail = $result['TotalEmail']; ?>
                                                                <td> <button class='mr-2 mb-2 btn ' style="background-color:#FF99FF;" type='button'><?php echo $TotalEmail ?></button> </td>


                                                            </tr>
                                                    <?php }
                                                    }

                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </section>