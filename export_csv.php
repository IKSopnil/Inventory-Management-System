<?php
require_once('includes/load.php');
// Check user level
page_require_level(2);

$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type == 'products') {
    $data = join_product_table();
    $filename = "products_" . date('Ymd') . ".csv";
    $header = array("ID", "Product Name", "Category", "Quantity", "Buying Price", "Selling Price", "Date Added");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    fputcsv($output, $header);
    foreach ($data as $row) {
        fputcsv($output, array($row['id'], $row['name'], $row['categorie'], $row['quantity'], $row['buy_price'], $row['sale_price'], $row['date']));
    }
    fclose($output);
    exit;

} elseif ($type == 'sales') {
    page_require_level(3);
    $data = find_all_sale();
    $filename = "sales_" . date('Ymd') . ".csv";
    $header = array("ID", "Product Name", "Quantity", "Total Price", "Date");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    fputcsv($output, $header);
    foreach ($data as $row) {
        fputcsv($output, array($row['id'], $row['name'], $row['qty'], $row['price'], $row['date']));
    }
    fclose($output);
    exit;

} elseif ($type == 'categories') {
    $data = find_all('categories');
    $filename = "categories_" . date('Ymd') . ".csv";
    $header = array("ID", "Category Name");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    fputcsv($output, $header);
    foreach ($data as $row) {
        fputcsv($output, array($row['id'], $row['name']));
    }
    fclose($output);
    exit;

} elseif ($type == 'groups') {
    page_require_level(1);
    $data = find_all('user_groups');
    $filename = "user_groups_" . date('Ymd') . ".csv";
    $header = array("ID", "Group Name", "Group Level", "Status");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    fputcsv($output, $header);
    foreach ($data as $row) {
        fputcsv($output, array($row['id'], $row['group_name'], $row['group_level'], $row['group_status']));
    }
    fclose($output);
    exit;

} elseif ($type == 'users') {
    page_require_level(1);
    $data = find_all_user();
    $filename = "users_" . date('Ymd') . ".csv";
    $header = array("ID", "Name", "Username", "User Role", "Status", "Last Login");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    fputcsv($output, $header);
    foreach ($data as $row) {
        fputcsv($output, array($row['id'], $row['name'], $row['username'], $row['group_name'], $row['status'], $row['last_login']));
    }
    fclose($output);
    exit;

} else {
    redirect('home.php');
}
?>