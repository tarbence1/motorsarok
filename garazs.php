<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    $_SESSION['url'] = 'garazs.php';
    header("Location: bejelentkezes.php");
    exit;
}

// Remove advert from garage
if (isset($_POST['remove'])) {
    $advertId = $_POST['advert-id'];
    $stmt4 = $link->prepare("DELETE FROM garage WHERE motorcycleid = ?");
    $stmt4->bind_param('i', $advertId);
    $stmt4->execute();
    $stmt4->close();
}

// Select the motorcycles stored in the garage table
$userid = $_SESSION['id'];
$status = 1;
$sql = "SELECT m.* FROM garage g INNER JOIN motorcycles m ON m.id = g.motorcycleid WHERE g.userid = ? AND m.status = ?";
$statement = $link->prepare($sql);
$statement->bind_param("ii", $userid, $status);
$statement->execute();
$result = $statement->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

// Select technical equipments
$technical_sql = "SELECT te.name FROM mx_technical_equipment mx INNER JOIN technical_equipment te ON mx.technical_equipment_id = te.id WHERE motorcycle_id = ?";
$technical_stmt = $link->prepare($technical_sql);
$technical_stmt->bind_param("i", $productid);

// Select comfort equipments
$comfort_sql = "SELECT ce.name FROM mx_comfort_equipment mx INNER JOIN comfort_equipment ce ON mx.comfort_equipment_id = ce.id WHERE motorcycle_id = ?";
$comfort_stmt = $link->prepare($comfort_sql);
$comfort_stmt->bind_param("i", $productid);

// Select safety equipments
$safety_sql = "SELECT se.name FROM mx_safety_equipment mx INNER JOIN safety_equipment se ON mx.safety_equipment_id = se.id WHERE motorcycle_id = ?";
$safety_stmt = $link->prepare($safety_sql);
$safety_stmt->bind_param("i", $productid);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Garázs</title>

    <link rel="icon" href="images/logo.png" type="image/gif" sizes="16x16">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Own CSS -->
    <link rel="stylesheet" href="assets/CSS/style.css">
    <!-- Scrollbar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Alertify CSS -->
    <link rel="stylesheet" href="assets/CSS/alertify.css">
    <link rel="stylesheet" href="assets/CSS/default.min.css">
    <!-- Alertify JS -->
    <script src="assets/JS/alertify.js"></script>

</head>

