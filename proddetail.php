<?php
    session_start();
    include('connection.php');

    if (isset($_GET['id'])) {
        $prod_id = $_GET['id'];

        // Fetch categories
        $cat_sql = "SELECT * FROM `tbl_waton_category`";
        $cat_result = mysqli_query($conn, $cat_sql);
        if ($cat_result) {
            $cat = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }

        // Fetch product details
        $prod_sql = "SELECT p.*, c.category_name, u.username, u.user_photo 
                    FROM `tbl_waton_product` p 
                    LEFT JOIN `tbl_waton_category` c ON p.category_id = c.category_id 
                    LEFT JOIN `tbl_waton_user` u ON p.user_id = u.user_id 
                    WHERE p.prod_id = '$prod_id'";
        $prod_result = mysqli_query($conn, $prod_sql);

        if ($prod_result && mysqli_num_rows($prod_result) > 0) {
            $prod = mysqli_fetch_assoc($prod_result);
        } else {
            echo 'Error: Product not found or ' . mysqli_error($conn);
            exit();
        }
    } else {
        echo "Product ID not set in session.";
        exit();
    }

    // Hot Product
    $hot_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_stock DESC LIMIT 8";
    $hot_result = mysqli_query($conn, $hot_sql);
    $hot_ids = array_column(mysqli_fetch_all($hot_result, MYSQLI_ASSOC), 'prod_id');

    // Cheap Product
    $cheap_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_price ASC LIMIT 8";
    $cheap_result = mysqli_query($conn, $cheap_sql);
    $cheap_ids = array_column(mysqli_fetch_all($cheap_result, MYSQLI_ASSOC), 'prod_id');

    // Similar Products (by category)
    $samecat_id = $prod['category_id'];
    $samecat_sql = "SELECT p.*, c.category_name FROM tbl_waton_product p
                LEFT JOIN tbl_waton_category c ON p.category_id = c.category_id
                WHERE p.category_id = '$samecat_id' AND p.prod_id != '$prod_id'
                ORDER BY p.prod_name ASC LIMIT 4";
    $samecat_result = mysqli_query($conn, $samecat_sql);

    if(mysqli_num_rows($samecat_result) > 0) {
        $samecat = mysqli_fetch_all($samecat_result, MYSQLI_ASSOC);
    } else {
        $samecat = [];
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
    <title><?=$prod['prod_name']?> - <?=$page['page_sitetitle']?></title>

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

    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

    </style>
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

    <!-- Product Details -->
    <div class="container bg-light shadow rounded p-3 my-3">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="img-container p-3 d-flex justify-content-center align-items-center">
                    <div class="ratio ratio-1x1 w-75 h-75">
                        <img src="<?= BASE_URL . str_replace('../', '', $prod['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="title mt-lg-5">
                    <?php if (in_array($prod['prod_id'], $cheap_ids)): ?>
                        <span class="badge bg-success">Budget</span>
                    <?php endif; ?>

                    <?php if (in_array($prod['prod_id'], $hot_ids)): ?>
                        <span class="badge bg-danger">Hot</span>
                    <?php endif; ?>

                    <?php if($prod['prod_by'] == 1): ?>
                        <span class="badge bg-primary">User</span>
                    <?php endif; ?>

                    <h1 class="fw-semibold">
                        <?=$prod['prod_name']?>                           
                    </h1> 
                </div> 

                <hr/>
                
                <div class="cat">
                    <h5 class="fw-semibold text-muted">
                        from <a href="catprod.php?id=<?=$prod['category_id']?>" style="color: inherit;"><?= $prod['category_name'] ?></a>
                    </h5>
                </div>

                <div class="num">
                    <h2 class="price fw-light" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <i class="bi bi-tags-fill"></i> 
                        $<?= $prod['prod_price'] ?>
                    </h2>

                    <h5 class="stock fw-medium mt-4" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?php if ($prod['prod_stock'] > 0 && $prod['prod_stock'] >= 25): ?>
                            Stock: <span class="text-success fw-semibold"><?= $prod['prod_stock'] ?></span>
                        <?php elseif($prod['prod_stock'] > 0 && $prod['prod_stock'] >= 15): ?>
                            Stock: <span class="text-warning fw-semibold"><?= $prod['prod_stock'] ?></span>
                        <?php elseif($prod['prod_stock'] > 0): ?>
                            Stock: <span class="text-danger fw-semibold"><?= $prod['prod_stock'] ?></span>
                        <?php else: ?>
                            <span class="text-danger">This product is out of stock</span>
                        <?php endif?>
                    </h5>
                </div>  
                
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="actions">
                        <!-- Quantity select (not functional) -->
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <label for="quantity" class="fw-semibold mb-0">Qty:</label>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">-</button>
                                <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="<?= $prod['prod_stock'] ?>" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="incrementQty()">+</button>
                            </div>
                        </div>

                        <?php if ($prod['prod_stock'] > 0): ?>
                            <!-- Basket Button -->
                            <a href="#">
                                <button type="button" class="btn btn-dark mb-1 mb-lg-0 btn-lg fw-semibold prod-btn">
                                    <i class="bi bi-basket"></i>
                                    Add to Basket
                                </button>
                            </a>                        

                            <!-- Buy Product Button-->
                            <a href="#">                                      
                                <button type="button" class="btn btn-warning btn-lg fw-semibold mb-1 mb-lg-0 prod-btn">
                                    <i class="bi bi-cash-coin"></i>
                                    Buy this item
                                </button>
                            </a>                               
                        <?php else: ?>
                            <a href="#">
                                <button type="button" class="btn btn-dark btn-lg fw-semibold prod-btn">
                                    <i class="bi bi-bookmark"></i>
                                    Wishlist this item
                                </button>
                            </a>
                        <?php endif; ?> 
                    </div>
                <?php else: ?>
                    <?php if ($prod['user_id'] == $_SESSION['user_id']): ?>
                        <h5 class="fw-medium">You created this product at <?=$prod['prod_created']?></h5>

                        <div class="actions">
                            <!-- Edit Product Button -->
                            <a href="editprod.php?id=<?php echo $prod['prod_id'] ?>">
                                <button type="button" class="btn btn-lg btn-success">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </button>
                            </a>

                            <!-- Delete Product Button with reusable modal -->
                            <button type="button" class="btn btn-lg btn-danger" data-bs-toggle="modal" data-bs-target="#prodDeleteModal<?php echo $prod['prod_id'] ?>">
                                <i class="bi bi-trash-fill me-1"></i>Delete
                            </button>

                            <!-- Delete Product Modal -->
                            <div class="modal fade" id="prodDeleteModal<?php echo $prod['prod_id'] ?>" tabindex="-1" aria-labelledby="prodDeleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="prodDeleteModalLabel">Delete Category</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete product <strong><?= $prod['prod_name'] ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="deleteproddet.php?id=<?php echo $prod['prod_id'] ?>" class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="actions">
                            <!-- Quantity select (not functional) -->
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <label for="quantity" class="fw-semibold mb-0">Qty:</label>
                                <div class="input-group" style="width: 120px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">-</button>
                                    <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="<?= $prod['prod_stock'] ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="incrementQty()">+</button>
                                </div>
                            </div>

                            <?php if ($prod['prod_stock'] > 0): ?>
                                <!-- Basket Button -->
                                <a href="#">
                                    <button type="button" class="btn btn-dark mb-1 mb-lg-0 btn-lg fw-semibold prod-btn">
                                        <i class="bi bi-basket"></i>
                                        Add to Basket
                                    </button>
                                </a>                        

                                <!-- Buy Product Button-->
                                <a href="#">                                      
                                    <button type="button" class="btn btn-warning btn-lg fw-semibold mb-1 mb-lg-0 prod-btn">
                                        <i class="bi bi-cash-coin"></i>
                                        Buy this item
                                    </button>
                                </a>                               
                            <?php else: ?>
                                <a href="#">
                                    <button type="button" class="btn btn-dark btn-lg fw-semibold prod-btn">
                                        <i class="bi bi-bookmark"></i>
                                        Wishlist this item
                                    </button>
                                </a>
                            <?php endif; ?> 
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="accordion accordion-flush my-3" id="desc">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#desccollapse" aria-expanded="true" aria-controls="desccollapse">
                            Description
                        </button>                     
                    </h2>
                </div> 
                
                <div id="desccollapse" class="accordion-collapse collapse show" data-bs-parent="#desc">
                    <div class="accordion-body">
                        <p class="text-muted lead" style="text-align: justify;">
                            <?= $prod['prod_desc'] ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>    
        
        <!-- Product Creator (for user products) -->
        <?php if ($prod['prod_by'] == 1): ?>
            <hr>
            
            <a href="userprof.php?id=<?=$prod['user_id']?>" style="text-decoration: none; color: inherit;">
                <div class="container-fluid bg-light shadow rounded p-lg-3 p-1 m-lg-2">
                    <div class="row align-items-center">
                        <!-- Image and Text Column -->
                        <div class="col-lg-6 col-md-12 col-sm-12 d-flex align-items-center">
                            <!-- Image with responsive circle -->
                            <img src="<?= BASE_URL . str_replace('../', '', $prod['user_photo']) ?>" 
                                alt="User Photo" 
                                class="rounded-circle mx-3 img-fluid" 
                                style="width: 30vw; height: 30vw; max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 50%;">
                            
                            <div class="d-flex flex-column">
                                <h5 class="lead fw-medium mb-0 text-muted">Product by</h5>
                                <h2 class="fw-semibold mb-0"><?= htmlspecialchars($prod['username']) ?></h2>
                            </div>
                        </div>

                        <!-- Button Column -->
                        <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-end align-items-center">
                            <span class="lead fw-medium mb-0 text-muted">View profile<i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </div> 
            </a>                     
        <?php endif; ?>


    </div>

    <hr class="mx-5">

    <!-- Similar Products -->
    <div class="container my-3">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <h1 class="display-6 text-center text-lg-start">More <strong><?= $prod['category_name'] ?></strong> Products</h1>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 d-lg-flex justify-content-end d-none">
                <a href="catprod.php?id=<?=$prod['category_id'];?>" class="viewallcats text-center text-muted fs-5" style="color: black;">View all</a>
            </div>
        </div> 
        
        <div class="row">
            <?php foreach ($samecat as $prods) :?>               
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
                        </div>   
                    </div>
                </a>               
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="col-lg-6 col-md-12 col-sm-12 d-lg-none justify-content-center d-flex">
            <a href="catprod.php?id=<?=$prod['category_id'];?>" class="viewallcats text-center fs-5" style="color: black;">View all</a>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Quantity select -->
    <script>
        function incrementQty() {
            let input = document.getElementById('quantity');
            let max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decrementQty() {
            let input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>