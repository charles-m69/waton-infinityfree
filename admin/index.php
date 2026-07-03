<?php
    session_start();

    include("connection.php");

    // Pagination
    $prod_per_page = 8;
    $prod_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($prod_page - 1) * $prod_per_page;

    $total_prods_sql = "SELECT COUNT(*) as total FROM `tbl_waton_product`";
    $total_prods_result = mysqli_query($conn, $total_prods_sql);

    $total_prods = mysqli_fetch_assoc($total_prods_result)['total'];	
    $total_page = ceil($total_prods / $prod_per_page);

    $sql = "SELECT p.*, c.category_name FROM `tbl_waton_product` p 
        LEFT JOIN `tbl_waton_category` c ON p.category_id = c.category_id 
        WHERE p.prod_by = 0 
        LIMIT $prod_per_page OFFSET $offset";

    $result = mysqli_query($conn, $sql);

    if($result) {
        $prod = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$sql.'<br/>'.mysqli_error( $conn );
    }

    // Category
    $cat_sql = "SELECT * FROM `tbl_waton_category`";

    $cat_result = mysqli_query($conn, $cat_sql);

    if($cat_result) {
        $cat = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$cat_sql.'<br/>'.mysqli_error( $conn );
    }

    // Incomplete Products
    $incprod_sql = "SELECT p.*, c.category_name 
                    FROM `tbl_waton_product` p
                    LEFT JOIN `tbl_waton_category` c ON p.category_id = c.category_id
                    WHERE p.prod_name IS NULL OR p.prod_name = '' 
                        OR p.prod_photo IS NULL OR p.prod_photo = '' 
                        OR p.prod_price IS NULL 
                        OR p.category_id IS NULL";

                        $incprod_result = mysqli_query($conn, $incprod_sql);

    if ($incprod_result) {
        $incprod = mysqli_fetch_all($incprod_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php"); // Redirect to login if not logged in
        exit();
    }

    // User Products
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>waton for sellers - Home</title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . $page['page_logo'] ?>">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>
    <?php include("header.php"); ?>

    <div class="container pt-3">
        <?php
            if (isset($_SESSION['lowrole_msg'])) {
                echo $_SESSION['lowrole_msg'];
                unset($_SESSION['lowrole_msg']);
            }
        ?>
    </div>

    <!-- Category List -->
    <div class="container pt-3 my-3 bg-white shadow rounded">
        <div class="container">
            <div class="row pb-3">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <h1 class="display-6">Category</h1>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-end justify-content-center">
                    <?php if($admin_level != 3) :?>
                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#categoryModal">
                            <i class="bi bi-plus-square"></i> Add Category
                        </button>
                    <?php else :?>
                        &nbsp;
                    <?php endif;?>                   
                </div>
            </div>

            <div class="row">
                <?php
                    if (isset($_SESSION['alert_cat'])) {
                        echo $_SESSION['alert_cat'];
                        unset($_SESSION['alert_cat']);
                    }
                ?>
            </div>

            <!-- Modal for Add Category -->
            <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="categoryModalLabel">Add New Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="process/addcat-process.php" method="post" enctype="multipart/form-data" id="categoryForm">

                            <!-- Category Photo -->
                            <div class="container my-3">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <div class="cat-picture-container">
                                            <div class="cat-picture">
                                                <img src="../catphoto/empty.jpg" 
                                                    class="picture-src" id="wizardPicturePreview" title="">
                                                <input type="file" id="category_photo" class="" name="category_photo">
                                            </div>
                                            <label for="category_photo" style="color: #888;">Category Photo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-body">
                                <!-- Category Name -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="bi bi-collection-fill"></i>
                                    </span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="category_name" name="category_name" placeholder="" required />
                                        <label for="category_name">Category Name</label>
                                    </div>
                                </div>

                                <!-- Category Description -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="bi bi-card-text"></i>
                                    </span>
                                    <div class="form-floating">
                                        <textarea id="category_desc" name="category_desc" class="form-control" style="height: 100px;" placeholder=" " required></textarea>
                                        <label for="category_desc" class="form-label">Category Description</label>
                                    </div>                                
                                </div>
                            </div>                           

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="category_added" class="btn btn-success">Add Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row table-responsive">
                <table class="table table-hover table-striped-columns">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-list-ol"></i></th>
                            <th><i class="bi bi-collection-fill me-1"></i>Category Name</th>
                            <th><i class="bi bi-calendar-fill me-1"></i>Date Added</th>
                            <th><i class="bi bi-calendar-check-fill me-1"></i>Last Updated</th>

                            <?php
                                if (isset($_SESSION['admin_level'])) {
                                    $admin_level = $_SESSION['admin_level'];
                                }
                            ?>

                            <?php if($admin_level != 3) :?>
                                <th><i class="bi bi-gear me-1"></i>Actions</th>
                            <?php else :?>
                                &nbsp;
                            <?php endif;?>
                        </tr>
                    </thead>
                    <?php $countcat = 1; ?>
                    <?php foreach ($cat as $cats) : ?>
                    <tbody id="categoryTableBody">
                        <tr>
                            <td>
                                <h5 class="lead fw-bold">
                                    <?= $countcat++ ?>
                                </h5>
                            </td>
                            <td>
                                <i class="bi bi-collection"></i>
                                <span class="lead fw-semibold fs-6">
                                    <?= $cats['category_name'] ?>
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-calendar"></i>
                                <span class="lead fw-medium fs-6">
                                    <?= $cats['category_added'] ?>
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-calendar-check"></i>
                                <span class="lead fw-medium fs-6">
                                    <?= $cats['category_updated'] ?>
                                </span>
                            </td>
                            <?php if($admin_level != 3) :?>
                                <td>
                                    <!-- Edit Category Button -->
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#catEditModal<?= $cats['category_id'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Edit Category Modal -->
                                    <div class="modal fade" id="catEditModal<?= $cats['category_id'] ?>" tabindex="-1" aria-labelledby="catEditModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="categoryModalLabel">Edit Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="process/editcat-process.php" method="post" enctype="multipart/form-data" id="categoryForm">
                                                    <!-- Category Photo -->
                                                    <div class="container my-3">
                                                        <div class="row justify-content-center">
                                                            <div class="col-auto">
                                                                <div class="cat-picture-container">
                                                                    <div class="cat-picture">
                                                                        <input type="file" 
                                                                            id="edit_category_photo_<?= $cats['category_id'] ?>" 
                                                                            class="" 
                                                                            name="category_photo" 
                                                                            data-preview-id="edit_wizardPicturePreview_<?= $cats['category_id'] ?>">
                                                                        <img src="<?= BASE_URL . str_replace('../../', '../', $cats['category_photo']); ?>" 
                                                                            class="picture-src" 
                                                                            id="edit_wizardPicturePreview_<?= $cats['category_id'] ?>" 
                                                                            title="">
                                                                        <input type="hidden" name="current_image" value="<?= str_replace('../../', '../', $cats['category_photo']); ?>">
                                                                    </div>
                                                                    <label for="edit_category_photo_<?= $cats['category_id'] ?>" style="color: #888;">Category Photo</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="modal-body">
                                                        <!-- Category Name -->
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <i class="bi bi-collection-fill"></i>
                                                            </span>
                                                            <div class="form-floating">
                                                                <input type="hidden" name="category_id" value="<?= $cats['category_id'] ?>" />
                                                                <input type="text" class="form-control" id="category_name" name="category_name" value="<?= $cats['category_name'] ?>" required />
                                                                <label for="category_name">Category Name</label>
                                                            </div>
                                                        </div>

                                                        <!-- Category Description -->
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <i class="bi bi-card-text"></i>
                                                            </span>
                                                            <div class="form-floating">
                                                                <textarea id="category_desc" name="category_desc" class="form-control" style="height: 100px;" placeholder=" " required><?= htmlspecialchars($cats['category_desc']) ?></textarea>
                                                                <label for="category_desc" class="form-label">Category Description</label>
                                                            </div>                                
                                                        </div>
                                                    </div>
                                        
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="category_updated" class="btn btn-dark">
                                                            Save changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete category button trigger modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#catDeleteModal<?= $cats['category_id'] ?>">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>

                                    <!-- Delete Category Modal -->
                                    <div class="modal fade" id="catDeleteModal<?= $cats['category_id'] ?>" tabindex="-1" aria-labelledby="catDeleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="catDeleteModalLabel">Delete Category</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete <strong><?= $cats['category_name'] ?></strong> category?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a href="deletecat.php?id=<?= $cats['category_id'] ?>" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            <?php else :?>
                                &nbsp;
                            <?php endif;?>
                        </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <!-- Category List -->

    <hr class="mx-5" />

    <!-- Product List -->
    <div class="container pt-3 my-3 bg-white shadow rounded">
        <div class="container my-2">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <h1 class="display-6">Products</h1>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-end justify-content-center">
                    <a href="newprod.php">
                        <button type="button" class="btn btn-dark mx-2">
                            <i class="bi bi-plus-square"></i> New Product
                        </button>
                    </a>
                    <a href="exportprod.php">
                        <button type="button" class="btn btn-dark mx-2">
                            <i class="bi bi-filetype-xml"></i> Export XML
                        </button>
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Search product from the list -->
                <div class="col-lg-6 col-md-12 col-sm-12 my-2 d-flex justify-content-lg-start justify-content-center">
                    <div class="input-group">
                        <span class="input-group-text" id="search-icon">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search" class="form-control" id="prod-search" placeholder="Search" aria-label="Search" aria-describedby="search-icon" />
                    </div>
                </div>

                <!-- Filter product list by category -->
                <div class="col-lg-6 col-md-12 col-sm-12 my-2 d-flex justify-content-lg-end justify-content-center">
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

                    <button type="button" class="btn" id="tableview">
                        <i class="bi bi-table mx-1"></i> Table View
                    </button>
                    <button type="button" class="btn" id="cardview">
                        <i class="bi bi-card-heading mx-1"></i> Card View
                    </button>
                </div>
            </div>

            <div class="row">
                <?php
                if (isset($_SESSION['alert_prod'])) {
                    echo $_SESSION['alert_prod'];
                    unset($_SESSION['alert_prod']);
                }
                ?>
            </div>
        </div>

        <div class="container">
            <!-- Product Card -->
            <div class="row" id="prodcard">
                <?php foreach ($prod as $prods) : ?>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                        <div class="card bg-light shadow rounded mb-4 h-100">
                            <div class="img-container p-3">
                                <div class="ratio ratio-1x1">
                                    <?php if($prods['prod_by'] == 0): ?>
                                        <img src="<?= BASE_URL . str_replace('../../', '../', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                                    <?php else: ?>
                                        <img src="../<?=$prod['prod_photo'];?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                                    <?php endif; ?>
                                </div>
                            </div>
                        
                            <div class="card-body">                                          
                                <h5 class="card-title fw-semibold product-name">
                                    <?=$prods['prod_name']?>                           
                                </h5>         

                                <h6 class="cat fw-semibold product-cat">
                                    <?= !empty($prods['category_name']) ? $prods['category_name'] : '<p class="text-danger">No category</p>' ?>
                                </h6>

                                <h5 class="price fw-light product-price" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="bi bi-tags-fill"></i> 
                                    $<?=$prods['prod_price']?>
                                </h5>

                                <h6 class="stock fw-medium product-stock" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25): ?>
                                        Stock: <span class="text-success"><?=$prods['prod_stock']?></span>
                                    <?php elseif($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15): ?>
                                        Stock: <span class="text-warning"><?=$prods['prod_stock']?></span>
                                    <?php elseif($prods['prod_stock'] > 0): ?>
                                        Stock: <span class="text-danger"><?=$prods['prod_stock']?></span>
                                    <?php else: ?>
                                        <span class="text-danger">Out of stock</span>
                                    <?php endif?>
                                </h6>
                        
                                <div class="card-actions">
                                    <?php if($prods['prod_by'] != 1) : ?>
                                        <!-- Edit Product Button -->
                                        <a href="editprod.php?id=<?php echo $prods['prod_id'] ?>">
                                            <button type="button" class="btn btn-success">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </a>
                                    <?php else :?>
                                        &nbsp;
                                    <?php endif?>

                                    <!-- Delete Product Button with reusable modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-prod-id="<?= $prods['prod_id'] ?>" data-prod-name="<?= htmlspecialchars($prods['prod_name'], ENT_QUOTES) ?>">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Pagination for Card -->
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
            </div>

            <!-- Product Table -->
            <div class="row table-responsive" id="prodtable">
                <table class="table table-striped-columns table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-list-ol"></i></th>
                            <th><i class="bi bi-image me-1"></i>Photo</th>
                            <th><i class="bi bi-box-fill me-1"></i>Name</th>
                            <th><i class="bi bi-collection-fill me-1"></i>Category</th>
                            <th><i class="bi bi-tags-fill me-1"></i>Price</th>
                            <th><i class="bi bi-basket-fill me-1"></i>Stock</th>
                            <th><i class="bi bi-calendar-fill me-1"></i>Date Added</th>
                            <th><i class="bi bi-calendar-check-fill me-1"></i>Last Updated</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <?php $countprod = 1; ?>
                    <?php foreach ($prod as $prods) : ?>
                        <tbody>
                            <tr>
                                <td>
                                    <h5 class="lead fw-bold">
                                        <?= $countprod++ ?>
                                    </h5>
                                </td>
                                <td>
                                    <img src="<?= BASE_URL . $prods['prod_photo']; ?>" class="card-img-top" style="width: 100px; height: 100px; object-fit: cover" alt="" />
                                </td>
                                <td>
                                    <i class="bi bi-box"></i>
                                    <span class="lead fw-semibold fs-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
                                        <?= $prods['prod_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-collection"></i>
                                    <span class="lead fw-normal fs-6">
                                        <?= !empty($prods['category_name']) ? $prods['category_name'] : '<span class="text-danger">No category</span>' ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-tags"></i>
                                    <span class="lead fw-light fs-6">
                                        $<?= $prods['prod_price'] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-basket"></i>
                                    <span class="lead fw-medium fs-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
                                        <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25) : ?>
                                            Stock: <span class="text-success"><?= $prods['prod_stock'] ?></span>
                                        <?php elseif ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15) : ?>
                                            Stock: <span class="text-warning"><?= $prods['prod_stock'] ?></span>
                                        <?php elseif ($prods['prod_stock'] > 0) : ?>
                                            Stock: <span class="text-danger"><?= $prods['prod_stock'] ?></span>
                                        <?php else : ?>
                                            <span class="text-danger">Out of stock</span>
                                        <?php endif ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar"></i>
                                    <span class="lead fw-medium fs-6">
                                        <?= $prods['prod_created'] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar-check"></i>
                                    <span class="lead fw-medium fs-6">
                                        <?= $prods['prod_updated'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($prods['prod_by'] != 1) : ?>
                                        <!-- Edit Product Button -->
                                        <a href="editprod.php?id=<?php echo $prods['prod_id'] ?>">
                                            <button type="button" class="btn btn-success">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </a>
                                    <?php else :?>
                                        &nbsp;
                                    <?php endif?>

                                    <!-- Delete Product Button with reusable modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-prod-id="<?= $prods['prod_id'] ?>" data-prod-name="<?= htmlspecialchars($prods['prod_name'], ENT_QUOTES) ?>">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
                </table>

                <!-- Pagination link -->
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
            </div>
        </div>

        <!-- Delete Product Modal - Reusable -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Product Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the product <strong id="deleteModalProdName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="#" id="deleteModalConfirmBtn" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>       

        <!-- User Products -->
        <hr />

        <div class="container">
            <h1 class="display-6 text-lg-start text-center">User Products</h1>
            <h4 class="text-lg-start text-center" style="font-weight: 300">Products created by waton users</h4>

            <div class="row table-responsive" id="user-prodtable">
                <table class="table table-striped-columns table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-list-ol"></i></th>
                            <th><i class="bi bi-image me-1"></i>Photo</th>
                            <th><i class="bi bi-box-fill me-1"></i>Name</th>
                            <th><i class="bi bi-collection-fill me-1"></i>Category</th>
                            <th><i class="bi bi-tags-fill me-1"></i>Price</th>
                            <th><i class="bi bi-basket-fill me-1"></i>Stock</th>
                            <th><i class="bi bi-calendar-fill me-1"></i>Date Added</th>
                            <th><i class="bi bi-calendar-check-fill me-1"></i>Last Updated</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <?php $countprod = 1; ?>
                    <?php foreach ($user_prod as $prods) : ?>
                        <tbody>
                            <tr>
                                <td>
                                    <h5 class="lead fw-bold">
                                        <?= $countprod++ ?>
                                    </h5>
                                </td>
                                <td>
                                    <img src="../<?=$prods['prod_photo'];?>" class="card-img-top" style="width: 100px; height: 100px; object-fit: cover" alt="" />
                                </td>
                                <td>
                                    <i class="bi bi-box"></i>
                                    <span class="lead fw-semibold fs-6" style="overflow: hidden; text-overflow: ellipsis">
                                        <?= $prods['prod_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-collection"></i>
                                    <span class="lead fw-normal fs-6">
                                        <?= !empty($prods['category_name']) ? $prods['category_name'] : '<span class="text-danger">No category</span>' ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-tags"></i>
                                    <span class="lead fw-light fs-6">
                                        $<?= $prods['prod_price'] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-basket"></i>
                                    <span class="lead fw-medium fs-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
                                        <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25) : ?>
                                            Stock: <span class="text-success"><?= $prods['prod_stock'] ?></span>
                                        <?php elseif ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15) : ?>
                                            Stock: <span class="text-warning"><?= $prods['prod_stock'] ?></span>
                                        <?php elseif ($prods['prod_stock'] > 0) : ?>
                                            Stock: <span class="text-danger"><?= $prods['prod_stock'] ?></span>
                                        <?php else : ?>
                                            <span class="text-danger">Out of stock</span>
                                        <?php endif ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar"></i>
                                    <span class="lead fw-medium fs-6">
                                        <?= $prods['prod_created'] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar-check"></i>
                                    <span class="lead fw-medium fs-6">
                                        <?= $prods['prod_updated'] ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Delete Product Button with reusable modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-prod-id="<?= $prods['prod_id'] ?>" data-prod-name="<?= htmlspecialchars($prods['prod_name'], ENT_QUOTES) ?>">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
                </table>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($user_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?user_page=<?= $user_page - 1 ?>#user-prodtable">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $user_total_pages; $i++): ?>
                            <li class="page-item <?= $i == $user_page ? 'active' : '' ?>">
                                <a class="page-link" href="?user_page=<?= $i ?>#user-prodtable"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($user_page < $user_total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?user_page=<?= $user_page + 1 ?>#user-prodtable">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Incomplete Product List -->
        <?php if (!empty($incprod)) : ?>
            <hr />

            <div class="container">
                <h1 class="display-6 text-center">Incomplete Products</h1>
                <h4 class="text-center" style="font-weight: 300">These products have missing details</h4>

                <div class="row table-responsive" id="prodtable">
                    <table class="table table-striped-columns table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-list-ol"></i></th>
                                <th><i class="bi bi-image me-1"></i>Photo</th>
                                <th><i class="bi bi-box-fill me-1"></i>Name</th>
                                <th><i class="bi bi-collection-fill me-1"></i>Category</th>
                                <th><i class="bi bi-tags-fill me-1"></i>Price</th>
                                <th><i class="bi bi-basket-fill me-1"></i>Stock</th>
                                <th><i class="bi bi-calendar-fill me-1"></i>Date Added</th>
                                <th><i class="bi bi-calendar-check-fill me-1"></i>Last Updated</th>
                                <th><i class="bi bi-gear-fill me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <?php $countprod = 1; ?>
                        <?php foreach ($incprod as $prods) : ?>
                            <tbody>
                                <tr>
                                    <td>
                                        <h5 class="lead fw-bold">
                                            <?= $countprod++ ?>
                                        </h5>
                                    </td>
                                    <td>
                                        <img src="<?= BASE_URL . $prods['prod_photo']; ?>" class="card-img-top" style="width: 100px; height: 100px; object-fit: cover" alt="" />
                                    </td>
                                    <td>
                                        <i class="bi bi-box"></i>
                                        <span class="lead fw-semibold fs-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
                                            <?= $prods['prod_name'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-collection"></i>
                                        <span class="lead fw-normal fs-6">
                                            <?= !empty($prods['category_name']) ? $prods['category_name'] : '<span class="text-danger">No category</span>' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-tags"></i>
                                        <span class="lead fw-light fs-6">
                                            $<?= $prods['prod_price'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-basket"></i>
                                        <span class="lead fw-medium fs-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
                                            <?php if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25) : ?>
                                                Stock: <span class="text-success"><?= $prods['prod_stock'] ?></span>
                                            <?php elseif ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15) : ?>
                                                Stock: <span class="text-warning"><?= $prods['prod_stock'] ?></span>
                                            <?php elseif ($prods['prod_stock'] > 0) : ?>
                                                Stock: <span class="text-danger"><?= $prods['prod_stock'] ?></span>
                                            <?php else : ?>
                                                <span class="text-danger">Out of stock</span>
                                            <?php endif ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar"></i>
                                        <span class="lead fw-medium fs-6">
                                            <?= $prods['prod_created'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar-check"></i>
                                        <span class="lead fw-medium fs-6">
                                            <?= $prods['prod_updated'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Edit Product Button -->
                                        <a href="editprod.php?id=<?php echo $prods['prod_id'] ?>">
                                            <button type="button" class="btn btn-success">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </a>

                                        <!-- Delete Product Button with reusable modal -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-prod-id="<?= $prods['prod_id'] ?>" data-prod-name="<?= htmlspecialchars($prods['prod_name'], ENT_QUOTES) ?>">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endif ?>
        <!-- Incomplete Product List -->

    </div>

    <?php include('footer.php')?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            var currentProdSearch = '';
            var currentCatFilter = '';
            var currentPage = 1;

            function fetchProducts(prod_search, cat_filter, page = 1) {
                currentProdSearch = prod_search;
                currentCatFilter = cat_filter;
                currentPage = page;

                $('#prodcard').html('<center><img src="../img/loading.gif" style="width: 500px; height: 500px;"></center>');
                $('#prodtable').html('<center><img src="../img/loading.gif" style="width: 500px; height: 500px;"></center>');

                $.ajax({
                    type: 'POST',
                    url: 'process/search-process.php',
                    data: {
                        prod_search: prod_search,
                        cat_filter: cat_filter,
                        page: page
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        setTimeout(function () {
                            $('#prodcard').html(data.cardView);
                            $('#prodtable').html(data.tableView);
                        }, 800);
                    },
                    error: function () {
                        console.log('Error fetching products');
                        $('#prodcard').html('<div class="alert alert-danger">Error fetching products.</div>');
                        $('#prodtable').html('');
                    }
                });
            }

            // Initial fetch
            fetchProducts('', '', 1);

            $('#prod-search').on('keyup', function () {
                var prod_search = $(this).val();
                var cat_filter = $('#cat-filter').val();
                fetchProducts(prod_search, cat_filter, 1);
            });

            $('#cat-filter').on('change', function () {
                var cat_filter = $(this).val();
                var prod_search = $('#prod-search').val();
                fetchProducts(prod_search, cat_filter, 1);
            });

            // Pagination for table view
            $(document).on('click', '#prodtable .pagination a.page-link', function (e) {
                e.preventDefault();
                var page = $(this).data('page');
                if (page && page !== currentPage) {
                    fetchProducts(currentProdSearch, currentCatFilter, page);
                    $('html, body').animate({ scrollTop: $('#prodtable').offset().top - 100 }, 300);
                }
            });

            // Pagination for card view (this is the fix)
            $(document).on('click', '#prodcard .pagination a.page-link', function (e) {
                e.preventDefault();
                var page = $(this).data('page');
                if (page && page !== currentPage) {
                    fetchProducts(currentProdSearch, currentCatFilter, page);
                    $('html, body').animate({ scrollTop: $('#prodcard').offset().top - 100 }, 300);
                }
            });

            // Delete modal
            $(document).on('click', '.btn-danger[data-bs-toggle="modal"]', function () {
                var prodId = $(this).data('prod-id');
                var prodName = $(this).data('prod-name');

                $('#deleteModalProdName').text(prodName);
                $('#deleteModalConfirmBtn').attr('href', 'deleteprod.php?id=' + prodId);
            });
        });
    </script>

    <!-- Category Photo -->
    <script>
        $(document).ready(function() {
            $(document).on('change', '#category_photo', function() {
                readURL(this, '#wizardPicturePreview');
            });

            $(document).on('change', 'input[type="file"][id^="edit_category_photo_"]', function() {
                const previewId = $(this).data('preview-id');
                readURL(this, `#${previewId}`);
            });
        });

        function readURL(input, previewSelector) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(previewSelector).attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <!-- External JS -->
    <script src="../JS/script.js" type="text/javascript"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

