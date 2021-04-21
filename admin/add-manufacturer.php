<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['manufacturerName']
)) {

    if ($_POST['manufacturerName'] === "") {
        $message = 'Kérjük adja meg a gyártó nevét!';
        $ok = false;
    } else {
        $name = $_POST['manufacturerName'];
        $message = 'Sikeres hozzáadás!';
    }

    if ($ok) {
        // Insert data
        $stmt = $link->prepare("INSERT INTO manufacturers (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();
        $link->close();
        $_SESSION['manufacturerSuccess'] = true;
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
