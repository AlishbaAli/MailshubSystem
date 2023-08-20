
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
	//User not logged in. Redirect them back to the login page.
	header('Location: login.php');
	exit;
}




$sql = "SELECT Camp_Status FROM campaign WHERE CampID=:CampID";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":CampID", $_POST['campid']);

if ($stmt->execute()) {
	if ($stmt->rowCount() == 1) {

		$row = $stmt->fetch();

		$data['Camp_Status'] = $row['Camp_Status'];
	}
}

echo json_encode($data);



?>