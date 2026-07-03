<?php
    session_start();

    $alert_msg = '';

    if(isset($_SESSION['admin_id']) &&  isset($_SESSION['admin_username'])) {
        include('../connection.php');

        if(isset($_POST['admin_password']) && isset($_POST['admin_new_password']) && isset($_POST['admin_retype_password'])){
            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $admin_password = validate($_POST['admin_password']);
            $admin_new_password = validate($_POST['admin_new_password']);
            $admin_retype_password = validate($_POST['admin_retype_password']);

            if(empty($admin_password)) {
                Header("Location: ../changepassword.php?error=" . urlencode("Please type your current password to allow change"));
                exit();
            } else if(empty($admin_new_password)) {
                Header("Location: ../changepassword.php?error=" . urlencode("Please type your new password"));
                exit();
            } else if($admin_new_password !== $admin_retype_password) {
                Header("Location: ../changepassword.php?error=" . urlencode("Password does not match"));
                exit();
            } else {
                $admin_password = md5($admin_password);
                $admin_new_password = md5($admin_new_password);
                $admin_id = $_SESSION['admin_id'];
            
                $sql = "SELECT `admin_password` FROM `tbl_waton_admin` WHERE `admin_id`='$admin_id' AND `admin_password`='$admin_password'";
            
                $result = mysqli_query($conn, $sql);
            
                if(mysqli_num_rows($result) === 1) {
                    $update_sql = "UPDATE `tbl_waton_admin` SET `admin_password`='$admin_new_password' WHERE `admin_id`='$admin_id'";
                    $update_result = mysqli_query($conn, $update_sql);
            
                    if($update_result) {
                        $_SESSION = array();

                        session_destroy();

                        session_start();
                        $_SESSION['alert_msg'] = '<div class="alert alert-success" role="alert">
                                        Password changed sucessfully, please log in with your new password.
                                    </div>';

                        Header("Location: ../login.php");
                        exit();
                    }
                } else {
                    Header("Location: ../changepassword.php?error=" . urlencode("Incorrect password"));
                    exit();
                }
            }

        } else {
            Header("Location: changepassword.php");
            exit();
        }

    } else {
        Header("Location: settings.php");
        exit();
    }
?>