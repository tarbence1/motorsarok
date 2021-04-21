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
$new_password = $old_password = $password_again = "";
$password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate old password
    if (empty(trim($_POST["old_password"]))) {
        $password_err = "Kérem adja meg a régi jelszavát.";
    } else {
        $select_sql = "SELECT password FROM users WHERE id = ?";
        if ($select_stmt = mysqli_prepare($link, $select_sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($select_stmt, "i", $param_id);

            // Set parameter
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($select_stmt)) {
                $result = mysqli_stmt_get_result($select_stmt);
                $row = mysqli_fetch_assoc($result);
                $selected_password = $row['password'];
            } else {
                die(header("HTTP/1.0 404 Not Found"));
            }
        }
        // Close statement
        mysqli_stmt_close($select_stmt);

        $old_password = trim($_POST["old_password"]);

        if (!password_verify($old_password, $selected_password)) {
            $password_err = "Hibás jelszó.";
        }
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $password_err = "Kérem adja meg az új jelszót.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $password_err = "A jelszónak legalább 6 karakterből kell állnia.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    if (empty(trim($_POST["password_again"]))) {
        $password_err = "Kérem erősítse meg az új jelszót.";
    } else {
        $password_again = trim($_POST["password_again"]);
        if ($new_password != $password_again) {
            $password_err = "A jelszavak nem egyeznek.";
        }
    }

    if (empty(trim($_POST["new_password"])) && empty(trim($_POST["old_password"])) && empty(trim($_POST["password_again"]))) {
        $password_err = "Kérem töltse ki a mezőket.";
    }

    // Check input errors before updating the database
    if (empty($password_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $password_err = "Sikeres jelszó módosítás!";
                exit(json_encode(
                    array(
                        'password_err' => $password_err,
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
                'password_err' => $password_err,
                'ok' => 0
            )
        ));
    }

    // Close connection
    mysqli_close($link);
}
