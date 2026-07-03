<?php
    session_start();
    include('connection.php');

    if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])) {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Delete the account from the database
            $sql = "DELETE FROM `tbl_waton_admin` WHERE `admin_id` = '$id'";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                // Unset all session variables
                $_SESSION = array();

                // Destroy the session
                session_destroy();
                
                // Redirect to the login page or settings page after deletion
                header("Location: login.php?alert=Account deleted successfully.");
                exit();
            } else {
                // Handle error if deletion fails
                header("Location: settings.php?alert=Failed to delete account. Please try again.");
                exit();
            }
        }
    } else {
        // Redirect if the user is not logged in
        header("Location: login.php");
        exit();
    }
?>
