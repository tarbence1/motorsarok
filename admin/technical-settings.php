<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['technicalID'],
    $_POST['technicalNewName'],
    $_POST['currentTechnicalName']
)) {

    if ($_POST['technicalNewName'] === "") {
        $message = 'Kérjük adja meg az új nevet!';
        $ok = false;
    } else {
        $newName = $_POST['technicalNewName'];
        $message = 'Sikeres módosítás!';
    }

    $technicalID = $_POST['technicalID'];
    $technicalName = $_POST['currentTechnicalName'];

    if ($ok) {
        // Update data
        $stmt = $link->prepare("UPDATE technical_equipment SET name=? WHERE id=?");
        $stmt->bind_param('si', $newName, $technicalID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'newName' => $newName,
                'technicalid' => $technicalID,
                'message' => $message
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
