<?php //IP Pool Ajax
include 'include/conn.php';
                                                if(isset($_POST['pcs'])){
                                                    $service_pro=$_POST['pcs'];
                                           
                                           
                                             $sql = "SELECT ip_pool_id,ip_pool FROM ip_pool where pool_status='Active'";
                                             if (!empty($service_pro)){ $sql.="and sp_id='$service_pro'"; }
                                             $stmt = $conn->prepare($sql);
                                             $stmt->execute();
                                             $sps = $stmt->fetchAll();
                                             ?>
                                               <option value="" disabled selected> Select IP Pool <?php echo $service_pro ;?></option>
                                                        <?php foreach ($sps as $output) { ?>
                                                            <option value="<?php echo $output["ip_pool_id"]; ?>"> <?php echo $output["ip_pool"]; ?> </option>
                                                        <?php
                                                        } ?>
                                                   
                                             <?php
                                           
                                             }
                                            
                                             ?>