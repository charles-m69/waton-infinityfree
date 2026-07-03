<?php
    session_start();

    include('connection.php');

    $alert_msg = '';


    if (isset($_GET['alert'])) {
        $alert_msg = $_GET['alert'];
    }

    if(!isset($_GET['id'])) {
        session_start();
        $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">Cannot edit, admin not found.</div>';
        header("Location: manageadmin.php");
        exit();
    } else {
        $id = $_GET['id'];

        if($_GET['id'] == $_SESSION['admin_id']) {
            header("Location: settings.php");
            exit();
        }

        $sql = "SELECT * FROM `tbl_waton_admin` WHERE `admin_id` = '$id'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $selectedadmin = mysqli_fetch_assoc($result);
        } else {
            session_start();
            $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">
                                        Error: '.$sql.'<br/>'.mysqli_error($conn).'
                                    </div>';
            exit();
        }
    }
?>

<?php
    $page_title = "Edit Admin";
    include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    

    <!-- Admin Settings Page -->
    <section id="admin-edit";>
        <div class="container bg-white shadow rounded mt-3">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 ds mx-auto">

                    <h1 class="display-4 fw-semibold text-info-emphasis text-center">Edit Admin</h1>
                    <h5 class="fw-thin text-center">You are editing <b><?=$selectedadmin['admin_username']?></b></h5>

                    <form action="process/editadmin-process.php" method="post" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="id" value="<?= $selectedadmin['admin_id']; ?>">
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
                                            <img src="<?=BASE_URL.$selectedadmin['admin_photo'];?>" 
                                                class="picture-src" id="wizardPicturePreview" title="">
                                            <input type="hidden" name="current_image" value="<?=$selectedadmin['admin_photo']?>">
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
                                <input type="text" id="admin_username" name="admin_username" class="form-control" placeholder=" " value="<?=$selectedadmin['admin_username']?>" required>
                                <label for="admin_username" class="form-label">Username</label>
                            </div>
                        </div>                               
                
                        <!--Email-->
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </span>
                            <div class="form-floating">
                                <input type="email" id="admin_email" name="admin_email" class="form-control" placeholder=" " value="<?=$selectedadmin['admin_email']?>" required>
                                <label for="admin_email" class="form-label">Email Address</label>
                            </div>
                        </div>
                        
                        <!-- Advanced -->
                        <div class="row">
                            <!-- Change Password -->
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <a href="changepassadmin.php?id=<?= $selectedadmin['admin_id']; ?>">
                                    <button data-mdb-ripple-init type="button" class="btn btn-success btn-block mb-4 w-100 text-white">Change Password</button>
                                </a>                                   
                            </div>

                            <!-- Save Changes Button -->
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="admin_updated" id="admin_updated">Save Changes</button>
                            </div>
                        </div>
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