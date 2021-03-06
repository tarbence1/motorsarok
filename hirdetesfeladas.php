<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    $_SESSION['url'] = 'hirdetesfeladas.php';
    header("Location: bejelentkezes.php");
    exit;
}

// Premium info variables
$premiumInfo = $premiumColor = $maxAdsInfo = "";

// Set the user id
$userid = $_SESSION['id'];

// Count active advertisements
$sql = "SELECT COUNT(id) as counted FROM motorcycles WHERE userid = ? AND status = ?";
$status = 1;
$stmt = $link->prepare($sql);
$stmt->bind_param("ii", $userid, $status);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$counted = $row['counted'];

// Select premium level for the user
$sql2 = "SELECT premium FROM users WHERE id = ?";
$stmt2 = $link->prepare($sql2);
$stmt2->bind_param("i", $userid);
$stmt2->execute();
$result = $stmt2->get_result();
$rec = $result->fetch_assoc();
$premium = $rec['premium'];

// Select manufacturers
$sql3 = "SELECT * FROM manufacturers";
$manufacturers_result = $link->query($sql3);

// Select motorcycle types
$sql4 = "SELECT * FROM motorcycle_types";
$types_result = $link->query($sql4);

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

// Set the maximum number, info, color of images that can be uploaded
switch ($premium) {
    case 0:
        $maxImage = 5;
        $maxAds = 2;
        $premiumInfo = "";
        $premiumColor = "default-color";
        break;
    case 1:
        $maxImage = 7;
        $maxAds = 5;
        $premiumInfo = "bronz";
        $premiumColor = "bronze-color";
        break;
    case 2:
        $maxImage = 10;
        $maxAds = 10;
        $premiumInfo = "ez??st";
        $premiumColor = "silver-color";
        break;
    case 3:
        $maxImage = 12;
        $maxAds = 9999;
        $premiumInfo = "arany";
        $premiumColor = "gold-color";
        break;
    default:
        $maxImage = 5;
        $maxAds = 2;
        $premiumInfo = "";
        $premiumColor = "";
}

