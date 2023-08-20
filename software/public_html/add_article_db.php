
<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: logout.php');
    exit;
}
if (isset($_SESSION['ADAT'])) {
    if ($_SESSION['ADAT'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orgunit_id = trim($_POST["orgunit_id"]);
    $org_prod_type_id = trim($_POST["ptype"]);
    $camp_id = trim($_POST["camp_id"]);

    $product_id = trim($_POST["productid"]);
    $title = trim($_POST['title']);
    $doi = trim($_POST['doi']);
    $url = trim($_POST['url']);
    $article_id = trim($_POST["article_id"]);
    $volume = trim($_POST["volume"]);
    $year = trim($_POST["year"]);
    $issue = trim($_POST["issue"]);
    $authors = trim($_POST["authors"]);
    $abstract = trim($_POST["abstract"]);
    $absurl = trim($_POST["absurl"]);
    $absurl_download = trim($_POST["absurl_download"]);
    
    // $product_category = trim($_POST["product_category"]);
    // $product_type = trim($_POST["product_desc"]);
    // $product_cover = $_POST["product_desc"];
    
    $status = trim($_POST["status"]);

    $o="SELECT * FROM `articles_scopus` where product_id='$product_id' and title = :title and doi=:doi and camp_id = :camp_id ";
    $o=$conn->prepare($o);
    $o->bindParam(":title", $title);
    $o->bindParam(":doi", $doi);
    $o->bindParam(":camp_id", $camp_id);
    $o->execute();
    if($o->rowCount()<1){

    $insert_query = " INSERT INTO articles_scopus(product_id, title, doi, camp_id ,`url`, volume, `year`, issue, authors, abstract, absurl,absurl_download, status)
       VALUES (:product_id, :title,:doi, :camp_id, :url, :volume, :year, :issue, :authors, :abstract, :absurl, :absurl_download, :status) ";

    $insert_stmt = $conn->prepare($insert_query);

    $insert_stmt->bindParam(":product_id", $product_id);
    $insert_stmt->bindParam(":title", $title);
    $insert_stmt->bindParam(":doi", $doi);
    $insert_stmt->bindParam(":camp_id", $camp_id);
    $insert_stmt->bindParam(":url", $url);
    $insert_stmt->bindParam(":volume", $volume);
    $insert_stmt->bindParam(":year", $year);
    $insert_stmt->bindParam(":issue", $issue);
    $insert_stmt->bindParam(":authors", $authors);
    $insert_stmt->bindParam(":abstract", $abstract);
    $insert_stmt->bindParam(":absurl", $absurl);
    $insert_stmt->bindParam(":absurl_download", $absurl_download);
    $insert_stmt->bindParam(":status", $status);
  
    // $insert_stmt->bindParam(":system_entityid", $system_entityid);
    // $insert_stmt->bindParam(":added_by", $_SESSION['username']);

    if ($insert_stmt->execute()) {

    

    // echo $product_cover = trim($_FILES["product_cover"]['name']);
    // $date = date('Ymd');
    // if (!empty($product_cover)) {

    //     $PK = $conn->lastInsertId();
    //     $file = $product_cover;
    //     $path = pathinfo($file);

    //     $ext = $path['extension'];
    //     $full_path = "product_cover/" . "C" . $PK . "-" . $product_code . "-" . $date . "." . $ext;
    //     //to store in db
    //     $file_name_cover = "C" . $PK . "-" . $product_code . "-" . $date . "." . $ext;

    //     move_uploaded_file($_FILES["product_cover"]["tmp_name"], $full_path);
    //     //move image path to db


    //     $update_query = "UPDATE products SET product_cover=:product_cover WHERE productid= :productid ";
    //     $update_stmt = $conn->prepare($update_query);
    //     $update_stmt->bindParam(":product_cover", $file_name_cover);
        
    //     $update_stmt->bindParam(":productid",$PK);
    //     $update_stmt->execute();

    //     //  $roww=$update_stmt->fetch();
    // }

    //    ---------------------------------------




        header("Location: add_product.php");
        exit();
    } } else {
        header("Location: add_product.php?flag=1");
        exit();
    }
}
?>