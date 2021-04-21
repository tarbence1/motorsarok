<?php
// Start the session
session_start();
// Incude config file
require_once("config.php");

$maxSize = 2 * 1024 * 1024; // Image upload max size (2MB)
$ok = true;

if ($_FILES['file']['name'] != '') {
    $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    $fileName = $_FILES['file']['name'];
    $fileTempName = $_FILES['file']['tmp_name'];
    $detectedType = exif_imagetype($fileTempName);

    if ($_FILES["file"]["size"] > $maxSize || !in_array($detectedType, $allowed_types)) {
        $ok = false;
    }

    if ($ok) {
        $stmt = $link->prepare("UPDATE users SET avatar=? WHERE id=?");
        $userid = $_SESSION['id'];
        $file = $fileTempName;
        if (is_uploaded_file($file) && !empty($file)) {
            $data = "avatars/" . time() . $_FILES["file"]["name"];
            move_uploaded_file($file, $data);
            $stmt->bind_param("si", $data, $userid);

            $stmt2 = $link->prepare("SELECT avatar FROM users WHERE id = ?");
            $stmt2->bind_param("i", $userid);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $record = $result2->fetch_assoc();
            $avatar = $record['avatar'];

            if (file_exists($avatar)) {
                unlink($avatar);
            }

            $stmt2->close();
            $stmt->execute();
            $stmt->close();
            $link->close();
            echo $data;
        }
    }
}
