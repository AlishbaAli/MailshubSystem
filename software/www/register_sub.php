<?php
ob_start();
session_start();
include 'include/conn.php';

?>
<html lang="en">

<head>
	<title>:: <?php include './include/title.php'; ?> :: Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="description" content="Mplify Bootstrap 4.1.1 Admin Template">
	<meta name="author" content="ThemeMakker, design by: ThemeMakker.com">

	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/vendor/animate-css/animate.min.css">
	<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">

	<!-- MAIN CSS -->
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/color_skins.css">
	<script>
		function checkPass() {
			var pass1 = document.getElementById('pass1');
			var pass2 = document.getElementById('pass2');
			var message = document.getElementById('error-nwl');
			var goodColor = "#66cc66";
			var badColor = "#EE8989";

			if (pass1.value.length > 5) {
				pass1.style.backgroundColor = goodColor;
				message.style.color = goodColor;
				message.innerHTML = "character number ok!"
			} else {
				pass1.style.backgroundColor = badColor;
				message.style.color = badColor;
				message.innerHTML = "You have to enter at least 6 digit!"
				return;
			}

			if (pass1.value == pass2.value) {
				pass2.style.backgroundColor = goodColor;
				message.style.color = goodColor;
				message.innerHTML = "Match Perfect!"
			} else {
				pass2.style.backgroundColor = badColor;
				message.style.color = badColor;
				message.innerHTML = " These passwords don't match"
			}
		}
	</script>
</head>

<body class="theme-blue">
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle auth-main">

				<div class="auth-box">
					<div class="auth-left">
						<div class="left-top">
							<h4 style="color:White;">Bentham <br />Subscription Campaign</h4>
						</div>
						<div class="left-slider">
							<img src="assets/images/login/1.jpg" class="img-fluid" alt="">
						</div>

					</div>

					<?php include './include/conn.php';
					$error = "";
					if (isset($_POST['register'])) {

						//Retrieve the field values from our registration form.
						$firstname = !empty($_POST['firstname']) ? trim($_POST['firstname']) : null;
						$username = !empty($_POST['username']) ? trim($_POST['username']) : null;
						$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
						$pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
						$status = 'Active';

						//Now, we need to check if the supplied username already exists.

						//Construct the SQL statement and prepare it.
						$sql = "SELECT COUNT(username) AS num 
							FROM admin 
							WHERE username = :username";

						$stmt = $conn->prepare($sql);

						//Bind the provided username to our prepared statement.
						$stmt->bindValue(':username', $username);

						//Execute.
						$stmt->execute();

						//Fetch the row.
						$row = $stmt->fetch(PDO::FETCH_ASSOC);

						//If the provided username already exists - display error.

						if ($row['num'] > 0) {
							echo "<div class='alert alert-danger'><strong>User Alredy Exists, Please try again!</strong>
							<meta http-equiv='refresh' content='2;url=register_sub.php'>
						</div>";

							die();
						}


						//Hash the password as we do NOT want to store our passwords in plain text.
						$passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));

						//Prepare our INSERT statement.
						$sql = "INSERT INTO admin (firstname, username, email, password, status) VALUES (:firstname, :username, :email, :password, :status)";

						$stmt = $conn->prepare($sql);

						//Bind our variables.
						$stmt->bindValue(':firstname', $firstname);
						$stmt->bindValue(':username', $username);
						$stmt->bindValue(':email', $email);
						$stmt->bindValue(':password', $passwordHash);
						$stmt->bindValue(':status', $status);

						//Execute the statement and insert the new account.
						$result = $stmt->execute();

						//If the signup process is successful.
						if ($result > 0) {

							echo "<div class='alert alert-success'><strong>You have successfully Registerd</strong>
								<br/>
								<div>
									<a href='login.php'>
									<button class='mr-2 mb-2 btn btn-primary btn-md' style='align:right;' type='button'>Login Now!!!</button></a>
								</div>
								
								</div>
								
						";

							die();
						}

						$conn = null;
					}

					?>

					<div class="auth-right">
						<div class="card">
							<div class="header">
								<p class="lead">Registeration</p>
							</div>

							<div class="body">
								<form class="form-auth-small" method="post" action="register_sub.php">
									<div class="form-group">
										<input name="firstname" class="form-control" required="required" data-error="Please input your First Name" placeholder="First Name" type="text">
										<div class="pre-icon os-icon os-icon-user"></div>
										<div class="help-block form-text with-errors form-control-feedback"></div>
									</div>
									<div class="form-group">
										<input name="username" class="form-control" required="required" data-error="Please input your Username" placeholder="Enter your username" type="text">
										<div class="pre-icon os-icon os-icon-user-male-circle"></div>
										<div class="help-block form-text with-errors form-control-feedback">
										</div>
									</div>
									<div class="form-group">
										<input name="email" class="form-control" required="required" data-error="Your email address is invalid" placeholder="Enter email" type="email">
										<div class="pre-icon os-icon os-icon-email-2-at2"></div>
										<div class="help-block form-text with-errors form-control-feedback"></div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<input name="password" class="form-control" data-minlength="6" required="required" placeholder="Password" type="password" id="pass1" onkeyup="checkPass(); return false;">
												<div class="pre-icon os-icon os-icon-fingerprint"></div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input class="form-control" data-match-error="Passwords don&#39;t match" required="required" placeholder="Confirm Password" type="password" id="pass2" onkeyup="checkPass(); return false;">
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div id="error-nwl"></div>
									</div>

									<div class="buttons-w">
										<button type="submit" name="register" class="btn btn-primary">Register Now</button>
									</div>
									<br />
									<a href="login.php">Already a member!!!</a>
									<br />

								</form>
							</div>



						</div>
					</div>
				</div>
			</div>
	
			<!-- END WRAPPER -->


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