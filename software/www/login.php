<?php

ob_start();
// session_start();
$lifetime=3600;
								session_start();
								setcookie(session_name(),session_id(),time()+$lifetime);
include 'include/conn.php';
//   error_reporting(E_ALL);
//   ini_set('display_errors', 1);

?>
<html lang="en">

<head>
	<title>:: <?php include './include/title.php'; ?> :: Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/vendor/animate-css/animate.min.css">
	<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">

	<!-- MAIN CSS -->
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/color_skins.css">
</head>

<body class="theme-blue">
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle auth-main">

				<div class="auth-box">
					<div class="auth-left">
						<div class="left-top">
							<!--<h4 style="color:White;">Brand Ambassador</h4>-->
						</div>
						<div class="left-slider">
							<img src="assets/image/1.jpeg" width="400" height="300" alt="">
						</div>

					</div>
					<?php

					$error = "";
					if (isset($_POST['login'])) {
						//Retrieve the field values from our login form.
						$username = !empty($_POST['username']) ? trim($_POST['username']) : null;
						$passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

						//Retrieve the admin account information for the given username.
						$sql = "SELECT AdminId, username, password, status, email 
					FROM admin 
					WHERE username = :username";

						$stmt = $conn->prepare($sql);

						//Bind value.
						$stmt->bindValue(':username', $username);

						//Execute.
						$stmt->execute();

						//Fetch row.
						$admin = $stmt->fetch(PDO::FETCH_ASSOC);
						//If $row is FALSE.



						if ($admin === false) {
							//Could not find a admin with that username!
							$error =  "<br/><div class='alert alert-danger'><strong>Incorrect Username!</strong></div>";
						} else {
							//admin account found. Check to see if the given password matches the
							//password hash that we stored in our admin table.

							//Compare the passwords.
							$validPassword = password_verify($passwordAttempt, $admin['password']);

							//If $validPassword is TRUE, the login has been successful.
							if ($validPassword === true) {
								$user_id =  $admin['AdminId'];
								$status= $admin['status'];

								//check if user is inactive then dont allow to login

								 if($status=='Active'){

								


                                $flag=0;
								$flago=0;
								//get org_id----------------
							
								$stmt_o = $conn->prepare("SELECT tbl_organizational_unit.orgunit_id, orgunit_status FROM tbl_orgunit_user INNER JOIN
								tbl_organizational_unit ON  tbl_organizational_unit.orgunit_id=tbl_orgunit_user.orgunit_id WHERE user_id= $user_id");

								$stmt_o->execute();
								$org = $stmt_o->fetch();
								$_SESSION['orgunit_id'] = $org['orgunit_id'];

								//-------------------------------------------------

								//------------session timeout in milliseconds-----

								$_SESSION['timeout'] = 3600000;
							
								if(isset($_SESSION['orgunit_id']) && $org['orgunit_status']!='Active'){
									$error =  "<br/><div class='alert alert-danger'><strong>Account temporary disabled!</strong></div>";

								}
								else {




								//org_id-------------------

								//Get settings based on organization
								if(isset($_SESSION['orgunit_id'])){

								$stmts = $conn->prepare("SELECT system_setting FROM tbl_organizational_unit WHERE orgunit_id=:orgunit_id");
								$stmts->bindValue(':orgunit_id', $_SESSION['orgunit_id']);
								$stmts->execute();
								$settings= $stmts->fetch();
								
								$_SESSION['settings_type']= $settings['system_setting'];

								if(trim($_SESSION['settings_type'])=="sys-defined"){
									$stmts1 = $conn->prepare("SELECT * FROM system_setting WHERE status='Active'");
									$stmts1->execute();
									$sys_settings= $stmts1->fetch();
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
                                    if($stmts2->rowCount()<1){
										$flag=1;
								

								

									}  
									else{

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

								}
							}
							

								if($flag==1){
									$error =  "<br/><div class='alert alert-danger'><strong>Account setup incomplete!</strong></div>";

								}
								else{

								//Provide the admin with a login session.
								$_SESSION['AdminId'] = $admin['AdminId'];


								$_SESSION['status'] = $admin['status'];
								$_SESSION['username'] = $admin['username'];
								//$_SESSION['firstname'] = $admin['firstname'];
								$_SESSION['email'] = $admin['email'];



								//user management code

								$_SESSION['DBL'] = "NO";
								$_SESSION['AC'] = "NO";
								$_SESSION['RC'] = "NO";
								$_SESSION['HC'] = "NO";
								$_SESSION['VU'] = "NO";
								$_SESSION['EU'] = "NO";
								$_SESSION['DU'] = "NO";
								$_SESSION['ADRE'] = "NO";
								$_SESSION['ASRE'] = "NO";
								$_SESSION['UM'] = "NO";
								$_SESSION['RM'] = "NO";
								$_SESSION['MM'] = "NO";
								$_SESSION['IPM'] = "NO";
								$_SESSION['SS'] = "NO";
								$_SESSION['OUM'] = "NO";
								$_SESSION['AM'] = "NO";
								$_SESSION['AU'] = "NO";
								$_SESSION['USUBM'] = "NO";
								$_SESSION['RTEM'] = "NO";
								$_SESSION['ACTC'] = "NO";
								$_SESSION['VAM'] = "NO";
								$_SESSION['OSM'] = "NO";
								$_SESSION['MOM'] = "NO";
								$_SESSION['AUWD'] = "NO";
								$_SESSION['AUID'] = "NO";
								$_SESSION['DDBL'] = "NO";
								$_SESSION['URAM'] = "NO";
								$_SESSION['ASA'] = "NO";
								$_SESSION['MO'] = "NO";
								$_SESSION['ET'] = "NO";
								$_SESSION['OS'] = "NO";



								$stmt_rl = $conn->prepare("SELECT
  
								r.restriction_level AS r_level
							FROM
								admin AS u
							INNER JOIN tbl_user_role_prev AS ur
							INNER JOIN tbl_role_privilege AS r
							ON
								u.AdminId = ur.user_id AND u.AdminId =:AdminId AND r.role_prev_id = ur.role_prev_id");
							 $stmt_rl->bindValue(':AdminId', $user_id);	
							 $stmt_rl->execute();
							 $r_level=$stmt_rl->fetch();
							 $_SESSION['r_level'] = $r_level['r_level'];



								//activity via roles
								$stmt = $conn->prepare(" SELECT
                    u.username AS username,
                    u.AdminId AS user_id,
                    u.email AS email,
                    r.role_prev_id AS role,
                    ra.activity_id AS activity,
                    a.activity_name,
                    a.activity_code
                FROM
                    admin AS u
                INNER JOIN tbl_user_role_prev AS r
                INNER JOIN tbl_role_prev_activity AS ra
                INNER JOIN tbl_activity AS a
                ON
                    u.AdminId = r.user_id AND r.role_prev_id = ra.role_prev_id AND ra.activity_id = a.activity_id
                WHERE
                    u.AdminId =$user_id");
								$stmt->execute();
								$row_allowed = $stmt->fetchAll();

								//activity directly

								$sql = "SELECT
                    u.username AS username,
                    u.AdminId AS user_id,
                    u.email AS email,
                    ua.activity_id AS activity,
                    a.activity_code
                FROM
                    admin AS u
                INNER JOIN tbl_user_activity AS ua
                INNER JOIN tbl_activity AS a
                ON
                    u.AdminId = ua.user_id AND ua.activity_id = a.activity_id
                WHERE
                    u.AdminId =
                     $user_id

                    ";
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$row_allowed2 = $stmt->fetchAll();


								$stmt_activity = $conn->prepare("SELECT * FROM tbl_activity");
								$stmt_activity->execute();
								$row_all = $stmt_activity->fetchAll();

								foreach ($row_allowed as $ra) {
									foreach ($row_all as $all) {

										if ($ra['activity'] == $all['activity_id']) {

											if ($ra['activity_code'] == "DBL") {
												$_SESSION['DBL'] = "YES";
											}

											if ($ra['activity_code'] == "AC") {
												$_SESSION['AC'] = "YES";
											}
											if ($ra['activity_code'] == "RC") {
												$_SESSION['RC'] = "YES";
											}
											if ($ra['activity_code'] == "HC") {
												$_SESSION['HC'] = "YES";
											}
											if ($ra['activity_code'] == "VU") {
												$_SESSION['VU'] = "YES";
											}
											if ($ra['activity_code'] == "EU") {
												$_SESSION['EU'] = "YES";
											}

											if ($ra['activity_code'] == "DU") {
												$_SESSION['DU'] = "YES";
											}
											if ($ra['activity_code'] == "ADRE") {
												$_SESSION['ADRE'] = "YES";
											}
											if ($ra['activity_code'] == "ASRE") {
												$_SESSION['ASRE'] = "YES";
											}
											if ($ra['activity_code'] == "UM") {
												$_SESSION['UM'] = "YES";
											}
											if ($ra['activity_code'] == "RM") {
												$_SESSION['RM'] = "YES";
											}
											if ($ra['activity_code'] == "MM") {
												$_SESSION['MM'] = "YES";
											}
											if ($ra['activity_code'] == "IPM") {
												$_SESSION['IPM'] = "YES";
											}
											if ($ra['activity_code'] == "SS") {
												$_SESSION['SS'] = "YES";
											}
											if ($ra['activity_code'] == "OUM") {
												$_SESSION['OUM'] = "YES";
											}
											if ($ra['activity_code'] == "AM") {
												$_SESSION['AM'] = "YES";
											}
											if ($ra['activity_code'] == "AU") {
												$_SESSION['AU'] = "YES";
											}
											if ($ra['activity_code'] == "USUBM") {
												$_SESSION['USUBM'] = "YES";
											}
											if ($ra['activity_code'] == "RTEM") {
												$_SESSION['RTEM'] = "YES";
											}
											if ($ra['activity_code'] == "ACTC") {
												$_SESSION['ACTC'] = "YES";
											}
											if ($ra['activity_code'] == "VAM") {
												$_SESSION['VAM'] = "YES";
											}
											if ($ra['activity_code'] == "OSM") {
												$_SESSION['OSM'] = "YES";
											}
											if ($ra['activity_code'] == "MOM") {
												$_SESSION['MOM'] = "YES";
											}
											if ($ra['activity_code'] == "AUWD") {
												$_SESSION['AUWD'] = "YES";
											}
											if ($ra['activity_code'] == "AUID") {
												$_SESSION['AUID'] = "YES";
											}
											if ($ra['activity_code'] == "DDBL") {
												$_SESSION['DDBL'] = "YES";
											}
											if ($ra['activity_code'] == "URAM") {
												$_SESSION['URAM'] = "YES";
											}
											if ($ra['activity_code'] == "ASA") {
												$_SESSION['ASA'] = "YES";
											}

											if ($ra['activity_code'] == "MO") {
												$_SESSION['MO'] = "YES";
											}
											if ($ra['activity_code'] == "ET") {
												$_SESSION['ET'] = "YES";
											}
											if ($ra['activity_code'] == "OS") {
												$_SESSION['OS'] = "YES";
											}
										}
									}
								}

								//user-activity
								foreach ($row_allowed2 as $ra) {
									foreach ($row_all as $all) {

										if ($ra['activity'] == $all['activity_id']) {

											if ($ra['activity_code'] == "DBL") {
												$_SESSION['DBL'] = "YES";
											}

											if ($ra['activity_code'] == "AC") {
												$_SESSION['AC'] = "YES";
											}
											if ($ra['activity_code'] == "RC") {
												$_SESSION['RC'] = "YES";
											}
											if ($ra['activity_code'] == "HC") {
												$_SESSION['HC'] = "YES";
											}
											if ($ra['activity_code'] == "VU") {
												$_SESSION['VU'] = "YES";
											}
											if ($ra['activity_code'] == "EU") {
												$_SESSION['EU'] = "YES";
											}

											if ($ra['activity_code'] == "DU") {
												$_SESSION['DU'] = "YES";
											}
											if ($ra['activity_code'] == "ADRE") {
												$_SESSION['ADRE'] = "YES";
											}
											if ($ra['activity_code'] == "ASRE") {
												$_SESSION['ASRE'] = "YES";
											}
											if ($ra['activity_code'] == "UM") {
												$_SESSION['UM'] = "YES";
											}
											if ($ra['activity_code'] == "RM") {
												$_SESSION['RM'] = "YES";
											}
											if ($ra['activity_code'] == "MM") {
												$_SESSION['MM'] = "YES";
											}
											if ($ra['activity_code'] == "IPM") {
												$_SESSION['IPM'] = "YES";
											}
											if ($ra['activity_code'] == "SS") {
												$_SESSION['SS'] = "YES";
											}
											if ($ra['activity_code'] == "OUM") {
												$_SESSION['OUM'] = "YES";
											}
											if ($ra['activity_code'] == "AM") {
												$_SESSION['AM'] = "YES";
											}
											if ($ra['activity_code'] == "AU") {
												$_SESSION['AU'] = "YES";
											}
											if ($ra['activity_code'] == "USUBM") {
												$_SESSION['USUBM'] = "YES";
											}
											if ($ra['activity_code'] == "RTEM") {
												$_SESSION['RTEM'] = "YES";
											}
											if ($ra['activity_code'] == "ACTC") {
												$_SESSION['ACTC'] = "YES";
											}
											if ($ra['activity_code'] == "VAM") {
												$_SESSION['VAM'] = "YES";
											}
											if ($ra['activity_code'] == "OSM") {
												$_SESSION['OSM'] = "YES";
											}
											if ($ra['activity_code'] == "MOM") {
												$_SESSION['MOM'] = "YES";
											}
											if ($ra['activity_code'] == "AUWD") {
												$_SESSION['AUWD'] = "YES";
											}
											if ($ra['activity_code'] == "AUID") {
												$_SESSION['AUID'] = "YES";
											}
											if ($ra['activity_code'] == "DDBL") {
												$_SESSION['DDBL'] = "YES";
											}

											if ($ra['activity_code'] == "URAM") {
												$_SESSION['URAM'] = "YES";
											}
											if ($ra['activity_code'] == "ASA") {
												$_SESSION['ASA'] = "YES";
											}
											if ($ra['activity_code'] == "MO") {
												$_SESSION['MO'] = "YES";
											}
											if ($ra['activity_code'] == "ET") {
												$_SESSION['ET'] = "YES";
											}
											if ($ra['activity_code'] == "OS") {
												$_SESSION['OS'] = "YES";
											}
										}
									}
								}


								//user management code
								$sql = "UPDATE admin
							SET login_time = Now()
							WHERE username = :username";

								$stmt = $conn->prepare($sql);

								//Bind value.
								$stmt->bindValue(':username', $username);

								//Execute.
								$stmt->execute();

								/*
						echo "<div class='alert alert-success' role='alert'>
							<strong>You have successfully Login</strong>
						  </div>";
						die(); 
					*/

								//Redirect to our protected page, which we called index.php
								if($_SESSION['r_level']==0) {
									header('Location: sys__dashboard.php'); 
									exit;
								} else {
								header('Location: index.php');
								exit;
								}

							}

						}
					}
					else if($admin['status']=='In Active'){
						$error =  "<br/><div class='alert alert-danger'><strong>Account Not Verified!</strong></div>";

					}
					else if($admin['status']=='Terminated' || $admin['status']=='Suspended'){
						$error =  "<br/><div class='alert alert-danger'><strong>Account disabled!</strong></div>";

					}
							} else {
								//$validPassword was FALSE. Passwords do not match.
								$error =  "<br/><div class='alert alert-danger'><strong>Incorrect Password!</strong></div>";
							}
						
						}
					}
					?>
					<div class="auth-right">
						<div class="card">
							<div class="header">
								<p class="lead">Log in</p>
							</div>
							<div class="body">
								<form class="form-auth-small" action="login.php" method="post">
									<div class="form-group">
										<label for="signin-email" class="control-label sr-only">Username</label>
										<input type="text" name="username" class="form-control" id="signin-email" value="" placeholder="User Name">
									</div>
									<div class="form-group">
										<label for="signin-password" class="control-label sr-only">Password</label>
										<input type="password" name="password" class="form-control" id="signin-password" value="" placeholder="Password">
									</div>

									<button type="submit" name="login" class="btn btn-primary btn-lg btn-block">LOGIN</button>
									<br />
									<?php
									echo $error;
									?>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
</body>

</html>