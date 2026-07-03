<?php
    session_start();
    include("connection.php");

    // Pagination
    $limit = 8;
    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page_number - 1) * $limit;

    // All Products
    $sql = "SELECT p.*, c.category_name 
        FROM tbl_waton_product p 
        JOIN tbl_waton_category c ON p.category_id = c.category_id
        ORDER BY COALESCE(p.prod_updated, p.prod_created) DESC 
        LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $prod = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$sql.'<br/>'.mysqli_error($conn);
    }

    $count_sql = "SELECT COUNT(*) as total FROM `tbl_waton_product`";
    $count_result = mysqli_query($conn, $count_sql);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_products = $count_row['total'];
    $total_pages = ceil($total_products / $limit);

    // Hot Products
    $hot_sql = "SELECT p.*, c.category_name FROM `tbl_waton_product` p 
                JOIN `tbl_waton_category` c ON p.category_id = c.category_id 
                ORDER BY p.prod_stock DESC LIMIT 8";
    $hot_result = mysqli_query($conn, $hot_sql);

    $hot_prod = [];
    $hot_ids = []; // For tracking hot product IDs globally

    if ($hot_result) {
        $hot_prod = mysqli_fetch_all($hot_result, MYSQLI_ASSOC);

        // Build an array of hot product IDs
        foreach ($hot_prod as $hot) {
            $hot_ids[] = $hot['prod_id'];
        }
    } else {
        echo 'Error: '.$hot_sql.'<br/>'.mysqli_error($conn);
    }

    // Low Price Products
    $cheap_sql = "SELECT p.*, c.category_name FROM `tbl_waton_product` p 
                JOIN `tbl_waton_category` c ON p.category_id = c.category_id 
                ORDER BY p.prod_price ASC LIMIT 8";
    $cheap_result = mysqli_query($conn, $cheap_sql);

    $cheap_prod = [];
    $cheap_ids = [];

    if ($cheap_result) {
        $cheap_prod = mysqli_fetch_all($cheap_result, MYSQLI_ASSOC);

        // Build an array of low-price product IDs
        foreach ($cheap_prod as $cheap) {
            $cheap_ids[] = $cheap['prod_id'];
        }
    } else {
        echo 'Error: '.$cheap_sql.'<br/>' . mysqli_error($conn);
    }

    // Pagination for user products
    $user_limit = 8;
    $user_page = isset($_GET['user_page']) ? (int)$_GET['user_page'] : 1;
    $user_offset = ($user_page - 1) * $user_limit;

    // Get only user-created products
    $user_sql = "SELECT p.*, c.category_name 
                FROM tbl_waton_product p 
                JOIN tbl_waton_category c ON p.category_id = c.category_id 
                WHERE p.prod_by = 1
                ORDER BY COALESCE(p.prod_updated, p.prod_created) DESC 
                LIMIT $user_limit OFFSET $user_offset";

    $user_result = mysqli_query($conn, $user_sql);
    $user_prod = [];

    if ($user_result) {
        $user_prod = mysqli_fetch_all($user_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$user_sql.'<br/>'.mysqli_error($conn);
    }

    // Total count for pagination
    $user_count_sql = "SELECT COUNT(*) AS total FROM tbl_waton_product WHERE prod_by = 1";
    $user_count_result = mysqli_query($conn, $user_count_sql);
    $user_total_row = mysqli_fetch_assoc($user_count_result);
    $user_total = $user_total_row['total'];
    $user_total_pages = ceil($user_total / $user_limit);

    // Top Categories
    $topcat_sql = "SELECT * FROM `tbl_waton_category` LIMIT 4";
    $topcat_result = mysqli_query($conn, $topcat_sql);

    if ($topcat_result) {
        $topcat = mysqli_fetch_all($topcat_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$topcat_sql.'<br/>'.mysqli_error($conn);
    }

    // All Categories
    $cat_sql = "SELECT * FROM `tbl_waton_category`";
    $cat_result = mysqli_query($conn, $cat_sql);

    if ($cat_result) {
        $cat = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$cat_sql.'<br/>'.mysqli_error($conn);
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
    <title><?=$page['page_sitetitle']?> - <?=$page['page_tagline']?></title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    <!-- Header -->
    <?php
        if (!isset($_SESSION['user_id'])) {
            include("headerpublic.php");
        } else {
            include("header.php");
        }       
    ?>

    <!-- Alert Message -->
    <div class="container my-3">
        <?php
            if (isset($_SESSION['alert_prod'])) {
                echo $_SESSION['alert_prod'];
                unset ($_SESSION['alert_prod']);
            }
        ?>
    </div>

    <!-- Page Banner -->
    <div class="container my-3">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <a href="<?=$page['page_banner1link'];?>">
                    <div class="carousel-item active">
                        <img src="<?= BASE_URL . str_replace('../', '', $page['page_banner1']); ?>" class="banner-img img-fluid" alt="" style="object-fit: cover;">
                    </div>
                </a>
                
                <a href="<?=$page['page_banner2link'];?>">
                    <div class="carousel-item">
                        <img src="<?= BASE_URL . str_replace('../', '', $page['page_banner2']); ?>" class="banner-img img-fluid" alt="" style="object-fit: cover;">
                    </div>
                </a>
                
                <a href="<?=$page['page_banner3link'];?>">
                    <div class="carousel-item">
                        <img src="<?= BASE_URL . str_replace('../', '', $page['page_banner3']); ?>" class="banner-img img-fluid" alt="" style="object-fit: cover;">
                    </div>
                </a>
                
                <a href="<?=$page['page_banner4link'];?>">
                    <div class="carousel-item">
                        <img src="<?= BASE_URL . str_replace('../', '', $page['page_banner4']); ?>" class="banner-img img-fluid" alt="" style="object-fit: cover;">
                    </div>
                </a>
                
                <a href="<?=$page['page_banner5link'];?>">
                    <div class="carousel-item">
                        <img src="<?= BASE_URL . str_replace('../', '', $page['page_banner5']); ?>" class="banner-img img-fluid" alt="" style="object-fit: cover;">
                    </div>
                </a>
                
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Page Banner -->

    <!-- Top Categories -->
    <div class="container my-3">
        <div class="row">
            <h1 class="display-6 text-center">Top Categories</h1>
        </div>
        <div class="row">
            <?php foreach ($topcat as $cats): ?>               
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
        <div class="row">
            <a href="cat.php" class="viewallcats text-center text-muted fs-5" style="color: black;">View all categories</a>
        </div>       
    </div>
    <!-- Top Categories -->

    <hr class="mx-5" />

    <!-- Hot Products -->
    <div class="container py-3 my-3 bg-white shadow rounded">
        <div class="container">
            <div class="row">
                <h1 class="display-6 text-lg-start text-center fw-medium"><i class="bi bi-fire fs-2 me-1"></i>Hot Products</h1>
                <p class="lead text-lg-start text-center">The products with the highest stocks are featured</p>
            </div>    
        </div>
        <div class="row">
            <!-- Hot Products Card -->
            <?php foreach ($hot_prod as $prods) : ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                    <a href="proddetail.php?id=<?=$prods['prod_id']?>" style="text-decoration: none; color: inherit;">
                        <div class="card bg-light shadow rounded mb-4 h-100">
                            <div class="img-container p-3">
                                <div class="ratio ratio-1x1">
                                    <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                                </div>
                            </div>

                            <div class="card-body">
                                <?php if (in_array($prods['prod_id'], $hot_ids)): ?>
                                    <span class="badge bg-danger">Hot</span>
                                <?php endif; ?>

                                <h5 class="card-title fw-semibold product-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= $prods['prod_name'] ?>                               
                                </h5>

                                <h6 class="cat fw-semibold text-muted product-cat">
                                    <?=$prods['category_name'] ?>
                                </h6>

                                <h5 class="price fw-light product-price" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="bi bi-tags-fill"></i> 
                                    $<?= $prods['prod_price'] ?>
                                </h5>

                                <h6 class="stock fw-medium product-stock" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25): ?>
                                        Stock: <span class="text-success fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15): ?>
                                        Stock: <span class="text-warning fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif($prods['prod_stock'] > 0): ?>
                                        Stock: <span class="text-danger fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">Out of stock</span>
                                    <?php endif?>
                                </h6>

                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <?php if ($prods['user_id'] == $_SESSION['user_id']) : ?>
                                        <div class="card-actions">
                                            <span class="text-muted">You created this product</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="card-actions">
                                            <?php if ($prods['prod_stock'] > 0): ?>
                                                <!-- Basket Button -->
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-basket"></i>
                                                        Basket
                                                    </button>
                                                </a>                        

                                                <!-- Buy Product Button-->
                                                <a href="#">                                      
                                                    <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-cash-coin"></i>
                                                        Buy
                                                    </button>
                                                </a>                               
                                            <?php else: ?>
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold">
                                                        <i class="bi bi-bookmark"></i>
                                                        Wishlist
                                                    </button>
                                                </a>
                                            <?php endif?>                                                  
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="card-actions">
                                        <?php if ($prods['prod_stock'] > 0): ?>
                                            <!-- Basket Button -->
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-basket"></i>
                                                    Basket
                                                </button>
                                            </a>                        

                                            <!-- Buy Product Button-->
                                            <a href="#">                                      
                                                <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-cash-coin"></i>
                                                    Buy
                                                </button>
                                            </a>                               
                                        <?php else: ?>
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold">
                                                    <i class="bi bi-bookmark"></i>
                                                    Wishlist
                                                </button>
                                            </a>
                                        <?php endif?>                                                  
                                    </div>
                                <?php endif; ?>
                            </div>   
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Hot Products -->

    <hr class="mx-5" />

    <!-- Low Price Products -->
    <div class="container py-3 my-3 bg-white shadow rounded">
        <div class="container">
            <div class="row">
                <h1 class="display-6 fw-medium text-lg-start text-center"><i class="bi bi-wallet2 fs-3 me-2"></i>Budget Picks</h1>
                <p class="lead text-lg-start text-center">Lowest prices for you</p>
            </div>
        </div>

        <div class="row">
            <?php foreach ($cheap_prod as $prods): ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                    <a href="proddetail.php?id=<?=$prods['prod_id']?>" style="text-decoration: none; color: inherit;">
                        <div class="card bg-light shadow rounded mb-4 h-100">
                            <div class="img-container p-3">
                                <div class="ratio ratio-1x1">
                                    <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                                </div>
                            </div>

                            <div class="card-body">
                                <?php if (in_array($prods['prod_id'], $cheap_ids)): ?>
                                    <span class="badge bg-success">Budget</span>                        
                                <?php endif; ?>

                                <h5 class="card-title fw-semibold product-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= $prods['prod_name'] ?>                               
                                </h5>
                                
                                <h6 class="cat fw-semibold text-muted product-cat">
                                    <?=$prods['category_name'] ?>
                                </h6>

                                <h5 class="price fw-light product-price" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="bi bi-tags-fill"></i> 
                                    $<?= $prods['prod_price'] ?>
                                </h5>

                                <h6 class="stock fw-medium product-stock" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25): ?>
                                        Stock: <span class="text-success fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15): ?>
                                        Stock: <span class="text-warning fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif($prods['prod_stock'] > 0): ?>
                                        Stock: <span class="text-danger fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">Out of stock</span>
                                    <?php endif?>
                                </h6>

                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <?php if ($prods['user_id'] == $_SESSION['user_id']) : ?>
                                        <div class="card-actions">
                                            <span class="text-muted">You created this product</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="card-actions">
                                            <?php if ($prods['prod_stock'] > 0): ?>
                                                <!-- Basket Button -->
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-basket"></i>
                                                        Basket
                                                    </button>
                                                </a>                        

                                                <!-- Buy Product Button-->
                                                <a href="#">                                      
                                                    <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-cash-coin"></i>
                                                        Buy
                                                    </button>
                                                </a>                               
                                            <?php else: ?>
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold">
                                                        <i class="bi bi-bookmark"></i>
                                                        Wishlist
                                                    </button>
                                                </a>
                                            <?php endif?>                                                  
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="card-actions">
                                        <?php if ($prods['prod_stock'] > 0): ?>
                                            <!-- Basket Button -->
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-basket"></i>
                                                    Basket
                                                </button>
                                            </a>                        

                                            <!-- Buy Product Button-->
                                            <a href="#">                                      
                                                <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-cash-coin"></i>
                                                    Buy
                                                </button>
                                            </a>                               
                                        <?php else: ?>
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold">
                                                    <i class="bi bi-bookmark"></i>
                                                    Wishlist
                                                </button>
                                            </a>
                                        <?php endif?>                                                  
                                    </div>
                                <?php endif; ?>
                            </div>   
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Low Price Products -->

    <hr class="mx-5" />

    <!-- User Products -->
    <div id="user-products" class="container py-3 my-3 bg-white shadow rounded">
        <div class="container">
            <div class="row">
                <h1 class="display-6 fw-medium text-lg-start text-center"><i class="bi bi-person-check-fill fs-3 me-2"></i></i>User Products</h1>
                <p class="lead text-lg-start text-center">Products created by your fellow waton users</p>
            </div>
        </div>

        <div class="row">
            <?php foreach ($user_prod as $prods): ?>
                <?php if($prods['prod_by'] != 1) continue; ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                    <a href="proddetail.php?id=<?=$prods['prod_id']?>" style="text-decoration: none; color: inherit;">
                        <div class="card bg-light shadow rounded mb-4 h-100">
                            <div class="img-container p-3">
                                <div class="ratio ratio-1x1">
                                    <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                                </div>
                            </div>

                            <div class="card-body">
                                <?php if($prods['prod_by'] == 1): ?>
                                    <span class="badge bg-primary">User</span>
                                <?php endif; ?>

                                <h5 class="card-title fw-semibold product-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= $prods['prod_name'] ?>                               
                                </h5>
                                
                                <h6 class="cat fw-semibold text-muted product-cat">
                                    <?=$prods['category_name'] ?>
                                </h6>

                                <h5 class="price fw-light product-price" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="bi bi-tags-fill"></i> 
                                    $<?= $prods['prod_price'] ?>
                                </h5>

                                <h6 class="stock fw-medium product-stock" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25): ?>
                                        Stock: <span class="text-success fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15): ?>
                                        Stock: <span class="text-warning fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif($prods['prod_stock'] > 0): ?>
                                        Stock: <span class="text-danger fw-semibold"><?= $prods['prod_stock'] ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">Out of stock</span>
                                    <?php endif?>
                                </h6>

                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <?php if ($prods['user_id'] == $_SESSION['user_id']) : ?>
                                        <div class="card-actions">
                                            <span class="text-muted">You created this product</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="card-actions">
                                            <?php if ($prods['prod_stock'] > 0): ?>
                                                <!-- Basket Button -->
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-basket"></i>
                                                        Basket
                                                    </button>
                                                </a>                        

                                                <!-- Buy Product Button-->
                                                <a href="#">                                      
                                                    <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-cash-coin"></i>
                                                        Buy
                                                    </button>
                                                </a>                               
                                            <?php else: ?>
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold">
                                                        <i class="bi bi-bookmark"></i>
                                                        Wishlist
                                                    </button>
                                                </a>
                                            <?php endif?>                                                  
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="card-actions">
                                        <?php if ($prods['prod_stock'] > 0): ?>
                                            <!-- Basket Button -->
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-basket"></i>
                                                    Basket
                                                </button>
                                            </a>                        

                                            <!-- Buy Product Button-->
                                            <a href="#">                                      
                                                <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-cash-coin"></i>
                                                    Buy
                                                </button>
                                            </a>                               
                                        <?php else: ?>
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold">
                                                    <i class="bi bi-bookmark"></i>
                                                    Wishlist
                                                </button>
                                            </a>
                                        <?php endif?>                                                  
                                    </div>
                                <?php endif; ?>
                            </div>   
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($user_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?user_page=<?= $user_page - 1 ?>#user-products">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $user_total_pages; $i++): ?>
                        <li class="page-item <?= $i == $user_page ? 'active' : '' ?>">
                            <a class="page-link" href="?user_page=<?= $i ?>#user-products"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($user_page < $user_total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?user_page=<?= $user_page + 1 ?>#user-products">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <!-- User Products -->

    <!-- All Product List -->
    <div id="allprod-box" class="container py-3 my-3 bg-white shadow rounded">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <h1 class="display-6 text-center text-lg-start"><i class="bi bi-globe2 me-2 fs-2"></i>Explore</h1>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-end justify-content-center">
                    &nbsp;
                </div>
            </div>    
        </div>
        <div class="row">
            <!-- Product Card -->
            <?php foreach ($prod as $prods) :?>               
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                <a href="proddetail.php?id=<?=$prods['prod_id']?>" style="text-decoration: none; color: inherit;">
                    <div class="card bg-light shadow rounded mb-4 h-100">
                        <div class="img-container p-3">
                            <div class="ratio ratio-1x1">
                                <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                            </div>
                        </div>
                    
                        <div class="card-body">                                          
                            <h5 class="card-title fw-semibold product-name">
                                <?php if (in_array($prods['prod_id'], $cheap_ids)): ?>
                                    <span class="badge bg-success">Budget</span>
                                <?php endif; ?>

                                <?php if (in_array($prods['prod_id'], $hot_ids)): ?>
                                    <span class="badge bg-danger">Hot</span>
                                <?php endif; ?>

                                <?php if($prods['prod_by'] == 1): ?>
                                    <span class="badge bg-primary">User</span>
                                <?php endif; ?>

                                <?=$prods['prod_name']?>                           
                            </h5>         

                            <h6 class="cat fw-semibold product-cat text-muted">
                                <?=$prods['category_name'] ?>
                            </h6>

                            <h5 class="price fw-light product-price" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <i class="bi bi-tags-fill"></i> 
                                $<?=$prods['prod_price']?>
                            </h5>

                            <h6 class="stock fw-medium product-stock" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25): ?>
                                    Stock: <span class="text-success fw-semibold"><?=$prods['prod_stock']?></span>
                                <?php elseif($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15): ?>
                                    Stock: <span class="text-warning fw-semibold"><?=$prods['prod_stock']?></span>
                                <?php elseif($prods['prod_stock'] > 0): ?>
                                    Stock: <span class="text-danger fw-semibold"><?=$prods['prod_stock']?></span>
                                <?php else: ?>
                                    <span class="text-danger">Out of stock</span>
                                <?php endif?>
                            </h6>
                            
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <?php if ($prods['user_id'] == $_SESSION['user_id']) : ?>
                                    <div class="card-actions">
                                        <span class="text-muted">You created this product</span>
                                    </div>
                                <?php else: ?>
                                    <div class="card-actions">
                                        <?php if ($prods['prod_stock'] > 0): ?>
                                            <!-- Basket Button -->
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-basket"></i>
                                                    Basket
                                                </button>
                                            </a>                        

                                            <!-- Buy Product Button-->
                                            <a href="#">                                      
                                                <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-cash-coin"></i>
                                                    Buy
                                                </button>
                                            </a>                               
                                        <?php else: ?>
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold">
                                                    <i class="bi bi-bookmark"></i>
                                                    Wishlist
                                                </button>
                                            </a>
                                        <?php endif?>                                                  
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="card-actions">
                                    <?php if ($prods['prod_stock'] > 0): ?>
                                        <!-- Basket Button -->
                                        <a href="#">
                                            <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                <i class="bi bi-basket"></i>
                                                Basket
                                            </button>
                                        </a>                        

                                        <!-- Buy Product Button-->
                                        <a href="#">                                      
                                            <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                <i class="bi bi-cash-coin"></i>
                                                Buy
                                            </button>
                                        </a>                               
                                    <?php else: ?>
                                        <a href="#">
                                            <button type="button" class="btn btn-dark fw-semibold">
                                                <i class="bi bi-bookmark"></i>
                                                Wishlist
                                            </button>
                                        </a>
                                    <?php endif?>                                                  
                                </div>
                            <?php endif; ?>
                        </div>   
                    </div>
                </a>
                
            </div>
            <?php endforeach; ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page_number > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page_number - 1 ?>#allprod-box">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page_number ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>#allprod-box"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page_number < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page_number + 1 ?>#allprod-box">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </div>
    <!-- All Product List -->

    <hr class="mx-5" />

    <?php include('footer.php'); ?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>