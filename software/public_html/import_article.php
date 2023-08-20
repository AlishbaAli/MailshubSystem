<?php
if (isset($_SESSION['AMA']) || isset($_SESSION['ADCAMP'])) {
	if (($_SESSION['AMA'] == "NO") && ($_SESSION['ADCAMP'] == "NO" ) ){

		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
}
if(isset($_POST['submit2'])) {
	ob_start();
	session_start();
}

	if(!isset($_SESSION['AdminId']))
	{
		//User not logged in. Redirect them back to the login page.//
		header('Location: logout.php');
		exit;
	}

	include './include/conn.php';	

	// ini_set('memory_limit', '-1');
	// $AdminId = $_SESSION['AdminId'];
	

			
		error_reporting(E_ALL);
		ini_set('display_errors', 1);			
			
			
// ------------------------------------------------ [ Article Data upload ] ----------------------------------------------------- //
			
			//Upload eBook Data
			if(isset($_FILES["Article_List"]))
			{
				// from addCampaign file Campid is last inserted id but in edit Campaign form it will be posted.
				if(isset($_POST['CampID'])) {
					$CampID = $_POST['CampID'];
				} 

				$target_dir = "uploads/";
				$target_file = $target_dir . basename($_FILES["Article_List"]["name"]);

				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

				$uploadOk = 1;
				if($imageFileType != "csv" ) 
				{
					$uploadOk = 0;
				}

				if ($uploadOk != 0) 
				{
					if (move_uploaded_file($_FILES["Article_List"]["tmp_name"], $target_dir.'Articlefile-Cid-'.$CampID.'.csv')) 
					{	
						// Checking file exists or not
						$target_file = $target_dir . 'Articlefile-Cid-'.$CampID.'.csv';
						$fileexists = 0;
						if (file_exists($target_file)) 
						{
						   $fileexists = 1;
						}
						if ($fileexists == 1 ) 
						{
							// Reading file
							$file = fopen($target_file,"r");
							$i = 0;

							$importData_arr = array();
						   
							while (($data = fgetcsv($file, 100000001, ",")) !== FALSE) 
							{
								$num = count($data);

								for ($c=0; $c < $num; $c++) 
								{
									$importData_arr[$i][] = $data[$c];
								}
								$i++;
							}
							fclose($file);

							$skip = 0;
							

							// insert import data
							foreach($importData_arr as $data)
							{
								if($skip != 0)
								{	
									
									
									//$AdminId = $_SESSION['AdminId'];
									$Sno = $data[0];
									$disp = htmlentities(addslashes($data[1]));
									$disp='%'.$disp.'%';

									$journalname = htmlentities(addslashes($data[2])); 
									$journalname1 = '%'.$journalname.'%';
									$url = htmlentities(addslashes($data[3]));
									$volume = htmlentities(addslashes($data[4]));
									$journalCover = htmlentities(addslashes($data[5]));
									$coverurl = htmlentities(addslashes($data[6]));
									$issue = htmlentities(addslashes($data[7]));
									$year = htmlentities(addslashes($data[8]));
									$article_title = htmlentities(addslashes($data[9]));
									$author = htmlentities(addslashes($data[10]));
									$abstract = htmlentities(addslashes($data[11]));
									$absurl = htmlentities(addslashes($data[12]));
									$doi = htmlentities(addslashes(trim($data[13])));
									$absurldownload = htmlentities(addslashes($data[14]));
									$article_id = htmlentities(addslashes($data[15]));

									$alerts=""; $alert="";


									// Checking if doi is not empty. only enter the record if doi exist.
if (!empty($doi) && $doi != null)
{

								// Checking duplicate entry

									// -----------------------------------
									$check_duplication = "SELECT * from articles_scopus 
									where 	
										camp_id = '$CampID' 
										and doi = trim('$doi')
									";
							
									$check_duplication = $conn->prepare($check_duplication);
									$check_duplication->execute();	
								
if( $check_duplication->rowCount()  >0) {
	//row exist means duplicate article exist
	//do nothing
} else {
								// 	-------------------------------------

							
										// Insert record 
										// jurl is journal article url so put in article table
										$pro="SELECT productid from products where disp like :disp and product_name like :jname and product_cover is not null";
										$pro=$conn->prepare($pro);
										$pro->bindParam(':disp',$disp);
										$pro->bindParam(':jname',$journalname1);

										$pro->execute();
										if($pro->rowCount() > 0) {
											$product_id=$pro->fetch();$product_id=$product_id['productid'];
											
											$insert_query = "INSERT into articles_scopus 
											(	
												camp_id,
												product_id, 
												
												`url`,
												volume, 	
												issue, 
												year, 
												title, 
												authors, 
												abstract,
												absurl,
												doi,
												absurl_download,
												article_id,
											
												`status`
											) 
									values (
												'$CampID', 
												'$product_id',
												'$url',
												'$volume',
												trim('".$issue."'),
												trim('".$year."'),
												trim('".$article_title."'),
												trim('".$author."'),
												trim('".$abstract."'),
												trim('".$absurl."'),
												trim('".$doi."'),
												trim('".$absurldownload."'),
												trim('".$article_id."'),
												
												'Active'									
											)";
									
									$stmt = $conn->prepare($insert_query);
									$result = $stmt->execute();	
								

										} // loop checks product id

										else { 
											$alert="the product : $journalname for article $article_title was not found. <br>";
											$alerts .= "<br>".$alert;
										}
									
										
									} //--------------if loop checks duplicate entry
								}	// -------------- loop check doi is not empty or null
											
								} // ------------ loop bring each entry in csv
								$skip ++;  
							}
										echo "<div class='alert alert-danger' role='alert'>";
									 	echo "<strong>" .$alerts."</strong>";
										echo "</div>"; 

							
							$newtargetfile = $target_file;
							if (file_exists($newtargetfile)) 
							{
								unlink($newtargetfile);
							} 

							// ...exit to dashboard
							header('Location: index.php');
		                    exit;

						}

					}
				}
			}

		//COMMMENTS		// -- 	and product_id = '$product_id' 
								// 	-- 	and `url`= 	'$url' 
										
								// 	-- 	and volume = '$volume'
										
								// 	-- and issue = 	trim('$issue')
								// 	-- and `year` = 	trim('$year')
								// 	-- and title = trim('$article_title')
								// 	-- and authors = trim('$author')
								// 	-- and abstract = trim('$abstract')
								// 	-- and absurl = trim('$absurl')
								//  -- and absurl_download = trim('$absurldownload')
								// 	-- and article_id = 	trim('$article_id')
									
								// 	-- 	and `status` = 'Active'	

									//$sql = "select count(eISBNNo) as allcount from ebook where eISBNNo = trim('".$eISBNNo."') and CampID = '".$CampID."'";
									
									//$retrieve_data = $conn->query($sql);
									
									//$row = $retrieve_data->fetch(PDO::FETCH_ASSOC);
									//$count = $row['allcount'];
									
									//if($count == 0)
									//{