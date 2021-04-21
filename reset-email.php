<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

// Store the url and redirect the user if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: bejelentkezes.php");
    exit;
}

// Define variables and initialize with empty values
$new_email = $new_email_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate new email
    if (empty(trim($_POST["new_email"]))) {
        $new_email_err = "Kérem adja meg az új email címét.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            // Set parameters
            $param_email = trim($_POST["new_email"]);

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $new_email_err = "Ez az Email cím már foglalt.";
                } else {
                    $new_email = trim($_POST["new_email"]);
                }
            } else {
                echo "Hiba! Kérjük próbálja meg később";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Check input errors before updating the database
    if (empty($new_email_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET email = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            // Set parameters
            $param_email = $new_email;
            $param_id = $_SESSION["id"];

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_email, $param_id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $new_email_err = "Sikeres email cím módosítás!";
                exit(json_encode(
                    array(
                        'new_email' => $new_email,
                        'new_email_err' => $new_email_err,
                        'ok' => 1
                    )
                ));
            } else {
                die(header("HTTP/1.0 404 Not Found"));
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        exit(json_encode(
            array(
                'new_email_err' => $new_email_err,
                'ok' => 0
            )
        ));
    }

    // Close connection
    mysqli_close($link);
}
