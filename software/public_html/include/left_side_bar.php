<div id="leftsidebar" class="sidebar">
    <div class="sidebar-scroll">
        <nav id="leftsidebar-nav" class="sidebar-nav">
            <ul id="main-menu" class="metismenu">



                <li><a href="sys__dashboard.php"><i class="icon-screen-tablet"></i><span>System Dashboard</span></a></li>

                <li><a href="org_dashboard.php"><i class="icon-screen-tablet"></i><span>Organization Dashboard</span></a></li>

                <li><a href="index.php"><i class="icon-home"></i><span>Campaign Dashboard</span></a></li>

                <li><a href="verifyAlertDashboard.php"><i class="icon-check"></i><span>Verification Dashboard</span></a></li>
                <!-- fa fa-check-square-o -->

                <li class="heading">Main Activities</li>
                <li><a class="has-arrow" href="role_and_activity.php"><i class="icon-lock"></i><span>User Management</span></a>
                    <ul>
                        <li><a href="role_and_activity.php"> User, Role & <br> Activity Management </a></li>
                        <li><a href="user_management.php"> User Management </a></li>
                        <li><a href="add_super_admin.php"> Add Super Admin </a></li>

                    </ul>
                </li>


                <li><a href="orgunit_form.php"><i class="fa fa-institution"></i><span>Organizational Unit</span></a></li>
                <li><a class="has-arrow" href="reply_to_email_form.php"><i class="fa fa-institution"></i><span>Institutes <br> Management</span></a>

                    <ul>
                        <li><a href="registered_institutes.php">Registered Institutes</a></li>
                        <li><a href="request_institute.php">Request Institute/domain</a></li>
                        <li><a href="institute_req_mngmnt.php">Institute Request <br> Management</a></li>
                    
                    </ul>                     

                </li>
              
                <li><a class="has-arrow" href="mail-ipdetails_form.php"><i class="icon-envelope"></i><span>Mail Server & <br> IP Details</span></a>

                    <ul>
                        <li><a href="add_service_provider.php">Add Service Provider</a></li>
                        <li><a href="ip_calculator.php">Add IP Pool</a></li>
                        <li><a href="mail-ipdetails_form.php">Add New Mailserver</a></li>
                        <li><a href="addip-mailserver.php">Add IPs to Exisiting <br>Mailserver</a></li>
                        <li><a href="ip_blacklist_report_new.php">IP Blacklist Report </a></li>

                    </ul>

                </li>

              

                <li><a class="has-arrow" href="reply_to_email_form.php"><i class="icon-reload"></i><span>Reply To Emails <br> Management</span></a>

                    <ul>
                        <li><a href="reply_to_email_form.php">Reply to Emails for <br> Organizational Unit </a></li>
                        <li><a href="reply_to_email_form_users.php">Reply to Emails for <br> Users </a></li>
                        <li><a href="reply_to_email_req_mngmt.php">Reply to Email <br> Requests Management </a></li>
                        <li><a href="reply_to_email_req.php">Request<br> Reply to Emails </a></li>

                    </ul>

                </li>
                <li><a href="dnsbl.php"><i class="icon-globe"></i><span>DNS BL</span></a></li>


                <?php
                if (isset($_SESSION['r_level'])) {
                    if ($_SESSION['r_level'] == "0") { ?>

                        <li><a class="has-arrow" href=""><i class="icon-settings"></i><span>Control Panel</span></a>

                            <ul>
                                <li><a href="system_settings.php"><span> System Settings</span></a></li>
                                <li><a href="org_settings.php"><span> Organizational Settings</span></a></li>

                            </ul>

                        </li>

                <?php
                    }
                }

                ?>




                <li><a href="embargotype.php"><i class="icon-support"></i><span> Campaign Wise Embargo <br> Management</span></a></li>


                <!-- <li><a class="has-arrow" href=""><i class="icon-folder-alt"></i><span>Campaign Type</span></a>

                    <ul>

                        <li><a href="ctype_form.php"><span> Organizational Campaign</span></a></li>

                    </ul>

                </li> -->


                <li><a  class="has-arrow" href=""><i class="icon-plus"></i><span> Campaign Forms</span></a>
            
                <ul>
                                <li><a href="addCampaign.php"><span>Add Campaign</span></a></li>
                                <li><a href="add_product_type.php"><span> Add Product Type</span></a></li>
                                <li><a href="add_product.php"><span> Add Product</span></a></li>
                                <li><a href="ctype_form.php"><span> Add Campaign Type</span></a></li>
                                <li><a href="assign_ptype_ctype.php"><span> Assign Product type <br> to Campaign Type</span></a></li>
                                <li><a href="components_for_campaign_type.php"><span> Assign Components <br> to Campaign Type</span></a></li>

                            </ul>
            
            </li>
                <!-- <li><a href="campaignHistory.php"><i class="icon-clock"></i><span>Campaign History</span></a></li> -->






                <li><a class="has-arrow" href=""><i class="icon-list"></i><span>Unsubscriber <br> Management</span></a>

                    <ul>
                        <?php if (isset($_SESSION['unsubscription_type'])) {
                            if ($_SESSION['unsubscription_type'] == "sys-defined") { ?>

                                <li><a href="unsubscriberList.php"><span> System Unsubscriber</span></a></li>
                        <?php  }
                        } ?>
                        <?php if (isset($_SESSION['unsubscription_type'])) {
                            if ($_SESSION['unsubscription_type'] != "sys-defined") { ?>
                                <li><a href="orgunsubscriberList.php"><span> Organizational <br> Unsubscriber</span></a></li>
                        <?php  }
                        } ?>
                    </ul>

                </li>


                <li><a class="has-arrow" href=""><i class="icon-ban"></i><span>Block Domain <br> Management</span></a>

                    <ul>
                        <?php if (isset($_SESSION['domain_block_type'])) {
                            if ($_SESSION['domain_block_type'] == "sys-defined") { ?>

                                <li><a href="block_domain.php"><span> System Block Domain</span></a></li>
                        <?php }
                        } ?>
                        <?php if (isset($_SESSION['domain_block_type'])) {
                            if ($_SESSION['domain_block_type'] != "sys-defined") { ?>
                                <li><a href="block_domainorg.php"><span> Organizational <br> Block Domain</span></a></li>
                        <?php }
                        } ?>
                    </ul>

                </li>
                <li><a class="has-arrow" href=""><i class="icon-ban"></i><span>Block URL <br> Management</span></a>

                    <ul>
                        <?php if (isset($_SESSION['url_block_type'])) {
                            if ($_SESSION['url_block_type'] == "sys-defined") { ?>
                                <li><a href="block_url.php"><span> System Block URL</span></a></li>
                        <?php  }
                        }    ?>

                        <?php if (isset($_SESSION['url_block_type'])) {
                            if ($_SESSION['url_block_type'] != "sys-defined") { ?>
                                <li><a href="block_urlorg.php"><span> Organizational <br> Block URL</span></a></li>
                        <?php  }
                        }    ?>
                    </ul>

                </li>

                <?php
                if (isset($_SESSION['r_level'])) {
                    if ($_SESSION['r_level'] == "0") { ?>

                        <li><a href="perm_block_url_form.php"><i class="icon-ban"></i><span>Permenantly blocked <br> Domains</span></a></li>

                <?php
                    }
                } ?>



                <!-- <li><a href="campaignHistory.php"><i class="fa fa-history"></i><span> Campaign History</span></a></li> -->








            </ul>
        </nav>
    </div>
</div>