
<?php 
$date1=date("Y-m-d",strtotime("-30 days"));
$date2=date("Y-m-d",strtotime("-20 days"));
$date3=date("Y-m-d",strtotime("-10 days"));
$date4=date("Y-m-d",strtotime("-15 days"));
$MS="SELECT use_date, Count(`email`) as totalembargo,
Count(Case when date(use_date)<'$date1' then `email` end ) as totalemb30,
Count(Case when date(use_date)>='$date1' then `email` end ) as emb30,
count(Case when date(use_date)>='$date2' then `email` end ) as emb20,
count(Case when date(use_date)>='$date3' then `email` end ) as emb10,
count(Case when date(use_date)>='$date4' then `email` end ) as emb15

FROM `email_embargo` WHERE 1";
$MS=$conn->prepare($MS);
$MS->execute();
$ms=$MS->fetch();

$total_ctype=$ms['totalembargo'];
$totalemb30=$ms['totalemb30'];
$inactive_ctype=$ms['emb20'];
$active_ctype=$ms['emb30'];
$emb10=$ms['emb10'];
$emb15=$ms['emb15'];


?>


                    <div class="col-sm-6">
                        <div class="card" style="border-color:black;">
                            <div class="header" data-toggle="modal" >
                                <h2 class="text-center">Embargo Free Data </h2>
                                <h1 class="text-center font-30 font-weight-bold text-primary"><?php echo $totalemb30;?></h1>
                            </div>
                            <div class="body">
                                <div class="row text-center">
                                    <div class="col-3 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-danger" data-toggle="modal" >
                                                    <label class="mb-0">30 Days</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $active_ctype;?></h4>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 border-right border-bottom ">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-warning" data-toggle="modal"  >
                                                    <label class="mb-0">20 Days</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $inactive_ctype;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 border-bottom border-right">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-info" data-toggle="modal" >
                                                    <label class="mb-0">15 Days</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $emb15;?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 border-bottom border-right">
                                        <div id="Traffic1" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active text-success" data-toggle="modal" >
                                                    <label class="mb-0">10 Days</label>
                                                    <h4 class="font-30 font-weight-bold "><?php echo $emb10;?></h4>
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
                   