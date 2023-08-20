<?php 

// $MSL="SELECT mailservers.mailserverid, vmname, COUNT(CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' THEN campaign.mailserverid END ) as load_t,
// COUNT(CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' and Camp_Status!='Active' THEN campaign.mailserverid END ) as load_pa,
//  COUNT(CASE WHEN Camp_Status='Active' THEN campaign.mailserverid END ) as load_a
// FROM  mailservers left join campaign on campaign.mailserverid = mailservers.mailserverid  

// group by mailserverid";
$sysset="Select * from system_setting";
$sysset=$conn->prepare($sysset);
$sysset->execute();
$sysset=$sysset->fetch();

$MSL="SELECT  Count(distinct mailservers.mailserverid) as t_ms,
COUNT(distinct (CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' THEN campaign.mailserverid END ) ) as ms_t,
COUNT(CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' THEN campaign.mailserverid END ) as load_t,

COUNT(distinct (CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' and Camp_Status!='Active' THEN campaign.mailserverid END)) as ms_pa,
COUNT(CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' and Camp_Status!='Active' THEN campaign.mailserverid END ) as load_pa,

COUNT(distinct (CASE WHEN Camp_Status='Active' THEN campaign.mailserverid END) ) as ms_a,
COUNT(CASE WHEN Camp_Status='Active' THEN campaign.mailserverid END ) as load_a,

COUNT(distinct (CASE WHEN Camp_Status='Interuptted (Mail Server Unavailable)' THEN campaign.mailserverid END) ) as ms_int,
COUNT(CASE WHEN Camp_Status='Interuptted (Mail Server Unavailable)' THEN campaign.mailserverid END ) as load_int

 FROM  mailservers left join campaign on campaign.mailserverid = mailservers.mailserverid";

$MSL=$conn->prepare($MSL);
$MSL->execute();
$msl=$MSL->fetch();

// $totalms=$msl['ms_t'];
// $activems=$msl['ms_a'];
// $pactivems=$msl['ms_pa'];
// $intms=$msl['ms_int'];

 $totalms=$msl['t_ms'];
 $activems=$msl['t_ms'];
 $pactivems=$msl['t_ms'];
 $intms=$msl['t_ms'];

if ($totalms == 0){ $totalms = 1; }
if ($activems == 0){ $activems = 1; }
if ($pactivems == 0){ $pactivems = 1; }
if ($intms == 0){ $intms = 1; }

 $totalload=$msl['load_t'];
 $activeload=$msl['load_a'];
 $pactiveload=$msl['load_pa'];
 $intload=$msl['load_int'];

$query="SELECT instance_email_send, max_camp_per_server_percentage from system_setting";
$sql_hours=$conn->prepare($query);
$sql_hours->execute();
$sr=$sql_hours->fetch(); 
$emails_per_mailserver=$sr['instance_email_send'];
$campaigns_allowed= $sr['max_camp_per_server_percentage'];//($emails_per_mailserver)/4; 

$campaigns_allowedt = $totalms *((int)$campaigns_allowed);
$percentt=round(($totalload / $campaigns_allowedt)* 100);

$campaigns_alloweda = $activems *((int)$campaigns_allowed);
$percenta=round(($activeload / $campaigns_alloweda)* 100);

$campaigns_allowedpa = $pactivems *((int)$campaigns_allowed);
$percentpa=round(($pactiveload / $campaigns_allowedpa)* 100);

$campaigns_allowedint = $intms *((int)$campaigns_allowed);
$percentint=round(($intload / $campaigns_allowedint)* 100);


// $MS="SELECT Count(`mailserverid`) as totalms,
// count(Case when vmstatus='In Active' then `mailserverid` end ) as Inactivems,
// count(Case when vmstatus='Active' then `mailserverid` end ) as Activems,
// count(Case when vmstatus='Blacklisted' then `mailserverid` end ) as Blackms
// FROM `mailservers` WHERE 1";
// $MS=$conn->prepare($MS);
// $MS->execute();
// $ms=$MS->fetch();

// $total_ms=$ms['totalms'];
// $inactive_ms=$ms['Inactivems'];
// $active_ms=$ms['Activems'];
// $black_ms=$ms['Blackms'];

?>
               
                    <div class="col-sm-4">

                        <div class="card" style="border-color:black;">
                            <div class="header" data-toggle="modal" 
                                            data-target="#TotalMailserversl">
                             <h2 class="text-center">Mailservers Load </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $percentt."%";?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">

                                <div class="col-5 border-bottom border-right ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning" data-toggle="modal" 
                                            data-target="#PAMailservers">
                                                    <label class="mb-0">Potentialy Active</label>
                                                    <h4 class="font-30 font-weight-bold text-warning"><?php echo $percentpa."%";?></h4>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-3 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success" data-toggle="modal" 
                                            data-target="#ActiveMailserversl" >
                                                    <label class="mb-0 ">Active</label>
                                                    <h4 class="font-30 font-weight-bold "  ><?php echo $percenta."%";?> </h4>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <div class="col-4 border-bottom  ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger" data-toggle="modal" 
                                            data-target="#BlacklistedMailserversl">
                                                    <label class="mb-0 ">Interrupted</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $percentint."%";?></h4>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
<?php 
$query="SELECT  vmname,vmstatus,Camp_Status,
COUNT(distinct (CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' THEN campaign.mailserverid END ) ) as ms_t,
COUNT(CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' THEN campaign.mailserverid END ) as load_t,

COUNT(distinct (CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' and Camp_Status!='Active' THEN campaign.mailserverid END)) as ms_pa,
COUNT(CASE WHEN Camp_Status!='Archive' and Camp_Status!= 'Completed' and Camp_Status!='Active' THEN campaign.mailserverid END ) as load_pa,

COUNT(distinct (CASE WHEN Camp_Status='Active' THEN campaign.mailserverid END) ) as ms_a,
COUNT(CASE WHEN Camp_Status='Active' THEN campaign.mailserverid END ) as load_a,

COUNT(distinct (CASE WHEN Camp_Status='Interuptted (Mail Server Unavailable)' THEN campaign.mailserverid END) ) as ms_int,
COUNT(CASE WHEN Camp_Status='Interuptted (Mail Server Unavailable)' THEN campaign.mailserverid END ) as load_int

FROM  mailservers left join campaign on campaign.mailserverid = mailservers.mailserverid group by campaign.mailserverid";


//$query="SELECT * FROM mailservers";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="TotalMailserversl"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '80%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '80%';min-width: 50%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">  
                                                                                <h4 align="center">Total Mailservers</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> Mailserver </th>
                                                <th> Mailserver Status </th>
                                                <th> Campaigns </th>
                                                <th> Load </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 
$totalms=$row['ms_t'];
$activems=$row['ms_a'];
$pactivems=$row['ms_pa'];
$intms=$row['ms_int'];

if ($totalms == 0){ $totalms = 1; }
if ($activems == 0){ $activems = 1; }
if ($pactivems == 0){ $pactivems = 1; }
if ($intms == 0){ $intms = 1; }

$totalload=$row['load_t'];
$activeload=$row['load_a'];
$pactiveload=$row['load_pa'];
$intload=$row['load_int'];

$query="SELECT instance_email_send ,max_camp_per_server_percentage from system_setting";
$sql_hours=$conn->prepare($query);
$sql_hours->execute();
$sr=$sql_hours->fetch(); 
$emails_per_mailserver=$sr['instance_email_send']; // ($emails_per_mailserver)/4;
$campaigns_allowed= $sr['max_camp_per_server_percentage'];

$campaigns_allowedt = ((int)$campaigns_allowed);
$percentt=round(($totalload / $campaigns_allowedt)* 100);

$campaigns_alloweda = $activems *((int)$campaigns_allowed);
$percenta=round(($activeload / $campaigns_alloweda)* 100);

$campaigns_allowedpa = $pactivems *((int)$campaigns_allowed);
$percentpa=round(($pactiveload / $campaigns_allowedpa)* 100);

$campaigns_allowedint = $intms *((int)$campaigns_allowed);
$percentint=round(($intload / $campaigns_allowedint)* 100); ?>

                                             <tr align="center">
                                             <td><?php echo $row['vmname'] ?></td>
                                             <td><?php echo $row['vmstatus'] ?></td>
                                             <td><?php echo $row['load_t'] ?></td>
                                             <td><?php echo $percentt."%"; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="ActiveMailserversl"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 50%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">Active Mailservers</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Mailserver </th>
                                                <th> Mailserver Status </th>
                                                <th> Campaigns </th>
                                                <th> Load </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 
                                                    $totalms=$row['ms_t'];
                                                    $activems=$row['ms_a'];
                                                    $pactivems=$row['ms_pa'];
                                                    $intms=$row['ms_int'];
                                                    
                                                    if ($totalms == 0){ $totalms = 1; }
                                                    if ($activems == 0){ $activems = 1; }
                                                    if ($pactivems == 0){ $pactivems = 1; }
                                                    if ($intms == 0){ $intms = 1; }
                                                    
                                                    $totalload=$row['load_t'];
                                                    $activeload=$row['load_a'];
                                                    $pactiveload=$row['load_pa'];
                                                    $intload=$row['load_int'];
                                                    
                                                    $query="SELECT instance_email_send, max_camp_per_server_percentage from system_setting";
                                                    $sql_hours=$conn->prepare($query);
                                                    $sql_hours->execute();
                                                    $sr=$sql_hours->fetch(); 
                                                    $emails_per_mailserver=$sr['instance_email_send'];
                                                    $campaigns_allowed= $sr['max_camp_per_server_percentage'];
                                                    
                                                    $campaigns_allowedt = ((int)$campaigns_allowed);
                                                    $percentt=round(($totalload / $campaigns_allowedt)* 100);
                                                    
                                                    $campaigns_alloweda = $activems *((int)$campaigns_allowed);
                                                    $percenta=round(($activeload / $campaigns_alloweda)* 100);
                                                    
                                                    $campaigns_allowedpa = $pactivems *((int)$campaigns_allowed);
                                                    $percentpa=round(($pactiveload / $campaigns_allowedpa)* 100);
                                                    
                                                    $campaigns_allowedint = $intms *((int)$campaigns_allowed);
                                                    $percentint=round(($intload / $campaigns_allowedint)* 100);?>
                                             <tr align="center">
                                             <td><?php echo $row['vmname'] ?></td>
                                             <td><?php echo $row['vmstatus'] ?></td>
                                             <td><?php echo $row['load_a']; ?></td>
                                             <td><?php echo $percenta."%"; ?></td>
                                            </tr>
                                            <?php }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="PAMailservers"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 50%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">In Active Mailservers</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Mailserver </th>
                                                <th> Mailserver Status </th>
                                                <th> Campaigns </th>
                                                <th> Load </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  
                                             $totalms=$row['ms_t'];
                                                    $activems=$row['ms_a'];
                                                    $pactivems=$row['ms_pa'];
                                                    $intms=$row['ms_int'];
                                                    
                                                    if ($totalms == 0){ $totalms = 1; }
                                                    if ($activems == 0){ $activems = 1; }
                                                    if ($pactivems == 0){ $pactivems = 1; }
                                                    if ($intms == 0){ $intms = 1; }
                                                    
                                                    $totalload=$row['load_t'];
                                                    $activeload=$row['load_a'];
                                                    $pactiveload=$row['load_pa'];
                                                    $intload=$row['load_int'];
                                                    
                                                    $query="SELECT instance_email_send,max_camp_per_server_percentage from system_setting";
                                                    $sql_hours=$conn->prepare($query);
                                                    $sql_hours->execute();
                                                    $sr=$sql_hours->fetch(); 
                                                    $emails_per_mailserver=$sr['instance_email_send'];
                                                    $campaigns_allowed=$sr['max_camp_per_server_percentage'];
                                                    
                                                    $campaigns_allowedt = ((int)$campaigns_allowed);
                                                    $percentt=round(($totalload / $campaigns_allowedt)* 100);
                                                    
                                                    $campaigns_alloweda = $activems *((int)$campaigns_allowed);
                                                    $percenta=round(($activeload / $campaigns_alloweda)* 100);
                                                    
                                                    $campaigns_allowedpa = $pactivems *((int)$campaigns_allowed);
                                                    $percentpa=round(($pactiveload / $campaigns_allowedpa)* 100);
                                                    
                                                    $campaigns_allowedint = $intms *((int)$campaigns_allowed);
                                                    $percentint=round(($intload / $campaigns_allowedint)* 100);?>
                                             <tr align="center">
                                             <td><?php echo $row['vmname'] ?></td>
                                             <td><?php echo $row['vmstatus'] ?></td>
                                             <td><?php echo $row['load_pa']; ?></td>
                                             <td><?php echo $percentpa."%"; ?></td>
                                            </tr>
                                            <?php  } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="BlacklistedMailserversl"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 50%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">Blacklisted Mailservers</h4>
                                                                            <p>Email per Hour</p>
                                                                            <p>Maximum Campaigns per Server</p>
                                                                            <p>Email per Hour</p>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 													
                                             <th> Mailserver </th>
                                                <th> Mailserver Status </th>
                                                <th> Campaigns </th>
                                                <th> Load </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  
                                             $totalms=$row['ms_t'];
                                                    $activems=$row['ms_a'];
                                                    $pactivems=$row['ms_pa'];
                                                    $intms=$row['ms_int'];
                                                    
                                                    if ($totalms == 0){ $totalms = 1; }
                                                    if ($activems == 0){ $activems = 1; }
                                                    if ($pactivems == 0){ $pactivems = 1; }
                                                    if ($intms == 0){ $intms = 1; }
                                                    
                                                    $totalload=$row['load_t'];
                                                    $activeload=$row['load_a'];
                                                    $pactiveload=$row['load_pa'];
                                                    $intload=$row['load_int'];
                                                    
                                                    $query="SELECT instance_email_send,max_camp_per_server_percentage from system_setting";
                                                    $sql_hours=$conn->prepare($query);
                                                    $sql_hours->execute();
                                                    $sr=$sql_hours->fetch(); 
                                                    $emails_per_mailserver=$sr['instance_email_send'];
                                                    $campaigns_allowed= $sr['max_camp_per_server_percentage'];
                                                    
                                                    $campaigns_allowedt = ((int)$campaigns_allowed);
                                                    $percentt=round(($totalload / $campaigns_allowedt)* 100);
                                                    
                                                    $campaigns_alloweda = $activems *((int)$campaigns_allowed);
                                                    $percenta=round(($activeload / $campaigns_alloweda)* 100);
                                                    
                                                    $campaigns_allowedpa = $pactivems *((int)$campaigns_allowed);
                                                    $percentpa=round(($pactiveload / $campaigns_allowedpa)* 100);
                                                    
                                                    $campaigns_allowedint = $intms *((int)$campaigns_allowed);
                                                    $percentint=round(($intload / $campaigns_allowedint)* 100);?>
                                             <tr align="center">
                                             <td><?php echo $row['vmname'] ?></td>
                                             <td><?php echo $row['vmstatus'] ?></td>
                                             <td><?php echo $row['load_int']; ?></td>
                                             <td><?php echo $percentint."%"; ?></td>
                                            </tr>
                                            <?php  } ?>
                                        </tbody>   
                                        </table>     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>