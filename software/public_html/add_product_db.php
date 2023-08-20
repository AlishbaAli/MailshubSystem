
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
if (isset($_SESSION['ADP'])) {
    if ($_SESSION['ADP'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orgunit_id = trim($_POST["orgunit_id"]);
    $org_prod_type_id = trim($_POST["ptype"]);
    $product_name = trim($_POST["product_name"]);
    $product_code = trim($_POST["product_code"]);
    $discription = trim($_POST["product_desc"]);
    $ISSN = trim($_POST["ISSN"]);
    $E_ISSN = trim($_POST["E-ISSN"]);
    $ISBN = trim($_POST["ISBN"]);
    $E_ISBN = trim($_POST["E_ISBN"]);
    // $product_category = trim($_POST["product_category"]);
    // $product_type = trim($_POST["product_desc"]);
    // $product_cover = $_POST["product_desc"];
    $product_cover1 = $_FILES["product_cover"]["name"];
    $product_cover_url = trim($_POST["product_coverurl"]);
    $status = trim($_POST["status"]);

    $o="SELECT * FROM `products` where org_prod_type_id='$org_prod_type_id' and product_name = :product_name and product_code = :product_code";
    $o=$conn->prepare($o);
    $o->bindParam(":product_name", $product_name);
    $o->bindParam(":product_code", $product_code);
    $o->execute();
    if($o->rowCount()<1){


    $insert_query = " INSERT INTO products(org_prod_type_id, product_name, product_code, disp,`product_desc`, ISSN, `E-ISSN`, ISBN, E_ISBN, product_cover_url, status)
       VALUES (:org_prod_type_id, :product_name,:product_code, :disp, :disc, :ISSN, :E_ISSN, :ISBN, :E_ISBN, :product_cover_url, :status) ";

    $insert_stmt = $conn->prepare($insert_query);

    $insert_stmt->bindParam(":org_prod_type_id", $org_prod_type_id);
    $insert_stmt->bindParam(":product_name", $product_name);
    $insert_stmt->bindParam(":product_code", $product_code);
    $insert_stmt->bindParam(":disp", $disp);
    $insert_stmt->bindParam(":ISSN", $ISSN);
    $insert_stmt->bindParam(":E_ISSN", $E_ISSN);
    $insert_stmt->bindParam(":ISBN", $ISBN);
    $insert_stmt->bindParam(":E_ISBN", $E_ISBN);
    $insert_stmt->bindParam(":product_cover_url", $product_cover_url);
    $insert_stmt->bindParam(":disc", $discription);
    $insert_stmt->bindParam(":status", $status);
  
    // $insert_stmt->bindParam(":system_entityid", $system_entityid);
    // $insert_stmt->bindParam(":added_by", $_SESSION['username']);

    if ($insert_stmt->execute()) {

        // $PK = $conn->lastInsertId();
        // $date=date('Ymd');
         
         
        // $path = pathinfo($product_cover);
        // $file = $_FILES["product_cover"]["tmp_name"];
        // // $imageType = $sourceProperties[2];
        // $ext = $path['extension'];
  
        // $full_path= "product_cover/C".$PK."-".$product_code."-".$date.".".$ext;
        //  //to store in db
        // $file_name_cover="C".$PK."-".$product_code."-".$date.".".$ext;
  
        // move_uploaded_file($file, $full_path);


        // $update_query = "UPDATE products SET product_cover=:product_cover WHERE productid= :productid ";
        // $update_stmt = $conn->prepare($update_query);
        // $update_stmt->bindParam(":product_cover", $file_name_cover);
        
        // $update_stmt->bindParam(":productid",$PK);
        // $update_stmt->execute();
    //    -----------------------------------

    echo $product_cover = trim($_FILES["product_cover"]['name']);
    $date = date('Ymd');
    if (!empty($product_cover)) {

        $PK = $conn->lastInsertId();
        $file = $product_cover;
        $path = pathinfo($file);

        $ext = $path['extension'];
        $full_path = "product_cover/" . "C" . $PK . "-" . $product_code . "-" . $date . "." . $ext;
        //to store in db
        $file_name_cover = "C" . $PK . "-" . $product_code . "-" . $date . "." . $ext;

        move_uploaded_file($_FILES["product_cover"]["tmp_name"], $full_path);
        //move image path to db


        $update_query = "UPDATE products SET product_cover=:product_cover WHERE productid= :productid ";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(":product_cover", $file_name_cover);
        
        $update_stmt->bindParam(":productid",$PK);
        $update_stmt->execute();

        //  $roww=$update_stmt->fetch();
    }

    //    ---------------------------------------




        header("Location: add_product.php");
        exit();
    } } else {
        header("Location: add_product.php?flag=1");
        exit();
    }
}
?>