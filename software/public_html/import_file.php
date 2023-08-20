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
if (isset($_SESSION['ADAU'])) {
	if ($_SESSION['ADAU'] == "NO") {

		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
}
?>
<html lang="en">

<!--head-->

<?php include 'include/head.php'; ?>
<!--head-->

<body class="theme-blue">

    <div class="overlay" style="display: none;"></div>

    <div id="wrapper">

        <!--nav bar-->
        <?php include 'include/nav_bar.php'; ?>

        <!--nav bar-->

        <!-- left side bar-->
        <?php include 'include/left_side_bar.php'; ?>


        <!-- left side bar-->


    

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
                                if (isset($_POST["UploadAuthor"]))                                 
                                {
                                                   
		                            $user_id = $_SESSION["AdminId"];
                                    $orgunit_id = $_POST['orgunit_id'];
                                    $CampID = $_POST['CampID'];
                                    $rtemid = $_POST['rtemid'];
                                    $ctype_id = $_POST['ctype_id'];
                                  


                                  

                                    $stmt_camp= $conn->prepare("SELECT domain FROM org_institute_maildomain 
                                    WHERE ou_inst_id=(SELECT ou_inst_id FROM campaign_institutes WHERE CampID='$CampID') AND domain IS NOT NULL AND domain!=''");
                                    $stmt_camp->execute();
                                    $domains= $stmt_camp->fetchAll();
                                    $institutional_domain_block="";
                                    if($stmt_camp->rowCount()>0 && $ctype_id==18)
                                    {
                                    //$domain= $domains['domain'];
                                    //$explode_domains= explode(",",$domain); 
                                    
                                    $bd = 0;
                                    foreach($domains as $exp_dom)
                                    {
                                    	$bd++;
                                    	if($bd <> 1)
                                    	{
                                    		$institutional_domain_block .= ",";
                                    	}
                                    	$institutional_domain_block .= chr(39).$exp_dom['domain'].chr(39);
                                    	
                                    }

                                    $institutional_domain_clause= "AND substring(email, locate(".chr(34)."@".chr(34).",email)+1, length(email)-locate(".chr(34)."@".chr(34).",email)) 
                                    IN ($institutional_domain_block)";
                                    }

                           
   
                                    if (isset($_SESSION['orgunit_id'])) 
                                    {
                                        $stmts2 = $conn->prepare("SELECT restrict_upload_data_org_level FROM `orgunit-systemsetting` WHERE status='Active' AND
                                        orgunit_id=:orgunit_id");
                                        $stmts2->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
                                        $stmts2->execute();
                                        $restrict_upload_data_org_level= $stmts2->fetch();
   
                                    }

                                  
   
                                           //blocked domains filter code
                              
                                    if(	$_SESSION['domain_block_type']=="sys-defined")
                                    {
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
                                        $domain_clause_in = " AND substring(email, locate(".chr(34)."@".chr(34).",email)+1, length(email)-locate(".chr(34)."@".chr(34).",email)) in ($domain_block)";
                                        //blocked domain count code
                                    }
                                    if($_SESSION['domain_block_type']=="ou-hybrid" || $_SESSION['domain_block_type']=="ou-dedicated")
                                    {
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
                                        
                                        $domain_clause_in = "  AND substring(email, locate(".chr(34)."@".chr(34).",email)+1, length(email)-locate(".chr(34)."@".chr(34).",email)) in ($domain_block)";
                                        //blocked domain count code
   
                                    }
                                    $camp_id = $_POST['CampID'];
                                    $time = date('U');
                                    $tablename = " I_" . $camp_id . "_" . $time . "";
                                    $df = $tablename . "_df";
                                    $uf = $tablename . "_uf";
                                    if ($_POST['format_type'] == "format1") 
                                    {
                                        $se="CREATE TABLE IF NOT EXISTS $tablename (
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

                                    }
                                    if($_POST['format_type'] == "format2")
                                    { 
                                        $se="CREATE TABLE IF NOT EXISTS $tablename (
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

                                    }
                                    if($_POST['format_type'] == "data_scopus" || ($_POST['format_type'] == "data_wos"))
                                    { 
                                        $se ="CREATE TABLE IF NOT EXISTS $tablename (
                                         author_id int(11) NOT NULL AUTO_INCREMENT,
                                             CampID int(11) NOT NULL,
                                            rtemid int(11) NOT NULL,
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



                                    }
                                  

                                    $create = $conn->prepare($se);
                                    $createresult = $create->execute();
                                    $insert_query="";
                                    $target_dir = "uploads/";
                                    $target_file = $target_dir . basename($_FILES["file"]["name"]);
                                    $date=date('Ymd');
                                    if($_POST['format_type']=='data_wos'){
                                        $filename='U'.$user_id.'-O'.$orgunit_id.'-C'.$CampID.'-'.$date.'.txt';
                                    }
                                    else
                                    {
                                    $filename='U'.$user_id.'-O'.$orgunit_id.'-C'.$CampID.'-'.$date.'.csv';
                                    }

                                    $log1="INSERT INTO upload_log (`filename`, `CampID`, `AdminID`, `orgunit_id`) 
                                    VALUES ('$filename','$CampID','$user_id','$orgunit_id') ";
                                    $log=$conn->prepare($log1);
                                    $log->execute();
                                    $log_id= $conn->lastInsertId();

                                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . 'F-'.$filename))
                                    {
                                        // Checking file exists or not
                                        $target_file = $target_dir . 'F-'.$filename;
                                        $fileexists = 0;
                                        if (file_exists($target_file)) 
                                        {
                                            $fileexists = 1;
                                        }

                                        if ($fileexists == 1)
                                        {
                                            // Reading file
                                            $file = fopen($target_file, "r");
                                            $i = 0;
                                            $importData_arr = array();
                                          
				                            $importAuthor_arr = array();
                                            $importAuthorId_arr = array();
                                          
                                            if($_POST['format_type'] != "data_wos")
                                            {
                                               
                                                
    
                                                while (($data = fgetcsv($file, 1000000, ",")) !== FALSE) 
                                                {
                                                    if($_POST['format_type'] == "data_scopus")
                                                    {
                                                        $importData_arr[$i] = $data[19];
    
                                                    }

                                                   
                                                    if($_POST['format_type'] == "format1" || $_POST['format_type'] == "format2")
                                                    {
                                                
                                                        $num = count($data);
                                                     
    
                                                        for ($c = 0; $c < $num; $c++)
                                                        {
                                                            $importData_arr[$i][] = $data[$c];
                                                          
                                                        }
                                                     
                                                    }
                                                    $i++;
                                                }
                                                fclose($file);
                                            }
                                           

                                           
                                            if($_POST['format_type'] == "data_wos")
                                            {
                                                $Status="Not Sent";
                                                while (!feof($file) )
                                                {
                                                    $ADD4='';
                                        
                                                        $line = fgets($file);
                                                        $c = fgetc($file);
                                                    if($c === false) break;
                                                        $carry=explode("\t",$line);                                                  
                                                        list($PT, $AU, $BA, $BE, $GP, $AF, $BF, $CA, $TI, $SO, $SE, $BS,
                                                        $LA, $DT, $CT, $CY, $CL, $SP, $HO, $DE, $ID, $AB, $C1, $C3,
                                                        $RP, $EM, $RI, $OI, $FU, $FX, $CR, $NR, $TC, $Z9, $U1,
                                                        $U2, $PU, $PI, $PA, $SN, $EI, $BN, $J9, $JI, $PD, $PY,
                                                        $VL, $IS, $PN, $SU, $SI, $MA, $BP, $EP, $AR, $DI, $D2,
                                                        $EA, $PG, $WC, $WE, $SC, $GA, $UT, $PM, $OA, $HC, $HP, $DA )=$carry;

                                                        
                                                        $author=explode(";",$RP);
                                                       // var_dump($author);

                                                       
                                                    $email=explode(";",$EM);

                                                
                    
                                                    if($i!=0)
                                                    {
                                                        if(strlen($EM)>1 && strlen($RP)>1)
                                                        {
                                                            
                                                            $data=explode(",",$author[0]);
                                                                 $pos=count($data)-1;
                                                                  $a=strtoupper($data[$pos]);
                                                                  $a= explode(".",$a);
                                                                  $country=strtoupper($a[0]);
                                                        $countrya = trim(str_replace(chr(0),"",$country));
                                                                
                                                    // if (strpos(strtoupper($countrya), 'USA')!==false)
                                                    //                {
                                                    //                    $countrya='USA';
                                                            
                                                    //                }
                                                    // else if (strpos(strtoupper($countrya), 'UNITED STATES')!==false)
                                                    //                {
                                                    //                    $countrya='UNITED STATES';
                                                            
                                                    //                }
                                                    //  else if (strpos(strtoupper($countrya), 'CHINA')!==false)
                                                    //                {
                                                    //                    $countrya='CHINA';
                                                                       
                                                    //                }
                                                                 $EMAIL = strtolower(addslashes(str_replace(chr(0),"",$email[0])));
                                                            
                                                                 for($k=0;$k<count($data);$k++)
                                                                 {
                                                                     if($k==0){
                                                                        $FNAME=addslashes((trim(str_replace(chr(0),"",$data[0]))));
                                                                             $FNAME =str_replace('(reprint author)',"",$FNAME);
                                                                             $FNAME =str_replace('"',"",$FNAME);
                                                                        
                                                                     }
                                                                      else if($k==1){
                                                                         $lname=explode("(",$data[1]);
                                                                         $LNAME=addslashes(trim(str_replace(chr(0),"",$lname[0])));
                                                                         $LNAME =str_replace('(reprint author)',"",$LNAME);
                                                                         $LNAME =str_replace('"',"",$LNAME);
                                                                    
                                                                     }
                                                                     else if($k==2){
                                                                         $ADD1=addslashes(trim(str_replace(chr(0),"",$data[2])));
                                                                        
                                                                     }
                                                                      else if($k==3){
                                                                         $ADD2=addslashes(trim(str_replace(chr(0),"",$data[3])));
                                                                        
                                                                     }
                                                                     else if($k==4){
                                                                         $ADD3=addslashes(trim(str_replace(chr(0),"",$data[4])));
                                                                        
                                                                     }
                                                                      else if($k>4 && $k<count($data)-1){
                                                                         $ADD4.=addslashes(trim(str_replace(chr(0),"",$data[$k])));
                                                                        if($k!=count($data)-2){
                                                                         $ADD4.= ", ";
                                                                        }
                                                                         $ADD4;
                                                                        
                                                                     }
                                                                     
                                                                 }
                                                                
                                                                
                                                                 
                                                            $insert_query.="INSERT INTO $tablename(
                                                                        CampID, 
                                                                        rtemid,
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
                                                                        VALUES(
                                                                        '" . $camp_id . "', 
                                                                        '" . $rtemid . "', 
                                                                        trim('".$FNAME."'),
                                                                        trim('".$LNAME."'),
                                                                        trim('".$ADD1."'),
                                                                        trim('".$ADD2."'),
                                                                        trim('".$ADD3."'),
                                                                        trim('".$ADD4."'),
                                                                        trim('".$countrya."'),
                                                                        trim('".$EMAIL."'),
                                                                        trim('" .$Status . "')
                                                                         ); ";
                                                            $countrya="";
                                                            $ADD1="";
                                                            $ADD2="";
                                                            $ADD3="";
                                                            $ADD4="";
                                                            $FNAME="";
                                                            $LNAME="";
                    
                                                        }
                                                    }
                                                        $i++;		
                                                }


                                                
                                            }
                                            $skip = 0;
                                            $j=0;
                                            $k=0;
                                         
                                            // insert import data 
                                            foreach ($importData_arr as $data) 
                                            {
                                                if($_POST['format_type'] == "format1")
                                                {
                                                    if ($skip != 0) 
                                                    {
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
                                                        // Insert record
                                                        $insert_query.= "INSERT into $tablename
                                                        (
                                                            CampID, 
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
                                                        VALUES
                                                        (
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
                                                        ); ";
    
                                                       
                                                    }
                                                    $skip++;
                                                }//format1
                                                if($_POST['format_type'] == "format2")
                                                {
                                                    if ($skip != 0) 
                                                    {
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
                                                        // Insert record
                                                        $insert_query.=" INSERT into $tablename
                                                        (    
                                                            CampID, 
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
                                                        values 
                                                        (
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
                                                        ); ";

                                                       
                                                    }
                                                    $skip++;
                                                } //format2

                                                if($_POST['format_type'] == "data_scopus")
                                                {
                                                    $Status="Not Sent";
                                                    if($skip!=0)
					                                {
						                                $correspondence = addslashes($data);
						                                $corrdata=explode(";",$correspondence);
						                                $pos1=count($corrdata)-1;
						                                if (strpos($corrdata[$pos1], ' email: ') !== false) 
						                                {
							                                $email=explode(":",$corrdata[$pos1]);
							                                $EMAIL=strtolower(addslashes(str_replace(chr(0),"",$email[1])));

							                                if($pos1>1)
							                                {
								                                $name=explode(",",$corrdata[0]);
								                                $FNAME=$name[0];
								                                if(!empty($name[1]))
								                                {
								                                	$lnamedot=$name[1];
								                                	$lnamepos=explode(".", $lnamedot);
								                                	$LNAME=$lnamepos[0];
								                                }
								                                else
								                                {
								                                	$LNAME="";
								                                }
								                        
								                                $Authors_arr=explode(",",$importAuthor_arr[$j]);
								                                $AuthorsId_arr=explode(";",$importAuthorId_arr[$j]);
								                                $author_counter=0;
                        
								                                foreach($Authors_arr as $author_record)
								                                {
								                        	        $authorname_parts = explode(" ",$author_record);
								                        	        $author_firstpart = $authorname_parts[0];
                        
								                        	        if($author_firstpart == $FNAME)
								                        	        {
								                        	        	$Author_ID = $AuthorsId_arr[$author_counter];
								                        	        }
								                        	        $author_counter++;
								                                }
                        
								                                $Address=explode(",", $corrdata[1]);
								                                $countadd=count($Address)-1;
								                                
								                                $country1=trim(str_replace(chr(0),"",$Address[$countadd]));
								                                if (strlen($country1)>230)
								                                {
									                                $country1 = substr($country1, 0, 230);
								                                }
								                                $country1 = addslashes($country1);
								
								
								                                for($k=0;$k<$countadd;$k++)
								                                {
								                                	if($k==0 && $Address[0]!==$country1)
								                                	{
								                                		$ADD1=trim(str_replace(chr(0),"",$Address[0]));
								                                		if (strlen($ADD1)>230)
								                                		{
								                                			$ADD1 = substr($ADD1, 0, 230);
								                                		}
								                                		$ADD1=addslashes($ADD1);
								                                	}
								                                	else if($k==1 && $Address[1]!==$country1)
								                                	{
								                                		$ADD2=trim(str_replace(chr(0),"",$Address[1]));
								                                		if (strlen($ADD2)>230)
								                                		{
								                                			$ADD2 = substr($ADD2, 0, 230);
								                                		}
								                                		$ADD2=addslashes($ADD2);
								                                	}
								                                	else if($k==2 && $Address[2]!==$country1)
								                                	{
								                                		$ADD3=trim(str_replace(chr(0),"",$Address[2]));
								                                		if (strlen($ADD3)>230)
								                                		{
								                                			$ADD3 = substr($ADD3, 0, 230);
								                                		}
								                                		$ADD3=addslashes($ADD3);
								                                	}
								                                	else if($k==3 && $Address[3]!==$country1)
								                                	{
								                                		$ADD4=trim(str_replace(chr(0),"",$Address[3]));
								                                		if (strlen($ADD4)>230)
								                                		{
								                                			$ADD4 = substr($ADD4, 0, 230);
								                                		}
								                                		$ADD4=addslashes($ADD4);
								                                	}
								                                }


						
								                                $countrya=strtoupper($country1);
								                                if($EMAIL !== "")
								                                {
								                                	$insert_query.=" INSERT INTO $tablename(
								                                		CampID, 
                                                                        rtemid,
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
								                                		VALUES(
                                                                        trim('".$camp_id."'),
                                                                         trim('".$rtemid."'),
								                                		trim('".$FNAME."'),
								                                		trim('".$LNAME."'),
								                                		trim('".$ADD1."'),
								                                		trim('".$ADD2."'),
								                                		trim('".$ADD3."'),
								                                		trim('".$ADD4."'),
								                                		trim('".$countrya."'),
								                                		trim('".$EMAIL."'),
                                                                        trim('".$Status."')
								                                		); ";
								                                }
								                                
												

								                                $country1="";
								                                $countrya="";
								                                $ADD1="";
								                                $ADD2="";
								                                $ADD3="";
								                                $ADD4="";
								                                $FNAME="";
								                                $LNAME="";
								                                $EMAIL="";

							                                }
					
							
									
					                                	}
						


					                                }
					                                $j++;
					                                $skip++;
                                                  
                                                }

                                            } //foreach end

                                            if(!empty($insert_query))
                                            {
                                                $conn->beginTransaction();
                                                $sth = $conn->exec($insert_query);
                                                $conn->commit();
                                                $insert_query="";
                                            }
                                            echo "<br/>";

                                           

                                            if (isset($_SESSION['orgunit_id']) AND $restrict_upload_data_org_level['restrict_upload_data_org_level']=='YES' )
                                            {
                                                $orgunit_id=$_SESSION['orgunit_id'];
                                                $select1 = "create TABLE $df
                                                as
                                                SELECT * from $tablename
                                                where email NOT IN (
                                                SELECT email FROM $tablename GROUP BY email HAVING COUNT(*) > 1) AND email NOT IN
                                                (SELECT email FROM campaingauthors INNER JOIN campaign INNER JOIN tbl_orgunit_user ON 
                                                campaign.CampID=campaingauthors.CampID  AND tbl_orgunit_user.ou_id= campaign.ou_id
                                                 WHERE orgunit_id='$orgunit_id')";
                                            } 
                                            else
                                            {
                                            $select1 = "create TABLE $df
                                            as
                                            SELECT * from $tablename
                                            where email NOT IN (
                                            SELECT email FROM $tablename GROUP BY email HAVING COUNT(*) > 1)";
                                            }
                                            $stmt = $conn->prepare($select1);
                                            $result = $stmt->execute();
                                           // echo "df created";
                                           
                                            //die();
                                            $bl_count=0;
                                            $unsub_count=0;
                                            $unsub_bl_count =0;

                                            if(	$_SESSION['unsubscription_type']=="sys-defined")
                                            {

                                            $select2 = "create TABLE $uf
                                            as
                                            SELECT * FROM $df 
                                            where email not in (select UnsubscriberEmail FROM unsubscriber WHERE Status='Enabled'
                                            AND Category <> 'ou-dedicated')".$domain_clause.$institutional_domain_clause;
                                     
                                            
// to count blocked domain authors
                                            $bl_count="SELECT count(email) as count1 FROM $df 
                                            where email not in (select UnsubscriberEmail FROM unsubscriber WHERE Status='Enabled'
                                            AND Category <> 'ou-dedicated')".$domain_clause_in.$institutional_domain_clause; 
                                              

// to count unsubscriber                                                   
                                            $unsub_count="SELECT count(email) as count1 FROM $df 
                                            where email in (select UnsubscriberEmail FROM unsubscriber WHERE Status='Enabled'
                                            AND Category <> 'ou-dedicated')".$domain_clause.$institutional_domain_clause; 
                                              

// authors who are unsub as well as have blocked domain
                                            $unsub_bl="SELECT count(email) as count1 FROM $df 
                                            where email in (select UnsubscriberEmail FROM unsubscriber WHERE Status='Enabled'
                                            AND Category <> 'ou-dedicated')".$domain_clause_in.$institutional_domain_clause; 

                                            }
                                            if(	$_SESSION['unsubscription_type']=="ou-dedicated" || $_SESSION['unsubscription_type']=="ou-hybrid")
                                            {
                                            $select2 = "create TABLE $uf
                                            as
                                            SELECT * FROM $df 
                                            where email not in (select UnsubscriberEmail FROM orgunit_unsubscriber)".$domain_clause.$institutional_domain_clause;
                                           
// to count blocked domain authors
                                            $bl_count="SELECT count(email) as count1 FROM $df 
                                            where email not in (select UnsubscriberEmail FROM orgunit_unsubscriber)".$domain_clause_in.$institutional_domain_clause; 

// to count unsubscriber                                                   
                                            $unsub_count="SELECT count(email) as count1 FROM $df 
                                            where email in (select UnsubscriberEmail FROM orgunit_unsubscriber)".$domain_clause.$institutional_domain_clause; 

// authors who are unsub as well as have blocked domain
                                            $unsub_bl="SELECT count(email) as count1 FROM $df 
                                            where email in (select UnsubscriberEmail FROM orgunit_unsubscriber)".$domain_clause_in.$institutional_domain_clause; 

                                            }
                                          
                                            $stmt1 = $conn->prepare($select2);
                                            $result1 = $stmt1->execute();
                                          
                                            $stmt_bl = $conn->prepare($bl_count);
                                            $stmt_bl->execute();
                                            $result_bl = $stmt_bl->fetch();
                                            if($stmt_bl->rowCount()>0)
                                            {                                              
                                            $bl_count= $result_bl["count1"];                                       
                                            }
                                           // echo "bl".$bl_count;

                                            $stmt_unsub = $conn->prepare($unsub_count);
                                            $stmt_unsub->execute();
                                            $result_unsub = $stmt_unsub->fetch();
                                            if($stmt_unsub->rowCount()>0)
                                            {
                                                
                                            $unsub_count= $result_unsub['count1'];
                                        
                                            }

                                            //echo "u".$unsub_count;
                                            $stmt_unsub_bl = $conn->prepare($unsub_bl);
                                            $stmt_unsub_bl->execute();
                                            $result_unsub_bl = $stmt_unsub_bl->fetch();
                                            if($stmt_unsub_bl->rowCount()>0)
                                            {
                                            $unsub_bl_count= $result_unsub_bl['count1'];
                                            }
                                          //  echo "ubl".$unsub_bl_count;


                                            $alt = "ALTER TABLE $uf CONVERT TO CHARACTER SET utf8;";
                                            $stmtt = $conn->prepare($alt);
                                            $result = $stmtt->execute();

                                            $updatee= "UPDATE `upload_log` SET 
                                            `unsubscriber`=:unsub,
                                            `block_domain`=:bl,
                                            `unsub_blocked_domain`=:unsub_bl
                                            WHERE log_id='$log_id'";

                                            $updatee=$conn->prepare($updatee);
                                            $updatee->bindParam(':bl',$bl_count);
                                            $updatee->bindParam(':unsub',$unsub_count);
                                            $updatee->bindParam(':unsub_bl',$unsub_bl_count);
                                            $updatee->execute();


                                            $conn->beginTransaction();
                                            if($_POST['format_type'] == "format1")
                                            {
                                                $sql1 = "INSERT INTO
                                                `campaingauthors`
                                                (
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
                                                    CampID = $camp_id)";

                                            }
                                            if($_POST['format_type'] == "format2")
                                            {
                                                $sql1 = "INSERT INTO
                                               `campaingauthors`
                                               (
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
                                                CampID = $camp_id)";
                                   

                                            }
                                            if($_POST['format_type'] == "data_scopus")
                                            {
                                                $sql1 = "INSERT INTO
                                               `campaingauthors`
                                               (
                                                  `CampID`,
                                                  `rtemid`,
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
                                                CampID = $camp_id)";
                                   

                                            }
                                            if($_POST['format_type'] == "data_wos")
                                            {
                                                $sql1 = "INSERT INTO
                                               `campaingauthors`
                                               (
                                                  `CampID`,
                                                  `rtemid`,
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
                                                CampID = $camp_id)";
                                   

                                            }
                                            $sth = $conn->exec($sql1);
                                            $conn->commit();

                                          //  Update record in activity table
                                            // $sql = "UPDATE `activity` SET `add_authordata` = 1 
                                            // WHERE `CampID` = :CampID";

                                            // $stmt = $conn->prepare($sql);
                                            // $stmt->bindValue(':CampID', $camp_id);
                                            // $result = $stmt->execute();
                                          //  ---------------------------------5------------------------------------------------//

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
                                        
                                            echo "<div class='table-responsive'>
                                            <table class='table center-aligned-table table-bordered table-hover js-basic-example dataTable table-custom'>
                                         
                                            <thead>
                                            <tr style='background-color:rgba(41,58,74,0.95); color:white; font-size: 13px;'>
                                                <th>No of Unsubscriber</th>
                                                <th>No of blocked domains</th>
                                                <th>Both</th>
    
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                            <td>";
                                            echo $bl_count;
                                            echo "</td><td>";
                                            echo $unsub_count;
                                            echo "</td> <td>";
                                            echo $unsub_bl_count; 
                                            echo "</td></tr></tbody>
                                            </table> </div>";

                                            $time_end = microtime(true);
                                            $time = $time_end - $time_start;
                                            $time = round($time, 2);

                                            $newtargetfile = $target_file;
                                            if (file_exists($newtargetfile)) 
                                            {
                                               // unlink($newtargetfile);
                                            }

                                        } // if fileexist==1

                                    }
      
                                    
                                }
                                ?>
 
                          


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> <!-- Bootstrap Colorpicker Js -->
    <script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js -->
    <script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
    <script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js -->
    <script src="assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js -->
    <script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script src="assets/js/pages/forms/form-wizard.js"></script>
    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>
    <script src="assets/vendor/editable-table/mindmup-editabletable.js"></script> <!-- Editable Table Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/tables/editable-table.js"></script>
    
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/bundles/datatablescripts.bundle.js"></script>
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