<?php 
// here is the code for banner input buttons
// ob_start();
// session_start();

// error_reporting(0);
// ini_set('display_errors', 0);
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['BINB'])) {
    if ($_SESSION['BINB'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}


?>

<!-- --------------------------------------------
------------------------------------------- -->

<?php if (isset($_SESSION['orgunit_id'])) {
                                                $stmt_org = $conn->prepare("SELECT * FROM tbl_organizational_unit WHERE orgunit_id=:orgunit_id");
                                                $stmt_org->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
                                                $stmt_org->execute();
                                                $org_id = $stmt_org->fetch();
                                            ?>
                                                <button id="add_new_banner" name="add_new_banner" class="btn btn-primary" data-toggle="modal" data-target="#add_new_modal"><i class="fa fa-image"></i> Upload New Banner</button>
                                                <div class="modal fade" id="add_new_modal" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">

                                                                <div class="alert alert-dark" role="alert"> Add New Banner</div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div style="width:800px; margin:0 auto;" class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="demo-masked-input">
                                                                        <form enctype="multipart/form-data" class="custom-validation" action="add_banner_db.php" method="post">

                                                                            <div class="form-group input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text "><span style="font-size:13px;">Upload Banner *</span></span>
                                                                                </div>

                                                                            </div>


                                                                            <div class="card">

                                                                                <div class="body">
                                                                                    <input type="file" name='banner_image' class="dropify" data-allowed-file-extensions="jpg jpeg bmp gif png">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text "><span style="font-size:13px;">Banner Heperlink (Optional) </span></span>
                                                                                </div>
                                                                                <input type="text" name="hyperlink" class="form-control" placeholder="Enter Hyperlink">
                                                                            </div>
                                                                            <input hidden type="text" name="orgunit_id" value="<?php echo  $org_id['orgunit_id']; ?>" class="form-control">
                                                                            <input hidden type="text" id="page_name" name="page_name" class="form-control">

                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                                                                Upload
                                                                            </button>


                                                                        </form>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>

                                            <?php } else { ?>
                                                <button disabled id="add_new_banner" name="add_new_banner" class="btn btn-primary"><i class="fa fa-plus"></i> Upload New Banner</button>
                                            <?php }
                                            if (isset($_SESSION['orgunit_id'])) {
                                            ?>
                                                <button id="add_existing_banner" name="add_existing_banner" class="btn btn-primary" data-toggle="modal" data-target="#add_existing_modal"><i class="fa fa-image"></i> Add Existing Banner</button>
                                                <div class="modal fade" id="add_existing_modal" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg" style="max-width: '100%';min-width: 80%;" role="document">
                                                        <div class="modal-content" style="overflow:scroll;">
                                                            <div class="modal-header">

                                                                <div class="alert alert-dark" role="alert"> Add Existing Banner</div>
                                                            </div>
                                                            <div class="modal-body">



                                                                <div class="col-md-12">
                                                                    <div class="card">
                                                                        <div class="body">
                                                                            <div class="table-responsive">
                                                                                <table class="table m-b-0 table-hover">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Banner</th>
                                                                                            <th>URL</th>
                                                                                            <th>Hyperlink</th>


                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php

                                                                                        $stmt_banner = $conn->prepare("SELECT * FROM banners WHERE orgunit_id=:orgunit_id");
                                                                                        $stmt_banner->bindValue(':orgunit_id', $org_id['orgunit_id']);
                                                                                        $stmt_banner->execute();
                                                                                        while ($row = $stmt_banner->fetch()) {
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <img src="banners/<?php echo $org_id['orgunit_code'] ?>/<?php echo $row['image_name'] ?>" alt="" height=100 width=500> </img>

                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php echo $row['image_url'] ?>
                                                                                                    <input hidden type="text" value=<?php echo $row['image_url'] ?> id="urlid">


                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php echo $row['image_hyperlink'] ?>
                                                                                                    <input hidden type="text" value=<?php echo $row['image_hyperlink'] ?> id="hypid">


                                                                                                </td>

                                                                                            </tr>

                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>





                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>

                                            <?php } else { ?>
                                                <button disabled id="add_existing_banner" name="add_existing_banner" class="btn btn-primary"><i class="fa fa-plus"></i> Add Exisitng Banner</button>
                                            <?php } ?>



<!-- ----------------------------------------------
------------------------------------------ --> </br><br>