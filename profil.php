<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION['url'] = 'profil.php';
    header("location: bejelentkezes.php");
    exit;
}

// Set the user id
$username = $_SESSION["id"];

// Select user data
$stmt = $link->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $username);
$stmt->execute();
$result = $stmt->get_result();
$record = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($link);

if (!empty($record['avatar'])) {
    $avatar = $record['avatar'];
} else {
    $avatar = 'images/no-avatar.png';
}



// Select premium level for the user
$level = $record['premium'];
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

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Profil</title>

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
                        <li class="nav-item active">
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
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container" id="welcome-text">
            <h3>Üdvözöljük az oldalon, <b><?php echo htmlspecialchars($record['username']); ?></b>!</h3>

            <div class="image-upload">
                <label for="file">
                    <img src="<?php echo htmlspecialchars($avatar) ?>" alt="Avatar" class="avatar" id="avatar-image" data-toggle="tooltip" title="Módosításhoz kattintson a képre" />
                </label>
                <input id="file" type="file" name="file" />
            </div>

            <a href="logout.php" class="btn btn-danger" id="logout" role="button">Kijelentkezés</a>
            <h5>Jelenlegi prémium csomag: <span id="premium"><?php echo htmlspecialchars($levelstr); ?></span></h5>
            <h5>Lejárati dátuma: <span id="premiumexpiration"><?php echo is_null($record['premiumexpiration']) ? '-' : htmlspecialchars($record['premiumexpiration']); ?></span></h5>
            <hr>
            <h5>Email cím: <span id="email-address"><?php echo htmlspecialchars($record['email']); ?></span></h5>
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Prémium csomag vásárlása
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="container">
                                <div class="card-deck mb-3 text-center">
                                    <div class="card mb-4 box-shadow" id="bronz">
                                        <div class="card-header">
                                            <h4 class="my-0 font-weight-normal">Bronz</h4>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h1 class="card-title pricing-card-title">1000 <small class="text-muted">Ft</small></h1>
                                            <ul class="list-unstyled mt-3 mb-4">
                                                <li>5 db motorkerékpár hirdetés feladás</li>
                                                <li>5 db alkatrész hirdetés feladás</li>
                                                <li>7 db kép minden hirdetéshez</li>
                                                <li>1 hónap hirdetés kiemelés bronz kerettel</li>
                                            </ul>
                                            <div id="paypal-payment-button-1000" class="mt-auto"></div>

                                        </div>
                                    </div>
                                    <div class="card mb-4 box-shadow" id="silver">
                                        <div class="card-header">
                                            <h4 class="my-0 font-weight-normal">Ezüst</h4>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h1 class="card-title pricing-card-title">2000 <small class="text-muted">Ft</small></h1>
                                            <ul class="list-unstyled mt-3 mb-4">
                                                <li>10 db motorkerékpár hirdetés feladás</li>
                                                <li>10 db alkatrész hirdetés feladás</li>
                                                <li>10 db kép minden hirdetéshez</li>
                                                <li>1 hónap hirdetés kiemelés ezüst kerettel</li>
                                            </ul>
                                            <div id="paypal-payment-button-2000" class="mt-auto"></div>
                                        </div>
                                    </div>
                                    <div class="card mb-4 box-shadow" id="gold">
                                        <div class="card-header">
                                            <h4 class="my-0 font-weight-normal">Arany</h4>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h1 class="card-title pricing-card-title">3000 <small class="text-muted">Ft</small></h1>
                                            <ul class="list-unstyled mt-3 mb-4">
                                                <li>Korlátlan motorkerékpár hirdetés feladás</li>
                                                <li>Korlátlan alkatrész hirdetés feladás</li>
                                                <li>12 db kép minden hirdetéshez</li>
                                                <li>1 hónap hirdetés kiemelés arany kerettel</li>
                                            </ul>
                                            <div id="paypal-payment-button-3000" class="mt-auto"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Jelszó módosítása
                            </button>
                        </h5>
                    </div>
                    <form action="" method="post" id="reset-password">
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body" id="password-change">
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="register-password"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" name="old_password" class="form-control input_pass" id="password-field" value="" placeholder="Régi jelszó">
                                    <div class="input-group-append" data-toggle="tooltip" data-placement="bottom" title="Jelszó mutatása">
                                        <span toggle="#password-field" class="fas fa-lock field-icon toggle-password input-group-text" id="show-password"></span>
                                    </div>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="register-password2"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" name="new_password" class="form-control input_pass" id="password-field2" value="" placeholder="Új jelszó">
                                    <div class="input-group-append" data-toggle="tooltip" data-placement="bottom" title="Jelszó mutatása">
                                        <span toggle="#password-field2" class="fas fa-lock field-icon toggle-password input-group-text" id="show-password2"></span>
                                    </div>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="register-password2"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" name="password_again" class="form-control input_pass" id="password-field3" value="" placeholder="Új jelszó megerősítése">
                                    <div class="input-group-append" data-toggle="tooltip" data-placement="bottom" title="Jelszó mutatása">
                                        <span toggle="#password-field3" class="fas fa-lock field-icon toggle-password input-group-text" id="show-password3"></span>
                                    </div>
                                </div>
                                <button class="btn btn-danger" id="password-change-button">Mentés</button>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Email cím módosítása
                            </button>
                        </h5>
                    </div>
                    <form action="" method="post" id="reset-email">
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body" id="email-change">
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="change-email"><i class="fas fa-at"></i></span>
                                    </div>
                                    <input type="email" name="new_email" class="form-control input_user" value="" placeholder="Új Email cím">
                                </div>
                                <button type="submit" class="btn btn-danger" id="email-change-button">Mentés</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scrollbar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- Paypal -->
    <script src="https://www.paypal.com/sdk/js?client-id=AQg51VY3fe1_veelCUoeVQA-SV8Mnwx84QG_4IC6xuttmCRdrGpXEmEvKBYhvuR7v0AMhjRK3MdlzDyS&disable-funding=credit,card&currency=HUF"></script>
    <!-- Jump to top -->
    <script src="assets/JS/jump-to-top.js"></script>
    <!-- Tooltips -->
    <script src="assets/JS/tooltips.js"></script>
    <!-- Show password -->
    <script src="assets/JS/show-password.js"></script>
    <!-- Avatar upload -->
    <script src="assets/JS/avatar-upload.js"></script>
    <!-- Paypal buttons -->
    <script src="assets/JS/paypal-buttons.js"></script>
    <!-- Profile Settings -->
    <script src="assets/JS/profile-settings.js"></script>

</body>

</html>