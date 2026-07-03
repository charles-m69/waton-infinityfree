<?php
    session_start();

    include('connection.php');

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php"); // Redirect to login if not logged in
        exit();
    }

    // Check if user is a superadmin
    if (isset($_SESSION['admin_level'])) {
        $admin_id = $_SESSION['admin_id'];
        $level_sql = "SELECT `admin_level` FROM `tbl_waton_admin` WHERE `admin_id` = '$admin_id'";

        $level_result = mysqli_query($conn, $level_sql);

        if($level_result && mysqli_num_rows($level_result) == 1) {
            $row = mysqli_fetch_assoc($level_result);
            $_SESSION['admin_level'] = $row['admin_level'];
        } else {
            header('Location: logout.php');
            exit();
        }
    }

    if($_SESSION['admin_level'] != 1) {
        $_SESSION['lowrole_msg'] = '<div class="alert alert-success" role="alert">
                                        Role changed successfuly, you have been redirected out of the Admin Management page because you no longer have Super Admin privileges.
                                    </div>';
        header('Location: '. BASE_URL);
        exit();
    }


    // Fetch all admins from the database
    $sql = "SELECT * FROM `tbl_waton_admin`";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $alladmin = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo 'Error: ' . $sql . '<br/>' . mysqli_error($conn);
    }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
        include('header.php');
    ?>

    <div class="container pt-3 mt-3 bg-white shadow rounded">
        <div class="container">
            <div class="row pb-3">
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-start justify-content-center">
                    <h1 class="display-6">Manage Administrator</h1>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-end justify-content-center">
                    <a href="newadmin.php">
                        <button type="button" class="btn btn-dark mx-2">
                            <i class="bi bi-person-fill-add"></i>
                            New Admin
                        </button>
                    </a>
                </div>
            </div>

            <div class="row">
                <?php
                    if (isset($_SESSION['alert_msg'])) {
                        echo $_SESSION['alert_msg'];
                        unset($_SESSION['alert_msg']);
                    }
                ?>
            </div>

            <div class="row table-responsive ">
                <table class="table table-striped-columns table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-list-ol"></i></th>
                            <th><i class="bi bi-person-bounding-box me-1"></i>Photo</th>
                            <th><i class="bi bi-person-fill me-1"></i>Name</th>
                            <th><i class="bi bi-envelope-at-fill me-1"></i>Email</th>
                            <th><i class="bi bi-person-gear me-1"></i>Admin Level</th>
                            <th><i class="bi bi-calendar-fill me-1"></i>Created at</th>
                            <th><i class="bi bi-calendar-check-fill me-1"></i>Updated at</th>
                            <th><i class="bi bi-gear me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <?php $count = 1;?>
                    <?php foreach ($alladmin as $alladmins) :?>
                        <tbody>
                            <tr>
                                <td>
                                    <h5 class="lead fw-bold">
                                        <?=$count++?>
                                    </h5>
                                </td>
                                <td>
                                    <img src="<?=BASE_URL.$alladmins['admin_photo'];?>" class="card-img-top" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                                </td>
                                <td>                                                                    
                                    <?php if ($alladmins['admin_id'] == $_SESSION['admin_id']): ?>
                                        <i class="bi bi-person-fill text-success" style="font-size: 15px;"></i>
                                        <span class="lead fw-semibold fs-6">
                                            <?=$alladmins['admin_username']?>
                                        </span>                                        
                                        <h6 class="text-success my-1" style="font-size: 13px; font-weight: 500;">
                                            logged in
                                        </h6>
                                    <?php else: ?>
                                        <i class="bi bi-person"></i>  
                                        <span class="lead fw-semibold fs-6">
                                            <?=$alladmins['admin_username']?>
                                        </span> 
                                    <?php endif?>
                                </td>
                                <td>
                                    <h6 style="font-weight: 300;"> 
                                        <i class="bi bi-envelope-at"></i> 
                                        <span class="lead fw-light fs-6">
                                            <?=$alladmins['admin_email']?>
                                        </span>                                                                            
                                    </h6>                                   
                                </td>
                                <td>
                                    <div class="container">
                                        <form action="process/manageadmin-process.php" method="post">
                                            <div class="form-floating mb-1">
                                                <input type="hidden" name="admin_id" value="<?=$alladmins['admin_id'];?>">
                                                <select class="form-select" name="admin_level" id="admin_level_<?=$alladmins['admin_id'];?>">
                                                    <?php
                                                    // Define admin levels
                                                    $admin_levels = [
                                                        1 => "Super Admin",
                                                        2 => "Admin",
                                                        3 => "Editor"
                                                    ];

                                                    foreach ($admin_levels as $level => $label) {
                                                        $selected = ($alladmins['admin_level'] == $level) ? 'selected' : 'No privileges';
                                                        echo "<option value=\"$level\" $selected>$label</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label for="admin_level"><i class="bi bi-person-fill-gear me-1"></i>Role</label>
                                            </div>
                                            <div class="row">
                                                <button type="submit" class="btn btn-dark">
                                                    Change
                                                </button>
                                            </div>
                                        </form>
                                    </div>                                   
                                </td>
                                <td>
                                    <i class="bi bi-calendar"></i>
                                    <span class="lead fw-medium fs-6">
                                        <?=$alladmins['admin_created']?>
                                    </span>                                   
                                </td>
                                <td>
                                    <i class="bi bi-calendar-check"></i>
                                    <span class="lead fw-medium fs-6">
                                        <?=$alladmins['admin_updated']?>
                                    </span>                                  
                                </td>
                                <td>                               
                                    <!-- Edit admin info button -->
                                    <a href="editadmin.php?id=<?= $alladmins['admin_id'] ?>">
                                        <button type="button" class="btn btn-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                                            </svg>
                                        </button>
                                    </a>

                                    <?php if ($alladmins['admin_id'] == $_SESSION['admin_id']): ?>
                                        &nbsp;
                                    <?php else: ?>
                                    
                                        <!-- Delete category button trigger modal -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#catDeleteModal<?= $alladmins['admin_id'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                            </svg>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="catDeleteModal<?= $alladmins['admin_id'] ?>" tabindex="-1" aria-labelledby="adminDeleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="adminDeleteModalLabel">Remove Admin</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to remove admin <strong><?= $alladmins['admin_username']?></strong>? This action means that this account will be fully deleted from the database.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a href="deleteadmin.php?id=<?=$alladmins['admin_id']?>" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif?>
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach?>
                </table>
            </div>
        </div>
    </div>

    <?php include('footer.php')?>

    <!-- External JS -->
    <script src="../JS/script.js" type="text/javascript"></script>

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>