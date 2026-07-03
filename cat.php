<?php
    session_start();
    include('connection.php');

    // Category
    $cat_sql = "SELECT * FROM `tbl_waton_category`";

    $cat_result = mysqli_query($conn, $cat_sql);

    if($cat_result) {
        $cat = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$cat_sql.'<br/>'.mysqli_error( $conn );
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
    <title>Categories - <?=$page['page_sitetitle'];?></title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> 

    <!-- AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php
        if (!isset($_SESSION['user_id'])) {
            include("headerpublic.php");
        } else {
            include("header.php");
        }       
    ?>

    <div class="container my-3">
        <div class="row">
            <h1 class="display-4 text-center">Categories</h1>
            <p class="lead text-center">Not sure where to start? Jump into a category and see what catches your eye!</p>
        </div>

        <div class="row">
            <?php foreach ($cat as $cats): ?>               
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    <a href="catprod.php?id=<?=$cats['category_id'];?>" style="text-decoration: none; color: inherit;">
                        <div class="catbox w-100 m-2 border border-1 rounded shadow-sm d-flex justify-content-center align-items-center"  style="--bg-image: url('<?= BASE_URL . str_replace('../', '', $cats['category_photo']); ?>');">
                            <div class="cattext">
                                <h5 class="fw-semibold text-center text-white" 
                                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 5px 10px;">
                                    <?= $cats['category_name'] ?>
                                </h5>
                            </div>
                        </div>
                    </a>                  
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>