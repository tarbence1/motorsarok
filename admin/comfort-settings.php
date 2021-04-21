<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['comfortID'],
    $_POST['comfortNewName'],
    $_POST['currentComfortName']
)) {

    if ($_POST['comfortNewName'] === "") {
        $message = 'Kérjük adja meg az új nevet!';
        $ok = false;
    } else {
        $newName = $_POST['comfortNewName'];
        $message = 'Sikeres módosítás!';
    }

    $comfortID = $_POST['comfortID'];
    $technicalName = $_POST['currentComfortName'];

    if ($ok) {
        // Update data
        $stmt = $link->prepare("UPDATE comfort_equipment SET name=? WHERE id=?");
        $stmt->bind_param('si', $newName, $comfortID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'newName' => $newName,
                'comfortid' => $comfortID,
                'message' => $message
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
