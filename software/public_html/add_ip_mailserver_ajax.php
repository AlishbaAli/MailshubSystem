<?php  // IP Address Select 
include 'include/conn.php';
                                                if(isset($_POST['ips'])){
                                                    $ip_pool=$_POST['ips'];
                                           
                                           
                                             $sql = "SELECT ip_hostname.ip_hostname_id,ip_addresses.ip_addresses_id,ip_address FROM ip_addresses 
                                             left join ip_pool on ip_addresses.ip_pool_id = ip_pool.ip_pool_id 
                                             left join ip_hostname on ip_addresses.ip_addresses_id = ip_hostname.ip_addresses_id 
                                             left join ipdetails on ip_hostname.ip_hostname_id =ipdetails.ip_hostname_id 
                                             where ip_addresses_status='Active' and iphost_status='Active' and ( ipdetails.ipstatus != 'Active' and ipdetails.ipstatus != 'WHITELIST' and ipdetails.ipstatus != 'BLACKLIST')";
                                             if (!empty($ip_pool)){ $sql.="and ip_pool.ip_pool_id='$ip_pool'"; }
                                             $stmt = $conn->prepare($sql);
                                             $stmt->execute();
                                             $sps = $stmt->fetchAll();
                                             ?>
                                            <option value=""  disabled selected> Select IP Address <?php echo $ip_pool ;?></option> 
                                                        <?php foreach ($sps as $output) { ?>
                                                            <option value="<?php echo $output["ip_hostname_id"].",". $output["ip_address"]; ?>"> <?php echo $output["ip_address"]; ?> </option>
                                                        <?php
                                                        } ?>
                                                   
                                             <?php
                                             exit;
                                             } //disabled
                                            
                                             ?>