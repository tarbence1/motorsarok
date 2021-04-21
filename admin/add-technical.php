<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['technicalName']
)) {

    if ($_POST['technicalName'] === "") {
        $message = 'Kérjük adja meg a felszereltség nevét!';
        $ok = false;
    } else {
        $name = $_POST['technicalName'];
        $message = 'Sikeres hozzáadás!';
    }

    if ($ok) {
        // Insert data
        $stmt = $link->prepare("INSERT INTO technical_equipment (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();
        $link->close();
        $_SESSION['manufacturerSuccess'] = true;
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
