
<?php 

$MS="SELECT  Count(`dnsbl_id`) as totaldnsbl,
Count(Case when priority_color='Red' then `dnsbl_id` end ) as Red,
count(Case when priority_color='Yellow' then `dnsbl_id` end ) as Yellow,
count(Case when priority_color='Orange' then `dnsbl_id` end ) as Orange,
count(Case when priority_color='black' then `dnsbl_id` end ) as Black
FROM `dnsbl` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$red=$ms['Red'];
$yellow=$ms['Yellow'];
$orange=$ms['Orange'];
$black=$ms['Black'];
$t_dnsbl=$ms['totaldnsbl'];

?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header" style="cursor: pointer;" data-toggle="modal" 
                                            data-target="#dnsbl">
                                <h2 class="text-center">DNS BL
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $t_dnsbl;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active "  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#blackdns">
                                                    <label class="mb-0">Black</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $black;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-bottom border-right">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#reddns">
                                                    <label class="mb-0">Red</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $red;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active " style="color:#ffa111"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#orangedns">
                                                    <label class="mb-0">Orange</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $orange;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT * FROM dnsbl";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="dnsbl"  
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
                                                                                <h4 align="center">DNS BL</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> DNS BL </th>
                                                <th> Priority Color </th>
                                                <th> Priority Score </th>
                                                <th> Status </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                         <?php foreach ($rows as $row) {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['dnsbl_name'] ?></td>
                                             <td><?php echo $row['priority_color'] ?></td>
                                             <td><?php echo $row['priority_score'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="blackdns"  
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
                                                                            <h4 align="center">DNS BL</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             
 												<th> DNS BL </th>
                                                <th> Priority Color </th>
                                                <th> Priority Score </th>
                                                <th> Status </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                         <?php foreach ($rows as $row) {
                                                  if ($row['priority_color']=='black') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['dnsbl_name'] ?></td>
                                             <td><?php echo $row['priority_color'] ?></td>
                                             <td><?php echo $row['priority_score'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="reddns"  
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
                                                                            <h4 align="center">DNS BL</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             
 												<th> DNS BL </th>
                                                <th> Priority Color </th>
                                                <th> Priority Score </th>
                                                <th> Status </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                         <?php foreach ($rows as $row) {
                                                  if ($row['priority_color']=='red') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['dnsbl_name'] ?></td>
                                             <td><?php echo $row['priority_color'] ?></td>
                                             <td><?php echo $row['priority_score'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="orangedns"  
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
                                                                            <h4 align="center">DNS BL</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> DNS BL </th>
                                                <th> Priority Color </th>
                                                <th> Priority Score </th>
                                                <th> Status </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['priority_color']=='orange') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['dnsbl_name'] ?></td>
                                             <td><?php echo $row['priority_color'] ?></td>
                                             <td><?php echo $row['priority_score'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>