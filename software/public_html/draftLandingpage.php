<?php
//session_start();

include 'include/conn.php';
if (isset($_SESSION['AC'])) {
	if ($_SESSION['AC'] == "NO") {

		//User not logged in. Redirect them back to the login page.
		header('Location: page-403.html');
		exit;
	}
}
$CampID =  $_GET['CampID'];

$sql = "SELECT draft_id, subscription_draft
                                        			FROM draft
                                        			WHERE CampID = :CampID
                                        			";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':CampID', $CampID);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$cmp_draft = html_entity_decode($row['subscription_draft']);


$sql_get = "SELECT  `Fname`, `Lastname`,Journal_title,article_title
                                        			FROM `campaingauthors` WHERE CampID = :CampID";
$stmt_get = $conn->prepare($sql_get);
$stmt_get->bindValue(':CampID', $CampID);
$result_get = $stmt_get->execute();

if ($stmt_get->rowCount() > 0) {
	$result_get = $stmt_get->fetchAll();
	//initialization for s.no.
	$a = 0;
	$b = 1;
	foreach ($result_get as $row_get) {
		$article_title = trim($row_get['article_title']);
		$Journal_title = trim($row_get['Journal_title']);

		$Draft_tags = ["{article_title}", "{Journal_title}"];

		echo "<h2>Author " . $c = $b + $a . " Draft</h2>";

		echo "<div style='border: 1px solid black; width:50%; padding:20px;'>";

		echo "Dear Dr. " . $row_get['Fname'] . " " . $row_get['Lastname'] . ":";

		$DB_Rows   = [$article_title, $Journal_title];

		$cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
		echo $cmp_draft_new;


		echo "</div>";
		echo "<br/>";
		$b++;
	}
}
