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
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Kérem adja meg a felhasználónevét/Email címét.";
    } else {
        $email = trim($_POST["username"]);
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Kérem adja meg a jelszavát.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, email, password, is_admin FROM users WHERE email = ? OR username = ? ";


        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_username);

            // Set parameters
            $param_email = $email;
            $param_username = $username;



            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username,  $email, $hashed_password, $is_admin);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["is_admin"] = $is_admin;



                            // Redirect user to the stored page
                            if (array_key_exists('url', $_SESSION)) {
                                header('Location: ' . $_SESSION['url']);
                                unset($_SESSION['url']);
                            } else {
                                header('Location: index.php');
                            }
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "A megadott jelszó nem megfelelő.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "Érvénytelen felhasználónév/Email cím.";
                }
            }
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
                <div class="d-flex justify-content-center form_container">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text" id="login-email"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="username" class="form-control input_user" placeholder="E-mal cím/Felhasználónév">
                            <span class="help-block" style="color: red;"><?php echo $username_err; ?></span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="login-password"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control input_pass" id="password-field" placeholder="Jelszó">
                            <div class="input-group-append" data-toggle="tooltip" data-placement="bottom" title="Jelszó mutatása">
                                <span toggle="#password-field" class="fas fa-lock field-icon toggle-password input-group-text" id="show-password"></span>
                            </div>
                            <span class="help-block" style="color: red;"><?php echo $password_err; ?></span>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <input type="submit" class="btn btn-primary" id="login-button" value="Bejelentkezés" style="background: #ee4a4a; border-color: #ee4a4a;">
                        </div>
                    </form>
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-center links" id="register-link">
                        <a href="regisztracio.php" class="ml-2">Regisztráció</a>
                    </div>
                    <div class="d-flex justify-content-center links" id="forgot-password-link">
                        <a href="elfelejtett-jelszo.php">Elfelejtett jelszó</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (isset($_SESSION['success'])) {
        ?>
            <script>
                alertify.success('Sikeres regisztráció!');
            </script>
        <?php
            unset($_SESSION['success']);
        }
        include('footer.php'); ?>
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
    <script src="assets/JS/tooltips.js"></script>

</body>

</html>