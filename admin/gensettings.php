<?php
session_start();
include('connection.php');

$sql = "SELECT * FROM tbl_waton_page WHERE page_id = 1";
$result = mysqli_query($conn, $sql);
$page = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>General Settings</title>

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    <?php
        if (isset($_POST['page_submit'])) {
            $site_title = mysqli_real_escape_string($conn, $_POST['page_sitetitle']);
            $tagline = mysqli_real_escape_string($conn, $_POST['page_tagline']);
            $socmed = [];
            for ($i = 1; $i <= 5; $i++) {
                $socmed[$i] = mysqli_real_escape_string($conn, $_POST["page_socmed$i"]);
            }

            $page_id = 1;
            $upload_dir = "../pagephoto/";

            function handleImageUpload($inputName, $existingPath) {
                global $upload_dir;
                if (!empty($_FILES[$inputName]["name"])) {
                    $imageName = basename($_FILES[$inputName]["name"]);
                    $targetPath = $upload_dir . $imageName;
                    if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetPath)) {
                        return $targetPath;
                    }
                }
                return $existingPath;
            }

            // Fetch existing data for the current page
            $sql_fetch = "SELECT * FROM tbl_waton_page WHERE page_id = $page_id LIMIT 1";
            $result = mysqli_query($conn, $sql_fetch);
            $row = mysqli_fetch_assoc($result);

            // Handle logo upload
            $logo = handleImageUpload("site_logo", $row['page_logo']);

            // Handle banner uploads
            $banners = [];
            $banner_links = [];
            for ($i = 1; $i <= 5; $i++) {
                $banners[$i] = handleImageUpload("page_banner$i", $row["page_banner$i"]);
                $banner_links[$i] = mysqli_real_escape_string($conn, $_POST["page_banner{$i}link"]);
            }

            // Handle social media icon uploads
            $socmed_icons = [];
            for ($i = 1; $i <= 5; $i++) {
                $socmed_icons[$i] = handleImageUpload("page_socmed{$i}icon", $row["page_socmed{$i}icon"]);
            }

            // Update the database with the new values
            $sql_update = "UPDATE tbl_waton_page SET 
                page_logo = '$logo',
                page_sitetitle = '$site_title',
                page_tagline = '$tagline',
                page_banner1 = '{$banners[1]}',
                page_banner2 = '{$banners[2]}',
                page_banner3 = '{$banners[3]}',
                page_banner4 = '{$banners[4]}',
                page_banner5 = '{$banners[5]}',
                page_banner1link = '{$banner_links[1]}',
                page_banner2link = '{$banner_links[2]}',
                page_banner3link = '{$banner_links[3]}',
                page_banner4link = '{$banner_links[4]}',
                page_banner5link = '{$banner_links[5]}',
                page_socmed1 = '{$socmed[1]}',
                page_socmed1icon = '{$socmed_icons[1]}',
                page_socmed2 = '{$socmed[2]}',
                page_socmed2icon = '{$socmed_icons[2]}',
                page_socmed3 = '{$socmed[3]}',
                page_socmed3icon = '{$socmed_icons[3]}',
                page_socmed4 = '{$socmed[4]}',
                page_socmed4icon = '{$socmed_icons[4]}',
                page_socmed5 = '{$socmed[5]}',
                page_socmed5icon = '{$socmed_icons[5]}'
                WHERE page_id = $page_id";

            if (mysqli_query($conn, $sql_update)) {
                $_SESSION['alert_page'] = '<div class="alert alert-success" role="alert">Settings updated successfully.</div>';
                header("Location: gensettings.php");
                exit();
            } else {
                $_SESSION['alert_page'] = '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($conn) . '</div>';
            }

            mysqli_close($conn);
        }
    ?>


    <?php 
    $page_title = "General Settings";
    include('header.php');
    ?>

    <div class="container p-3 my-3 bg-light shadow rounded">
        <div class="container">
            <div class="row pb-3">
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-start justify-content-center">
                    <h1 class="display-6">General Settings</h1>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-start justify-content-center">
                    <?php
                    if (isset($_SESSION['alert_page'])) {
                        echo $_SESSION['alert_page'];
                        unset($_SESSION['alert_page']);
                    }
                    ?>
                </div>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="w-100">
                <div class="row">
                    <!-- Site Info-->
                    <div class="input-group">
                        <h3 class="lead">Site Info</h3>
                    </div>
                    <div class="input-group mb-5">
                        <span class="input-group-text">Title & Tagline</span>
                        <input type="text" name="page_sitetitle" placeholder="Site Title" aria-label="Site title" class="form-control" value="<?=$page['page_sitetitle']?>" required />
                        <input type="text" name="page_tagline" placeholder="Tagline" aria-label="Tagline" class="form-control" value="<?=$page['page_tagline']?>" required />
                    </div>

                    <!-- Logo Input-->
                    <div class="mb-3">
                        <div class="input-group">
                            <div class="row">
                                <h3 class="lead">Logo</h3>
                            </div>
                            
                            <div class="row">
                                <div class="logo-picture-container">
                                    <div class="logo-picture">
                                        <img src="<?=$page['page_logo']?>" class="picture-src" id="logoPreview" title="">
                                        <input type="file" id="site_logo" name="site_logo">
                                    </div>
                                    <label for="site_logo" style="color: #888;">Upload Logo</label>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>

                <hr>

                <!-- SocMed Link -->
                <div class="row">
                    <div class="input-group">
                        <h3 class="lead">Social Media Links</h3>
                    </div>

                    <div class="container mb-5">
                        <div class="row justify-content-center">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <div class="col-auto mb-3">
                                <div class="socmed-picture-container">
                                    <div class="socmed-picture">
                                        <img src="<?= htmlspecialchars($page["page_socmed{$i}icon"]) ?: 'default-icon.png' ?>" class="picture-src" id="socmedIconPreview<?=$i?>" title="">
                                        <input type="file" id="page_socmed<?=$i?>icon" name="page_socmed<?=$i?>icon">
                                    </div>
                                    <label for="page_socmed<?=$i?>icon" style="color: #888;">SocMed Icon <?=$i?></label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="bi bi-link-45deg"></i>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="page_socmed<?=$i?>" name="page_socmed<?=$i?>" placeholder="Link <?=$i?>" value="<?= htmlspecialchars($page["page_socmed$i"]) ?>">
                                        <label for="page_socmed<?=$i?>" class="text-muted">Social Link <?=$i?></label>
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Banners -->
                <div class="row">
                    <div class="input-group">
                        <h3 class="lead">Banners</h3>
                    </div>

                    <div class="container mb-5">
                        <div class="row justify-content-center">

                            <!-- Banner 1 -->
                            <div class="col-auto mb-3">
                                <div class="ban-picture-container">
                                    <div class="ban-picture">
                                        <img src="<?=$page['page_banner1']?>" class="picture-src" id="wizardPicturePreview1" title="">
                                        <input type="file" id="page_banner1" name="page_banner1">
                                    </div>
                                    <label for="page_banner1" style="color: #888;">Banner 1</label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="bi bi-link"></i>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="page_banner1link" name="page_banner1link" placeholder="Link 1" value="<?=$page['page_banner1link']?>">
                                        <label for="banner1_link" class="text-muted">Banner 1 Link</label>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Banner 2 -->
                            <div class="col-auto mb-3">
                                <div class="ban-picture-container">
                                    <div class="ban-picture">
                                        <img src="<?=$page['page_banner2']?>" class="picture-src" id="wizardPicturePreview2" title="">
                                        <input type="file" id="page_banner2" name="page_banner2">
                                    </div>
                                    <label for="page_banner2" style="color: #888;">Banner 2</label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="bi bi-link"></i>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="page_banner2link" name="page_banner2link" placeholder="Link 2" value="<?=$page['page_banner2link']?>">
                                        <label for="banner2_link" class="text-muted">Banner 2 Link</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Banner 3 -->
                            <div class="col-auto mb-3">
                                <div class="ban-picture-container">
                                    <div class="ban-picture">
                                        <img src="<?=$page['page_banner3']?>" class="picture-src" id="wizardPicturePreview3" title="">
                                        <input type="file" id="page_banner3" name="page_banner3">
                                    </div>
                                    <label for="page_banner3" style="color: #888;">Banner 3</label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="bi bi-link"></i>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="page_banner3link" name="page_banner3link" placeholder="Link 3" value="<?=$page['page_banner3link']?>">
                                        <label for="banner3_link" class="text-muted">Banner 3 Link</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Banner 4 -->
                            <div class="col-auto mb-3">
                                <div class="ban-picture-container">
                                    <div class="ban-picture">
                                        <img src="<?=$page['page_banner4']?>" class="picture-src" id="wizardPicturePreview4" title="">
                                        <input type="file" id="page_banner4" name="page_banner4">
                                    </div>
                                    <label for="page_banner4" style="color: #888;">Banner 4</label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="bi bi-link"></i>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="page_banner4link" name="page_banner4link" placeholder="Link 4" value="<?=$page['page_banner4link']?>">
                                        <label for="banner4_link" class="text-muted">Banner 4 Link</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Banner 5 -->
                            <div class="col-auto mb-3">
                                <div class="ban-picture-container">
                                    <div class="ban-picture">
                                        <img src="<?=$page['page_banner5']?>" class="picture-src" id="wizardPicturePreview5" title="">
                                        <input type="file" id="page_banner5" name="page_banner5">
                                    </div>
                                    <label for="page_banner5" style="color: #888;">Banner 5</label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="bi bi-link"></i>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="page_banner5link" name="page_banner5link" placeholder="Link 5" value="<?=$page['page_banner5link']?>">
                                        <label for="banner5_link" class="text-muted">Banner 5 Link</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" name="page_submit" class="btn btn-dark w-100 mt-3">Save Settings</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include('footer.php')?>

    <!-- External JS -->
    <script src="../JS/script.js" type="text/javascript"></script>

    <!-- Image Previews -->
    <script>
        // Logo Preview
        $(document).ready(function() {
            $("#site_logo").change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#logoPreview').attr('src', e.target.result).fadeIn('slow');
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Banner Preview
            for (let i = 1; i <= 5; i++) {
                $(`#page_banner${i}`).change(function() {
                    readURL(this, i, 'banner');
                });
            }

            // Social Media Icon Preview
            for (let i = 1; i <= 5; i++) {
                $(`#page_socmed${i}icon`).change(function() {
                    readURL(this, i, 'socmed');
                });
            }

            function readURL(input, index, type) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        if (type === 'banner') {
                            $(`#wizardPicturePreview${index}`).attr('src', e.target.result).fadeIn('slow');
                        } else if (type === 'socmed') {
                            $(`#socmedIconPreview${index}`).attr('src', e.target.result).fadeIn('slow');
                        }
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>