<?php
ob_start();
session_start();
include 'include/conn.php';
if (!isset($_SESSION['AdminId'])) {

	header("Location: login.php");
} else if (isset($_SESSION['AdminId']) != "") {
	header("Location: index.php");
}

try {
	$sqlu = "UPDATE admin 
					SET logout_time = Now() 
					WHERE AdminId = :AdminId";

	$stmtu = $conn->prepare($sqlu);

	//Bind our variables.
	$stmtu->bindValue(':AdminId', $_SESSION['AdminId']);

	//Execute the statement.
	$resultu = $stmtu->execute();

	//If the logout process is successful.
	unset($_SESSION['AdminId']);
	unset($_SESSION['status']);
	unset($_SESSION['username']);
	unset($_SESSION['email']);
	unset($_SESSION['DBL']);
	unset($_SESSION['AC']);
	unset($_SESSION['RC']);
	unset($_SESSION['HC']);
	unset($_SESSION['VU']);
	unset($_SESSION['EU']);
	unset($_SESSION['DU']);
	unset($_SESSION['ADRE']);
	unset($_SESSION['ASRE']);
	unset($_SESSION['UM']);
	unset($_SESSION['RM']);
	unset($_SESSION['MM']);
	unset($_SESSION['IPM']);
	unset($_SESSION['SS']);
	unset($_SESSION['OUM']);
	unset($_SESSION['AM']);
	unset($_SESSION['AU']);
	unset($_SESSION['USUBM']);
	unset($_SESSION['RTEM']);
	unset($_SESSION['ACTC']);
	unset($_SESSION['VAM']);
	unset($_SESSION['OSM']);
	unset($_SESSION['MOM']);
	unset($_SESSION['AUWD']);
	unset($_SESSION['AUID']);
	unset($_SESSION['DDBL']);
	unset($_SESSION['URAM']);
	unset($_SESSION['ASA']);
	unset($_SESSION['MO']);
	unset($_SESSION['orgunit_id']);

	session_unset();
	session_destroy();

	header("Location: login.php");
	exit;
} catch (PDOException $e) {
	$errMsg = $e->getMessage();
	echo $errMsg;
}
