<?php
    include("../connection.php");

    $alert_msg = '';
    $target_file_path = '';

    if(isset($_POST['user_updated'])) {
        $id = $_POST['id'];
        $user_fname = mysqli_real_escape_string($conn, $_POST['user_fname']);
        $user_lname = mysqli_real_escape_string($conn, $_POST['user_lname']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
        $user_photo = $_POST['current_image'];

        if(!empty($_FILES["user_photo"]["name"])) {
            $target_dir = "../userphoto/";
            $image_name = basename($_FILES["user_photo"]["name"]);
            $target_file_path = $target_dir . $image_name;
        }

        if (move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target_file_path)) {
            $photo_alert = '<div class="alert alert-success" role="alert">
                                Photo set successfully
                            </div>';

            $user_photo = 'userphoto/' . $image_name;

        } else {
            $photo_alert = '<div class="alert alert-success" role="alert">
                                Photo set failed
                            </div>';
        }

        // Store credentials to database
        $sql = "UPDATE `tbl_waton_user` SET `user_fname`='$user_fname',`user_lname`='$user_lname',`username`='$username',`user_email`='$user_email',`user_photo`='$user_photo',`user_updated`=NOW() WHERE `user_id`='$id'";

        $result = mysqli_query($conn, $sql);

        if ( $result ) {
            session_start();
            $_SESSION['alert_msg'] = '<div class="alert alert-success" role="alert">
                            Changes saved
                        </div>';
                        header('Location: ../settings.php?id='.$id);
                        exit();
        } else {
            echo 'Error:'. $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn);