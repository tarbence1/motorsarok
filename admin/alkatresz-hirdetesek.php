<?php
// Start the session
session_start();
// Incude config file
require_once("../config.php");

// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    $_SESSION['url'] = './admin/alkatresz-hirdetesek.php';
    header("Location: ../bejelentkezes.php");
    exit;
} elseif ($_SESSION['is_admin'] !== 1) {
    header("Location: ../index.php");
    exit;
}

// Update status if delete button is pressed
if (isset($_POST['delete'])) {
    $advertId = $_POST['advert-id'];
    $change_status = 0;
    $stmt = $link->prepare("UPDATE parts SET status=? WHERE id = ?");
    $stmt->bind_param('ii', $change_status, $advertId);
    $stmt->execute();
    $stmt->close();
    $link->close();
}

// Select parts table
$sql = "SELECT * FROM parts WHERE status = ?";
$status = 1;
$statement = $link->prepare($sql);
$statement->bind_param("i", $status);
$statement->execute();
$result = $statement->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Hirdetéseim</title>

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
                        <li class="nav-item">
                            <a href="admin.php" class="nav-link d-flex flex-column">
                                <i class="fas fa-user-cog fa-lg"></i>
                                <span class="d-sm-inline mt-1">általános</span>
                            </a>
                        </li>
                        <li class="nav-item active dropdown">
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

        <!-- Cards -->
        <div class="row" id="main-cards-garage">

            <?php
            if (empty($data)) {
                echo '<div class="text-center" id="no-advertisement">Nem található alkatrész hirdetés a rendszerben.</div>';
            } else {


                foreach ($data as $row) {

                    // Select images
                    $sql4 = "SELECT * FROM parts_images WHERE productid = ? ORDER BY is_mainimage DESC";
                    $stmt7 = $link->prepare($sql4);
                    $stmt7->bind_param("i", $productid);
                    $productid = $row['id'];
                    $stmt7->execute();
                    $result3 = $stmt7->get_result();
                    $data3 = $result3->fetch_all(MYSQLI_ASSOC);

            ?>

                    <a href="#" data-toggle="modal" data-target="#modal-<?php echo $row['id']; ?>">
                        <div class="card" id="motor-cards">
                            <?php
                            // Walk around the image array
                            $i = 0;
                            foreach ($data3 as $main) {
                                $admin_mainimage = '../' . $main['thumbnailimage'];
                                // Display the primary image if exists
                                if ($i == 0) {
                                    $mainimage = (file_exists($admin_mainimage)) ? $admin_mainimage : '../images/no-part-image.png';
                                }
                                $i++;
                            }

                            echo "<img src='" . $mainimage . "' class='card-img-top' alt='Main Image'/>";



                            ?>
                            <div class="card-body">
                                <div class="short-data">
                                    <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['price']; ?> Ft</h6>
                                </div>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-<?php echo $row['id']; ?>">Részletek</a>
                                <hr>
                                <form action="" method="POST" onsubmit="return confirm('Biztosan törölni szeretné?');">
                                    <div class="float-right">
                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="advert-id">
                                        <a href="alkatresz-hirdetes-modositas.php?id=<?php echo $row['id']; ?>" class="btn btn-warning" role="button">Módosítás</a>
                                        <input type='submit' value="Törlés" name="delete" id="btn-delete" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </a>

                    <!-- Details Modal -->

                    <div class="modal full" id="modal-<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
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
                                        foreach ($data3 as $rec) {
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
                                        foreach ($data3 as $rec) {
                                            $admin_thumbnailimage = '../' . $rec['thumbnailimage'];
                                            $actives = '';
                                            if ($i == 0) {
                                                $actives = 'active';
                                            }

                                            $image = (file_exists($admin_thumbnailimage)) ? $admin_thumbnailimage : '../images/no-part-image.png';

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
        // Display the success new ad message
        if (isset($_SESSION['successad'])) {
        ?>
            <script>
                alertify.success('Sikeres hirdetésfeladás! Ezen az oldalon megtekintheti hirdetését.');
            </script>
        <?php
            unset($_SESSION['successad']);
        }

        // Display the success modify message
        if (isset($_SESSION['success-modify'])) {
        ?>
            <script>
                alertify.success('Hirdetése sikeresen módosítva lett!');
            </script>
        <?php
            unset($_SESSION['success-modify']);
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
    <!-- Jump to top -->
    <script src="../assets/JS/jump-to-top.js"></script>
    <!-- Tooltips -->
    <script src="../assets/JS/tooltips.js"></script>


</body>

</html>