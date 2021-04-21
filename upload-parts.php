<?php
session_start();
require_once("config.php");

$errorMsg = array();
$maxSize = 2 * 1024 * 1024;
$ok = true;
$success = false;


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
    header("Location: bejelentkezes.php");
    exit;
}

$userid = $_SESSION['id'];

//count advertisements
$sql2 = "SELECT COUNT(id) as counted FROM parts WHERE userid = ? AND status = ?";
$status = 1;
$stmt3 = $link->prepare($sql2);
$stmt3->bind_param("ii", $userid, $status);
$stmt3->execute();
$result = $stmt3->get_result();
$row = $result->fetch_assoc();
$countAds = $row['counted'];


//get premium
$sql = "SELECT premium FROM users WHERE id = ?";
$stmt2 = $link->prepare($sql);
$stmt2->bind_param("i", $userid);
$stmt2->execute();
$result = $stmt2->get_result();
$rec = $result->fetch_assoc();
$premium = $rec['premium'];

switch ($premium) {
    case 0:
        $maxImage = 5;
        $maxAds = 2;
        break;
    case 1:
        $maxImage = 7;
        $maxAds = 5;
        break;
    case 2:
        $maxImage = 10;
        $maxAds = 10;
        break;
    case 3:
        $maxImage = 12;
        $maxAds = 9999;
        break;
    default:
        $maxImage = 5;
        $maxAds = 2;
}

