<?php
// Start the session

use function PHPSTORM_META\type;

session_start();
// Incude config file
require_once("config.php");

$errorMsg = ""; // Error messages
$maxSize = 2 * 1024 * 1024; // Image max size (2Mb)
$ok = true; // Check if everything ok


// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    $_SESSION['url'] = 'hirdetes-modositas.php';
    header("Location: bejelentkezes.php");
    exit;
}

// Set the id or redirect the user if unset
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: hirdeteseim.php");
}

// Set the userid
$userid = $_SESSION['id'];

// Select images
$sql = "SELECT * FROM images WHERE productid = ? ORDER BY is_mainimage DESC";
$stmt2 = $link->prepare($sql);
$id = $_GET['id'];
$stmt2->bind_param('i', $id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$data = $result2->fetch_all(MYSQLI_ASSOC);
$countImages = mysqli_num_rows($result2);


// Select premium level for the user
$sql3 = "SELECT premium FROM users WHERE id = ?";
$stmt3 = $link->prepare($sql3);
$stmt3->bind_param("i", $userid);
$stmt3->execute();
$result = $stmt3->get_result();
$rec = $result->fetch_assoc();
$premium = $rec['premium'];

// Select manufacturers
$sql4 = "SELECT * FROM manufacturers";
$manufacturers_result = $link->query($sql4);

// Select motorcycle types
$sql5 = "SELECT * FROM motorcycle_types";
$types_result = $link->query($sql5);

// Select counties
$counties_sql = "SELECT * FROM counties ORDER BY name";
$counties_result = $link->query($counties_sql);

// Select technical equipment
$technical_equipment_sql = "SELECT * FROM technical_equipment ORDER BY name";
$technical_equipment_result = $link->query($technical_equipment_sql);

// Select comfort equipment
$comfort_equipment_sql = "SELECT * FROM comfort_equipment ORDER BY name";
$comfort_equipment_result = $link->query($comfort_equipment_sql);

// Select safety equipment
$safety_equipment_sql = "SELECT * FROM safety_equipment ORDER BY name";
$safety_equipment_result = $link->query($safety_equipment_sql);



// Select stored technical equipments
$technical_sql = "SELECT technical_equipment_id FROM mx_technical_equipment WHERE motorcycle_id = ?";
$technical_stmt = $link->prepare($technical_sql);
$technical_stmt->bind_param("i", $id);

// Select stored comfort equipments
$comfort_sql = "SELECT comfort_equipment_id FROM mx_comfort_equipment WHERE motorcycle_id = ?";
$comfort_stmt = $link->prepare($comfort_sql);
$comfort_stmt->bind_param("i", $id);

// Select stored safety equipments
$safety_sql = "SELECT safety_equipment_id FROM mx_safety_equipment WHERE motorcycle_id = ?";
$safety_stmt = $link->prepare($safety_sql);
$safety_stmt->bind_param("i", $id);

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

// Select motorcycles table
$getstmt = $link->prepare("SELECT * FROM motorcycles WHERE id = ? AND status = ?");
if (
    $getstmt and
    $getstmt->bind_param('ii', $id, $status) and
    $getstmt->execute() and
    $result = $getstmt->get_result() and
    $row = $result->fetch_assoc()
) {
    if ($row['userid'] == $userid) {
        $manufacturer = $row['manufacturer'];
        $model = $row['model'];
        $price = $row['price'];
        $type = $row['type'];
        $year = $row['year'];
        $month = $row['month'];
        $documents = $row['documents'];
        $documentsvalidity = $row['documentsvalidity'];
        $kilometers = $row['kilometers'];
        $operatingtime = $row['operatingtime'];
        $motyear = $row['motyear'];
        $motmonth = $row['motmonth'];
        $capacity = $row['capacity'];
        $performance = $row['performance'];
        $enginetype = $row['enginetype'];
        $fuel = $row['fuel'];
        $color = $row['color'];
        $cond = $row['cond'];
        $license = $row['license'];
        $description = $row['description'];
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $phone2 = $row['phone2'];
        $county = $row['county'];
        $settlement = $row['settlement'];
    } else {
        header("Location: index.php");
        exit;
    }

    // Attempt to modify
    if (isset($_POST['upload'])) {

        if (isset($_POST['manufacturer']) && $_POST['manufacturer'] != '') {
            $manufacturer = $_POST['manufacturer'];
        } else {
            $errorMsg = "K??rj??k v??lassza ki a gy??rt??t!";
            $ok = false;
        }

        if (isset($_POST['model']) && $_POST['model'] != '') {
            $model = $_POST['model'];
        } else {
            $errorMsg = "K??rj??k adja meg a modellt!";
            $ok = false;
        }

        if (isset($_POST['price']) && $_POST['price'] != '') {
            $price = $_POST['price'];
        } else {
            $errorMsg = "K??rj??k adja meg az ??rat!";
            $ok = false;
        }

        if (isset($_POST['type']) && $_POST['type'] != '') {
            $type = $_POST['type'];
        } else {
            $errorMsg = "K??rj??k adja meg a kivitelt!";
            $ok = false;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
        } else {
            $errorMsg = "K??rj??k adja meg a gy??rt??si ??vet!";
            $ok = false;
        }

        if (isset($_POST['month']) && $_POST['month'] != '') {
            $month = $_POST['month'];
        } else {
            $errorMsg = "K??rj??k adja meg a gy??rt??si h??napot!";
            $ok = false;
        }

        if (isset($_POST['documents']) && $_POST['documents'] != '') {
            $documents = $_POST['documents'];
        } else {
            $errorMsg = "K??rj??k adja meg az okm??nyok jelleg??t!";
            $ok = false;
        }

        if (isset($_POST['documentsvalidity']) && $_POST['documentsvalidity'] != '') {
            $documentsvalidity = $_POST['documentsvalidity'];
        } else {
            $errorMsg = "K??rj??k adja meg az okm??nyok ??rv??nyess??g??t!";
            $ok = false;
        }

        if (isset($_POST['kilometers']) && $_POST['kilometers'] != '') {
            $kilometers = $_POST['kilometers'];
        } else {
            $errorMsg = "K??rj??k adja meg a kilom??ter??ra ??ll??s??t!";
            $ok = false;
        }

        if (isset($_POST['kilometers']) && $_POST['kilometers'] != '') {
            $kilometers = $_POST['kilometers'];
        } else {
            $errorMsg = "K??rj??k adja meg a kilom??ter??ra ??ll??s??t!";
            $ok = false;
        }

        if (isset($_POST['performance']) && $_POST['performance'] != '') {
            $performance = $_POST['performance'];
        } else {
            $errorMsg = "K??rj??k adja meg a teljes??tm??nyt!";
            $ok = false;
        }

        if (isset($_POST['license']) && $_POST['license'] != '') {
            $license = $_POST['license'];
        } else {
            $errorMsg = "K??rj??k adja meg a jogos??tv??ny t??pus??t!";
            $ok = false;
        }

        if (isset($_POST['name']) && $_POST['name'] != '') {
            $name = $_POST['name'];
        } else {
            $errorMsg = "K??rj??k adja meg a nev??t!";
            $ok = false;
        }

        if (isset($_POST['phone']) && $_POST['phone'] != '') {
            $phone = $_POST['phone'];
        } else {
            $errorMsg = "K??rj??k adja meg a telefonsz??m??t!";
            $ok = false;
        }

        if (isset($_POST['county']) && $_POST['county'] != '') {
            $county = $_POST['county'];
        } else {
            $errorMsg = "K??rj??k adja meg a megy??t!";
            $ok = false;
        }

        if (isset($_POST['settlement']) && $_POST['settlement'] != '') {
            $settlement = $_POST['settlement'];
        } else {
            $errorMsg = "K??rj??k adja meg a telep??l??s nev??t!";
            $ok = false;
        }

        $operatingtime = $_POST['operatingtime'];
        $motyear = $_POST['motyear'];
        $motmonth = $_POST['motmonth'];
        $capacity = $_POST['capacity'];
        $enginetype = $_POST['enginetype'];
        $fuel = $_POST['fuel'];
        $color = $_POST['color'];
        $cond = $_POST['cond'];
        $description = $_POST['description'];
        $email = $_POST['email'];
        $phone2 = $_POST['phone2'];

        // Check lengths
        if (
            strlen($model) > 50  || strlen($kilometers)  > 10 || strlen($operatingtime) > 10 || strlen($capacity) > 10 || strlen($performance) > 10
            || strlen($color) > 50 || strlen($description) > 1000 || strlen($name) > 50 || strlen($email) > 50 || strlen($phone) > 20 || strlen($phone2) > 20 || strlen($settlement) > 50
        ) {
            $errorMsg = "K??rj??k megfelel?? hossz??s??g?? v??laszokat adjon meg!";
            $ok = false;
        }

        // Check if phone numbers are valid
        if (!preg_match("/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/", $phone)) {
            $ok = false;
            $errorMsg = "K??rj??k val??s telefonsz??mot adjon meg!";
        }
        if (!empty($phone2) && $phone2 !== "" && !preg_match("/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/", $phone2)) {
            $ok = false;
            $errorMsg = "K??rj??k val??s 2. telefonsz??mot adjon meg!";
        }

        $stmt = $link->prepare("UPDATE motorcycles SET manufacturer=?, model=?, price=?, type=?, year=?, month=?, documents=?, documentsvalidity=?, kilometers=?, operatingtime=?, motyear=?, motmonth=?,
                        capacity=?, performance=?, enginetype=?, fuel=?, color=?, cond=?, license=?, description=?, name=?, email=?, phone=?, phone2=?, county=?, settlement=? WHERE id = ?");

        $stmt->bind_param(
            "ssisiissiiiiiissssssssssssi",
            $manufacturer,
            $model,
            $price,
            $type,
            $year,
            $month,
            $documents,
            $documentsvalidity,
            $kilometers,
            $operatingtime,
            $motyear,
            $motmonth,
            $capacity,
            $performance,
            $enginetype,
            $fuel,
            $color,
            $cond,
            $license,
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
                    $errorMsg = "A k??pnek 2Mb-n??l kisebbnek kell lennie!";
                } elseif (!in_array($detectedType, $allowed_types)) {
                    $errorMsg = "A k??p csak PNG/JPG/JPEG/GIF form??tumban elfogadott!";
                } elseif ($filesize_error == 0) {

                    $statement = $link->prepare("UPDATE images SET thumbnailimage=? WHERE id=?");


                    $file = $filesTempName;
                    $is_mainimage = 0;
                    if (is_uploaded_file($file) && !empty($file)) {
                        $data = "uploads/" . time() . $_FILES["images"]["name"];
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
                        $errorMsg = "T??ll??pte a maxim??lisan felt??lthet?? k??pek sz??m??t!";
                    } else {

                        $detectedType2 = exif_imagetype($filesTempName2[$i]);
                        if ($_FILES["files"]["size"][$i] > $maxSize) {
                            $filesize_error2 = 1;
                            $errorMsg = "Minden k??pnek 2 Mb-n??l kisebbnek kell lennie!";
                        } elseif (!in_array($detectedType2, $allowed_types)) {
                            $errorMsg = "A k??pek csak PNG/JPG/JPEG/GIF form??tumban elfogadottak!";
                        } elseif ($filesize_error2 == 0) {

                            $productid = $_GET['id'];
                            $statement2 = $link->prepare("INSERT INTO images(thumbnailimage, productid, is_mainimage) VALUES(?, ?, ?)");

                            for ($i = 0; $i < $counted; $i++) {
                                $file = $filesTempName2[$i];
                                if (is_uploaded_file($file) && !empty($file)) {
                                    $data = "uploads/" . time() . $_FILES["files"]["name"][$i];
                                    move_uploaded_file($file, $data);
                                    $is_mainimage = 0;

                                    $statement2->bind_param("sii", $data, $productid, $is_mainimage);
                                    $stmt->execute();
                                    $statement2->execute();
                                }
                            }
                            $statement2->close();
                        }
                    }
                }
            }

            $stmt->execute();

            // Checkboxes

            // technical equipments
            $technical_arr = $_POST['technical'];
            $productid = $_GET['id'];

            $delete_technical_stmt = $link->prepare("DELETE FROM mx_technical_equipment WHERE motorcycle_id = ?");
            $delete_technical_stmt->bind_param("i", $productid);
            $delete_technical_stmt->execute();
            $delete_technical_stmt->close();

            foreach ($technical_arr as $key => $technical) {
                $equipment_id = $key;
                if ($technical == 1) {
                    $insert_technical_stmt = $link->prepare("INSERT INTO mx_technical_equipment(motorcycle_id, technical_equipment_id) VALUES(?, ?)");
                    $insert_technical_stmt->bind_param("ii", $productid, $equipment_id);
                    $insert_technical_stmt->execute();
                    $insert_technical_stmt->close();
                }
            }


            // comfort equipments
            $delete_comfort_stmt = $link->prepare("DELETE FROM mx_comfort_equipment WHERE motorcycle_id = ?");
            $delete_comfort_stmt->bind_param("i", $productid);
            $delete_comfort_stmt->execute();
            $delete_comfort_stmt->close();

            $comfort_arr = $_POST['comfort'];
            foreach ($comfort_arr as $key => $comfort) {
                $equipment_id = $key;
                if ($comfort == 1) {
                    $insert_comfort_stmt = $link->prepare("INSERT INTO mx_comfort_equipment(motorcycle_id, comfort_equipment_id) VALUES(?, ?)");
                    $insert_comfort_stmt->bind_param("ii", $productid, $equipment_id);
                    $insert_comfort_stmt->execute();
                    $insert_comfort_stmt->close();
                }
            }

            // safety equipments
            $delete_safety_stmt = $link->prepare("DELETE FROM mx_safety_equipment WHERE motorcycle_id = ?");
            $delete_safety_stmt->bind_param("i", $productid);
            $delete_safety_stmt->execute();
            $delete_safety_stmt->close();

            $safety_arr = $_POST['safety'];
            foreach ($safety_arr as $key => $safety) {
                $equipment_id = $key;
                if ($safety == 1) {
                    $insert_comfort_stmt = $link->prepare("INSERT INTO mx_safety_equipment(motorcycle_id, safety_equipment_id) VALUES(?, ?)");
                    $insert_comfort_stmt->bind_param("ii", $productid, $equipment_id);
                    $insert_comfort_stmt->execute();
                    $insert_comfort_stmt->close();
                }
            }

            $stmt->close();

            $_SESSION['success-modify'] = true;
            echo "<script> window.location.replace('hirdeteseim.php') </script>";
        }
        $link->close();
    }


    // Delete the selected image from the database and copy it to another folder
    if (isset($_POST['delete'])) {
        $delete_imageid = $_POST['actual-image'];
        $statement = $link->prepare("SELECT id, thumbnailimage FROM images WHERE productid = ? AND id= ?");
        $statement->bind_param('ii', $id, $delete_imageid);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        $num_rows = mysqli_num_rows($result);
        $image_name = $row['thumbnailimage'];
        $image_id = $row['id'];

        if ($num_rows > 0) {
            $direction = "deleted-images/" . $id . "/";
            if (!file_exists($direction)) {
                mkdir($direction);
                mkdir($direction . "/uploads");
            }
            $deleted_image = $direction . $image_name;
            copy($image_name, $deleted_image);
            unlink($image_name);
            $stmt = $link->prepare("DELETE FROM images WHERE id = ?");
            $stmt->bind_param('i', $delete_imageid);
            $stmt->execute();
            $stmt->close();
            $statement->close();
            header("Location: hirdetes-modositas.php?id=" . $id);
            exit();
        } else {
            die("K??rj??k csak a saj??t hirdet??s??hez tartoz?? k??peket t??r??lje!");
        }
    }
?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Hirdet??s m??dos??t??sa</title>

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
                    <a class="navbar-brand" href="index.php">
                        <div class="nav-title">motor<span style="color: #ee4a4a">s</span>arok<span style="color: #ee4a4a">.</span>hu</div>
                    </a>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav nav-justified w-100 text-center">
                            <li class="nav-item">
                                <a href="alkatreszek.php" class="nav-link d-flex flex-column">
                                    <i class="fas fa-tools fa-lg"></i>
                                    <span class="d-sm-inline mt-1">alkatr??szek</span>
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
                                    <span class="d-sm-inline px-1 mt-1">hirdet??seim</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="hirdeteseim.php">Motorker??kp??r</a>
                                    <a class="dropdown-item" href="alkatresz-hirdeteseim.php">Alkatr??sz</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link text-center d-flex flex-column" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus-circle fa-lg"></i>
                                    <span class="d-sm-inline px-1 mt-1">hirdet??sfelad??s</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="hirdetesfeladas.php">Motorker??kp??r</a>
                                    <a class="dropdown-item" href="alkatresz-hirdetesfeladas.php">Alkatr??sz</a>
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
                <form action="hirdetes-modositas.php?id=<?php echo htmlspecialchars($id); ?>" method="post" enctype='multipart/form-data' id="add">
                    <h2 class="display-6">Hirdet??s m??dos??t??sa</h2>
                    <ul class="fa-ul">
                        <li><span class="fa-li"><i class="fas fa-info-circle"></i></span><?= $moreImages > 0 ? 'Tov??bbi <b>' . $moreImages . '</b> db k??pet t??lthet fel.' : 'El??rte a maxim??lisan felt??lthet?? k??pek sz??m??t.' ?></li>
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
                                        <h5 class="text-center">F?? k??p</h5>
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
                                            <label for="file">M??dos??t??s</label>
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
                                        <input type='submit' value="T??rl??s" class="btn btn-danger" name="delete" id="btn-delete<?php echo htmlspecialchars($rec['id']) ?>" />
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
                    <h5 class="data">Term??k adatai</h5>
                    <p class="lead">Az al??bbi mez??kben l??thatja a kor??bban megadott ??rt??keket. M??dos??t??s ut??n az ??j ??rt??kek fognak megjelenni a f??oldalon.</p>
                    <div class="info">A <span class="req">csillaggal*</span> jel??lt mez??k kit??lt??se k??telez??!</div>
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="manufacturer">Gy??rt??<span class="req">*</span></label>
                            <select class="form-control" id="manufacturer" name="manufacturer" required>
                                <option selected value='<?php echo htmlspecialchars($manufacturer); ?>'><?php echo htmlspecialchars($manufacturer); ?></option>
                                <?php
                                // Display manufacturers
                                if ($manufacturers_result->num_rows > 0) {
                                    while ($row = $manufacturers_result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                    }
                                } else {
                                    echo 'Nincs el??rhet?? gy??rt??!';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="model">Modell<span class="req">*</span></label>
                            <input class="form-control" id="model" type="text" placeholder="Modell" name="model" value="<?php echo htmlspecialchars($model); ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="make">Kivitel<span class="req">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option selected value='<?php echo htmlspecialchars($type); ?>'><?php echo htmlspecialchars($type); ?></option>
                                <?php
                                // Display manufacturers
                                if ($types_result->num_rows > 0) {
                                    while ($row = $types_result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                    }
                                } else {
                                    echo 'Nincs el??rhet?? kivitel!';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="price">V??tel??r<span class="req">*</span></label>
                            <input class="form-control" id="price" type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
                            <span class="unit">Ft</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>??vj??rat<span class="req">*</span></label>
                            <select id="made-year" class="form-control" name="year" required>
                                <option value="" disabled>??v</option>
                                <option selected value='<?php echo htmlspecialchars($year); ?>'><?php echo htmlspecialchars($year); ?></option>
                                <?php
                                for ($i = 1950; $i <= date('Y'); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3" id="month-group">
                            <select id="made-month" class="form-control" name="month" required>
                                <option value="" disabled>H??</option>
                                <option selected value='<?php echo htmlspecialchars($month); ?>'><?php echo htmlspecialchars($month); ?></option>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Okm??nyok jellege<span class="req">*</span></label>
                            <select id="documents" class="form-control" name="documents" required>
                                <option selected value='<?php echo htmlspecialchars($documents); ?>'><?php echo htmlspecialchars($documents); ?></option>
                                <option value="Magyar okm??nyokkal">Magyar okm??nyokkal</option>
                                <option value="K??lf??ldi okm??nyokkal">K??lf??ldi okm??nyokkal</option>
                                <option value="Okm??nyok n??lk??l">Okm??nyok n??lk??l</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Okm??nyok ??rv??nyess??ge<span class="req">*</span></label>
                            <select id="documentsvalidity" class="form-control" name="documentsvalidity" required>
                                <option selected value='<?php echo htmlspecialchars($documentsvalidity); ?>'><?php echo htmlspecialchars($documentsvalidity); ?></option>
                                <option value="??rv??nyes okm??nyokkal">??rv??nyes okm??nyokkal</option>
                                <option value="Lej??rt okm??nyokkal">Lej??rt okm??nyokkal</option>
                                <option value="Forgalomb??l ideiglenesen kivont">Forgalomb??l ideiglenesen kivont</option>
                                <option value="Okm??nyok n??lk??l">Okm??nyok n??lk??l</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="km">Kilom??ter??ra ??ll??sa<span class="req">*</span></label>
                            <input class="form-control" id="kilometers" type="number" name="kilometers" value="<?php echo htmlspecialchars($kilometers); ?>" required>
                            <span class="unit">km</span>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="hours">??zem??ra</label>
                            <input class="form-control" id="hours" type="number" name="operatingtime" value="<?php if (!empty($operatingtime)) {
                                                                                                                    echo htmlspecialchars($operatingtime);
                                                                                                                } ?>">
                            <span class="unit">??ra</span>
                        </div>
                        <div class="form-group col-md-3">
                            <label>M??szaki ??rv??nyess??g</label>
                            <select id="license-year" class="form-control" name="motyear">
                                <?php
                                if (!empty($motyear)) {
                                    echo "<option selected value='" . htmlspecialchars($motyear) . "'> " . htmlspecialchars($motyear) . "</option>";
                                } else {
                                    echo '<option value="" selected>??v</option>';
                                }

                                for ($i = date("Y"); $i <= date("Y") + 4; $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3" id="month-group">
                            <select id="license-month" class="form-control" name="motmonth">
                                <?php
                                if (!empty($motmonth)) {
                                    echo "<option selected value='" . htmlspecialchars($motmonth) . "'> " . htmlspecialchars($motmonth) . "</option>";
                                } else {
                                    echo '<option value="" selected>H??</option>';
                                }

                                for ($i = 1; $i <= 12; $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="hours">Henger??rtartalom</label>
                            <input class="form-control" id="ccm" type="number" name="capacity" value="<?php echo htmlspecialchars($capacity); ?>" required>
                            <span class="unit">cm??</span>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="hours">Teljes??tm??ny<span class="req">*</span></label>
                            <input class="form-control" id="kw" type="number" name="performance" value="<?php echo htmlspecialchars($performance); ?>" required>
                            <span class="unit">kW</span>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Munka??tem</label>
                            <select id="stroke" class="form-control" name="enginetype">
                                <?php
                                if (!empty($enginetype)) {
                                    echo "<option selected value='" . htmlspecialchars($enginetype) . "'> " . htmlspecialchars($enginetype) . "</option>";
                                } else {
                                    echo '<option value="" selected>K??rj??k v??lasszon</option>';
                                }
                                ?>
                                <option value="2">2</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>??zemanyag</label>
                            <select id="fuel" class="form-control" name="fuel">
                                <?php
                                if (!empty($fuel)) {
                                    echo "<option selected value='" . htmlspecialchars($fuel) . "'> " . htmlspecialchars($fuel) . "</option>";
                                } else {
                                    echo '<option value="" selected>K??rj??k v??lasszon</option>';
                                }
                                ?>
                                <option value="Benzin">Benzin</option>
                                <option value="D??zel">D??zel</option>
                                <option value="Elektromos">Elektromos</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="color">Sz??n</label>
                            <input class="form-control" id="color" type="text" name="color" value="<?php echo htmlspecialchars($color); ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label>??llapot</label>
                            <select id="condition" class="form-control" name="cond">
                                <?php
                                if (!empty($cond)) {
                                    echo "<option selected value='" . htmlspecialchars($cond) . "'> " . htmlspecialchars($cond) . "</option>";
                                } else {
                                    echo '<option value="" selected>K??rj??k v??lasszon</option>';
                                }
                                ?>
                                <option value="Kit??n??">Kit??n??</option>
                                <option value="??jszer??">??jszer??</option>
                                <option value="Norm??l">Norm??l</option>
                                <option value="S??r??lt">S??r??lt</option>
                                <option value="Hi??nyos">Hi??nyos</option>


                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Jogos??tv??ny t??pusa<span class="req">*</span></label>
                            <select id="driver-license" class="form-control" name="license" required>
                                <option selected value='<?php echo htmlspecialchars($license); ?>'><?php echo htmlspecialchars($license); ?></option>
                                <option value="AM">AM</option>
                                <option value="A1">A1</option>
                                <option value="A2">A2</option>
                                <option value="A">A</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Le??r??s</label>
                        <textarea class="form-control" id="description" rows="6" name="description" maxlength="1000"><?php echo htmlspecialchars($description); ?></textarea>
                        <div id="characters-left"></div>
                    </div>

                    <h5 class="data">Felszerelts??g</h5>
                    <hr class="my-4">
                    <div class="equipment-container">
                        <div id="first">
                            <div class="equipment"><b>M??szaki felszerelts??g</b></div>
                            <?php

                            // Technical equipments mx
                            $technical_stmt->execute();
                            $technical_result = $technical_stmt->get_result();
                            $technical_data = $technical_result->fetch_all(MYSQLI_ASSOC);

                            $technical_selected_arr = [];

                            foreach ($technical_data as $td) {
                                array_push($technical_selected_arr, $td['technical_equipment_id']);
                            }

                            // Display technical equipments
                            if ($technical_equipment_result->num_rows > 0) {
                                while ($row = $technical_equipment_result->fetch_assoc()) {

                                    $is_checked = in_array($row['id'], $technical_selected_arr) ? "checked" : "";

                                    echo "<div class='form-check'>";
                                    echo "<input type='checkbox' class='form-check-input' name='technical[" . htmlspecialchars($row['id']) . "]' id='technical-" . htmlspecialchars($row['id']) . "' value='1'" . htmlspecialchars($is_checked) . ">";
                                    echo "<label class='form-check-label' for='technical-" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</label>";
                                    echo "</div>";
                                }
                            } else {
                                echo 'Nincs el??rhet?? m??szaki felszerelts??g!';
                            }
                            ?>
                        </div>

                        <div id="second">
                            <div class="equipment"><b>K??nyelmi felszerelts??g</b></div>
                            <?php

                            // Comfort equipments mx
                            $comfort_stmt->execute();
                            $comfort_result = $comfort_stmt->get_result();
                            $comfort_data = $comfort_result->fetch_all(MYSQLI_ASSOC);

                            $comfort_selected_arr = [];

                            foreach ($comfort_data as $cd) {
                                array_push($comfort_selected_arr, $cd['comfort_equipment_id']);
                            }

                            // Display comfort equipments
                            if ($comfort_equipment_result->num_rows > 0) {
                                while ($row = $comfort_equipment_result->fetch_assoc()) {

                                    $is_checked = in_array($row['id'], $comfort_selected_arr) ? "checked" : "";

                                    echo "<div class='form-check'>";
                                    echo "<input type='checkbox' class='form-check-input' name='comfort[" . htmlspecialchars($row['id']) . "]' id='comfort-" . htmlspecialchars($row['id']) . "' value='1'" . htmlspecialchars($is_checked) . ">";
                                    echo "<label class='form-check-label' for='comfort-" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</label>";
                                    echo "</div>";
                                }
                            } else {
                                echo 'Nincs el??rhet?? k??nyelmi felszerelts??g!';
                            }
                            ?>
                        </div>

                        <div id="third">
                            <div class="equipment"><b>Biztons??gi felszerelts??g</b></div>
                            <?php

                            // Safety equipments mx
                            $safety_stmt->execute();
                            $safety_result = $safety_stmt->get_result();
                            $safety_data = $safety_result->fetch_all(MYSQLI_ASSOC);

                            $safety_selected_arr = [];

                            foreach ($safety_data as $sd) {
                                array_push($safety_selected_arr, $sd['safety_equipment_id']);
                            }

                            // Display safety equipments
                            if ($safety_equipment_result->num_rows > 0) {
                                while ($row = $safety_equipment_result->fetch_assoc()) {

                                    $is_checked = in_array($row['id'], $safety_selected_arr) ? "checked" : "";

                                    echo "<div class='form-check'>";
                                    echo "<input type='checkbox' class='form-check-input' name='safety[" . htmlspecialchars($row['id']) . "]' id='safety-" . htmlspecialchars($row['id']) . "' value='1'" . htmlspecialchars($is_checked) . ">";
                                    echo "<label class='form-check-label' for='safety-" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</label>";
                                    echo "</div>";
                                }
                            } else {
                                echo 'Nincs el??rhet?? k??nyelmi felszerelts??g!';
                            }
                            ?>
                        </div>
                    </div>

                    <h5 class="data">Hirdet?? adatai</h5>
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="advertiser-name">N??v<span class="req">*</span></label>
                            <input class="form-control" id="advertiser-name" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="advertiser-email">E-mail c??m</label>
                            <input class="form-control" id="advertiser-email" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tel-number">Telefonsz??m<span class="req">*</span></label>
                            <input class="form-control" id="tel-number" type="number" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tel-number2">2. Telefonsz??m</label>
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
                                    echo 'Nincs el??rhet?? megye!';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="settlement">Telep??l??s<span class="req">*</span></label>
                            <input class="form-control" id="settlement" type="text" name="settlement" value="<?php echo htmlspecialchars($settlement); ?>" required>
                        </div>
                    </div>
                    <h5 class="advertiser">K??pek felt??lt??se</h5>
                    <hr class="my-4">


                    <input type="submit" value="Ment??s" class="btn btn-primary btn-xl" id="sendButton" name="upload" />
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
    header("Location: hirdeteseim.php");
}
    ?>

    <!-- Image uploader -->
    <script type="text/javascript" src="assets/JS/image-uploader.min.js"></script>
    <!-- Tooltips -->
    <script src="assets/JS/tooltips.js"></script>
    <!-- Check if inputs are empty -->
    <script src="assets/JS/motorcycles-inputs.js"></script>
    <!-- Main image size check -->
    <script src="assets/JS/image-size.js"></script>
    <!-- Description characters -->
    <script src="assets/JS/character-counter.js"></script>
    <!-- Show selected main image data -->
    <script src="assets/JS/image-data.js"></script>

    <!-- Image uploader -->
    <script>
        $('.input-images').imageUploader({
            label: 'K??rj??k h??zzon ide tov??bbi maximum <?php echo htmlspecialchars($moreImages) ?> db, felt??lteni k??v??nt k??pet.',
            maxSize: 2 * 1024 * 1024,
            maxFiles: <?php echo htmlspecialchars($moreImages) ?>,
            imagesInputName: 'files'
        });
    </script>

    </body>

    </html>