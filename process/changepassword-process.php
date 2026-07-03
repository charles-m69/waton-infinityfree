<?php
    session_start();

    if(isset($_SESSION['user_id']) &&  isset($_SESSION['username'])) {
        include('../connection.php');

        if(isset($_POST['user_password']) && isset($_POST['user_new_password']) && isset($_POST['user_retype_password'])){
            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $user_password = validate($_POST['user_password']);
            $user_new_password = validate($_POST['user_new_password']);
            $user_retype_password = validate($_POST['user_retype_password']);

            if(empty($user_password)) {
                Header("Location: ../changepassword.php?error=" . urlencode("Please type your current password to allow change"));
                exit();
            } else if(empty($user_new_password)) {
                Header("Location: ../changepassword.php?error=" . urlencode("Please type your new password"));
                exit();
            } else if($user_new_password !== $user_retype_password) {
                Header("Location: ../changepassword.php?error=" . urlencode("Password does not match"));
                exit();
            } else {
                $user_password = md5($user_password);
                $user_new_password = md5($user_new_password);
                $user_id = $_SESSION['user_id'];
            
                $sql = "SELECT `user_password` FROM `tbl_waton_user` WHERE `user_id`='$user_id' AND `user_password`='$user_password'";
            
                $result = mysqli_query($conn, $sql);
            
                if(mysqli_num_rows($result) === 1) {
                    $update_sql = "UPDATE `tbl_waton_user` SET `user_password`='$user_new_password' WHERE `user_id`='$user_id'";
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