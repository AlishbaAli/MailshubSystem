<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['RQI'])) {
  if ($_SESSION['RQI'] == "NO") {

      //User not logged in. Redirect them back to the login page.
      header('Location: page-403.html');
      exit;
  }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

  if (isset($_POST["sendreq"]))                                 
  {
    $grid_id= $_POST['grid_id'];
    $req_institute= $_POST['institute_name'];
    $requested_by= $_SESSION['AdminId'];
    $orgunit_id= $_SESSION['orgunit_id'];
    $domains=$_POST['domain'];
    $status= "Pending";
      //checlk if institute already requested by this organization
      $sql = "SELECT  domain FROM request_institute INNER JOIN request_domain ON request_institute.`req_id`=request_domain.req_id
       WHERE req_institute = :req_institute AND orgunit_id=:orgunit_id AND request_institute.status=:status";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':req_institute', $req_institute);    
    $stmt->bindValue(':orgunit_id', $orgunit_id);       
    $stmt->bindValue(':status', $status);            
    $stmt->execute();
    $row = $stmt->fetchAll();
      //Already in pending
    $already_pending_domains = []; //to store already pending domains
    foreach ($row as $output) 
    {
      $already_pending_domains[] = $output['domain'];
    }
    $flag=0;
    foreach($domains as $domain)
    {
      if (!in_array($domain, $already_pending_domains))
      {
        $flag=1;
      }

    }
    if($flag==0)
    {
      $rinst="true";
      header("Location:request_institute.php?rinst={$rinst}");
      exit();
    }
    $stmt_insert=$conn->prepare("INSERT INTO request_institute(req_institute, grid_id, requested_by, orgunit_id, status, system_date) 
    VALUES(:req_institute, :grid_id, :requested_by, :orgunit_id, :status, NOW())");
    $stmt_insert->bindValue(':req_institute', $req_institute);
    $stmt_insert->bindValue(':grid_id', $grid_id);
    $stmt_insert->bindValue(':requested_by', $requested_by);
    $stmt_insert->bindValue(':orgunit_id', $orgunit_id);
    $stmt_insert->bindValue(':status', $status);
    if($stmt_insert->execute())
    {
      $req_id=$conn->lastInsertId();
      //insert requested domains
      foreach($domains as $domain)
      {
      $stmt_dom=$conn->prepare("INSERT INTO  request_domain(req_id, domain, status, system_date) VALUES(:req_id, :domain, :status, NOW())");
      $stmt_dom->bindValue(':req_id', $req_id);
      $stmt_dom->bindValue(':domain', $domain);
      $stmt_dom->bindValue(':status', $status);
      $stmt_dom->execute();
      }

      //check if institute already registered or not
      $stmt_reg_chk= $conn->prepare("SELECT ri_id, grid_id FROM registered_institutions WHERE grid_id=:grid_id");
      $stmt_reg_chk->bindValue(':grid_id',$grid_id);
      $stmt_reg_chk->execute();
      $ri_ids= $stmt_reg_chk->fetch();
      $ri_id= $ri_ids['ri_id'];
      if($stmt_reg_chk->rowCount()<1)
      {
        //register if not registered
        $stmt_reg= $conn->prepare("INSERT INTO registered_institutions(institute_name,grid_id,system_date) VALUES(:institute_name, :grid_id, NOW())");
        $stmt_reg->bindValue(':institute_name',$req_institute);
        $stmt_reg->bindValue(':grid_id',$grid_id);
        $stmt_reg->execute();
        $ri_id= $conn->lastInsertId();

      }

    //assign institute to org if not assigned
    
    $sql = "SELECT grid_id, registered_institutions.ri_id as ri_id FROM registered_institutions INNER JOIN organizational_institutes
    ON registered_institutions.ri_id = organizational_institutes.ri_id WHERE orgunit_id=:orgunit_id AND grid_id=:grid_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':grid_id', $grid_id);    
    $stmt->bindValue(':orgunit_id', $orgunit_id);                 
    $stmt->execute();
    //$ri_ids= $stmt->fetch();
    //$ri_id= $ri_ids['ri_id'];
    // echo $ri_id;


    // die();
    if($stmt->rowCount()<1)
    {
      $stmt_insert2= $conn->prepare("INSERT INTO organizational_institutes(orgunit_id,ri_id,system_date) VALUES(:orgunit_id,:ri_id, NOW()) ");
      $stmt_insert2->bindValue(':orgunit_id',$orgunit_id);
      $stmt_insert2->bindValue(':ri_id',$ri_id);
      $stmt_insert2->execute();
      
    }
      $inserted="true";
      header("Location:request_institute.php?inserted={$inserted}");
      exit();
      }
        

    }
}