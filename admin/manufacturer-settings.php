<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['manufacturerID'],
    $_POST['manufacturerNewName'],
    $_POST['currentManufacturerName']
)) {

    if ($_POST['manufacturerNewName'] === "") {
        $message = 'Kérjük adja meg az új nevet!';
        $ok = false;
    } else {
        $newName = $_POST['manufacturerNewName'];
        $message = 'Sikeres módosítás!';
    }

    $manufacturerID = $_POST['manufacturerID'];
    $manufacturerName = $_POST['currentManufacturerName'];

    if ($ok) {
        // Update motorcycles
        $motorcycles_stmt = $link->prepare("UPDATE motorcycles SET manufacturer=? WHERE manufacturer LIKE ?");
        $motorcycles_stmt->bind_param('ss', $newName, $manufacturerName);
        $motorcycles_stmt->execute();
        $motorcycles_stmt->close();

        // Update data
        $stmt = $link->prepare("UPDATE manufacturers SET name=? WHERE id=?");
        $stmt->bind_param('si', $newName, $manufacturerID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'newName' => $newName,
                'manufacturerid' => $manufacturerID,
                'message' => $message
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
