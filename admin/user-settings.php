<?php
session_start();
require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset(
    $_POST['userID'],
    $_POST['premiumSelect'],
    $_POST['adminSelect']
)) {

    $userid = $_POST['userID'];

    // Select data
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt_select = $link->prepare($sql);
    $stmt_select->bind_param("i", $userid);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $row = $result->fetch_assoc();

    // If no option selected, don't change the current admin level
    if ($_POST['adminSelect'] === "") {
        $is_admin = $row['is_admin'];
    } else {
        $is_admin = $_POST['adminSelect'];
    }

    if ($_POST['premiumSelect'] === "") {
        $premium = $row['premium'];
        $premiumstart = $row['premiumstart'];
        $premiumexpiration = $row['premiumexpiration'];
    } else {
        $premium = $_POST['premiumSelect'];
        $premiumstart = date('Y-m-d H:i:s');
        $premiumexpiration = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($premiumstart)));
    }

    // Set is_admin string
    if($is_admin == 1){
        $admin_str = 'Igen';
    } else {
        $admin_str = 'Nem';
    }

    // Set premium string
    $level_str = '';
    switch ($premium) {
        case 1:
            $level_str = 'Bronz';
            break;
        case 2:
            $level_str = 'Ezüst';
            break;
        case 3:
            $level_str = 'Arany';
            break;
        default:
            $level_str = 'Nincs';
    }

    // Update data
    $stmt = $link->prepare("UPDATE users SET premium=?, premiumstart=?, premiumexpiration=?, is_admin=? WHERE id=?");
    $stmt->bind_param('issii', $premium, $premiumstart, $premiumexpiration, $is_admin, $userid);
    $stmt->execute();
    $stmt->close();
    $stmt_select->close();
    $link->close();

    echo json_encode(
        array(
            'userid' => $userid,
            'admin' => $admin_str,
            'premium' => $level_str,
            'start_date' => $premiumstart,
            'end_date' => $premiumexpiration,
            'message' => 'Sikeres módosítás!'
        )
    );
}
