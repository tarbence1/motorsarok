<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

$name_error = $email_error = $message_error = $message_success = "";

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST["recaptcha_response"])) {

    // Create POST request to send to Google
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_secret = "6LcyIbMaAAAAAJmg94kSxlowWY_JZ-1e1505aw5z";
    $recaptcha_response = $_POST["recaptcha_response"];

    // Send POST request
    $recaptcha = file_get_contents($recaptcha_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);

    // Decode response
    $recaptcha = json_decode($recaptcha);

    // Check if verified
    if ($recaptcha->success && $recaptcha->score >= 0.5) {

        if (empty($_POST['name'])) {
            $message_error = "Kérjük adja meg a nevét!";
        } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $message_error = "Kérjük adja meg a valódi Email címét!";
        } elseif (empty($_POST['message'])) {
            $message_error = "Kérjük adja meg üzenetét!";
        } else {
            $to = "tarbence1@gmail.com";
            $from = $_POST['email'];
            $name = $_POST['name'];
            $msg = $_POST['message'];
            $subject = "motorsarok.hu - Új üzenet";
            $message = "<html>
                            <head>
                            <title>Kedves admin!</title>
                            </head>
                            <body>
                            <p>Új E-mail érkezett a motorsarok.hu weboldalról.</p>
                            <p>Részletek:</p>
                            <p>Név: " . $name . "</p>
                            <p>Email: " . $from . "</p>
                            <p>Üzenet: " . $msg . "</p>
                            </body>
                            </html>";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <motor@sarok.hu>' . "\r\n";
            if (mail($to, $subject, $message, $headers)) {
                $message_success = "Köszönjük üzenetét. Hamarosan felvesszük Önnel a kapcsolatot!";
            } else {
                $message_error = "Valami hiba történt. Kérjük próbálja meg később!";
            }
        }
    } else {
        $message_error = "A Google reCAPTCHA hibát észlelt, kérjük próbálja meg később!";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Kapcsolat</title>

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
    <!-- reCAPTCHA JS -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LcyIbMaAAAAAMS8-tDa_NXYWljy7VawX6Hhlp34"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LcyIbMaAAAAAMS8-tDa_NXYWljy7VawX6Hhlp34', {
                action: 'submit'
            }).then(function(token) {
                let recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>
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
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container" id="contact-container">
            <div id="contact-text">
                <h3>Kapcsolat</h3>
                <h5>Bármilyen kérdés, probléma esetén, töltse ki az alábbi mezőket és hamarosan felvesszük Önnel a kapcsolatot.</h5>
            </div>
            
            <form action="" method="post" name="contact" id="contact-form">
                <div class="form-row" id="contact-group">
                    <div class="col">
                        <input class="form-control" id="name" name="name" type="text" required placeholder="Név">
                    </div>
                    <div class="col">
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Email cím">
                    </div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Üzenet"></textarea>
                </div>
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                <br>
                <div class="form-group">
                    <div class="col text-center">
                        <input type="submit" class="btn btn-primary" id="contact-button" name="submit" value="Küldés">
                    </div>
                </div>

            </form>


        </div>

        <?php
        if (!empty($message_success) && $message_success !== "") {
        ?>
            <script>
                alertify.success('<?php echo $message_success ?>');
            </script>
        <?php
        }
        if (!empty($message_error) && $message_error !== "") {
        ?>
            <script>
                alertify.error('<?php echo $message_error ?>');
            </script>
        <?php
        }
        include('footer.php');
        ?>
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