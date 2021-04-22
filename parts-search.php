<?php
if (empty($_SESSION['parts_actual_link'])) {
    $_SESSION['parts_actual_link'] = "alkatreszek.php?";
}

$parts_actual_link = 'alkatreszek.php?';

if (
    isset($_GET['product_name']) || isset($_GET['county_search']) || isset($_GET['min_price'])
    || isset($_GET['max_price']) || isset($_GET['results']) || isset($_GET['orderby'])
) {
    $default_params[] = "i";
    $default_vars[] = $status;
    $params[] = "i";
    $vars[] = $status;
    $sql_search = 'SELECT p.*, u.premium, u.avatar FROM parts p INNER JOIN users u ON u.id = p.userid WHERE status = ?';
    $default_search =  'SELECT p.*, u.premium, u.avatar FROM parts p INNER JOIN users u ON u.id = p.userid WHERE status = ?';

    // Product name
    if (isset($_GET['product_name']) && $_GET['product_name'] !== "") {
        $product_name = $_GET['product_name'];
        $default_search .= " AND product_name LIKE ?";
        $sql_search .= " AND product_name LIKE ?";
        array_push($default_params, 's');
        array_push($default_vars, $product_name);
        array_push($params, 's');
        array_push($vars, $product_name);
        $parts_actual_link .= '&product_name=' . $product_name;
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
        $parts_actual_link .= '&county_search=' . $county_search;
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
        $parts_actual_link .= '&min_price=' . $min_price;
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
        $parts_actual_link .= '&max_price=' . $max_price;
    }

    $default_search .= " ORDER BY u.premium DESC";

    if (isset($_GET['orderby']) && $_GET['orderby'] == 1) {
        $default_search .= ", price ASC";
        $parts_actual_link .= '&orderby=1';
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 2) {
        $default_search .= ", price DESC";
        $parts_actual_link .= '&orderby=2';
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
                $parts_actual_link .= '&results=10';
                break;
            case 25:
                $parts_actual_link .= '&results=25';
                break;
            case 50:
                $parts_actual_link .= '&results=50';
                break;
            case 100:
                $parts_actual_link .= '&results=100';
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


    $_SESSION['parts_actual_link'] = $parts_actual_link;

    if (isset($_GET['orderby']) && $_GET['orderby'] == 1) {
        $sql_search .= " ORDER BY u.premium, price ASC LIMIT ?, ?";
    } elseif (isset($_GET['orderby']) && $_GET['orderby'] == 2) {
        $sql_search .= " ORDER BY u.premium, price DESC LIMIT ?, ?";
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
