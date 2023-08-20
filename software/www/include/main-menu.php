<!-- Page Loader -->

<!-- Overlay For Sidebars -->
<div class="overlay" style="display: none;"></div>

<div id="wrapper">

    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">

            <div class="navbar-brand">
                <a href="index.php">
                    <span class="name">
                    	<?php echo "".$_SESSION['firstname'];?> </span>
                </a>
            </div>
            
            <div class="navbar-right">
                <ul class="list-unstyled clearfix mb-0">
					 <li>
                        <form id="navbar-search" class="navbar-form search-form">
                  
				  <h3 style="color:#007bfff0; font-family: Helvetica, Arial, sans-serif; padding-top:12px;"><strong>ARTICLES CONTRIBUTIONS SYSTEM <?php echo $dates = date('Y'); ?></strong></h3>
                        </form>
                    </li>

					<li>
                       
                    </li>
                    <li>
                        <div id="navbar-menu">
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                    <a href="logout.php" class="icon-menu" >
                                         <button type="button" class="btn btn-primary"><i class="icon-power"></i> <span>Logout</span></button>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="leftsidebar" class="sidebar">
        <div class="sidebar-scroll">
            <nav id="leftsidebar-nav" class="sidebar-nav">
                <ul id="main-menu" class="metismenu">
                    <li class="active"><a href="index.php"><i class="icon-home"></i><span>Dashboard</span></a></li>
					<li><a href="addCampaign.php"><i class="icon-plus"></i><span>Add Campaign</span></a></li>
                    <li><a href="campaignHistory.php"><i class="icon-list"></i><span>Campaign Histroy</span></a></li>
                    <li><a href="unsubscriberList.php"><i class="fa fa-trash"></i><span>Unsubscriber List</span></a></li>
                </ul>
            </nav>
        </div>
    </div>