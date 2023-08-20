
<?php 

$MS="SELECT orgunit_status, Count(`orgunit_id`) as totalorg,
Count(Case when orgunit_status='In Active' then `orgunit_id` end ) as Inactiveorg,
count(Case when orgunit_status='Active' then `orgunit_id` end ) as Activeorg,
count(Case when orgunit_status='Suspended' then `orgunit_id` end ) as susporg,
count(Case when orgunit_status='Terminated' then `orgunit_id` end ) as termorg
FROM `tbl_organizational_unit` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$total_org=$ms['totalorg'];
$inactive_org=$ms['Inactiveorg'];
$active_org=$ms['Activeorg'];
$susp_org=$ms['susporg'];
$term_org=$ms['termorg'];

?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#totalorg">
                                <h2 class="text-center">Organizations 
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $total_org;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success"  style="cursor: pointer;" data-toggle="modal" 
                                            data-target="#activeorg">
                                                    <label class="mb-0">Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $active_org;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#sdorg">
                                                    <label class="mb-0">Suspended</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $susp_org;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning"  style="cursor: pointer;" data-toggle="modal" 
                                            data-target="#trmorg">
                                                    <label class="mb-0">Terminated</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $term_org;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT * FROM tbl_organizational_unit inner join system_entity on system_entity.system_entityid= tbl_organizational_unit.system_entityid";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="totalorg"  
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
                                                                                <h4 align="center">Total Organizations</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> Organization </th>
                                                <th> System Entity </th>
                                                <th> Settings </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) { 
                                                 if ($row['system_setting']=='ou-defined'){
                                                    $set='Organization Dedicated';
                                                } elseif ($row['system_setting']=='sys-defined') {
                                                    $set='System Defined';
                                                } else {
                                                    $set='Organization Hybird';
                                                } ?>
                                             <tr align="center">
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['system_entity_type'] ?></td>
                                             <td><?php echo $set ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="activeorg"  
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
                                                                            <h4 align="center">Active Organizations</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Organization </th>
                                                <th> System Entity </th>
                                                <th> Settings </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['orgunit_status']=='Active') {
                                                    if ($row['system_setting']=='ou-defined'){
                                                        $set='Organization Dedicated';
                                                    } elseif ($row['system_setting']=='sys-defined') {
                                                        $set='System Defined';
                                                    } else {
                                                        $set='Organization Hybird';
                                                    } ?>
                                             <tr align="center">
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['system_entity_type'] ?></td>
                                             <td><?php echo $set; ?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="sdorg"  
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
                                                                            <h4 align="center">Suspended Organizations</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Organization </th>
                                                <th> System Entity </th>
                                                <th> Settings </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['orgunit_status']=='Suspended') { 
                                                    if ($row['system_setting']=='ou-defined'){
                                                        $set='Organization Dedicated';
                                                    } elseif ($row['system_setting']=='sys-defined') {
                                                        $set='System Defined';
                                                    } else {
                                                        $set='Organization Hybird';
                                                    } ?>
                                             <tr align="center">
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['system_entity_type'] ?></td>
                                             <td><?php echo $set ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="trmorg"  
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
                                                                            <h4 align="center">Terminated Organizations</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Organization </th>
                                                <th> System Entity </th>
                                                <th> Settings </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['orgunit_status']=='Terminated') { 
                                                    if ($row['system_setting']=='ou-defined'){
                                                        $set='Organization Dedicated';
                                                    } elseif ($row['system_setting']=='sys-defined') {
                                                        $set='System Defined';
                                                    } else {
                                                        $set='Organization Hybird';
                                                    } ?>
                                             <tr align="center">
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['system_entity_type'] ?></td>
                                             <td><?php echo $set; ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>