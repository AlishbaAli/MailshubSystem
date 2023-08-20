<?php
require 'include/conn.php';


if (isset($_GET['CAID']) && (!empty($_GET['CAID']))) {
	$CampID = $_GET['CID'];
	$CampaingAuthorsID = $_GET['CAID'];
	//GET camp status

	$stmt_s = $conn->prepare("SELECT Camp_Status FROM campaign WHERE CampID= :CampID");
	$stmt_s->bindValue(':CampID', $CampID);
	$stmt_s->execute();
	$result = $stmt_s->fetch(PDO::FETCH_ASSOC);
	$Camp_Status = $result['Camp_Status'];

	if ($Camp_Status == 'Active') {
		$campauthourstable = "campaingauthors";
	} else if ($Camp_Status == 'Stop') {
		$campauthourstable = "campaingauthors_hold_archive";
	} else if ($Camp_Status == 'Completed') {
		$campauthourstable = "campaingauthors_comp_archive";
	}

	//Find author email address


	$stmt = $conn->prepare("SELECT Email FROM $campauthourstable WHERE CampaingAuthorsID =  :CampaingAuthorsID");
	$stmt->bindValue(':CampaingAuthorsID', $CampaingAuthorsID);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$AuthorEmail = $result['Email'];



	//Function for IP Info
	function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
	{
		$output = NULL;
		if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
			$ip = $_SERVER["REMOTE_ADDR"];
			if ($deep_detect) {
				if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
					$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
		}
		$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), " ", strtolower(trim($purpose)));
		$support    = array("country", "countrycode", "state", "region", "city", "location", "address", "continent");
		$continents = array(
			"AF" => "Africa",
			"AN" => "Antarctica",
			"AS" => "Asia",
			"EU" => "Europe",
			"OC" => "Australia (Oceania)",
			"NA" => "North America",
			"SA" => "South America"
		);
		if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
			$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
			if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
				switch ($purpose) {
					case "location":
						$output = array(
							"city"           => @$ipdat->geoplugin_city,
							"state"          => @$ipdat->geoplugin_regionName,
							"country"        => @$ipdat->geoplugin_countryName,
							"country_code"   => @$ipdat->geoplugin_countryCode,
							"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
							"continent_code" => @$ipdat->geoplugin_continentCode
						);
						break;
					case "address":
						$address = array($ipdat->geoplugin_countryName);
						if (@strlen($ipdat->geoplugin_regionName) >= 1)
							$address[] = $ipdat->geoplugin_regionName;
						if (@strlen($ipdat->geoplugin_city) >= 1)
							$address[] = $ipdat->geoplugin_city;
						$output = implode(", ", array_reverse($address));
						break;
					case "city":
						$output = @$ipdat->geoplugin_city;
						break;
					case "state":
						$output = @$ipdat->geoplugin_regionName;
						break;
					case "region":
						$output = @$ipdat->geoplugin_regionName;
						break;
					case "country":
						$output = @$ipdat->geoplugin_countryName;
						break;
					case "countrycode":
						$output = @$ipdat->geoplugin_countryCode;
						break;
					case "continent":
						if (@$ipdat->geoplugin_continentCode == "AF") {
							$output = "Africa";
						} else if (@$ipdat->geoplugin_continentCode == "AN") {
							$output = "Antarctica";
						} else if (@$ipdat->geoplugin_continentCode == "AS") {
							$output = "Asia";
						} else if (@$ipdat->geoplugin_continentCode == "EU") {
							$output = "Europe";
						} else if (@$ipdat->geoplugin_continentCode == "OC") {
							$output = "Australia (Oceania)";
						} else if (@$ipdat->geoplugin_continentCode == "NA") {
							$output = "North America";
						} else if (@$ipdat->geoplugin_continentCode == "SA") {
							$output = "South America";
						} else {
							$output = @$ipdat->geoplugin_continentCode;
						}
						break;
				}
			}
		}
		return $output;
	}
	//End Of IP Info Function (Returns Output)	


	$output = shell_exec('geoiplookup ' . $_SERVER['REMOTE_ADDR']);
	$line = explode("\n", $output);

	$country_raw = explode(":", $line[0]);
	$address_raw = explode(":", $line[1]);
	$isp_raw = explode(":", $line[2]);

	$country_data = explode(",", $country_raw[1]);
	$address_data = explode(",", $address_raw[1]);



	date_default_timezone_set('UTC') + 5;

	$Country = trim($country_data[1], " ");
	$CountryCode = trim($country_data[0], " ");
	$StateCode = trim($address_data[1], " ");
	$State = trim($address_data[2], " ");
	$City = trim($address_data[3], " ");
	$ZipCode = trim($address_data[4], " ");
	$IP_Owner = trim($isp_raw[1], " ");

	if ($Country == "") {
		$Country = "Others";
	}

	$Region = ip_info($_SERVER['REMOTE_ADDR'], "Continent"); //$_SERVER['REMOTE_ADDR']
	$IP_Address = $_SERVER['REMOTE_ADDR'];
	$RefScript = $_SERVER['SCRIPT_FILENAME'];
	$CampaingAuthorsID = $_GET['CAID'];
	$Stats_Type = 'EmailOpened';
	$LinkURL = $_SERVER['HTTP_REFERER'];
	$REMOTE_HOST = $_SERVER['REMOTE_HOST'];
	$REMOTE_HOST = 'REMOTE_HOST';
	$REMOTE_PORT = $_SERVER['REMOTE_PORT'];
	$REQUEST_TIME = $_SERVER['REQUEST_TIME'];
	$AccessDay = date("d");
	$AccessMonthID = date("m");
	$AccessMonth = date("F");
	$AccessYear = date("Y");
	$AccessWeek = date("W");



	/* echo "<br/>Country :".$Country ;
		echo "<br/>Region :".$Region ;
		echo "<br/>IP_Address :".$IP_Address ;
		echo "<br/>RefScript :".$RefScript ;
		echo "<br/>CampaingAuthorsID :".$CampaingAuthorsID ;
		echo "<br/>Stats_Type :".$Stats_Type ;
		echo "<br/>REMOTE_PORT :".$REMOTE_PORT ;
		echo "<br/>REQUEST_TIME :".$REQUEST_TIME ;
		echo "<br/>AccessDay :".$AccessDay ;
		echo "<br/>AccessMonthID :".$AccessMonthID ;
		echo "<br/>AccessMonth :".$AccessMonth ;
		echo "<br/>AccessYear :".$AccessYear ;
		echo "<br/>AccessWeek :".$AccessWeek ; */



	$query_stmt1 = $conn->prepare("INSERT INTO `campaign_author_stats`
										(
											`CampaingAuthorsID`,
											`Stats_Type`,
											`IP_Address`,
											`Country`,
											`CountryCode`,
											`State`,
											`City`,
											`Region`,
											`StateCode`,
											`ZipCode`,
											`EmailOpenedDateTime`,
											`AccessDay`,
											`AccessMonth`,
											`AccessYear`,
											`RefScript`,
											`LinkURL`,
											`REMOTE_HOST`,
											`REMOTE_PORT`,
											`REQUEST_TIME`,
											`IP_Owner`,
											`AuthorEmail`
										)
										VALUES
										(
											:CampaingAuthorsID,
											:Stats_Type,
											:IP_Address,
											:Country,
											:CountryCode,
											:State,
											:City,
											:Region,
											:StateCode,
											:ZipCode,
											 NOW(),
											:AccessDay,
											:AccessMonth,
											:AccessYear,
											:RefScript,
											:LinkURL,
											:REMOTE_HOST,
											:REMOTE_PORT,
											:REQUEST_TIME,
											:IP_Owner,
											:AuthorEmail
										)");


	$query_stmt1->bindParam(':CampaingAuthorsID', $CampaingAuthorsID);
	$query_stmt1->bindParam(':Stats_Type', $Stats_Type);
	$query_stmt1->bindParam(':IP_Address', $IP_Address);
	$query_stmt1->bindParam(':Country', $Country);
	$query_stmt1->bindParam(':CountryCode', $CountryCode);
	$query_stmt1->bindParam(':State', $State);
	$query_stmt1->bindParam(':City', $City);
	$query_stmt1->bindParam(':Region', $Region);
	$query_stmt1->bindParam(':StateCode', $StateCode);
	$query_stmt1->bindParam(':ZipCode', $ZipCode);
	$query_stmt1->bindParam(':AccessDay', $AccessDay);
	$query_stmt1->bindParam(':AccessMonth', $AccessMonth);
	$query_stmt1->bindParam(':AccessYear', $AccessYear);
	$query_stmt1->bindParam(':RefScript', $RefScript);
	$query_stmt1->bindParam(':LinkURL', $LinkURL);
	$query_stmt1->bindParam(':REMOTE_HOST', $REMOTE_HOST);
	$query_stmt1->bindParam(':REMOTE_PORT', $REMOTE_PORT);
	$query_stmt1->bindParam(':REQUEST_TIME', $REQUEST_TIME);
	$query_stmt1->bindParam(':IP_Owner', $IP_Owner);
	$query_stmt1->bindParam(':AuthorEmail', $AuthorEmail);

	$result = $query_stmt1->execute();

	if ($result > 0) {
		"<br/><br/>Record Inserted<br/>";
	}
}
// open the file in a binary mode
$name = 'img/transparent_img.png';
$fp = fopen($name, 'rb');

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($name));

// dump the picture and stop the script
fpassthru($fp);
exit;