<body>

    <button onclick="topFunction()" id="go-to-top"><i class="fas fa-arrow-up"></i></button>

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
                                <span class="d-sm-inline mt-1">alkatrészek</span>
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

        <!-- Cards -->
        <div class="row" id="main-cards-garage">

            <?php
            // If no motorcycles are stored in the garage table
            if (empty($data)) {
                echo '<div class="text-center" id="no-advertisement">Jelenleg nincs garázsba helyezett hirdetés.</div>';
            } else {
                // Else, walk around the fetched array
                foreach ($data as $row) {

                    // Thumbnail images
                    $sql4 = "SELECT * FROM images WHERE productid = ? ORDER BY is_mainimage DESC";
                    $stmt7 = $link->prepare($sql4);
                    $stmt7->bind_param("i", $productid);
                    $productid = $row['id'];
                    $stmt7->execute();
                    $result3 = $stmt7->get_result();
                    $data3 = $result3->fetch_all(MYSQLI_ASSOC);

                    // Technical equipments
                    $technical_stmt->execute();
                    $technical_result = $technical_stmt->get_result();
                    $technical_data = $technical_result->fetch_all(MYSQLI_ASSOC);

                    // Comfort equipments
                    $comfort_stmt->execute();
                    $comfort_result = $comfort_stmt->get_result();
                    $comfort_data = $comfort_result->fetch_all(MYSQLI_ASSOC);

                    // Safety equipments
                    $safety_stmt->execute();
                    $safety_result = $safety_stmt->get_result();
                    $safety_data = $safety_result->fetch_all(MYSQLI_ASSOC);

            ?>

                    <a href="#" data-toggle="modal" data-target="#modal-<?php echo htmlspecialchars($row['id']); ?>">
                        <div class="card" id="motor-cards">
                            <?php
                            $i = 0;
                            foreach ($data3 as $main) {
                                if ($i == 0) {
                                    $mainimage = (file_exists($main['thumbnailimage'])) ? $main['thumbnailimage'] : 'images/no-image.png';
                                }
                                $i++;
                            }

                            echo "<img src='" . htmlspecialchars($mainimage) . "' class='card-img-top' alt='Main Image'/>";

                            ?>
                            <div class="card-body">
                                <div class="short-data">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['manufacturer']) . ' ' . htmlspecialchars($row['model']); ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($row['price']); ?> Ft</h6>
                                    <div class="card-footer">
                                        <i class="fas fa-calendar-alt text-center" data-toggle="tooltip" title="Évjárat"><br><?php echo htmlspecialchars($row['year']); ?></i>
                                        <i class="fas fa-road text-center" data-toggle="tooltip" title="Kilométeróra állása"><br><?php echo htmlspecialchars($row['kilometers']); ?> km</i>
                                        <i class="fas fa-tachometer-alt text-center" data-toggle="tooltip" title="Hengerűrtartalom"><br><?php echo htmlspecialchars($row['capacity']); ?> cm³</i>
                                        <i class="fas fa-id-card text-center" data-toggle="tooltip" title="Jogosítvány típusa"><br><?php echo htmlspecialchars($row['license']); ?></i>
                                    </div>
                                </div>

                                <form action="garazs.php" method="POST" onsubmit="return confirm('Biztosan el szeretné távolítani a garázsból?');">
                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-<?php echo htmlspecialchars($row['id']); ?>">Részletek</a>
                                    <div class="float-right">
                                        <input type="hidden" value="<?php echo htmlspecialchars($row['id']); ?>" name="advert-id">
                                        <input type='submit' value="Eltávolítás" name="remove" id="btn-delete" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </a>

                    <!-- Details Modal -->

                    <div class="modal full" id="modal-<?php echo htmlspecialchars($row['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog-full-width modal-dialog momodel modal-fluid" role="document" id="modalFull">
                            <div class="modal-content-full-width modal-content ">
                                <div class=" modal-header-full-width   modal-header text-center">
                                    <h5 class="modal-title w-100" id="detailsModalLabel"><?php echo htmlspecialchars($row['manufacturer']) . ' ' . htmlspecialchars($row['model']); ?></h5>
                                    <button type="button" class="close " data-dismiss="modal" aria-label="Bezárás">
                                        <span style="font-size: 1.3em;" aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div id="imageGallery<?php echo htmlspecialchars($row['id']); ?>" class="carousel slide" data-ride="carousel">

                                    <!-- Indicators -->
                                    <ul class="carousel-indicators">
                                        <?php
                                        $i = 0;
                                        foreach ($data3 as $rec) {
                                            $actives = '';
                                            if ($i == 0) {
                                                $actives = 'active';
                                            }
                                        ?>
                                            <li data-target="#imageGallery<?php echo htmlspecialchars($row['id']); ?>" data-slide-to="<?php echo $i; ?>" class="<?php echo $actives; ?>"></li>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                    </ul>

                                    <!-- The slideshow -->
                                    <div class="carousel-inner">
                                        <?php
                                        $i = 0;
                                        foreach ($data3 as $rec) {
                                            $actives = '';
                                            if ($i == 0) {
                                                $actives = 'active';
                                            }

                                            $image = (file_exists($rec['thumbnailimage'])) ? $rec['thumbnailimage'] : 'images/no-image.png';

                                        ?>
                                            <div class="carousel-item <?php echo $actives ?>">
                                                <a href="#imagemodal-<?php echo htmlspecialchars($rec['id']) ?>" data-toggle="modal" data-target="#imagemodal-<?php echo htmlspecialchars($rec['id']) ?>">
                                                    <img src="<?php echo htmlspecialchars($image) ?>" class="img-fluid" id="thumbnailimage">
                                                </a>
                                            </div>


                                            <!-- Thumbnail image modal -->
                                            <div class="modal-wrapper" style="z-index: 999999;">
                                                <div id="imagemodal-<?php echo htmlspecialchars($rec['id']) ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" id="centered">
                                                        <img src="<?php echo htmlspecialchars($image) ?>" id="galleryImage" class="img-thumbnail">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                            $i++;
                                        }
                                        ?>
                                    </div>

                                    <!-- Left and right controls -->
                                    <a class="carousel-control-prev" href="#imageGallery<?php echo htmlspecialchars($row['id']); ?>" data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#imageGallery<?php echo htmlspecialchars($row['id']); ?>" data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>

                                </div>

                                <div class="modal-body">
                                    <div class="card-body">

                                        <?php if (!empty($row['description'])) {
                                            echo '<h5 id="description">Leírás</h5><hr>
                                            <div id="description-text">'
                                                . htmlspecialchars($row["description"]) .
                                                '</div>';
                                        }
                                        ?>

                                        <table class="table">
                                            <tr>
                                                <th>Általános adatok</th>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-money-bill-wave"><span style="padding-left: 10px;">Vételár:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['price']); ?> Ft</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-calendar-alt"><span style="padding-left: 10px;">Évjárat:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['year']) . '/' . htmlspecialchars($row['month']); ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-road"><span style="padding-left: 10px;">Kilométeróra
                                                            állása:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['kilometers']); ?> km</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-tachometer-alt"><span style="padding-left: 10px;">Hengerűrtartalom:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['capacity']); ?> cm³</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-power-off"><span style="padding-left: 10px;">Teljesítmény:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['performance']); ?> kW</td>
                                            </tr>
                                            <tr>
                                                <?php if (!empty($row['fuel'])) {
                                                    echo '<tr>
                                                <td><i class="fas fa-oil-can"><span style="padding-left: 10px;">Üzemanyag:</span></i></td>
                                                <td>' . htmlspecialchars($row["fuel"]) .  '</td>
                                            </tr>';
                                                }
                                                ?>

                                                <td><i class="fas fa-motorcycle"><span style="padding-left: 10px;">Kivitel:</span></i>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                            </tr>
                                            <?php if (!empty($row['enginetype'])) {
                                                echo '<tr>
                                                <td><i class="fas fa-dumbbell"><span style="padding-left: 10px;">Munkaütem:</span></i></td>
                                                <td>' . htmlspecialchars($row["enginetype"]) .  '</td>
                                            </tr>';
                                            }
                                            ?>
                                            <tr>
                                                <td><i class="fas fa-id-card text-md-left"><span style="padding-left: 10px;">Jogosítvány típusa:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['license']); ?></td>
                                            </tr>

                                            <tr>
                                                <th style="padding-top: 50px;">Okmányok</th>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td><i class="fas fa-paste"><span style="padding-left: 10px;">Okmányok
                                                            jellege:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['documents']); ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-file"><span style="padding-left: 10px;">Okmányok
                                                            érvényessége:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['documentsvalidity']); ?></td>
                                            </tr>

                                            <?php if (!empty($row['motyear'])) {
                                                echo '<tr>
                                                <td><i class="fas fa-calendar-check"><span style="padding-left: 10px;">Műszaki vizsga
                                                érvényes:</span></i></td>
                                                <td>' . htmlspecialchars($row['motyear']) . '/' . htmlspecialchars($row['motmonth']) . '</td>
                                            </tr>';
                                            }
                                            if (count($technical_data) && count($comfort_data) && count($safety_data)) {
                                            ?>
                                                <tr>
                                                    <th style="padding-top: 50px;">Felszereltség</th>
                                                    <td></td>
                                                </tr>
                                            <?php
                                            }

                                            if (count($technical_data)) {
                                            ?>
                                                <tr>
                                                    <td><i class="fas fa-cogs"><span style="padding-left: 10px;">Műszaki felszereltség:</span></i></td>
                                                    <td></td>
                                                </tr>
                                            <?php
                                            }
                                            foreach ($technical_data as $tr) {
                                                echo "<tr><td></td><td>";
                                                echo $tr['name'];
                                                echo "</td></tr>";
                                            }

                                            if (count($comfort_data)) {
                                            ?>
                                                <tr>
                                                    <td><i class="fas fa-couch"><span style="padding-left: 10px;">Kényelmi felszereltség:</span></i></td>
                                                    <td></td>
                                                </tr>
                                            <?php
                                            }
                                            foreach ($comfort_data as $cr) {
                                                echo "<tr><td></td><td>";
                                                echo $cr['name'];
                                                echo "</td></tr>";
                                            }

                                            if (count($safety_data)) {
                                            ?>
                                                <tr>
                                                    <td><i class="fas fa-shield-alt"><span style="padding-left: 10px;">Biztonsági felszereltség:</span></i></td>
                                                    <td></td>
                                                </tr>
                                            <?php
                                            }
                                            foreach ($safety_data as $sr) {
                                                echo "<tr><td></td><td>";
                                                echo $sr['name'];
                                                echo "</td></tr>";
                                            }
                                            ?>
                                            <tr>
                                                <th style="padding-top: 50px;">Eladó adatai</th>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td><i class="fas fa-user"><span style="padding-left: 10px;">Név:</span></i></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            </tr>
                                            <?php if (!empty($row['email'])) {
                                                echo '<tr>
                                                        <td><i class="fas fa-at"><span style="padding-left: 10px;">Email cím:</span></i></td>
                                                        <td><a href="mailto:' . htmlspecialchars($row['email']) . '">' . htmlspecialchars($row["email"]) .  '</td>
                                                        </tr>';
                                            }
                                            ?>

                                            <tr>
                                                <td><i class="fas fa-mobile-alt"><span style="padding-left: 10px;">Telefonszám:</span></i></td>
                                                <td><a href="tel:<?php echo htmlspecialchars($row['phone']); ?>"><?php echo htmlspecialchars($row['phone']); ?></a></td>
                                            </tr>
                                            <?php if (!empty($row['phone2'])) {
                                                echo '<tr>
                                                        <td><i class="fas fa-phone-volume"><span style="padding-left: 10px;">Másodlagos telefonszám:</span></i></td>
                                                        <td><a href="tel:' . $row['phone2'] . '">' . $row["phone2"] .  '</td> 
                                                        </tr>';
                                            }
                                            ?>
                                            <tr>
                                                <td><i class="fas fa-city"><span style="padding-left: 10px;">Település:</span></i></td>
                                                <td><?php
                                                    if ($row['county'] == 'Budapest') {
                                                        $county = '';
                                                    } else {
                                                        $county = ' megye';
                                                    }

                                                    echo htmlspecialchars($row['settlement']) . ', ' . htmlspecialchars($row['county']) . $county; ?>
                                                </td>

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer-full-width  modal-footer">
                                    <button type="button" class="btn btn-danger btn-md btn-rounded" data-dismiss="modal">Bezárás</button>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
                }
            }
            ?>
        </div>

        <?php
        // Display the success message
        if (isset($_SESSION['successad'])) {
        ?>
            <script>
                alertify.success('Sikeres hirdetésfeladás! Ezen az oldalon megtekintheti hirdetését.');
            </script>
        <?php
            unset($_SESSION['successad']);
        }
        ?>

        <?php include('footer.php'); ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- Jump to top -->
    <script src="assets/JS/jump-to-top.js"></script>
    <!-- Tooltips -->
    <script src="assets/JS/tooltips.js"></script>


</body>

</html>