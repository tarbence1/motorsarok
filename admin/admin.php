<?php
// Start the session
session_start();
// Incude config file
require_once("../config.php");

// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    $_SESSION['url'] = './admin/admin.php';
    header("Location: ../bejelentkezes.php");
    exit;
} elseif ($_SESSION['is_admin'] !== 1) {
    header("Location: ../index.php");
    exit;
}



// Select users
$sql = "SELECT * FROM users ORDER BY id";
$users_result = $link->query($sql);

// Select manufacturers
$sql_manufacturers = "SELECT * FROM manufacturers ORDER BY id";
$manufacturers_result = $link->query($sql_manufacturers);

// Select counties
$counties_sql = "SELECT * FROM counties ORDER BY id";
$counties_result = $link->query($counties_sql);

// Select technical equipments
$technical_equipment_sql = "SELECT * FROM technical_equipment ORDER BY id";
$technical_equipment_result = $link->query($technical_equipment_sql);

// Select comfort equipments
$comfort_equipment_sql = "SELECT * FROM comfort_equipment ORDER BY id";
$comfort_equipment_result = $link->query($comfort_equipment_sql);

// Select safety equipments
$safety_equipment_sql = "SELECT * FROM safety_equipment ORDER BY id";
$safety_equipment_result = $link->query($safety_equipment_sql);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Admin panel</title>

    <link rel="icon" href="../images/logo.png" type="image/gif" sizes="16x16">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Own CSS -->
    <link rel="stylesheet" href="../assets/CSS/style.css">
    <!-- Scrollbar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Alertify CSS -->
    <link rel="stylesheet" href="../assets/CSS/alertify.css">
    <link rel="stylesheet" href="../assets/CSS/default.min.css">
    <!-- Alertify JS -->
    <script src="../assets/JS/alertify.js"></script>
</head>

