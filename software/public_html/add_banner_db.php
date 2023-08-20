<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['BINB'])) {
    if ($_SESSION['BINB'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    
    $page_name= $_POST['page_name'];
    $date = date('Ymd');
    $hyperlink= $_POST['hyperlink'];
    $origional_name = $_FILES["banner_image"]["name"];
    $tempname = $_FILES["banner_image"]["tmp_name"];
    $orgunit_id= $_POST['orgunit_id'];  
    $stmt_org_code= $conn->prepare("SELECT orgunit_code FROM tbl_organizational_unit WHERE orgunit_id='$orgunit_id'");
    $stmt_org_code->execute();
    $org_code= $stmt_org_code->fetch();

    //Insert in database
    $insert_stmt= $conn->prepare("INSERT INTO banners(orgunit_id, image_hyperlink) VALUES(:orgunit_id, :image_hyperlink)");
    $insert_stmt->bindValue(':orgunit_id', $orgunit_id);
    $insert_stmt->bindValue(':image_hyperlink', $hyperlink);
    $insert_stmt->execute();
    $banner_id = $conn->lastInsertId();


    $ext = pathinfo($origional_name, PATHINFO_EXTENSION);
    $target_dir='banners/';
    $dir_name= $org_code['orgunit_code'];
    if (!file_exists($target_dir.$dir_name)) {
        mkdir($target_dir.$dir_name, 0777, true);
    }
    $image_name= $banner_id.'-'.$date.'.'.$ext;
    $image_url= $target_dir.$dir_name.'/'.$image_name;

 if (move_uploaded_file($tempname, $image_url))  {
            $msg = "Image uploaded successfully";
        }else{
            $msg = "Failed to upload image";
      }

    $update_stmt= $conn->prepare("UPDATE banners SET image_name=:image_name, image_url=:image_url WHERE banner_id=:banner_id");
    $update_stmt->bindValue(':image_name', $image_name);
    $update_stmt->bindValue(':image_url', $image_url);
    $update_stmt->bindValue(':banner_id', $banner_id);
    $update_stmt->execute();

      header("Location: $page_name");
       exit();


   

}
?>