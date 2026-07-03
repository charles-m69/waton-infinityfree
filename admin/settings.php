<?php
    session_start();

    include('connection.php');

    $alert_msg = '';


    if (isset($_GET['alert'])) {
        $alert_msg = $_GET['alert'];
    }

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    } else {
        $id = $_SESSION['admin_id']; // Use the session ID
    }
    
    $sql = "SELECT * FROM `tbl_waton_admin` WHERE `admin_id` = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $admin = mysqli_fetch_assoc($result);
    } else {
        echo 'Error: ' . $sql . '<br/>' . mysqli_error($conn);
    }
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
    <title>My Account</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <!-- Admin Settings Page -->
    <section id="admin-edit";>
        <div class="container bg-white shadow rounded my-3">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 ds mx-auto">

                    <h1 class="display-4 fw-semibold text-info-emphasis text-center">My Account</h1>

                    <form action="process/settings-process.php" method="post" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="id" value="<?= $admin['admin_id']; ?>">
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
                                            <img src="<?=BASE_URL.$admin['admin_photo'];?>" 
                                                class="picture-src" id="wizardPicturePreview" title="">
                                            <input type="hidden" name="current_image" value="<?=$admin['admin_photo']?>">
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
                                <i class="bi bi-person"></i>
                            </span>
                            <div class="form-floating">
                                <input type="text" id="admin_username" name="admin_username" class="form-control" placeholder=" " value="<?=$admin['admin_username']?>" required>
                                <label for="admin_username" class="form-label">Username</label>
                            </div>                           
                        </div>                               
                
                        <!--Email-->
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <div class="form-floating">
                                <input type="email" id="admin_email" name="admin_email" class="form-control" placeholder=" " value="<?=$admin['admin_email']?>" required>
                                <label for="admin_email" class="form-label">Email Address</label>
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
                                        <a href="deleteaccount.php?id=<?= $admin['admin_id']; ?>" class="btn btn-danger">Delete Account</a>
                                    </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                
                        <!--Submit Button-->
                        <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="admin_updated" id="admin_updated">Save Changes</button>
                    </form>                     
                </div>
            </div>
        </div>
    </section>

    <?php include('footer.php')?>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Profile Photo -->
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