<?php
$page_title = 'All Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="add_product.php" class="btn btn-primary">Add New</a>
          <a href="export_csv.php?type=products" class="btn btn-success"><span
              class="material-symbols-outlined">file_download</span> Export CSV</a>
          <a href="import_csv.php" class="btn btn-info"><span class="material-symbols-outlined">file_upload</span>
            Import CSV</a>
        </div>
      </div>
      <div class="panel-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th style="width: 80px;">Photo</th>
              <th> Product </th>
              <th> Category </th>
              <th class="text-center"> Stock </th>
              <th class="text-center"> Cost </th>
              <th class="text-center"> Price </th>
              <th class="text-center"> Added </th>
              <th class="text-center" style="width: 120px;"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td>
                  <?php if ($product['media_id'] === '0'): ?>
                    <img class="img-avatar" src="uploads/products/no_image.png" alt="" style="border-radius:8px;">
                  <?php else: ?>
                    <img class="img-avatar" src="uploads/products/<?php echo $product['image']; ?>" alt=""
                      style="border-radius:8px;">
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?php echo remove_junk($product['name']); ?></strong>
                </td>
                <td> <?php echo remove_junk($product['categorie']); ?></td>
                <td class="text-center">
                  <?php
                  $qty = (int) $product['quantity'];
                  if ($qty <= 0)
                    echo '<span class="badge badge-danger">Out of Stock</span>';
                  elseif ($qty < 10)
                    echo '<span class="badge badge-warning">Low: ' . $qty . '</span>';
                  else
                    echo '<span class="badge badge-success">' . $qty . '</span>';
                  ?>
                </td>
                <td class="text-center"> $<?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"> $<?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center text-muted" style="font-size:11px;">
                  <?php echo date('M d, Y', strtotime($product['date'])); ?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int) $product['id']; ?>" class="btn btn-primary btn-xs"
                      title="Edit" data-toggle="tooltip">
                      <span class="material-symbols-outlined">edit</span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int) $product['id']; ?>" class="btn btn-danger btn-xs"
                      title="Delete" data-toggle="tooltip">
                      <span class="material-symbols-outlined">delete</span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>