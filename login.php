<?php
    session_start();

    include('connection.php');
    
    // Redirect to homepage and prevent back action when logged in
    if(isset($_SESSION['user_id'])) {
        header( 'Location: '.BASE_URL);
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
    <title>waton - Sign In</title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> 

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <!-- Login Process -->
    <?php
        $alert_msg = '';

        if(isset($_POST['user_created'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $user_password = mysqli_real_escape_string($conn, $_POST['user_password']);
            $user_password = md5($user_password);

            // reCAPTCHA verification
            $recaptcha_secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // test secret key
            $recaptcha_response = $_POST['g-recaptcha-response'];

            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
            $response = json_decode($verify);

            if (!$response->success) {
                $alert_msg = '<div class="alert alert-danger" role="alert">CAPTCHA failed. Please try again.</div>';
            } else {
                $sql = "SELECT * FROM `tbl_waton_user` WHERE `username` = '$username' AND `user_password` = '$user_password'";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_email'] = $row['user_email'];
                    $_SESSION['user_password'] = $row['user_password'];
                    $_SESSION['user_photo'] = $row['user_photo'];

                    header('Location: '.BASE_URL);
                    exit();
                } else {
                    $alert_msg = '<div class="alert alert-danger" role="alert">Invalid user credentials</div>';
                }
            }
        }
    ?>

    <!-- User Login Page -->
    <section id="user-login">
        <div class="container">
            <div class="container bg-light mt-3 ms-auto rounded shadow">  
                <div class="row">
                    <!--Left-->
                    <div class="col-lg-6 col-md-12 col-sm-12 d-flex flex-column justify-content-center align-items-center bg-dark rounded" style="padding: 20px;">
                        <!-- Alert Message -->
                        <div class="container text-center">
                            <?php
                                if($alert_msg) {
                                    echo $alert_msg;
                                    unset($alert_msg);
                                }
                            ?>

                            <?php
                                if (isset($_SESSION['alert_msg'])) {
                                    echo $_SESSION['alert_msg'];
                                    unset($_SESSION['alert_msg']);
                                }
                            ?>
                        </div>

                        <img src="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>" class="picture-src img-fluid" alt="" style="width: 60px; height: 60px; object-fit: cover;">
                        <h1 class="text-center" id="waton" style="color:#fff;">waton</h1>   
                        <p class="lead fw-light mb-3 text-light text-center">Sign-in to your waton account</p>
                        <!-- to User Register -->
                        <a href="register.php">
                            <button data-mdb-ripple-init type="button" class="btn btn-light btn-block mb-4 w-100 text-dark" name="user_created">Sign-up</button>
                        </a>
                    </div>
    
                    <!--Right-->
                    <!-- to Admin Login -->
                    <div class="col-lg-6 col-md-12 col-sm-12">                                              
                    <div class="row">                               
                        <div class="col d-flex justify-content-end my-3">
                            <a href="admin/login.php" class="user-level">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                               

                        <h1 class="display-4 fw-semibold text-info-emphasis">Log In</h1>

                        <form action="" method="post" autocomplete="off">
                            <!-- Username -->
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="user-icon">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="text" id="username" name="username" class="form-control" placeholder=" " required>
                                    <label for="user_username" class="form-label">Username</label>
                                </div>                                
                            </div>                               
                    
                            <!-- Password -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="password" id="user_password" name="user_password" class="form-control" placeholder=" " required>
                                    <label for="user_password" class="form-label">Password</label>
                                </div>
                            </div>

                            <!-- Forgot password -->
                            <div class="form-group">
                                <a href="resetpassword.php" class="user-level">Forgot password</a>
                            </div>

                            <!--Checkbox-->
                            <!-- <div class="form-check">
                                <input type="checkbox" id="saveInfo" class="form-check-input">
                                <label for="saveInfo" class="form-check-label">Remember me</label>
                            </div> -->

                            <div class="g-recaptcha mb-3" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                    
                            <!--Submit Button-->
                            <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="user_created">Log In</button>
                        </form>                     
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <script>
        $(document).ready(function(){
        // Prepare the preview for profile picture
            $("#wizard-picture").change(function(){
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

    <!-- Require CAPTCHA verification -->
    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
            const response = grecaptcha.getResponse();
            if (response.length === 0) {
                e.preventDefault();
                alert("Please verify that you're not a robot.");
            }
        });
    </script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>