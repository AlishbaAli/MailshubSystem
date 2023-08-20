<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
?>
<html lang="en">

<!--head-->

<?php include 'include/head.php'; ?>
<!--head-->

<body class="theme-blue">

    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="assets/images/thumbnail.png" width="48" height="48" alt="Mplify"></div>
            <p>Please wait...</p>
        </div>
    </div> -->
    <!-- Overlay For Sidebars -->
    <div class="overlay" style="display: none;"></div>

    <div id="wrapper">

        <!--nav bar-->
        <?php include 'include/nav_bar.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->


        <!-- <div id="main-content">
        <div class="container-fluid"> -->
        <!-- <div class="block-header">
                <div class="row">
                    <div class="col-lg-5 col-md-8 col-sm-12">
                        <h2>Dashboard</h2>
                    </div>            
                    <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                        <ul class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>                            
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div> -->

        <!---Add code here-->
        <div id="main-content">
            <div class="container-fluid">

                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-5 col-md-8 col-sm-12">
                            <h2>Dashboard</h2>
                        </div>
                        <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                            <ul class="breadcrumb justify-content-end">
                                <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="block-header">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h3>ACTIONS</h3>
                                </div>



                                <?php

                                try {
                                    ////Upload Author Data
                                    if (isset($_POST["UploadAuthor"])) {

                                        //---------------------------------1-------------------------------------------//   
                                       
                                      //Get Org ID
		                                $user_id = $_SESSION["AdminId"];
                                     $orgunit_id = $_SESSION['orgunit_id'];
                                     $CampID = $_POST['CampID'];
                                     $rtemid = $_POST['rtemid'];
		                                $org_id_stmt = $conn->prepare("SELECT orgunit_id FROM tbl_orgunit_user WHERE user_id=:user_id");
		                                $org_id_stmt->bindValue(':user_id', $user_id);
		                                $org_id_stmt->execute();
                                        if($org_id_stmt->rowCount()>0) {
		                                $orgunit = $org_id_stmt->fetch();
		                                $orgunit_id = $orgunit["orgunit_id"];
                                        } else { 
                                            $orgunit_id = $_SESSION['orgunit_id']; 
                                        }
                                        
                                  


                                   
                                     

                                        //blocked domains filter code
                           
		if(	$_SESSION['domain_block_type']=="sys-defined"){
            //blocked domains filter code
            $domain_block = "";
            $domain_clause = "";
            $sql_blk_dmn = "SELECT * FROM `blocked_domains` where domain_status = 'Active'";
            $stmt_blk_dmn = $conn->prepare($sql_blk_dmn);
            $stmt_blk_dmn->execute();
            $data_blk_dmn = $stmt_blk_dmn->fetchAll();
            
            $bd = 0;
            foreach($data_blk_dmn as $bd_ele)
            {
                $bd++;
                if($bd <> 1)
                {
                    $domain_block .= ",";
                }
                $domain_block .= chr(39).$bd_ele['domain_name'].chr(39);
                
            }
            
            $domain_clause = "  AND substring(email, locate(".chr(34)."@".chr(34).",email)+1, length(email)-locate(".chr(34)."@".chr(34).",email)) not in ($domain_block)";
            //blocked domain filtere code



}
if($_SESSION['domain_block_type']=="ou-hybrid" || $_SESSION['domain_block_type']=="ou-dedicated"){
             //blocked domains filter code
             $domain_block = "";
             $domain_clause = "";
             $sql_blk_dmn = "SELECT * FROM `blocked_domain_org` where domain_status = 'Active'";
             $stmt_blk_dmn = $conn->prepare($sql_blk_dmn);
             $stmt_blk_dmn->execute();
             $data_blk_dmn = $stmt_blk_dmn->fetchAll();
             
             $bd = 0;
             foreach($data_blk_dmn as $bd_ele)
             {
               $bd++;
               if($bd <> 1)
               {
                   $domain_block .= ",";
               }
               $domain_block .= chr(39).$bd_ele['domain_name'].chr(39);
               
}

$domain_clause = "  AND substring(email, locate(".chr(34)."@".chr(34).",email)+1, length(email)-locate(".chr(34)."@".chr(34).",email)) not in ($domain_block)";
//blocked domain filtere code

}
                                        //blocked domain filtere code

                                        if ($_POST['format_type'] == "format1") {

                                            $camp_id = $_POST['CampID'];
                                            $time = date('U');
                                            $tablename = " I_" . $camp_id . "_" . $time . "";
                                            $df = $tablename . "_df";
                                            $uf = $tablename . "_uf";
                                  
                                            //die();
                                            $se = "CREATE TABLE IF NOT EXISTS $tablename (
                                          author_id int(11) NOT NULL AUTO_INCREMENT,
                                           CampID int(11) NOT NULL,
                                          rtemid int(11) NOT NULL,
                                          Journal_title varchar(1000) NOT NULL,
                                          Role varchar(255) NOT NULL,
                                          Fname varchar(255) NOT NULL,
                                          Lastname varchar(255) NOT NULL,
                                          affiliation varchar(6000) NOT NULL,
                                          Country varchar(255) NOT NULL,
                                          email varchar(100) NOT NULL,
                                          article_title varchar(5000) NOT NULL,
                                          eurekaselect_url varchar(2000) NOT NULL,
                                          Status varchar(100) NOT NULL,
                                          PRIMARY KEY (author_id)
                                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
                                            $create = $conn->prepare($se);
                                            $createresult = $create->execute();

                                            //---------------------------------end 1-------------------------------------------//


                                            $target_dir = "uploads/";
                                            $target_file = $target_dir . basename($_FILES["file"]["name"]);
                                            $date=date('Ymd');
                                            $filename='U'.$user_id.'-O'.$orgunit_id.'-C'.$CampID.'-'.$date.'.csv';

$log1="INSERT INTO upload_log (`filename`, `CampID`, `AdminID`, `orgunit_id`) 
 VALUES ('$filename','$CampID','$user_id','$orgunit_id') ";
 $log=$conn->prepare($log1);
 $log->execute();
 



                                            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . 'F-'.$filename)) {

                                                // Checking file exists or not
                                                $target_file = $target_dir . 'F-'.$filename;
                                                $fileexists = 0;
                                                if (file_exists($target_file)) {
                                                    $fileexists = 1;
                                                }

                                                if ($fileexists == 1) {
                                                    // Reading file
                                                    $file = fopen($target_file, "r");
                                                    $i = 0;

                                                    $importData_arr = array();

                                                    while (($data = fgetcsv($file, 1000000, ",")) !== FALSE) {
                                                        $num = count($data);

                                                        for ($c = 0; $c < $num; $c++) {
                                                            $importData_arr[$i][] = $data[$c];
                                                        }
                                                        $i++;
                                                    }
                                                    fclose($file);

                                                    $skip = 0;

                                                    // insert import data 
                                                    foreach ($importData_arr as $data) {






                                                        if ($skip != 0) {
                                                            $CampID = $_POST['CampID'];
                                                         
                                                            $Journal_title =  htmlentities(addslashes($data[0]));
                                                            $Role =  htmlentities(addslashes($data[1]));
                                                            $Fname = htmlentities(addslashes($data[2]));
                                                            $Lastname = htmlentities(addslashes($data[3]));
                                                            $affiliation = htmlentities(addslashes($data[4]));
                                                            $Country = htmlentities(addslashes($data[5]));
                                                            $email = htmlentities(addslashes($data[6]));
                                                            $article_title = htmlentities(addslashes($data[7]));
                                                            $eurekaselect_url = htmlentities(addslashes($data[8]));
                                                            $Status = 'Not Sent';

                                                            //---------------2---------------//
                                                            $time_start = microtime(true);
                                                            //----------------end-2-------------//



                                                            //--------------------------------------------------3--------------------------------------------------//
                                                            // Insert record
                                                            $insert_query = "INSERT into $tablename
                                                (   CampID, 
                                                    rtemid,
                                                    Journal_title,
                                                    Role, 
                                                    Fname,
                                                    Lastname,
                                                    affiliation, 
                                                    Country, 
                                                    email, 
                                                    article_title,
                                                    eurekaselect_url,
                                                    Status
                                                ) 
                                        values (
                                                    '" . $camp_id . "', 
                                                     '" . $rtemid . "', 
                                                    trim('" . $Journal_title . "'),
                                                    trim('" . $Role . "'),
                                                    trim('" . $Fname . "'),
                                                    trim('" . $Lastname . "'),
                                                    trim('" . $affiliation . "'),
                                                    trim('" . $Country . "'),
                                                    trim('" . $email . "'),
                                                    trim('" . $article_title . "'),
                                                    trim('" . $eurekaselect_url . "'),
                                                    trim('" . $Status . "')                                     
                                                )";

                                                            $stmt = $conn->prepare($insert_query);
                                                            $result = $stmt->execute();
                                                        }
                                                        $skip++;
                                                    }

                                                    echo "<br/>";

                                                    $select1 = "create TABLE $df
                                            as
                                            SELECT * from $tablename
                                            where author_id in (
                                                select max(author_id) from $tablename
                                                group by email
                                            )";
                                                    $stmt = $conn->prepare($select1);
                                                    $result = $stmt->execute();
                                                    echo "df created";
                                                   
                                                    //die();

                                                    if(	$_SESSION['unsubscription_type']=="sys-defined"){
                                                    $select2 = "create TABLE $uf
                                                    as
                                                    SELECT * FROM $df 
                                                    where email not in (select UnsubscriberEmail FROM unsubscriber WHERE Status='Enabled'
                                                    AND Category <> 'ou-dedicated')".$domain_clause;

                                                    }
                                                    if(	$_SESSION['unsubscription_type']=="ou-dedicated" || $_SESSION['unsubscription_type']=="ou-hybrid"){
                                                    $select2 = "create TABLE $uf
                                                    as
                                                    SELECT * FROM $df 
                                                    where email not in (select UnsubscriberEmail FROM orgunit_unsubscriber)".$domain_clause;
                                                    }
                                           
                                                    $stmt1 = $conn->prepare($select2);
                                                    $result1 = $stmt1->execute();

                                                    $alt = "ALTER TABLE $uf CONVERT TO CHARACTER SET utf8;";
                                                    $stmtt = $conn->prepare($alt);
                                                    $result = $stmtt->execute();

                                                    echo "uf created";
                                                    //die();
                                                    $conn->beginTransaction();




                                                    // ----------------------------------------------------4------------------------------------------------//

                                                    $sql1 = "INSERT INTO
                                            `campaingauthors`(
                                               `CampID`,
                                                `rtemid`,
                                                `Journal_title`,
                                                `Role`,
                                                `Fname`,
                                                `Lastname`,
                                                `affiliation`,
                                                `Country`,
                                                `email`,
                                                `article_title`,
                                                `eurekaselect_url`,
                                                `Status`
                                            )
                                        SELECT
                                            `CampID`,
                                            `rtemid`,
                                            `Journal_title`,
                                            `Role`,
                                            `Fname`,
                                            `Lastname`,
                                            `affiliation`,
                                            `Country`,
                                            `email`,
                                            `article_title`,
                                            `eurekaselect_url`,
                                            `Status`
                                        FROM
                                            $uf
                                        WHERE
                                            `email` NOT IN(
                                            SELECT
                                                email
                                            FROM
                                                campaingauthors
                                            WHERE
                                                    CampID = $camp_id
                                        )
                                ";
                                                    //die();
                                                    $sth = $conn->exec($sql1);

                                                    $conn->commit();


                                                    $CampID = $_POST['CampID'];
                                                    // Update record in activity table
                                                    $sql = "UPDATE `activity` SET `add_authordata` = 1 
                                                    WHERE `CampID` = :CampID";

                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->bindValue(':CampID', $CampID);
                                                    $result = $stmt->execute();
                                                    //---------------------------------5------------------------------------------------//

                                                    $dtsql = "drop table  $tablename";
                                                    $stmt = $conn->prepare($dtsql);
                                                    $stmt->execute();

                                                    $dtsql = "drop table  $df";
                                                    $stmt = $conn->prepare($dtsql);
                                                    $stmt->execute();
                                                    $dtsql = "drop table  $uf";
                                                    $stmt = $conn->prepare($dtsql);
                                                    $stmt->execute();


                                                    //-----------------------------end-5------------------------------------------------//



                                                    echo "<script type=\"text/javascript\">
                                    alert(\"CSV File has been successfully Imported.\");
                                    window.location = \"index.php\"
                                </script>";

                                                    //------------------6----------------------//
                                                    //die();
                                                    $time_end = microtime(true);
                                                    $time = $time_end - $time_start;
                                                    $time = round($time, 2);

                                                    //---------------END-6--------------------//


                                                    $newtargetfile = $target_file;
                                                    if (file_exists($newtargetfile)) {
                                                      //  unlink($newtargetfile);
                                                    }
                                                }
                                            }
                                        }


                                        //format2

                                        if ($_POST['format_type'] == "format2") {

                                            $camp_id = $_POST['CampID'];
                                            $time = date('U');
                                            $tablename = " I_" . $camp_id . "_" . $time . "";
                                            $df = $tablename . "_df";
                                            $uf = $tablename . "_uf";
                                            //die();
                                            $se = "CREATE TABLE IF NOT EXISTS $tablename (
                                             author_id int(11) NOT NULL AUTO_INCREMENT,
                                              CampID int(11) NOT NULL,
                                             rtemid int(11) NOT NULL,
                                             Initials varchar(50) NOT NULL,
                                             Fname varchar(255) NOT NULL,
                                             Lastname varchar(255) NOT NULL,
                                             Add1 varchar(500) NOT NULL,
                                             Add2 varchar(500) NOT NULL,
                                             Add3 varchar(500) NOT NULL,
                                             Add4 varchar(500) NOT NULL,         
                                             Country varchar(255) NOT NULL,
                                             email varchar(100) NOT NULL,
                                             Status varchar(100) NOT NULL,
                                             PRIMARY KEY (author_id)
                                           ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
                                            $create = $conn->prepare($se);
                                            $createresult = $create->execute();

                                            //---------------------------------end 1-------------------------------------------//


                                            $target_dir = "uploads/";
                                            $target_file = $target_dir . basename($_FILES["file"]["name"]);
                                            $date=date('Ymd');
                                            $filename='U'.$user_id.'-O'.$orgunit_id.'-C'.$CampID.'-'.$date.'.csv';

                                            $log1="INSERT INTO upload_log (`filename`, `CampID`, `AdminID`, `orgunit_id`) 
                                            VALUES ('$filename','$CampID','$user_id','$orgunit_id') ";
                                            $log=$conn->prepare($log1);
                                            $log->execute();
                                            
                                            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . 'F-'.$filename)) {

                                                // Checking file exists or not
                                                $target_file = $target_dir . 'F-'.$filename;
                                                $fileexists = 0;
                                                if (file_exists($target_file)) {
                                                    $fileexists = 1;
                                                }

                                                if ($fileexists == 1) {
                                                    // Reading file
                                                    $file = fopen($target_file, "r");
                                                    $i = 0;

                                                    $importData_arr = array();

                                                    while (($data = fgetcsv($file, 1000000, ",")) !== FALSE) {
                                                        $num = count($data);

                                                        for ($c = 0; $c < $num; $c++) {
                                                            $importData_arr[$i][] = $data[$c];
                                                        }
                                                        $i++;
                                                    }
                                                    fclose($file);

                                                    $skip = 0;

                                                    // insert import data 
                                                    foreach ($importData_arr as $data) {






                                                        if ($skip != 0) {
                                                            $CampID = $_POST['CampID'];
                                                           
                                                            $initials =  htmlentities(addslashes($data[0]));
                                                            $Fname =  htmlentities(addslashes($data[1]));
                                                            $Lname = htmlentities(addslashes($data[2]));
                                                            $Add1 = htmlentities(addslashes($data[3]));
                                                            $Add2 = htmlentities(addslashes($data[4]));
                                                            $Add3 = htmlentities(addslashes($data[5]));
                                                            $Add4 = htmlentities(addslashes($data[6]));
                                                            $Country = htmlentities(addslashes($data[7]));
                                                            $email = htmlentities(addslashes($data[8]));
                                                            $Status = 'Not Sent';

                                                            //---------------2---------------//
                                                            $time_start = microtime(true);
                                                            //----------------end-2-------------//



                                                            //--------------------------------------------------3--------------------------------------------------//
                                                            // Insert record
                                                            $insert_query = "INSERT into $tablename
                                                   (    CampID, 
                                                     rtemid,
                                                       Initials,
                                                       Fname,
                                                       Lastname,
                                                      Add1,
                                                      Add2,
                                                      Add3,
                                                      Add4, 
                                                       Country, 
                                                       email, 
                                                       Status
                                                   ) 
                                           values (
                                                       '" . $camp_id . "', 
                                                       '" . $rtemid . "', 
                                            
                                                       trim('" . $initials . "'),
                                                       trim('" . $Fname . "'),
                                                       trim('" . $Lname . "'),
                                                       trim('" . $Add1 . "'),
                                                       trim('" . $Add2 . "'),
                                                       trim('" . $Add3 . "'),
                                                       trim('" . $Add4 . "'),
                                                       trim('" . $Country . "'),
                                                       trim('" . $email . "'),
                                                       trim('" . $Status . "')                                      
                                                   )";

                                                            $stmt = $conn->prepare($insert_query);
                                                            $result = $stmt->execute();
                                                        }
                                                        $skip++;
                                                    }

                                                    echo "<br/>";

                                                    $select1 = "create TABLE $df
                                               as
                                               SELECT * from $tablename
                                               where author_id in (
                                                   select max(author_id) from $tablename
                                                   group by email
                                               )";
                                                    $stmt = $conn->prepare($select1);
                                                    $result = $stmt->execute();
                                                    echo "df created";
                                                    //die();
                                                    if(	$_SESSION['unsubscription_type']=="sys-defined"){
                                                        $select2 = "create TABLE $uf
                                                        as
                                                        SELECT * FROM $df 
                                                        where email not in (select UnsubscriberEmail FROM unsubscriber WHERE Status='Enabled'
                                                        AND Category!='ou-dedicated')".$domain_clause;
    
                                                        }
                                                        if(	$_SESSION['unsubscription_type']=="ou-dedicated" || $_SESSION['unsubscription_type']=="ou-hybrid"){
                                                        $select2 = "create TABLE $uf
                                                        as
                                                        SELECT * FROM $df 
                                                        where email not in (select UnsubscriberEmail FROM orgunit_unsubscriber)".$domain_clause;
                                                        }
                                               
                                                        $stmt1 = $conn->prepare($select2);
                                                        $result1 = $stmt1->execute();
    
                                                        $alt = "ALTER TABLE $uf CONVERT TO CHARACTER SET utf8;";
                                                        $stmtt = $conn->prepare($alt);
                                                        $result = $stmtt->execute();
                                                    echo "uf created";
                                                    //die();
                                                    $conn->beginTransaction();




                                                    // ----------------------------------------------------4------------------------------------------------//

                                                    $sql1 = "INSERT INTO
                                               `campaingauthors`(
                                                  `CampID`,
                                                  `rtemid`,
                                                   `Initials`,
                                                   `Fname`,
                                                   `Lastname`,
                                                   `Add1`,
                                                   `Add2`,
                                                   `Add3`,
                                                   `Add4`,
                                                   `Country`,
                                                   `email`,
                                                   `Status`
                                               )
                                           SELECT
                                               `CampID`,
                                              `rtemid`,
                                               `Initials`,
                                               `Fname`,
                                               `Lastname`,
                                               `Add1`,
                                               `Add2`,
                                               `Add3`,
                                               `Add4`,
                                               `Country`,
                                               `email`,
                                               `Status`
                                           FROM
                                               $uf
                                           WHERE
                                               `email` NOT IN(
                                               SELECT
                                                   email
                                               FROM
                                                   campaingauthors
                                               WHERE
                                                       CampID = $camp_id
                                           )
                                   ";
                                                    //die();
                                                    $sth = $conn->exec($sql1);

                                                    $conn->commit();


                                                    $CampID = $_POST['CampID'];
                                                    // Update record in activity table
                                                    $sql = "UPDATE `activity` SET `add_authordata` = 1 
                                       WHERE `CampID` = :CampID";

                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->bindValue(':CampID', $CampID);
                                                    $result = $stmt->execute();
                                                    //---------------------------------5------------------------------------------------//

                                                    $dtsql = "drop table  $tablename";
                                                    $stmt = $conn->prepare($dtsql);
                                                    $stmt->execute();

                                                    $dtsql = "drop table  $df";
                                                    $stmt = $conn->prepare($dtsql);
                                                    $stmt->execute();
                                                    $dtsql = "drop table  $uf";
                                                    $stmt = $conn->prepare($dtsql);
                                                    $stmt->execute();


                                                    //-----------------------------end-5------------------------------------------------//



                                                    echo "<script type=\"text/javascript\">
                                       alert(\"CSV File has been successfully Imported.\");
                                       window.location = \"viewAuthor.php?CampID=" . $_POST['CampID'] . "\"
                                   </script>";

                                                    //------------------6----------------------//
                                                    //die();
                                                    $time_end = microtime(true);
                                                    $time = $time_end - $time_start;
                                                    $time = round($time, 2);

                                                    //---------------END-6--------------------//


                                                    $newtargetfile = $target_file;
                                                    if (file_exists($newtargetfile)) {
                                                       // unlink($newtargetfile);
                                                    }
                                                }
                                            }
                                        }
                                    }







                                    // ------------------------------------------------ [ Draft Area ] ----------------------------------------------------- //

                                    //Upload Draft Letter Data