// Display info according to the premium level
if ($counted >= $maxAds) {
    $maxAdsInfo = '<li><span class="fa-li"><i class="fas fa-info-circle"></i></span>El??rte a maximum feladhat?? hirdet??sek sz??m??t. Amennyiben ??jabb hirdet??st szeretne feladni, k??rj??k v??lasszon egyet <a href ="profile.php"><b>pr??mium</b></a> csomagjaink k??z??l.</li>';
} else {
    $calc = $maxAds - (int)$counted;
    if ($calc > 10) {
        $maxAdsInfo = '<li><span class="fa-li"><i class="fas fa-info-circle"></i></span>Korl??tlan sz??m?? hirdet??st adhat fel.</li>';
    } else {
        $maxAdsInfo = '<li><span class="fa-li"><i class="fas fa-info-circle"></i></span>M??g <b>' . $calc . '</b> hirdet??st adhat fel.</li>';
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Hirdet??sfelad??s</title>

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
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="assets/CSS/dropzone.css">
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
                        <li class="nav-item dropdown">
                            <a class="nav-link text-center d-flex flex-column" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-folder-open fa-lg"></i>
                                <span class="d-sm-inline px-1 mt-1">hirdet??seim</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="hirdeteseim.php">Motorker??kp??r</a>
                                <a class="dropdown-item" href="alkatresz-hirdeteseim.php">Alkatr??sz</a>
                            </div>
                        </li>
                        <li class="nav-item active dropdown">
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
            <form action="upload.php" method="post" enctype='multipart/form-data' id="add">
                <h2 class="display-6">Motorker??kp??r hirdet??sfelad??s</h2>
                <ul class="fa-ul">
                    <?php
                    echo $maxAdsInfo;
                    ?>
                </ul>
                <h5 class="data">K??pek felt??lt??se</h5>
                <hr class="my-4">
                <ul class="fa-ul">
                    <?php
                    if (empty($premiumInfo)) {
                        echo '<li><span class="fa-li"><i class="fas fa-info-circle"></i></span>??n jelenleg <b>nem</b> rendelkezik pr??mium csomaggal, ??gy maximum <b>' . $maxImage . '</b> k??pet t??lhet fel.</li>';
                        echo '<li><span class="fa-li"><i class="fas fa-info-circle"></i></span>Amennyiben szeretne a csomagok k??z??l v??lasztani, <b><a href="profile.php">ide</a></b> kattintva v??laszthat egyet.</li>';
                    } else {
                        echo '<li><span class="fa-li"><i class="fas fa-info-circle"></i></span>??n jelenleg <b><span id="' . $premiumColor . '">' . $premiumInfo . '</span></b> csomaggal rendelkezik, ??gy maximum <b>' . $maxImage . '</b> k??pet t??lthet fel.</li>';
                    }
                    ?>
                    <li>
                    <li><span class="fa-li"><i class="fas fa-info-circle"></i></span>Legal??bb <b>egy</b> k??p felt??lt??se k??telez??!</li>
                </ul>
                <div class="dropzone" id="uploader">
                </div>
                <h5 class="data">Term??k adatai</h5>
                <hr class="my-4">
                <ul class="fa-ul" id="product-info">
                    <li><span class="fa-li"><i class="fas fa-info-circle"></i></span>A <span class="req">csillaggal*</span> jel??lt mez??k kit??lt??se k??telez??!</li>
                </ul>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="manufacturer">Gy??rt??<span class="req">*</span></label>
                        <select class="form-control" id="manufacturer" name="manufacturer" style="border: 1px solid red;" required>
                            <option value="">K??rj??k v??lasszon</option>
                            <?php
                            // Display manufacturers
                            if ($manufacturers_result->num_rows > 0) {
                                while ($row = $manufacturers_result->fetch_assoc()) {
                                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                }
                            } else {
                                echo 'Nincs el??rhet?? gy??rt??!';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="model">Modell<span class="req">*</span></label>
                        <input class="form-control" id="model" type="text" placeholder="Modell" name="model" style="border: 1px solid red;" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="make">Kivitel<span class="req">*</span></label>
                        <select class="form-control" id="type" name="type" style="border: 1px solid red;" required>
                            <option value="">K??rj??k v??lasszon</option>
                            <?php
                            // Display manufacturers
                            if ($types_result->num_rows > 0) {
                                while ($row = $types_result->fetch_assoc()) {
                                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                }
                            } else {
                                echo 'Nincs el??rhet?? kivitel!';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="price">V??tel??r<span class="req">*</span></label>
                        <input class="form-control" id="price" type="number" name="price" style="border: 1px solid red;" required>
                        <span class="unit">Ft</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>??vj??rat<span class="req">*</span></label>
                        <select id="made-year" class="form-control" name="year" style="border: 1px solid red;" required>
                            <option value="">??v</option>
                            <?php
                            for ($i = 1950; $i <= date('Y'); $i++) {
                                echo "<option value='" . $i . "'>" . $i . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3" id="month-group">
                        <select id="made-month" class="form-control" name="month" style="border: 1px solid red;" required>
                            <option value="">H??</option>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<option value='" . $i . "'>" . $i . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Okm??nyok jellege<span class="req">*</span></label>
                        <select id="documents" class="form-control" name="documents" style="border: 1px solid red;" required>
                            <option value="">K??rj??k v??lasszon</option>
                            <option value="Magyar okm??nyokkal">Magyar okm??nyokkal</option>
                            <option value="K??lf??ldi okm??nyokkal">K??lf??ldi okm??nyokkal</option>
                            <option value="Okm??nyok n??lk??l">Okm??nyok n??lk??l</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Okm??nyok ??rv??nyess??ge<span class="req">*</span></label>
                        <select id="documentsvalidity" class="form-control" name="documentsvalidity" style="border: 1px solid red;" required>
                            <option value="">K??rj??k v??lasszon</option>
                            <option value="??rv??nyes okm??nyokkal">??rv??nyes okm??nyokkal</option>
                            <option value="Lej??rt okm??nyokkal">Lej??rt okm??nyokkal</option>
                            <option value="Forgalomb??l ideiglenesen kivont">Forgalomb??l ideiglenesen kivont</option>
                            <option value="Okm??nyok n??lk??l">Okm??nyok n??lk??l</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="km">Kilom??ter??ra ??ll??sa<span class="req">*</span></label>
                        <input class="form-control" id="kilometers" type="number" name="kilometers" style="border: 1px solid red;" required>
                        <span class="unit">km</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="hours">??zem??ra</label>
                        <input class="form-control" id="hours" type="number" name="operatingtime">
                        <span class="unit">??ra</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label>M??szaki ??rv??nyess??g</label>
                        <select id="license-year" class="form-control" name="motyear">
                            <option value="">??v</option>
                            <?php
                            for ($i = date("Y"); $i <= date("Y") + 4; $i++) {
                                echo "<option value='" . $i . "'>" . $i . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3" id="month-group">
                        <select id="license-month" class="form-control" name="motmonth">
                            <option value="">H??</option>
                            <?php
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
                        <input class="form-control" id="ccm" type="number" name="capacity" style="border: 1px solid red;" required>
                        <span class="unit">cm??</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="hours">Teljes??tm??ny<span class="req">*</span></label>
                        <input class="form-control" id="kw" type="number" name="performance" style="border: 1px solid red;" required>
                        <span class="unit">kW</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Munka??tem</label>
                        <select id="stroke" class="form-control" name="enginetype">
                            <option value="">K??rj??k v??lasszon</option>
                            <option value="2">2</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>??zemanyag</label>
                        <select id="fuel" class="form-control" name="fuel">
                            <option value="">K??rj??k v??lasszon</option>
                            <option value="Benzin">Benzin</option>
                            <option value="D??zel">D??zel</option>
                            <option value="Elektromos">Elektromos</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="color">Sz??n</label>
                        <input class="form-control" id="color" type="text" name="color">
                    </div>
                    <div class="form-group col-md-3">
                        <label>??llapot</label>
                        <select id="condition" class="form-control" name="cond">
                            <option value="">K??rj??k v??lasszon</option>
                            <option value="Kit??n??">Kit??n??</option>
                            <option value="??jszer??">??jszer??</option>
                            <option value="Norm??l">Norm??l</option>
                            <option value="S??r??lt">S??r??lt</option>
                            <option value="Hi??nyos">Hi??nyos</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Jogos??tv??ny t??pusa<span class="req">*</span></label>
                        <select id="driver-license" class="form-control" name="license" style="border: 1px solid red;" required>
                            <option value="">K??rj??k v??lasszon</option>
                            <option value="AM">AM</option>
                            <option value="A1">A1</option>
                            <option value="A2">A2</option>
                            <option value="A">A</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Le??r??s</label>
                    <textarea class="form-control" id="description" rows="6" name="description" maxlength="1000"></textarea>
                    <div id="characters-left"></div>
                </div>

                <h5 class="data">Felszerelts??g</h5>
                <hr class="my-4">
                <div class="equipment-container">
                    <div id="first">
                        <div class="equipment"><b>M??szaki felszerelts??g</b></div>
                        <?php
                        // Display technical equipments
                        if ($technical_equipment_result->num_rows > 0) {
                            while ($row = $technical_equipment_result->fetch_assoc()) {
                                echo "<div class='form-check'>";
                                echo "<input type='checkbox' class='form-check-input' name='technical[" . $row['id'] . "]' id='technical-" . $row['id'] . "' value='1'>";
                                echo "<label class='form-check-label' for='technical-" . $row['id'] . "'>" . $row['name'] . "</label>";
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
                        // Display comfort equipments
                        if ($comfort_equipment_result->num_rows > 0) {
                            while ($row = $comfort_equipment_result->fetch_assoc()) {
                                echo "<div class='form-check'>";
                                echo "<input type='checkbox' class='form-check-input' name='comfort[" . $row['id'] . "]' id='comfort-" . $row['id'] . "' value='1'>";
                                echo "<label class='form-check-label' for='comfort-" . $row['id'] . "'>" . $row['name'] . "</label>";
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
                        // Display comfort equipments
                        if ($safety_equipment_result->num_rows > 0) {
                            while ($row = $safety_equipment_result->fetch_assoc()) {
                                echo "<div class='form-check'>";
                                echo "<input type='checkbox' class='form-check-input' name='safety[" . $row['id'] . "]' id='safety-" . $row['id'] . "' value='1'>";
                                echo "<label class='form-check-label' for='safety-" . $row['id'] . "'>" . $row['name'] . "</label>";
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
                        <input class="form-control" id="advertiser-name" type="text" name="name" style="border: 1px solid red;" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="advertiser-email">E-mail c??m</label>
                        <input class="form-control" id="advertiser-email" type="email" name="email">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tel-number">Telefonsz??m<span class="req">*</span></label>
                        <input class="form-control" id="tel-number" type="number" name="phone" style="border: 1px solid red;" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tel-number2">2. Telefonsz??m</label>
                        <input class="form-control" id="tel-number2" type="number" name="phone2">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Megye<span class="req">*</span></label>
                        <select id="county" class="form-control" name="county" style="border: 1px solid red;" required>
                            <option value="">K??rj??k v??lasszon</option>
                            <?php
                            // Display counties
                            if ($counties_result->num_rows > 0) {
                                while ($row = $counties_result->fetch_assoc()) {
                                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                }
                            } else {
                                echo 'Nincs el??rhet?? megye!';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="settlement">Telep??l??s<span class="req">*</span></label>
                        <input class="form-control" id="settlement" type="text" name="settlement" style="border: 1px solid red;" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-xl" id="sendButton" name="upload">K??ld??s</button>

                <div class="errors">
                    <!-- Display image error messages-->
                    <div class="alert alert-danger" role="alert" id="image-messages">
                        <h5 class="alert-heading">Hiba! Az al??bbi probl??m??k mer??ltek fel a k??pek felt??lt??se sor??n:</h5>
                        <ul id="image-message">
                            <li id="message-holder"></li>
                        </ul>
                    </div>

                    <!-- Display error messages-->
                    <div class="alert alert-danger" role="alert" id="error-messages">
                        <h5 class="alert-heading">Hiba! Az al??bbi probl??m??k mer??ltek fel az adatok felt??lt??se sor??n:</h5>
                        <ul id="error-message"></ul>
                    </div>
                </div>
                <input type="hidden" id="mainimage" name="mainimage" readonly>
            </form>
        </div>


        <?php include('footer.php'); ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>
    <!-- Dropzone JS -->
    <script src="assets/JS/dropzone.js"></script>
    <!-- Tooltips -->
    <script src="assets/JS/tooltips.js"></script>
    <!-- Check if inputs are empty -->
    <script src="assets/JS/motorcycles-inputs.js"></script>
    <!-- Description characters -->
    <script src="assets/JS/character-counter.js"></script>


    <script>
        Dropzone.options.uploader = {
            url: 'upload.php',
            autoProcessQueue: false,
            uploadMultiple: true,
            paramName: "images", // The name that will be used to transfer the file
            parallelUploads: 12,
            maxFilesize: 2, // MB
            maxFiles: <?php echo $maxImage ?>,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            dictDefaultMessage: '<i class="fas fa-cloud-upload-alt"></i><br/>K??rj??k h??zza ide a k??peket vagy kattintson a tall??z??shoz!<br/><b>Legal??bb egy k??p felt??lt??se k??telez??!</b>',
            dictFallbackMessage: 'B??ng??sz??je nem t??mogatja a k??p el??n??zetet!',
            dictFallbackText: 'K??rj??k haszn??lja a tall??z??st a k??pek kiv??laszt??s??hoz!',
            dictFileTooBig: 'A f??jl m??rete t??l nagy. ({{filesize}}MiB). Maximum {{maxFilesize}}MiB lehet!',
            dictInvalidFileType: 'A kiv??lasztott f??jl kiterjeszt??se nem megfelel??!',
            dictResponseError: 'A szerver {{statusCode}} k??ddal v??laszolt. K??rj??k pr??b??lja meg k??s??bb!',
            dictCancelUpload: 'Felt??lt??s visszavon??sa',
            dictUploadCanceled: 'felt??lt??s visszavonva!',
            dictCancelUploadConfirmation: 'Biztosan visszavonja a felt??lt??st?',
            dictRemoveFile: 'K??p t??rl??se',
            dictMaxFilesExceeded: 'El??rte a maxim??lisan felt??lthet?? k??pek sz??m??t!',
            accept: function(file, done) {
                let fileReader = new FileReader();
                fileReader.readAsDataURL(file);
                fileReader.onloadend = function() {
                    $('<a>', {
                        class: 'primary',
                        text: "Legyen ez a f?? k??p",
                        href: "#"
                    }).appendTo(file.previewElement)
                    //file.previewElement.append($textContainer)
                    file.previewElement.classList.add("dz-success");
                    if (($(".dz-success.dz-complete").length > 0) && ($(".main").length == 0)) {
                        $(".dz-success.dz-complete:first .primary").text("F?? k??p")
                        //add class to first one
                        $(".dz-success.dz-complete:first").addClass("main")
                        $("#mainimage").val($(".dz-success.dz-complete:first").find(".dz-filename span").text()) //add default name to imgs input
                    }


                }

                file.previewElement.classList.add("dz-complete");

                done();
            },
            errormultiple: function(files, message, xhr) {
                $('#error-message').empty();
                files.forEach(file => {
                    this.removeFile(file);
                });
                if (xhr != undefined) {
                    files.forEach(f => {
                        this.addFile(f);
                    });
                    var errorMessages = JSON.parse(message).errorMsg;
                    errorMessages.forEach(msg => {
                        $('#error-messages').css('display', 'block');
                        $('#error-message').append('<li>' + msg + '</li>');

                    });

                } else {
                    alert(message);
                }
            },

            // auto select and remove
            removedfile: function(file) {
                var is_there = file.previewElement.classList.contains("main");
                console.log(is_there)
                file.previewElement.remove();
                if (is_there && $(".dz-success.dz-complete").length > 0) {
                    $(".dz-success.dz-complete .primary").text("Legyen ez a f?? k??p")
                    $(".dz-success.dz-complete:first .primary").text("F?? k??p")
                    $(".dz-success.dz-complete:first").addClass("main")
                    $("#mainimage").val($(".dz-success.dz-complete:first").find(".dz-filename span").text()) //add default name to imgs input
                }

                if ($(".dz-success.dz-complete").length == 0) {
                    $("#mainimage").val("")
                }
            },
            successmultiple: function(file, response) {
                return window.location.replace('hirdeteseim.php');
            },
            init: function() {
                dzClosure = this;

                // for Dropzone to process the queue (instead of default form behavior):
                //document.getElementById("sendButton").addEventListener("click", function(e) {
                $('#sendButton').on('click', function(e) {
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();
                    e.stopPropagation();
                    if (dzClosure.getQueuedFiles().length === 0) {
                        console.log('nincs k??p');
                        $('#mainimage').val("");
                        $('#image-messages').css('display', 'block');
                        $('#message-holder').text('Legal??bb egy k??pet ki kell v??lasztani!');
                    } else {
                        dzClosure.processQueue();
                        $('#message-holder').empty();
                        $('#image-messages').css('display', 'none');

                    }
                });

                //send all the form data along with the files:
                this.on("sendingmultiple", function(data, xhr, formData) {
                    $(":input[name]", $("form")).each(function() {

                        $('.form-check-input').each(function(index, item) {
                            $(item).is(':checked') ? $(item).val('1') : $(item).val('0');
                        });

                        formData.append(this.name, $("[name='" + this.name + "']", $("form")).val());
                    });
                });
            }

        };

        $(document).on("click", ".primary", function() {
            $(".dz-success.dz-complete.main .primary").text("Legyen ez a f?? k??p")
            $(this).text("F?? k??p")
            $(".dz-success.dz-complete").removeClass("main")
            $(this).closest(".dz-success.dz-complete").addClass("main")
            $("#mainimage").val($(this).closest(".dz-success.dz-complete").find(".dz-filename span").text())

        });
    </script>

</body>

</html>