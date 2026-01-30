<?php
$page_title = 'All sale';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php
$sales = find_all_sale();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="material-symbols-outlined">shopping_cart</span>
          <span><?php echo __('all_sales'); ?></span>
        </strong>
        <div class="pull-right">
          <a href="add_sale.php" class="btn btn-primary"><?php echo __('add_sale'); ?></a>
          <a href="export_csv.php?type=sales" class="btn btn-success"><span
              class="material-symbols-outlined">file_download</span> <?php echo __('export_csv'); ?></a>
        </div>
      </div>
      <div class="panel-body">
        <div class="list-filter-container">
          <div class="input-group">
            <span class="input-group-addon"><span class="material-symbols-outlined">search</span></span>
            <input type="text" id="sales-search" class="form-control"
              placeholder="Search sales by product name or date...">
            <span class="input-group-btn">
              <button class="btn btn-primary" type="button" id="sales-search-btn">Go</button>
            </span>
          </div>
        </div>
        <table class="table table-bordered table-striped" id="sales-table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th style="width: 80px;"><?php echo __('photo'); ?></th>
              <th> <?php echo __('product_name_label'); ?> </th>
              <th class="text-center" style="width: 15%;"> <?php echo __('quantity'); ?></th>
              <th class="text-center" style="width: 15%;"> <?php echo __('total'); ?> </th>
              <th class="text-center" style="width: 15%;"> <?php echo __('date'); ?> </th>
              <th class="text-center" style="width: 100px;"> <?php echo __('actions'); ?> </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td>
                  <?php if ($sale['media_id'] === '0'): ?>
                    <img class="img-avatar" src="uploads/products/no_image.png" alt="" style="border-radius:8px;">
                  <?php else: ?>
                    <img class="img-avatar" src="uploads/products/<?php echo $sale['image']; ?>" alt=""
                      style="border-radius:8px;">
                  <?php endif; ?>
                </td>
                <td><?php echo remove_junk($sale['name']); ?></td>
                <td class="text-center"><?php echo (int) $sale['qty']; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
                <td class="text-center"><?php echo $sale['date']; ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <?php
                    $existing_invoice = $invoice_obj->find_by_sale_id($sale['id']);
                    if ($existing_invoice): ?>
                      <a href="print_invoice.php?id=<?php echo (int) $existing_invoice['id']; ?>"
                        class="btn btn-info btn-xs" title="View Invoice" data-toggle="tooltip" target="_blank">
                        <span class="material-symbols-outlined">receipt_long</span>
                      </a>
                    <?php else: ?>
                      <a href="add_invoice.php?sale_id=<?php echo (int) $sale['id']; ?>" class="btn btn-primary btn-xs"
                        title="Convert to Invoice" data-toggle="tooltip">
                        <span class="material-symbols-outlined">description</span>
                      </a>
                    <?php endif; ?>
                    <a href="edit_sale.php?id=<?php echo (int) $sale['id']; ?>" class="btn btn-warning btn-xs"
                      title="Edit" data-toggle="tooltip">
                      <span class="material-symbols-outlined">edit</span>
                    </a>
                    <a href="delete_sale.php?id=<?php echo (int) $sale['id']; ?>" class="btn btn-danger btn-xs"
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