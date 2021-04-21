<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

$name_error = $email_error = $message_error = "";

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST["recaptcha_response"])) {

    // Create POST request to send to Google
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_secret = "6LeR3rIaAAAAACTFR9CwOTG74QTKlXzzdA30DzMh";
    $recaptcha_response = $_POST["recaptcha_response"];

    // Send POST request
    $recaptcha = file_get_contents($recaptcha_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);

    // Decode response
    $recaptcha = json_decode($recaptcha);
    print_r($recaptcha);

    // Check if verified
    if ($recaptcha->success && $recaptcha->score >= 0.5) {

        if (empty($_POST['name'])) {
            $_SESSION['contactError'] = "Kérjük adja meg a nevét!";
        } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['contactError'] = "Kérjük adja meg a valódi Email címét!";
        } elseif (empty($_POST['message'])) {
            $_SESSION['contactError'] = "Kérjük adja meg üzenetét!";
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
                            <p>Név:" . $name . "</p>
                            <p>Email:" . $from . "</p>
                            <p>Üzenet:" . $msg . "</p>
                            </body>
                            </html>";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <motor@sarok.hu>' . "\r\n";
            if (mail($to, $subject, $message, $headers)) {
                echo "jo";
                $_SESSION['contactSuccess'] = "Köszönjük üzenetét. Hamarosan felvesszük Önnel a kapcsolatot!";
            } else {
                echo "nemjo";
                $_SESSION['contactError'] = "Valami hiba történt. Kérjük próbálja meg később!";
            }
            echo $_SESSION['contactSuccess'];
            echo $_SESSION['contactError'];
        }
    } else {
        $_SESSION['contactError'] = "A Google reCAPTCHA hibát észlelt, kérjük próbálja meg később!";
    }
}
