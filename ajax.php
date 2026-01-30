<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn()) {
  redirect('index.php', false);
}
?>

<?php
// Auto suggetion
$html = '';
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
  $products = find_product_by_title($_POST['product_name']);
  if ($products) {
    foreach ($products as $product):
      $html .= "<li class=\"list-group-item\">";
      $html .= $product['name'];
      $html .= "</li>";
    endforeach;
  } else {

    $html .= '<li class="list-group-item">Not found</li>';

  }

  echo json_encode($html);
}
?>
<?php
// Sales Express Analytics
if (isset($_POST['stats_range'])) {
  $range = $_POST['stats_range'];
  $stats = find_sales_analytics($range);
  $labels = [];
  $totals = [];

  foreach ($stats as $s) {
    $labels[] = $s['label'];
    $totals[] = (float) $s['total'];
  }

  echo json_encode([
    'labels' => $labels,
    'totals' => $totals
  ]);
}
?>
<?php
// find all product
if ((isset($_POST['p_name']) && strlen($_POST['p_name'])) || (isset($_POST['product_id']) && strlen($_POST['product_id']))) {
  if (isset($_POST['p_name'])) {
    $product_title = remove_junk($db->escape($_POST['p_name']));
    $results = find_all_product_info_by_title($product_title);
  } else {
    $product_id = (int) $_POST['product_id'];
    $results = find_product_info_by_id($product_id);
  }

  if ($results) {
    foreach ($results as $result) {

      $html .= "<tr>";

      $html .= "<td id=\"s_name\">" . $result['name'] . "</td>";
      $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";
      $html .= "<td>";
      $html .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['sale_price']}\">";
      $html .= "</td>";
      $html .= "<td id=\"s_qty\">";
      $html .= "<input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\">";
      $html .= "</td>";
      $html .= "<td>";
      $html .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\">";
      $html .= "</td>";
      $html .= "<td>";
      $html .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">";
      $html .= "</td>";
      $html .= "<td>";
      $html .= "<button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Add sale</button>";
      $html .= "</td>";
      $html .= "</tr>";

    }
  } else {
    $html = '<tr><td>product name not resgister in database</td></tr>';
  }

  echo json_encode($html);
}
?>