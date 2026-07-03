<?php
session_start();
include('../connection.php');

if (isset($_POST['prod_updated'])) {
    $prod_id = mysqli_real_escape_string($conn, $_POST['prod_id']);
    $prod_name = mysqli_real_escape_string($conn, $_POST['prod_name']);
    $prod_desc = mysqli_real_escape_string($conn, $_POST['prod_desc']);
    $prod_price = mysqli_real_escape_string($conn, $_POST['prod_price']);
    $prod_stock = mysqli_real_escape_string($conn, $_POST['prod_stock']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $current_image = mysqli_real_escape_string($conn, $_POST['current_image']);

    $prod_photo = $current_image;
    if (!empty($_FILES["prod_photo"]["name"])) {
        $target_dir = "../prodphoto/";
        $image_name = basename($_FILES["prod_photo"]["name"]);
        $target_file_path = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["prod_photo"]["tmp_name"], $target_file_path)) {
            $prod_photo = '../prodphoto/' . $image_name;
        }
    }

    $update_sql = "UPDATE `tbl_waton_product` SET 
        `prod_name`='$prod_name', 
        `prod_desc`='$prod_desc', 
        `prod_price`='$prod_price', 
        `prod_stock`='$prod_stock', 
        `prod_photo`='$prod_photo', 
        `category_id`='$category_id' 
        WHERE `prod_id`='$prod_id'";

    $update_result = mysqli_query($conn, $update_sql);
    if ($update_result) {
        session_start();
        $_SESSION['alert_prod'] = '<div class="alert alert-success" role="alert">
                        Changes saved to the product
                    </div>';
        header("Location: ../editprod.php");
        exit();
    } else {
        session_start();
        $_SESSION['alert_prod'] = '<div class="alert alert-danger" role="alert">
                                    Error: '.mysqli_error($conn).'
                                </div>';
        exit();
    }

    mysqli_close($conn);
}
?>