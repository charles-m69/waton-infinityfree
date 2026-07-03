<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    $nullprodcat = "UPDATE `tbl_waton_product` SET `category_id` = NULL WHERE `category_id` = $category_id";
    mysqli_query($conn, $nullprodcat);

    $sql = "DELETE FROM `tbl_waton_category` WHERE `category_id` = $category_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        session_start();
        $_SESSION['alert_cat'] = '<div class="alert alert-success" role="alert">
                                    Category deleted.
                                </div>';
        header("Location: ". BASE_URL);
        exit();
    } else {
        session_start();
        $_SESSION['alert_cat'] = '<div class="alert alert-danger" role="alert">
                                    Error deleting category: '.mysqli_error($conn).'
                                </div>';
        exit();
    }
} else {
    session_start();
    $_SESSION['alert_cat'] = '<div class="alert alert-danger" role="alert">
                                No category specified
                            </div>';
    exit();
}

mysqli_close($conn);
?>
