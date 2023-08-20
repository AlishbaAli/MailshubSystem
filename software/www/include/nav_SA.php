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
                    <?php

                    $sql_dept = "SELECT *  from   tbl_organizational_unit";
                    $stmt3 = $conn->prepare($sql_dept);
                    $stmt3->execute();
                    $org_units = $stmt3->fetchAll();

                    ?>
                     <?php
        // query for Basic info 
        $user_id = $_SESSION['AdminId'];
        $stmt_a = $conn->prepare("SELECT * from admin WHERE AdminId =$user_id");
        $stmt_a->execute();
        $rowa = $stmt_a->fetch();

        ?>

                    <div class="row">

                        <div class="col-lg-1">
                          
                        </div>
                        <div class="col-lg-11">
                            <div id="navbar-menu">
                                <ul class="nav navbar-nav">


                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                            <img class="rounded-circle" src="user_images/<?php echo $rowa['image'];  ?>" onerror="this.onerror=null; this.src='user_images/avatar.jpg'" width="30" height="30" alt="">
                                        </a>
                                        <div class="dropdown-menu animated flipInY user-profile">
                                            <div class="d-flex p-3 align-items-center">
                                                <div class="drop-left m-r-10">
                                                    <img src="user_images/<?php echo $rowa['image'];  ?>" onerror="this.onerror=null; this.src='user_images/avatar.jpg'" class="rounded" width="50" height="50" alt="">
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
