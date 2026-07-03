<?php
    include_once('connection.php');

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
    <title>Header</title>

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

</head>
<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-sm bg-white shadow-sm" id="navbar-id">
        <div class="container">
            <!-- <a href="#" class="navbar-brand ms-2 text-dark" id="waton">waton</a> -->

            <a href="<?=BASE_URL?>" class="navbar-brand ms-2">
                <img src="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>" class="picture-src img-fluid" alt="" style="width: 60px; height: 60px; object-fit: cover;">
            </a>

            <!--Hamburger Menu Icon / For Mobile View-->
            <button class="navbar-toggler collapsed ms-auto me-2" type="button" data-bs-toggle="collapse" data-bs-target="#toggleMobileMenu" aria-controls="toggleMobileMenu" aria-expanded="false" aria-label="toggleNavigation">
                <span class="toggler-icon top-bar"></span>
                <span class="toggler-icon middle-bar"></span>
                <span class="toggler-icon bottom-bar"></span>
            </button>
            <!--Hamburger Menu Icon / For Mobile View-->

            <!--Nav Links-->
            <div class="collapse navbar-collapse my-2" id="toggleMobileMenu">
                <ul class="navbar-nav ms-auto">
                    <li>
                        <!-- Search Bar -->
                        <form id="global-search-form" action="prods.php" method="get" class="d-flex justify-content-center flex-grow-1 mx-3">
                            <div class="input-group w-100">
                                <span class="input-group-text" id="search-icon">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="search" name="search" class="form-control" id="prod-search" placeholder="Search" aria-label="Search" aria-describedby="search-icon">
                            </div>
                        </form>
                    </li>
                    <li>
                        <a href="<?=BASE_URL?>" class="nav-link">Home</a>
                    </li>
                    <li>
                        <a href="prods.php" class="nav-link">Products</a>
                    </li>
                    <li>
                        <a href="cat.php" class="nav-link">Categories</a>
                    </li>
                    <li>
                        <a href="contact.php" class="nav-link">Contact</a>
                    </li>
                    <li>
                        <a href="toledoweb/mypersonalweb.html" class="nav-link">About</a>
                    </li>
                    <li>
                        <a href="login.php" class="nav-link">
                            <i class="bi bi-person-circle"></i>
                            Sign-in
                        </a>                       
                    </li>
                </ul>
            </div>
            <!--Nav Links-->

            <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0"></div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0"></div>
            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0"></div>
            <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0"></div>
            </div>
        </div>           
    </nav>      
    <!--Navbar-->

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>
</html>