<body>

    <button onclick="topFunction()" id="go-to-top"><i class="fas fa-arrow-up"></i></button>

    <!-- Page Content  -->
    <div id="content">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light" id="main-navbar">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin.php">
                    <div class="nav-title">adm<span style="color: #ee4a4a">i</span>n p<span style="color: #ee4a4a">a</span>nel</div>
                </a>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-align-justify"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav nav-justified w-100 text-center">
                        <li class="nav-item">
                            <a href="../index.php" class="nav-link d-flex flex-column">
                                <i class="fas fa-home fa-lg"></i>
                                <span class="d-sm-inline mt-1">főoldal</span>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a href="admin.php" class="nav-link d-flex flex-column">
                                <i class="fas fa-user-cog fa-lg"></i>
                                <span class="d-sm-inline mt-1">általános</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-center d-flex flex-column" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-folder-open fa-lg"></i>
                                <span class="d-sm-inline px-1 mt-1">hirdetések</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="motorkerekpar-hirdetesek.php">Motorkerékpár</a>
                                <a class="dropdown-item" href="alkatresz-hirdetesek.php">Alkatrész</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container" id="welcome-text">
            <h3>Admin panel</h3>
            <hr>
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Felhasználók
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div style="overflow-x:auto;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Felhasználónév</th>
                                            <th scope="col">Prémium</th>
                                            <th scope="col">Prémium kezdete</th>
                                            <th scope="col">Prémium vége</th>
                                            <th scope="col">Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $is_admin = '';
                                        // Display users data
                                        if ($users_result->num_rows > 0) {
                                            while ($row = $users_result->fetch_assoc()) {
                                                // Check premium levels
                                                $level = $row['premium'];
                                                $levelstr = '';
                                                switch ($level) {
                                                    case 1:
                                                        $levelstr = 'Bronz';
                                                        break;
                                                    case 2:
                                                        $levelstr = 'Ezüst';
                                                        break;
                                                    case 3:
                                                        $levelstr = 'Arany';
                                                        break;
                                                    default:
                                                        $levelstr = 'Nincs';
                                                }

                                                //Check if admin
                                                $is_admin = ($row['is_admin'] == 1) ? 'Igen' : 'Nem';

                                                // Check if premium start is null
                                                $premiumstart = ($row['premiumstart'] === NULL) ? '-' : $row['premiumstart'];


                                                // Check if premium expiration is null
                                                $premiumexp = ($row['premiumexpiration'] === NULL) ? '-' : $row['premiumexpiration'];

                                        ?>
                                                <tr class="clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#userModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#userModal-<?php echo $row['id']; ?>">
                                                    <th scope="row"><?php echo $row['id']; ?></th>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><span id="tablePremiumStatus-<?php echo $row['id']; ?>"><?php echo $levelstr; ?></span></td>
                                                    <td><span id="tablePremiumStart-<?php echo $row['id']; ?>"><?php echo $premiumstart; ?></span></td>
                                                    <td><span id="tablePremiumEnd-<?php echo $row['id']; ?>"><?php echo $premiumexp; ?></span></td>
                                                    <td><span id="tableAdminStatus-<?php echo $row['id']; ?>"><?php echo $is_admin; ?></span></td>
                                                </tr>

                                                <!-- User modal -->
                                                <form action="" id="userData<?php echo $row['id']; ?>" method="POST">
                                                    <div class="modal fade" id="userModal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="userModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="userModalLabel-<?php echo $row['id']; ?>"><?php echo $row['username']; ?> felhasználó</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <label for="adminSelect">Admin - <span id="currentAdminStatus-<?php echo $row['id']; ?>"><?php echo $is_admin ?></span></label>
                                                                    <select class="form-control form-control-sm adminSelect" id="adminSelect-<?php echo $row['id']; ?>" name="adminSelect">
                                                                        <option value="">Admin</option>
                                                                        <option value="1">Igen</option>
                                                                        <option value="0">Nem</option>
                                                                    </select>
                                                                    <label for="premiumSelect">Prémium - <span id="currentPremiumStatus-<?php echo $row['id']; ?>"><?php echo $levelstr ?></span></label>
                                                                    <select class="form-control form-control-sm" id="premiumSelect-<?php echo $row['id']; ?>" name="premiumSelect">
                                                                        <option value="">Prémium</option>
                                                                        <option value="1">Bronz</option>
                                                                        <option value="2">Ezüst</option>
                                                                        <option value="3">Arany</option>
                                                                    </select>

                                                                </div>
                                                                <input type="hidden" name="userID" value="<?php echo $row['id']; ?>" />
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="user-submit" class="btn btn-primary">Mentés</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                        <?php
                                            }
                                        } else {
                                            echo 'Nincs elérhető felhasználó!';
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Gyártók
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div style="overflow-x:auto;">
                                <table class="table table-hover">
                                    <thead>
                                        <button type="button" class="btn btn-primary" id="addManufacturer" data-toggle="modal" data-target="#addManufacturerModal">
                                            Új gyártó hozzáadása
                                        </button>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Név</th>
                                            <th scope="col">Művelet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Display manufacturers
                                        if ($manufacturers_result->num_rows > 0) {
                                            while ($row = $manufacturers_result->fetch_assoc()) {
                                        ?>
                                                <form action="" id="manufacturerDelete<?php echo $row['id']; ?>" method="POST">
                                                    <tr id="manufacturers-row-<?php echo $row['id']; ?>">
                                                        <th scope="row" class="manufacturers-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#manufacturerModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#manufacturerModal-<?php echo $row['id']; ?>"><?php echo $row['id']; ?></th>
                                                        <td class="manufacturers-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#manufacturerModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#manufacturerModal-<?php echo $row['id']; ?>"><span id="tableManufacturerName-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></span></td>
                                                        <input type="hidden" name="hiddenManufacturerName" value="<?php echo $row['name']; ?>">
                                                        <input type="hidden" name="hiddenManufacturerID" value="<?php echo $row['id']; ?>">
                                                        <td><button type="submit" name="manufacturer-delete" class="btn btn-link">Törlés</button></td>
                                                    </tr>
                                                </form>

                                                <!-- Manufacturer modal -->
                                                <form action="" id="manufacturerData<?php echo $row['id']; ?>" method="POST">
                                                    <div class="modal fade" id="manufacturerModal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="manufacturerModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="manufacturerModalLabel-<?php echo $row['id']; ?>">Gyártó módosítása: <span id="manufacturerModalTitle-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></span></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input class="form-control form-control-sm" type="text" name="manufacturerNewName" placeholder="Új név">
                                                                </div>
                                                                <input type="hidden" name="manufacturerID" value="<?php echo $row['id']; ?>" />
                                                                <input type="hidden" name="currentManufacturerName" id="currentManufacturerName-<?php echo $row['id']; ?>" value="<?php echo $row['name']; ?>" />
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="manufacturer-submit" class="btn btn-primary">Mentés</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                        <?php
                                            }
                                        } else {
                                            echo 'Nincs elérhető gyártó!';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Megyék
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Név</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Display counties
                                    if ($counties_result->num_rows > 0) {
                                        while ($row = $counties_result->fetch_assoc()) {
                                    ?>
                                            <tr class="counties-clickable-row" id="countiesclickable-<?php echo $row['id']; ?>" data-href='#countyModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#countyModal-<?php echo $row['id']; ?>">
                                                <th scope="row"><?php echo $row['id']; ?></th>
                                                <td><span id="tableCountyName-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></span></td>

                                            </tr>

                                            <!-- County modal -->
                                            <form action="" id="countyData<?php echo $row['id']; ?>" method="POST">
                                                <div class="modal fade" id="countyModal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="countyModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="countyModalLabel-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input class="form-control form-control-sm" type="text" name="countyNewName" placeholder="Új név">
                                                            </div>
                                                            <input type="hidden" name="countyID" value="<?php echo $row['id']; ?>" />
                                                            <input type="hidden" name="currentCountyName" id="currentCountyName-<?php echo $row['id']; ?>" value="<?php echo $row['name']; ?>" />
                                                            <div class="modal-footer">
                                                                <button type="submit" name="county-submit" class="btn btn-primary">Mentés</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                    <?php
                                        }
                                    } else {
                                        echo 'Nincs elérhető megye!';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Műszaki felszereltség
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <button type="button" class="btn btn-primary" id="addTechnical" data-toggle="modal" data-target="#addTechnicalModal">
                                        Új felszereltség hozzáadása
                                    </button>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Név</th>
                                        <th scope="col">Művelet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Display technical equipments
                                    if ($technical_equipment_result->num_rows > 0) {
                                        while ($row = $technical_equipment_result->fetch_assoc()) {
                                    ?>
                                            <form action="" id="technicalDelete<?php echo $row['id']; ?>" method="POST">
                                                <tr id="technical-row-<?php echo $row['id']; ?>">
                                                    <th scope="row" class="technical-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#technicalModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#technicalModal-<?php echo $row['id']; ?>"><?php echo $row['id']; ?></th>
                                                    <td class="technical-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#technicalModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#technicalModal-<?php echo $row['id']; ?>"><span id="tableTechnicalName-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></span></td>
                                                    <input type="hidden" name="hiddenTechnicalID" value="<?php echo $row['id']; ?>">
                                                    <td><button type="submit" name="technical-delete" class="btn btn-link">Törlés</button></td>
                                                </tr>
                                            </form>

                                            </tr>

                                            <!-- Technical equipment modal -->
                                            <form action="" id="technicalData<?php echo $row['id']; ?>" method="POST">
                                                <div class="modal fade" id="technicalModal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="technicalModallabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="technicalModallabel-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input class="form-control form-control-sm" type="text" name="technicalNewName" placeholder="Új név">
                                                            </div>
                                                            <input type="hidden" name="technicalID" value="<?php echo $row['id']; ?>" />
                                                            <input type="hidden" name="currentTechnicalName" id="currentTechnicalName-<?php echo $row['id']; ?>" value="<?php echo $row['name']; ?>" />
                                                            <div class="modal-footer">
                                                                <button type="submit" name="technical-submit" class="btn btn-primary">Mentés</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                    <?php
                                        }
                                    } else {
                                        echo 'Nincs elérhető műszaki felszereltség!';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Kényelmi felszereltség
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <button type="button" class="btn btn-primary" id="addComfort" data-toggle="modal" data-target="#addComfortModal">
                                        Új felszereltség hozzáadása
                                    </button>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Név</th>
                                        <th scope="col">Művelet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Display comfort equipments
                                    if ($comfort_equipment_result->num_rows > 0) {
                                        while ($row = $comfort_equipment_result->fetch_assoc()) {
                                    ?>
                                            <form action="" id="comfortDelete<?php echo $row['id']; ?>" method="POST">
                                                <tr id="comfort-row-<?php echo $row['id']; ?>">
                                                    <th scope="row" class="comfort-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#comfortModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#comfortModal-<?php echo $row['id']; ?>"><?php echo $row['id']; ?></th>
                                                    <td class="comfort-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#comfortModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#comfortModal-<?php echo $row['id']; ?>"><span id="tableComfortName-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></span></td>
                                                    <input type="hidden" name="hiddenComfortID" value="<?php echo $row['id']; ?>">
                                                    <td><button type="submit" name="comfort-delete" class="btn btn-link">Törlés</button></td>
                                                </tr>
                                            </form>

                                            </tr>

                                            <!-- Comfort equipment modal -->
                                            <form action="" id="comfortData<?php echo $row['id']; ?>" method="POST">
                                                <div class="modal fade" id="comfortModal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="comfortModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="comfortModalLabel-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input class="form-control form-control-sm" type="text" name="comfortNewName" placeholder="Új név">
                                                            </div>
                                                            <input type="hidden" name="comfortID" value="<?php echo $row['id']; ?>" />
                                                            <input type="hidden" name="currentComfortName" id="currentComfortName-<?php echo $row['id']; ?>" value="<?php echo $row['name']; ?>" />
                                                            <div class="modal-footer">
                                                                <button type="submit" name="comfort-submit" class="btn btn-primary">Mentés</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                    <?php
                                        }
                                    } else {
                                        echo 'Nincs elérhető kényelmi felszereltség!';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingSix">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Biztonsági felszereltség
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <button type="button" class="btn btn-primary" id="addSafety" data-toggle="modal" data-target="#addSafetyModal">
                                        Új felszereltség hozzáadása
                                    </button>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Név</th>
                                        <th scope="col">Művelet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Display safety equipments
                                    if ($safety_equipment_result->num_rows > 0) {
                                        while ($row = $safety_equipment_result->fetch_assoc()) {
                                    ?>
                                            <form action="" id="safetyDelete<?php echo $row['id']; ?>" method="POST">
                                                <tr id="safety-row-<?php echo $row['id']; ?>">
                                                    <th scope="row" class="safety-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#safetyModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#safetyModal-<?php echo $row['id']; ?>"><?php echo $row['id']; ?></th>
                                                    <td class="safety-clickable-row" id="clickable-<?php echo $row['id']; ?>" data-href='#safetyModal-<?php echo $row['id']; ?>' data-toggle="modal" data-target="#safetyModal-<?php echo $row['id']; ?>"><span id="tableSafetyName-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></span></td>
                                                    <input type="hidden" name="hiddenSafetyID" value="<?php echo $row['id']; ?>">
                                                    <td><button type="submit" name="safety-delete" class="btn btn-link">Törlés</button></td>
                                                </tr>
                                            </form>

                                            </tr>

                                            <!-- Safety equipment modal -->
                                            <form action="" id="safetyData<?php echo $row['id']; ?>" method="POST">
                                                <div class="modal fade" id="safetyModal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="safetyModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="safetyModalLabel-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input class="form-control form-control-sm" type="text" name="safetyNewName" placeholder="Új név">
                                                            </div>
                                                            <input type="hidden" name="safetyID" value="<?php echo $row['id']; ?>" />
                                                            <input type="hidden" name="currentSafetyName" id="currentSafetyName-<?php echo $row['id']; ?>" value="<?php echo $row['name']; ?>" />
                                                            <div class="modal-footer">
                                                                <button type="submit" name="safety-submit" class="btn btn-primary">Mentés</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                    <?php
                                        }
                                    } else {
                                        echo 'Nincs elérhető kényelmi felszereltség!';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Manufacturer Modal -->
        <form action="" id="addManufacturerData" method="POST">
            <div class="modal fade" id="addManufacturerModal" tabindex="-1" role="dialog" aria-labelledby="addManufacturerModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addManufacturerModalLabel">Új gyártó hozzáadása</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input class="form-control form-control-sm" type="text" name="manufacturerName" placeholder="Gyártó neve">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="new-manufacturer-submit" class="btn btn-primary">Mentés</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add Technical Modal -->
        <form action="" id="addTechnicalData" method="POST">
            <div class="modal fade" id="addTechnicalModal" tabindex="-1" role="dialog" aria-labelledby="addTechnicalModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTechnicalModalLabel">Új műszaki felszereltség hozzáadása</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input class="form-control form-control-sm" type="text" name="technicalName" placeholder="Műszaki felszereltség megnevezése">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="new-technical-submit" class="btn btn-primary">Mentés</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add Comfort Modal -->
        <form action="" id="addComfortData" method="POST">
            <div class="modal fade" id="addComfortModal" tabindex="-1" role="dialog" aria-labelledby="addComfortModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addComfortModalLabel">Új kényelmi felszereltség hozzáadása</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input class="form-control form-control-sm" type="text" name="comfortName" placeholder="Kényelmi felszereltség megnevezése">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="new-comfort-submit" class="btn btn-primary">Mentés</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add Safety Modal -->
        <form action="" id="addSafetyData" method="POST">
            <div class="modal fade" id="addSafetyModal" tabindex="-1" role="dialog" aria-labelledby="addSafetyModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSafetyModalLabel">Új biztonsági felszereltség hozzáadása</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input class="form-control form-control-sm" type="text" name="safetyName" placeholder="Kényelmi felszereltség megnevezése">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="new-safety-submit" class="btn btn-primary">Mentés</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Bezárás</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <?php
        // Display the success message
        if (isset($_SESSION['manufacturerSuccess'])) {
        ?>
            <script>
                alertify.success('Sikeres hozzáadás!');
            </script>
        <?php
            unset($_SESSION['manufacturerSuccess']);
        }
        ?>


        <div class="footer-container">
            <footer class="page-footer font-small unique-color-dark">
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
                                <h6 class="text-uppercase font-weight-bold">Egyéb</h6>
                                <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                                <p>
                                    <a href="../kapcsolat.php">Kapcsolat</a>

                                </p>
                                <p>
                                    <a href="../documents/ÁSZF-minta.pdf">Általános szerződési feltételek</a>

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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scrollbar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- Jump to top -->
    <script src="../assets/JS/jump-to-top.js"></script>
    <!-- Tooltips -->
    <script src="../assets/JS/tooltips.js"></script>


    <!-- Upload user form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=userData]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "user-settings.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        alertify.success(response.message);
                        user = response.userid;
                        //Update modal status
                        $('#currentAdminStatus-' + user).html(response.admin);
                        $('#currentPremiumStatus-' + user).html(response.premium);
                        // Update table status
                        $('#tableAdminStatus-' + user).html(response.admin);
                        $('#tablePremiumStatus-' + user).html(response.premium);
                        $('#tablePremiumStart-' + user).html(response.start_date);
                        $('#tablePremiumEnd-' + user).html(response.end_date);

                    }
                });
            });
        });
    </script>

    <!-- Modify manufacturer form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=manufacturerData]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "manufacturer-settings.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        alertify.success(response.message);
                        manufacturer = response.manufacturerid;
                        //Update modal name
                        $('#manufacturerModalTitle-' + manufacturer).html(response.newName);
                        $('#tableManufacturerName-' + manufacturer).html(response.newName);
                        $('#currentManufacturerName-' + manufacturer).val(response.newName);
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg az új nevet!');
                    }
                });
            });
        });
    </script>

    <!-- Add manufacturer form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("#addManufacturerData").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "add-manufacturer.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        location.reload();
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg a gyártó nevét!');
                    }
                });
            });
        });
    </script>

    <!-- Delete manufacturer form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=manufacturerDelete]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "manufacturer-delete.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        manufacturer = response.manufacturerid;
                        $('#manufacturers-row-' + manufacturer).remove();
                        alertify.success('Sikeres törlés!');
                    },
                    error: function(data) {
                        alertify.error('A kiválasztott gyártóhoz tartoznak hirdetések, így nem lehet törölni!');
                    }
                });
            });
        });
    </script>

    <!-- Modify county form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=countyData]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "county-settings.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        alertify.success(response.message);
                        county = response.countyid;
                        //Update modal name
                        $('#countyModalLabel-' + county).html(response.newName);
                        $('#tableCountyName-' + county).html(response.newName);
                        $('#currentCountyName-' + county).val(response.newName);
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg az új nevet!');
                    }
                });
            });
        });
    </script>

    <!-- Modify technical equipment form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=technicalData]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "technical-settings.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        alertify.success(response.message);
                        technical = response.technicalid;
                        //Update modal name
                        $('#technicalModallabel-' + technical).html(response.newName);
                        $('#tableTechnicalName-' + technical).html(response.newName);
                        $('#currentTechnicalName-' + technical).val(response.newName);
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg az új nevet!');
                    }
                });
            });
        });
    </script>

    <!-- Add technical equipment form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("#addTechnicalData").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "add-technical.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        location.reload();
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg a felszereltség nevét!');
                    }
                });
            });
        });
    </script>

    <!-- Delete technical form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=technicalDelete]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "technical-delete.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        technical = response.technicalid;
                        $('#technical-row-' + technical).remove();
                        alertify.success('Sikeres törlés!');
                    },
                    error: function(data) {
                        alertify.error('A kiválasztott felszereltséghez tartoznak hirdetések, így nem lehet törölni!');
                    }
                });
            });
        });
    </script>

    <!-- Modify comfort equipment form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=comfortData]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "comfort-settings.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        alertify.success(response.message);
                        comfort = response.comfortid;
                        //Update modal name
                        $('#comfortModalLabel-' + comfort).html(response.newName);
                        $('#tableComfortName-' + comfort).html(response.newName);
                        $('#currentComfortName-' + comfort).val(response.newName);
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg az új nevet!');
                    }
                });
            });
        });
    </script>

    <!-- Add comfort equipment form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("#addComfortData").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "add-comfort.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        location.reload();
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg a felszereltség nevét!');
                    }
                });
            });
        });
    </script>

    <!-- Delete comfort form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=comfortDelete]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "comfort-delete.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        comfort = response.comfortid;
                        $('#comfort-row-' + comfort).remove();
                        alertify.success('Sikeres törlés!');
                    },
                    error: function(data) {
                        alertify.error('A kiválasztott felszereltséghez tartoznak hirdetések, így nem lehet törölni!');
                    }
                });
            });
        });
    </script>

    <!-- Modify safety equipment form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=safetyData]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "safety-settings.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        alertify.success(response.message);
                        safety = response.safetyid;
                        //Update modal name
                        $('#safetyModalLabel-' + safety).html(response.newName);
                        $('#tableSafetyName-' + safety).html(response.newName);
                        $('#currentSafetyName-' + safety).val(response.newName);
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg az új nevet!');
                    }
                });
            });
        });
    </script>

    <!-- Add safety equipment form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("#addSafetyData").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "add-safety.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        location.reload();
                    },
                    error: function(data) {
                        alertify.error('Kérjük adja meg a felszereltség nevét!');
                    }
                });
            });
        });
    </script>

    <!-- Delete safety form -->
    <script>
        $(document).ready(function() {
            //form when submit
            $("form[id*=safetyDelete]").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "safety-delete.php",
                    method: "post",
                    data: $(this).serialize(),
                    dataType: "text",
                    success: function(message) {
                        var response = JSON.parse(message);
                        safety = response.safetyid;
                        $('#safety-row-' + safety).remove();
                        alertify.success('Sikeres törlés!');
                    },
                    error: function(data) {
                        alertify.error('A kiválasztott felszereltséghez tartoznak hirdetések, így nem lehet törölni!');
                    }
                });
            });
        });
    </script>


</body>

</html>