<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    $sql = "DELETE FROM `tbl_waton_admin` WHERE `admin_id` = $admin_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: manageadmin.php");
        exit();
    } else {
        echo 'Error deleting admin: ' . mysqli_error($conn);
    }
} else {
    echo 'Admin not found.';
}

mysqli_close($conn);
?>
