<?php
  ob_start();
   session_start();
  // error_reporting(E_ALL);
  // ini_set('display_errors', 1);
  
  include 'include/conn.php';	
	
if(!isset($_SESSION['AdminId']))
{
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_REQUEST["term"])) {
    // create prepared statement
    $query = "SELECT * FROM registered_institutions WHERE institute_name LIKE :term";
    $statement = $conn->prepare($query);
    $term = $_REQUEST["term"] . '%';
    $statement->bindParam(":term", $term);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        while ($row = $statement->fetch()) {
      
            echo "<p>" . $row["institute_name"] . "</p>";
        }
    } else {
        echo "<p>No matches found</p>";
    }
}

?>