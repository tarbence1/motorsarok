<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['hiddenComfortID'],
)) {
    $comfortID = $_POST['hiddenComfortID'];


    // Check if comfort equipment is in use
    $comfort_stmt = $link->prepare("SELECT COUNT(comfort_equipment_id) as counted FROM mx_comfort_equipment WHERE comfort_equipment_id = ?");
    $comfort_stmt->bind_param('i', $comfortID);
    $comfort_stmt->execute();
    $result = $comfort_stmt->get_result();
    $row = $result->fetch_assoc();
    $counted = $row['counted'];
    $comfort_stmt->close();

    if ($counted > 0) {
        $ok = false;
    }

    if ($ok) {
        // Update data
        $stmt = $link->prepare("DELETE FROM comfort_equipment WHERE id = ?");
        $stmt->bind_param('i', $comfortID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'comfortid' => $comfortID
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
