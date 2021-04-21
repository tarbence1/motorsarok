<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

// Check existing adverts
$noAd = false;

// How many results per page
if (isset($_GET['results'])) {
    $results_per_page = $_GET['results'];
} else {
    $results_per_page = 10;
}

// How many page buttons to show
$range = 2;

// Display the available ads only
$status = 1;

//Select everything from parts
if (
    !isset($_GET['product_name']) || !isset($_GET['county_search']) || !isset($_GET['min_price'])
    || !isset($_GET['max_price']) || !isset($_GET['results']) || !isset($_GET['orderby'])
) {
    $_SESSION['parts_actual_link'] = "";

    $default_sql =  'SELECT p.*, u.premium, u.avatar FROM parts p INNER JOIN users u ON u.id = p.userid WHERE status = ?';
    $default_stmt = $link->prepare($default_sql);
    $default_stmt->bind_param('i', $status);
    $default_stmt->execute();
    $default_result = $default_stmt->get_result();
    $number_of_results = mysqli_num_rows($default_result);

    // Set the number of pages
    $number_of_pages = ceil($number_of_results / $results_per_page);

    if (!isset($_GET['page']) || $_GET['page'] < 1) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    // Check if current page is bigger than the number of pages
    if ($page > $number_of_pages) {
        $page = $number_of_pages;
    }

    // sql LIMIT starting number for the results on the displaying page
    $this_page_first_result = ($page - 1) * $results_per_page;

    //Select everything from motorcycles with limit
    $sql_limit = 'SELECT p.*, u.premium, u.avatar FROM parts p INNER JOIN users u ON u.id = p.userid WHERE status = ? LIMIT ?, ?';
    $stmt_limit = $link->prepare($sql_limit);
    $stmt_limit->bind_param('iii', $status, $this_page_first_result, $results_per_page);
    $stmt_limit->execute();
    $result = $stmt_limit->get_result();
}

// Search panel
include('parts-search.php');

// Select images
$sql2 = "SELECT * FROM parts_images WHERE productid = ? ORDER BY is_mainimage DESC";
$stmt = $link->prepare($sql2);
$stmt->bind_param("i", $productid);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Alkatrész hirdetések</title>

    <link rel="icon" href="images/logo.png" type="image/gif" sizes="16x16">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Own CSS -->
    <link rel="stylesheet" href="assets/CSS/style.css">
    <!-- Scrollbar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <script type="text/javascript" src="http://api.geonames.org/export/geonamesData.js?username=tarbence1"></script>
    <script type="text/javascript" src="assets/JS/jsr_class.js"></script>
    <!-- Alertify CSS -->
    <link rel="stylesheet" href="assets/CSS/alertify.css">
    <link rel="stylesheet" href="assets/CSS/default.min.css">
    <!-- Alertify JS -->
    <script src="assets/JS/alertify.js"></script>
</head>

