<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

// Check existing adverts
$noAd = false;

// How many results per page
if (isset($_POST['results'])) {
    $results_per_page = $_POST['results'];
} else {
    $results_per_page = 1;
}


// How many page buttons to show
$range = 2;

// Display the available ads only
$status = 1;

// Search panel
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search_button'])) {
    $params[] = "i";
    $vars[] = $status;
    $limit_params[] = "i";
    $limit_vars[] = $status;
    $default_sql = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ?';
    $sql_limit = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ?';

    // Manufacturer
    if (isset($_GET['manufacturer_search']) && $_GET['manufacturer_search'] !== "") {
        $manufacturer_search = $_GET['manufacturer_search'];
        $default_sql .= " AND manufacturer LIKE ?";
        array_push($params, 's');
        array_push($vars, $manufacturer_search);

        $sql_limit .= " AND manufacturer LIKE ?";
        array_push($limit_params, 's');
        array_push($limit_vars, $manufacturer_search);
    }

    // Type
    if (isset($_GET['type_search']) && $_GET['type_search'] !== "") {
        $type_search = $_GET['type_search'];
        $default_sql .= " AND type LIKE ?";
        array_push($params, 's');
        array_push($vars, $type_search);

        $sql_limit .= " AND type LIKE ?";
        array_push($limit_params, 's');
        array_push($limit_vars, $type_search);
    }

    // Driver license
    if (isset($_GET['driver_license_search']) && $_GET['driver_license_search'] !== "") {
        $driver_license_search = $_GET['driver_license_search'];
        $default_sql .= " AND license LIKE ?";
        array_push($params, 's');
        array_push($vars, $driver_license_search);

        $sql_limit .= " AND license LIKE ?";
        array_push($limit_params, 's');
        array_push($limit_vars, $driver_license_search);
    }

    // Minimum year
    if (isset($_GET['min_year']) && $_GET['min_year'] !== "") {
        $min_year = $_GET['min_year'];
        $default_sql .= " AND year >= ?";
        array_push($params, 'i');
        array_push($vars, $min_year);

        $sql_limit .= " AND year >= ?";
        array_push($limit_params, 'i');
        array_push($limit_vars, $min_year);
    }

    // Maximum year
    if (isset($_GET['max_year']) && $_GET['max_year'] !== "") {
        $max_year = $_GET['max_year'];
        $default_sql .= " AND year <= ?";
        array_push($params, 'i');
        array_push($vars, $max_year);

        $sql_limit .= " AND year <= ?";
        array_push($limit_params, 'i');
        array_push($limit_vars, $max_year);
    }

    // County
    if (isset($_GET['county_search']) && $_GET['county_search'] !== "") {
        $county_search = $_GET['county_search'];
        $default_sql .= " AND county LIKE ?";
        array_push($params, 's');
        array_push($vars, $county_search);

        $sql_limit .= " AND county LIKE ?";
        array_push($limit_params, 'i');
        array_push($limit_vars, $county_search);
    }

    // Minimum price
    if (isset($_GET['min_price']) && $_GET['min_price'] !== "") {
        $min_price = $_GET['min_price'];
        $default_sql .= " AND price >= ?";
        array_push($params, 'i');
        array_push($vars, $min_price);

        $sql_limit .= " AND price >= ?";
        array_push($limit_params, 'i');
        array_push($limit_vars, $min_price);
    }

    // Maximum price
    if (isset($_GET['max_price']) && $_GET['max_price'] !== "") {
        $max_price = $_GET['max_price'];
        $default_sql .= " AND price <= ?";
        array_push($params, 'i');
        array_push($vars, $max_price);

        $sql_limit .= " AND price <= ?";
        array_push($limit_params, 'i');
        array_push($limit_vars, $max_price);
    }

    // Minimum ccm
    if (isset($_GET['min_ccm']) && $_GET['min_ccm'] !== "") {
        $min_ccm = $_GET['min_ccm'];
        $default_sql .= " AND performance >= ?";
        array_push($params, 'i');
        array_push($vars, $min_ccm);

        $sql_limit .= " AND performance >= ?";
        array_push($limit_params, 'i');
        array_push($limit_vars, $min_ccm);
    }

    // Maximum ccm
    if (isset($_GET['max_ccm']) && $_GET['max_ccm'] !== "") {
        $max_ccm = $_GET['max_ccm'];
        $default_sql .= " AND performance <= ?";
        array_push($params, 'i');
        array_push($vars, $max_ccm);

        $sql_limit .= " AND performance <= ?";
        array_push($limit_params, 'i');
        array_push($limit_vars, $max_ccm);
    }

    $default_stmt = $link->prepare($default_sql);
    $default_stmt->bind_param(implode("", $params), ...$vars);
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


    if (isset($_GET['orderby']) && $_GET['orderby'] == 1) {
        $sql_limit .= " ORDER BY u.premium DESC, price ASC LIMIT ?, ?";
        array_push($limit_params, 'ii');
        array_push($limit_vars, $this_page_first_result, $results_per_page);
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 2) {
        $sql_limit .= " ORDER BY u.premium DESC, price DESC LIMIT ?, ?";
        array_push($limit_params, 'ii');
        array_push($limit_vars, $this_page_first_result, $results_per_page);
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 3) {
        $sql_limit .= " ORDER BY u.premium DESC, manufacturer ASC LIMIT ?, ?";
        array_push($limit_params, 'ii');
        array_push($limit_vars, $this_page_first_result, $results_per_page);
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 4) {
        $sql_limit .= " ORDER BY u.premium DESC, manufacturer DESC LIMIT ?, ?";
        array_push($limit_params, 'ii');
        array_push($limit_vars, $this_page_first_result, $results_per_page);
    } else {
        $sql_limit .= " ORDER BY u.premium DESC LIMIT ?, ?";
        array_push($limit_params, 'ii');
        array_push($limit_vars, $this_page_first_result, $results_per_page);
    }

    echo $sql_limit;

    $stmt_limit = $link->prepare($sql_limit);
    $stmt_limit->bind_param(implode("", $limit_params), ...$limit_vars);
    $stmt_limit->execute();
    $result = $stmt_limit->get_result();
} else {
    //Select everything from motorcycles
    if (isset($_GET['orderby']) && $_GET['orderby'] == 1) {
        $default_sql =  'SELECT m.*, u.premium FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, price ASC';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 2) {
        $default_sql =  'SELECT m.*, u.premium FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, price DESC';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 3) {
        $default_sql =  'SELECT m.*, u.premium FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, manufacturer ASC';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 4) {
        $default_sql =  'SELECT m.*, u.premium FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, manufacturer DESC';
    } else {
        $default_sql =  'SELECT m.*, u.premium FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC';
    }

    echo $default_sql;
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


    if (isset($_GET['orderby']) && $_GET['orderby'] == 1) {
        $sql_limit = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, price ASC LIMIT ?, ?';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 2) {
        $sql_limit = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, price DESC LIMIT ?, ?';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 3) {
        $sql_limit = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, manufacturer ASC LIMIT ?, ?';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 4) {
        $sql_limit = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC, manufacturer DESC LIMIT ?, ?';
    } else {
        $sql_limit = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ? ORDER BY u.premium DESC LIMIT ?, ?';
    }

    $stmt_limit = $link->prepare($sql_limit);
    $stmt_limit->bind_param('iii', $status, $this_page_first_result, $results_per_page);
    $stmt_limit->execute();
    $result = $stmt_limit->get_result();
}


