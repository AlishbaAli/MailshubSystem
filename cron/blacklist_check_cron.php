<?php

$servername = "localhost";
$username = "mailshub_admin";
$password = "VFD1^*srYlH+"; 

try {
    $conn = new PDO("mysql:host=$servername; dbname=mailshub_db", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "Connected successfully"; 
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

//Get API Key from system settings
$stmt_ss=$conn->prepare("SELECT url, api_key, mailserver_min_active_ips FROM system_setting WHERE status='Active'");
$stmt_ss->execute();
$result_ss=$stmt_ss->fetch();

$mailserver_min_active_ips = $result_ss['mailserver_min_active_ips'];
$url = $result_ss['url'];
$api_key= $result_ss['api_key'];

//Get IP Details from system settings
$stmt_ipd=$conn->prepare("SELECT ipdetailid, ipaddress, hostname, mailserverid FROM ipdetails WHERE ipstatus <> 'In Active'");
$stmt_ipd->execute();
$result_ipd=$stmt_ipd->fetchAll();

foreach ($result_ipd as $row_ipd) 
{
    $ipaddress = $row_ipd['ipaddress'];
    $hostname = $row_ipd['hostname'];
    $mailserverid = $row_ipd['mailserverid'];
    $ipdetailid = $row_ipd['ipdetailid'];

    //API Link
    $checkbyip = $url.$ipaddress."?authorization=".$api_key;
    $checkbyhost = $url.$hostname."?authorization=".$api_key;


    $ip_json = file_get_contents($checkbyip);
    $host_json = file_get_contents($checkbyhost);

    $ip_data = json_decode($ip_json);
    $host_data = json_decode($host_json);

    $failed_ip_data = $ip_data->Failed;
    $failed_host_data = $host_data->Failed;
    
    echo "IP and Hostname: ".$ipaddress." and ".$hostname;
    echo "\n";

    echo "Blacklisting Details for the IP Address: ".$ipaddress;
    echo "\n";

    $blacklist_query_type = "";
    $query_string = "";
    $blacklist_query_type = "IP_ADDRESS_CHECK";
    $query_string = $ipaddress;

    //Insert Temporary IP Check Results
    $conn->beginTransaction();
    foreach ($failed_ip_data as $ele_ip)
    {
        $dnsbl_ip = $ele_ip;
        
        $blacklist_name = "";
        $blacklist_reason = "";
        $delist_url = "";

        $blacklist_name = $dnsbl_ip->Name;
        $blacklist_reason = $dnsbl_ip->BlacklistReasonDescription;
        $delist_url = $dnsbl_ip->DelistUrl;

        //Insert Blacklist of via IP Lookup to Temp table
        $insert_tmp_bl_log = "INSERT INTO ipblacklist_log_temp (ipdetailid,blacklist_query_type,query_string,mailserverid,blacklist_name, 
        blacklist_reason,delist_url)
        VALUES (:ipdetailid, :blacklist_query_type,:query_string, :mailserverid, :blacklist_name, :blacklist_reason, :delist_url)";
        $stmt_tmp_bl_log = $conn->prepare($insert_tmp_bl_log);
        $stmt_tmp_bl_log->bindValue(':ipdetailid',$ipdetailid);
        $stmt_tmp_bl_log->bindValue(':blacklist_query_type',$blacklist_query_type);
        $stmt_tmp_bl_log->bindValue(':query_string', $query_string);
        $stmt_tmp_bl_log->bindValue(':mailserverid', $mailserverid);
        $stmt_tmp_bl_log->bindValue(':blacklist_name', $blacklist_name);
        $stmt_tmp_bl_log->bindValue(':blacklist_reason', $blacklist_reason);
        $stmt_tmp_bl_log->bindValue(':delist_url', $delist_url);
        $stmt_tmp_bl_log->execute();

        echo $dnsbl_ip->Name;
        echo "\n";
        echo $dnsbl_ip->BlacklistReasonDescription;
        echo "\n";
        echo $dnsbl_ip->DelistUrl;
        echo "\n";
        echo "\n";
    }
    $conn->commit();

    echo "Blacklisting Details for the Hostname: ".$hostname;
    echo "\n";

    $blacklist_query_type = "";
    $query_string = "";
    $blacklist_query_type = "HOSTNAME_CHECK";
    $query_string = $hostname;
    
    //Insert Temporary Hostname Check Results
    $conn->beginTransaction();
    foreach ($failed_host_data as $ele_host)
    {
        $dnsbl_host = $ele_host;

        $blacklist_name = "";
        $blacklist_reason = "";
        $delist_url = "";

        $blacklist_name = $dnsbl_host->Name;
        $blacklist_reason = $dnsbl_host->BlacklistReasonDescription;
        $delist_url = $dnsbl_host->DelistUrl;

        //Insert Blacklist of via Hostname Lookup to Temp table
        $insert_tmp_bl_log = "INSERT INTO ipblacklist_log_temp (ipdetailid,blacklist_query_type,query_string,mailserverid,blacklist_name, 
        blacklist_reason,delist_url)
        VALUES (:ipdetailid, :blacklist_query_type,:query_string, :mailserverid, :blacklist_name, :blacklist_reason, :delist_url)";
        $stmt_tmp_bl_log = $conn->prepare($insert_tmp_bl_log);
        $stmt_tmp_bl_log->bindValue(':ipdetailid',$ipdetailid);
        $stmt_tmp_bl_log->bindValue(':blacklist_query_type',$blacklist_query_type);
        $stmt_tmp_bl_log->bindValue(':query_string', $query_string);
        $stmt_tmp_bl_log->bindValue(':mailserverid', $mailserverid);
        $stmt_tmp_bl_log->bindValue(':blacklist_name', $blacklist_name);
        $stmt_tmp_bl_log->bindValue(':blacklist_reason', $blacklist_reason);
        $stmt_tmp_bl_log->bindValue(':delist_url', $delist_url);
        $stmt_tmp_bl_log->execute();

        echo $dnsbl_host->Name;
        echo "\n";
        echo $dnsbl_host->BlacklistReasonDescription;
        echo "\n";
        echo $dnsbl_host->DelistUrl;
        echo "\n";
        echo "\n";
    }
    $conn->commit();

    //Insert New DNSBL Agency
    $stmt_ip_bl_log=$conn->prepare("INSERT INTO dnsbl (dnsbl_name, priority_color, priority_score, status)
                                    SELECT blacklist_name, 'yellow', 1, 'Newly Added'
                                    FROM ipblacklist_log_temp
                                    WHERE lower(trim(ipblacklist_log_temp.blacklist_name)) NOT IN (
                                            SELECT lower(trim(dnsbl_name)) 
                                            FROM dnsbl)");
    $stmt_ip_bl_log->execute();


    //Insert Query for Both IP and Host Blacklist to tbl_ipblacklist_log
    $stmt_ip_bl_log=$conn->prepare("INSERT INTO tbl_ipblacklist_log (ip_blacklist_status, hostname_blacklist_status, blacklist_reason, delist_url, blacklist_date, blacklist_color, blacklist_score, status, dnsbl_id, ipdetailid)
                                    SELECT 'YES', 'YES', blacklist_reason, delist_url, CURDATE(), priority_color, priority_score,'BLACKLIST', dnsbl_id,".$ipdetailid."
                                    FROM ipblacklist_log_temp, dnsbl
                                    WHERE lower(trim(ipblacklist_log_temp.blacklist_name)) = lower(trim(dnsbl.dnsbl_name))
                                    AND lower(trim(blacklist_name)) NOT IN (
                                            SELECT lower(trim(dnsbl_name)) 
                                            FROM dnsbl, tbl_ipblacklist_log
                                            WHERE dnsbl.dnsbl_id = tbl_ipblacklist_log.dnsbl_id
                                            AND whitelist_date IS NULL
                                            AND ipdetailid = $ipdetailid
                                            )
                                    AND ipblacklist_log_temp_id IN (
                                        SELECT max(ipblacklist_log_temp_id) 
                                        FROM ipblacklist_log_temp
                                        WHERE ipdetailid = $ipdetailid
                                        GROUP BY ipdetailid, mailserverid, blacklist_name 
                                        HAVING count(1) = 2)");
    $stmt_ip_bl_log->execute();

    //Insert Query for Both IP Blacklist Only to tbl_ipblacklist_log
    $stmt_ip_bl_log=$conn->prepare("INSERT INTO tbl_ipblacklist_log (ip_blacklist_status, hostname_blacklist_status, blacklist_reason, delist_url, blacklist_date, blacklist_color, blacklist_score, status, dnsbl_id, ipdetailid)
                                    SELECT 'YES', 'NO', blacklist_reason, delist_url, CURDATE(), priority_color, priority_score,'BLACKLIST', dnsbl_id,".$ipdetailid."
                                    FROM ipblacklist_log_temp, dnsbl
                                    WHERE lower(trim(ipblacklist_log_temp.blacklist_name)) = lower(trim(dnsbl.dnsbl_name))
                                    AND lower(trim(blacklist_name)) NOT IN (
                                            SELECT lower(trim(dnsbl_name)) 
                                            FROM dnsbl, tbl_ipblacklist_log
                                            WHERE dnsbl.dnsbl_id = tbl_ipblacklist_log.dnsbl_id
                                            AND whitelist_date IS NULL 
                                            AND ipdetailid = $ipdetailid
                                            )
                                    AND blacklist_query_type = 'IP_ADDRESS_CHECK'
                                    AND ipblacklist_log_temp_id IN (
                                        SELECT max(ipblacklist_log_temp_id) 
                                        FROM ipblacklist_log_temp
                                        WHERE ipdetailid = $ipdetailid
                                        GROUP BY ipdetailid, mailserverid, blacklist_name 
                                        HAVING count(1) = 1)");
    $stmt_ip_bl_log->execute();
    
    //Insert Query for Both Hostname Blacklist Only to tbl_ipblacklist_log
    $stmt_ip_bl_log=$conn->prepare("INSERT INTO tbl_ipblacklist_log (ip_blacklist_status, hostname_blacklist_status, blacklist_reason, delist_url, blacklist_date, blacklist_color, blacklist_score, status, dnsbl_id, ipdetailid)
                                    SELECT 'NO', 'YES', blacklist_reason, delist_url, CURDATE(), priority_color, priority_score,'BLACKLIST', dnsbl_id,".$ipdetailid."
                                    FROM ipblacklist_log_temp, dnsbl
                                    WHERE lower(trim(ipblacklist_log_temp.blacklist_name)) = lower(trim(dnsbl.dnsbl_name))
                                    AND lower(trim(blacklist_name)) NOT IN (
                                            SELECT lower(trim(dnsbl_name)) 
                                            FROM dnsbl, tbl_ipblacklist_log
                                            WHERE dnsbl.dnsbl_id = tbl_ipblacklist_log.dnsbl_id
                                            AND whitelist_date IS NULL 
                                            AND ipdetailid = $ipdetailid
                                            )
                                    AND blacklist_query_type = 'HOSTNAME_CHECK'
                                    AND ipblacklist_log_temp_id IN (
                                        SELECT max(ipblacklist_log_temp_id) 
                                        FROM ipblacklist_log_temp
                                        WHERE ipdetailid = $ipdetailid
                                        GROUP BY ipdetailid, mailserverid, blacklist_name 
                                        HAVING count(1) = 1)");
    $stmt_ip_bl_log->execute();

    //Whitelisting Query for previous Blacklisting which are not found in the current checking
    $stmt_ip_bl_log=$conn->prepare("UPDATE tbl_ipblacklist_log 
                                    SET status = 'WHITELIST',
                                    blacklist_duration = DATEDIFF(CURDATE(), blacklist_date),
                                    whitelist_date = CURDATE() 
                                    WHERE whitelist_date IS NULL
                                    AND status = 'BLACKLIST'
                                    AND ipdetailid = $ipdetailid
                                    AND dnsbl_id NOT IN (
                                        SELECT dnsbl_id
                                        FROM ipblacklist_log_temp, dnsbl
                                        WHERE lower(trim(ipblacklist_log_temp.blacklist_name)) = lower(trim(dnsbl.dnsbl_name))
                                        )");
    $stmt_ip_bl_log->execute();




    //Whitelisting Query for previous Blacklisting which are not found in the current checking
    $stmt_ip_bl_log=$conn->prepare("UPDATE ipdetails 
                                    SET ipstatus = (select case when(IFNULL(sum(blacklist_score),0)> 9) then 'BLACKLIST' else 'WHITELIST' end from tbl_ipblacklist_log ipd, dnsbl d where ipd.dnsbl_id = d.dnsbl_id and d.status = 'Active' and ipdetailid = $ipdetailid and ipd.status = 'BLACKLIST'),
                                    ipblack_color = (select case when(IFNULL(sum(blacklist_score),0)> 9) then 'black' 
                                                            when(IFNULL(sum(blacklist_score),0)> 4) then 'red'
                                                            when(IFNULL(sum(blacklist_score),0)> 2) then 'orange'
                                                            when(IFNULL(sum(blacklist_score),0)> 0) then 'yellow'
                                                            else 'green' end
                                                    from tbl_ipblacklist_log ipd, dnsbl d where ipd.dnsbl_id = d.dnsbl_id and d.status = 'Active' and ipdetailid = $ipdetailid and ipd.status = 'BLACKLIST'),
                                    ipblack_score = (select IFNULL(sum(blacklist_score),0) from tbl_ipblacklist_log ipd, dnsbl d where ipd.dnsbl_id = d.dnsbl_id and d.status = 'Active' and ipdetailid = $ipdetailid and ipd.status = 'BLACKLIST')
                                    WHERE ipdetailid = $ipdetailid");
    $stmt_ip_bl_log->execute();

    //Cleanup the temp table for the next IP and Hostname set
    $stmt_ip_bl_log=$conn->prepare("TRUNCATE TABLE ipblacklist_log_temp");

    $stmt_ip_bl_log->execute();



}

$stmt_blms=$conn->prepare("SELECT mailserverid, count(1) WhitelistedIPs FROM ipdetails WHERE ipstatus = 'WHITELIST' GROUP BY mailserverid");
$stmt_blms->execute();
$result_blms=$stmt_blms->fetchAll();

foreach ($result_blms as $row_blms) 
{
    $mailserverid_blms = $row_blms['mailserverid'];
    $WhitelistedIPs = $row_blms['WhitelistedIPs'];

    if($WhitelistedIPs < $mailserver_min_active_ips)
    {
        $stmt_blcmp=$conn->prepare("UPDATE campaign 
                                    SET Camp_Status = 'Interuptted (Mail Server Unavailable)'
                                    WHERE Camp_Status = 'Active' 
                                    AND mailserverid = ".$mailserverid_blms);
        $stmt_blcmp->execute();
        $stmt_msbl=$conn->prepare("UPDATE mailservers 
                                    SET vmstatus = 'Blacklisted'
                                    WHERE vmstatus = 'Active' 
                                    AND mailserverid = ".$mailserverid_blms);
        $stmt_msbl->execute();
    }
    else
    {
        $stmt_msbl=$conn->prepare("UPDATE mailservers 
                                    SET vmstatus = 'Active'
                                    WHERE vmstatus = 'Blacklisted' 
                                    AND mailserverid = ".$mailserverid_blms);
        $stmt_msbl->execute();
        $stmt_blcmp=$conn->prepare("UPDATE campaign 
                                    SET Camp_Status = 'Active'
                                    WHERE Camp_Status = 'Interuptted (Mail Server Unavailable)' 
                                    AND mailserverid = ".$mailserverid_blms);
        $stmt_blcmp->execute();
    }
}

//Close Database Connection
$conn=null;
?>
