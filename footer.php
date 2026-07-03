<?php
    // Top Categories
    $topcat_sql = "SELECT * FROM `tbl_waton_category` LIMIT 4";
    $topcat_result = mysqli_query($conn, $topcat_sql);

    if ($topcat_result) {
        $topcat = mysqli_fetch_all($topcat_result, MYSQLI_ASSOC);
    } else {
        echo 'Error: '.$topcat_sql.'<br/>'.mysqli_error($conn);
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
    <title>Document</title>

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
    <footer class="text-center text-lg-start text-white bg-dark">
        <div class="container p-4 pb-0">
            <section class="">
                <div class="row">
                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                    <img src="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>" class="picture-src img-fluid" alt="" style="width: 60px; height: 60px; object-fit: cover;">
                    <h5 class="text-lowecase mb-4 font-weight-bold" style="font-family: waton">waton</h5>
                    <p class="lead">We do not care about you, do better than everyone else, you get what you pay for.</p>
                </div>

                <hr class="w-100 clearfix d-md-none" />

                <!-- Top Categories -->
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h6 class="text-uppercase mb-4 font-weight-bold">Top Categories</h6>
                    <?php foreach($topcat as $cats) :?>
                    <p><a href="catprod.php?id=<?=$cats['category_id'];?>" class="text-white"><?=$cats['category_name']?></a></p>
                    <?php endforeach?>
                </div>

                <hr class="w-100 clearfix d-md-none" />

                <!-- Useful Links -->
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h6 class="text-uppercase mb-4 font-weight-bold">Useful links</h6>
                    <p><a href="prods.php" class="text-white">Products</a></p>
                    <p><a href="cat.php" class="text-white">Categories</a></p>
                    <p><a href="contact.php" class="text-white">Contact</a></p>
                    <p><a href="https://charles-m69.github.io/profilio/" class="text-white">About</a></p>
                </div>

                <hr class="w-100 clearfix d-md-none" />
                </div>
            </section>

            <hr class="my-3">

            <section class="p-3 pt-0">
                <div class="row d-flex align-items-center">
                    <div class="col-md-7 col-lg-8 text-center text-md-start">
                        <div class="p-3">
                        © 2026 Copyright:
                        <a class="text-white" href="#"
                            >waton.com</a
                            >
                        </div>
                    </div>

                    <!-- SocMed Links -->
                    <div class="col-md-5 col-lg-4 ml-lg-0 text-center text-md-end">
                        <div class="d-flex justify-content-center">
                            <!-- Link 1 -->
                            <a href="<?=$page['page_socmed1']?>" class="btn btn-floating" role="button">                  
                                <img src="<?= BASE_URL . str_replace('../', '', $page['page_socmed1icon']); ?>" class="picture-src img-fluid" alt="" style="max-width: 40px; max-height: 40px; object-fit: contain;">
                            </a>

                            <!-- Link 2 -->
                            <a href="<?=$page['page_socmed2']?>" class="btn btn-floating" role="button">  
                                <img src="<?= BASE_URL . str_replace('../', '', $page['page_socmed2icon']); ?>" class="picture-src img-fluid" alt="" style="max-width: 40px; max-height: 40px; object-fit: contain;">
                            </a>

                            <!-- Link 3 -->
                            <a href="<?=$page['page_socmed3']?>" class="btn btn-floating" role="button">  
                                <img src="<?= BASE_URL . str_replace('../', '', $page['page_socmed3icon']); ?>" class="picture-src img-fluid" alt="" style="max-width: 40px; max-height: 40px; object-fit: contain;">
                            </a>

                            <!-- Link 4 -->
                            <a href="<?=$page['page_socmed4']?>" class="btn btn-floating" role="button">  
                                <img src="<?= BASE_URL . str_replace('../', '', $page['page_socmed4icon']); ?>" class="picture-src img-fluid" alt="" style="max-width: 40px; max-height: 40px; object-fit: contain;">
                            </a>
                            
                            <!-- Link 5 -->
                            <a href="<?=$page['page_socmed5']?>" class="btn btn-floating" role="button">  
                                <img src="<?= BASE_URL . str_replace('../', '', $page['page_socmed5icon']); ?>" class="picture-src img-fluid" alt="" style="max-width: 40px; max-height: 40px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </footer>
</body>
</html>