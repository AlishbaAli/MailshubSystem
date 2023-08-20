<?php

include 'conn.php';


?>
<nav class="navbar navbar-fixed-top">
    <div class="container-fluid">

        <div class="navbar-brand">
            <a href="index.php">
                <img src="assets/images/logo-icon.svg" alt="Mplify Logo" class="img-responsive logo">
                <span class="name">mailshub </span>
            </a>
        </div>

        <div class="navbar-right">
            <ul class="list-unstyled clearfix mb-0">
                <li>
                    <div class="navbar-btn btn-toggle-show">

                        <!-- <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button> -->


                    </div>
                    <!-- <a href="javascript:void(0);" class="btn-toggle-fullwidth btn-toggle-hide"><i class="fa fa-bars"></i></a> -->
                </li>
                <li>
                    <form id="navbar-search" class="navbar-form search-form">

                        <div class="row">

                            <h4> Organization:&nbsp&nbsp </h4>

                            <?php
                            $stmts1 = $conn->prepare("SELECT * FROM system_setting WHERE status='Active'");
									$stmts1->execute();
									$sys_settings= $stmts1->fetch();


                            if (isset($_SESSION['orgunit_id'])) {
                                $org = $_SESSION['orgunit_id'];
                                $stmt = $conn->prepare("SELECT orgunit_name FROM tbl_organizational_unit WHERE orgunit_id = $org");
                                $stmt->execute();
                                $orgname = $stmt->fetch();

                                //get its settinggs
                                
								$stmts = $conn->prepare("SELECT system_setting FROM tbl_organizational_unit WHERE orgunit_id=:orgunit_id");
								$stmts->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
								$stmts->execute();
								$settings= $stmts->fetch();
                                $_SESSION['settings_type']= $settings['system_setting'];

								if(trim($_SESSION['settings_type'])=="sys-defined"){
									//system settings the above query
                                    
									$_SESSION['embargo_duration_type']= "sys-defined";
									$_SESSION['embargo_duration']= $sys_settings['embargo_duration'];
									$_SESSION['max_records_type']="sys-defined";
									$_SESSION['max_records']=$sys_settings['max_records'];
									$_SESSION['data_loading_type']=$sys_settings['data_loading_type'];
									$_SESSION['embargo_implementation_type']="sys-defined";
									$_SESSION['unsubscription_type']="sys-defined";
									$_SESSION['domain_block_type']="sys-defined";
									$_SESSION['url_block_type']="sys-defined";
                                 
								}
								else if(trim($_SESSION['settings_type'])=="ou-defined"){
									$stmts2 = $conn->prepare("SELECT * FROM `orgunit-systemsetting` WHERE status='Active' AND
									 orgunit_id=:orgunit_id");
									$stmts2->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
									$stmts2->execute();
									$org_settings= $stmts2->fetch();
                                  

									$_SESSION['embargo_duration_type']= $org_settings['embargo_duration_type'];
									$_SESSION['org_embargo_duration']= $org_settings['org_embargo_duration'];
									$_SESSION['max_records_type']= $org_settings['max_records_type'];
									$_SESSION['max_records']=$org_settings['max_records'];
									$_SESSION['data_loading_type']=$org_settings['data_loading_type'];
									$_SESSION['embargo_implementation_type']= $org_settings['embargo_implementation_type'];
									$_SESSION['unsubscription_type']=$org_settings['unsubscription_type'];
									$_SESSION['domain_block_type']=$org_settings['domain_block_type'];
									$_SESSION['url_block_type']=$org_settings['url_block_type'];
                                   
									

								}

                                //settings stored in session variables



                            ?>
                                <h4 id="orgunit_name"><?php echo $orgname['orgunit_name'] ?></h4>

                            <?php } else { 
                                $_SESSION['embargo_duration_type']= "sys-defined";
									$_SESSION['embargo_duration']= $sys_settings['embargo_duration'];
									$_SESSION['max_records_type']="sys-defined";
									$_SESSION['max_records']=$sys_settings['max_records'];
									$_SESSION['data_loading_type']=$sys_settings['data_loading_type'];
									$_SESSION['embargo_implementation_type']="sys-defined";
									$_SESSION['unsubscription_type']="sys-defined";
									$_SESSION['domain_block_type']="sys-defined";
									$_SESSION['url_block_type']="sys-defined"; ?>

                                <h4 id="orgunit_name">None Selected</h4>

                            <?php } ?>






                        </div>
                    </form>
                </li>

                <li>
                    <?php

                    $sql_dept = "SELECT *  from   tbl_organizational_unit";
                    $stmt3 = $conn->prepare($sql_dept);
                    $stmt3->execute();
                    $org_units = $stmt3->fetchAll();

                    ?>

                    <div class="row">

                        <div class="col-lg-1">
                            <div id="navbar-menu">
                                <ul class="nav navbar-nav">


                                    <li class="dropdown">
                                        <?php

                                        $AdminId = $_SESSION['AdminId'];
                                        $stmt = $conn->prepare("SELECT
  
                                           r.restriction_level AS r_level
                                       FROM
                                           admin AS u
                                       INNER JOIN tbl_user_role_prev AS ur
                                       INNER JOIN tbl_role_privilege AS r
                                       ON
                                           u.AdminId = ur.user_id AND u.AdminId =:AdminId AND r.role_prev_id = ur.role_prev_id");
                                        $stmt->bindValue(':AdminId', $AdminId);
                                        $stmt->execute();
                                        $rl = $stmt->fetch();
                                        if ($rl["r_level"] == '0') { 

                                           ?>

                                            <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                                <img class="rounded-circle" src="assets/images/org.jpg" width="30" alt="">
                                            </a>
                                            <div class="dropdown-menu animated flipInY user-profile">

                                                <div class="m-t-10 p-3 drop-list">
                                                    <ul class="list-unstyled">
                                                        <form class="custom-validation" action="manage_org_db.php" method="post">
                                                            <select id="selectBox" onchange="changeFunc();" name="orgunit_id" id="orgunit_id" class="form-control" required>
                                                                <option value="" disabled selected>Select Organization</option>
                                                                <?php foreach ($org_units as $output) { 

                                            $stmts = $conn->prepare("SELECT system_setting FROM tbl_organizational_unit WHERE orgunit_id=:orgunit_id");
                                            $stmts->bindValue(':orgunit_id', $output['orgunit_id']);
                                            $stmts->execute();
                                            $settings= $stmts->fetch(); 
                                            if(trim($settings['system_setting'])=="ou-defined"){
                                                $stmts2 = $conn->prepare("SELECT * FROM `orgunit-systemsetting` WHERE status='Active' AND
                                                orgunit_id=:orgunit_id");
                                               $stmts2->bindValue(':orgunit_id', $output['orgunit_id']);
                                               $stmts2->execute();
                                               $org_settings= $stmts2->fetch();
                                               if($stmts2->rowCount()<1){?>

                                                <option value="<?php echo $output["orgunit_id"]; ?>" disabled> <?php echo $output["orgunit_name"]." (Setup Incomplete)"; ?> </option>
                                               <?php    continue;

                                               }
                                            }
                                               if($output['orgunit_status']!='Active'){?>
                                                <option value="<?php echo $output["orgunit_id"]; ?>" disabled> <?php echo $output["orgunit_name"]." (".$output['orgunit_status'].")" ; ?> </option>

                                            <?php   
                                        continue;    
                                        }
                                            
                                            
                                        
                                        //     if($output['system_entityid']!=2){?>
                                        <!-- //         <option value="<?php echo $output["orgunit_id"]; ?>" disabled> <?php echo $output["orgunit_name"]." (Technical)" ; ?> </option> -->

                                        //     <?php   
                                        // continue;    
                                        // }
                                        if($output['system_entityid']==2){ 
                                            
                                            ?>

                                                                    <option value="<?php echo $output["orgunit_id"]; ?>"> <?php echo $output["orgunit_name"]; ?> </option>

                                                                <?php

                                                               } } ?>

                                                                    <option value="none"> All </option>
                                                            </select>
                                                        </form>
                                                    </ul>
                                                </div>

                                            </div>
                                        <?php } ?>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-11">
                            <div id="navbar-menu">
                                <ul class="nav navbar-nav">

                                <?php
        // query for Basic info 
        $user_id = $_SESSION['AdminId'];
        $stmt_a = $conn->prepare("SELECT * from admin WHERE AdminId =$user_id");
        $stmt_a->execute();
        $rowa = $stmt_a->fetch();

        ?>
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                            <img class="rounded-circle" src="user_images/<?php echo $rowa['image'];  ?>" onerror="this.onerror=null; this.src='user_images/avatar.jpg'" width="30" alt="">
                                        </a>
                                        <div class="dropdown-menu animated flipInY user-profile">
                                            <div class="d-flex p-3 align-items-center">
                                                <div class="drop-left m-r-10">
                                                    <img src="user_images/<?php echo $rowa['image'];  ?>" onerror="this.onerror=null; this.src='user_images/avatar.jpg'" class="rounded" width="50" alt="">
                                                </div>
                                                <div class="drop-right">
                                                    <h4><?php echo $_SESSION['username'] ?></h4>

                                                    <p class="user-name"></p>
                                                </div>
                                            </div>
                                            <div class="m-t-10 p-3 drop-list">
                                                <ul class="list-unstyled">
                                                    <li><a href="profile2.php"><i class="icon-user"></i>My Profile</a></li>
                                                    <!-- <li><a href="app-inbox.html"><i class="icon-envelope-open"></i>Messages</a></li>
                                            <li><a href="javascript:void(0);"><i class="icon-settings"></i>Settings</a></li> -->
                                                    <li class="divider"></li>
                                                    <li><a href="logout.php"><i class="icon-power"></i>Logout</a></li>
                                                </ul>
                                            </div>

                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>
        </div>
    </div>
</nav>


<script type="text/javascript">
    function changeFunc() {

        var selectBox = document.getElementById("selectBox");
        var orgunit_id = selectBox.options[selectBox.selectedIndex].value;

        $.ajax({
                url: "manage_org_db.php",
                method: "POST",
                data: {
                    orgunit_id: orgunit_id
                },
                dataType: "JSON",
                success: function(data) {

                    var org = " " + data.orgunit_name;
                    $('#orgunit_name').text(org);
                   var str=window.location.href
                    const urlarray = str.split("/");
                    var lastItem = urlarray.pop();
                    location.assign(lastItem);



                }


            }

        )




    }
    
</script>