// Select manufacturers
$manufacturers_sql = "SELECT * FROM manufacturers";
$manufacturers_result = $link->query($manufacturers_sql);

// Select motorcycle types
$types_sql = "SELECT * FROM motorcycle_types";
$types_result = $link->query($types_sql);

// Select counties
$counties_sql = "SELECT * FROM counties ORDER BY name";
$counties_result = $link->query($counties_sql);


// Select images
$sql2 = "SELECT * FROM images WHERE productid = ? ORDER BY is_mainimage DESC";
$stmt = $link->prepare($sql2);
$stmt->bind_param("i", $productid);




?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>motorsarok.hu</title>

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
                <form action="index.php" id="search-form" method="GET">
                    <div class="form-inline">
                        <div class="inputBox">
                            <label style="padding-right: 130px">Minimum ár</label>
                            <input type="text" name="min_price" style="text-align: right">
                            <span style="padding-left: 2px">Ft</span>

                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="inputBox">
                            <label style="padding-right: 130px">Maximum ár</label>
                            <input type="text" name="max_price" style="text-align: right">
                            <span style="padding-left: 2px">Ft</span>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="inputBox">
                            <label style="padding-right: 50px; font-size: 13px">Minimum hengerűrtartalom</label>
                            <input type="text" name="min_ccm" style="text-align: right; width: 80%">
                            <span style="padding-left: 2px">cm³</span>

                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="inputBox">
                            <label style="padding-right: 50px; font-size: 13px">Maximum hengerűrtartalom</label>
                            <input type="text" name="max_ccm" style="text-align: right; width: 80%">
                            <span style="padding-left: 2px">cm³</span>

                        </div>
                    </div>

                    <div class="select-settings">
                        <div class="brand-label">
                            <label>Gyártó</label>
                        </div>
                        <div class="brand-select">
                            <select class="form-control col-8 mx-auto" name="manufacturer_search">
                                <option value="">Összes</option>
                                <?php
                                // Display manufacturers
                                if ($manufacturers_result->num_rows > 0) {
                                    while ($row = $manufacturers_result->fetch_assoc()) {
                                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                    }
                                } else {
                                    echo 'Nincs elérhető gyártó!';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="category-label">
                            <label>Kivitel</label>
                        </div>
                        <div class="motorcycle-category-select">
                            <select class="form-control col-8 mx-auto" name="type_search">
                                <option value="">Összes</option>
                                <?php
                                // Display manufacturers
                                if ($types_result->num_rows > 0) {
                                    while ($row = $types_result->fetch_assoc()) {
                                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                    }
                                } else {
                                    echo 'Nincs elérhető kivitel!';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="year-label">
                            <label>Évjárat (Min-Max)</label>
                        </div>
                        <div class="year-select">
                            <select class="form-control col-5 year" name="min_year">
                                <option value="">Összes</option>
                                <?php
                                for ($i = 1950; $i <= date('Y'); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="year-select">
                            <select class="form-control col-5 year" name="max_year">
                                <option value="">Összes</option>
                                <?php
                                for ($i = 1950; $i <= date('Y'); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="driver-license-label">
                            <label>Jogosítvány</label>
                        </div>
                        <select class="form-control col-8 mx-auto" name="driver_license_search">
                            <option value="">Összes</option>
                            <option value="1">AM</option>
                            <option value="2">A2</option>
                            <option value="3">A1</option>
                            <option value="4">A</option>
                        </select>

                        <div class="county-label">
                            <label>Megye</label>
                        </div>
                        <select class="form-control col-8 mx-auto" name="county_search">
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
                        <li class="nav-item" style="margin-top: -7px;">
                            <button type="button" id="sidebarCollapse" class="btn btn-md btn-link">
                                <a href="#" class="nav-link d-flex flex-column">
                                    <i class="fas fa-search fa-lg"></i>
                                    <span class="d-sm-inline mt-1">keresés</span>
                                </a>
                            </button>
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
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarSortDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-sort-numeric-down"></i>
                        <span id="nav-sorter">Rendezés</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="navbarSortDropdown" id="sortDropdown">
                        <a class="dropdown-item <?php if (!isset($_GET['orderby'])) echo 'active'; ?>" href="index.php">Alapértelmezett</a>
                        <a class="dropdown-item <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 1) echo 'active'; ?>" href="index.php?orderby=1"><i class="fas fa-sort-numeric-down sort-item"></i>Olcsók</a>
                        <a class="dropdown-item <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 2) echo 'active'; ?>" href="index.php?orderby=2"><i class="fas fa-sort-numeric-up sort-item"></i>Drágák</a>
                        <a class="dropdown-item <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 3) echo 'active'; ?>" href="index.php?orderby=3"><i class="fas fa-sort-alpha-down sort-item"></i>Márka (A-Z)</a>
                        <a class="dropdown-item <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 4) echo 'active'; ?>" href="index.php?orderby=4"><i class="fas fa-sort-alpha-up sort-item"></i>Márka (Z-A)</a>
                    </div>

                </li>
                <form action="index.php" id="order-form" method="GET">
                    <li class="nav-item">
                        <select name="orderby" onchange="this.form.submit()">
                            <option value="1">Olcsók</option>
                            <option value="2">Drágák</option>
                            <option value="3">Márka (A-Z)</option>
                            <option value="4">Márka (Z-A)</option>
                        </select>
                    </li>
                </form>
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

        <form action="" name="results-form" method="post">
            <select class="form-control form-control-md" id="results" name="results" data-toggle="tooltip" title="Találatok (db/oldal)" onchange="this.form.submit()">
                <option value="10" <?php if (isset($_POST['results']) && $_POST['results'] == '10' || !isset($_POST['results'])) echo 'selected'; ?>>10</option>
                <option value="25" <?php if (isset($_POST['results']) && $_POST['results'] == '25') echo 'selected'; ?>>25</option>
                <option value="50" <?php if (isset($_POST['results']) && $_POST['results'] == '50') echo 'selected'; ?>>50</option>
                <option value="100" <?php if (isset($_POST['results']) && $_POST['results'] == '100') echo 'selected'; ?>>100</option>
            </select>
        </form>


        <!-- Page navigation -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">

                <?php
                if ($page > 1) {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=1" aria-label="Előző" data-toggle="tooltip" title="Első oldal">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Előző</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="index.php?page=1" aria-label="Első oldal" data-toggle="tooltip" title="Első oldal">
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
                        echo '<li class="page-item"><a class="page-link' . $disabled . '" href="index.php?page=' .  $x . '">' . $x . '</a></li>';
                    }
                }


                if ($number_of_pages > $page) {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Utolsó oldal</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="index.php?page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
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

                    <a href="#" data-toggle="modal" data-target="#hirdetes-<?php echo $row['id']; ?>">
                        <div class="card <?php echo $border ?>" id="motor-cards">
                            <?php

                            $i = 0;
                            foreach ($data as $main) {
                                if ($i == 0) {
                                    $mainimage = (file_exists($main['thumbnailimage'])) ? $main['thumbnailimage'] : 'images/no-image.png';
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
                                    <h5 class="card-title"><?php echo $row['manufacturer'] . ' ' . $row['model']; ?></h5>

                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['price']; ?> Ft</h6>
                                    <div class="card-footer">
                                        <i class="fas fa-calendar-alt text-center" data-toggle="tooltip" title="Évjárat"><br><?php echo $row['year']; ?></i>
                                        <i class="fas fa-road text-center" data-toggle="tooltip" title="Kilométeróra állása"><br><?php echo $row['kilometers']; ?> km</i>
                                        <i class="fas fa-tachometer-alt text-center" data-toggle="tooltip" title="Hengerűrtartalom"><br><?php echo $row['capacity']; ?> cm³</i>
                                        <i class="fas fa-id-card text-center" data-toggle="tooltip" title="Jogosítvány típusa"><br><?php echo $row['license']; ?></i>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#hirdetes-<?php echo $row['id']; ?>">Részletek</a>
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


                    <div class="modal full" id="hirdetes-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog-full-width modal-dialog momodel modal-fluid" role="document" id="modalFull">
                            <div class="modal-content-full-width modal-content ">
                                <div class=" modal-header-full-width   modal-header text-center">
                                    <h5 class="modal-title w-100" id="detailsModalLabel"><?php echo $row['manufacturer'] . ' ' . $row['model']; ?></h5>
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

                                            $image = (file_exists($rec['thumbnailimage'])) ? $rec['thumbnailimage'] : 'images/no-image.png';

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
                                                <td><i class="fas fa-money-bill-wave"><span style="padding-left: 10px;">Vételár:</span></i></td>
                                                <td><?php echo $row['price']; ?> Ft</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-calendar-alt"><span style="padding-left: 10px;">Évjárat:</span></i></td>
                                                <td><?php echo $row['year'] . '/' . $row['month']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-road"><span style="padding-left: 10px;">Kilométeróra
                                                            állása:</span></i></td>
                                                <td><?php echo $row['kilometers']; ?> km</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-tachometer-alt"><span style="padding-left: 10px;">Hengerűrtartalom:</span></i></td>
                                                <td><?php echo $row['capacity']; ?> cm³</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-power-off"><span style="padding-left: 10px;">Teljesítmény:</span></i></td>
                                                <td><?php echo $row['performance']; ?> kW</td>
                                            </tr>
                                            <tr>
                                                <?php if (!empty($row['fuel'])) {
                                                    echo '<tr>
                                                <td><i class="fas fa-oil-can"><span style="padding-left: 10px;">Üzemanyag:</span></i></td>
                                                <td>' . $row["fuel"] .  '</td>
                                            </tr>';
                                                }
                                                ?>

                                                <td><i class="fas fa-motorcycle"><span style="padding-left: 10px;">Kivitel:</span></i>
                                                </td>
                                                <td><?php echo $row['type']; ?></td>
                                            </tr>
                                            <?php if (!empty($row['enginetype'])) {
                                                echo '<tr>
                                                <td><i class="fas fa-dumbbell"><span style="padding-left: 10px;">Munkaütem:</span></i></td>
                                                <td>' . $row["enginetype"] .  '</td>
                                            </tr>';
                                            }
                                            ?>
                                            <tr>
                                                <td><i class="fas fa-id-card text-md-left"><span style="padding-left: 10px;">Jogosítvány típusa:</span></i></td>
                                                <td><?php echo $row['license']; ?></td>
                                            </tr>

                                            <tr>
                                                <th style="padding-top: 50px;">Okmányok</th>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td><i class="fas fa-paste"><span style="padding-left: 10px;">Okmányok
                                                            jellege:</span></i></td>
                                                <td><?php echo $row['documents']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-file"><span style="padding-left: 10px;">Okmányok
                                                            érvényessége:</span></i></td>
                                                <td><?php echo $row['documentsvalidity']; ?></td>
                                            </tr>

                                            <?php if (!empty($row['motyear'])) {
                                                echo '<tr>
                                                <td><i class="fas fa-calendar-check"><span style="padding-left: 10px;">Műszaki vizsga
                                                érvényes:</span></i></td>
                                                <td>' . $row['motyear'] . '/' . $row['motmonth'] . '</td>
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
                echo '<div class="text-center" id="no-advertisement">Nem található hirdetés.</div>';
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
                        <a class="page-link" href="index.php?page=1" aria-label="Előző" data-toggle="tooltip" title="Első oldal">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Előző</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="index.php?page=1" aria-label="Első oldal" data-toggle="tooltip" title="Első oldal">
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
                        echo '<li class="page-item"><a class="page-link' . $disabled . '" href="index.php?page=' .  $x . '">' . $x . '</a></li>';
                    }
                }


                if ($number_of_pages > $page) {
                ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Utolsó oldal</span>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="page-item">
                        <a class="page-link btn disabled" href="index.php?page=<?php echo $number_of_pages ?>" aria-label="Utolsó oldal" data-toggle="tooltip" title="Utolsó oldal">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Utolsó oldal</span>
                        </a>
                    </li>
                <?php
                }
                ?>

            </ul>
        </nav>


        <!-- Footer -->
        <div class="footer-container">
            <footer class="page-footer font-small unique-color-dark">
                <div class="upper-footer">
                    <div class="media">
                        <div class="container">

                            <!-- Grid row-->
                            <div class="row py-4 d-flex align-items-center">

                                <!-- Grid column -->
                                <div class="col-md-6 col-lg-5 text-center text-md-left mb-4 mb-md-0">
                                    <h6 class="mb-0">Kövessen minket a közösségi médiákon is</h6>
                                </div>
                                <!-- Grid column -->

                                <!-- Grid column -->
                                <div class="col-md-6 col-lg-7 text-center text-md-right">

                                    <!-- Facebook -->
                                    <a class="fb-ic">
                                        <i class="fab fa-facebook-f white-text mr-4"> </i>
                                    </a>
                                    <!-- Twitter -->
                                    <a class="tw-ic">
                                        <i class="fab fa-twitter white-text mr-4"> </i>
                                    </a>
                                    <!-- Google +-->
                                    <a class="gplus-ic">
                                        <i class="fab fa-google-plus-g white-text mr-4"> </i>
                                    </a>
                                    <!--Linkedin -->
                                    <a class="li-ic">
                                        <i class="fab fa-linkedin-in white-text mr-4"> </i>
                                    </a>
                                    <!--Instagram-->
                                    <a class="ins-ic">
                                        <i class="fab fa-instagram white-text"> </i>
                                    </a>

                                    <!-- Grid column -->

                                </div>
                            </div>
                            <!-- Grid row-->
                        </div>
                    </div>
                </div>



                <!-- Footer Links -->
                <div class="footer-linkek">
                    <div class="container text-center text-md-left mt-5">
                        <div class="row mt-3">
                            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">

                                <h6 class="text-uppercase font-weight-bold">Hasznos linkek</h6>
                                <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 140px;">
                                <p>
                                    <a href="https://nemzetiutdij.hu/files/img/articles/295/1.%20sz._mell%C3%A9klet_Kib%C5%91v%C3%ADtett_ad%C3%A1s-v%C3%A9teli_minta.pdf">Adásvételi szerződés letöltése</a>
                                </p>
                                <p>
                                    <a href="https://motorjogositvany.com/kategoria" target="_blank">Jogosítvány típusok</a>
                                </p>
                            </div>

                            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                                <h6 class="text-uppercase font-weight-bold">Kapcsolat</h6>
                                <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                                <p>
                                    <i class="fas fa-home mr-3"></i>2152, XY, Alma utca 4/B
                                </p>
                                <p>
                                    <i class="fas fa-envelope mr-3"></i> valami@example.com
                                </p>
                                <p>
                                    <i class="fas fa-phone mr-3"></i> + 36 30 425 525
                                </p>
                                <p>
                                    <i class="fas fa-print mr-3"></i> + 36 20 245 89
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-copyright text-center py-3">
                    Copyright &copy;
                    <script>
                        document.write(new Date().getFullYear())
                    </script>
                    <a href="index.php"> motorsarok.hu</a>
                </div>
            </footer>
        </div>
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
    <script src="assets/JS/motorcycles-copy-link.js"></script>
    <!-- Show modal from link -->
    <script src="assets/JS/modal-display.js"></script>
    <!-- Show modal link in search bar -->
    <script src="assets/JS/modal-link.js"></script>
    <!-- Hide modal link from search bar -->
    <script src="assets/JS/hide-modal-link.js"></script>

    <!-- AJAX Garage -->
    <script>
        //From card to garage
        $(document).ready(function() {
            //form when submit
            $("form[id*=upload-to-garage]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "upload-to-garage.php",
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
                    url: "upload-to-garage.php",
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