if (isset($_POST['upload'])) {

    $stmt = $link->prepare("INSERT INTO parts (userid, product_name, price, motorcycle_type, cond, description, name, email, phone, phone2, county, settlement) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "isisssssssss",
        $userid,
        $product_name,
        $price,
        $motorcycle_type,
        $cond,
        $description,
        $name,
        $email,
        $phone,
        $phone2,
        $county,
        $settlement
    );


    if (isset($_POST['product_name']) && $_POST['product_name'] != '') {
        $product_name = $_POST['product_name'];
    } else {
        $errorMsg[] = "Kérjük adja meg a termék nevét!";
        $ok = false;
    }

    if (isset($_POST['price']) && $_POST['price'] != '') {
        $price = $_POST['price'];
    } else {
        $errorMsg[] = "Kérjük adja meg az árat!";
        $ok = false;
    }

    if (isset($_POST['cond']) && $_POST['cond'] != '') {
        $cond = $_POST['cond'];
    } else {
        $errorMsg[] = "Kérjük válassza ki az állapotot!";
        $ok = false;
    }

    if (isset($_POST['name']) && $_POST['name'] != '') {
        $name = $_POST['name'];
    } else {
        $errorMsg[] = "Kérjük adja meg a nevét!";
        $ok = false;
    }

    if (isset($_POST['phone']) && $_POST['phone'] != '') {
        $phone = $_POST['phone'];
    } else {
        $errorMsg[] = "Kérjük adja meg a telefonszámát!";
        $ok = false;
    }

    if (isset($_POST['county']) && $_POST['county'] != '') {
        $county = $_POST['county'];
    } else {
        $errorMsg[] = "Kérjük válassza ki a megyét!";
        $ok = false;
    }

    if (isset($_POST['settlement']) && $_POST['settlement'] != '') {
        $settlement = $_POST['settlement'];
    } else {
        $errorMsg[] = "Kérjük adja meg a település nevét!";
        $ok = false;
    }

    $userid = $_SESSION['id'];
    $motorcycle_type = $_POST['motorcycle_type'];
    $description = $_POST['description'];
    $email = $_POST['email'];
    $phone2 = $_POST['phone2'];

    //Check lengths
    if (
        strlen($product_name) > 50  || strlen($price)  > 10 || strlen($motorcycle_type) > 100 || strlen($description) > 1000 || strlen($name) > 50 || strlen($email) > 50 || strlen($phone) > 20 || strlen($phone2) > 20 || strlen($settlement) > 50
    ) {
        $errorMsg[] = "Kérjük megfelelő hosszúságú válaszokat adjon meg!";
        $ok = false;
    }


    //Check adverts
    if ($countAds >= $maxAds) {
        $ok = false;
        $errorMsg[] = "Elérte a maximum feladható hirdetések számát! Amennyiben újabb hirdetést szeretne feladni, kérjük válasszon egyet <a href ='profile.php'><b>prémium</b></a> csomagjaink közül.";
    }

    // Check if phone numbers are valid
    if (!preg_match("/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/", $phone)) {
        $ok = false;
        $errorMsg[] = "Kérjük valós telefonszámot adjon meg!";
    }
    if (!empty($phone2) && $phone2 !== "" && !preg_match("/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/", $phone2)) {
        $ok = false;
        $errorMsg[] = "Kérjük valós 2. telefonszámot adjon meg!";
    }

    // Check if email is valid
    if (!empty($email) && $email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ok = false;
        $errorMsg[] = "Kérjük valós email címet adjon meg!";
    }


    if ($ok) {
        //IMAGE UPLOAD
        $filesize_error = 0;
        $filesTempName = $_FILES['images']['tmp_name'];
        $filesName = $_FILES['images']['name'];
        $counted = count($filesName);
        $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);

        if ($counted > $maxImage) {
            $errorMsg[] = "Maximum 5 képet lehet feltölteni!";
        } else {
            for ($i = 0; $i < $counted; $i++) {
                if (empty($filesTempName[$i])) {
                    $errorMsg[] = "Legalább egy képet ki kell választani!";
                } else {
                    $detectedType = exif_imagetype($filesTempName[$i]);
                    if ($_FILES["images"]["size"][$i] > $maxSize) {
                        $filesize_error = 1;
                        $errorMsg[] = "Minden képnek 2 Mb-nál kisebbnek kell lennie!";
                    } elseif (!in_array($detectedType, $allowed_types)) {
                        $errorMsg[] = "A képek csak PNG/JPG/JPEG/GIF formátumban elfogadottak!";
                    } elseif ($filesize_error == 0) {
                        if (isset($_POST['mainimage']) && $_POST['mainimage'] != '') {
                            $check = $stmt->execute();
                            if ($check === false) {
                                http_response_code(400);
                                $stmt->close();
                                exit(json_encode(
                                    array(
                                        'ok' => false,
                                        'errorMsg' => 'Valami hiba történt. Kérjük próbálja meg később!'
                                    )
                                ));
                            }
                            $stmt->close();

                            $productid = $link->insert_id;
                            $statement = $link->prepare("INSERT INTO parts_images(thumbnailimage, productid, is_mainimage) VALUES(?, ?, ?)");


                            for ($i = 0; $i < $counted; $i++) {
                                $file = $filesTempName[$i];
                                $is_mainimage = 0;
                                if (is_uploaded_file($file) && !empty($file)) {
                                    if ($_FILES["images"]["name"][$i] == $_POST['mainimage']) {
                                        $is_mainimage = 1;
                                    } else {
                                        $is_mainimage = 0;
                                    }
                                    $data = "parts-uploads/" . time() . $_FILES["images"]["name"][$i];
                                    move_uploaded_file($file, $data);

                                    $statement->bind_param("sii", $data, $productid, $is_mainimage);
                                    $statement->execute();
                                }
                            }
                            $statement->close();
                            $link->close();
                            $success = true;
                            $_SESSION['successad'] = true;
                        } else {
                            $errorMsg[] = "Kérjük válassza ki a fő képet!";
                            $ok = false;
                        }
                    }
                }
            }
        }
    }

    if ($success) {
        http_response_code(200);
        exit(json_encode(
            array(
                'ok' => $ok,
                'errorMsg' => $errorMsg
            )
        ));
        exit();
    } else {
        http_response_code(400);
        exit(json_encode(
            array(
                'ok' => $ok,
                'errorMsg' => $errorMsg
            )
        ));
    }
}
