<?php
session_start();
include('../connection.php');

if (isset($_POST['catId']) && isset($_POST['userId'])) {
    $catId = $_POST['catId'];
    $userId = $_POST['userId'];

    // Build query based on selected category
    $prod_sql = "SELECT p.*, c.category_name 
                 FROM `tbl_waton_product` p
                 LEFT JOIN `tbl_waton_category` c ON p.category_id = c.category_id
                 WHERE p.user_id = '$userId'";

    if ($catId !== '') {
        $prod_sql .= " AND p.category_id = '$catId'";
    }

    $result = mysqli_query($conn, $prod_sql);

    if ($result) {
        $prod = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (!empty($prod)) {
            foreach ($prod as $prods) {
                ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                    <a href="proddetail.php?id=<?= $prods['prod_id'] ?>" style="text-decoration: none; color: inherit;">
                        <div class="card bg-light shadow rounded mb-4 h-100">
                            <div class="img-container p-3">
                                <div class="ratio ratio-1x1">
                                    <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']) ?>" 
                                         class="w-100 h-100 rounded" 
                                         style="object-fit: cover;" 
                                         alt="Product Image">
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-semibold product-name">
                                    <?= htmlspecialchars($prods['prod_name']) ?>
                                </h5>

                                <h6 class="cat fw-semibold product-cat">
                                    <?= !empty($prods['category_name']) 
                                        ? htmlspecialchars($prods['category_name']) 
                                        : '<p class="text-danger">No category</p>' ?>
                                </h6>

                                <h5 class="price fw-light product-price" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="bi bi-tags-fill"></i>
                                    $<?= htmlspecialchars($prods['prod_price']) ?>
                                </h5>

                                <h6 class="stock fw-medium product-stock" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php if ($prods['prod_stock'] >= 25): ?>
                                        Stock: <span class="text-success"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif ($prods['prod_stock'] >= 15): ?>
                                        Stock: <span class="text-warning"><?= $prods['prod_stock'] ?></span>
                                    <?php elseif ($prods['prod_stock'] > 0): ?>
                                        Stock: <span class="text-danger"><?= $prods['prod_stock'] ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">Out of stock</span>
                                    <?php endif; ?>
                                </h6>

                                <div class="card-actions">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <?php if ($_SESSION['user_id'] == $prods['user_id']) : ?>
                                            <!-- Owner buttons -->
                                            <a href="editprod.php?id=<?= $prods['prod_id'] ?>">
                                                <button type="button" class="btn btn-success">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </a>

                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#prodDeleteModal<?= $prods['prod_id'] ?>">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        <?php else: ?>
                                            <!-- Non-owner buttons -->
                                            <?php if ($prods['prod_stock'] > 0): ?>
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-basket"></i> Basket
                                                    </button>
                                                </a>
                                                <a href="#">
                                                    <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                        <i class="bi bi-cash-coin"></i> Buy
                                                    </button>
                                                </a>
                                            <?php else: ?>
                                                <a href="#">
                                                    <button type="button" class="btn btn-dark fw-semibold">
                                                        <i class="bi bi-bookmark"></i> Wishlist
                                                    </button>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- Guest user buttons -->
                                        <?php if ($prods['prod_stock'] > 0): ?>
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-basket"></i> Basket
                                                </button>
                                            </a>
                                            <a href="#">
                                                <button type="button" class="btn btn-warning fw-semibold mb-1 mb-lg-0">
                                                    <i class="bi bi-cash-coin"></i> Buy
                                                </button>
                                            </a>
                                        <?php else: ?>
                                            <a href="#">
                                                <button type="button" class="btn btn-dark fw-semibold">
                                                    <i class="bi bi-bookmark"></i> Wishlist
                                                </button>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
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
                <?php
            }
        } else {
            echo '<div class="col-12 text-center py-5"><h5 class="text-muted">No products found in this category.</h5></div>';
        }
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request.';
}
