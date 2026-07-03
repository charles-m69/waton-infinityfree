<?php
    session_start();

    include('../connection.php');

    $photo_alert = "";

    if(isset($_POST['category_added'])) {
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        $category_desc = mysqli_real_escape_string($conn, $_POST['category_desc']);

        $target_dir = "../../catphoto/";
        $image_name = basename($_FILES["category_photo"]["name"]);
        $target_file_path = $target_dir . $image_name;

        if(move_uploaded_file($_FILES["category_photo"]["tmp_name"], $target_file_path)) {
            $photo_alert = '<div class="alert alert-success" role="alert">
                                        Photo set successfully
                            </div>';

            $category_photo = '../../catphoto/' . $image_name;
            $insert_sql = "INSERT INTO `tbl_waton_category`(`category_name`, `category_photo`, `category_desc`) VALUES ('$category_name', '$category_photo', '$category_desc')";

            $insert_result = mysqli_query($conn, $insert_sql);

            if($insert_result) {
                session_start();
                $_SESSION['alert_cat'] = '<div class="alert alert-success" role="alert">
                                            Category added.
                                        </div>';
                header("Location: ". BASE_URL);
                exit();
            } else {
                session_start();
                $_SESSION['alert_cat'] = '<div class="alert alert-danger" role="alert">
                                            Error: '.mysqli_error($conn).'
                                        </div>';
                header("Location: ". BASE_URL);
                exit();
            }
        } else {
            session_start();
            $_SESSION['alert_cat'] = '<div class="alert alert-danger" role="alert">
                                        Failed, category photo is required'.mysqli_error($conn).'
                                    </div>';
            header("Location: ". BASE_URL);
            exit();
        }

        
        mysqli_close($conn);
    }
?>