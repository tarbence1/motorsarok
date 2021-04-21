<?php
session_start();
require_once("config.php");
// Add to garage
if (isset($_POST['garage'])) {
    $ok = true;
    $errorMsg = "";

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
        $errorMsg = 'Kérjük előbb jelentkezzen be!';
        $ok = false;
    } else {

    

    $userid = $_SESSION['id'];
    $advertId = $_POST['advert-id'];


    $stmt_check = $link->prepare("SELECT COUNT(1) FROM parts_garage WHERE userid = ? AND partid = ?");
    $stmt_check->bind_param('ii', $userid, $advertId);
    $stmt_check->execute();
    $stmt_check->bind_result($found);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($found) {
        $ok = false;
        $errorMsg = 'A kiválasztott hirdetés már a garázsban van!';
    } else {
        $ok = true;
        $errorMsg = 'Garázsba helyezve!';
        $stmt = $link->prepare("INSERT INTO parts_garage (userid, partid) VALUES (?, ?)");
        $stmt->bind_param('ii', $userid, $advertId);
        $stmt->execute();
        $stmt->close();
    }
}

    $link->close();

    exit(json_encode(
        array(
            'ok' => $ok,
            'errorMsg' => $errorMsg
        )
    ));
}
