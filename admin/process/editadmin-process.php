<?php
    include("../connection.php");

    $alert_msg = '';
    $target_file_path = '';

    if(isset($_POST['admin_updated'])) {
        $id = $_POST['id'];
        $admin_username = mysqli_real_escape_string($conn, $_POST['admin_username']);
        $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
        $admin_photo = $_POST['current_image'];

        if(!empty($_FILES["admin_photo"]["name"])) {
            $target_dir = "../adminphoto/";
            $image_name = basename($_FILES["admin_photo"]["name"]);
            $target_file_path = $target_dir . $image_name;
        }

        if (move_uploaded_file($_FILES["admin_photo"]["tmp_name"], $target_file_path)) {
            $photo_alert = '<div class="alert alert-success" role="alert">
                                Photo set successfully
                            </div>';

            // $admin_photo = substr($target_file_path, 2);
            $admin_photo = 'adminphoto/' . $image_name;

        } else {
            $photo_alert = '<div class="alert alert-success" role="alert">
                                Photo set failed
                            </div>';
        }

        // Store credentials to database
        $sql = "UPDATE `tbl_waton_admin` SET `admin_username`='$admin_username',`admin_email`='$admin_email',`admin_photo`='$admin_photo' WHERE `admin_id`='$id'";

        $result = mysqli_query($conn, $sql);

        if ( $result ) {
            session_start();
            $_SESSION['alert_msg'] = '<div class="alert alert-success" role="alert">
                            Changes saved
                        </div>';
                        header('Location: ../manageadmin.php');
                        exit();
        } else {
            echo 'Error:'. $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn);