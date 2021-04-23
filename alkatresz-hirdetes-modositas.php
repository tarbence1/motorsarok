<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

$errorMsg = ""; // Error messages
$maxSize = 2 * 1024 * 1024; // Image max size (2Mb)
$ok = true; // Check if everything ok

// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    $_SESSION['url'] = 'alkatresz-hirdetes-modositas.php';
    header("Location: bejelentkezes.php");
    exit;
}

// Set the id or redirect the user if unset
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: alkatresz-hirdeteseim.php");
}

// Set the userid
$userid = $_SESSION['id'];

// Select parts images
$sql = "SELECT * FROM parts_images WHERE productid = ? ORDER BY is_mainimage DESC";
$stmt2 = $link->prepare($sql);
$id = $_GET['id'];
$stmt2->bind_param('i', $id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$data = $result2->fetch_all(MYSQLI_ASSOC);

// Count the selected images
$countImages = mysqli_num_rows($result2);

// Select counties
$counties_sql = "SELECT * FROM counties ORDER BY name";
$counties_result = $link->query($counties_sql);


// Select premium level for the user
$sql3 = "SELECT premium FROM users WHERE id = ?";
$stmt3 = $link->prepare($sql3);
$stmt3->bind_param("i", $userid);
$stmt3->execute();
$result = $stmt3->get_result();
$rec = $result->fetch_assoc();
$premium = $rec['premium'];

// Set the maximum number of images that can be uploaded
switch ($premium) {
    case 0:
        $maxImage = 5;
        break;
    case 1:
        $maxImage = 7;
        break;
    case 2:
        $maxImage = 10;
        break;
    case 3:
        $maxImage = 12;
        break;
    default:
        $maxImage = 5;
}

// Count how many more images you can upload
$moreImages = $maxImage - $countImages;

// Advert status variable
$status = 1;

// Select parts table
$getstmt = $link->prepare("SELECT * FROM parts WHERE id = ? AND status = ?");
if (
    $getstmt and
    $getstmt->bind_param('ii', $id, $status) and
    $getstmt->execute() and
    $result = $getstmt->get_result() and
    $row = $result->fetch_assoc()
) {
    if ($row['userid'] == $userid) {
        $product_name = $row['product_name'];
        $price = $row['price'];
        $motorcycle_type = $row['motorcycle_type'];
        $cond = $row['cond'];
        $description = $row['description'];
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $phone2 = $row['phone2'];
        $county = $row['county'];
        $settlement = $row['settlement'];
    } else {
        header("Location: alkatreszek.php");
        exit;
    }

    // Attempt to modify
    if (isset($_POST['upload'])) {

        if (isset($_POST['product_name']) && $_POST['product_name'] != '') {
            $product_name = $_POST['product_name'];
        } else {
            $errorMsg = "Kérjük adja meg a termék nevét!";
            $ok = false;
        }

        if (isset($_POST['price']) && $_POST['price'] != '') {
            $price = $_POST['price'];
        } else {
            $errorMsg = "Kérjük adja meg az árat!";
            $ok = false;
        }

        if (isset($_POST['cond']) && $_POST['cond'] != '') {
            $cond = $_POST['cond'];
        } else {
            $errorMsg = "Kérjük válassza ki az állapotot!";
            $ok = false;
        }

        if (isset($_POST['name']) && $_POST['name'] != '') {
            $name = $_POST['name'];
        } else {
            $errorMsg = "Kérjük adja meg a nevét!";
            $ok = false;
        }

        if (isset($_POST['phone']) && $_POST['phone'] != '') {
            $phone = $_POST['phone'];
        } else {
            $errorMsg = "Kérjük adja meg a telefonszámát!";
            $ok = false;
        }

        if (isset($_POST['county']) && $_POST['county'] != '') {
            $county = $_POST['county'];
        } else {
            $errorMsg = "Kérjük válassza ki a megyét!";
            $ok = false;
        }

        if (isset($_POST['settlement']) && $_POST['settlement'] != '') {
            $settlement = $_POST['settlement'];
        } else {
            $errorMsg = "Kérjük adja meg a település nevét!";
            $ok = false;
        }

        $motorcycle_type = $_POST['motorcycle_type'];
        $description = $_POST['description'];
        $email = $_POST['email'];
        $phone2 = $_POST['phone2'];


        // Check lengths
        if (
            strlen($product_name) > 50  || strlen($price)  > 10 || strlen($motorcycle_type) > 100 || strlen($description) > 1000 || strlen($name) > 50 || strlen($email) > 50 || strlen($settlement) > 50
        ) {
            $errorMsg = "Kérjük megfelelő hosszúságú válaszokat adjon meg!";
            $ok = false;
        }

        // Check if phone numbers are valid
        if (!preg_match("/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/", $phone)) {
            $ok = false;
            $errorMsg = "Kérjük valós telefonszámot adjon meg!";
        }
        if (!empty($phone2) && $phone2 !== "" && !preg_match("/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/", $phone2)) {
            $ok = false;
            $errorMsg = "Kérjük valós 2. telefonszámot adjon meg!";
        }



        $stmt = $link->prepare("UPDATE parts SET product_name=?, price=?, motorcycle_type=?, cond=?, description=?, name=?, email=?, phone=?, phone2=?, county=?, settlement=? WHERE id = ?");

        $stmt->bind_param(
            "sisssssssssi",
            $product_name,
            $price,
            $motorcycle_type,
            $cond,
            $description,
            $name,
            $email,
            $phone,
            $phone2,
            $county,
            $settlement,
            $id
        );
        if ($ok) {
            //IMAGE UPLOAD
            $filesize_error = 0;
            $filesTempName = $_FILES['images']['tmp_name'];
            $filesName = $_FILES['images']['name'];
            $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);

            // Update main image
            if (!empty($filesTempName)) {
                $detectedType = exif_imagetype($filesTempName);
                if ($_FILES["images"]["size"] > $maxSize) {
                    $filesize_error = 1;
                    $errorMsg = "A képnek 2Mb-nál kisebbnek kell lennie!";
                } elseif (!in_array($detectedType, $allowed_types)) {
                    $errorMsg = "A kép csak PNG/JPG/JPEG/GIF formátumban elfogadott!";
                } elseif ($filesize_error == 0) {

                    $statement = $link->prepare("UPDATE parts_images SET thumbnailimage=? WHERE id=?");


                    $file = $filesTempName;
                    $is_mainimage = 0;
                    if (is_uploaded_file($file) && !empty($file)) {
                        $data = "parts-uploads/" . time() . $_FILES["images"]["name"];
                        $imageid = $_POST['mainimageid'];
                        move_uploaded_file($file, $data);

                        $statement->bind_param("si", $data, $imageid);
                        $stmt->execute();
                        $statement->execute();
                    }
                    $statement->close();
                }
            }

            $filesize_error2 = 0;
            $filesTempName2 = $_FILES['files']['tmp_name'];
            $filesName2 = $_FILES['files']['name'];
            $counted = count($filesName2);

            // Upload more images
            for ($i = 0; $i < $counted; $i++) {
                if (!empty($filesTempName2[$i])) {
                    if ($counted > $moreImages) {
                        $errorMsg = "Túllépte a maximálisan feltölthető képek számát!";
                    } else {
                        $detectedType2 = exif_imagetype($filesTempName2[$i]);
                        if ($_FILES["files"]["size"][$i] > $maxSize) {
                            $filesize_error2 = 1;
                            $errorMsg = "Minden képnek 2 Mb-nál kisebbnek kell lennie!";
                        } elseif (!in_array($detectedType2, $allowed_types)) {
                            $errorMsg = "A képek csak PNG/JPG/JPEG/GIF formátumban elfogadottak!";
                        } elseif ($filesize_error2 == 0) {
                            $productid = $_GET['id'];
                            $statement2 = $link->prepare("INSERT INTO parts_images(thumbnailimage, productid, is_mainimage) VALUES(?, ?, ?)");
                            $file = $filesTempName2[$i];
                            if (is_uploaded_file($file) && !empty($file)) {
                                $data = "parts-uploads/" . time() . $_FILES["files"]["name"][$i];
                                move_uploaded_file($file, $data);
                                $is_mainimage = 0;
                                $statement2->bind_param("sii", $data, $productid, $is_mainimage);
                                $stmt->execute();
                                $statement2->execute();
                            }
                            $statement2->close();
                        }
                    }
                }
            }

            $stmt->execute();
            $stmt->close();
            $_SESSION['success-modify'] = true;
            echo "<script> window.location.replace('alkatresz-hirdeteseim.php') </script>";
        }
        $link->close();
    }


    // Delete the selected image from the database and copy it to another folder
    if (isset($_POST['delete'])) {
        $delete_imageid = $_POST['actual-image'];
        $statement = $link->prepare("SELECT id, thumbnailimage FROM parts_images WHERE productid = ? AND id= ?");
        $statement->bind_param('ii', $id, $delete_imageid);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        $num_rows = mysqli_num_rows($result);
        $image_name = $row['thumbnailimage'];
        $image_id = $row['id'];

        if ($num_rows > 0) {
            // Create a folder according to the advert id
            $direction = "deleted-parts-images/" . $id . "/";
            if (!file_exists($direction)) {
                mkdir($direction);
                mkdir($direction . "/parts-uploads");
            }
            $deleted_image = $direction . $image_name;
            copy($image_name, $deleted_image);
            unlink($image_name);
            $stmt = $link->prepare("DELETE FROM parts_images WHERE id = ?");
            $stmt->bind_param('i', $delete_imageid);
            $stmt->execute();
            $stmt->close();
            $statement->close();
            header("Location: alkatresz-hirdetes-modositas.php?id=" . $id);
            exit();
        } else {
            die("Kérjük csak a saját hirdetéséhez tartozó képeket törölje!");
        }
    }
?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Hirdetés módosítása</title>

        <link rel="icon" href="images/logo.png" type="image/gif" sizes="16x16">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- Own CSS -->
        <link rel="stylesheet" href="assets/CSS/style.css">
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link type="text/css" rel="stylesheet" href="assets/CSS/image-uploader.min.css">
        <!-- Alertify CSS -->
        <link rel="stylesheet" href="assets/CSS/alertify.css">
        <link rel="stylesheet" href="assets/CSS/default.min.css">
        <!-- Alertify JS -->
        <script src="assets/JS/alertify.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
        </script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
        </script>
    </head>

    <body>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light" id="main-navbar">
                <div class="container-fluid">
                    <a class="navbar-brand" href="alkatreszek.php">
                        <div class="nav-title">alkatré<span style="color: #ee4a4a">s</span>zek</div>
                    </a>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav nav-justified w-100 text-center">
                            <li class="nav-item">
                                <a href="alkatreszek.php" class="nav-link d-flex flex-column">
                                    <i class="fas fa-motorcycle fa-lg"></i>
                                    <span class="d-sm-inline mt-1">motorkerékpárok</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="profil.php" class="nav-link d-flex flex-column">
                                    <i class="fas fa-user fa-lg"></i>
                                    <span class="d-sm-inline mt-1">profil</span>
                                </a>
                            </li>
                            <li class="nav-item active dropdown">
                                <a class="nav-link text-center d-flex flex-column" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-folder-open fa-lg"></i>
                                    <span class="d-sm-inline px-1 mt-1">hirdetéseim</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="hirdeteseim.php">Motorkerékpár</a>
                                    <a class="dropdown-item" href="alkatresz-hirdeteseim.php">Alkatrész</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link text-center d-flex flex-column" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus-circle fa-lg"></i>
                                    <span class="d-sm-inline px-1 mt-1">hirdetésfeladás</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="hirdetesfeladas.php">Motorkerékpár</a>
                                    <a class="dropdown-item" href="alkatresz-hirdetesfeladas.php">Alkatrész</a>
                                </div>
                            </li>
                            <?php
                            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
                            ?>
                                <li class="nav-item">
                                    <a href="./admin/admin.php" class="nav-link d-flex flex-column">
                                        <i class="fas fa-code fa-lg"></i>
                                        <span class="d-sm-inline mt-1">admin</span>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="jumbotron">
                <form action="alkatresz-hirdetes-modositas.php?id=<?php echo htmlspecialchars($id); ?>" method="post" enctype='multipart/form-data' id="add">
                    <h2 class="display-6">Hirdetés módosítása</h2>
                    <ul class="fa-ul">
                        <li><span class="fa-li"><i class="fas fa-info-circle"></i></span><?= htmlspecialchars($moreImages) > 0 ? 'További <b>' . htmlspecialchars($moreImages) . '</b> db képet tölthet fel.' : 'Elérte a maximálisan feltölthető képek számát.' ?></li>
                    </ul>

                    <!-- Image uploader -->
                    <?php
                    if ($moreImages > 0) {
                    ?>
                        <div class="input-images"></div>
                    <?php
                    }
                    ?>


                    <div class="row">


                        <?php
                        $i = 0;
                        foreach ($data as $rec) {
                            $image = $rec['thumbnailimage'];
                            if ($i == 0) {

                        ?>
                                <div class="card col-md-3" id="mainimage-card">
                                    <div class="card-top">
                                        <h5 class="text-center">Fő kép</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <?php
                                        echo "<img src='" . htmlspecialchars($image) . "' class='img-fluid img-thumbnail' id='thumbimages'  alt='thumbnail images'/>";
                                        ?>
                                        <input value="<?php echo htmlspecialchars($rec['id']); ?>" name="mainimageid" type="hidden">
                                    </div>
                                    <div class="card-footer">

                                        <div class="file-input">
                                            <input type="file" name="images" id="file" class="file">
                                            <label for="file">Módosítás</label>
                                            <p class="file-name" id="file-data"></p>
                                        </div>

                                    </div>
                                </div>
                            <?php
                            } else {
                            ?>

                                <div class="card col-md-3" id="thumbnailimage-card-<?php echo htmlspecialchars($rec['id']); ?>">
                                    <div class="card-body text-center">
                                        <?php
                                        echo "<img src='" . htmlspecialchars($image) . "' class='img-fluid img-thumbnail' id='thumbimages' alt='thumbnail images'/>";
                                        ?>
                                    </div>
                                    <div class="card-footer">
                                        <input type='submit' value="Törlés" class="btn btn-danger" name="delete" id="btn-delete<?php echo htmlspecialchars($rec['id']) ?>" />
                                    </div>
                                </div>

                                <!-- Set actual selected image  -->
                                <script>
                                    $("#btn-delete<?php echo htmlspecialchars($rec['id']) ?>").click(function() {
                                        document.getElementById('actual-image').value = "<?php echo htmlspecialchars($rec['id']) ?>";
                                    });
                                </script>

                        <?php
                            }
                            $i++;
                        }
                        ?>

                        <input type="hidden" id="actual-image" name="actual-image">

                    </div>

                    <!-- Product data-->
                    <h5 class="data">Termék adatai</h5>
                    <p class="lead">Az alábbi mezőkben láthatja a korábban megadott értékeket. Módosítás után az új értékek fognak megjelenni a főoldalon.</p>
                    <div class="info">A <span class="req">csillaggal*</span> jelölt mezők kitöltése kötelező!</div>
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="product_name">Megnevezés<span class="req">*</span></label>
                            <input class="form-control" id="product_name" type="text" value="<?php echo htmlspecialchars($product_name); ?>" placeholder="Termék megnevezése" name="product_name" style="border: 1px solid red;" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="price">Vételár<span class="req">*</span></label>
                            <input class="form-control" id="price" type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
                            <span class="unit">Ft</span>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Állapot</label>
                            <select id="condition" class="form-control" name="cond" style="border: 1px solid red;" required>
                                <option selected value='<?php echo htmlspecialchars($cond); ?>'><?php echo htmlspecialchars($cond); ?></option>
                                <option value="Kitünő">Kitünő</option>
                                <option value="Újszerű">Újszerű</option>
                                <option value="Normál">Normál</option>
                                <option value="Sérült">Sérült</option>
                                <option value="Hiányos">Hiányos</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="motorcycle_type">Motor típus</label>
                            <input class="form-control" id="motorcycle_type" type="text" name="motorcycle_type" value="<?php echo htmlspecialchars($motorcycle_type); ?>" placeholder="Mihez való az alkatrész?">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Leírás</label>
                        <textarea class="form-control" id="description" rows="6" name="description" maxlength="1000"><?php echo htmlspecialchars($description); ?></textarea>
                        <div id="characters-left"></div>
                    </div>

                    <h5 class="data">Hirdető adatai</h5>
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="advertiser-name">Név<span class="req">*</span></label>
                            <input class="form-control" id="advertiser-name" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="advertiser-email">E-mail cím</label>
                            <input class="form-control" id="advertiser-email" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tel-number">Telefonszám<span class="req">*</span></label>
                            <input class="form-control" id="tel-number" type="number" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tel-number2">2. Telefonszám</label>
                            <input class="form-control" id="tel-number2" type="number" name="phone2" value="<?php echo htmlspecialchars($phone2); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Megye<span class="req">*</span></label>
                            <select id="county" class="form-control" name="county" required>
                                <option selected value='<?php echo htmlspecialchars($county); ?>'><?php echo htmlspecialchars($county); ?></option>
                                <?php
                                // Display counties
                                if ($counties_result->num_rows > 0) {
                                    while ($row = $counties_result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                    }
                                } else {
                                    echo 'Nincs elérhető megye!';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="settlement">Település<span class="req">*</span></label>
                            <input class="form-control" id="settlement" type="text" name="settlement" value="<?php echo htmlspecialchars($settlement); ?>" required>
                        </div>
                    </div>
                    <h5 class="advertiser">Képek feltöltése</h5>
                    <hr class="my-4">


                    <input type="submit" value="Mentés" class="btn btn-primary btn-xl" id="sendButton" name="upload" />
                </form>
            </div>

            <?php
            // Display error messages
            if ($errorMsg !== '') {
            ?>
                <script>
                    alertify.error(<?php echo ' " ' . htmlspecialchars($errorMsg) . ' " '; ?>);
                </script>
            <?php
            }
            ?>


            <?php include('footer.php'); ?>
        </div>

    <?php
} else {
    header("Location: alkatresz-hirdeteseim.php");
}
    ?>

    <!-- Image uploader -->
    <script type="text/javascript" src="assets/JS/image-uploader.min.js"></script>
    <!-- Tooltips -->
    <script src="assets/JS/tooltips.js"></script>
    <!-- Main image size check -->
    <script src="assets/JS/image-size.js"></script>
    <!-- Description characters -->
    <script src="assets/JS/character-counter.js"></script>
    <!-- Display image data -->
    <script src="assets/JS/image-data.js"></script>

    <!-- Hide select options (NOT 100%) -->
    <script>
        /*
        let values = []
        $("option").each((index, item) => {
            let {
                value
            } = item //item.value is the value of our current option in the loop

            // check if value is already in values array
            if (values.includes(value)) {
                // delete duplicate from the DOM
                item.remove()
            } else {
                // push value to values array so that duplicates can be detected later on
                values.push(value)
            }
        })*/
    </script>

    <!-- Image uploader -->
    <script>
        $('.input-images').imageUploader({
            label: 'Kérjük húzzon ide további maximum <?php echo htmlspecialchars($moreImages) ?> db, feltölteni kívánt képet.',
            maxSize: 2 * 1024 * 1024,
            maxFiles: <?php echo htmlspecialchars($moreImages) ?>,
            imagesInputName: 'files'
        });
    </script>

    </body>

    </html>