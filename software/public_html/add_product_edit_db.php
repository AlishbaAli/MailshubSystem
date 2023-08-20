<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if(!isset($_SESSION['AdminId'])) {
  //User not logged in. Redirect them back to the login page.
  header('Location: login.php');
  exit;
}
if (isset($_SESSION['ADPE'])) {
  if ($_SESSION['ADPE'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $product_name = trim($_POST["product_name"]);
    $product_code = trim($_POST["product_code"]);
    $disp = trim($_POST["disp"]);
    $discription = trim($_POST["product_desc"]);
    $ISSN = trim($_POST["ISSN"]);
    $E_ISSN = trim($_POST["E_ISSN"]);
    $ISBN = trim($_POST["ISBN"]);
    $E_ISBN = trim($_POST["E_ISBN"]);
    $product_cover_old= trim($_POST["product_cover_old"]);
    // $product_category = trim($_POST["product_category"]);
    // $product_type = trim($_POST["product_desc"]);
    // $product_cover = $_POST["product_desc"];
    $product_cover1 = $_FILES["product_cover"]["name"];
    // echo  "trt ertertre" . $product_cover1;
    $product_cover_url = trim($_POST["product_coverurl"]);
    $status = trim($_POST["status"]);
    $product_ID  = trim($_POST["product_Id"]);
   


if (!empty($product_cover1) || $product_cover1!= null)
  
{                                                 
   echo $name = "journals" . $product_ID;
   $date = date('Ymd');
                                                    // $product_cover = trim($_FILES["product_cover"]['name']);
                                                    $path = pathinfo($product_cover1);
                                                    $file = $_FILES["product_cover"]["name"];
                                                    // $imageType = $sourceProperties[2];
                                                    $ext = $path['extension'];

                                                    $full_path = "product_cover/" . $product_code . "-" . $product_ID . "-" . $date ." ." . $ext;
                                                    //to store in db
                                                    $file_name_cover = $product_code . "-" . $product_ID . "-" . $date .  " ." . $ext;

                                                    move_uploaded_file($_FILES["product_cover"]["tmp_name"], $full_path);
} else {
    echo $file_name_cover = $product_cover_old;
} 


// die();



    $update_stmt = $conn->prepare('UPDATE `products` SET `product_name`=:product_name,`product_code`=:product_code,
    `disp`=:disp,`product_desc`=:product_desc,`ISSN`=:ISSN,`E-ISSN`=:E_ISSN,`ISBN`=:ISBN,`E_ISBN`=:E_ISBN,`product_cover`=:product_cover,
    `product_cover_url`=:product_cover_url,`status`=:status WHERE productid = :productid');
    $update_stmt->bindValue(':productid', $product_ID);
    $update_stmt->bindValue(':product_name', $product_name);
    $update_stmt->bindValue(':product_code', $product_code);
    $update_stmt->bindValue(':disp', $disp);
    $update_stmt->bindValue(':product_desc', $discription);
    $update_stmt->bindValue(':ISSN', $ISSN);
    $update_stmt->bindValue(':E_ISSN', $E_ISSN);
    $update_stmt->bindValue(':ISBN', $ISBN);
    $update_stmt->bindValue(':E_ISBN', $E_ISBN);
    $update_stmt->bindValue(':product_cover',  $file_name_cover);
    $update_stmt->bindValue(':product_cover_url', $product_cover_url);
    $update_stmt->bindValue(':status', $status);
    if ($update_stmt->execute()) {

        header("Location: add_product.php");
        exit();
      }

}
 

?>