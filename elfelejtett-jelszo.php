<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

// Redirect the user if already logged in
if (isset($_SESSION["loggedin"])) {
    header("Location: index.php");
    exit;
}

// Define variables and initialize with empty values
$email = $email_err = $success = $token = "";
$error = 1;


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Kérem adja meg az Email címét.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate credentials
    if (empty($email_err)) {

        $stmt = $link->prepare("SELECT id, email FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            $error = 0;
            $to = $result['email'];
            $id = $result['id'];

            $sql = "UPDATE users SET password = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $token = substr(str_shuffle($permitted_chars), 0, 10);
                $param_password = password_hash($token, PASSWORD_DEFAULT);
                $param_id = $id;
            }
            if (mysqli_stmt_execute($stmt)) {
                $success = "Egy Emailt küldtünk a megadott E-mail címre. A benne található linkre kattintva új jelszót tud beállítani.";
            }
        } else {
            $error = 1;
            $email_err = "Érvénytelen Email cím.";
        }

        $to;
        $subject = "Jelszo emlekezteto";
        $message = "
                    <html>
                    <head>
                    <title>Kedves Felhasználó!</title>
                    </head>
                    <body>
                    <p>Ezt az E-mailt azért küldtük, mert az Ön E-mail címére valaki jelszó emlékeztetőt kért.</p>
                    <p>Amennyiben nem Ön kedvezményezte, kérjük hagyja figyelmen kívül levelünket.</p>
                    <p>Új ideiglenes jelszava:</p>
                    <p>" . $token . "</p>
                    <p>Bejelentkezés után kérjük változtassa meg a profil menüpont alatt.</p>
                    </body>
                    </html>";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <motor@sarok.hu>' . "\r\n";

        if ($error == 0) {
            mail($to, $subject, $message, $headers);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Bejelentkezés</title>

    <link rel="icon" href="images/logo.png" type="image/gif" sizes="16x16">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Own CSS -->
    <link rel="stylesheet" href="assets/CSS/style.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Alertify CSS -->
    <link rel="stylesheet" href="assets/CSS/alertify.css">
    <link rel="stylesheet" href="assets/CSS/default.min.css">
    <!-- Alertify JS -->
    <script src="assets/JS/alertify.js"></script>

</head>

<body>

    <!-- Page Content  -->
    <div id="content">

        <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">

            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <div class="nav-title">motor<span style="color: #ee4a4a">s</span>arok<span style="color: #ee4a4a">.</span>hu</div>
                </a>

                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-align-justify"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Főoldal</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="d-flex justify-content-center" style="padding-bottom: 100px;">
            <div class="login-panel">
                <div class="d-flex justify-content-center">
                    <div class="logo_container">
                        <img src="images/logo.png" class="logo" alt="Logo">
                    </div>
                </div>
                <p class="forgot-password-text text-center">
                    Kérjük adja meg Email címét, amelyel regisztrált az oldalra.
                </p>
                <div class="d-flex justify-content-center form_container">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input-group mb-3" id="forgot-pass-email">
                            <div class="input-group-append">
                                <span class="input-group-text" id="login-email"><i class="fas fa-at"></i></span>
                            </div>
                            <input type="email" name="email" class="form-control input_user" placeholder="E-mal cím">
                        </div>
                        <span class="help-block" style="color: red;"><?php echo $email_err; ?></span>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <input type="submit" class="btn btn-primary" id="login-button" value="Küldés" style="background: #ee4a4a; border-color: #ee4a4a;">
                        </div>
                    </form>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-center links" id="back-to-login">
                        <a href="bejelentkezes.php" class="ml-2"><i class="fas fa-long-arrow-alt-left fa-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include('footer.php');
        ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- Tooltips -->
    <script src="assets/JS/tooltips.js"></script>

</body>

</html>