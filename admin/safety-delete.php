<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['hiddenSafetyID'],
)) {
    $safetyID = $_POST['hiddenSafetyID'];


    // Check if safety equipment is in use
    $safety_stmt = $link->prepare("SELECT COUNT(safety_equipment_id) as counted FROM mx_safety_equipment WHERE safety_equipment_id = ?");
    $safety_stmt->bind_param('i', $safetyID);
    $safety_stmt->execute();
    $result = $safety_stmt->get_result();
    $row = $result->fetch_assoc();
    $counted = $row['counted'];
    $safety_stmt->close();

    if ($counted > 0) {
        $ok = false;
    }

    if ($ok) {
        // Update data
        $stmt = $link->prepare("DELETE FROM safety_equipment WHERE id = ?");
        $stmt->bind_param('i', $safetyID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'safetyid' => $safetyID
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
