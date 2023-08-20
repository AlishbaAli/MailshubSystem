<?php
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
if (isset($_POST['submit'])) {

    $CampID=$_POST['CampID'];

    $await="UPDATE activity SET add_authordata = '2' 
            WHERE CampID=:CampID";
            $awstmt=$conn->prepare($await);
            $awstmt->bindParam(':CampID',$CampID);
        
            if(  $awstmt->execute())
         {
          header('Location: index.php');
             // fetchdata activity 
            // <script>
            // function myFunction() {
            //   setTimeout(function(){ alert("Hello"); }, 300000);
            // }
            //   </script>
            //   <p myFunction()></p>

            
            // sleep(100);
            // $await="UPDATE activity SET add_authordata = '1' 
            // WHERE CampID=:CampID";
            // $awstmt=$conn->prepare($await);
            // $awstmt->bindParam(':CampID',$CampID);
            // if(  $awstmt->execute()) 
            // {
            //   $await="UPDATE campaign SET Camp_Status = 'Active' 
            //   WHERE CampID=:CampID";
            //   $awstmt=$conn->prepare($await);
            //   $awstmt->bindParam(':CampID',$CampID);
            //   if(  $awstmt->execute()) 
            //   {
            //     header('Location: index.php');
            //    }
            // }
            
           


         }  
}

?>

