
<?php 

$MS="SELECT  Count(`rtemid`) as totalrte,
 Count(Case when rtem_status='In Active' then `rtemid` end ) as rteinactive
-- count(Case when role_type='Technical' then `role_prev_id` end ) as roleT,
-- count(Case when role_type='Operations' then `role_prev_id` end ) as roleO

FROM `reply_to_emails` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$totalrte=$ms['totalrte'];
$rteinactive=$ms['rteinactive'];

$MS="SELECT  Count(distinct `rtemid`) as assignrte
-- Count(Case when rtem_status='In Active' then `rtemid` end ) as rteinactive
-- count(Case when role_type='Technical' then `role_prev_id` end ) as roleT,
-- count(Case when role_type='Operations' then `role_prev_id` end ) as roleO

FROM `tbl_orgunit_rte` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$assignrte=$ms['assignrte'];

$MS="SELECT  Count(distinct `rtemid`) as inuserte
-- Count(Case when rtem_status='In Active' then `rtemid` end ) as rteinactive
-- count(Case when role_type='Technical' then `role_prev_id` end ) as roleT,
-- count(Case when role_type='Operations' then `role_prev_id` end ) as roleO

FROM `campaign` WHERE Camp_Status !='Archive' and Camp_Status !='Completed'";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$inuserte=$ms['inuserte'];


?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#totalrte">
                                <h2 class="text-center">Reply-to-Emails 
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold  text-primary"><?php echo $totalrte;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">

                                <div class="col-4 border-bottom border-right">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel"  data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#inactiverte">
                                                    <label class="mb-0   ">In Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $rteinactive;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#rtemorg">
                                                    <label class="mb-0   ">Assigned</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $assignrte;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#inuserte">
                                                    <label class="mb-0   ">In Use</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $inuserte;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT  reply_to_emails.`rtemid`, reply_to_email, orgunit_name, rtem_status,CampName

FROM `reply_to_emails` left join (SELECT  rtemid, Group_Concat(orgunit_name SEPARATOR ', ') as orgunit_name from`tbl_orgunit_rte` 
                                  left join tbl_organizational_unit on tbl_organizational_unit.orgunit_id=tbl_orgunit_rte.orgunit_id 
                                  group by rtemid) as test
on  reply_to_emails.rtemid = test.rtemid

 left join (SELECT  rtemid, Group_Concat(CampName SEPARATOR ', ') as CampName from`campaign` 
                                 where Camp_Status != 'Archive' and Camp_Status != 'Completed' group by rtemid) as test2
on  reply_to_emails.rtemid = test2.rtemid
WHERE 1";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="totalrte"  
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
                                                                                <h4 align="center">Total Reply-to-Emails </h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                            
                                             <th> Reply to Email </th>
 												<th> Organization </th>
                                                <th> Status </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) { ?>
                                             <tr align="center">
                                             <td><?php echo $row['reply_to_email'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['rtem_status'] ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="rtemorg"  
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
                                                                            <h4 align="center"></h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Reply to Email </th>
 												<th> Organization </th>
                                                <th> Status </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['orgunit_name']!=null || !empty($row['orgunit_name'])) { ?>
                                             <tr align="center">
                                             <td><?php echo $row['reply_to_email'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['rtem_status'] ?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="inuserte"  
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
                                                                            <h4 align="center">In Use Reply to Emails</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Reply to Email </th>
 												<th> Organization </th>
                                                <th> Status </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                   if ($row['CampName']!=null || !empty($row['CampName'])) { ?>
                                                    <tr align="center">
                                                    <td><?php echo $row['reply_to_email'] ?></td>
                                                    <td><?php echo $row['orgunit_name'] ?></td>
                                                    <td><?php echo $row['rtem_status'] ?></td>
                                                   </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="inactiverte"  
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
                                                                            <h4 align="center">In Use Reply to Emails </h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Reply to Email </th>
 												<th> Organization </th>
                                                <th> Status </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['rtem_status']=='In Active') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['reply_to_email'] ?></td>
                                                    <td><?php echo $row['orgunit_name'] ?></td>
                                                    <td><?php echo $row['rtem_status'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>