<?php
session_start();
include('connection.php');

if (isset($_GET['id'])) {
    $profileUserId = $_GET['id'];
} else {
    echo 'No user ID provided.';
    exit();
}

// User profile
$user_sql = "SELECT * FROM `tbl_waton_user` WHERE `user_id` = '$profileUserId'";
$user_result = mysqli_query($conn, $user_sql);
if ($user_result) {
    $profileUser = mysqli_fetch_assoc($user_result);
    if (!$profileUser) {
        echo 'User not found.';
        exit();
    }
} else {
    echo 'Error: ' . $user_sql . '<br/>' . mysqli_error($conn);
    exit();
}

// Pagination
$prod_per_page = 8;
$prod_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($prod_page - 1) * $prod_per_page;

// Get total number of products by the user
$total_prods_sql = "SELECT COUNT(*) as total FROM `tbl_waton_product` WHERE user_id = '$profileUserId'";
$total_prods_result = mysqli_query($conn, $total_prods_sql);
$total_prods = mysqli_fetch_assoc($total_prods_result)['total'];    
$total_page = ceil($total_prods / $prod_per_page);

// Products
$prod_sql = "SELECT p.*, c.category_name FROM `tbl_waton_product` p 
LEFT JOIN `tbl_waton_category` c ON p.category_id = c.category_id 
WHERE p.user_id = '$profileUserId' 
LIMIT $prod_per_page OFFSET $offset";
$result = mysqli_query($conn, $prod_sql);

if ($result) {
    $prod = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo 'Error: ' . $prod_sql . '<br/>' . mysqli_error($conn);
    exit();
}

// Category data
$cat_sql = "SELECT * FROM `tbl_waton_category`";
$cat_result = mysqli_query($conn, $cat_sql);
if ($cat_result) {
    $cat = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);
} else {
    echo 'Error: ' . $cat_sql . '<br/>' . mysqli_error($conn);
    exit();
}

// Page settings
$page_sql = "SELECT * FROM tbl_waton_page WHERE page_id = 1";
$page_result = mysqli_query($conn, $page_sql);
$page = mysqli_fetch_assoc($page_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profile - <?= htmlspecialchars($profileUser['username']) ?></title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>" />

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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

    <div class="container p-3 my-3 bg-white shadow rounded">
        <!-- Profile -->
        <div class="row">
            <div class="row align-items-center">
                <!-- Image and Text Column -->
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex align-items-center">
                    <!-- Image with responsive circle -->
                    <img src="<?= BASE_URL . str_replace('../', '', $profileUser['user_photo']) ?>"
                         alt="User Photo"
                         class="rounded-circle mx-3 img-fluid"
                         style="width: 30vw; height: 30vw; max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 50%;" />

                    <div class="d-flex flex-column">
                        <h2 class="fw-semibold mb-0"><?= htmlspecialchars($profileUser['username']) ?></h2>
                        <h5 class="lead fw-medium mb-0 text-muted"><?= htmlspecialchars($profileUser['user_email']) ?></h5>
                    </div>
                </div>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profileUserId) : ?>
                    <!-- Show settings button only for the logged-in user viewing own profile -->
                    <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-end align-items-center">
                        <a href="settings.php">
                            <button type="button" class="btn btn-lg btn-dark">
                                <i class="bi bi-gear-fill"></i>
                            </button>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <hr />

        <!-- Products -->
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <h1 class="display-6 text-center text-lg-start">Products by <?= htmlspecialchars($profileUser['username']) ?></h1>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 d-lg-flex justify-content-end d-none">
                <div class="form-floating">
                    <select class="form-select" name="cat-filter" id="cat-filter">
                        <option value="">All Categories</option>
                        <?php foreach ($cat as $cats) : ?>
                            <option value="<?= $cats['category_id'] ?>">
                                <?= htmlspecialchars($cats['category_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <label for="cat-filter">Filter by Category</label>
                </div>
            </div>

            <div class="row" id="prodcard">
                <?php if (!empty($prod)) : ?>
                    <?php foreach ($prod as $prods) : ?>
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                            <a href="proddetail.php?id=<?=$prods['prod_id']?>" style="text-decoration: none; color: inherit;">
                                <div class="card bg-light shadow rounded mb-4 h-100">
                                    <div class="img-container p-3">
                                        <div class="ratio ratio-1x1">
                                            <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="" />
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title fw-semibold product-name">
                                            <?= htmlspecialchars($prods['prod_name']) ?>
                                        </h5>

                                        <h6 class="cat fw-semibold product-cat">
                                            <?= !empty($prods['category_name']) ? htmlspecialchars($prods['category_name']) : '<p class="text-danger">No category</p>' ?>
                                        </h6>

                                        <h5 class="price fw-light product-price" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <i class="bi bi-tags-fill"></i>
                                            $<?= htmlspecialchars($prods['prod_price']) ?>
                                        </h5>

                                        <h6 class="stock fw-medium product-stock" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25): ?>
                                                Stock: <span class="text-success"><?= $prods['prod_stock'] ?></span>
                                            <?php elseif ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15): ?>
                                                Stock: <span class="text-warning"><?= $prods['prod_stock'] ?></span>
                                            <?php elseif ($prods['prod_stock'] > 0): ?>
                                                Stock: <span class="text-danger"><?= $prods['prod_stock'] ?></span>
                                            <?php else: ?>
                                                <span class="text-danger">Out of stock</span>
                                            <?php endif; ?>
                                        </h6>

                                        <?php if(isset($_SESSION['user_id'])): ?>
                                            <?php if ($prods['user_id'] == $_SESSION['user_id']) : ?>
                                                <div class="card-actions">
                                                    <!-- Edit Product Button -->
                                                    <a href="editprod.php?id=<?= $prods['prod_id'] ?>">
                                                        <button type="button" class="btn btn-success">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                    </a>

                                                    <!-- Delete category button trigger modal -->
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#prodDeleteModal<?= $prods['prod_id'] ?>">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
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

                            <!-- Delete Product Modal -->
                            <div class="modal fade" id="prodDeleteModal<?= $prods['prod_id'] ?>" tabindex="-1" aria-labelledby="prodDeleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="prodDeleteModalLabel">Delete Product</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong><?= htmlspecialchars($prods['prod_name']) ?></strong> product?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="deleteprod.php?id=<?= $prods['prod_id'] ?>" class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Pagination -->
                    <div class="container">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($prod_page > 1) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $prod_page - 1 ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                                    <li class="page-item <?= $i === $prod_page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($prod_page < $total_page) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $prod_page + 1 ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php else : ?>
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">No products by this user.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Category Filter -->
    <script>
        $(document).ready(function() {
            $('#cat-filter').on('change', function() {
                var catId = $(this).val();
                var userId = <?= $profileUserId ?>;
                $('#prodcard').html('<center><img src="img/loading.gif" style="width: 500px; height: 500px;"></center>');
                $.ajax({                   
                    type: 'POST',
                    url: 'process/user-prodcat.php',
                    data: {catId: catId, userId: userId},
                    success: function(data) {                       
                        console.log(data);
                        setTimeout(function() {
                            $('#prodcard').html(data);
                        }, 800);
                    }
                });
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

