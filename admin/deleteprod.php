<?php
session_start();
include("connection.php");


if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $prod_id = $_GET['id'];

    $sql = "DELETE FROM `tbl_waton_product` WHERE `prod_id` = $prod_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        session_start();
        $_SESSION['alert_prod'] = '<div class="alert alert-success" role="alert">
                        Product deleted
                    </div>';
        header("Location: ". BASE_URL);
        exit();
    } else {
        session_start();
        $_SESSION['alert_prod'] = '<div class="alert alert-danger" role="alert">
                                    Error deleting product: '.mysqli_error($conn).'
                                </div>';
        exit();
    }
} else {
    echo 'No product specified.';
}

mysqli_close($conn);
?>
