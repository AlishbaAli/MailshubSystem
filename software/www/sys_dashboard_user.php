
<?php 

$MS="SELECT AdminId, Count(`AdminId`) as totaluser,
Count(Case when status='Terminated' or status='Org-Terminated' then `AdminId` end ) as userT,
count(Case when status='Suspended' or status='Org-Suspended' then `AdminId` end ) as userS,
count(Case when status='Active' then `AdminId` end ) as userA

FROM `admin` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$totaluser=$ms['totaluser'];
$userA=$ms['userA'];
$userT=$ms['userT'];
$userS=$ms['userS'];


?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#totaluser">
                                <h2 class="text-center"> Users 
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $totaluser;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success"  style="cursor: pointer;" data-toggle="modal" 
                                            data-target="#userA">
                                                    <label class="mb-0 ">Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $userA;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-bottom border-right ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#userS">
                                                    <label class="mb-0  ">Suspended</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $userS;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#userT">
                                                    <label class="mb-0 ">Terminated</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $userT;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT AdminId,username,email,role_prev_title,orgunit_name,status,date(add_date) as add_date,added_by

FROM `admin` 
left join (Select tbl_user_role_prev.user_id as uidr,role_prev_title from tbl_user_role_prev inner join tbl_role_privilege 
on  tbl_user_role_prev.role_prev_id = tbl_role_privilege.role_prev_id ) as user_role
on `admin`.AdminId= user_role.uidr 

left join (Select tbl_orgunit_user.user_id as uido,orgunit_name from tbl_orgunit_user inner join tbl_organizational_unit 
on  tbl_orgunit_user.orgunit_id = tbl_organizational_unit.orgunit_id ) as user_org
on `admin`.AdminId= user_org.uido 

WHERE 1";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="totaluser"  
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
                                                                                <h4 align="center">Total Organizations</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Date </th>
                                                <th> Added By </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) { ?>
                                             <tr align="center">
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                             <td><?php echo $row['add_date'] ?></td>
                                             <td><?php echo $row['added_by'] ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="userA"  
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
                                                                            <h4 align="center">Active Users</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Date </th>
                                                <th> Added By </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['status']=='Active') { ?>
                                              <tr align="center">
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                             <td><?php echo $row['add_date'] ?></td>
                                             <td><?php echo $row['added_by'] ?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="userS"  
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
                                                                            <h4 align="center">Suspended Organizations</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Date </th>
                                                <th> Added By </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['status']=='Suspended' || $row['status']=='Org-Suspended') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                             <td><?php echo $row['add_date'] ?></td>
                                             <td><?php echo $row['added_by'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="userT"  
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
                                                                            <h4 align="center">Terminated Users</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Date </th>
                                                <th> Added By </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['status']=='Terminated' || $row['status']=='Org-Terminated') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                             <td><?php echo $row['add_date'] ?></td>
                                             <td><?php echo $row['added_by'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>