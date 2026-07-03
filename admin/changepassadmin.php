<?php
session_start();
include("connection.php");

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">You are not logged in</div>';
    header("Location: login.php");
    exit();
}

// Check if user is a superadmin
$admin_id = $_SESSION['admin_id'];
$level_sql = "SELECT `admin_level` FROM `tbl_waton_admin` WHERE `admin_id` = '$admin_id'";
$level_result = mysqli_query($conn, $level_sql);

if ($level_result && mysqli_num_rows($level_result) > 0) {
    $row = mysqli_fetch_assoc($level_result);
    if ($row['admin_level'] != 1) {
        $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">You are not a superadmin</div>';
        header('Location: ' . BASE_URL);
        exit();
    }
} else {
    $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">Error fetching admin level</div>';
    header('Location: ' . BASE_URL);
    exit();
}

// Get the selected admin ID from GET parameter
if (!isset($_GET['id'])) {
    $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">Cannot change password, admin not found.</div>';
    header("Location: manageadmin.php");
    exit();
}

$selected_admin_id = intval($_GET['id']);

// Fetch the selected admin's username
$username_sql = "SELECT `admin_username` FROM `tbl_waton_admin` WHERE `admin_id` = '$selected_admin_id'";
$username_result = mysqli_query($conn, $username_sql);

if ($username_result && mysqli_num_rows($username_result) > 0) {
    $username_row = mysqli_fetch_assoc($username_result);
    $selected_admin_username = htmlspecialchars($username_row['admin_username']); // Escape for safety
} else {
    $_SESSION['alert_msg'] = '<div class="alert alert-danger" role="alert">Error fetching selected admin username</div>';
    header("Location: manageadmin.php");
    exit();
}

$page_title = "Change Admin Password";

include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Change Admin Password</title>
    
    <!-- CSS Style -->
    <link rel="stylesheet" href="../CSS/style.css" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<section id="changepassword">
    <div class="container mt-3 bg-white shadow rounded">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 ds mx-auto">
                <h1 class="display-4 fw-semibold text-info-emphasis text-center">Change Password</h1>

                <div class="alert alert-info">
                    Note: You are now changing the password of <strong><?= $selected_admin_username; ?></strong>
                </div>


                <form action="process/changepassadmin-process.php" method="post" autocomplete="off">
                    <!-- Hidden admin ID -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($selected_admin_id); ?>" />

                    <?php if (isset($_GET['error'])) : ?>
                    <div class="container">
                        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($_GET['error']); ?></div>
                    </div>
                    <?php endif; ?>

                    <!-- Current Password -->
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input type="password" id="admin_password" name="admin_password" class="form-control" placeholder=" " required />
                            <label for="admin_password" class="form-label">Current Password</label>
                        </div>
                        
                    </div>

                    <!-- New Password -->
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input type="password" id="admin_new_password" name="admin_new_password" class="form-control" placeholder=" " required />
                            <label for="admin_new_password" class="form-label">New Password</label>
                        </div>
                    </div>

                    <!-- Re-type Password -->
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input type="password" id="admin_retype_password" name="admin_retype_password" class="form-control" placeholder=" " required />
                            <label for="admin_retype_password" class="form-label">Re-type Password</label>
                        </div>                      
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-dark btn-block mb-4 w-100 text-white" name="admin_updated" id="admin_updated">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php')?>

<!-- JS -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
