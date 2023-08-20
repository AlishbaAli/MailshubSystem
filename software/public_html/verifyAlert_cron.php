<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//require 'public_html/include/conn.php';
require 'include/conn.php';

//get all system admins email ids
$to_emails="";
$stmt_to = $conn->prepare("SELECT u.email AS email, r.role_prev_title as role_title FROM admin AS u INNER JOIN tbl_user_role_prev AS ur
INNER JOIN tbl_role_privilege AS r ON u.AdminId = ur.user_id AND ur.role_prev_id = r.role_prev_id WHERE
r.role_prev_title= 'Verification Manager'  AND u.email_status='Verified'
");
$emails = $stmt_to->execute();
$emails = $stmt_to->fetchAll();

foreach ($emails as $to) {
    //$to_emails[] .= $to['email'];
    //echo $to['email'];
    $to_emails.=$to['email'].",";
}

// $to_emails = implode(",", $emails);
// print_r($to_emails);
$to_emails=trim($to_emails,","); echo $to_emails;
//Select All Pending Verification Alert Campaigns

$stmt1 = $conn->prepare("SELECT *
FROM campaign
WHERE Camp_Status ='Pending Verification Alert' AND crtem_status='Active'");
$result1 = $stmt1->execute();

$result1 = $stmt1->fetchAll();

if ($stmt1->rowCount() > 0) {

    $CampIDs = array();
    foreach ($result1 as $row1) {
        $CampID = $row1['CampID'];
// //-------------------------------
// $message_oaarticles="";
// include "verify_alert(3).php";
// //---------------------------------
///------------------------------------ 
        $CampIDs[] .= $row1['CampID'];

        $article_title = '{article_title}';
        $Journal_title = '{Journal_title}';
        $first_name='{first_name}';
        $last_name='{last_name}';

        $sql_get = "SELECT
        a.Fname,
        a.Lastname,
        a.Journal_title,
        a.article_title,
        c.CampName,
        c.mailserverid,
        d.camp_alert
        FROM
        `admin` a, tbl_orgunit_user ou, campaign c, camp_alerts d
        WHERE
        ou.user_id=a.AdminId and ou.ou_id = c.ou_id AND d.CampID = c.CampID AND
        c.CampID = :CampID";
        $stmt_get = $conn->prepare($sql_get);
        $stmt_get->bindValue(':CampID', $CampID);
        $result_get = $stmt_get->execute();
        if ($stmt_get->rowCount() > 0) {
            $result_get = $stmt_get->fetchAll();

            foreach ($result_get as $row_get) {
                $article_title = trim($row_get['article_title']);
                $Journal_title = trim($row_get['Journal_title']);
             

              $Draft_tags = ["{article_title}", "{Journal_title}","{first_name}","{last_name}"];

                echo "<div style='border: 1px solid black; width:70%; padding:20px; background-color:#bababb17;'>";
             
                //,$name="Dear Dr. " . $row_get['fname'] . " " . $row_get['Lastname'] . ",";
               
               
               // echo "Dear Dr. " . $row_get['Fname'] . " " . $row_get['Lastname'] . ","; echo "<br/></br/>";

                $cmp_draft = html_entity_decode(htmlspecialchars_decode($row_get['camp_alert']));
                $DB_Rows   = [$article_title, $Journal_title, $first_name, $last_name];
                $cmp_draft_new = str_replace($Draft_tags, $DB_Rows, $cmp_draft);
                echo $cmp_draft_new;

                echo "</div>";
                echo "<br/>";

                /* $AdminEmail = $_SESSION['email'];
                $to = $AdminEmail; */
                $mailserverid = trim($row_get['mailserverid']);

                //from_address
                $sql_get = "SELECT
                emailaddress
                FROM
                current_mailserver_config
                WHERE mailserverid=$mailserverid";

                $stmt_get = $conn->prepare($sql_get);
                $stmt_get->execute();
                $row = $stmt_get->fetch();

                $to = $to_emails;
                $subject = "Verify - " . $row_get['CampName'];
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                //$headers .= 'From:Articles Contributions<editorial@specialissueeditor.net>'."\r\n"; root@mailshub.net
                //$headers .= "From:Mailshub<" . $row['emailaddress'];
                $headers .= "From:Mailshub<root@mailshub.net
                >" . "\r\n";
                //$headers .= 'Cc:qasit@benthamscience.net, faisal@benthamscience.net'."\r\n";


// $message_oaarticles="";
// $ctype_article_list=$row['ctype_article_list'] ;    
// include "verify_alert(3).php";
// //---------------------------------

$draftalert= $cmp_draft_new;
$message = "<html>
    </body>
        <div style=' width:85%; padding:20px;text-align: justify;'>
        <p style='text-align:justify;'>$draftalert</p>
     
        </div>
        </body>
</html>";

                echo "<br/>" . "$message" . "<br/>";

               // mail($to, $subject, $message, $headers);

            }
        }
    }
    $CampIDs = implode(",", $CampIDs);

    //update status
    $stmt_update_status = $conn->prepare("UPDATE `campaign`
SET `Camp_Status` = 'Pending Verification by Admin'
WHERE CampID IN ($CampIDs)");
    $stmt_update_status->execute();
}