//                                     if (isset($_POST["submitDraft"])) {
//                                         $CampID = $_POST['CampID'];
//                                         $sql = "SELECT COUNT(CampID) AS numm 
//                         FROM draft 
//                         WHERE CampID = :CampID";

//                                         $stmt = $conn->prepare($sql);

//                                         //Bind the provided username to our prepared statement.
//                                         $stmt->bindValue(':CampID', $CampID);

//                                         //Execute.
//                                         $stmt->execute();

//                                         //Fetch the row.
//                                         $row = $stmt->fetch(PDO::FETCH_ASSOC);

//                                         //If the provided CampName already exists - display error.
//                                         if ($row['numm'] > 0) {
//                                             echo ("<br/><br/><div class='alert alert-danger' role='alert'><strong>Templete Draft already exists!</strong></div>");
//                                             echo   "<div class='element-box-content'>
//                                 <a href='viewDraft.php?CampID=" . $_POST['CampID'] . "'>
//                                 <button class='mr-2 mb-2 btn btn-primary btn-md' type='button'>Please Check Your Draft!</button></a>
//                             </div>";
//                                             die();
//                                         }

//                                         $subscription_draft = !empty($_POST['subscription_draft']) ? trim($_POST['subscription_draft']) : null;



//                                         $templete_created_date = !empty($_POST['templete_created_date']) ? trim($_POST['templete_created_date']) : null;





