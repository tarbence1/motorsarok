<?php
session_start();
// Incude config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password  = $is_admin = $email = $recaptcha_err = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recaptcha_response"])) {

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

        // Validate username
        if (empty(trim($_POST["username"]))) {
            $username_err = "Kérem adjon meg egy felhasználónevet.";
        } else {
            // Prepare a select statement
            $sql = "SELECT id FROM users WHERE username = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                // Set parameters
                $param_username = trim($_POST["username"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "A megadott felhasználónév már foglalt.";
                    } elseif (strlen(trim($_POST["username"])) > 20) {
                        $username_err = "A felhasználónév maximum 20 karakterből állhat.";
                    } else {
                        $username = trim($_POST["username"]);
                    }
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }


        // Validate email
        if (empty(trim($_POST["email"]))) {
            $email_err = "Kérem adjon meg egy Email címet.";
        } else {
            // Prepare a select statement
            $sql = "SELECT id FROM users WHERE email = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);

                // Set parameters
                $param_email = trim($_POST["email"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $email_err = "A megadott email cím már foglalt.";
                    } else {
                        $email = trim($_POST["email"]);
                    }
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Kérem adjon meg egy jelszót.";
        } elseif (strlen(trim($_POST["password"])) < 6) {
            $password_err = "A jelszónak legalább 6 karakterből kell állnia.";
            $_POST["confirm_password"] = "";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Kérem erősítse meg a jelszót.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "A jelszavak nem egyeznek.";
            }
        }

        // Check input errors before inserting in database
        if (empty($username_err)  && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

            // Prepare an insert statement
            $sql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssi", $param_username, $param_email, $param_password, $is_admin);

                // Set parameters
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                $is_admin = 0;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['success'] = true;
                    // Redirect to login page
                    echo "<script> window.location.replace('bejelentkezes.php') </script>";
                } else {
                    echo "Hiba történt! Kérem próbálja meg később.";
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Close connection
        mysqli_close($link);
    } else {
        $recaptcha_err = "A Google reCAPTCHA hibát észlelt, kérjük próbálja meg újra!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Regisztráció</title>

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
            <div class="register-panel">
                <div class="d-flex justify-content-center">
                    <div class="logo_container">
                        <img src="images/logo.png" class="logo" alt="Logo">
                    </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text" id="register-username"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="username" class="form-control input_user" value="" placeholder="Felhasználónév" required>
                            <span class="help-block" style="color: red;"><?php echo $username_err; ?></span>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text" id="register-email"><i class="fas fa-at"></i></span>
                            </div>
                            <input type="email" name="email" class="form-control input_user" value="" placeholder="E-mail cím" required>
                            <span class="help-block" style="color: red;"><?php echo $email_err; ?></span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="register-password"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control input_pass" id="password-field" value="" placeholder="Jelszó" required>
                            <div class="input-group-append" data-toggle="tooltip" data-placement="bottom" title="Jelszó mutatása">
                                <span toggle="#password-field" class="fas fa-lock field-icon toggle-password input-group-text" id="show-password"></span>
                            </div>
                            <span class="help-block" style="color: red;"><?php echo $password_err; ?></span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="register-password2"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="confirm_password" class="form-control input_pass" id="password-field2" value="" placeholder="Jelszó megerősítése" required>
                            <div class="input-group-append" data-toggle="tooltip" data-placement="bottom" title="Jelszó mutatása">
                                <span toggle="#password-field2" class="fas fa-lock field-icon toggle-password input-group-text" id="show-password2"></span>
                            </div>
                            <span class="help-block" style="color: red;"><?php echo $confirm_password_err; ?></span>
                            <span class="help-block" style="color: red;"><?php echo $recaptcha_err; ?></span>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container" id="register-button">
                            <input type="submit" class="btn btn-primary" id="register-button" value="Regisztráció">
                        </div>
                        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    </form>
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-center links" id="login-link">
                        Már regisztrált?<a href="bejelentkezes.php" class="ml-2">Bejelentkezés</a>
                    </div>
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
    <!-- Show password -->
    <script src="assets/JS/show-password.js"></script>

    <!-- Tooltips -->
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</body>

</html>