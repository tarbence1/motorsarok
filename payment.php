<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

if (!isset($_POST['package'])) {
    header("Location: profile.php");
    exit;
} else {
    $package = $_POST['package'];
    $userid = $_SESSION['id'];
    $premium = 0;
    $premium_name = '';
    $premiumstart = date('Y-m-d H:i:s');
    $premiumexpiration = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($premiumstart)));


    switch ($package) {
        case 'bronze':
            $premium = 1;
            $premium_name = 'Bronz';
            break;
        case 'silver':
            $premium = 2;
            $premium_name = 'Ezüst';
            break;
        case 'gold':
            $premium = 3;
            $premium_name = 'Arany';
            break;
        default:
            $premium = 0;
    }

    $stmt = $link->prepare("UPDATE users SET premium=?, premiumstart=?, premiumexpiration=? WHERE id = ?");
    $stmt->bind_param('issi', $premium, $premiumstart, $premiumexpiration, $userid);
    $stmt->execute();
    $stmt->close();
    $link->close();

    echo json_encode(
        array(
            'premium_name' => $premium_name,
            'premiumexpiration' => $premiumexpiration,
            'message' => 'Sikeres vásárlás!'
        )
    );
}