//                                         $subscription_draft = !empty($_POST['subscription_draft']) ? trim($_POST['subscription_draft']) : null;
//                                         $templete_created_date = !empty($_POST['templete_created_date']) ? trim($_POST['templete_created_date']) : null;
//                                                                                   //url_block_type = "sys-defined"
//   if($_SESSION['url_block_type']=="sys-defined"){
//     $stmt_url=$conn->prepare("SELECT url FROM blocked_url WHERE status='Active'");
//     $stmt_url->execute();
//     $urls=$stmt_url->fetchAll(); 




// }
// if($_SESSION['url_block_type']=="ou-dedicated" || $_SESSION['url_block_type']=="ou-hybrid"){
//     $stmt_url=$conn->prepare("SELECT url FROM blocked_url_org WHERE status='Active'");
//     $stmt_url->execute();
//     $urls=$stmt_url->fetchAll(); 



// }
// $flag=0;

    

// foreach ($urls as $url) {
    
//     if (stripos($subscription_draft,$url['url'])==true) {
//         //url found in draft
//         $flag=1;
//         break;
//     }
// }

//                                         if ($flag==0) {


//                                             $insert_draft_query = "INSERT into draft 
//                                         (   
//                                             subscription_draft, 
//                                             CampID, 
//                                             templete_created_date 
//                                             -- AdminID
                                        
