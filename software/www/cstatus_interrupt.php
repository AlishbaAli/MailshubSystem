<?php
//file is used to make campaign status Interuptted (Mail Server Unavailable)
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
?>

<?php 
if (isset($_GET['CampID'])) {

    $CampID=$_GET['CampID'];

    $await="UPDATE campaign SET Camp_Status = 'Interuptted (Mail Server Unavailable)' 
            WHERE CampID=:CampID";
            $awstmt=$conn->prepare($await);
            $awstmt->bindParam(':CampID',$CampID);
        
            if(  $awstmt->execute())
         {
            $await2="INSERT INTO Campaign_flow SET (`CampID`, `Camp_Status`) VALUES ('$CampID','Interuptted (Mail Server Unavailable)' ) 
            WHERE CampID=:CampID";
            $awstmt2=$conn->prepare($await2);
            $awstmt2->bindParam(':CampID',$CampID);
        
            if(  $awstmt2->execute())
         {
          header('Location: index.php');
         }  
            

         }  
}

?>

