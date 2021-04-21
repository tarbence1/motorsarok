<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['safetyID'],
    $_POST['safetyNewName'],
    $_POST['currentSafetyName']
)) {

    if ($_POST['safetyNewName'] === "") {
        $message = 'Kérjük adja meg az új nevet!';
        $ok = false;
    } else {
        $newName = $_POST['safetyNewName'];
        $message = 'Sikeres módosítás!';
    }

    $safetyID = $_POST['safetyID'];
    $safetyName = $_POST['currentSafetyName'];

    if ($ok) {
        // Update data
        $stmt = $link->prepare("UPDATE safety_equipment SET name=? WHERE id=?");
        $stmt->bind_param('si', $newName, $safetyID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'newName' => $newName,
                'safetyid' => $safetyID,
                'message' => $message
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
