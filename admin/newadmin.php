<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>waton - New Admin</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Registration Process -->
    <?php
        include("connection.php");

        $alert_msg = '';
        $photo_alert = '';
        
        // Start process when admin submits all credentials
        if (isset($_POST['admin_created'])) {
            $admin_username = mysqli_real_escape_string($conn, $_POST['admin_username']);
            $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
            $admin_password = mysqli_real_escape_string($conn, $_POST['admin_password']);
            $admin_password = md5($admin_password);
            $admin_level = mysqli_real_escape_string($conn, $_POST['admin_level']);

            // Verify if credentials already exists in the database
            $verify_sql = "SELECT `admin_username`, `admin_email` FROM `tbl_waton_admin` WHERE `admin_username` = '$admin_username' OR `admin_email` = '$admin_email'";

            $verify_result = mysqli_query($conn, $verify_sql);

            if(mysqli_num_rows($verify_result) != 0) {
                $alert_msg = '<div class="alert alert-danger" role="alert">
                                This admin account already exists
                            </div>';
            } else {
                $target_dir = "adminphoto/";
                $image_name = basename($_FILES["admin_photo"]["name"]);
                $target_file_path = $target_dir . $image_name;

                if (move_uploaded_file($_FILES["admin_photo"]["tmp_name"], $target_file_path)) {
                    $photo_alert = '<div class="alert alert-success" role="alert">
                                        Photo set successfully
                                    </div>';

                    // $admin_photo = substr($target_file_path, 2);
                    $admin_photo = 'adminphoto/' . $image_name;

                    // Store credentials to database
                    $insert_sql = "INSERT INTO `tbl_waton_admin`(`admin_username`, `admin_email`, `admin_password`, `admin_photo`, `admin_level`) VALUES ('$admin_username','$admin_email','$admin_password','$admin_photo', '$admin_level')";
    
                    $insert_result = mysqli_query($conn, $insert_sql);
    
                    if ( $insert_result ) {
                        session_start();
                        $_SESSION['alert_msg'] = '<div class="alert alert-success" role="alert">Admin account created successfully!</div>';
                        header('Location: manageadmin.php');
                        exit();
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

    <!-- Admin Creation Page -->
    <section id="admin-register">
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
                                }
                                // if ($photo_alert) {
                                //     echo $photo_alert;
                                // }'

                                if ($photo_alert) {
                                    echo $photo_alert;
                                }
                            ?>
                        </div>

                        <h1 class="mt-5 mb-0 text-center" id="waton" style="color:#fff;">waton</h1>
                        <h3 class="mt-0 mb-5 text-center" id="waton1" style="color:#fff;">for sellers</h3>
                        <p class="lead fw-light mb-3 text-light text-center">Administrator Account Creation For Super Admin</p>
                    </div>
    
                    <!--Right-->
                    <!-- to User Register -->
                    <div class="col-lg-6 col-md-12 col-sm-12">

                        <h1 class="display-4 fw-semibold text-info-emphasis">New Admin</h1>

                        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                            <!-- Profile Photo -->
                            <div class="container mb-5">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <div class="picture-container">
                                            <div class="picture">
                                                <img src="https://mdbootstrap.com/img/Photos/Others/placeholder-avatar.jpg" 
                                                    class="picture-src" id="wizardPicturePreview" title="">
                                                <input type="file" id="admin_photo" class="" name="admin_photo">
                                            </div>
                                            <label for="user_photo" style="color: #888;">Profile Photo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="text" id="admin_username" name="admin_username" class="form-control" placeholder=" " required>
                                    <label for="admin_username" class="form-label">Username</label>
                                </div>                               
                            </div>                               
                    
                            <!--Email-->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="email" id="admin_email" name="admin_email" class="form-control" placeholder=" " required>
                                    <label for="admin_email" class="form-label">Email Address</label>
                                </div>                               
                            </div>
                    
                            <!-- Password -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="password" id="admin_password" name="admin_password" class="form-control" placeholder=" " required>
                                    <label for="admin_password" class="form-label">Password</label>
                                </div>                               
                            </div>

                            <!-- Admin Level -->
                            <div class="form-floating">
                                <select name="admin_level" id="admin_level" class="form-select mb-3" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Editor</option>
                                </select>
                                <label for="admin_level">Admin Level</label>
                            </div>
                            
                    
                            <!--Submit Button-->
                            <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="admin_created" id="admin_created">Create Admin Account</button>
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
            $("#admin_photo").change(function(){
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