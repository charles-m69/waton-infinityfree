<?php
    // Top Categories
    $topcat_sql = "SELECT * FROM `tbl_waton_category` LIMIT 4";
    $topcat_result = mysqli_query($conn, $topcat_sql);

    if ($topcat_result) {
        $topcat = mysqli_fetch_all($topcat_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$topcat_sql.'<br/>'.mysqli_error($conn);
    }

    // Page
    $page_sql = "SELECT * FROM tbl_waton_page WHERE page_id = 1";
    $result = mysqli_query($conn, $page_sql);
    $page = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> 
</head>
<body>
    <footer class="bg-dark text-center text-white">
        <div class="d-flex align-items-center justify-content-center py-2">
            <img src="<?= BASE_URL . $page['page_logo']; ?>" class="picture-src img-fluid" alt="" style="width: 60px; height: 60px; object-fit: cover;">
            <h5 class="text-lowercase mb-0 font-weight-bold" style="font-family: waton; margin-left: 10px;">waton for sellers</h5>
        </div>

        <!-- Social Media Links -->
        <div class="container py-3">
            <div class="d-flex justify-content-center align-items-center flex-nowrap w-100" style="gap: 2vw;">
                <!-- Link 1 -->
                <a href="<?= $page['page_socmed1'] ?>" class="btn btn-floating" role="button">                  
                    <img src="<?= BASE_URL . $page['page_socmed1icon']; ?>" alt="" style="width: 6vw; max-width: 40px; height: auto; object-fit: contain;">
                </a>

                <!-- Link 2 -->
                <a href="<?= $page['page_socmed2'] ?>" class="btn btn-floating" role="button">  
                    <img src="<?= BASE_URL . $page['page_socmed2icon']; ?>" alt="" style="width: 6vw; max-width: 40px; height: auto; object-fit: contain;">
                </a>

                <!-- Link 3 -->
                <a href="<?= $page['page_socmed3'] ?>" class="btn btn-floating" role="button">  
                    <img src="<?= BASE_URL . $page['page_socmed3icon']; ?>" alt="" style="width: 6vw; max-width: 40px; height: auto; object-fit: contain;">
                </a>

                <!-- Link 4 -->
                <a href="<?= $page['page_socmed4'] ?>" class="btn btn-floating" role="button">  
                    <img src="<?= BASE_URL . $page['page_socmed4icon']; ?>" alt="" style="width: 6vw; max-width: 40px; height: auto; object-fit: contain;">
                </a>

                <!-- Link 5 -->
                <a href="<?= $page['page_socmed5'] ?>" class="btn btn-floating" role="button">  
                    <img src="<?= BASE_URL . $page['page_socmed5icon']; ?>" alt="" style="width: 6vw; max-width: 40px; height: auto; object-fit: contain;">
                </a>
            </div>
        </div>

        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2030 Copyright:
            <a class="text-white" href="../index.php">waton</a>
        </div>
    </footer>
</body>
</html>