<body onload="setDefaultCountry();">

    <button onclick="topFunction()" id="go-to-top"><i class="fas fa-arrow-up"></i></button>

    <div class="wrapper">

        <!-- Sidebar  -->
        <nav id="sidebar">
            <div id="dismiss">
                <i class="fas fa-arrow-left"></i>
            </div>

            <div class="sidebar-header">
                <h3>motor<span style="color: #ee4a4a">s</span>arok</h3>
            </div>

            <ul class="list-unstyled components">
                <form action="" id="search-form" method="GET">
                    <div class="form-inline">
                        <div class="inputBox">
                            <label style="padding-right: 130px">Megnevezés</label>
                            <input type="text" name="product_name" style="text-align: right" value="<?php if (isset($_GET['product_name'])) {
                                                                                                        echo $_GET['product_name'];
                                                                                                    } ?>">
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="inputBox">
                            <label style="padding-right: 130px">Minimum ár</label>
                            <input type="text" name="min_price" style="text-align: right" value="<?php if (isset($_GET['min_price'])) {
                                                                                                        echo $_GET['min_price'];
                                                                                                    } ?>">
                            <span style="padding-left: 2px">Ft</span>

                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="inputBox">
                            <label style="padding-right: 130px">Maximum ár</label>
                            <input type="text" name="max_price" style="text-align: right" value="<?php if (isset($_GET['max_price'])) {
                                                                                                        echo $_GET['max_price'];
                                                                                                    } ?>">
                            <span style="padding-left: 2px">Ft</span>
                        </div>
                    </div>
                    <div class="select-settings">

                        <div class="county-label">
                            <label>Megye</label>
                        </div>
                        <select class="form-control col-8 mx-auto" name="county_search">
                            <?php if (isset($_GET['county_search']) && $_GET['county_search'] !== "") {
                                echo '<option value="' . $_GET['county_search'] . '" selected>' . $_GET['county_search'] . '</option>';
                            } ?>
                            <option value="">Összes</option>
                            <?php
                            // Display counties
                            if ($counties_result->num_rows > 0) {
                                while ($row = $counties_result->fetch_assoc()) {
                                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                }
                            } else {
                                echo 'Nincs elérhető megye!';
                            }
                            ?>
                        </select>

                        <div class="sorting-label">
                            <label>Rendezés</label>
                        </div>
                        <select class="form-control col-8 mx-auto" name="orderby">
                            <?php if (isset($_GET['orderby']) && $_GET['orderby'] !== "") {
                                if ($_GET['orderby'] == 1) {
                                    $orderby = 'Olcsók';
                                }
                                switch ($_GET['orderby']) {
                                    case 1:
                                        $orderby = "Ár szerint növekvő";
                                        break;
                                    case 2:
                                        $orderby = "Ár szerint csökkenő";
                                        break;
                                    case 3:
                                        $orderby = "Márka szerint növekvő";
                                        break;
                                    case 4:
                                        $orderby = "Márka szerint csökkenő";
                                        break;
                                }

                                echo '<option value="' . $_GET['orderby'] . '" selected>' . $orderby . '</option>';
                            }
                            ?>
                            <option value="">Alapértelmezett</option>
                            <option value="1">Ár szerint növekvő</option>
                            <option value="2">Ár szerint csökkenő</option>
                        </select>

                        <div class="results-label">
                            <label>Találatok (db/oldal)</label>
                        </div>
                        <select class="form-control col-8 mx-auto" id="results" name="results">
                            <option value="10" <?php if (isset($_GET['results']) && $_GET['results'] == '10' || !isset($_GET['results'])) echo 'selected'; ?>>10</option>
                            <option value="25" <?php if (isset($_GET['results']) && $_GET['results'] == '25') echo 'selected'; ?>>25</option>
                            <option value="50" <?php if (isset($_GET['results']) && $_GET['results'] == '50') echo 'selected'; ?>>50</option>
                            <option value="100" <?php if (isset($_GET['results']) && $_GET['results'] == '100') echo 'selected'; ?>>100</option>
                        </select>

                        <div class="search-button">
                            <button type="submit" class="btn btn-info sidebar-search" name="search_button">Keresés</button>
                        </div>
                    </div>
                </form>
            </ul>



        </nav>
    </div>

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
                        <li class="nav-item" style="margin-top: -7px;">
                            <button type="button" id="sidebarCollapse" class="btn btn-md btn-link">
                                <a href="#" class="nav-link d-flex flex-column">
                                    <i class="fas fa-search fa-lg"></i>
                                    <span class="d-sm-inline mt-1">keresés</span>
                                </a>
                            </button>
                        </li>
                        <li class="nav-item">
                            <a href="index.php" class="nav-link d-flex flex-column">
                                <i class="fas fa-motorcycle fa-lg"></i>
                                <span class="d-sm-inline mt-1">motorkerékpárok</span>
                            </a>
                        </li>
                        <?php
                        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                        ?>
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
                        <?php
                        } else {
                        ?>
                            <li class="nav-item">
                                <a href="bejelentkezes.php" class="nav-link d-flex flex-column">
                                    <i class="fas fa-sign-in-alt fa-lg"></i>
                                    <span class="d-sm-inline mt-1">bejelentkezés</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="regisztracio.php" class="nav-link d-flex flex-column">
                                    <i class="fas fa-sign-out-alt fa-lg"></i>
                                    <span class="d-sm-inline mt-1">regisztráció</span>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
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
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Secondary nav -->
        <nav class="navbar navbar-light bg-light" id="secondary-nav">
            <ul class="nav navbar-nav ml-auto" id="secondary-nav-ul">
                <li class="nav-item" id="nav-settlement">
                    <a class="nav-link" href="#locationModal" data-toggle="modal" data-target="#locationModal">
                        <i class="fas fa-city"></i>
                        <span id="json">Település</span>
                    </a>
                </li>
                <li class="nav-item dropdown" id="garage">
                    <a class="nav-link" href="#" id="garageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-warehouse"></i>
                        <span id="nav-garage">Garázs</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="garageDropdown" id="garageDropdownOpen">
                        <a class="dropdown-item" href="garazs.php">Motorkerékpár garázs</a>
                        <a class="dropdown-item" href="alkatresz-garazs.php">Alkatrész garázs</a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Location Modal -->
        <div class="modal fade" id="locationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Település kiválasztása</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span>Kérjük engedélyezze, hogy megtaláljuk automatikusan jelenlegi helyezetét, így tudunk segíteni az útvonal tervezésben.</span>
                        <div class="locationButtons">
                            <button type="button" class="btn btn-primary" onclick="getLocation();">
                                Találjon meg
                            </button>
                            <hr>
                            <span>Amennyiben nem szeretné engedélyezni a tartózkodási helyének lekérését, kérjük írja be a kívánt település irányítószámát vagy a település nevét, így bárhonnan megtervezheti az indulást.</span>
                            <div class="zipCodeManual">
                                <label for="inputState">Ország</label>
                                <select id="countrySelect" class="form-control" name="country">
                                    <option value="HU">Magyarország</option>
                                    <option value="RO">Románia</option>
                                    <option value="SK">Szlovákia</option>
                                </select>
                                <div class="form-row" id="zipCodeInputs">
                                    <div class="form-group">
                                        <label for="postalcodeInput">Irányítószám</label>
                                        <input type="text" class="form-control" id="postalcodeInput" name="postalcode" oninput="postalCodeLookup();">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputCity">Település</label>
                                        <input type="text" class="form-control" id="placeInput" name="place">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <p>Jelenleg mentett település: <span id="currentLocation">-</span></p>





                            <button class="btn btn-danger" onclick="deleteItems(); location.reload();">Törlés</button>
                            <button class="btn btn-primary" onclick="saveLocation();">Mentés</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page navigation -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                if ($page > 1) {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SESSION['parts_actual_link'] . '&page=1' ?>" aria-label="Előző" data-toggle="tooltip" title="Első oldal">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Első oldal</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="<?php echo $_SESSION['parts_actual_link'] . '&page=1' ?>" aria-label="Első oldal" data-toggle="tooltip" title="Első oldal">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Első oldal</span>
                        </a>
                    </li>
                <?php
                }


                for ($x = ($page - $range); $x < (($page + $range) + 1); $x++) {
                    if (($x > 0) && ($x <= $number_of_pages)) {
                        if ($x == $page) {
                            $disabled = ' btn disabled';
                        } else {
                            $disabled = '';
                        }
                        echo '<li class="page-item"><a class="page-link' . $disabled . '" href="' . $_SESSION['parts_actual_link'] . '&page=' .  $x . '">' . $x . '</a></li>';
                    }
                }


                if ($number_of_pages > $page) {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SESSION['parts_actual_link']; ?>&page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Utolsó oldal</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="<?php echo $_SESSION['parts_actual_link']; ?>&page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Utolsó oldal</span>
                        </a>
                    </li>
                <?php
                }
                ?>

            </ul>
        </nav>

        <!-- Cards -->
        <div class="row" id="main-cards">

            <?php
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $productid = $row['id'];
                    $stmt->execute();
                    $result2 = $stmt->get_result();
                    $data = $result2->fetch_all(MYSQLI_ASSOC);

                    $premium = $row['premium'];
                    $border = "";

                    if ($premium == 0) {
                        $border = "";
                    } else if ($premium == 1) {
                        $border = "border-info";
                    } else if ($premium == 2) {
                        $border = "border-secondary";
                    } else if ($premium == 3) {
                        $border = "border-warning";
                    }



            ?>

                    <a href="#" data-toggle="modal" data-target="#alkatresz-hirdetes-<?php echo $row['id']; ?>">
                        <div class="card <?php echo $border ?>" id="motor-cards">
                            <?php

                            $i = 0;
                            foreach ($data as $main) {
                                if ($i == 0) {

                                    $mainimage = (file_exists($main['thumbnailimage'])) ? $main['thumbnailimage'] : 'images/no-part-image.png';
                                }
                                $i++;
                            }

                            echo "<img src='" . $mainimage . "' class='card-img-top' alt='Main Image'/>";
                            ?>
                            <div class="card-body">
                                <div class="short-data">
                                    <?php
                                    if (!empty($row['avatar']) && file_exists($row['avatar'])) {
                                        $avatar = $row['avatar'];
                                    } else {
                                        $avatar = 'images/no-avatar.png';
                                    }
                                    ?>
                                    <img src="<?php echo $avatar ?>" class="float-right rounded-circle index-avatar">
                                    <h5 class="card-title"><?php echo $row['product_name']; ?></h5>

                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['price']; ?> Ft</h6>
                                </div>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#alkatresz-hirdetes-<?php echo $row['id']; ?>">Részletek</a>
                                <form action="" method="POST" id="upload-to-garage<?php echo $row['id']; ?>">
                                    <div class="float-right">
                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="advert-id">
                                        <button type="submit" class="btn bg-transparent" name="garage"><i class="fas fa-warehouse fa-lg" data-toggle="tooltip" title="Garázsba helyezés"></i></button>
                                        <a href="#" onclick="Copy(this.id);return false;" id="copy-<?php echo $row['id']; ?>"><i class="fas fa-copy fa-lg" data-toggle="tooltip" title="Link másolása" style="padding-left: 10px;"></i></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </a>

                    <!-- Details Modal -->
                    <div class="modal full" id="alkatresz-hirdetes-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog-full-width modal-dialog momodel modal-fluid" role="document" id="modalFull">
                            <div class="modal-content-full-width modal-content ">
                                <div class=" modal-header-full-width   modal-header text-center">
                                    <h5 class="modal-title w-100" id="detailsModalLabel"><?php echo $row['product_name']; ?></h5>
                                    <button type="button" class="close " data-dismiss="modal" aria-label="Bezárás">
                                        <span style="font-size: 1.3em;" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div id="imageGallery<?php echo $row['id']; ?>" class="carousel slide" data-ride="carousel">

                                    <!-- Indicators -->
                                    <ul class="carousel-indicators">
                                        <?php
                                        $i = 0;
                                        foreach ($data as $rec) {
                                            $actives = '';
                                            if ($i == 0) {
                                                $actives = 'active';
                                            }
                                        ?>
                                            <li data-target="#imageGallery<?php echo $row['id']; ?>" data-slide-to="<?php echo $i; ?>" class="<?php echo $actives; ?>"></li>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                    </ul>

                                    <!-- The slideshow -->
                                    <div class="carousel-inner">

                                        <?php
                                        $i = 0;
                                        foreach ($data as $rec) {
                                            $actives = '';
                                            if ($i == 0) {
                                                $actives = 'active';
                                            }

                                            $image = (file_exists($rec['thumbnailimage'])) ? $rec['thumbnailimage'] : 'images/no-part-image.png';

                                        ?>
                                            <div class="carousel-item <?php echo $actives ?>">
                                                <a href="#imagemodal-<?php echo $rec['id'] ?>" data-toggle="modal" data-target="#imagemodal-<?php echo $rec['id'] ?>">
                                                    <img src="<?php echo $image ?>" class="img-fluid" id="thumbnailimage">
                                                </a>
                                            </div>


                                            <!-- Thumbnail image modal -->
                                            <div class="modal-wrapper" style="z-index: 999999;">
                                                <div id="imagemodal-<?php echo $rec['id'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" id="centered">
                                                        <img src="<?php echo $image ?>" id="galleryImage" class="img-thumbnail">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                            $i++;
                                        }

                                        ?>
                                    </div>

                                    <!-- Left and right controls -->
                                    <a class="carousel-control-prev" href="#imageGallery<?php echo $row['id']; ?>" data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#imageGallery<?php echo $row['id']; ?>" data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>

                                </div>

                                <div class="modal-body">
                                    <div class="card-body">
                                        <form action="" method="POST" id="modal-to-garage<?php echo $row['id']; ?>">
                                            <input type="hidden" value="<?php echo $row['id']; ?>" name="advert-id">
                                            <button type="submit" class="btn bg-transparent" name="garage"><i class="fas fa-warehouse fa-lg" data-toggle="tooltip" title="Garázsba helyezés"></i></button>
                                            <a href="#" onclick="ModalCopy();return false;" id="copy-<?php echo $row['id']; ?>"><i class="fas fa-copy fa-lg" data-toggle="tooltip" title="Link másolása" style="padding-left: 10px;"></i></a>
                                        </form>


                                        <?php if (!empty($row['description'])) {
                                            echo '<h5 id="description">Leírás</h5><hr>
                                            <div id="description-text">'
                                                . $row["description"] .
                                                '</div>';
                                        }
                                        ?>

                                        <table class="table">
                                            <tr>
                                                <th>Általános adatok</th>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-tools"><span style="padding-left: 10px;">Megnevezés:</span></i></td>
                                                <td><?php echo $row['product_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-money-bill-wave"><span style="padding-left: 10px;">Vételár:</span></i></td>
                                                <td><?php echo $row['price']; ?> Ft</td>
                                            </tr>
                                            <?php if (!empty($row['motorcycle_type'])) {
                                                echo '<tr>
                                                        <td><i class="fas fa-motorcycle"><span style="padding-left: 10px;">Kompatibilis:</span></i></td>
                                                        <td>' . $row['motorcycle_type'] .  '</td> 
                                                        </tr>';
                                            }
                                            ?>
                                            <tr>
                                                <th style="padding-top: 50px;">Eladó adatai</th>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td><i class="fas fa-user"><span style="padding-left: 10px;">Név:</span></i></td>
                                                <td><?php echo $row['name']; ?></td>
                                            </tr>
                                            <?php if (!empty($row['email'])) {
                                                echo '<tr>
                                                        <td><i class="fas fa-at"><span style="padding-left: 10px;">Email cím:</span></i></td>
                                                        <td><a href="mailto:' . $row['email'] . '">' . $row["email"] .  '</td>
                                                        </tr>';
                                            }
                                            ?>

                                            <tr>
                                                <td><i class="fas fa-mobile-alt"><span style="padding-left: 10px;">Telefonszám:</span></i></td>
                                                <td><a href="tel:<?php echo $row['phone']; ?>"><?php echo $row['phone']; ?></a></td>
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

                                                    echo $row['settlement'] . ', ' . $row['county'] . $county; ?>
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="route(this.id)" id="btn-<?php echo $row['id']; ?>" style="margin-left">Útvonal</button>
                                                </td>
                                                <input type="text" id="position<?php echo $row['id']; ?>" value="<?php echo $row['settlement']; ?>" style="display: none;">

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
            } else {
                $noAd = true;
            }

            if ($noAd) {
                echo '<div class="text-center" id="no-advertisement">Nincs megjelenítendő hirdetés.</div>';
            }
            ?>


        </div>

        <!-- Page navigation -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                if ($page > 1) {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SESSION['parts_actual_link'] . '&page=1' ?>" aria-label="Előző" data-toggle="tooltip" title="Első oldal">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Első oldal</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="<?php echo $_SESSION['parts_actual_link'] . '&page=1' ?>" aria-label="Első oldal" data-toggle="tooltip" title="Első oldal">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Első oldal</span>
                        </a>
                    </li>
                <?php
                }


                for ($x = ($page - $range); $x < (($page + $range) + 1); $x++) {
                    if (($x > 0) && ($x <= $number_of_pages)) {
                        if ($x == $page) {
                            $disabled = ' btn disabled';
                        } else {
                            $disabled = '';
                        }
                        echo '<li class="page-item"><a class="page-link' . $disabled . '" href="' . $_SESSION['parts_actual_link'] . '&page=' .  $x . '">' . $x . '</a></li>';
                    }
                }


                if ($number_of_pages > $page) {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SESSION['parts_actual_link']; ?>&page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Utolsó oldal</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="<?php echo $_SESSION['parts_actual_link']; ?>&page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Utolsó oldal</span>
                        </a>
                    </li>
                <?php
                }
                ?>

            </ul>
        </nav>

        <?php include('footer.php'); ?>
    </div>

    <!-- Search overlay -->
    <div class="overlay"></div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scrollbar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- Jump to top -->
    <script src="assets/JS/jump-to-top.js"></script>
    <!-- Tooltips -->
    <script src="assets/JS/tooltips.js"></script>
    <!-- Location delete -->
    <script src="assets/JS/location-delete.js"></script>
    <!-- ZIP code finder -->
    <script src="assets/JS/zipcode-finder.js"></script>
    <!-- Location finder -->
    <script src="assets/JS/location-finder.js"></script>
    <!-- Save location from input -->
    <script src="assets/JS/location-input.js"></script>
    <!-- Start and End positions for routing -->
    <script src="assets/JS/routing.js"></script>
    <!-- Copy link -->
    <script src="assets/JS/parts-copy-link.js"></script>
    <!-- Show modal from link -->
    <script src="assets/JS/modal-display.js"></script>
    <!-- Show modal link in search bar -->
    <script src="assets/JS/modal-link.js"></script>
    <!-- Hide modal link from search bar -->
    <script src="assets/JS/hide-modal-link.js"></script>

    <!-- Sidebar -->
    <script>
        $(document).ready(function() {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#dismiss, .overlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').addClass('active');
                $('.overlay').addClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });
    </script>

    <!-- AJAX Garage -->
    <script>
        //From card to garage
        $(document).ready(function() {
            //form when submit
            $("form[id*=upload-to-garage]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "upload-to-parts-garage.php",
                    method: "post",
                    data: $(this).serialize() + "&garage=somevalue",
                    dataType: "text",
                    success: function(response) {
                        var errorMessages = JSON.parse(response);
                        if (errorMessages.ok) {
                            alertify.success(errorMessages.errorMsg);
                        } else {
                            alertify.error(errorMessages.errorMsg);
                        }
                    }
                });
            });
        });

        //From modal to garage
        $(document).ready(function() {
            //form when submit
            $("form[id*=modal-to-garage]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "upload-to-parts-garage.php",
                    method: "post",
                    data: $(this).serialize() + "&garage=somevalue",
                    dataType: "text",
                    success: function(response) {
                        var errorMessages = JSON.parse(response);
                        if (errorMessages.ok) {
                            alertify.success(errorMessages.errorMsg);
                        } else {
                            alertify.error(errorMessages.errorMsg);
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>