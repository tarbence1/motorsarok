<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['safetyName']
)) {

    if ($_POST['safetyName'] === "") {
        $message = 'Kérjük adja meg a felszereltség nevét!';
        $ok = false;
    } else {
        $name = $_POST['safetyName'];
        $message = 'Sikeres hozzáadás!';
    }

    if ($ok) {
        // Insert data
        $stmt = $link->prepare("INSERT INTO safety_equipment (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();
        $link->close();
        $_SESSION['manufacturerSuccess'] = true;
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
