<?php
session_start();

$alert_msg = '';

if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])) {
    include('../connection.php');

    // Check if the selected admin ID is provided
    if (isset($_POST['id']) && isset($_POST['admin_password']) && isset($_POST['admin_new_password']) && isset($_POST['admin_retype_password'])) {
        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $admin_id = intval($_POST['id']); // Get the selected admin ID
        $admin_password = validate($_POST['admin_password']);
        $admin_new_password = validate($_POST['admin_new_password']);
        $admin_retype_password = validate($_POST['admin_retype_password']);

        if (empty($admin_password)) {
            Header("Location: ../changepassadmin.php?id=$admin_id&error=" . urlencode("Please type the current password to allow change"));
            exit();
        } else if (empty($admin_new_password)) {
            Header("Location: ../changepassadmin.php?id=$admin_id&error=" . urlencode("Please type the new password"));
            exit();
        } else if ($admin_new_password !== $admin_retype_password) {
            Header("Location: ../changepassadmin.php?id=$admin_id&error=" . urlencode("Passwords do not match"));
            exit();
        } else {
            $admin_password = md5($admin_password);
            $admin_new_password = md5($admin_new_password);
            
            // Check if the current password is correct for the selected admin
            $sql = "SELECT `admin_password` FROM `tbl_waton_admin` WHERE `admin_id`='$admin_id' AND `admin_password`='$admin_password'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) === 1) {
                // Update the password for the selected admin
                $update_sql = "UPDATE `tbl_waton_admin` SET `admin_password`='$admin_new_password' WHERE `admin_id`='$admin_id'";
                $update_result = mysqli_query($conn, $update_sql);

                if ($update_result) {
                    $_SESSION['alert_msg'] = '<div class="alert alert-success" role="alert">
                                        Password changed successfully for the selected admin.
                                    </div>';
                    Header("Location: ../manageadmin.php");
                    exit();
                } else {
                    Header("Location: ../changepassadmin.php?id=$admin_id&error=" . urlencode("Failed to update password"));
                    exit();
                }
            } else {
                Header("Location: ../changepassadmin.php?id=$admin_id&error=" . urlencode("Incorrect current password"));
                exit();
            }
        }
    } else {
        Header("Location: ../changepassadmin.php?id=$admin_id&error=" . urlencode("Invalid request"));
        exit();
    }
} else {
    Header("Location: settings.php");
    exit();
}
?>
