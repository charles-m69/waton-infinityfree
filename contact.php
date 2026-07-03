<?php
    session_start();
    include('connection.php');

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
    <title>Contact - <?=$page['page_sitetitle'];?></title>

    <!-- Tab Icon -->
    <link rel="icon" href="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
        if(isset($_POST['contactsubmit'])) {
            $_SESSION['alert_msg'] = "Bro we do not care lmao get outta here";
            header("Location: contact.php");
            exit();
        }
    ?>

    <!-- Header -->
    <?php
        if (!isset($_SESSION['user_id'])) {
            include("headerpublic.php");
        } else {
            include("header.php");
        }       
    ?>

    <div class="container my-3 p-3 bg-white shadow rounded">
        <div class="row my-3">
            <img src="<?= BASE_URL . str_replace('../', '', $page['page_logo']); ?>" class="picture-src img-fluid m-auto my-0" style="width: 90px; height: 70px; object-fit: cover;" alt="">       
            <h1 class="text-center my-0" style="font-family: waton;"><?=$page['page_sitetitle'];?></h1>    
        </div>

        <div class="container">
            <?php
                if (isset($_SESSION['alert_msg'])) {
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $_SESSION['alert_msg'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                    unset($_SESSION['alert_msg']);
                }
            ?>
        </div>

        <hr class="mx-5">

        <div class="row my-3">
            <h1 class="display-6 text-center fw-medium">Get in touch</h1>
            <p class="lead text-center">You may submit any request through the form below</p>
        </div>   

        <div class="row">
            <form action="" method="post">
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="form-floating">                       
                        <input type="text" class="form-control" id="name" placeholder="Enter your name">
                        <label for="name" class="form-label">Name</label>
                    </div>                  
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="form-floating">                       
                        <input type="text" class="form-control" id="email" placeholder="Enter your email">
                        <label for="email" class="form-label">Email</label>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="subject" aria-label="Floating label select example">
                        <option selected>Choose...</option>
                        <option value="1">Product Inquiry</option>
                        <option value="2">Order Status</option>
                        <option value="3">General Inquiry</option>
                        <option value="4">Feedback</option>
                        <option value="5">Other</option>
                        <option value="6">I'm black</option>
                    </select>
                    <label for="subject" class="form-label">Subject</label>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                    <div class="form-floating">                                             
                        <textarea class="form-control" id="message" rows="4" placeholder="Enter your message" style="height: 150px;"></textarea>
                        <label for="message" class="form-label">Message</label>
                    </div>                   
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile01">Upload a photo</label>
                    <input type="file" class="form-control" id="inputGroupFile01">
                </div>
                <button type="submit" name="contactsubmit" class="btn btn-dark">Submit</button>
            </form>
        </div>
    </div>

    <?php include('footer.php'); ?>
    
    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!--jQuery Minified v3.x.x-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>