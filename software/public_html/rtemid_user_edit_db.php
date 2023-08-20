<?php
ob_start();
session_start();
include 'include/conn.php';

if (!isset($_SESSION['AdminId'])) {
    //User not logged in. Redirect them back to the login page.
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['MRU'])) {
    if ($_SESSION['MRU'] == "NO") {
  
        //User not logged in. Redirect them back to the login page.
        header('Location: page-403.html');
        exit;
    }
  }



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["id"];
    $reply_to_emails_input = $_POST['reply_to_emails'];


    $stmt = $conn->prepare("SELECT reply_to_emails.rtemid, reply_to_email FROM reply_to_emails 
INNER JOIN tbl_user_rte ON reply_to_emails.rtemid = tbl_user_rte.rtemid 
WHERE tbl_user_rte.user_id=$user_id ");
    $stmt->execute();
    $row = $stmt->fetchAll();

    $reply_to_emails_values = []; //to store already assigned reply_to_emails
    foreach ($row as $output) {

        $reply_to_emails_values[] = $output['rtemid'];
    }

    //Insert Newly added reply_to_emails

    foreach ($reply_to_emails_input as $input_val) {
        if (!in_array($input_val, $reply_to_emails_values)) {


            $ins_stmt = $conn->prepare("INSERT INTO   tbl_user_rte (rtemid, user_id) 
        VALUES (:rtemid, :user_id)");
            $ins_stmt->bindValue(':rtemid', $input_val);
            $ins_stmt->bindValue(':user_id', $user_id);
            $ins_stmt->execute();
        }
    }
    //Delete assigned reply_to_emails

    foreach ($reply_to_emails_values as $reply_to_emails_values_row) {

        if (!in_array($reply_to_emails_values_row, $reply_to_emails_input)) {
            // echo $role_row."Delete this one";

            $sql = "DELETE FROM tbl_user_rte WHERE rtemid= $reply_to_emails_values_row AND user_id= $user_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
        }
    }

    header("Location: reply_to_email_form_users.php");
    exit();
}
