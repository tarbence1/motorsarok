<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['countyID'],
    $_POST['countyNewName'],
    $_POST['currentCountyName']
)) {

    if ($_POST['countyNewName'] === "") {
        $message = 'Kérjük adja meg az új nevet!';
        $ok = false;
    } else {
        $newName = $_POST['countyNewName'];
        $message = 'Sikeres módosítás!';
    }

    $countyID = $_POST['countyID'];
    $countyName = $_POST['currentCountyName'];

    if ($ok) {
        // Update counties in motorcycles
        $motorcycles_stmt = $link->prepare("UPDATE motorcycles SET county=? WHERE county LIKE ?");
        $motorcycles_stmt->bind_param('ss', $newName, $countyName);
        $motorcycles_stmt->execute();
        $motorcycles_stmt->close();

        // Update data
        $stmt = $link->prepare("UPDATE counties SET name=? WHERE id=?");
        $stmt->bind_param('si', $newName, $countyID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'newName' => $newName,
                'countyid' => $countyID,
                'message' => $message
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