//                                         ) 
//                                 values (
//                                             '" . htmlentities(addslashes($subscription_draft)) . "',
//                                             '" . $CampID . "', 
//                                             NOW()
                                                
//                                         )";


//                                             $stmtt = $conn->prepare($insert_draft_query);
//                                             $result1 = $stmtt->execute();

//                                             if ($result1 == true) {
//                                                 $CampID = $_POST['CampID'];
//                                                 // Update record in activity table
//                                                 $sql = "UPDATE `activity` SET `add_draft_activity` = 1 
//                             WHERE `CampID` = :CampID";

//                                                 $stmt = $conn->prepare($sql);
//                                                 $stmt->bindValue(':CampID', $CampID);
//                                                 $result = $stmt->execute();


//                                                 $CampID = $_POST['CampID'];
//                                                 // Update record in Campaign table
//                                                 $sql = "UPDATE `campaign` SET `draft_status` = 'subscriptionDraft' 
//                             WHERE `CampID` = :CampID";

//                                                 $stmt = $conn->prepare($sql);
//                                                 $stmt->bindValue(':CampID', $CampID);
//                                                 $result = $stmt->execute();


//                                                 echo "<br/><br/><div class='alert alert-success'><strong>New Draft Successfuly Added</strong>
//                         </div><meta http-equiv='refresh' content='1;url=index.php>";
//                                             }
//                                         } else {

