
<?php 

$MS="SELECT role_type, Count(`role_prev_id`) as totalrole,
Count(Case when role_type='Administrative' then `role_prev_id` end ) as roleA,
count(Case when role_type='Technical' then `role_prev_id` end ) as roleT,
count(Case when role_type='Operations' then `role_prev_id` end ) as roleO

FROM `tbl_role_privilege` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$totalrole=$ms['totalrole'];
$roleA=$ms['roleA'];
$roleT=$ms['roleT'];
$roleO=$ms['roleO'];


?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header" data-toggle="modal" 
                                            data-target="#totalrole">
                                <h2 class="text-center"> Roles 
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $totalrole;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning" data-toggle="modal" 
                                            data-target="#roleA">
                                                    <label class="mb-0    ">Administrative</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $roleA;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-bottom border-right">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success" data-toggle="modal" 
                                            data-target="#roleT">
                                                    <label class="mb-0    ">Technical</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $roleT;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger" data-toggle="modal" 
                                            data-target="#roleO">
                                                    <label class="mb-0    ">Operational</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $roleO;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT *,Count(user_id)as uid,Group_Concat(username SEPARATOR ', ') as unm 
FROM `tbl_role_privilege` left join tbl_user_role_prev 
on tbl_role_privilege.role_prev_id = tbl_user_role_prev.role_prev_id left join `admin` 
on `admin`.AdminId=tbl_user_role_prev.user_id 
WHERE 1 group by tbl_role_privilege.role_prev_id";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="totalrole"  
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
                                                                                <h4 align="center">Total Roles</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> Role </th>
                                                <th> Role Type </th>
                                                <th> Total Users </th>
                                                <th> Usernames </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) { ?>
                                             <tr align="center">
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['role_type'] ?></td>
                                             <td><?php echo $row['uid'] ?></td>
                                             <td><?php echo wordwrap($row['unm'],40,"<br>\n") ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="roleO"  
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
                                                                            <h4 align="center">Operational Roles</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                           
 												<th> Role </th>
                                                <th> Role Type </th>
                                                <th> Total Users </th>
                                                <th> Usernames </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['role_type']=='Operations') { ?>
                                             <tr align="center">
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['role_type'] ?></td>
                                             <td><?php echo $row['uid'] ?></td>
                                             <td><?php echo wordwrap($row['unm'],40,"<br>\n") ?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="roleT"  
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
                                                                            <h4 align="center">Technical Roles</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Role </th>
                                                <th> Role Type </th>
                                                <th> Total Users </th>
                                                <th> Usernames </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                   if ($row['role_type']=='Technical') { ?>
                                                    <tr align="center">
                                                    <td><?php echo $row['role_prev_title'] ?></td>
                                                    <td><?php echo $row['role_type'] ?></td>
                                                    <td><?php echo $row['uid'] ?></td>
                                                    <td><?php echo wordwrap($row['unm'],40,"<br>\n") ?></td>
                                                   </tr>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="roleA"  
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
                                                                            <h4 align="center">Adminitrative Role</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Role </th>
                                                <th> Role Type </th>
                                                <th> Total Users </th>
                                                <th> Usernames </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['role_type']=='Administrative') { ?>
                                                    <tr align="center">
                                                    <td><?php echo $row['role_prev_title'] ?></td>
                                                    <td><?php echo $row['role_type'] ?></td>
                                                    <td><?php echo $row['uid'] ?></td>
                                                    <td><?php echo wordwrap($row['unm'],40,"<br>\n") ?></td>
                                                   </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>