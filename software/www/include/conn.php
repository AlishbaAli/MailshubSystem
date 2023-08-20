<?php
// $servername = 'localhost';
// $username = 'alishba';
//  $password = 'Alishba-786';

  $servername = 'localhost';
  $username = 'mailshub_admin';
  $password = 'VFD1^*srYlH+';

//$servername = 'localhost';
 //$username = 'saeeda';
 //$password = 'Saeeda-786';

	try {
			$conn = new PDO("mysql:host=$servername;dbname=mailshub_db", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			
			//echo "Connected successfully"; 
		}
	catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
?>


<?php
/*$servername = "172.31.2.173";
$username = "benthame_eshot";
$password = "z5WUj[n.}Hm)";

	try {
			$conn = new PDO("mysql:host=$servername;dbname=benthame_db", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//echo "Connected successfully"; 
		}
	catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}*/
?>
