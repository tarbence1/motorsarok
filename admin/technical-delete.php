<?php
session_start();
require_once("../config.php");
$ok = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['hiddenTechnicalID'],
)) {
    $technicalID = $_POST['hiddenTechnicalID'];


    // Check if technical equipment is in use
    $technical_stmt = $link->prepare("SELECT COUNT(technical_equipment_id) as counted FROM mx_technical_equipment WHERE technical_equipment_id = ?");
    $technical_stmt->bind_param('i', $technicalID);
    $technical_stmt->execute();
    $result = $technical_stmt->get_result();
    $row = $result->fetch_assoc();
    $counted = $row['counted'];
    $technical_stmt->close();

    if ($counted > 0) {
        $ok = false;
    }

    if ($ok) {
        // Update data
        $stmt = $link->prepare("DELETE FROM technical_equipment WHERE id = ?");
        $stmt->bind_param('i', $technicalID);
        $stmt->execute();
        $stmt->close();
        $link->close();

        exit(json_encode(
            array(
                'technicalid' => $technicalID,
            )
        ));
    } else {
        die(header("HTTP/1.0 404 Not Found"));
    }
}
