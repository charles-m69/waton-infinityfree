<?php
    include("connection.php");

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
    <title>waton - Sign Up</title>

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
    <!-- Registration Process -->
    <?php
        $alert_msg = '';
        $photo_alert = '';

        // Start process when user submits all credentials
        if (isset($_POST['user_created'])) {
            $user_fname = mysqli_real_escape_string($conn, $_POST['user_fname']);
            $user_lname = mysqli_real_escape_string($conn, $_POST['user_lname']);
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
            $user_password = mysqli_real_escape_string($conn, $_POST['user_password']);
            $user_password = md5($user_password);

            // Verify if credentials already exists in the database
            $verify_sql = "SELECT `username`, `user_email` FROM `tbl_waton_user` WHERE `username` = '$username' OR `user_email` = '$user_email'";

            $verify_result = mysqli_query($conn, $verify_sql);

            if(mysqli_num_rows($verify_result) != 0) {
                $alert_msg = '<div class="alert alert-danger" role="alert">
                                This user already exists
                            </div>';
            } else {
                $target_dir = "userphoto/";
                $image_name = basename($_FILES["user_photo"]["name"]);
                $target_file_path = $target_dir . $image_name;

                if (move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target_file_path)) {
                    $photo_alert = '<div class="alert alert-success" role="alert">
                                        Photo set successfully
                                    </div>';

                    // $user_photo = substr($target_file_path, 2);
                    $user_photo = 'userphoto/' . $image_name;


                    // Store credentials to database
                    $insert_sql = "INSERT INTO `tbl_waton_user`(`user_fname`, `user_lname`, `username`, `user_email`, `user_password`, `user_photo`) VALUES ('$user_fname','$user_lname','$username','$user_email','$user_password','$user_photo')";
    
                    $insert_result = mysqli_query($conn, $insert_sql);
    
                    if ( $insert_result ) {
                        $alert_msg = '<div class="alert alert-success" role="alert">
                                        Successful, welcome to waton! Sign-in to get started!
                                    </div>';
                    } else {
                        echo 'Error:'. $sql . "<br>" . mysqli_error($conn);
                    }
                } else {
                    $photo_alert = '<div class="alert alert-success" role="alert">
                                        Photo set failed
                                    </div>';
                }

                mysqli_close($conn);
            }
        }
    ?>

    <!-- User Registration Page -->
    <section id="user-register">
        <div class="container">
            <div class="container bg-light mt-3 ms-auto rounded shadow">  
                <div class="row">
                    <!--Left-->
                    <div class="col-lg-6 col-md-12 col-sm-12 d-flex flex-column justify-content-center align-items-center bg-dark rounded" style="padding: 20px;">
                        <!-- Alert Message -->
                        <div class="container">
                            <?php
                                if ($alert_msg) {
                                    echo $alert_msg;
                                    unset($alert_msg);
                                }
                                // if ($photo_alert) {
                                //     echo $photo_alert;
                                // }'

                                if ($photo_alert) {
                                    echo $photo_alert;
                                    unset($photo_alert);
                                }
                            ?>
                        </div>

                        <h1 class="mt-5 text-center" id="waton" style="color:#fff;">waton</h1>   
                        <p class="lead fw-light mb-3 text-light text-center">Create a waton account</p>
                        <!-- to User Login -->
                        <a href="login.php">
                            <button data-mdb-ripple-init type="button" class="btn btn-light btn-block mb-4 w-100 text-dark" name="user_created">Sign-in</button>
                        </a>
                    </div>

                    
    
                    <!--Right-->
                    <div class="col-lg-6 col-md-12 col-sm-12">

                        <!-- to Admin Register -->
                        <div class="d-flex justify-content-end my-3">
                            <a href="admin/register.php" class="user-level">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">

                                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>

                                </svg>
                            </a>
                        </div>

                        <h1 class="display-4 fw-semibold text-info-emphasis">Register</h1>


                        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                            <!-- Profile Photo -->
                            <div class="container mb-5">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <div class="picture-container">
                                            <div class="picture">
                                                <img src="https://mdbootstrap.com/img/Photos/Others/placeholder-avatar.jpg" 
                                                    class="picture-src" id="wizardPicturePreview" title="">
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
                                <input type="text" id="user_fname" name="user_fname" aria-label="First Name" class="form-control" placeholder="First Name" required>
                                <input type="text" id="user_lname" name="user_lname" aria-label="Last Name" class="form-control" placeholder="Last Name" required>
                            </div> 

                            <!-- Username -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="text" id="username" name="username" class="form-control" placeholder=" " required>
                                    <label for="username" class="form-label">Username</label>
                                </div>
                            </div>                               
                    
                            <!--Email-->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="email" id="user_email" name="user_email" class="form-control" placeholder=" " required>
                                    <label for="user_email" class="form-label">Email Address</label>
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
                    
                            <!--Submit Button-->
                            <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="user_created">Create Account</button>
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