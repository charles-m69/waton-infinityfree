<?php
    session_start();

    include('connection.php');

    $alert_prod = "";

    $cat_sql = "SELECT * FROM `tbl_waton_category`";

    $cat_result = mysqli_query($conn, $cat_sql);

    if($cat_result) {
        $cat = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$cat_sql.'<br/>'.mysqli_error( $conn );
    }

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php"); // Redirect to login if not logged in
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
        $photo_alert = "";

        if(isset($_POST['prod_created'])) {
            $prod_name = mysqli_real_escape_string($conn, $_POST['prod_name']);
            $prod_desc = mysqli_real_escape_string($conn, $_POST['prod_desc']);
            $prod_price = mysqli_real_escape_string($conn, $_POST['prod_price']);
            $prod_stock = mysqli_real_escape_string($conn, $_POST['prod_stock']);
            $prod_by = 0;
            $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

            $target_dir = "../prodphoto/";
            $image_name = basename($_FILES["prod_photo"]["name"]);
            $target_file_path = $target_dir . $image_name;

            if(move_uploaded_file($_FILES["prod_photo"]["tmp_name"], $target_file_path)) {
                $photo_alert = '<div class="alert alert-success" role="alert">
                                        Photo set successfully
                                </div>';

                $prod_photo = '../prodphoto/' . $image_name;

                $insert_sql = "INSERT INTO `tbl_waton_product`(`prod_photo`, `prod_name`, `prod_desc`, `prod_price`, `prod_stock`, `prod_by`, `category_id`) VALUES ('$prod_photo','$prod_name','$prod_desc','$prod_price','$prod_stock','$prod_by','$category_id')";

                $insert_result = mysqli_query($conn, $insert_sql);

                if($insert_result) {
                    session_start();
                    $_SESSION['alert_prod'] = '<div class="alert alert-success" role="alert">
                                    Product has been added
                                </div>';
                    header("Location: ". BASE_URL);
                    exit();
                }
            } else {
                $photo_alert = '<div class="alert alert-success" role="alert">
                                    Photo set failed
                                </div>';
            }
        }
    ?>

    <?php
        include("header.php");
    ?>

    <div class="container bg-white shadow rounded my-3">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 ds mx-auto">

                <h1 class="display-4 fw-semibold text-info-emphasis text-center">Add New Product</h1>

                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <!-- Alert Message -->
                            <div class="container">
                                <?php
                                    if ($photo_alert) {
                                        echo $photo_alert;
                                    }
                                ?>
                            </div>
                            <!-- Product Image -->
                            <div class="container mb-5">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <div class="prod-picture-container">
                                            <div class="prod-picture">
                                                <img src="../prodphoto/empty.jpg" 
                                                    class="picture-src" id="wizardPicturePreview" title="">
                                                <input type="file" id="prod_photo" class="" name="prod_photo">
                                            </div>
                                            <label for="prod_photo" style="color: #888;">Product Photo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <!-- Product Name -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-box"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="text" id="prod_name" name="prod_name" class="form-control" placeholder=" " required>
                                    <label for="prod_name" class="form-label">Product Name</label>
                                </div>                              
                            </div>

                            <!-- Product Description -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <div class="form-floating">
                                    <textarea id="prod_desc" name="prod_desc" class="form-control" style="height: 150px;" placeholder=" " required></textarea>
                                    <label for="prod_desc" class="form-label">Product Description</label>
                                </div>                                
                            </div>

                            <!-- Product Category -->
                            <div class="form-floating">
                                <select name="category_id" id="category_id" class="form-select mb-3" required>
                                    <?php foreach ($cat as $cats) :?>
                                    <option name="category_id" id="category_id" value="<?=$cats['category_id']?>"><?=$cats['category_name']?></option>
                                    <?php endforeach;?>
                                </select>
                                <label for="category_id">Category</label>
                            </div>
                            

                            <!-- Product Price -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-tags-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="number" id="prod_price" name="prod_price" class="form-control" placeholder=" " step="0.01" required>
                                    <label for="prod_price" class="form-label">Product Price</label>
                                </div>                              
                            </div>

                            <!-- Product Stock -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-basket"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="number" id="prod_stock" name="prod_stock" class="form-control" placeholder=" " required>
                                    <label for="prod_stock" class="form-label">Product Stock</label>
                                </div>                               
                            </div>

                            <!-- Submit Button -->
                            <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="prod_created" id="prod_created">Add Product</button>
                        </div>  
                    </div>    
                </form>                     
            </div>
        </div>
    </div>

    <?php include('footer.php')?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Profile Photo -->
    <script>
        $(document).ready(function(){
        // Prepare the preview for profile picture
            $("#prod_photo").change(function(){
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>