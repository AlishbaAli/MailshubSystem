
<?php 
$date=date('Y-m-d');
$MS="SELECT AdminId, Count(`AdminId`) as totaluser,
Count(Case when login_time	> logout_time or(login_time is not null and logout_time is null) then `AdminId` end ) as userT,
count(Case when logout_time	>=login_time or login_time is null then `AdminId` end ) as userS,
count(Case when date(login_time) like '$date' then `AdminId` end ) as userA

FROM `admin` WHERE status='Active' and email_status ='Verified'";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$totaluserA=$ms['totaluser'];
$Todaylogin=$ms['userA'];
$userLoggedin=$ms['userT'];
$userLoggedout=$ms['userS'];


?>

                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#userLoggedin">
                                <h2 class="text-center">  Currently Online
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $userLoggedin;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success"  style="cursor: pointer;" data-toggle="modal" 
                                            data-target="#totaluserA">
                                                    <label class="mb-0 ">Total Active</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $totaluserA;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-bottom border-right ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#todaylogin">
                                                    <label class="mb-0  ">Today's Login</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $Todaylogin;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning"  style="cursor: pointer;"  data-toggle="modal" 
                                            data-target="#userLoggedout">
                                                    <label class="mb-0 ">Offline Users</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $userLoggedout;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT *, date(login_time) as lgin,AdminId,username,email,role_prev_title,orgunit_name,status,date(add_date) as add_date,added_by

FROM `admin` 
left join (Select tbl_user_role_prev.user_id as uidr,role_prev_title from tbl_user_role_prev inner join tbl_role_privilege 
on  tbl_user_role_prev.role_prev_id = tbl_role_privilege.role_prev_id ) as user_role
on `admin`.AdminId= user_role.uidr 

left join (Select tbl_orgunit_user.user_id as uido,orgunit_name from tbl_orgunit_user inner join tbl_organizational_unit 
on  tbl_orgunit_user.orgunit_id = tbl_organizational_unit.orgunit_id AND ou_status='Active') as user_org
on `admin`.AdminId= user_org.uido 

WHERE status ='Active' and email_status ='Verified'";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="totaluserA"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '80%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '010%';min-width: 80%;" role="document">
                                                                        <div class="modal-content" style="overflow:scroll;">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">  
                                                                                <h4 align="center">Total Active Users</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Login Time</th>
                                                <th> Logout Time</th>
                                                <th> Add Date </th>
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
                                             <td><?php echo $row['login_time'] ?></td>
                                             <td><?php echo $row['logout_time'] ?></td>
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
                    <div class="modal fade"   id="todaylogin"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 80%;" role="document">
                                                                        <div class="modal-content"  style="overflow:scroll;">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">Users Logged In Today</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Login Time</th>
                                                <th> Logout Time</th>
                                                <th> Add Date </th>
                                                <th> Added By </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                //  echo $row['lgin']; echo date('Y-m-d') ;
                                                 if ( $row['lgin']==date('Y-m-d') 
                                                ) { ?>
                                              <tr align="center">
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                             <td><?php echo $row['login_time'] ?></td>
                                             <td><?php echo $row['logout_time'] ?></td>
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
                    <div class="modal fade"   id="userLoggedin"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 80%;" role="document">
                                                                        <div class="modal-content" style="overflow:scroll;">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">Currently Online Users</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                                <th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Login Time</th>
                                                <th> Logout Time</th>
                                                <th> Add Date </th>
                                                <th> Added By </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ( $row['login_time']>$row['logout_time']) {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                             <td><?php echo $row['login_time'] ?></td>
                                             <td><?php echo $row['logout_time'] ?></td>
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
                    <div class="modal fade"   id="userLoggedout"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 80%;" role="document">
                                                                        <div class="modal-content" style="overflow:scroll;">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">Offline Users</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> User Name </th>
                                                <th> Email </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Status </th>
                                                <th> Login Time</th>
                                                <th> Logout Time</th>
                                                <th> Add Date </th>
                                                <th> Added By </th>
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['login_time']<=$row['logout_time'] || $row['login_time']==null ) {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['status'] ?></td>
                                             <td><?php echo $row['login_time'] ?></td>
                                             <td><?php echo $row['logout_time'] ?></td>
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