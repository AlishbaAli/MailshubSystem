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
if (isset($_SESSION['AO'])) {
  if ($_SESSION['AO'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $orgunit_name = trim($_POST["orgunit_name"]);
  $orgunit_code = trim($_POST["orgunit_code"]);
  $system_entityid = trim($_POST["system_entityid"]);
  if ($system_entityid==3){
    $system_setting ='sys-defined';
  } else { $system_setting = trim($_POST["system_setting"]); }
  $orgunit_status = trim($_POST["orgunit_status"]);


  // my code
  $sql = "SELECT COUNT(orgunit_name) AS num
  FROM  tbl_organizational_unit
  WHERE  orgunit_name=:orgunit_name OR orgunit_code=:orgunit_code";

  $stmt = $conn->prepare($sql);

//Bind the provided username to our prepared statement.
$stmt->bindValue(':orgunit_name', $orgunit_name);          
$stmt->bindValue(':orgunit_code', $orgunit_code);         

//Execute.
$stmt->execute();

//Fetch the row.
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row['num'] > 0)
{


  //organization already exist
  $O_exist= "true";

  header("Location:orgunit_form.php?O_exist={$O_exist}");
  exit();





}

else 
{  
   



  $ins_stmt = $conn->prepare("INSERT INTO  tbl_organizational_unit (orgunit_name, orgunit_code, system_setting, system_entityid,orgunit_status)
  VALUES (:orgunit_name, :orgunit_code, :system_setting, :system_entityid, :orgunit_status)");
  $ins_stmt->bindValue(':orgunit_name', $orgunit_name);
  $ins_stmt->bindValue(':orgunit_code', $orgunit_code);
  $ins_stmt->bindValue(':system_entityid', $system_entityid);
  $ins_stmt->bindValue(':system_setting', $system_setting);
  $ins_stmt->bindValue(':orgunit_status', $orgunit_status);

  if ($ins_stmt->execute()) {
    //insert level 0 role to newly added organization
    $org_id = $conn->lastInsertId();

    $stmtr = $conn->prepare("SELECT role_prev_id FROM tbl_role_privilege WHERE restriction_level = '0'");
    $stmtr->execute();
    $role_id = $stmtr->fetch();
    $rid = $role_id['role_prev_id'];

    $stmt = $conn->prepare("INSERT INTO orgunit_role_prev (role_prev_id, orgunit_id)
      VALUES(:role_prev_id, :orgunit_id)");
    $stmt->bindValue(':role_prev_id', $rid);
    $stmt->bindValue(':orgunit_id', $org_id);
    $stmt->execute();

   ///insert tecnical / organization admin
    
      $stmtr = $conn->prepare("SELECT role_prev_id FROM tbl_role_privilege WHERE restriction_level = '1' and system_entityid='$system_entityid'");
      $stmtr->execute();
      $role_id = $stmtr->fetch();
      $rid = $role_id['role_prev_id'];
  
      $stmt = $conn->prepare("INSERT INTO orgunit_role_prev (role_prev_id, orgunit_id)
        VALUES(:role_prev_id, :orgunit_id)");
      $stmt->bindValue(':role_prev_id', $rid);
      $stmt->bindValue(':orgunit_id', $org_id);
      $stmt->execute(); 
      if ($system_entityid==2){
        $uname=$orgunit_code."-Proxy Admin";
        $stmt = $conn->prepare("INSERT INTO admin (username, email,status)
        VALUES(:username, 'NA','Active')");
        $stmt->bindValue(':username', $uname);
        $stmt->execute(); 
     
        $adm_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO tbl_orgunit_user (orgunit_id, user_id)
        VALUES(:org,:adm)");
        $stmt->bindValue(':org', $org_id);
        $stmt->bindValue(':adm', $adm_id);
        $stmt->execute(); 
     
      } 
    

    header("Location: orgunit_form.php");
    exit();
  }
}

}