//                                             echo "<div class='alert alert-danger' role='alert'>  
//                                              Yout draft has blocked URLs which are not allowed to use.
//                                             Please remove them and try again.
//              </div>
//                     <a href='addDraft.php?CampID=" . $_POST['CampID'] . "'>
//                 <button class='mr-2 mb-2 btn btn-info btn-md' style='float:right;' type='button'>Try Again</button></a>";
//                                             die();
//                                         }
//                                     }

////////////////////draft code ended///////////////////////sssss
                                }
                                //catch exception
                                catch (Exception $e) {
                                    echo 'Message: ' . $e->getMessage();
                                }


                                ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->

        <!---Add code here-->



        <!--      
        </div>
    </div> -->

    </div>

    <!-- Javascript -->
    <!-- Javascript -->


    <script src="assets/vendor/ckeditor/ckeditor.js"></script> <!-- Ckeditor -->
    <script src="assets/js/pages/forms/editors.js"></script>


    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/bundles/chartist.bundle.js"></script>
    <script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
    <script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
    <script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/index.js"></script>



    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="index.js"></script>

<!-- Session timeout js -->
<script>
    $(document).ready(function() {
        $.sessionTimeout({
            keepAliveUrl: "pages-starter.html",
            logoutUrl: "logout.php",
            redirUrl: "logout.php",
            warnAfter: <?php echo $_SESSION['timeout']; ?>,
            redirAfter: <?php echo $_SESSION['timeout'] + 15000; ?>,
            countdownMessage: "Redirecting in {timer} seconds."
        });
    });
</script>
</body>

</html>