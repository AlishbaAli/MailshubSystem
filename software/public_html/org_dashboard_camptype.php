
<?php 

$MS="SELECT ctype_status, Count(`ctype_id`) as totalctype,
Count(Case when ctype_status='In Active' then `ctype_id` end ) as Inactivectype,
count(Case when ctype_status='Active' then `ctype_id` end ) as Activectype

FROM `Campaign_type` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$total_ctype=$ms['totalctype'];
$inactive_ctype=$ms['Inactivectype'];
$active_ctype=$ms['Activectype'];


?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header" data-toggle="modal" 
                                            data-target="#totalctype">
                                <h2 class="text-center">Campaign Type
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $total_ctype;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-6 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success" data-toggle="modal" 
                                            data-target="#activectype">
                                                    <label class="mb-0">Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $active_ctype;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger" data-toggle="modal" 
                                            data-target="#sdctype">
                                                    <label class="mb-0">In Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $inactive_ctype;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT * FROM Campaign_type left join (Select ctype_id, GROUP_CONCAT(orgunit_name SEPARATOR ', ') as orgunit_name from tbl_organizational_unit 
inner join tbl_orgunit_ctype on tbl_orgunit_ctype.orgunit_id= tbl_organizational_unit.orgunit_id group by ctype_id) as oname 
on oname.ctype_id=Campaign_type.ctype_id ";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="totalctype"  
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
                                                                                <h4 align="center">Campaign Type</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th>Campaign Type </th>
                                                <th> Organization </th>
                                               
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                    ?>
                                             <tr align="center">
                                             <td><?php echo $row['ctype_name'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                            
                                            
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="activectype"  
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
                                                                            <h4 align="center">Active Campaign Types</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th>Campaign Type </th>
                                                <th> Organization </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['ctype_status']=='Active') { ?>
                                             <tr align="center">
                                             <td><?php echo $row['ctype_name'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                            
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="sdctype"  
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
 												
                                             <th>Campaign Type </th>
                                                <th> Organization </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['ctype_status']=='In Active') { ?>
                                             <tr align="center">
                                             <td><?php echo $row['ctype_name'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                   