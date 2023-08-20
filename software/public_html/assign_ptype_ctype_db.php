
<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['ASPC'])) {
    if ($_SESSION['ASPC'] == "NO") {

        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
}
$role = $role_tenure = $role_code = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    

    $orgunit_id = trim($_POST["orgunit_id"]);
    $ou_pd_id = trim($_POST["ptype"]);
    $o_ct_id = trim($_POST["ctype"]);
    $status = trim($_POST["status"]);

    $o="SELECT * from org_ptype_ctype  where ou_pd_id='$ou_pd_id' and o_ct_id='$o_ct_id'";
    $o=$conn->prepare($o);
    $o->execute();
if ($o->rowCount()<1) {

    $insert_query = " INSERT INTO org_ptype_ctype(ou_pd_id, o_ct_id, orgunit_id, status)
       VALUES (:ou_pd_id, :o_ct_id, :orgunit_id, :status) ";

    $insert_stmt = $conn->prepare($insert_query);

    $insert_stmt->bindParam(":ou_pd_id", $ou_pd_id);
    $insert_stmt->bindParam(":o_ct_id", $o_ct_id);
    $insert_stmt->bindParam(":orgunit_id", $orgunit_id);
   
    $insert_stmt->bindParam(":status", $status);
  
    // $insert_stmt->bindParam(":system_entityid", $system_entityid);
    // $insert_stmt->bindParam(":added_by", $_SESSION['username']);

    if ($insert_stmt->execute()) {
        header("Location: assign_ptype_ctype.php");
        exit();
    } } else{
        header("Location: assign_ptype_ctype.php?flag=1");
        exit();
    }
}
?>