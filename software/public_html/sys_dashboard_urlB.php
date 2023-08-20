
<?php 

$MS="SELECT  Count(`url`) as urlb,
Count(Case when status='In Active' then `url` end ) as Inactiveorg,
count(Case when status='Active' then `url` end ) as Activeorg

FROM `blocked_url` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$total_org=$ms['urlb'];
$inactive_org=$ms['Inactiveorg'];
$active_org=$ms['Activeorg'];

?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#urlb">
                                <h2 class="text-center">Blocked Urls 
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $total_org;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-6 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#inactiveurlb">
                                                    <label class="mb-0">In Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $inactive_org;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success"  style="cursor: pointer;" data-toggle="modal" 
                                            data-target="#activeurlb">
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

$query="SELECT *,date(system_date)as sd FROM blocked_url";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="urlb"  
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
                                                                                <h4 align="center">Blocked URLs</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> URL </th>
                                                <th> Date </th>
                                                <th> Status </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) { ?>
                                             <tr align="center">
                                             <td><?php echo $row['url'] ?></td>
                                             <td><?php echo $row['sd'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="inactiveurlb"  
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
                                                                            <h4 align="center">In Active URLs</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> URL </th>
                                                <th> Date </th>
                                                <th> Status </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['status']=='In Active') { ?>
                                             <tr align="center">
                                             <td><?php echo $row['url'] ?></td>
                                             <td><?php echo $row['sd'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="activeurlb"  
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
                                                                            <h4 align="center">Active Blocked URLs</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
                                            
                                             <th> URL </th>
                                                <th> Date </th>
                                                <th> Status </th>

 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['status']=='Active') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['url'] ?></td>
                                             <td><?php echo $row['sd'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                   