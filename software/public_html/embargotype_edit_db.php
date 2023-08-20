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





if(isset($_POST['submit1']))
{

     $status=trim($_POST['status']); 
     $id=trim($_POST['id']); 

    $day="UPDATE `embargotype` SET `embargotype_status`=:status WHERE embargotype_id=:id ";

    $day=$conn->prepare($day);

    $day->bindParam(':status',$status);
    $day->bindParam(':id',$id);

    if ($day->execute())
    {
        header('Location: embargotype.php');
        exit;
    }
    
}


if(isset($_POST['submit2']))
{

    // echo $status=trim($_POST['status']); 
     $org_id=trim($_POST['id']); 
    $days_id=$_POST['days_id'];
    
    //print_r($days_id); echo"<br>";

    $stmt = $conn->prepare("SELECT embargotype_org_status,orgunit_name,GROUP_CONCAT(allowed_days SEPARATOR ',') as all_days,
    GROUP_CONCAT(embargotype.embargotype_id  SEPARATOR ',') as days_id 
    FROM tbl_organizational_unit 
    left join `embargotype_org`  on tbl_organizational_unit.orgunit_id = embargotype_org.orgunit_id 
    left join embargotype on embargotype_org.embargotype_id = embargotype.embargotype_id 
    WHERE tbl_organizational_unit.orgunit_id= :id and ( embargotype_org_status='Active' or embargotype_org_status is null) group by orgunit_name");

    
$stmt->bindParam(':id',$org_id);
$stmt->execute();
    $row1 = $stmt->fetch();

    if(!empty($row1['all_days'])) {
        $all_days=$row1['all_days'];
        $arr=explode(',',$all_days);
        } else {
            $all_days="";
            $arr=[];
        }
        
        if(!empty($row1['days_id'])) {
        $day_id=$row1['days_id'];

        $arr2=explode(',',$day_id); //echo"<br>";

      //  print_r($arr2);
        } else {
            $day_id="";
            $arr2=[];
        }

     //Insert Newly added roles

    foreach ($days_id as $input_val) {
        if (!in_array($input_val, $arr2)) {
           // echo $input_val;
            // echo $input_val."insert here";
            $ins_stmt = $conn->prepare("INSERT INTO  embargotype_org (orgunit_id, embargotype_id) 
        VALUES (:orgunit_id, :embargotype_id)");
            $ins_stmt->bindValue(':orgunit_id', $org_id);
            $ins_stmt->bindValue(':embargotype_id', $input_val);
            $ins_stmt->execute();
        }
    }

    //Delete assigned roles

    foreach ($arr2 as $all_days_id) {
        if (!in_array($all_days_id, $days_id)) {

//echo $all_days_id;
            $sql = "DELETE FROM embargotype_org WHERE embargotype_id= '$all_days_id' AND orgunit_id= '$org_id'";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: embargotype.php");
    exit();
    
}

?>