
<?php 
$orgunit_id = "";
if (isset($_SESSION['orgunit_id'])) {
    $orgunit_id = $_SESSION['orgunit_id'];
    echo $orgunit_id;
}
if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
$AdminId = $_SESSION['AdminId'];
$Quote='SELECT Group_Concat("\'",`user_id`,"\'" separator ",") as users from  tbl_orgunit_user';

if (!empty($_SESSION['orgunit_id'])) {
$Quote.=" where orgunit_id = '$orgunit_id'";
}

$Quote=$conn->prepare($Quote);$Quote->execute();
$quote=$Quote->fetch();
$aid=$quote['users'];

$MS="SELECT CampID, Count(`CampID`) as totalcamp,
Count(Case when Camp_Status='Archive' then `CampID` end ) as arc,
count(Case when Camp_Status='Completed' then `CampID` end ) as comp,
count(Case when Camp_Status!='Archive' and Camp_Status!='Completed' then `CampID` end ) as prog

FROM `campaign`  left join tbl_orgunit_user on tbl_orgunit_user.ou_id=campaign.ou_id WHERE ou_status='Active' and user_id in ('0',$aid)";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$totalcamp=$ms['totalcamp'];
$ca=$ms['arc'];
$cc=$ms['comp'];
$cp=$ms['prog'];


?>


                    <div class="col-sm-4">
                        <div class="card" style="border-color:black;">
                            <div class="header" data-toggle="modal" 
                                            data-target="#totalcamp">
                                <h2 class="text-center">Campaigns
                                </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $totalcamp;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger" data-toggle="modal" 
                                            data-target="#inprog">
                                                    <label class="mb-0">In Progress</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $cp;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success" data-toggle="modal" 
                                            data-target="#CCOMP">
                                                    <label class="mb-0">Completed </label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $cc;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning" data-toggle="modal" 
                                            data-target="#carc">
                                                    <label class="mb-0">Archived</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $ca;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <?php 

$query="SELECT CampID,CampName,username,email,reply_to_email,role_prev_title,orgunit_name,Camp_Status,
date(Camp_Created_Date) as Camp_Created_Date,date(Camp_Send_Date) as Camp_Send_Date

FROM `campaign` 

left join (Select tbl_orgunit_user.ou_id,tbl_orgunit_user.user_id as uido,orgunit_name from tbl_orgunit_user inner join tbl_organizational_unit 
on  tbl_orgunit_user.orgunit_id = tbl_organizational_unit.orgunit_id ) as user_org
on `campaign`.ou_id= user_org.ou_id 

left join (Select tbl_user_role_prev.user_id as uidr,role_prev_title from tbl_user_role_prev inner join tbl_role_privilege 
on  tbl_user_role_prev.role_prev_id = tbl_role_privilege.role_prev_id ) as user_role
on `user_org`.uido= user_role.uidr 



left join admin on `user_org`.uido  =`admin`.AdminId

left join reply_to_emails on `campaign`.rtemid =`reply_to_emails`.rtemid

WHERE  `user_org`.uido in ('0',$aid)";
$query=$conn->prepare($query);
$query->execute();
$rows=$query->fetchAll();

?>
                    <div class="modal fade"   id="totalcamp"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '80%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '80%';min-width: 80%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">  
                                                                                <h4 align="center">Total Campaigns</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
 												<th> Campaign Name </th>
                                                <th> Username </th>
                                                <th> Reply to Email</th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Campaign Status </th>
                                                <th> Campaign Created Date </th>
                                                <th> Campaign Sent Date </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) { ?>
                                             <tr align="center">
                                             <td><?php echo $row['CampName'] ?></td>
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['reply_to_email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['Camp_Status'] ?></td>
                                             <td><?php echo $row['Camp_Created_Date'] ?></td>
                                             <td><?php echo $row['Camp_Send_Date'] ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>  
                                                                                        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="inprog"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 85%;" role="document">
                                                                        <div class="modal-content">                                                                           
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                 </button>
                                                                            </div>
                                                                            <div class="modal-body">       
                                                                            <h4 align="center">In Progress Campaigns</h4>
                                        <table class="table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom">
 										<thead class="text-center">
 											<tr style="background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;">
 												
                                             <th> Campaign Name </th>
                                                <th> Username </th>
                                                <th> Reply to Email ID </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Campaign Status </th>
                                                <th> Campaign Created Date </th>
                                                <th> Campaign Sent Date </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                 if ($row['Camp_Status']!='Archive' && $row['Camp_Status']!='Completed') { ?>
                                             <tr align="center">
                                             <td><?php echo $row['CampName'] ?></td>
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['reply_to_email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['Camp_Status'] ?></td>
                                             <td><?php echo $row['Camp_Created_Date'] ?></td>
                                             <td><?php echo $row['Camp_Send_Date'] ?></td>
                                            </tr>
                                            <?php } }?>
                                        </tbody>   
                                        </table>        
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="CCOMP"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 85%;" role="document">
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
 												
                                             <th> Campaign Name </th>
                                                <th> Username </th>
                                                <th> Reply to Email ID </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Campaign Status </th>
                                                <th> Campaign Created Date </th>
                                                <th> Campaign Sent Date </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['Camp_Status']=='Completed') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['CampName'] ?></td>
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['reply_to_email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['Camp_Status'] ?></td>
                                             <td><?php echo $row['Camp_Created_Date'] ?></td>
                                             <td><?php echo $row['Camp_Send_Date'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>             
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>
                    <div class="modal fade"   id="carc"  
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle" style="max-width: '100%';" >
                                                                    <div class="modal-dialog modal-dialog-centered " 
                                                                    style="max-width: '100%';min-width: 85%;" role="document">
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
 												
                                             <th> Campaign Name </th>
                                                <th> Username </th>
                                                <th> Reply to Email ID </th>
                                                <th> Role </th>
                                                <th> Organization </th>
                                                <th> Campaign Status </th>
                                                <th> Campaign Created Date </th>
                                                <th> Campaign Sent Date </th>
 												
 											</tr>
 										</thead> 
                                         <tbody>
                                             <?php foreach ($rows as $row) {
                                                  if ($row['Camp_Status']=='Archive') {  ?>
                                             <tr align="center">
                                             <td><?php echo $row['CampName'] ?></td>
                                             <td><?php echo $row['username'] ?></td>
                                             <td><?php echo $row['reply_to_email'] ?></td>
                                             <td><?php echo $row['role_prev_title'] ?></td>
                                             <td><?php echo $row['orgunit_name'] ?></td>
                                             <td><?php echo $row['Camp_Status'] ?></td>
                                             <td><?php echo $row['Camp_Created_Date'] ?></td>
                                             <td><?php echo $row['Camp_Send_Date'] ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>   
                                        </table>     
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                     </div>