<?php
$page_title = 'Admin Home Page';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
?>
<?php
$c_categorie = count_by_id('categories');
$c_product = count_by_id('products');
$c_sale = count_by_id('sales');
$c_user = count_by_id('users');
$products_sold = find_higest_saleing_product('10');
$recent_products = find_recent_product_added('5');
$recent_sales = find_recent_sale_added('5')
  ?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <a href="users.php" class="quick-card">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-blue2">
          <span class="material-symbols-outlined">person_search</span>
        </div>
        <div class="panel-value pull-right">
          <h2 class="m-0"> <?php echo $c_user['total']; ?> </h2>
          <p class="text-muted">Active Users</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="categorie.php" class="quick-card">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-red">
          <span class="material-symbols-outlined">label</span>
        </div>
        <div class="panel-value pull-right">
          <h2 class="m-0"> <?php echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">Categories</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="product.php" class="quick-card">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-blue">
          <span class="material-symbols-outlined">inventory</span>
        </div>
        <div class="panel-value pull-right">
          <h2 class="m-0"> <?php echo $c_product['total']; ?> </h2>
          <p class="text-muted">Total Products</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="sales.php" class="quick-card">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-green">
          <span class="material-symbols-outlined">payments</span>
        </div>
        <div class="panel-value pull-right">
          <h2 class="m-0"> <?php echo $c_sale['total']; ?></h2>
          <p class="text-muted">Total Sales</p>
        </div>
      </div>
    </a>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined text-danger">trending_up</span>
          <span>Best Selling Products</span>
        </strong>
      </div>
      <div class="panel-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th>Product</th>
              <th class="text-center">Sold</th>
              <th class="text-center">In-Stock</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as $product_sold): ?>
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td class="text-center"><span
                    class="badge badge-success"><?php echo (int) $product_sold['totalSold']; ?></span></td>
                <td class="text-center"><span
                    class="badge badge-info"><?php echo (int) $product_sold['totalQty']; ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined text-primary">receipt_long</span>
          <span>Recent Sale Records</span>
        </strong>
      </div>
      <div class="panel-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Sold Date</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_sales as $recent_sale): ?>
              <tr>
                <td>
                  <a href="edit_sale.php?id=<?php echo (int) $recent_sale['id']; ?>" style="font-weight:500;">
                    <?php echo remove_junk(first_character($recent_sale['name'])); ?>
                  </a>
                </td>
                <td class="text-muted" style="font-size: 11px;">
                  <?php echo date('d M, Y', strtotime($recent_sale['date'])); ?>
                </td>
                <td><span class="text-success"
                    style="font-weight:700;">$<?php echo remove_junk(first_character($recent_sale['price'])); ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined text-warning">add_circle</span>
          <span>Recently Added Items</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="list-group m-0">
          <?php foreach ($recent_products as $recent_product): ?>
            <a class="list-group-item mb-20"
              style="border:none; padding:0; display:flex; align-items:center; text-decoration:none;"
              href="edit_product.php?id=<?php echo (int) $recent_product['id']; ?>">
              <div style="margin-right:15px; flex-shrink:0;">
                <?php if ($recent_product['media_id'] === '0'): ?>
                  <img class="img-avatar" src="uploads/products/no_image.png" alt=""
                    style="border-radius:10px; width:45px; height:45px; object-fit:cover;">
                <?php else: ?>
                  <img class="img-avatar" src="uploads/products/<?php echo $recent_product['image']; ?>" alt=""
                    style="border-radius:10px; width:45px; height:45px; object-fit:cover;" />
                <?php endif; ?>
              </div>
              <div style="flex-grow:1;">
                <p class="m-0" style="font-weight:700; color:var(--text-main); font-size:14px; line-height:1.2;">
                  <?php echo remove_junk(first_character($recent_product['name'])); ?>
                </p>
                <small class="text-muted"
                  style="font-weight:500;"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></small>
              </div>
              <div style="margin-left:10px; flex-shrink:0;">
                <span class="badge badge-success"
                  style="font-size:12px;">$<?php echo (int) $recent_product['sale_price']; ?></span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>




<?php include_once('layouts/footer.php'); ?>