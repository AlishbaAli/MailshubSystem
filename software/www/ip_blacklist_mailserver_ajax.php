<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
} 
if (isset($_POST["ms_id"])) { ?>
<div class="table-responsive">                    
                                    <table id="display1" class="table  center-aligned-table table-bordered table-hover  dataTable table-custom">

                        <thead>
                            <tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;text-align: center;">
                            
                               
                               <th>Mailserver</th>
                               <th>Service Provider</th>
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Agencies</th>
                              



                            </tr>
                        </thead>
                        <tbody> 

                            <?php
                           
if($_POST["ms_id"]=="all" || !isset($_POST["ms_id"])){
$sql = "SELECT mailservers.mailserverid,ipdetails.ipdetailid,`ipaddress` ,service_providers.sp_id as service_provider,`ipstatus`,`ipblack_score`,`ipblack_color`, 
GROUP_CONCAT(DISTINCT dnsbl_id,'|',blacklist_date,'|',blacklist_score, '|',blacklist_color SEPARATOR ',') AS dnsbl_id FROM ipdetails
inner join mailservers on mailservers.mailserverid = ipdetails.mailserverid 
                                                left join ip_hostname on ip_hostname.ip_hostname_id = ipdetails.ip_hostname_id
                                                left join ip_addresses on ip_addresses.ip_addresses_id = ip_hostname.ip_addresses_id
                                                left join ip_pool on ip_pool.ip_pool_id = ip_addresses.ip_pool_id
                                                left join service_providers on service_providers.sp_id=ip_pool.sp_id

 RIGHT JOIN tbl_ipblacklist_log ON ipdetails.ipdetailid= tbl_ipblacklist_log.ipdetailid 
 WHERE ( whitelist_date is null and blacklist_duration is null)  GROUP BY ipdetailid";
  $stmt = $conn->prepare($sql);
 $stmt->execute();
}
else{
    $sql = "SELECT mailservers.mailserverid,ipdetails.ipdetailid,`ipaddress` ,service_providers.sp_id as service_provider,`ipstatus`,`ipblack_score`,`ipblack_color`, 
    GROUP_CONCAT(DISTINCT dnsbl_id,'|',blacklist_date,'|',blacklist_score, '|',blacklist_color SEPARATOR ',') AS dnsbl_id FROM ipdetails
    inner join mailservers on mailservers.mailserverid = ipdetails.mailserverid 
                                                left join ip_hostname on ip_hostname.ip_hostname_id = ipdetails.ip_hostname_id
                                                left join ip_addresses on ip_addresses.ip_addresses_id = ip_hostname.ip_addresses_id
                                                left join ip_pool on ip_pool.ip_pool_id = ip_addresses.ip_pool_id
                                                left join service_providers on service_providers.sp_id=ip_pool.sp_id

     RIGHT JOIN tbl_ipblacklist_log ON ipdetails.ipdetailid= tbl_ipblacklist_log.ipdetailid WHERE mailserverid=:service_provider
     and ( whitelist_date is null and blacklist_duration is null)  GROUP BY ipdetailid";
 $stmt = $conn->prepare($sql);
 $stmt->bindParam(':service_provider', $_POST["ms_id"]);
$stmt->execute();

}

      




                            while ($row = $stmt->fetch()) {



                            ?>
                                <tr style="text-align: center;">
                                
                                <td><?php 
                                $mid= $row['mailserverid'];
                                $stmtm = $conn->prepare("SELECT vmname FROM mailservers WHERE mailserverid =$mid");
                                $stmtm->execute();
                                $rowm = $stmtm->fetch();
                               echo $rowm["vmname"] . " "; ?></td>
                               <td><?php
                                $id=$row["service_provider"];
                                   $stmtsp = $conn->prepare("SELECT sp_name FROM service_providers WHERE sp_id =$id");
                                   $stmtsp->execute();
                                   $rowsp = $stmtsp->fetch();
                                
                                echo  $rowsp["sp_name"] ?></td>
                               
                                    <td><?php echo $row["ipaddress"] . " "; ?></td>

                                    <td>                                  <?php if($row["ipstatus"]=="In Active") {?>    
                                                            <span class="badge badge-danger"><?php echo $row["ipstatus"] . " ";?> 
                                                           <?php } else if($row["ipstatus"]=="Active"){ ?>
                                                            <span class="badge badge-success">  <?php echo $row["ipstatus"] . " "?>

                                                           <?php }
                                                            else if($row["ipstatus"]=="WHITELIST"){ ?>
                                                                <span class="badge badge-light">  <?php echo $row["ipstatus"] . " "?>
    
                                                               <?php }
                                                                else if($row["ipstatus"]=="BLACKLIST"){ ?>
                                                                    <span class="badge badge-dark">  <?php echo $row["ipstatus"] . " "?>
        
                                                                   <?php }
                                                            
                                                            ?></td>
                                                          
                                                            <?php if($row['ipblack_color']=='black'){?>
                                                                <td>
                                                                <span class="badge badge-dark"> <?php  
                                                                echo $row["ipblack_score"] . " "; ?>
                                                                </td> 
                                                           <?php } 
                                                           
                                                           
                                                          else if($row['ipblack_color']=='yellow'){?>
                                                            <td>
                                                            <span class="badge badge-warning" style="background:yellow; color:black"> <?php  
                                                                echo $row["ipblack_score"] . " "; ?>

                                                            </td>
                                                                  <?php } 
                                                                  
                                                                  else if($row['ipblack_color']=='green'){?>
                                                                   <td>
                                                                   <span class="badge badge-success"style="background:green; color:white"> <?php  
                                                                echo $row["ipblack_score"] . " "; ?>

                                                                   </td>
                                                                  <?php } else if($row['ipblack_color']=='red'){?>
                                                                    <td>
                                                                   <span class="badge badge-danger"style="background:red; color:white"> <?php  
                                                                echo $row["ipblack_score"] . " "; ?>

                                                                   </td>

                                                                    <?php } else if($row['ipblack_color']=='orange'){?>
                                                                        <td>
                                                                 
                                                                        <span class="badge badge-danger"style="background:#F28C28; color:black;border-color:#F28C28"> <?php  
                                                                echo $row["ipblack_score"] . " "; ?>
                                                                   </td>

                                                                        <?php } ?>
                                                           
                                                            <td><?php 
                                                            
                                                            if ($row["dnsbl_id"] != "") {
                                                                $dnsbl_ids = $row["dnsbl_id"];
                                                                $dnsbl_arr = explode(",", $dnsbl_ids);
                                                                $res = "";
                                                                foreach ($dnsbl_arr as $dnsbl) {

                                                                    $dnsbl_array= explode("|",$dnsbl);
                                                                    $id=$dnsbl_array[0];
                                                                    $date= explode(" ",$dnsbl_array[1]);
                                                                    $score= $dnsbl_array[2];
                                                                    $color= $dnsbl_array[3];


                                                                    $stmtr = $conn->prepare("SELECT dnsbl_name FROM dnsbl WHERE dnsbl_id =$id");
                                                                    $stmtr->execute();
                                                                    $rowr = $stmtr->fetch();

                                                                  //  $res .= $rowr["dnsbl_name"] . ", ";
                                                                  
                                                                  if($color=="black"){?>
                                                                   <li style="list-style:none;"> <span class="badge badge-dark"> <?php echo $rowr["dnsbl_name"]." (score".$score.") [".$date[0]."]"?> </li>


                                                                     <?php } 
                                                                     else if($color=="yellow"){
                                                                         ?>
                                                                  <li style="list-style:none;">  <span class="badge badge-warning" style="background:yellow; color:black;border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>
                                                                          
                                                                    
                                                                <?php } else if($color=="green"){ ?>
                                                                    <li style="list-style:none;">  <span class="badge badge-success" style="background:green; border-color:black;color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                             <?php   } 
                                                             else if($color=="orange"){ ?>
                                                                <li style="list-style:none;">  <span class="badge badge-success" style="background:orange; color:black; border-color:black"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>

                                                         <?php   }
                                                         else if($color=="red"){ ?>
                                                            <li style="list-style:none;">  <span class="badge badge-danger" style="background:red;border-color:black; color:white;"><?php echo $rowr["dnsbl_name"]." (score=".$score.") [".$date[0]."]"?> </li>


                                                     <?php    } 
                                                             
                                                            }
                                                                 
                                                                
                                                            } 
                                                            
                                                           ?></td>
                                 
                                </tr>
                            <?php }


                            ?>

                        </tbody>
                    </table>

<?php }?>

<Script>
              $(document).ready(function() {
    $('#display1').DataTable( {
        dom:"<'row'<'col-sm-12 col-md-6'l> <'right aligned col-sm-12 col-md-6'f>r>"+"<'row'<'col-sm-12 col-md-12'B>>"+
"t"+
"<'row'<'col-sm-12 col-md-5'i><'right aligned col-sm-12 col-md-7'p>>",
        buttons: [
            'copy', 'csv', 'pdf', 'print'
        ]
    } );
} );
</script>