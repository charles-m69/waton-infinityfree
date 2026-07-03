<?php
include("../connection.php");

if (isset($_POST['category_updated'])) {
    $cat_id = $_POST['category_id'];
    $cat_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $cat_desc = mysqli_real_escape_string($conn, $_POST['category_desc']);
    $current_image = mysqli_real_escape_string($conn, $_POST['current_image']);
    $category_photo = $current_image;

    if (!empty($_FILES["category_photo"]["name"])) {
        $target_dir = "../../catphoto/";
        $image_name = basename($_FILES["category_photo"]["name"]);
        $target_file_path = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["category_photo"]["tmp_name"], $target_file_path)) {
            $category_photo = '../../catphoto/' . $image_name;
        }
    }
    
    $sql = "UPDATE `tbl_waton_category` SET `category_name`='$cat_name', `category_photo`='$category_photo', `category_desc`='$cat_desc', `category_updated` = NOW() WHERE `category_id`='$cat_id'";

    if (mysqli_query($conn, $sql)) {
        session_start();
        $_SESSION['alert_cat'] = '<div class="alert alert-success" role="alert">
                                    Category edited.
                                </div>';
        header("Location: ". BASE_URL);
        exit();
    } else {
        session_start();
        $_SESSION['alert_cat'] = '<div class="alert alert-danger" role="alert">
                                    Error updating category: '.mysqli_error($conn).'
                                </div>';
        exit();
    }
    
    
}
mysqli_close($conn);
?>
