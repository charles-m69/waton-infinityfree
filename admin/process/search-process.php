<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prod_search = $_POST['prod_search'] ?? '';
    $cat_filter = $_POST['cat_filter'] ?? '';
    $prod_page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

    $prod_per_page = 8;
    $offset = ($prod_page - 1) * $prod_per_page;

    $sql_base = "FROM `tbl_waton_product` p
            LEFT JOIN `tbl_waton_category` c ON p.category_id = c.category_id
            WHERE 1=1 AND p.prod_by = 0";

    if (!empty($prod_search)) {
        $prod_search = mysqli_real_escape_string($conn, $prod_search);
        $sql_base .= " AND p.prod_name LIKE '%$prod_search%'";
    }

    if (!empty($cat_filter)) {
        $cat_filter = mysqli_real_escape_string($conn, $cat_filter);
        $sql_base .= " AND p.category_id = '$cat_filter'";
    }

    // Get total count for pagination
    $count_sql = "SELECT COUNT(*) as total " . $sql_base;
    $count_result = mysqli_query($conn, $count_sql);
    $total_prods = 0;
    if ($count_result) {
        $row = mysqli_fetch_assoc($count_result);
        $total_prods = (int)$row['total'];
    }
    $total_page = ceil($total_prods / $prod_per_page);

    // Get paged products
    $sql = "SELECT p.*, c.category_name " . $sql_base . " LIMIT $prod_per_page OFFSET $offset";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $cardViewHTML = '';
        $tableBodyHTML = '';
        $countprod = $offset + 1;

        if (empty($products)) {
            $cardViewHTML = '<div class="alert alert-info text-center" role="alert">No products found.</div>';
            $tableBodyHTML = '<tr><td colspan="9" class="text-center">No products found.</td></tr>';
        } else {
            foreach ($products as $prods) {
               
                // Photo display
                $photoDisplay = '';
                if($prods['prod_by'] == 0) {
                    $photoDisplay = '<img src="' . BASE_URL . str_replace("../../", "../", $prods['prod_photo']) . '" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">';
                } else {
                    $photoDisplay = '<img src="../' . $prods['prod_photo'] . '" class="w-100 h-100 rounded" style="object-fit: cover;" alt="">';
                }

                // Stock display by color
                $stockDisplay = '';
                if ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 25) {
                    $stockDisplay = 'Stock: <span class="text-success fw-semibold">' . intval($prods['prod_stock']) . '</span>';
                } elseif ($prods['prod_stock'] > 0 && $prods['prod_stock'] >= 15) {
                    $stockDisplay = 'Stock: <span class="text-warning fw-semibold">' . intval($prods['prod_stock']) . '</span>';
                } elseif ($prods['prod_stock'] > 0) {
                    $stockDisplay = 'Stock: <span class="text-danger fw-semibold">' . intval($prods['prod_stock']) . '</span>';
                } else {
                    $stockDisplay = '<span class="text-danger">Out of stock</span>';
                }

                // Card View
                $cardViewHTML .= '<div class="col-6 col-sm-6 col-md-4 col-lg-3 p-3">
                    <div class="card bg-light shadow rounded mb-4 h-100">
                        <div class="img-container p-3">
                            <div class="ratio ratio-1x1">' . $photoDisplay . '</div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-semibold product-name">' . htmlspecialchars($prods['prod_name']) . '</h5>
                            <h6 class="cat fw-semibold product-cat text-muted">' . (!empty($prods['category_name']) ? htmlspecialchars($prods['category_name']) : '<span class="text-danger">No category</span>') . '</h6>
                            <h5 class="price fw-light product-price">
                                <i class="bi bi-tags-fill"></i> $' . htmlspecialchars($prods['prod_price']) . '
                            </h5>
                            <h6 class="stock fw-medium product-stock">' . $stockDisplay . '</h6>
                            <div class="card-actions">';
                if ($prods['prod_by'] != 1) {
                    $cardViewHTML .= '
                                <a href="editprod.php?id=' . intval($prods['prod_id']) . '">
                                    <button type="button" class="btn btn-success">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </a>';
                } else {
                    $cardViewHTML .= '&nbsp;';
                }
                $cardViewHTML .= '
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-prod-id="' . intval($prods['prod_id']) . '" data-prod-name="' . htmlspecialchars($prods['prod_name'], ENT_QUOTES) . '">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>';



                // Table view rows
                $tableBodyHTML .= '<tr>
                    <td><h5 class="lead fw-bold">' . $countprod++ . '</h5></td>
                    <td><img src="' . BASE_URL . $prods['prod_photo'] . '" class="card-img-top" style="width: 100px; height: 100px; object-fit: cover;" alt=""></td>
                    <td><i class="bi bi-box"></i> <span class="lead fw-semibold fs-6">' . htmlspecialchars($prods['prod_name']) . '</span></td>
                    <td><i class="bi bi-collection"></i> <span class="lead fw-normal fs-6">' . (!empty($prods['category_name']) ? htmlspecialchars($prods['category_name']) : '<span class="text-danger">No category</span>') . '</span></td>
                    <td><i class="bi bi-tags"></i> <span class="lead fw-light fs-6">$' . htmlspecialchars($prods['prod_price']) . '</span></td>
                    <td><i class="bi bi-basket"></i> <span class="lead fw-medium fs-6">' . $stockDisplay . '</span></td>
                    <td><i class="bi bi-calendar"></i> <span class="lead fw-medium fs-6">' . htmlspecialchars($prods['prod_created']) . '</span></td>
                    <td><i class="bi bi-calendar-check"></i> <span class="lead fw-medium fs-6">' . htmlspecialchars($prods['prod_updated']) . '</span></td>
                    <td>';
                if ($prods['prod_by'] != 1) {
                    $tableBodyHTML .= '
                        <a href="editprod.php?id=' . intval($prods['prod_id']) . '">
                            <button type="button" class="btn btn-success">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </a>';
                } else {
                    $tableBodyHTML .= '&nbsp;';
                }
                $tableBodyHTML .= '
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-prod-id="' . intval($prods['prod_id']) . '" data-prod-name="' . htmlspecialchars($prods['prod_name'], ENT_QUOTES) . '">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>';

            }
        }

        // Pagination links
        $paginationHTML = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
        if ($prod_page > 1) {
            $paginationHTML .= '<li class="page-item"><a href="#" class="page-link" data-page="' . ($prod_page - 1) . '">&laquo;</a></li>';
        }
        for ($i = 1; $i <= $total_page; $i++) {
            $activeClass = ($i === $prod_page) ? 'active' : '';
            $paginationHTML .= '<li class="page-item ' . $activeClass . '"><a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a></li>';
        }
        if ($prod_page < $total_page) {
            $paginationHTML .= '<li class="page-item"><a href="#" class="page-link" data-page="' . ($prod_page + 1) . '">&raquo;</a></li>';
        }
        $paginationHTML .= '</ul></nav>';

        // Append pagination to card view as well
        $cardViewHTML .= '<div class="container">' . $paginationHTML . '</div>';

        $tableViewHTML = '<div class="row table-responsive" id="prodtable">
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
                <tbody>' . $tableBodyHTML . '</tbody>
            </table>
            ' . $paginationHTML . '
        </div>';

        echo json_encode([
            'cardView' => $cardViewHTML,
            'tableView' => $tableViewHTML,
        ]);
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>
