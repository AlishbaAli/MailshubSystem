
<?php 

$MS="SELECT  Count(blocked_domain_org.`domain_name`) as urlb,
Count(Case when blocked_domain_org.domain_status='In Active' then blocked_domain_org.`domain_name` end ) as Inactiveorg,
count(Case when blocked_domain_org.domain_status='Active' then blocked_domain_org.`domain_name` end ) as Activeorg

FROM `blocked_domain_org`inner join blocked_domains on blocked_domain_org.blocked_domain_id=blocked_domains.blocked_domain_id ";

if (!empty($_SESSION['orgunit_id'])) {
    $MS.=" where orgunit_id = '$orgunit_id'";
    }
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$total_org=$ms['urlb'];
$inactive_org=$ms['Inactiveorg'];
$active_org=$ms['Activeorg'];

?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header" data-toggle="modal" 
                                            data-target="#urlpb">
                                <h2 class="text-center">Blocked Domains 
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $total_org;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-6 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger" data-toggle="modal" 
                                            data-target="#inactiveurlpb">
                                                    <label class="mb-0">In Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $inactive_org;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success" data-toggle="modal" 
                                            data-target="#activeurlpb">
                                                    <label class="mb-0">Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $active_org;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning" data-toggle="modal" 
                                            data-target="#trmorg">
                                                    <label class="mb-0">Terminated</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $term_org;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT *,bdo.domain_status as domain_status,date(blocked_domains.system_date)as sd FROM blocked_domains inner join 
(SELECT blocked_domain_id,blocked_domain_org.orgunit_id,orgunit_name,blocked_domain_org.domain_status 
 from blocked_domain_org left join tbl_organizational_unit 
 on blocked_domain_org.orgunit_id=tbl_organizational_unit.orgunit_id) 
as bdo on blocked_domains.blocked_domain_id=bdo.blocked_domain_id  
";
if (!empty($_SESSION['orgunit_id'])) {
    $query.=" where orgunit_id = '$orgunit_id'";
    }
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="urlpb"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '80%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '80%';min-width: 70%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">  
                                                                                <h4 align="center">Blocked Domains</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> Domain Name </th>
                                                <th> Domain Owner </th>
                                                <th> Domain Status </th>
                                                <th> Organizations </th>

 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) { ?>
                                             <tr align="center">
                                             <td><?php echo wordwrap($row['domain_name'] ,45,"<br>\n")?></td>
                                             <td><?php echo wordwrap($row['domain_owner'] ,45,"<br>\n")?></td>
                                             <td><?php echo $row['domain_status'] ?></td>
                                             <td><?php echo wordwrap($row['orgunit_name'] ,45,"<br>\n")?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="inactiveurlpb"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 70%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">In Active Domains</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Domain Name </th>
                                                <th> Domain Owner </th>
                                                <th> Domain Status </th>
                                                <th> Organizations </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['domain_status']=='In Active') { ?>
                                             <tr align="center">
                                             <td><?php echo wordwrap($row['domain_name'] ,45,"<br>\n")?></td>
                                             <td><?php echo wordwrap($row['domain_owner'] ,45,"<br>\n")?></td>
                                             <td><?php echo $row['domain_status'] ?></td>
                                             <td><?php echo wordwrap($row['orgunit_name'] ,45,"<br>\n")?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="activeurlpb"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 70%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">Active Blocked Domains</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                            
                                             <th> Domain Name </th>
                                                <th> Domain Owner </th>
                                                <th> Domain Status </th>
                                                <th> Organizations </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['domain_status']=='Active') {  ?>
                                             <tr align="center">
                                             <td><?php echo wordwrap($row['domain_name'] ,45,"<br>\n")?></td>
                                             <td><?php echo wordwrap($row['domain_owner'] ,45,"<br>\n")?></td>
                                             <td><?php echo $row['domain_status'] ?></td>
                                             <td><?php echo wordwrap($row['orgunit_name'] ,45,"<br>\n")?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                   