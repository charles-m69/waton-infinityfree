<?php
    session_start();
    include("connection.php");

    $initial_search = $_GET['search'] ?? '';

    // Pagination
    $limit = 12;
    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page_number - 1) * $limit;

    // Hot Product
    $hot_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_stock DESC LIMIT 8";
    $hot_result = mysqli_query($conn, $hot_sql);
    $hot_ids = array_column(mysqli_fetch_all($hot_result, MYSQLI_ASSOC), 'prod_id');

    // Cheap Product
    $cheap_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_price ASC LIMIT 8";
    $cheap_result = mysqli_query($conn, $cheap_sql);
    $cheap_ids = array_column(mysqli_fetch_all($cheap_result, MYSQLI_ASSOC), 'prod_id');

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
    <title>Products - <?=$page['page_sitetitle'];?></title>

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
    <!-- Header -->
    <?php
        if (!isset($_SESSION['user_id'])) {
            include("headerpublic.php");
        } else {
            include("header.php");
        }       
    ?>

    <!-- All Product List -->
    <div id="allprod-box" class="container py-3 my-3 bg-white shadow rounded">
        <div class="container">
            <div class="row align-items-center flex-wrap">
                <!-- Explore title -->
                <div class="col-lg-6 col-12 mb-3 mb-lg-0">
                    <h1 class="display-6 text-center text-lg-start">
                        <i class="bi bi-globe2 me-2 fs-2"></i>Explore
                    </h1>
                    <p class="lead text-center text-lg-start">All products in waton</p>
                </div>

                <!-- Filters section -->
                <div class="col-lg-6 col-12">
                    <div class="row g-2">
                        <!-- Category filter -->
                        <div class="col-12 col-sm-4">
                            <div class="form-floating">
                                <select id="filter-category" class="form-select" aria-label="Category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($cat as $c): ?>
                                        <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="filter-category">Category</label>
                            </div>
                        </div>

                        <!-- Price Min -->
                        <div class="col-6 col-sm-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="price-min" placeholder="Min Price">
                                <label for="price-min">Min Price</label>
                            </div>
                        </div>

                        <!-- Price Max -->
                        <div class="col-6 col-sm-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="price-max" placeholder="Max Price">
                                <label for="price-max">Max Price</label>
                            </div>
                        </div>

                        <!-- Order Filter -->
                        <div class="col-12 d-flex align-items-center justify-content-center justify-content-lg-end">
                            <h6 class="text-muted me-3 fw-semibold form-check-label">Order</h6>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="date-filter" id="date-newest" value="desc" checked>
                                <label class="form-check-label" for="date-newest">Newest First</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="date-filter" id="date-oldest" value="asc">
                                <label class="form-check-label" for="date-oldest">Oldest First</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        
        <div class="row" id="prodcard">
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

    <?php include('footer.php'); ?>
  
    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        const initialSearch = <?= json_encode($initial_search) ?>;
    </script>

    <script>
        $(document).ready(function () {
            if (initialSearch) {
                $('#prod-search').val(initialSearch);
            }

            function fetchProducts(page = 1) {
                let category = $('#filter-category').val();
                let priceMin = $('#price-min').val();
                let priceMax = $('#price-max').val();
                let sort = $('input[name="date-filter"]:checked').val();
                let search = $('#prod-search').val();

                $.ajax({
                    url: 'process/filter-process.php',
                    type: 'POST',
                    data: {
                        category: category,
                        price_min: priceMin,
                        price_max: priceMax,
                        sort: sort,
                        search: search,
                        page: page
                    },
                    success: function (response) {
                        $('#prodcard').html(response);
                    }
                });
            }

            fetchProducts();

            $('#filter-category, #price-min, #price-max').on('change keyup', function () {
                fetchProducts();
            });
            
            $('input[name="date-filter"]').on('change', function () {
                fetchProducts();
            });

            $('#prod-search').on('input', function () {
                fetchProducts();
            });

            // Pagination
            $(document).on('click', '.page-link[data-page]', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                fetchProducts(page);
            });
        });
    </script>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>