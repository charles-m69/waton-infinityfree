<?php
session_start();
include('../connection.php');

$alert_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the admin ID and new admin level from the form
    $admin_id = $_POST['admin_id'];
    $new_admin_level = $_POST['admin_level'];

    if (in_array($new_admin_level, [1, 2, 3])) {
        $update_sql = "UPDATE `tbl_waton_admin` SET `admin_level` = '$new_admin_level', `admin_updated` = NOW() WHERE `admin_id` = '$admin_id'";

        if (mysqli_query($conn, $update_sql)) {
            session_start();
            $_SESSION['alert_msg'] = '<div class="alert alert-success" role="alert">Successfully changed role</div>';
            header("Location: ../manageadmin.php?success=1");
            exit();
        } else {
            echo 'Error updating admin level: ' . mysqli_error($conn);
        }
    } else {
        session_start();
        $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">Invalid admin level seleted</div>';
        exit();
    }
} else {
    session_start();
    $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">Invalid request method</div>';
    exit();
}
?>