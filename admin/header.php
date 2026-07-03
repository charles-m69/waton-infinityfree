<?php
    include_once('connection.php');

    $sql = "SELECT * FROM tbl_waton_page WHERE page_id = 1";
    $result = mysqli_query($conn, $sql);
    $page = mysqli_fetch_assoc($result);
    
    // Check if the admin is logged in
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];

        // Fetch the specific admin's data
        $sql = "SELECT * FROM `tbl_waton_admin` WHERE `admin_id` = '$admin_id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['admin_level'] = $admin['admin_level'];
        } else {
            echo 'Error: ' . $sql . '<br/>' . mysqli_error($conn);
        }
    } else {
        // Handle the case where the admin is not logged in
        echo 'Admin not logged in.';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : $page['page_sitetitle'] ?></title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . $page['page_logo']; ?>">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

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
            <a href="<?=BASE_URL?>" class="navbar-brand ms-2 text-dark" id="waton">waton for sellers</a>

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
                        <a href="#" class="nav-link">Dashboard</a>
                    </li>
                </ul>

                <?php
                    if(isset($_SESSION['admin_level'])) {
                        $admin_level = $_SESSION['admin_level'];
                        
                        if (isset($_SESSION['admin_level'])) {
                            $admin_level = $_SESSION['admin_level'];
                
                            // 2 - Admin Level
                            if($admin_level == 2) {
                                echo <<<HTML
                                <span class="navbar-text">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{$admin['admin_photo']}" class="img-fluid rounded-circle" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                                                <div class="d-flex flex-column">
                                                    <span>{$admin['admin_username']}</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>                               
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="gensettings.php">
                                                        <i class="bi bi-toggles"></i>
                                                        General Settings
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="newprod.php">
                                                        <i class="bi bi-plus-square"></i>
                                                        New Product
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-bell-fill"></i>
                                                        Notifications
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-chat-left-dots-fill"></i>
                                                        Messages
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="settings.php">
                                                        <i class="bi bi-gear-fill"></i>
                                                        My Account
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="logout.php">
                                                        <i class="bi bi-box-arrow-right"></i>
                                                        Log Out
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </span>
                                HTML;
                            // 3 - Editor Level
                            } else if($admin_level == 3) {
                                echo <<<HTML
                                <span class="navbar-text">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{$admin['admin_photo']}" class="img-fluid rounded-circle" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                                                <div class="d-flex flex-column">
                                                    <span>{$admin['admin_username']}</span>
                                                    <small class="text-muted">Editor</small>
                                                </div>                               
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="newprod.php">
                                                        <i class="bi bi-plus-square"></i>
                                                        New Product
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-bell-fill"></i>
                                                        Notifications
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-chat-left-dots-fill"></i>
                                                        Messages
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="settings.php">
                                                        <i class="bi bi-gear-fill"></i>
                                                        My Account
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="logout.php">
                                                        <i class="bi bi-box-arrow-right"></i>
                                                        Log Out
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </span>
                                HTML;
                            // 1 - Super Admin Level
                            } else {
                                echo <<<HTML
                                <span class="navbar-text">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{$admin['admin_photo']}" class="img-fluid rounded-circle" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                                                <div class="d-flex flex-column">
                                                    <span>{$admin['admin_username']}</span>
                                                    <small class="text-muted">Super Admin</small>
                                                </div>                               
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="manageadmin.php">
                                                        <i class="bi bi-person-fill-gear"></i>
                                                        Manage Admin
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="gensettings.php">
                                                        <i class="bi bi-toggles"></i>
                                                        General Settings
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="newprod.php">
                                                        <i class="bi bi-plus-square"></i>
                                                        New Product
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-bell-fill"></i>
                                                        Notifications
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-chat-left-dots-fill"></i>
                                                        Messages
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="settings.php">
                                                        <i class="bi bi-gear-fill"></i>
                                                        My Account
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="logout.php">
                                                        <i class="bi bi-box-arrow-right"></i>
                                                        Log Out
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </span>
                                HTML;
                            }
                        }
                    } else {
                        header("Location: login.php"); // Redirect to login if not logged in
                        exit();
                    }
                ?>
                
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
</body>
</html>