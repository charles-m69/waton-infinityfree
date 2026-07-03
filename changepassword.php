<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        // If not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }

    if(isset($_SESSION['user_id']) &&  isset($_SESSION['username'])) {
?>

<?php
    include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>waton - Change Password</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <section id="changepassword">
        <div class="container bg-light shadow rounded">
            <div class="row">

                <!-- to User Register -->
                <div class="col-lg-6 col-md-12 col-sm-12 ds mx-auto">

                    <h1 class="display-4 fw-semibold text-info-emphasis text-center">Change Password</h1>

                    <div class="alert alert-info">Note: Changing your password will require you to relogin to your account using the new password you will set.</div>

                    <form action="process/changepassword-process.php" method="post" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="id" value="<?= $user['user_id']; ?>">
                        <?php if(isset($_GET['error'])) { ?>
                            <div class="container">
                                <?php
                                    // Display error message from URL if exists
                                    if (isset($_GET['error'])) {
                                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
                                    }
                                ?>
                            </div>  
                        <?php } ?>

                        <!--Current Password-->
                        <div class="input-group mb-3">
                            <div class="form-floating">
                                <input type="password" id="user_password" name="user_password" class="form-control" placeholder=" ">
                                <label for="user_password" class="form-label">Current Password</label>
                            </div>                          
                        </div>

                        <!--New Password-->
                        <div class="input-group mb-3">
                            <div class="form-floating">
                                <input type="password" id="user_new_password" name="user_new_password" class="form-control" placeholder=" ">
                                <label for="user_new_password" class="form-label">New Password</label>
                            </div>                           
                        </div>

                        <!--Re-type Password-->
                        <div class="input-group mb-3">
                            <div class="form-floating">
                                <input type="password" id="user_retype_password" name="user_retype_password" class="form-control" placeholder=" ">
                                <label for="user_retype_password" class="form-label">Re-type Password</label>
                            </div>                           
                        </div>
                
                        <!--Submit Button-->
                        <button data-mdb-ripple-init type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="user_updated" id="user_updated">Save Changes</button>
                    </form>                     
                </div>
            </div>
        </div>
    </section>

    <?php include('footer.php'); ?>
    
    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</body>
</html>
<?php
    } else {
        Header("Location: settings.php");
        exit();
    }
?>