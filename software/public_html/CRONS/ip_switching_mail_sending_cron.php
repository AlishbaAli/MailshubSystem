<?php
//code by Alishba Ali
$key = 123456;


$max = 1;
$permissions = 0666;
$autoRelease = 1;

$semaphore = sem_get($key, $max, $permissions, $autoRelease);

if(!$semaphore) {
    echo "Failed on sem_get().\n";
    exit;
}
sem_acquire($semaphore);




//**Connect to Application Server
$servername = "172.16.111.2";
$username = "mailshub_admin";
$password = "VFD1^*srYlH+"; 

try 
{
    $conn = new PDO("mysql:host=$servername; dbname=mailshub_db", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "Connected successfully"; 
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    exit;
}
 	//**Finding who am I and Am I Active

$mac_list = "";
$macaddresses = explode("\n", shell_exec("sudo /sbin/ifconfig -a | grep -Po 'ether \K\w+:\w+:\w+:\w+:\w+:\w+'"));
$a =0;
foreach ($macaddresses as $ele)
{
    if($a != 0 && $ele != "")
    {   
        $mac_list .=",";
    }
    $a++;
    if($ele != "")
    {
        $mac_list .=chr(39).$ele.chr(39);
    }      
}
    
echo "<br>";
echo "MAC List: ".$mac_list;
echo "<br>";

$current_ipaddress = "";
$current_hostname = "";
$current_email = "";
$interface_name = "";
$mac_address = "";
 $mailserverid = 0;
$mail_server_name = "";
$mail_server_count = 0;

$rs_mcms = $conn->prepare("select * from mailservers where vmstatus = 'Active' and mac_address in (".$mac_list.")");
$rs_mcms->execute();
$row_mcms = $rs_mcms->fetch(PDO::FETCH_ASSOC);
$mail_server_count = $rs_mcms->rowCount();

echo "<br>";
echo "<br>";
echo "Mail Server Count: ".$mail_server_count;
echo "<br>";

date_default_timezone_set("Asia/Karachi");
$date = date('Y-m-d');
//If I am Active
if ($mail_server_count == 1)
{
    $mac_address = $row_mcms['mac_address'];
    $interface_name = $row_mcms['ethernet_name'];
    $mailserverid = $row_mcms['mailserverid'];
    $mail_server_name = $row_mcms['vmname'];
    
//     //find system settings
    $sql_ss = "select * from system_setting where status = 'Active'";
    $stmt_ss = $conn->prepare($sql_ss);
    $stmt_ss->execute();
    $system_settings = $stmt_ss->fetch(PDO::FETCH_ASSOC);

//     //**Find actual(real) ip and hostname on server 
    $current_ipaddress = shell_exec("sudo /sbin/ifconfig ".$interface_name." | grep -Po 'inet \K\w+.\w+.\w+.\w+'");
    $current_hostname = shell_exec('hostname');

    //variables from system_settings needed for switching
    $ip_switching_execution = $system_settings['ip_switching_execution']; //Enable, Disable
    $ip_switch_criteria_code = $system_settings['ip_switch_criteria_code']; //SSW, RSW
    $ip_switch_criteria = $system_settings['ip_switch_criteria']; //Standard-Switch, Random-Switch
    $ip_switch_min_interval = $system_settings['ip_switch_min_interval'];//45-60
    $ip_switch_max_interval = $system_settings['ip_switch_max_interval'];//60-75
    $ip_switch_standard_interval = $system_settings['ip_switch_standard_interval'];//45-75
    $ip_selection_criteria = $system_settings['ip_selection_criteria']; //Sequential-Selection, Random-Selection
    $ip_selection_criteria_code = $system_settings['ip_selection_criteria_code']; //SSL, RSL
    $random_ip_selection_offset = $system_settings['random_ip_selection_offset']; //4-7
    $mailserver_min_active_ips = $system_settings['mailserver_min_active_ips'];//4

    //mail sending cron 
    $mail_sending_execution = $system_settings['mail_sending_execution']; //Enable, Disable
  

    echo "<br>";
    echo "IP Switching Execution: ".$ip_switching_execution;
    echo "<br>";
    echo "IP Switching Criteria: ".$ip_switch_criteria;
    echo "<br>";
    echo "IP Switching Min Interval: ".$ip_switch_min_interval;
    echo "<br>";
    echo "IP Switching Max Interval: ".$ip_switch_max_interval;
    echo "<br>";
    echo "IP Switching Standard Interval: ".$ip_switch_standard_interval;
    echo "<br>";
    echo "IP Selection Criteria: ".$ip_selection_criteria;
    echo "<br>";
    echo "Random IP Selection Offset: ".$random_ip_selection_offset;
    echo "<br>";
    echo "Mail Server Name: ".$mail_server_name;
    echo "<br>";
    echo "Mail Server ID: ".$mailserverid;
    echo "<br>";
    echo "Mail Server Min Active IPs: ".$mailserver_min_active_ips;
    echo "<br>";
    echo "Interface Name: ".$interface_name;
    echo "<br>";
    echo "MAC Address: ".$mac_address;
    echo "<br>";

    //**If ip switching is enabled */
    if($ip_switching_execution=='Enable')
    {
        $first_time_in_config=0;
        $ip_unallocated=0;
        $ip_no_hostname=0;
        $ip_unverified=0;
        $hostname_from_command="";
        $ipaddress_from_command="";
        $ip_selected=0;
        $last_used_ip="";
        $time_to_switch=1;
        $switch_to_sequential=0;

        //Get hostname of current ip and find if this ip is no more allocated to the server
        $stmt_ip_details=$conn->prepare("SELECT hostname FROM ipdetails WHERE ipaddress=:ipaddress AND mailserverid=:maliserverid AND ipstatus='WHITELIST'");
        $stmt_ip_details->bindParam(':ipaddress',$current_ipaddress);
        $stmt_ip_details->bindParam(':maliserverid',$maliserverid);
        $stmt_ip_details->execute();
        $ipdetails_from_db = $stmt_ip_details->fetch();

        //2nd condition: ip is no more allocated to the server
        if($stmt_ip_details->rowCount()<1)
        {
            $ip_unallocated=1;
        }
        else
        {
            $hostname_from_db = $ipdetails_from_db['hostname'];
        }

       

        //4th condition: check if unverified

        //Check reverse entry
        $hostname_from_cmd = shell_exec("dig +noall +answer -x $current_ipaddress");
        if ($hostname_from_cmd != NULL)
        {
            $hostname_from_cmd = explode('PTR', $hostname_from_cmd);
            $hostname_from_command = substr_replace(trim($hostname_from_cmd[1]), "", -1);
        }
        else
        {
            $ip_no_hostname=1;        
        }
    
        //Check forward entry
        $ipaddress_from_command = shell_exec("dig +short $current_hostname");

        if($ip_unallocated==0)
        { 
            if($ip_no_hostname==1)
            {
                //change status in ipdetails and ip_hostname table
                $stmt_update= $conn->prepare("UPDATE ipdetails SET ipstatus='No Hostname' WHERE ipstatus='WHITELIST' AND ipaddress=:ipaddress");
                $stmt_update->bindParam(':ipaddress',$current_ipaddress);
                $stmt_update->execute();
                //change status in ipdetails and ip_hostname table
                $stmt_update= $conn->prepare("UPDATE ip_hostname SET iphost_status ='No Hostname' WHERE iphost_status ='Active' 
                AND ip_addresses_id = (SELECT ip_addresses_id WHERE ip_address=:ip_address)");
                $stmt_update->bindParam(':ip_address',$current_ipaddress);
                $stmt_update->execute();
            }
            else
            {
                if(trim($hostname_from_command)!=trim($hostname_from_db) ||  trim($ipaddress_from_command)!=trim($current_ipaddress))
                {
                    $ip_unverified=1;

                    //change status in ipdetails and ip_hostname table
                    $stmt_update= $conn->prepare("UPDATE ipdetails SET ipstatus='Hostname Unverified' WHERE ipstatus='WHITELIST' AND ipaddress=:ipaddress");
                    $stmt_update->bindParam(':ipaddress',$current_ipaddress);
                    $stmt_update->execute();
                    //change status in ipdetails and ip_hostname table
                    $stmt_update= $conn->prepare("UPDATE ip_hostname SET iphost_status ='Hostname Unverified' WHERE iphost_status ='Active' 
                    AND ip_addresses_id = (SELECT ip_addresses_id WHERE ip_address=:ip_address)");
                    $stmt_update->bindParam(':ip_address',$current_ipaddress);
                    $stmt_update->execute();
                }
            }
        }

        $stmt_check_time= $conn->prepare("SELECT cmc_id FROM current_mailserver_config WHERE mailserverid=:mailserverid AND ipaddress=:ipaddres");
        $stmt_check_time->bindParam(':ipaddress', $current_ipaddress);
        $stmt_check_time->bindParam(':mailserverid', $mailserverid);
        $stmt_check_time->execute();

        //3rd condition: if its the first time of this ip in config table
        if($stmt_check_time->rowCount()<1)
        {
            $first_time_in_config=1;
        }

        if ($first_time_in_config==0)
        {
            //1st condition
            if($ip_switch_criteria_code=='SSW')
            {

                $stmt_ssw= $conn->prepare("SELECT system_date FROM current_mailserver_config WHERE mailserverid=:mailserverid AND ipaddress=:ipaddress
                AND TIMESTAMPDIFF(MINUTE,system_date,LOCALTIME()) < '$ip_switch_standard_interval' order by `cmc_id` DESC LIMIT 1");
                $stmt_ssw->execute();
                if($stmt_ssw->rowCount()>0)
                {
                //its not yet the time
                $time_to_switch=0;
                }
            }
            if($ip_switch_criteria_code=='RSW')
            {
                $random_switch_interval = rand($ip_switch_min_interval,$ip_switch_max_interval);
                $stmt_rsw= $conn->prepare("SELECT system_date FROM current_mailserver_config WHERE mailserverid=:mailserverid AND ipaddress=:ipaddress
                AND TIMESTAMPDIFF(MINUTE,system_date,LOCALTIME()) < '$random_switch_interval' order by `cmc_id` DESC LIMIT 1");
                $stmt_rsw->execute();
                if($stmt_rsw->rowCount()>0)
                {
                //its not yet the time
                $time_to_switch=0;
                }
            }
        }

        //check All 4 conditions

        if($time_to_switch==1 || $ip_unallocated==1 || $ip_unverified==1  || $ip_no_hostname==1  || $first_time_in_config=1)
        {
    
            while($ip_selected==0)
            {
                $newip_no_hostname=0;
                $newip_unverified=0;

                 //check selection criteria
               
                if($ip_selection_criteria_code=='RSL')
                {
                     // get last offset ips from config table
                    $get_last_ip= $conn->prepare("SELECT ipaddress FROM current_mailserver_config WHERE mailserverid=:mailserverid AND
                    order by `cmc_id` DESC LIMIT '$random_ip_selection_offset'");
                    $get_last_ip->bindParam(':mailserverid',$mailserverid);
                    $get_last_ip->execute();
                    $get_last_ips = $get_last_ip->fetchAll();

                    foreach($get_last_ips as $ips)
                    {
                        $last_offset_ip.= $ips.",";
                    }
                    $last_offset_ips = substr_replace($last_offset_ip, "", -1);

                    // get ips other than offset

                    $stmt_msipr = $conn->prepare("SELECT ipaddress, ipsubnet, ipgateway, hostname, emailaddress, mailserverid FROM ipdetails WHERE  ipstatus = 'WHITELIST' 
                    AND mailserverid = :mailserverid AND ipaddress NOT IN($last_offset_ips)");
                    $stmt_msipr->bindParam(':mailserverid', $mailserverid);
                    $stmt_msipr->execute();

                    $IpDetails = array();

                    //check if count is >= min active ips
                    if($stmt_msipr->rowCount() >= $mailserver_min_active_ips)
                    {
                        //random index to be selected
                        $random_ip_index= rand(1, $stmt_msipr->rowCount());

                        $ip_rec_count = 0;
                        $rs_msipr = $stmt_msipr->fetchAll();
                        foreach ($rs_msipr as $row_msip) 
                        {
                            $ip_info = array ("iporder" => $ip_rec_count, "ipaddress" => $row_msip['ipaddress'], "ipsubnet" => $row_msip['ipsubnet'], "ipgateway" => $row_msip['ipgateway'], "hostname" => $row_msip['hostname'], "emailaddress" => $row_msip['emailaddress']);
                            array_push($IpDetails, $ip_info);
                            $ip_info = null;
                            $ip_rec_count++;
                        }

                        $new_ipaddress = "";
                        $new_hostname = "";
                        $new_subnet = "";
                        $new_gateway = "";
                        $new_email = "";
                        $old_ipaddress = "";
                        $old_hostname = "";
                        $old_subnet = "";
                        $old_gateway = "";
                        $old_email = "";
 
                        echo "Total Usable IP Address Records: ".count($IpDetails);
                        echo "<br>";
                        echo "<br>";
                         
                        foreach($IpDetails as $iprecord)
                        {
                             if(trim($iprecord['ipaddress'])==trim($current_ipaddress))
                            {
                                $new_ipaddress = $IpDetails[$random_ip_index]['ipaddress'];
                                $new_hostname = $IpDetails[$random_ip_index]['hostname'];
                                $new_subnet = $IpDetails[$random_ip_index]['ipsubnet'];
                                $new_gateway = $IpDetails[$random_ip_index]['ipgateway'];
                                $new_email = $IpDetails[$random_ip_index]['emailaddress'];

                                $old_ipaddress = $iprecord['ipaddress'];
                                $old_hostname = $iprecord['hostname'];
                                $old_subnet = $iprecord['ipsubnet'];
                                $old_gateway = $iprecord['ipgateway'];
                                $old_email = $iprecord['emailaddress'];
                            }
                        }
 
                        $old_subnet_bits_array = explode(".",$old_subnet);
                        $old_subnet_bits = 0;
                        foreach($old_subnet_bits_array as $subnet_octet)
                        {
                             $old_subnet_bits = $old_subnet_bits+strlen(str_replace("0","",decbin($subnet_octet)));
                        }
 
                        $new_subnet_bits_array = explode(".",$new_subnet);
                        $new_subnet_bits = 0;
                        foreach($new_subnet_bits_array as $subnet_octet)
                        {
                             $new_subnet_bits = $new_subnet_bits+strlen(str_replace("0","",decbin($subnet_octet)));
                        }
 
                        echo "Old Configurations:";
                        echo "<br>";
                        echo "===================";
                        echo "<br>";
                        echo "Old IP Address: ".$old_ipaddress;
                        echo "<br>";
                        echo "Old Subnet Mask: ".$old_subnet;
                        echo "<br>";
                        echo "Old Subnet Bits: ".$old_subnet_bits;
                        echo "<br>";
                        echo "Old Gateway: ".$old_gateway;
                        echo "<br>";
                        echo "Old Hostname: ".$old_hostname;
                        echo "<br>";
                        echo "Old From Email Address: ".$old_email;
                        echo "<br>";
                        echo "<br>";
                        echo "New Configurations:";
                        echo "<br>";
                        echo "===================";
                        echo "<br>";
                        echo "New IP Address: ".$new_ipaddress;
                        echo "<br>";
                        echo "New Subnet Mask: ".$new_subnet;
                        echo "<br>";
                        echo "New Subnet Bits: ".$new_subnet_bits;
                        echo "<br>";
                        echo "New Gateway: ".$new_gateway;
                        echo "<br>";
                        echo "New Hostname: ".$new_hostname;
                        echo "<br>";
                        echo "New From Email Address: ".$new_email;
                        echo "<br>";
                        echo "<br>";

                    } // min ip left RSL
                     else
                    {
                        //  //blacklist mailserver
                        // $stmt_blcklist= $conn->prepare("UPDATE mailservers SET vmstatus='Blacklisted' WHERE mailserverid=:mailserverid");
                        // $stmt_blcklist->bindParam(':mailserverid',$mailserverid);
                        // $stmt_blcklist->execute();

                        // //change campaign status to Interuptted (Mail Server Unavailable)
                        // $stmt_intrptd= $conn->prepare("UPDATE campaign SET Camp_Status='Interuptted (Mail Server Unavailable)' WHERE mailserverid=:mailserverid");
                        // $stmt_intrptd->bindParam(':mailserverid',$mailserverid);
                        // $stmt_intrptd->execute();
                        // exit;
                        $switch_to_sequential=1;
                    }
                } // RSL

                //check selection criteria
                if($ip_selection_criteria_code=='SSL' ||  $switch_to_sequential==1)
                {
                    $stmt_msip = $conn->prepare("SELECT ipaddress, ipsubnet, ipgateway, hostname, emailaddress, mailserverid FROM ipdetails WHERE  ipstatus = 'WHITELIST' 
                    AND mailserverid = :mailserverid");
                    $stmt_msip->bindParam(':mailserverid', $mailserverid);
                    $stmt_msip->execute();
                    $IpDetails = array();

                    //check if count is >= min active ips
                    if($stmt_msip->rowCount() >= $mailserver_min_active_ips)
                    {

                        $ip_rec_count = 0;
                        $rs_msip = $stmt_msip->fetchAll();
                        foreach ($rs_msip as $row_msip) 
                        {
                            $ip_info = array ("iporder" => $ip_rec_count, "ipaddress" => $row_msip['ipaddress'], "ipsubnet" => $row_msip['ipsubnet'], "ipgateway" => $row_msip['ipgateway'], "hostname" => $row_msip['hostname'], "emailaddress" => $row_msip['emailaddress']);
                            array_push($IpDetails, $ip_info);
                            $ip_info = null;
                            $ip_rec_count++;
                        }
      
                        $new_ipaddress = "";
                        $new_hostname = "";
                        $new_subnet = "";
                        $new_gateway = "";
                        $new_email = "";
                        $old_ipaddress = "";
                        $old_hostname = "";
                        $old_subnet = "";
                        $old_gateway = "";
                        $old_email = "";

                        echo "Total Usable IP Address Records: ".count($IpDetails);
                        echo "<br>";
                        echo "<br>";

                        foreach($IpDetails as $iprecord)
                        {
                            if(trim($iprecord['ipaddress'])==trim($current_ipaddress))
                            {
                                if($iprecord['iporder']+1 < count($IpDetails))
                                {
                                    $new_ipaddress = $IpDetails[$iprecord['iporder']+1]['ipaddress'];
                                    $new_hostname = $IpDetails[$iprecord['iporder']+1]['hostname'];
                                    $new_subnet = $IpDetails[$iprecord['iporder']+1]['ipsubnet'];
                                    $new_gateway = $IpDetails[$iprecord['iporder']+1]['ipgateway'];
                                    $new_email = $IpDetails[$iprecord['iporder']+1]['emailaddress'];
                                }
                                else
                                {
                                    $new_ipaddress = $IpDetails[0]['ipaddress'];
                                    $new_hostname = $IpDetails[0]['hostname'];
                                    $new_subnet = $IpDetails[0]['ipsubnet'];
                                    $new_gateway = $IpDetails[0]['ipgateway'];
                                    $new_email = $IpDetails[0]['emailaddress'];
                                }
                                $old_ipaddress = $iprecord['ipaddress'];
                                $old_hostname = $iprecord['hostname'];
                                $old_subnet = $iprecord['ipsubnet'];
                                $old_gateway = $iprecord['ipgateway'];
                                $old_email = $iprecord['emailaddress'];
                            }
                        }
        
                        $old_subnet_bits_array = explode(".",$old_subnet);
                        $old_subnet_bits = 0;
                        foreach($old_subnet_bits_array as $subnet_octet)
                        {
                            $old_subnet_bits = $old_subnet_bits+strlen(str_replace("0","",decbin($subnet_octet)));
                        }

                        $new_subnet_bits_array = explode(".",$new_subnet);
                        $new_subnet_bits = 0;
                        foreach($new_subnet_bits_array as $subnet_octet)
                        {
                            $new_subnet_bits = $new_subnet_bits+strlen(str_replace("0","",decbin($subnet_octet)));
                        }

                        echo "Old Configurations:";
                        echo "<br>";
                        echo "===================";
                        echo "<br>";
                        echo "Old IP Address: ".$old_ipaddress;
                        echo "<br>";
                        echo "Old Subnet Mask: ".$old_subnet;
                        echo "<br>";
                        echo "Old Subnet Bits: ".$old_subnet_bits;
                        echo "<br>";
                        echo "Old Gateway: ".$old_gateway;
                        echo "<br>";
                        echo "Old Hostname: ".$old_hostname;
                        echo "<br>";
                        echo "Old From Email Address: ".$old_email;
                        echo "<br>";
                        echo "<br>";
                        echo "New Configurations:";
                        echo "<br>";
                        echo "===================";
                        echo "<br>";
                        echo "New IP Address: ".$new_ipaddress;
                        echo "<br>";
                        echo "New Subnet Mask: ".$new_subnet;
                        echo "<br>";
                        echo "New Subnet Bits: ".$new_subnet_bits;
                        echo "<br>";
                        echo "New Gateway: ".$new_gateway;
                        echo "<br>";
                        echo "New Hostname: ".$new_hostname;
                        echo "<br>";
                        echo "New From Email Address: ".$new_email;
                        echo "<br>";
                        echo "<br>";

                    } // min ip left SSL
	                else
                    {
                        //blacklist mailserver
                        $stmt_blcklist= $conn->prepare("UPDATE mailservers SET vmstatus='Blacklisted' WHERE mailserverid=:mailserverid");
                        $stmt_blcklist->bindParam(':mailserverid',$mailserverid);
                        $stmt_blcklist->execute();
                        
                        //change campaign status to Interuptted (Mail Server Unavailable)
                        $stmt_intrptd= $conn->prepare("UPDATE campaign SET Camp_Status='Interuptted (Mail Server Unavailable)' WHERE mailserverid=:mailserverid");
                        $stmt_intrptd->bindParam(':mailserverid',$mailserverid);
                        $stmt_intrptd->execute();
                        exit;
                    }
                }//SSL

               

                //After selection (either by sequential or random)

                // verify new hostname and IP-----------------------------------------------------------------

                //Check reverse entry
                $hostname_from_cmd = shell_exec("dig +noall +answer -x $new_ipaddress");
                if ($hostname_from_cmd != NULL)
                {
                    $hostname_from_cmd = explode('PTR', $hostname_from_cmd);
                    $hostname_from_command = substr_replace(trim($hostname_from_cmd[1]), "", -1);
                }
                else
                {
                    $newip_no_hostname=1;        
                }
   
                //Check forward entry
                $ipaddress_from_command = shell_exec("dig +short $new_hostname");

                if($newip_no_hostname==1)
                {
                    //change status in ipdetails and ip_hostname table
                    $stmt_update= $conn->prepare("UPDATE ipdetails SET ipstatus='No Hostname' WHERE ipstatus='WHITELIST' AND ipaddress=:ipaddress");
                    $stmt_update->bindParam(':ipaddress',$new_ipaddress);
                    $stmt_update->execute();
                    //change status in ipdetails and ip_hostname table
                    $stmt_update= $conn->prepare("UPDATE ip_hostname SET iphost_status ='No Hostname' WHERE iphost_status ='Active' 
                    AND ip_addresses_id = (SELECT ip_addresses_id WHERE ip_address=:ip_address)");
                    $stmt_update->bindParam(':ip_address',$new_ipaddress);
                    $stmt_update->execute();
                }
                else
                {
                    if(trim($hostname_from_command)!=trim($new_hostname) ||  trim($ipaddress_from_command)!=trim($new_ipaddress)){
                        $newip_unverified=1;
                
                        //change status in ipdetails and ip_hostname table
                        $stmt_update= $conn->prepare("UPDATE ipdetails SET ipstatus='Hostname Unverified' WHERE ipstatus='WHITELIST' AND ipaddress=:ipaddress");
                        $stmt_update->bindParam(':ipaddress',$new_ipaddress);
                        $stmt_update->execute();
                        //change status in ipdetails and ip_hostname table
                        $stmt_update= $conn->prepare("UPDATE ip_hostname SET iphost_status ='Hostname Unverified' WHERE iphost_status ='Active' 
                        AND ip_addresses_id = (SELECT ip_addresses_id WHERE ip_address=:ip_address)");
                        $stmt_update->bindParam(':ip_address',$new_ipaddress);
                        $stmt_update->execute();
                    }
                }
                // verify new hostname and IP----------------------------------------------------------------------------------------------------------------------

                //if verified check starts
                if($newip_no_hostname==0 && $newip_unverified==0)
                {
                    // Apply changes to real server

                    //Change Hostname
                    shell_exec("hostnamectl --static set-hostname ".$new_hostname);

                    //Read the current configuration
                    $interface_config=file_get_contents("/etc/sysconfig/network-scripts/ifcfg-".$interface_name);

                    //Replace IP Address
                    $interface_config=str_replace("IPADDR=".$old_ipaddress, "IPADDR=".$new_ipaddress, $interface_config);

                    //Replace Subnet Bits
                    $interface_config=str_replace("PREFIX=".$old_subnet_bits, "PREFIX=".$new_subnet_bits, $interface_config);

                    //Replace Gateway
                    $interface_config=str_replace("GATEWAY=".$old_gateway, "GATEWAY=".$new_gateway, $interface_config);

                    //Write the new configuration
                    file_put_contents("/etc/sysconfig/network-scripts/ifcfg-".$interface_name, $interface_config);

                    //Apply Changes
                    shell_exec("sudo /sbin/ifdown ".$interface_name);
                    shell_exec("sudo /sbin/ifup ".$interface_name);

                    //so that while loop ends
                    $ip_selected=1;
                    //Not first time
                    if($first_time_in_config==0)
                    {
                        //get last duration from last ipaddress of this mailserver
                        $stmt_last_ip= $conn->prepare("SELECT TIMESTAMPDIFF(MINUTE,system_date,LOCALTIME()) As last_duration FROM current_mailserver_config WHERE mailserverid=:mailserverid AND
                        order by `cmc_id` DESC LIMIT 1");
                        $stmt_last_ip->bindParam('mailserverid', $mailserverid);
                        $stmt_last_ip->execute();
                        $lastduration=$stmt_last_ip->fetch();
                        //first update previous record and make it In Active then insert in config
                        $stmt_upd= $conn->prepare("UPDATE current_mailserver_config SET config_status='In Active' WHERE mailserverid=:mailserverid
                        AND ipaddress=:ipaddress");
                        $stmt_upd->bindParam(':mailserverid', $mailserverid);
                        $stmt_upd->bindParam(':ipaddress', $old_ipaddress);
                        $stmt_upd->execute();
                        
                        $stmt_insert = $conn->prepare("INSERT INTO current_mailserver_config(mailserverid, ipaddress,hostname, ipgateway, ipsubnet, emailaddress, switching_criteria,
                        selection_criteria, duration_from_last_switch_in_min)
                        VALUES(:mailserverid, :ipaddress, :hostname, :ipgateway, :ipsubnet, :emailaddress, :switching_criteria, :selection_criteria, :duration_from_last_switch_in_min)");
                        $stmt_insert->bindParam(':mailserverid', $mailserverid);
                        $stmt_insert->bindParam(':ipaddress', $new_ipaddress);
                        $stmt_insert->bindParam(':hostname', $new_hostname);
                        $stmt_insert->bindParam(':ipgateway', $new_gateway);
                        $stmt_insert->bindParam(':ipsubnet', $new_subnet);
                        $stmt_insert->bindParam(':emailaddress', $new_email);
                        $stmt_insert->bindParam(':switching_criteria', $ip_switch_criteria_code);
                        $stmt_insert->bindParam(':selection_criteria', $ip_selection_criteria_code);
                        $stmt_insert->bindParam(':duration_from_last_switch_in_min', $lastduration['last_duration']);
                        $stmt_insert->execute();
                    }
                    else
                    {
                        //just insert record in config table
                        $stmt_insert = $conn->prepare("INSERT INTO current_mailserver_config(mailserverid, ipaddress,hostname, ipgateway, ipsubnet, emailaddress, switching_criteria,
                        selection_criteria)
                        VALUES(:mailserverid, :ipaddress, :hostname, :ipgateway, :ipsubnet, :emailaddress, :switching_criteria, :selection_criteria)");
                        $stmt_insert->bindParam(':mailserverid', $mailserverid);
                        $stmt_insert->bindParam(':ipaddress', $new_ipaddress);
                        $stmt_insert->bindParam(':hostname', $new_hostname);
                        $stmt_insert->bindParam(':ipgateway', $new_gateway);
                        $stmt_insert->bindParam(':ipsubnet', $new_subnet);
                        $stmt_insert->bindParam(':emailaddress', $new_email);
                        $stmt_insert->bindParam(':switching_criteria', $ip_switch_criteria_code);
                        $stmt_insert->bindParam(':selection_criteria', $ip_selection_criteria_code);
                        $stmt_insert->execute();
                    }

                    //Select New From Email Address

                    $stmt_nea = $conn->prepare("SELECT emailaddress FROM current_mailserver_config WHERE mailserverid = :mailserverid AND config_status='Active'");
                    $stmt_nea->bindParam(':mailserverid', $mailserverid);
                    $stmt_nea->execute();
                    $rs_nea = $stmt_nea->fetch(PDO::FETCH_ASSOC);
                    $new_applied_current_email = $rs_nea['emailaddress'];

                    //Check Changes
                    $new_applied_ipaddress = shell_exec("sudo /sbin/ifconfig ".$interface_name." | grep -Po 'inet \K\w+.\w+.\w+.\w+'");
                    $new_applied_hostname = shell_exec('hostname');

                    echo "Check Applied Changes:";
                    echo "<br>";
                    echo "======================";
                    echo "<br>";
                    echo "New IP Address: ".$new_applied_ipaddress;
                    echo "<br>";
                    echo "New Hostname: ".$new_applied_hostname;
                    echo "<br>";
                    echo "New From Email Address: ".$new_applied_current_email;
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                }//if verified check ends
            } // END WHILE LOOP
        }    //Check if swithcing needs to be done or not ENDS (4 conditions)
    }// if ip_switching_execution is Enabled then allow switching

    function random_float ($min,$max) {
        return ($min+lcg_value()*(abs($max-$min)));
     }

    //------------------------------------------------------------xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx----------------------------------------------------------------//
    if(trim($mail_sending_execution)=='Enable')
    {  
       

        $instance_email_send = $system_settings['instance_email_send'];
        //Default: Standard-Send-Time	
        $email_send_criteria= $system_settings['email_send_criteria']; //Standard-Send-Time, Random-Send-Time	
        $email_send_criteria_code = $system_settings['email_send_criteria_code']; //SST, RST
        //Default: Standard-Qunatity
        $email_quantity_criteria =  $system_settings['email_quantity_criteria']; //Standard-Qunatity, Random-Quantity
        $email_quantity_criteria_code= $system_settings['email_quantity_criteria_code'];//SQ, RQ
        $mail_execution_category= $system_settings['mail_execution_category'];//Mailserver-Wise, Campaign-Wise
        //Default: 45
        $random_min_send_interval =  $system_settings['random_min_send_interval']; //45 - 60	
        //Default: 75
        $random_max_send_interval = $system_settings['random_max_send_interval']; // 60 - 75
        //Default: 60
        $standard_send_interval=   $system_settings['standard_send_interval'];//45 - 75	
        //Default: 0.5
        $email_qty_lower_random_limit =  $system_settings['email_qty_lower_random_limit'];//0.5 - 0.7
        //Default: 1
        $email_qty_upper_random_limit = $system_settings['email_qty_upper_random_limit'];//0.7 - 1 

        $customizable_camp_embargo= $system_settings['customizable_camp_embargo']; //by default YES {siwtch to control campaign wise embargo}
        $customizable_org_embargo= $system_settings['customizable_org_embargo']; //by default YES {siwtch to control org embargo}
        $domain_wise_email_send_filter= $system_settings['domain_wise_email_send_filter'];//by default YES (switch to control domainwise mailsend)
        $domain_wise_email_send_offset= $system_settings['domain_wise_email_send_offset'];//number of emails per domain per campaign per interval

        $do_mail_esecution=1;
        $unsubscriber_lock=0;
        $unsubscriber_free=0;
        $domain_lock=0;
        $domain_free=0;
        $embargo_lock=0;
        $embargo_free=0;
        

        echo "Instance Email Send: ".$instance_email_send;
        echo "<br>";
        echo "Email Send Criteria: ".$email_send_criteria;
        echo "<br>";
        echo "Email Quantity Criteria: ".$email_quantity_criteria;
        echo "<br>";
        echo "Email Quantity Lower Random Limit: ".$email_qty_lower_random_limit;
        echo "<br>";
        echo "Email Quantity Upper Random Limit: ".$email_qty_upper_random_limit;
        echo "<br>";
        echo "Mail Execution: ".$mail_execution_category;
        echo "<br>";
        echo "Random Min Send Interval: ".$random_min_send_interval;
        echo "<br>";
        echo "Random Max Send Interval: ".$random_max_send_interval;
        echo "<br>";
        echo "Standard Send Interval: ".$standard_send_interval;
        echo "<br>";
     
        

        //determine email said critera and mail execution category
        if($email_send_criteria_code=='SST')
        {   

            if($mail_execution_category=='Mailserver-Wise')
            {
                $stmt_sst = $conn->prepare("SELECT system_date FROM camp_execution_task INNER JOIN campaign WHERE mailserverid='$mailserverid' 
                AND Camp_Status='Active' AND TIMESTAMPDIFF(MINUTE,system_date,LOCALTIME()) < '$standard_send_interval' 
                order by `cet_id` DESC LIMIT 1");
                $stmt_sst->execute();
                if($stmt_sst->rowCount()> 0)
                {
                    $do_mail_esecution=0;
                }
            }
          
        }

        if($email_send_criteria_code=='RST')
        {
            $random_send_interval = rand($random_min_send_interval,$random_max_send_interval);
            if($mail_execution_category=='Mailserver-Wise')
            {
              
                $stmt_rst = $conn->prepare("SELECT system_date FROM camp_execution_task WHERE mailserverid='$mailserverid' 
                AND Camp_Status='Active' AND TIMESTAMPDIFF(MINUTE,system_date,LOCALTIME()) < '$random_send_interval' 
                order by `cet_id` DESC LIMIT 1");
                $stmt_rst->execute();
                if($stmt_rst->rowCount()> 0)
                {
                    $do_mail_esecution=0;
                }
            }      

        }


        echo "<br>";
        echo "Do Mail Execution: ".$do_mail_esecution;
        echo "<br>";        
      

        if($do_mail_esecution==1)
        {
            $campaingauthors_remaining_data_table1= $mail_server_name."_cron_campaingauthors_remaining_data_table1".
            $crdt = "CREATE TABLE IF NOT EXISTS $campaingauthors_remaining_data_table1 (
            crdt_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (crdt_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($crdt);
            $create->execute();

            $campaingauthors_domaincount_table2= $mail_server_name."_cron_campaingauthors_domaincount_table2";    
            $cdct = "CREATE TABLE IF NOT EXISTS $campaingauthors_domaincount_table2 (
            cdct_id int(11) NOT NULL AUTO_INCREMENT,
            domain_name varchar(50) NOT NULL,
            domain_count int(11) NOT NULL,
            PRIMARY KEY (cdct_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($cdct);
            $create->execute();

            $campaingauthors_offsetwise_domainqty_table3= $mail_server_name."_cron_campaingauthors_offsetwise_domainqty_table3";
            $codqt = "CREATE TABLE IF NOT EXISTS $campaingauthors_offsetwise_domainqty_table3 (
            codqt_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (codqt_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($codqt);
            $create->execute();

            $campaingauthors_allowedqty_table4= $mail_server_name."_cron_campaingauthors_allowedqty_table4";
            $caqt = "CREATE TABLE IF NOT EXISTS $campaingauthors_allowedqty_table4 (
            caqt_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (caqt_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($caqt);
            $create->execute();

            $campaingauthors_tempunsubcriberfree= $mail_server_name."_cron_campaingauthors_tempunsubcriberfree";
            $unsubfree_tbl = "CREATE TABLE IF NOT EXISTS $campaingauthors_tempunsubcriberfree (
            unsubfree_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (unsubfree_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($unsubfree_tbl);
            $create->execute();

            $campaingauthors_tempdomainfree= $mail_server_name."_cron_campaingauthors_tempdomainfree";
            $domainfree_tbl = "CREATE TABLE IF NOT EXISTS $campaingauthors_tempdomainfree (
            domainfree_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (domainfree_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($domainfree_tbl);
            $create->execute();

            $campaingauthors_tempdomainlock= $mail_server_name."_cron_campaingauthors_tempdomainlock";
            $domainlock_tbl = "CREATE TABLE IF NOT EXISTS $campaingauthors_tempdomainlock (
            domainlock_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (domainlock_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($domainlock_tbl);
            $create->execute();

            $campaingauthors_tempembargofree= $mail_server_name."_cron_campaingauthors_tempembargofree";
            $embargofree_tbl = "CREATE TABLE IF NOT EXISTS $campaingauthors_tempembargofree (
            embargofree_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (embargofree_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($embargofree_tbl);
            $create->execute();

            $campaingauthors_tempembargolock= $mail_server_name."_cron_campaingauthors_tempembargolock";
            $embargolock_tbl = "CREATE TABLE IF NOT EXISTS $campaingauthors_tempembargolock (
            embargolock_id int(11) NOT NULL AUTO_INCREMENT,
            CampaingAuthorsID int(11) NOT NULL,
            CampID int(11) NOT NULL,
            rtemid int(11) NOT NULL,
            Initials varchar(50) NOT NULL,
            Journal_title varchar(1000) NOT NULL,
            Role varchar(255) NOT NULL,
            Fname varchar(255) NOT NULL,
            Lastname varchar(255) NOT NULL,
            affiliation varchar(6000) NOT NULL,
            Add1 varchar(500) NOT NULL,
            Add2 varchar(500) NOT NULL,
            Add3 varchar(500) NOT NULL,
            Add4 varchar(500) NOT NULL, 
            Country varchar(255) NOT NULL,
            email varchar(100) NOT NULL,
            article_title varchar(5000) NOT NULL,
            eurekaselect_url varchar(2000) NOT NULL,
            Status varchar(100) NOT NULL,
            PRIMARY KEY (embargolock_id) )
            ENGINE=InnoDB  DEFAULT CHARSET=utf8";
            $create = $conn->prepare($embargolock_tbl);
            $create->execute();
           
        
            //get active campaigns of the mailserver with replytoemail status as active
           
            $sql1= "SELECT  tbl_organizational_unit.orgunit_id, system_setting, CampID,CampName, draft_status, embargo_type, campaign_embargo_days
            FROM tbl_orgunit_user INNER JOIN tbl_organizational_unit INNER JOIN admin INNER JOIN campaign ON tbl_orgunit_user.ou_id=campaign.ou_id AND 
            tbl_orgunit_user.orgunit_id = tbl_organizational_unit.orgunit_id AND tbl_orgunit_user.user_id=admin.AdminId AND ou_status='Active' 
            AND orgunit_status='Active' AND admin.status='Active' AND Camp_Status ='Active' AND mailserverid=:mailserverid AND crtem_status='Active'";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindValue(':mailserverid', $mailserverid);
            $result1 = $stmt1->execute();
            $TotalActiveCamp = $stmt1->rowCount();
                if ($TotalActiveCamp > 0) 
                {
                    $result1 = $stmt1->fetchAll();
                    print_r($result1);

               
            
                 
                    //iterate through each active campaign
                    foreach ($result1 as $row1) 
                    {
                        $campidt= $row1['CampID'];
                        $stmt_get_ctask= $conn->prepare("SELECT max(cet_id) FROM camp_execution_task WHERE CampID='$campidt'");
                        $stmt_get_ctask->execute();
                        //if camp is running for first time then skip the following checking
                        if($stmt_get_ctask->rowCount() > 0)
                        {
                                 //determine email said critera and mail execution category
                            if($email_send_criteria_code=='SST')
                            {   
            
                                if($mail_execution_category=='Campaign-Wise')
                                {
                                    $stmt_sst = $conn->prepare("SELECT system_date FROM camp_execution_task WHERE CampID='$campidt' 
                                    AND TIMESTAMPDIFF(MINUTE,system_date,LOCALTIME()) < '$standard_send_interval' 
                                    order by `cet_id` DESC LIMIT 1");
                                    $stmt_sst->execute();
                                    if($stmt_sst->rowCount()> 0)
                                    {
                                        continue;  
                                    }
                    
                                }
                      
                            }
            
                            if($email_send_criteria_code=='RST')
                            {
                                $random_send_interval = rand($random_min_send_interval,$random_max_send_interval);
                                if($mail_execution_category=='Campaign-Wise')
                                {
                              
                                    $stmt_rst = $conn->prepare("SELECT system_date FROM camp_execution_task WHERE CampID='$campidt' 
                                    AND TIMESTAMPDIFF(MINUTE,system_date,LOCALTIME()) < '$random_send_interval' 
                                    order by `cet_id` DESC LIMIT 1");
                                    $stmt_rst->execute();
                                    if($stmt_rst->rowCount()> 0)
                                    {
                                        continue;
                                    }
                                }

                            }
    
                        }//if camp is running for first time then skip the above checking
                        
                        if($row1['system_setting']=="sys-defined")
                        {
                        	$embargo_implementation_type="sys-defined";
                        	$unsubscription_type="sys-defined";
                        	$domain_block_type="sys-defined";
                            $embargo_duration= $system_settings['embargo_duration'];
                        
                        }
                        else if($row1['system_setting']=="ou-defined")
                        {
                        	$stmts2 = $conn->prepare("SELECT * FROM `orgunit-systemsetting` WHERE status='Active' AND
                        	 orgunit_id=:orgunit_id");
                        	$stmts2->bindValue(':orgunit_id', $row1['orgunit_id']);
                        	$stmts2->execute();
                        	$org_settings= $stmts2->fetch();
                        	if($stmts2->rowCount()>0)
                            {
                        	    $embargo_implementation_type= $org_settings['embargo_implementation_type'];
                        	    $unsubscription_type=$org_settings['unsubscription_type'];
                        	    $domain_block_type=$org_settings['domain_block_type'];
                                $embargo_duration= $org_settings['org_embargo_duration'];
                        	
                        	}
                        }  
                        echo "System Settings: ".$row1['system_setting'];
                        echo "<br>";
                        echo "Embargo Implementation Type: ".$embargo_implementation_type;
                        echo "<br>";
                        echo "Unsubscription Type: ".$unsubscription_type;
                        echo "<br>";
                        echo "Domain Block Type: ".$domain_block_type;
                        echo "<br>";
                        echo "Embargo Duration From Settings: ".$embargo_duration;
                        echo "<br>";
                        echo "Domain Wise Email Send Filter: ".$domain_wise_email_send_filter;
                        echo "<br>";
                        echo "Domain Wise Email Send Offset: ".$domain_wise_email_send_offset;
                        echo "<br>";


                        //check for quantity type
                   
                        if(trim($email_quantity_criteria)=="Random-Quantity")
                        {
                            $camp_email_qty = floor(($instance_email_send/$TotalActiveCamp) * number_format(random_float($email_qty_lower_random_limit,$email_qty_upper_random_limit),1));  
                        }
                        else
                        {
                            $camp_email_qty = floor($instance_email_send / $TotalActiveCamp); //50     
                        }   
                        echo "<br>";
                        echo "Quantity: ".$camp_email_qty;
                        echo "<br>";  
                        //domainwise send
                        if($domain_wise_email_send_filter=='YES')
                        {
                           $dom_clause="substring(email, locate('@',email)+1, length(email)-locate('@',email))";
                            //get all campignauthors data of camp which is not sent into campaingauthors_remaining_data_table1
                            $stmt_all_camp_data = $conn->prepare("INSERT INTO $campaingauthors_remaining_data_table1(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,email, article_title, eurekaselect_url, Status)
                            SELECT CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,email, article_title, eurekaselect_url, Status FROM campaingauthors WHERE CampID = " . $row1['CampID'] . "
					        and Status = 'Not Sent'");
                            $stmt_all_camp_data->execute();

                            //find out unique domain and their counts and insert in campaingauthors_domaincount_table2
                            $stmt_get_dom= $conn->prepare("SELECT $dom_clause as domain_name, 
                            count($dom_clause) as domain_count FROM $campaingauthors_remaining_data_table1 
					        GROUP BY $dom_clause");
                            $stmt_get_dom->execute();
                            $get_domains= $stmt_get_dom->fetchAll();

                            $conn->beginTransaction();
                            foreach($get_domains as $unique_domain)
                            {
                                $insert= $conn->prepare("INSERT INTO $campaingauthors_domaincount_table2(domain_name, domian_count) VALUES(:domain_name, :domian_count)");
                                $insert->bindValue(':domain_name', $unique_domain['domain_name']);
                                $insert->bindValue(':domian_count', $unique_domain['domian_count']);
                                $insert->execute();
                            }
                            $conn->commit();
                            //offsetwise domain qty

                            for($i=1; $i<=$domain_wise_email_send_offset; $i++)
                            {
                                foreach ($get_domains as $unique_domain)
                                {
                                    $select_domain_wise= $conn->prepare("INSERT INTO $campaingauthors_offsetwise_domainqty_table3(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                                    affiliation, Country,email, article_title, eurekaselect_url, Status)
                                    VALUES(:CampaingAuthorsID,:CampID, :rtemid,:Initials, :Journal_title, :Role, :Fname, :Lastname, :Add1, :Add2, :Add3, :Add4,:affiliation, :Country,
				                    :email, :article_title, :eurekaselect_url, :Status)                                    
                                    SELECT CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                                    affiliation, Country,email, article_title, eurekaselect_url, Status  FROM $campaingauthors_remaining_data_table1 WHERE  
                                    $dom_clause= ".$unique_domain['domain_name']." AND 
                                    crdt_id =(SELECT max(crdt_id) FROM $campaingauthors_remaining_data_table1) AND  $dom_clause
                                    NOT IN(SELECT  $dom_clause FROM $campaingauthors_offsetwise_domainqty_table3)");
                                    $select_domain_wise->execute();                               

                                }

                            }
                            //Allowed Qty from offset table into table4
                            $stmt_allowed_qty = $conn->prepare("INSERT INTO $campaingauthors_allowedqty_table4(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,email, article_title, eurekaselect_url, Status)
                            SELECT CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,email, article_title, eurekaselect_url, Status FROM $campaingauthors_offsetwise_domainqty_table3 LIMIT $camp_email_qty");
                            $stmt_allowed_qty->execute();

                        }
                        else
                        {
                              //Allowed Qty from campaignauthors table into table4
                            $stmt_allowed_qty = $conn->prepare("INSERT INTO $campaingauthors_allowedqty_table4(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,email, article_title, eurekaselect_url, Status)
                            SELECT CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,email, article_title, eurekaselect_url, Status FROM campaingauthors WHERE CampID = " . $row1['CampID'] . "
                            and Status = 'Not Sent'
                            LIMIT $camp_email_qty");
                            $stmt_allowed_qty->execute();
                            
                        }                        
                        $found_qty=$stmt_allowed_qty->rowCount();
                        echo "Found Quantity: ".$found_qty;
                        echo "<br>";
                      


                        //chunk of campaigns found
		                if ($found_qty> 0) 
                        {

			                //Get data from campaingauthors_allowedqty_table4 make it unsubscriber free and insert in tempUnsubccriberFree
  
			                if($unsubscription_type=="sys-defined")
                            {
			                	$sql2 ="INSERT INTO $campaingauthors_tempunsubcriberfree(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
			                	affiliation, Country,
			                	email, article_title, eurekaselect_url, Status)
                                SELECT ampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
			                	affiliation, Country,
			                	email, article_title, eurekaselect_url, Status FROM $campaingauthors_allowedqty_table4
			                	WHERE email NOT IN (select UnsubscriberEmail FROM unsubscriber WHERE Status='Enabled' AND Category <> 'ou-dedicated')";
			                }
			                if($unsubscription_type=="ou-dedicated" || $unsubscription_type=="ou-hybrid")
                            {
			                	$sql2 = "INSERT INTO $campaingauthors_tempunsubcriberfree(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
			                	affiliation, Country,
			                	email, article_title, eurekaselect_url, Status)
                                SELECT ampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
			                	affiliation, Country,
			                	email, article_title, eurekaselect_url, Status FROM $campaingauthors_allowedqty_table4
			                	WHERE email NOT IN (select UnsubscriberEmail FROM orgunit_unsubscriber AND  orgunit_id = " .  $row1['orgunit_id'] . ")";
                
			                }
                            $stmt2 = $conn->prepare($sql2);
                            $stmt2->execute();
                            $unsubscriber_free = $stmt2->rowCount();

                            //Update status of remaining email that have unsubcribed status

			                $sql = "SELECT * FROM $campaingauthors_allowedqty_table4
			                WHERE email NOT IN (select email FROM  $campaingauthors_tempunsubcriberfree)";
			                $stmt = $conn->prepare($sql);
			                $unsub = $stmt->execute();
			                $unsub = $stmt->fetchAll();

                            $unsubscriber_lock= count($unsub);
                          
			                foreach ($unsub as $unsubrow)
                            {
			                	$email_array.= trim($unsubrow['email']).",";
			                
                            }
                            $email_array=trim($email_array,",");
                            $sql = "UPDATE campaingauthors 
                            SET Status = 'Unsubscribed lock'
                            WHERE CampID = " . $row1['CampID'] . " AND email IN($email_array)";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                          
                            //make domain_clause---------------------------------------------
                            if(	$domain_block_type=="sys-defined")
                            {
                                //blocked domains filter code
                                $domain_block = "";
                                $domain_clause = "";
                                $sql_blk_dmn = "SELECT * FROM `blocked_domains` where domain_status = 'Active'";
                                $stmt_blk_dmn = $conn->prepare($sql_blk_dmn);
                                $stmt_blk_dmn->execute();
                                $data_blk_dmn = $stmt_blk_dmn->fetchAll();
                                $bd = 0;
                                foreach($data_blk_dmn as $bd_ele)
                                {
                                    $bd++;
                                    if($bd <> 1)
                                    {
                                        $domain_block .= ",";
                                    }
                                    $domain_block .= chr(39).$bd_ele['domain_name'].chr(39);
                                    
                                }
                                $domain_clause = "  substring(email, locate(".chr(34)."@".chr(34).",email)+1, length(email)-locate(".chr(34)."@".chr(34).",email)) not in ($domain_block)";
                                //blocked domain filtere code
                            }
                            if($domain_block_type=="ou-dedicated" || $domain_block_type=="ou-hybrid")
                            {
                                //blocked domains filter code
                                $domain_block = "";
                                $domain_clause = "";
                                $sql_blk_dmn = "SELECT * FROM `blocked_domain_org` where domain_status = 'Active' AND orgunit_id = " .  $row1['orgunit_id'] . "";
                                $stmt_blk_dmn = $conn->prepare($sql_blk_dmn);
                                $stmt_blk_dmn->execute();
                                $data_blk_dmn = $stmt_blk_dmn->fetchAll();
                                
                                $bd = 0;
                                foreach($data_blk_dmn as $bd_ele)
                                {
                                  $bd++;
                                  if($bd <> 1)
                                  {
                                      $domain_block .= ",";
                                  }
                                  $domain_block .= chr(39).$bd_ele['domain_name'].chr(39);
                                }

                                $domain_clause = "  substring(email, locate(".chr(34)."@".chr(34).",email)+1, length(email)-locate(".chr(34)."@".chr(34).",email)) not in ($domain_block)";
                                //blocked domain filtere code
                            }

                            //Filter domain
                            //Get data from campaingauthors_tempunsubcriberfree make it blockeddomain free and insert in campaingauthors_tempdomainfree


                            $sql2 ="INSERT INTO $campaingauthors_tempdomainfree(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,
                            email, article_title, eurekaselect_url, Status)
                            SELECT CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country,
                            email, article_title, eurekaselect_url, Status FROM $campaingauthors_tempunsubcriberfree WHERE $domain_clause";
                            $stmt2 = $conn->prepare($sql2);
                            $stmt2->execute();
                            $domain_free=$stmt2->rowCount();                         

                            //Get and Update status of remaining email that have domain blocked status and insert in tempdomainlock
                            $sql = "INSERT INTO $campaingauthors_tempdomainlock(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country, email, article_title, eurekaselect_url, Status)
                            SELECT CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                            affiliation, Country, email, article_title, eurekaselect_url, Status FROM $campaingauthors_tempunsubcriberfree
                            WHERE email NOT IN (SELECT email FROM $campaingauthors_tempdomainfree)";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            
                            $domain_lock=$stmt->rowCount();

                            //update domain lock status
                            $sql= "UPDATE campaingauthors SET `Status` = 'Domain lock'
                            WHERE email IN (SELECT email FROM $campaingauthors_tempdomainlock)";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            
			                //Check For Embargo free and insert in tempEmbargoFree
                
			                //NOT IN(SELECT email FROM email_embargo WHERE DATEDIFF(:date_cur, use_date) <= 	:campaign_embargo_day)
			                //means locked data (29<=30 means still locked) so rest is embargo free
			                //but there is possibility that some data im campaingauthors_tempdomainfree is totally new
			                //thats why insert otherwise just update

                            //fill campaig_embargo with appropriate variable
                            if($customizable_org_embargo=="NO")
                            {   
                                $campaign_embargo_days= $system_settings['embargo_duration'];

                            }
                            else 
                            {
                                if($customizable_camp_embargo=="NO")
                                {
                                    $campaign_embargo_days=$embargo_duration; //(from settings ou or sys whatever settings say)

                                }
                                else
                                {
                                    $campaign_embargo_days=$row1['campaign_embargo_days'];
                                }
                            }
                          

			                if ($embargo_implementation_type = "sys-defined") 
                            {
                                $sql_get_embargofree =  "SELECT * FROM
                                                        $campaingauthors_tempdomainfree
                                                        WHERE
                                                        CampID = " . $row1['CampID'] . " AND(
                                                        email NOT IN(
                                                        SELECT
                                                        email
                                                        FROM
                                                        email_embargo
                                                        WHERE
                                                        DATEDIFF(:date_cur, use_date) <= 	:campaign_embargo_days) AND (email NOT IN(
                                                        SELECT
                                                        email
                                                        FROM
                                                        cron_temp_email_embargo
                                                        )))";
                                $stmt2 = $conn->prepare($sql_get_embargofree);
                                $stmt2->bindValue(':date_cur', $date);
                                $stmt2->bindValue(':campaign_embargo_days', $campaign_embargo_days);
                                $embargofree = $stmt2->execute();
                                $embargofree = $stmt2->fetchAll();
                            }                
    
    
                            //if embargo_implementation_type="organizational_dedicated" check onyl from OE but add to EE(with old date) and OE as well.
                            if($embargo_implementation_type = "ou-dedicated" || $embargo_implementation_type = "ou-hybrid")
                            {
                                $sql_get_embargofree =  "SELECT * FROM
                                                        $campaingauthors_tempdomainfree
                                                        WHERE
                                                        CampID = " . $row1['CampID'] . " AND email NOT IN(SELECT  email  FROM  email_embargorg  WHERE orgunit_id = " .  $row1['orgunit_id'] . " AND
                                                        DATEDIFF(:date_cur, use_date) <= 	:campaign_embargo_days) AND email NOT IN(
                                                        SELECT
                                                        email
                                                        FROM
                                                        cron_temp_email_embargorg WHERE  orgunit_id = " .  $row1['orgunit_id'] . ")";
                                $stmt2 = $conn->prepare($sql_get_embargofree);
                                $stmt2->bindValue(':date_cur', $date);
                                $stmt2->bindValue(':campaign_embargo_days', $campaign_embargo_days);
                                $embargofree = $stmt2->execute();
                                $embargofree = $stmt2->fetchAll();
                            }

                            
                        

                            foreach ($embargofree as $embargofreerow) 
                            {
                                //Insert in embargo_free 
                                $insert = "INSERT INTO 	$campaingauthors_tempembargofree(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                                affiliation, Country,email, article_title, eurekaselect_url, Status)
                                VALUES (:CampaingAuthorsID,:CampID, :rtemid,:Initials, :Journal_title, :Role, :Fname, :Lastname, :Add1, :Add2, :Add3, :Add4,:affiliation, :Country,
                                :email, :article_title, :eurekaselect_url, :Status)";
                                $stmt = $conn->prepare($insert);
                                $stmt->bindValue(':CampaingAuthorsID', $embargofreerow["CampaingAuthorsID"]);
                                $stmt->bindValue(':CampID', $embargofreerow["CampID"]);
                                $stmt->bindValue(':rtemid', $embargofreerow["rtemid"]);
                                $stmt->bindValue(':Initials', $embargofreerow["Initials"]);
                                $stmt->bindValue(':Journal_title', $embargofreerow["Journal_title"]);
                                $stmt->bindValue(':Role', $embargofreerow["Role"]);
                                $stmt->bindValue(':Fname', $embargofreerow["Fname"]);
                                $stmt->bindValue(':Lastname', $embargofreerow["Lastname"]);
                                $stmt->bindValue(':Add1', $embargofreerow["Add1"]);
                                $stmt->bindValue(':Add2', $embargofreerow["Add2"]);
                                $stmt->bindValue(':Add3', $embargofreerow["Add3"]);
                                $stmt->bindValue(':Add4', $embargofreerow["Add4"]);
                                $stmt->bindValue(':affiliation', $embargofreerow["affiliation"]);
                                $stmt->bindValue(':Country', $embargofreerow["Country"]);
                                $stmt->bindValue(':email', $embargofreerow["email"]);
                                $stmt->bindValue(':article_title', $embargofreerow["article_title"]);
                                $stmt->bindValue(':eurekaselect_url', $embargofreerow["eurekaselect_url"]);
                                $stmt->bindValue(':Status', $embargofreerow["Status"]);
                                $stmt->execute();
                
                                //get server and ipdetails
                              
                                $getserver = "SELECT * FROM current_mailserver_config WHERE mailserverid= $mailserverid AND config_status='Active'";
                                $stmt = $conn->prepare($getserver);
                                $stmt->execute();
                                $ipserverdetails = $stmt->fetch();

                                //check if record already exists is email_embargo table
                                $stmt = $conn->prepare("SELECT email FROM email_embargo WHERE email=:email");
                                $stmt->bindValue(':email', $embargofreerow["email"]);
                                if ($stmt->execute()) 
                                {
                                    $count_email = $stmt->rowCount();
                                }
                
                
                                //if embargo_implementation_type="organizational_dedicated" insert old date
                                if ($embargo_implementation_type = "organizational_dedicated")
                                {
                                    $date="2017-06-05";
                                }
                
                                if ($count_email < 1) 
                                {
                                    //Insert in embargo_email
                                    $insert = "INSERT INTO 	email_embargo(email,use_date, CampID, mailserverid, ipaddress, hostname,emailaddress)
                                    VALUES (:email,:use_date, :CampID, :mailserverid, :ipaddress, :hostname, :emailaddress)";
                                    $stmt = $conn->prepare($insert);
                                    $stmt->bindValue(':email', $embargofreerow["email"]);
                                    $stmt->bindValue(':use_date', $date);
                                    $stmt->bindValue(':CampID', $embargofreerow["CampID"]);
                                    $stmt->bindValue(':mailserverid', $ipserverdetails["mailserverid"]);
                                    $stmt->bindValue(':ipaddress', $ipserverdetails["ipaddress"]);
                                    $stmt->bindValue(':hostname', $ipserverdetails["hostname"]);
                                    $stmt->bindValue(':emailaddress', $ipserverdetails["emailaddress"]);
                                    $stmt->execute();
                                }
                                //update already existing record
                                else 
                                {
                                   //for update
                                    //insert in temp_embargo_cron table
                                    $stmt_ins_emb=$conn->prepare("INSERT INTO cron_temp_email_embargo(email,use_date, CampID, mailserverid, ipaddress, hostname, emailaddress)
                                    VALUES(:email, :use_date, :CampID, :mailserverid, :ipaddress, :hostname, :emailaddress)");
                                    $stmt_ins_emb->bindValue(':email', $embargofreerow["email"]);
                                    $stmt_ins_emb->bindValue(':use_date', $date);
                                    $stmt_ins_emb->bindValue(':mailserverid', $ipserverdetails["mailserverid"]);
                                    $stmt_ins_emb->bindValue(':ipaddress', $ipserverdetails["ipaddress"]);
                                    $stmt_ins_emb->bindValue(':hostname', $ipserverdetails["hostname"]);
                                    $stmt_ins_emb->bindValue(':emailaddress', $ipserverdetails["emailaddress"]);
                                    $stmt_ins_emb->execute();
                               


                                }
                
                                // incase of OH and OD insert and update in OE as well
                                //check if record already exists is email_embargo table
                                if ($embargo_implementation_type = "organizational_dedicated" || $embargo_implementation_type = "organizational_hybrid")
                                {
                                    $stmt = $conn->prepare("SELECT email FROM email_embargorg WHERE email=:email AND orgunit_id=:orgunit_id");
                                    $stmt->bindValue(':email', $embargofreerow["email"]);
                                    $stmt->bindValue(':orgunit_id',  $row1['orgunit_id']);
                                    if ($stmt->execute())
                                    {
                                        $count_emailorg = $stmt->rowCount();
                                    }
                
                                    //get email_embargoid from email_embargo table
                                    $stmt = $conn->prepare("SELECT email_embargoid FROM email_embargo WHERE email=:email");
                                    $stmt->bindValue(':email', $embargofreerow["email"]);
                                    $stmt->execute();
                                    $email_embargoid= $stmt->fetch();
                                    $email_embargoid= $email_embargoid["email_embargoid"];

                                    if ($count_emailorg < 1) 
                                    { 
            
                                        //Insert in email_embargorg
                                        $insert = "INSERT INTO 	email_embargorg(email_embargoid,email,use_date, CampID, orgunit_id)
                                        VALUES (:email_embargoid, :email,:use_date, :CampID, :orgunit_id)";
                                        $stmt = $conn->prepare($insert);
                                        $stmt->bindValue(':email_embargoid',$email_embargoid );
                                        $stmt->bindValue(':email', $embargofreerow["email"]);
                                        $stmt->bindValue(':use_date', $date);
                                        $stmt->bindValue(':CampID', $embargofreerow["CampID"]);
                                        $stmt->bindValue(':orgunit_id',  $row1['orgunit_id']);
                                        $stmt->execute();
                                    }
                                    //update already existing record
                                    else 
                                    {
                                        
                                        //for update
                                        //insert to cron_temp_embargorg
                                        $stmt_ins_emborg= $conn->prepare("INSERT INTO cron_temp_email_embargorg(email_embargoid, email, use_date, CampID,orgunit_id) 
                                        VALUES(:email_embargoid, :email, :use_date, :CampID)");
                                        $stmt_ins_emborg->bindValue(':email_embargoid',$email_embargoid);
                                        $stmt_ins_emborg->bindValue(':email',$embargofreerow["email"]);
                                        $stmt_ins_emborg->bindValue(':use_date',$date);
                                        $stmt_ins_emborg->bindValue(':CampID',$embargofreerow["CampID"]);
                                        $stmt_ins_emborg->bindValue(':orgunit_id',$row1['orgunit_id']);
                                        $stmt_ins_emborg->execute();
                                    }
                
                            //////////////////////////////////////////////////////////
                                }
                            }

                            if($embargo_implementation_type = "sys-defined")
                            {
                                //get embargo locked data and put it inside embargo lock table and set its status as embargo lock
                                $sql_get_embargolock =  "SELECT *
                                                        FROM
                                                        $campaingauthors_tempdomainfree
                                                        WHERE
                                                        CampID = " . $row1['CampID'] . " AND(
                                                        email IN(
                                                        SELECT
                                                            email
                                                        FROM
                                                            email_embargo
                                                        WHERE
                                                        DATEDIFF(:date_cur, use_date) <= 	:campaign_embargo_days))";                         
                                $stmt2 = $conn->prepare($sql_get_embargolock);
                                $stmt2->bindValue(':date_cur', $date);
                                $stmt2->bindValue(':campaign_embargo_days', $campaign_embargo_days);
                                $embargolock = $stmt2->execute();
                                $embargolock = $stmt2->fetchAll();
                            }
                    
                            if($embargo_implementation_type = "ou-dedicated" || $embargo_implementation_type = "ou-hybrid")
                            {
                                //get embargo locked data and put it inside embargo lock table and set its status as embargo lock
                                $sql_get_embargolock = "SELECT *
                                                        FROM
                                                        $campaingauthors_tempdomainfree
                                                        WHERE
                                                        CampID = " . $row1['CampID'] . " AND(
                                                        email IN(
                                                        SELECT
                                                            email
                                                        FROM
                                                            email_embargorg
                                                        WHERE
                                                        orgunit_id = " .  $row1['orgunit_id'] . " AND
                                                        DATEDIFF(:date_cur, use_date) <= 	:campaign_embargo_days))";
                                $stmt2 = $conn->prepare($sql_get_embargolock);
                                $stmt2->bindValue(':date_cur', $date);
                                $stmt2->bindValue(':campaign_embargo_days', $campaign_embargo_days);
                                $embargolock = $stmt2->execute();
                                $embargolock = $stmt2->fetchAll();
                    
                            }


                            $embargo_lock= count($embargolock);
                          
                            $conn->beginTransaction();
                            foreach ($embargolock as $embargolockrow) 
                            {
                                    //Insert in embargo_lock 
                                    $insert = "INSERT INTO 	$campaingauthors_tempembargolock(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                                    affiliation, Country,
                                    email, article_title, eurekaselect_url, Status)
                                    VALUES (:CampaingAuthorsID,:CampID, :rtemid, :Initials, :Journal_title, :Role, :Fname, :Lastname, :Add1, :Add2, :Add3, :Add4,:affiliation, :Country,
                                    :email, :article_title, :eurekaselect_url, :Status)";
                                    $stmt = $conn->prepare($insert);
                                    $stmt->bindValue(':CampaingAuthorsID', $embargolockrow["CampaingAuthorsID"]);
                                    $stmt->bindValue(':CampID', $embargolockrow["CampID"]);
                                    $stmt->bindValue(':rtemid', $embargolockrow["rtemid"]);
                                    $stmt->bindValue(':Initials', $embargolockrow["Initials"]);
                                    $stmt->bindValue(':Journal_title', $embargolockrow["Journal_title"]);
                                    $stmt->bindValue(':Role', $embargolockrow["Role"]);
                                    $stmt->bindValue(':Fname', $embargolockrow["Fname"]);
                                    $stmt->bindValue(':Lastname', $embargolockrow["Lastname"]);
                                    $stmt->bindValue(':Add1', $embargolockrow["Add1"]);
                                    $stmt->bindValue(':Add2', $embargolockrow["Add2"]);
                                    $stmt->bindValue(':Add3', $embargolockrow["Add3"]);
                                    $stmt->bindValue(':Add4', $embargolockrow["Add4"]);
                                    $stmt->bindValue(':affiliation', $embargolockrow["affiliation"]);
                                    $stmt->bindValue(':Country', $embargolockrow["Country"]);
                                    $stmt->bindValue(':email', $embargolockrow["email"]);
                                    $stmt->bindValue(':article_title', $embargolockrow["article_title"]);
                                    $stmt->bindValue(':eurekaselect_url', $embargolockrow["eurekaselect_url"]);
                                    $stmt->bindValue(':Status', $embargolockrow["Status"]);
                                    $stmt->execute();
                            }
                            $conn->commit();
                            //update embargo lock status
                            $sql = "UPDATE campaingauthors
                            SET `Status` = 'Embargo lock'
                            WHERE email IN (select email FROM $campaingauthors_tempembargolock)";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();

                            
			                // foreach ($result2 as $row2)
                            $embargo_free= count($embargofree);
			                foreach($embargofree as $row2) 
                            {

                 
                 				//1st We Check "Campaign draft_type Status"
                 				$draft_type = $row1['draft_status'];
                 				//1st We Check "Campaign draft_type Status"
                 				$stmt_D = $conn->prepare("SELECT subscription_draft from draft WHERE CampID  = " . $row1['CampID'] . "");
                 				$stmt_D->execute();
                 				$result_D = $stmt_D->fetch(PDO::FETCH_ASSOC);
                 				$cmp_draft = $result_D['subscription_draft'];
                 
                 				//Email Script For Draft
                 				if($draft_type == 'subscriptionDraft')
                                {
                 
					                //Campaign Type
					                $sql_camp ="SELECT Journal_title,article_title
					                			FROM campaingauthors 
					                			WHERE CampID = :CampID";
					                $stmt_camp = $conn->prepare($sql_camp);
					                $stmt_camp->bindValue(':CampID', $row1['CampID']);
					                $stmt_camp->execute();
					                $cmp_draft_new = $cmp_draft;
                 
            
            
					                if ($stmt_camp->rowCount() > 0) 
                                    {
            
					            	    $row_camp = $stmt_camp->fetch(PDO::FETCH_ASSOC);
					            	    $Journal_title = $row_camp['Journal_title'];
					            	    $article_title = $row_camp['article_title'];
						                $Draft_tags = ["{Journal_title}", "{article_title}"];
						                $DB_Rows   = ["" . $row2['Journal_title'] . "", "" . $row2['article_title'] . ""];
						                $cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
					                }


					                $mailserverid = $row1['mailserverid'];
					                $sql_from_email =  "SELECT emailaddress
											        FROM
												    current_email
											        WHERE mailserverid=$mailserverid";
					                $stmt_email = $conn->prepare($sql_from_email);
					                $stmt_email->execute();
					                $result_email = $stmt_email->fetch();
					                $to = $row2['email'];
					                $subject = "" . $row1['CampName']; //$row1['CampName'];
					                $headers = "MIME-Version: 1.0" . "\r\n";
					                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					                $headers .= "From:Articles Contributions<" . $ipserverdetails['emailaddress'];
					                ">" . "\r\n";
					                $headers .= 'From:Articles Contributions<>' . "\r\n";
					                $headers .= 'Reply-To: ' . $row1['rtemid'] . '' . "\r\n";
					                $FirstName = html_entity_decode($row2['Fname']);
                                    if (!empty($LastName)) 
                                    {
                                        $LastName = html_entity_decode($row2['Lastname']);
                                    }
                                    else
                                    {
					                	$LastName = "";
                                    }
                                    
					                if (empty($FirstName)) 
                                    {
					                	$FirstName = "Colleague";
					                } 
                                    else
                                    {
					                	$FirstName = "Dr. " . $FirstName;
                                    }


                                    // ------------------------------------------------
                                    $message_oaarticles="";
                                    $CampID=$row1['CampName'];

                                    $art_type="SELECT ctype_article_list,ctype_name From Campaign_type ct ,Campaign c 
                                    where  ct.ctype_id=c.ctype_id and c.CampID='$CampID'";
                                    $art_type=$conn->prepare($art_type);
                                    $art_type->execute();
                                    
                                    $ctype_article_list=$row['ctype_article_list'] ;    

                                    include "../verify_alert(3).php";
                                    //---------------------------------
                                    if (isset($CampID) && $ctype_article_list=='Yes') {

                                        $draftalert="<p>" . html_entity_decode($message_oaarticles) . "</p>";

                                    } else { 
                                        $draftalert="Dear " . $FirstName . " " . $LastName . ",<br/><br/>
                                                    <p>" . html_entity_decode($cmp_draft_new) . "</p>"; }    
                                    //-------------------------------------------------------
                                    
                                    

					                $message="<html>
										    </body>	
										    <div style=' width:90%; padding:20px;text-align: justify;'>	
										    " . $draftalert . "
											<img src='emailTracker.php?CAID=" . $row2['CampaingAuthorsID'] . "&CID=" . $row1['CampID'] . "' alt=''/>
										    <p style='text-align:center;'><span style='text-align: center;'>Disclaimer:</span>If you prefer not to receive any further emails, please send us an email with the subject line <u><a href='unsubscriber.php?CAID=" . $row2["CampaingAuthorsID"] . "' target='_blank'>UNSUBSCRIBE</u></a>.</p>
										    </div>
										    </body>
									        </html>";
					                echo $message;


					                //mail($to, $subject, $message, $headers);
				                }
			                }

                            //Select first and last id and email from table4

                            $stmt_F= $conn->prepare("select CampID,`CampaingAuthorsID`,`email`FROM $campaingauthors_allowedqty_table4 order by `caqt_id` ASC LIMIT 1");
                            $stmt_F->execute();
                            $First_data= $stmt_F->fetch();
                            $stmt_L=$conn->prepare("select CampID,`CampaingAuthorsID`,`email` from $campaingauthors_allowedqty_table4 order by `caqt_id` DESC LIMIT 1");
                            $stmt_L->execute();
                            $Last_data = $stmt_L->fetch();
                            //camp execution insert data
                            $stmt_ce= $conn->prepare("INSERT INTO camp_execution_task(CampID,FCAID, LCAID, allowed_qty, found_qty, unsubscriber_lock, unsubscriber_free,
                            domain_lock, domain_free, embargo_lock, embargo_free, FAuthor_email, LAuthor_email, 
                            cmc_id,system_date,email_send_criteria,mail_execution_category,domain_wise_email_send_filter, domain_wise_email_send_offset)
                            VALUES(:CampID, :FCAID, :LCAID, :allowed_qty, :found_qty, :unsubscriber_lock, :unsubscriber_free,
                            :domain_lock, :domain_free, :embargo_lock, :embargo_free, :FAuthor_email, :LAuthor_email, :cmc_id, NOW(), :email_send_criteria, :mail_execution_category,
                            :domain_wise_email_send_filter, :domain_wise_email_send_offset)");
                            $stmt_ce->bindvalue(':FCAID', $First_data['CampID']);
                            $stmt_ce->bindvalue(':FCAID', $First_data['CampaingAuthorsID']);
                            $stmt_ce->bindvalue(':LCAID', $Last_data['CampaingAuthorsID']);
                            $stmt_ce->bindvalue(':allowed_qty', $camp_email_qty);
                            $stmt_ce->bindvalue(':found_qty', $found_qty);
                            $stmt_ce->bindvalue(':unsubscriber_lock', $unsubscriber_lock);
                            $stmt_ce->bindvalue(':unsubscriber_free', $unsubscriber_free);
                            $stmt_ce->bindvalue(':domain_lock', $domain_lock);
                            $stmt_ce->bindvalue(':domain_free', $domain_free);
                            $stmt_ce->bindvalue(':embargo_lock', $embargo_lock);
                            $stmt_ce->bindvalue(':embargo_free', $embargo_free);
                            $stmt_ce->bindvalue(':FAuthor_email', $First_data['email']);
                            $stmt_ce->bindvalue(':LAuthor_email', $Last_data['email']);
                            $stmt_ce->bindvalue(':cmc_id', $ipserverdetails['cmc_id'] );
                            $stmt_ce->bindvalue(':mail_execution_category', $mail_execution_category);
                            $stmt_ce->bindvalue(':email_send_criteria', $email_send_criteria_code);
                            $stmt_ce->bindvalue(':domain_wise_email_send_filter', $domain_wise_email_send_filter);
                            $stmt_ce->bindvalue(':domain_wise_email_send_offset', $domain_wise_email_send_offset);
                            $stmt_ce->execute();

                            //Fill domainwise_details table from camp_execution_task

                            //Fill sent_emails_address from camp_execution_task

			                //Truncate tables
			                $sqlT1 = "TRUNCATE TABLE $campaingauthors_tempembargofree";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
			                $sqlT1 = "TRUNCATE TABLE  $campaingauthors_tempembargolock";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
			                $sqlT1 = "TRUNCATE TABLE $campaingauthors_tempunsubcriberfree";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
			                $sqlT1 = "TRUNCATE TABLE $campaingauthors_tempdomainfree";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
			                $sqlT1 = "TRUNCATE TABLE $campaingauthors_tempdomainlock";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
                            $sqlT1 = "TRUNCATE TABLE $campaingauthors_remaining_data_table1";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
                            $sqlT1 = "TRUNCATE TABLE $campaingauthors_domaincount_table2";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
                            $sqlT1 = "TRUNCATE TABLE $campaingauthors_offsetwise_domainqty_table3";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
                            $sqlT1 = "TRUNCATE TABLE $campaingauthors_allowedqty_table4";
			                $stmtT1 = $conn->prepare($sqlT1);
			                $stmtT1->execute();
                    
                            //print for testing

			                $sql = "SELECT * 
                            FROM campaingauthors
                            WHERE CampID = " . $row1['CampID'] . "";
                            $stmt = $conn->prepare($sql);
                            if ($stmt->execute())
                            {
                                $message ="<html>
                                                <body>
                                       
                                                    <table border=" . chr(34) . "1" . chr(34) . ">
                                                    <thead>
                                                    <tr>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . "> CampaingAuthorsID </th>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">CampID</th>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">email</th>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">sending_IP</th>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">from_email</th>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">sending_hostname</th>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">Status </th>
                                                        <th style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">Sent_email_datetime </th> 
                                                    </tr>
                                                    </thead>
                                                    <tbody>";

                                while ($row = $stmt->fetch())
                                {

                                        $message .=     "<tr>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['CampaingAuthorsID'] . "</td>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['CampID'] . "</td>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['email'] . " </td>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['sending_IP'] . "</td>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['from_email'] . "</td>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['sending_hostname'] . "</td>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['Status'] . "</td>
                                                        <td style=" . chr(34) . "text-align:center; padding:5px;" . chr(34) . ">" . $row['Sent_email_datetime'] . "</td>
        
                                                    </tr>";
                                }
                                $message .= "      </tbody>
                                                   </table>
                                              
                                                </body>
                                            </html>";
                            }

                            echo $message;
			
        
                        }//chunk of campaign is found
                        else
                        {
                           //Update record in Campaign table
                            $sql4="UPDATE `campaign` SET `Camp_Status` = 'Completed', Camp_Send_Date = Now() WHERE CampID = " . $row1['CampID'] . "";
                            $stmt4 = $conn->prepare($sql4);
                            $result4 = $stmt4->execute();
                            
                            //Update record in Activity table
                            $sql5 ="UPDATE activity SET activity_succeed = 1 WHERE CampID =" . $row1['CampID'] . "";
                            $stmt5 = $conn->prepare($sql5);
                            $result5 = $stmt5->execute();
                
                            //Archive
                            $sql_archive = "SELECT * FROM campaingauthors WHERE CampID=" . $row1['CampID'] . " AND Camp_Status='Completed'";
                            $stmt_archive = $conn->prepare($sql_archive);
                            $stmt_archive->execute();
                            $To_archive_data =	$stmt_archive->fetchAll();
                
                            $conn->beginTransaction();
                            foreach ($To_archive_data as $data) 
                            {
                               //Insert in archive
                                $insert = "INSERT INTO 	campaingauthors_comp_archive(CampaingAuthorsID,CampID,rtemid,Initials, Journal_title, Role, Fname, Lastname,Add1, Add2, Add3, Add4,
                                affiliation, Country,
                                email, article_title, eurekaselect_url, sending_IP, from_email, sending_hostname, Status, Sent_email_datetime)
                                VALUES (:CampaingAuthorsID,:CampID, :rtemid, :Initials, :Journal_title, :Role, :Fname, :Lastname, :Add1, :Add2, :Add3, :Add4,:affiliation, :Country,
                                :email, :article_title, :eurekaselect_url, :sending_IP, :from_email, :sending_hostname, :Status, :Sent_email_datetime)";

                                $stmt = $conn->prepare($insert);
                                $stmt->bindValue(':CampaingAuthorsID', $data["CampaingAuthorsID"]);
                                $stmt->bindValue(':CampID', $data["CampID"]);
                                $stmt->bindValue(':rtemid', $data["rtemid"]);
                                $stmt->bindValue(':Initials', $data["Initials"]);
                                $stmt->bindValue(':Journal_title', $data["Journal_title"]);
                                $stmt->bindValue(':Role', $data["Role"]);
                                $stmt->bindValue(':Fname', $data["Fname"]);
                                $stmt->bindValue(':Lastname', $data["Lastname"]);
                                $stmt->bindValue(':Add1', $data["Add1"]);
                                $stmt->bindValue(':Add2', $data["Add2"]);
                                $stmt->bindValue(':Add3', $data["Add3"]);
                                $stmt->bindValue(':Add4', $data["Add4"]);
                                $stmt->bindValue(':affiliation', $data["affiliation"]);
                                $stmt->bindValue(':Country', $data["Country"]);
                                $stmt->bindValue(':email', $data["email"]);
                                $stmt->bindValue(':article_title', $data["article_title"]);
                                $stmt->bindValue(':eurekaselect_url', $data["eurekaselect_url"]);
                                $stmt->bindValue(':sending_IP', $data["ipaddress"]);
                                $stmt->bindValue(':from_email', $data["emailaddress"]);
                                $stmt->bindValue(':sending_hostname', $data["hostname"]);
                                $stmt->bindValue(':Sent_email_datetime', $data["Sent_email_datetime"]);
                                $stmt->bindValue(':Status', $data["Status"]);
                                $stmt->execute();
                           }
                           $conn->commit();
               
                            //DELETE archived data from campaginauthors
                            $sql_delete = "DELETE FROM campaingauthors WHERE CampID=" . $row1['CampID'] . "";
                            $stmt_delete = $conn->prepare($sql_delete);
                            $stmt_delete->execute();
                       } //else ends
                        
                    } //iterate through each active campaign

                } // if Active campaigns found on the mailserver

            }//do mail execution==1







    }//mail_Sending='Enable'

} //mailserver count should be 1

//Close Database Connection
$conn=null;
sem_release($semaphore);
?>