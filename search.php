<?php
if (empty($_SESSION['actual_link'])) {
    $_SESSION['actual_link'] = "index.php?";
}

$actual_link = 'index.php?';

if (
    isset($_GET['manufacturer_search']) || isset($_GET['type_search']) || isset($_GET['driver_license_search'])
    || isset($_GET['min_year']) || isset($_GET['max_year']) || isset($_GET['county_search']) || isset($_GET['min_price'])
    || isset($_GET['max_price']) || isset($_GET['min_ccm']) || isset($_GET['max_ccm']) || isset($_GET['results']) || isset($_GET['orderby'])
) {
    $default_params[] = "i";
    $default_vars[] = $status;
    $params[] = "i";
    $vars[] = $status;
    $sql_search = 'SELECT m.*, u.premium, u.avatar FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ?';
    $default_search =  'SELECT m.*, u.premium FROM motorcycles m INNER JOIN users u ON u.id = m.userid WHERE status = ?';

    // Manufacturer
    if (isset($_GET['manufacturer_search']) && $_GET['manufacturer_search'] !== "") {
        $manufacturer_search = $_GET['manufacturer_search'];
        $default_search .= " AND manufacturer LIKE ?";
        $sql_search .= " AND manufacturer LIKE ?";
        array_push($default_params, 's');
        array_push($default_vars, $manufacturer_search);
        array_push($params, 's');
        array_push($vars, $manufacturer_search);
        $actual_link .= '&manufacturer_search=' . $manufacturer_search;
    }

    // Type
    if (isset($_GET['type_search']) && $_GET['type_search'] !== "") {
        $type_search = $_GET['type_search'];
        $default_search .= " AND type LIKE ?";
        $sql_search .= " AND type LIKE ?";
        array_push($default_params, 's');
        array_push($default_vars, $type_search);
        array_push($params, 's');
        array_push($vars, $type_search);
        $actual_link .= '&type_search=' . $type_search;
    }

    // Driver license
    if (isset($_GET['driver_license_search']) && $_GET['driver_license_search'] !== "") {
        $driver_license_search = $_GET['driver_license_search'];
        $default_search .= " AND license LIKE ?";
        $sql_search .= " AND license LIKE ?";
        array_push($default_params, 's');
        array_push($default_vars, $driver_license_search);
        array_push($params, 's');
        array_push($vars, $driver_license_search);
        $actual_link .= '&driver_license_search=' . $driver_license_search;
    }

    // Minimum year
    if (isset($_GET['min_year']) && $_GET['min_year'] !== "") {
        $min_year = $_GET['min_year'];
        $default_search .= " AND year >= ?";
        $sql_search .= " AND year >= ?";
        array_push($default_params, 'i');
        array_push($default_vars, $min_year);
        array_push($params, 'i');
        array_push($vars, $min_year);
        $actual_link .= '&min_year=' . $min_year;
    }

    // Maximum year
    if (isset($_GET['max_year']) && $_GET['max_year'] !== "") {
        $max_year = $_GET['max_year'];
        $default_search .= " AND year <= ?";
        $sql_search .= " AND year <= ?";
        array_push($default_params, 'i');
        array_push($default_vars, $max_year);
        array_push($params, 'i');
        array_push($vars, $max_year);
        $actual_link .= '&max_year=' . $max_year;
    }

    // County
    if (isset($_GET['county_search']) && $_GET['county_search'] !== "") {
        $county_search = $_GET['county_search'];
        $default_search .= " AND county LIKE ?";
        $sql_search .= " AND county LIKE ?";
        array_push($default_params, 's');
        array_push($default_vars, $county_search);
        array_push($params, 's');
        array_push($vars, $county_search);
        $actual_link .= '&county_search=' . $county_search;
    }

    // Minimum price
    if (isset($_GET['min_price']) && $_GET['min_price'] !== "") {
        $min_price = $_GET['min_price'];
        $default_search .= " AND price >= ?";
        $sql_search .= " AND price >= ?";
        array_push($default_params, 'i');
        array_push($default_vars, $min_price);
        array_push($params, 'i');
        array_push($vars, $min_price);
        $actual_link .= '&min_price=' . $min_price;
    }

    // Maximum price
    if (isset($_GET['max_price']) && $_GET['max_price'] !== "") {
        $max_price = $_GET['max_price'];
        $default_search .= " AND price <= ?";
        $sql_search .= " AND price <= ?";
        array_push($default_params, 'i');
        array_push($default_vars, $max_price);
        array_push($params, 'i');
        array_push($vars, $max_price);
        $actual_link .= '&max_price=' . $max_price;
    }

    // Minimum ccm
    if (isset($_GET['min_ccm']) && $_GET['min_ccm'] !== "") {
        $min_ccm = $_GET['min_ccm'];
        $default_search .= " AND performance >= ?";
        $sql_search .= " AND performance >= ?";
        array_push($default_params, 'i');
        array_push($default_vars, $min_ccm);
        array_push($params, 'i');
        array_push($vars, $min_ccm);
        $actual_link .= '&min_ccm=' . $min_ccm;
    }

    // Maximum ccm
    if (isset($_GET['max_ccm']) && $_GET['max_ccm'] !== "") {
        $max_ccm = $_GET['max_ccm'];
        $default_search .= " AND performance <= ?";
        $sql_search .= " AND performance <= ?";
        array_push($default_params, 'i');
        array_push($default_vars, $max_ccm);
        array_push($params, 'i');
        array_push($vars, $max_ccm);
        $actual_link .= '&max_ccm=' . $max_ccm;
    }

    $default_search .= " ORDER BY u.premium DESC";

    if (isset($_GET['orderby']) && $_GET['orderby'] == 1) {
        $default_search .= ", price ASC";
        $actual_link .= '&orderby=1';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 2) {
        $default_search .= ", price DESC";
        $actual_link .= '&orderby=2';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 3) {
        $default_search .= ", manufacturer ASC";
        $actual_link .= '&orderby=3';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 4) {
        $default_search .= ", manufacturer DESC";
        $actual_link .= '&orderby=4';
    }

    $status = 1;

    $default_stmt = $link->prepare($default_search);
    $default_stmt->bind_param(implode("", $default_params), ...$default_vars);
    $default_stmt->execute();
    $default_result = $default_stmt->get_result();
    $number_of_results = mysqli_num_rows($default_result);

    // How many results per page
    if (isset($_GET['results'])) {
        $results_per_page = $_GET['results'];
        switch ($_GET['results']) {
            case 10:
                $actual_link .= '&results=10';
                break;
            case 25:
                $actual_link .= '&results=25';
                break;
            case 50:
                $actual_link .= '&results=50';
                break;
            case 100:
                $actual_link .= '&results=100';
                break;
        }
    } else {
        $results_per_page = 25;
    }

    // Set the number of pages
    $number_of_pages = ceil($number_of_results / $results_per_page);
    $_SESSION['number_of_pages'] = $number_of_pages;

    if (!isset($_GET['page']) || $_GET['page'] < 1) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    // Check if current page is bigger than the number of pages
    if ($page > $number_of_pages) {
        $page = $number_of_pages;
    }

    // sql LIMIT starting number for the results on the displaying page
    $this_page_first_result = ($page - 1) * $results_per_page;


    $_SESSION['actual_link'] = $actual_link;

    if (isset($_GET['orderby']) && $_GET['orderby'] == 1) {
        $sql_search .= " ORDER BY u.premium, price ASC LIMIT ?, ?";
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 2) {
        $sql_search .= " ORDER BY u.premium, price DESC LIMIT ?, ?";
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 3) {
        $sql_search .= " ORDER BY u.premium, manufacturer ASC LIMIT ?, ?";
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 4) {
        $sql_search .= " ORDER BY u.premium, manufacturer DESC LIMIT ?, ?";
    } else {
        $sql_search .= " ORDER BY u.premium DESC LIMIT ?, ?";
    }

    array_push($params, 'ii');
    array_push($vars, $this_page_first_result, $results_per_page);

    $stmt_search = $link->prepare($sql_search);
    $stmt_search->bind_param(implode("", $params), ...$vars);
    $stmt_search->execute();
    $result = $stmt_search->get_result();
}
