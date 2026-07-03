<?php
    session_start();
    include('connection.php');

    // Pagination
    $limit = 8;
    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page_number - 1) * $limit;

    if (isset($_GET['id'])) {
        $category_id = $_GET['id'];

        // Fetch category info
        $cat_info_sql = "SELECT * FROM tbl_waton_category WHERE category_id = '$category_id'";
        $cat_info_result = mysqli_query($conn, $cat_info_sql);
        $cat_info = mysqli_fetch_assoc($cat_info_result);

        // Fetch all products under this category
        $catprod_sql = "SELECT p.*, c.category_name FROM tbl_waton_product p
            LEFT JOIN tbl_waton_category c ON p.category_id = c.category_id
            WHERE p.category_id = '$category_id'
            ORDER BY p.prod_name ASC
            LIMIT $limit OFFSET $offset";

        $catprod_result = mysqli_query($conn, $catprod_sql);

        if (mysqli_num_rows($catprod_result) > 0) {
            $samecat = mysqli_fetch_all($catprod_result, MYSQLI_ASSOC);
        } else {
            $samecat = [];
        }
    } else {
        echo "Category ID not provided.";
        exit();
    }

    $count_sql = "SELECT COUNT(*) as total FROM tbl_waton_product WHERE category_id = '$category_id'";
    $count_result = mysqli_query($conn, $count_sql);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_products = $count_row['total'];
    $total_pages = ceil($total_products / $limit);

    // Hot Product
    $hot_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_stock DESC LIMIT 8";
    $hot_result = mysqli_query($conn, $hot_sql);
    $hot_ids = array_column(mysqli_fetch_all($hot_result, MYSQLI_ASSOC), 'prod_id');

    // Cheap Product
    $cheap_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_price ASC LIMIT 8";
    $cheap_result = mysqli_query($conn, $cheap_sql);
    $cheap_ids = array_column(mysqli_fetch_all($cheap_result, MYSQLI_ASSOC), 'prod_id');

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
    <title><?=$cat_info['category_name'];?> - <?=$page['page_sitetitle']?></title>

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

    <!-- Category Products -->
    <div id="catprods" class="container py-3 my-3 bg-white shadow rounded">
        <div class="container">
            <div class="row">
                <div class="catinfobox w-100 m-2 border border-1 rounded shadow-sm d-flex justify-content-center align-items-center"  style="background-image: url('<?= BASE_URL . str_replace('../', '', $cat_info['category_photo']); ?>'); background-size: cover; background-position: center; min-height: 150px;">
                    <div class="cattitle rounded-5 p-2 m-2" style="background-color: rgba(0, 0, 0, 0.5);">
                        <h1 class="fw-semibold text-center text-white" 
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-decoration: underline; ">
                            <?= $cat_info['category_name'] ?>
                        </h1>
                        <p class="lead text-white text-lg-start text-center"><?= $cat_info['category_desc'] ?></p>
                    </div>
                </div>               
            </div>    
        </div>

        <div class="row">
            <?php foreach ($samecat as $prods) : ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                    <a href="proddetail.php?id=<?= $prods['prod_id'] ?>" style="text-decoration: none; color: inherit;">
                        <div class="card bg-light shadow rounded mb-4 h-100">
                            <div class="img-container p-3">
                                <div class="ratio ratio-1x1">
                                    <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-semibold product-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php if (in_array($prods['prod_id'], $cheap_ids)): ?>
                                        <span class="badge bg-success">Budget</span>
                                    <?php endif; ?>

                                    <?php if (in_array($prods['prod_id'], $hot_ids)): ?>
                                        <span class="badge bg-danger">Hot</span>
                                    <?php endif; ?>

                                    <?php if($prods['prod_by'] == 1): ?>
                                        <span class="badge bg-primary">User</span>
                                    <?php endif; ?>

                                    <?= $prods['prod_name'] ?>                               
                                </h5>

                                <h6 class="cat fw-semibold text-muted product-cat">
                                    <?= $prods['category_name'] ?>
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
                                    <?php endif ?>
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
                            <a class="page-link" href="?id=<?= $category_id ?>&page=<?= $page_number - 1 ?>#catprods" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page_number ? 'active' : '' ?>">
                            <a class="page-link" href="?id=<?= $category_id ?>&page=<?= $i ?>#catprods"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page_number < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?id=<?= $category_id ?>&page=<?= $page_number + 1 ?>#catprods" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Category Products -->
     
    <?php include('footer.php'); ?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>