<?php
session_start();
include("../connection.php");

// Get filters from AJAX
$category = $_POST['category'] ?? '';
$priceMin = $_POST['price_min'] ?? '';
$priceMax = $_POST['price_max'] ?? '';
$sortOrder = $_POST['sort'] ?? 'desc';
$search = $_POST['search'] ?? '';
$page_number = $_POST['page'] ?? 1;
$limit = 12;
$offset = ($page_number - 1) * $limit;

// Get hot & cheap product IDs
$hot_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_stock DESC LIMIT 8";
$hot_result = mysqli_query($conn, $hot_sql);
$hot_ids = array_column(mysqli_fetch_all($hot_result, MYSQLI_ASSOC), 'prod_id');

$cheap_sql = "SELECT prod_id FROM tbl_waton_product ORDER BY prod_price ASC LIMIT 8";
$cheap_result = mysqli_query($conn, $cheap_sql);
$cheap_ids = array_column(mysqli_fetch_all($cheap_result, MYSQLI_ASSOC), 'prod_id');

// Base query
$sql = "SELECT p.*, c.category_name 
        FROM tbl_waton_product p 
        JOIN tbl_waton_category c ON p.category_id = c.category_id
        WHERE 1=1";

// Apply filters
if (!empty($category)) {
    $sql .= " AND p.category_id = " . intval($category);
}
if (is_numeric($priceMin)) {
    $sql .= " AND p.prod_price >= " . floatval($priceMin);
}
if (is_numeric($priceMax)) {
    $sql .= " AND p.prod_price <= " . floatval($priceMax);
}
if (!empty($search)) {
    $escaped = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (p.prod_name LIKE '%$escaped%' OR c.category_name LIKE '%$escaped%')";
}

// Sort and paginate
$sql .= " ORDER BY COALESCE(p.prod_updated, p.prod_created) " . ($sortOrder === 'asc' ? 'ASC' : 'DESC');
$sql .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

// Count total matching results
$count_sql = "SELECT COUNT(*) as total 
              FROM tbl_waton_product p 
              JOIN tbl_waton_category c ON p.category_id = c.category_id
              WHERE 1=1";

if (!empty($category)) {
    $count_sql .= " AND p.category_id = " . intval($category);
}
if (is_numeric($priceMin)) {
    $count_sql .= " AND p.prod_price >= " . floatval($priceMin);
}
if (is_numeric($priceMax)) {
    $count_sql .= " AND p.prod_price <= " . floatval($priceMax);
}
if (!empty($search)) {
    $escaped = mysqli_real_escape_string($conn, $search);
    $count_sql .= " AND (p.prod_name LIKE '%$escaped%' OR c.category_name LIKE '%$escaped%')";
}

$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_products = $count_row['total'];
$total_pages = ceil($total_products / $limit);

// Return products
if ($result && mysqli_num_rows($result) > 0) {
    while ($prods = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
            <a href="proddetail.php?id=<?= $prods['prod_id'] ?>" style="text-decoration: none; color: inherit;">
                <div class="card bg-light shadow rounded mb-4 h-100">
                    <div class="img-container p-3">
                        <div class="ratio ratio-1x1">
                            <img src="<?= BASE_URL . str_replace('../', '', $prods['prod_photo']); ?>" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-semibold product-name">
                            <?php if (in_array($prods['prod_id'], $cheap_ids)) : ?>
                                <span class="badge bg-success">Budget</span>
                            <?php endif; ?>
                            <?php if (in_array($prods['prod_id'], $hot_ids)) : ?>
                                <span class="badge bg-danger">Hot</span>
                            <?php endif; ?>
                            <?php if ($prods['prod_by'] == 1) : ?>
                                <span class="badge bg-primary">User</span>
                            <?php endif; ?>
                            <?= htmlspecialchars($prods['prod_name']) ?>
                        </h5>
                        <h6 class="cat fw-semibold product-cat text-muted">
                            <?= htmlspecialchars($prods['category_name']) ?>
                        </h6>
                        <h5 class="price fw-light product-price">
                            <i class="bi bi-tags-fill"></i>
                            $<?= number_format($prods['prod_price'], 2) ?>
                        </h5>
                        <h6 class="stock fw-medium product-stock">
                            <?php if ($prods['prod_stock'] >= 25): ?>
                                Stock: <span class="text-success fw-semibold"><?= $prods['prod_stock'] ?></span>
                            <?php elseif ($prods['prod_stock'] >= 15): ?>
                                Stock: <span class="text-warning fw-semibold"><?= $prods['prod_stock'] ?></span>
                            <?php elseif ($prods['prod_stock'] > 0): ?>
                                Stock: <span class="text-danger fw-semibold"><?= $prods['prod_stock'] ?></span>
                            <?php else: ?>
                                <span class="text-danger">Out of stock</span>
                            <?php endif; ?>
                        </h6>
                        <div class="card-actions">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($prods['user_id'] == $_SESSION['user_id']) : ?>
                                    <span class="text-muted">You created this product</span>
                                <?php else: ?>
                                    <!-- Logged-in user, not owner -->
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
                                <!-- Guest / Not Logged-in -->
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
        </div>
        <?php
    }
} else {
    echo '<div class="col-12 text-center"><p>No products found.</p></div>';
}

// Pagination
echo '<nav><ul class="pagination justify-content-center">';
if ($page_number > 1) {
    echo '<li class="page-item"><a class="page-link" href="#" data-page="' . ($page_number - 1) . '">&laquo;</a></li>';
}
for ($i = 1; $i <= $total_pages; $i++) {
    echo '<li class="page-item' . ($i == $page_number ? ' active' : '') . '">
            <a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a>
          </li>';
}
if ($page_number < $total_pages) {
    echo '<li class="page-item"><a class="page-link" href="#" data-page="' . ($page_number + 1) . '">&raquo;</a></li>';
}
echo '</ul></nav>';
?>
