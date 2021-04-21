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
$sql2 = "SELECT COUNT(id) as counted FROM motorcycles WHERE userid = ? AND status = ?";
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

    $stmt = $link->prepare("INSERT INTO motorcycles (userid, manufacturer, model, price, type, year, month, documents, documentsvalidity, kilometers, operatingtime, motyear, motmonth,
                        capacity, performance, enginetype, fuel, color, cond, license, description, name, email, phone, phone2, county, settlement) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "issisiissiiiiiissssssssssss",
        $userid,
        $manufacturer,
        $model,
        $price,
        $type,
        $year,
        $month,
        $documents,
        $documentsvalidity,
        $kilometers,
        $operatingtime,
        $motyear,
        $motmonth,
        $capacity,
        $performance,
        $enginetype,
        $fuel,
        $color,
        $cond,
        $license,
        $description,
        $name,
        $email,
        $phone,
        $phone2,
        $county,
        $settlement
    );



    $userid = $_SESSION['id'];

    if (isset($_POST['manufacturer']) && $_POST['manufacturer'] != '') {
        $manufacturer = $_POST['manufacturer'];
    } else {
        $errorMsg[] = "Kérjük válassza ki a gyártót!";
        $ok = false;
    }

    if (isset($_POST['model']) && $_POST['model'] != '') {
        $model = $_POST['model'];
    } else {
        $errorMsg[] = "Kérjük adja meg a modellt!";
        $ok = false;
    }

    if (isset($_POST['price']) && $_POST['price'] != '') {
        $price = $_POST['price'];
    } else {
        $errorMsg[] = "Kérjük adja meg az árat!";
        $ok = false;
    }

    if (isset($_POST['type']) && $_POST['type'] != '') {
        $type = $_POST['type'];
    } else {
        $errorMsg[] = "Kérjük adja meg a kivitelt!";
        $ok = false;
    }

    if (isset($_POST['year']) && $_POST['year'] != '') {
        $year = $_POST['year'];
    } else {
        $errorMsg[] = "Kérjük adja meg a gyártási évet!";
        $ok = false;
    }

    if (isset($_POST['month']) && $_POST['month'] != '') {
        $month = $_POST['month'];
    } else {
        $errorMsg[] = "Kérjük adja meg a gyártási hónapot!";
        $ok = false;
    }

    if (isset($_POST['documents']) && $_POST['documents'] != '') {
        $documents = $_POST['documents'];
    } else {
        $errorMsg[] = "Kérjük adja meg az okmányok jellegét!";
        $ok = false;
    }

    if (isset($_POST['documentsvalidity']) && $_POST['documentsvalidity'] != '') {
        $documentsvalidity = $_POST['documentsvalidity'];
    } else {
        $errorMsg[] = "Kérjük adja meg az okmányok érvényességét!";
        $ok = false;
    }

    if (isset($_POST['kilometers']) && $_POST['kilometers'] != '') {
        $kilometers = $_POST['kilometers'];
    } else {
        $errorMsg[] = "Kérjük adja meg a kilométeróra állását!";
        $ok = false;
    }

    if (isset($_POST['capacity']) && $_POST['capacity'] != '') {
        $capacity = $_POST['capacity'];
    } else {
        $errorMsg[] = "Kérjük adja meg a hengerűrtartalmat!";
        $ok = false;
    }

    if (isset($_POST['performance']) && $_POST['performance'] != '') {
        $performance = $_POST['performance'];
    } else {
        $errorMsg[] = "Kérjük adja meg a teljesítményt!";
        $ok = false;
    }

    if (isset($_POST['license']) && $_POST['license'] != '') {
        $license = $_POST['license'];
    } else {
        $errorMsg[] = "Kérjük adja meg a jogosítvány típusát!";
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
        $errorMsg[] = "Kérjük adja meg a megyét!";
        $ok = false;
    }

    if (isset($_POST['settlement']) && $_POST['settlement'] != '') {
        $settlement = $_POST['settlement'];
    } else {
        $errorMsg[] = "Kérjük adja meg a település nevét!";
        $ok = false;
    }

    /*$front_disc_brake = isset($_POST['front-disc-brake']) && $_POST['front-disc-brake'] == '1' ? 1 : 0;

    $rear_disc_brake = isset($_POST['rear-disc-brake']) && $_POST['rear-disc-brake'] == '1' ? 1 : 0;
    $onboard_computer = isset($_POST['onboard-computer']) && $_POST['onboard-computer'] == '1' ? 1 : 0;
    $sports_air_filter = isset($_POST['sports-air-filter']) && $_POST['sports-air-filter'] == '1' ? 1 : 0;
    $tempomat = isset($_POST['tempomat']) && $_POST['tempomat'] == '1' ? 1 : 0;
    $heated_mirror = isset($_POST['heated-mirror']) && $_POST['heated-mirror'] == '1' ? 1 : 0;
    $immobiliser = isset($_POST['immobiliser']) && $_POST['immobiliser'] == '1' ? 1 : 0;
    $self_starting = isset($_POST['self-starting']) && $_POST['self-starting'] == '1' ? 1 : 0;
    $alarm = isset($_POST['alarm']) && $_POST['alarm'] == '1' ? 1 : 0;
    $sports_exhaust = isset($_POST['sports-exhaust']) && $_POST['sports-exhaust'] == '1' ? 1 : 0;

    $factory_boxes = isset($_POST['factory-boxes']) && $_POST['factory-boxes'] == '1' ? 1 : 0;
    $rear_boxes = isset($_POST['rear-boxes']) && $_POST['rear-boxes'] == '1' ? 1 : 0;
    $side_boxes = isset($_POST['side-boxes']) && $_POST['side-boxes'] == '1' ? 1 : 0;
    $navigation = isset($_POST['navigation']) && $_POST['navigation'] == '1' ? 1 : 0;
    $leather_seat = isset($_POST['leather-seat']) && $_POST['leather-seat'] == '1' ? 1 : 0;

    $heated_seat = isset($_POST['heated-seat']) && $_POST['heated-seat'] == '1' ? 1 : 0;
    $middle_stand = isset($_POST['middle-stand']) && $_POST['middle-stand'] == '1' ? 1 : 0;
    $footrest = isset($_POST['footrest']) && $_POST['footrest'] == '1' ? 1 : 0;
    $handle_heating = isset($_POST['handle-heating']) && $_POST['handle-heating'] == '1' ? 1 : 0;
    $plexi = isset($_POST['plexi']) && $_POST['plexi'] == '1' ? 1 : 0;

    $abs = isset($_POST['abs']) && $_POST['abs'] == '1' ? 1 : 0;
    $frame_sliders = isset($_POST['frame-sliders']) && $_POST['plexi'] == '1' ? 1 : 0;
    $dtc = isset($_POST['dtc']) && $_POST['dtc'] == '1' ? 1 : 0;
    $hand_protector = isset($_POST['hand-protector']) && $_POST['hand-protector'] == '1' ? 1 : 0;
    $fog_light = isset($_POST['fog-light']) && $_POST['fog-light'] == '1' ? 1 : 0;
    $airbag = isset($_POST['airbag']) && $_POST['airbag'] == '1' ? 1 : 0;
    $xenon = isset($_POST['xenon']) && $_POST['xenon'] == '1' ? 1 : 0;*/

    $operatingtime = $_POST['operatingtime'];
    $motyear = $_POST['motyear'];
    $motmonth = $_POST['motmonth'];
    $enginetype = $_POST['enginetype'];
    $fuel = $_POST['fuel'];
    $color = $_POST['color'];
    $cond = $_POST['cond'];
    $description = $_POST['description'];
    $email = $_POST['email'];
    $phone2 = $_POST['phone2'];

    //Check lengths
    if (
        strlen($model) > 50  || strlen($kilometers)  > 10 || strlen($operatingtime) > 10 || strlen($capacity) > 10 || strlen($performance) > 10
        || strlen($color) > 50 || strlen($description) > 1000 || strlen($name) > 50 || strlen($email) > 50 || strlen($phone) > 20 || strlen($phone2) > 20 || strlen($settlement) > 50
    ) {
        $ok = false;
        $errorMsg[] = "Kérjük megfelelő hosszúságú válaszokat adjon meg!";
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


                            // Checkboxes

                            // technical equipments
                            $technical_arr = $_POST['technical'];
                            foreach ($technical_arr as $key => $technical) {
                                $equipment_id = $key;
                                if ($technical == 1) {
                                    $technical_stmt = $link->prepare("INSERT INTO mx_technical_equipment(motorcycle_id, technical_equipment_id) VALUES(?, ?)");
                                    $technical_stmt->bind_param("ii", $productid, $equipment_id);
                                    $technical_stmt->execute();
                                    $technical_stmt->close();
                                }
                            }
                            

                            // comfort equipments
                            $comfort_arr = $_POST['comfort'];
                            foreach ($comfort_arr as $key => $comfort) {
                                $equipment_id = $key;
                                if ($comfort == 1) {
                                    $comfort_stmt = $link->prepare("INSERT INTO mx_comfort_equipment(motorcycle_id, comfort_equipment_id) VALUES(?, ?)");
                                    $comfort_stmt->bind_param("ii", $productid, $equipment_id);
                                    $comfort_stmt->execute();
                                    $comfort_stmt->close();
                                }
                            }
                            

                            // safety equipments
                            $safety_arr = $_POST['safety'];
                            foreach ($safety_arr as $key => $safety) {
                                $equipment_id = $key;
                                if ($safety == 1) {
                                    $safety_stmt = $link->prepare("INSERT INTO mx_safety_equipment(motorcycle_id, safety_equipment_id) VALUES(?, ?)");
                                    $safety_stmt->bind_param("ii", $productid, $equipment_id);
                                    $safety_stmt->execute();
                                    $safety_stmt->close();
                                }
                            }
                            


                            // Images
                            $statement = $link->prepare("INSERT INTO images(thumbnailimage, productid, is_mainimage) VALUES(?, ?, ?)");


                            for ($i = 0; $i < $counted; $i++) {
                                $file = $filesTempName[$i];
                                $is_mainimage = 0;
                                if (is_uploaded_file($file) && !empty($file)) {
                                    if ($_FILES["images"]["name"][$i] == $_POST['mainimage']) {
                                        $is_mainimage = 1;
                                    } else {
                                        $is_mainimage = 0;
                                    }
                                    $data = "uploads/" . time() . $_FILES["images"]["name"][$i];
                                    move_uploaded_file($file, $data);

                                    $statement->bind_param("sii", $data, $productid, $is_mainimage);
                                    if($statement->execute()){
                                        echo "asd\n";
                                        echo $data."\n";
                                        echo $productid."\n";
                                        echo $is_mainimage."\n";
                                    }
                                    
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
