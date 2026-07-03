<?php
    session_start();

    include('connection.php');

    $alert_msg = '';


    if (isset($_GET['alert'])) {
        $alert_msg = $_GET['alert'];
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    } else {
        $id = $_SESSION['user_id']; // Use the session ID
    }
    
    $sql = "SELECT * FROM `tbl_waton_user` WHERE `user_id` = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo 'Error: ' . $sql . '<br/>' . mysqli_error($conn);
    }

    // Pagination
    $prod_per_page = 8;
    $prod_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($prod_page - 1) * $prod_per_page;

    $total_prods_sql = "SELECT COUNT(*) as total FROM `tbl_waton_product` WHERE user_id = '$id'";
    $total_prods_result = mysqli_query($conn, $total_prods_sql);

    $total_prods = mysqli_fetch_assoc($total_prods_result)['total'];	
    $total_page = ceil($total_prods / $prod_per_page);

    // Products
    $prod_sql = "SELECT p.*, c.category_name FROM `tbl_waton_product` p 
    LEFT JOIN `tbl_waton_category` c ON p.category_id = c.category_id 
    WHERE p.user_id = '$id' 
    LIMIT $prod_per_page OFFSET $offset";


    $result = mysqli_query($conn, $prod_sql);

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

    // Page
    $page_sql = "SELECT * FROM tbl_waton_page WHERE page_id = 1";
    $result = mysqli_query($conn, $page_sql);
    $page = mysqli_fetch_assoc($result);
?>

<?php
    $page_title = "My Account";
    include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>waton - My Account</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <!-- User Settings Page -->
    <section id="user-edit";>
        <div class="container bg-white shadow rounded">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 ds mx-auto">

                    <h1 class="display-4 fw-semibold text-info-emphasis text-center">My Account</h1>

                    <form action="process/settings-process.php" method="post" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="id" value="<?= $user['user_id']; ?>">
                        <!-- Alert Message -->
                        <div class="container">
                            <?php
                                if (isset($_SESSION['alert_msg'])) {
                                    echo $_SESSION['alert_msg'];
                                    unset ($_SESSION['alert_msg']);
                                }
                                // if ($photo_alert) {
                                //     echo $photo_alert;
                                // }
                            ?>
                        </div>
                        <!-- Profile Photo -->
                        <div class="container mb-5">
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <div class="picture-container">
                                        <div class="picture">
                                            <img src="<?=BASE_URL.$user['user_photo'];?>" 
                                                class="picture-src" id="wizardPicturePreview" title="">
                                            <input type="hidden" name="current_image" value="<?=$user['user_photo']?>">
                                            <input type="file" id="user_photo" class="" name="user_photo">
                                        </div>
                                        <label for="user_photo" style="color: #888;">Profile Photo</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Full Name-->
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                Full Name
                            </span>
                            <input type="text" id="user_fname" name="user_fname" aria-label="First Name" class="form-control" placeholder="First Name" value="<?=$user['user_fname']?>" required>
                            <input type="text" id="user_lname" name="user_lname" aria-label="Last Name" class="form-control" placeholder="Last Name" value="<?=$user['user_lname']?>"  required>
                        </div> 

                        <!-- Username -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <div class="form-floating">
                                <input type="text" id="username" name="username" class="form-control" placeholder=" " value="<?=$user['username']?>" required>
                                <label for="username" class="form-label">Username</label>
                            </div>                           
                        </div>                               
                
                        <!--Email-->
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <div class="form-floating">
                                <input type="email" id="user_email" name="user_email" class="form-control" placeholder=" " value="<?=$user['user_email']?>" required>
                                <label for="user_email" class="form-label">Email Address</label>
                            </div>                           
                        </div>
                        
                        <!-- Advanced -->
                        <div class="row">
                            <!-- Change Password -->
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <a href="changepassword.php">
                                    <button data-mdb-ripple-init type="button" class="btn btn-success btn-block mb-4 w-100 text-white">Change Password</button>
                                </a>                                   
                            </div>

                            <!-- Delete Account -->
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <button data-mdb-ripple-init type="button" class="btn btn-danger btn-block mb-4 w-100 text-white" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
                            </div>
                            <!-- Delete Account Confirmation Modal -->
                            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete your account? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <!-- Confirm deletion button, linked to backend -->
                                        <a href="deleteaccount.php?id=<?= $user['user_id']; ?>" class="btn btn-danger">Delete Account</a>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                       
                        <!--Submit Button-->
                        <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="user_updated" id="user_updated">Save Changes</button>
                    </form>                     
                </div>
            </div>
        </div>
    </section>

    <hr class="mx-5">

    <!-- User Product -->
    <section id="userprod">
        <div class="container bg-white shadow rounded pt-3">
            <div class="container my-2">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <h1 class="display-6">My Products</h1>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-end justify-content-center">
                        <a href="newprod.php">
                            <button type="button" class="btn btn-dark">
                                <i class="bi bi-plus-square"></i> New Product
                            </button>
                        </a>
                    </div>
                </div>

                <!-- Alert Message -->
                <div class="container">
                    <?php
                        if (isset($_SESSION['alert_msg'])) {
                            echo $_SESSION['alert_msg'];
                            unset ($_SESSION['alert_msg']);
                        }
                    ?>
                </div>

                <!-- Products -->
                <div class="row" id="prodcard">
                    <?php if (!empty($prod)) :?>
                        <?php foreach ($prod as $prods) : ?>
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
                                                <!-- Edit Product Button -->
                                                <a href="editprod.php?id=<?php echo $prods['prod_id'] ?>">
                                                    <button type="button" class="btn btn-success">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                </a>

                                                <!-- Delete category button trigger modal -->
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#prodDeleteModal<?php echo $prods['prod_id'] ?>">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Delete Product Modal -->
                                <div class="modal fade" id="prodDeleteModal<?php echo $prods['prod_id'] ?>" tabindex="-1" aria-labelledby="prodDeleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="prodDeleteModalLabel">Delete Category</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete product <strong><?= $prods['prod_name'] ?></strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <a href="deleteprod.php?id=<?php echo $prods['prod_id'] ?>" class="btn btn-danger">Delete</a>
                                            </div>
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
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <h5 class="text-muted">You haven't created any products yet.</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include('footer.php'); ?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Profile Photo -->
    <script>
        $(document).ready(function(){
        // Prepare the preview for profile picture
            $("#user_photo").change(function(){
                readURL(this);
            });
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>