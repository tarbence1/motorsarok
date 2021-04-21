<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['hiddenManufacturerName'],
    $_POST['hiddenManufacturerID'],
)) {

    $manufacturerName = $_POST['hiddenManufacturerName'];
    $manufacturerID = $_POST['hiddenManufacturerID'];


    // Check if manufacturer is in use
    $manufacturer_stmt = $link->prepare("SELECT COUNT(manufacturer) as counted FROM motorcycles WHERE manufacturer LIKE ?");
    $manufacturer_stmt->bind_param('s', $manufacturerName);
    $manufacturer_stmt->execute();
    $result = $manufacturer_stmt->get_result();
    $row = $result->fetch_assoc();
    $counted = $row['counted'];
    $manufacturer_stmt->close();

    if ($counted > 0) {
        $ok = false;
    }

    if ($ok) {
        // Update data
        $stmt = $link->prepare("DELETE FROM manufacturers WHERE name LIKE ?");
        $stmt->bind_param('s', $manufacturerName);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'manufacturerid' => $manufacturerID,
            )
        ));

    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
