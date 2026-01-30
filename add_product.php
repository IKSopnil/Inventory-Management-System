<?php
$page_title = 'Add Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');
?>
<?php
if (isset($_POST['add_product'])) {
  $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
  validate_fields($req_fields);
  if (empty($errors)) {
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = remove_junk($db->escape($_POST['product-categorie']));
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['saleing-price']));
    if (isset($_FILES['product-photo-file']) && $_FILES['product-photo-file']['error'] == 0) {
      $photo = new Media();
      $photo->upload($_FILES['product-photo-file']);
      if ($photo->process_media()) {
        $media_id = $db->insert_id();
      } else {
        $session->msg('d', join($photo->errors));
        redirect('add_product.php', false);
      }
    } elseif (isset($_POST['product-photo']) && $_POST['product-photo'] !== "") {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    } else {
      $media_id = '0';
    }

    $date = make_date();
    $query = "INSERT INTO products (";
    $query .= " name,quantity,buy_price,sale_price,categorie_id,media_id,date";
    $query .= ") VALUES (";
    $query .= " '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}'";
    $query .= ")";
    $query .= " ON DUPLICATE KEY UPDATE name='{$p_name}'";
    if ($db->query($query)) {
      $session->msg('s', "Product added ");
      redirect('add_product.php', false);
    } else {
      $session->msg('d', ' Sorry failed to added!');
      redirect('product.php', false);
    }

  } else {
    $session->msg("d", $errors);
    redirect('add_product.php', false);
  }

}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined">add_circle</span>
          <span>Add New Product</span>
        </strong>
        <div class="pull-right">
          <a href="product.php" class="btn btn-default btn-xs" title="Back" data-toggle="tooltip">
            <span class="material-symbols-outlined">arrow_back</span>
          </a>
        </div>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix" enctype="multipart/form-data">
            <div class="form-group">
              <label>Product Title</label>
              <input type="text" class="form-control" name="product-title" placeholder="e.g. Wireless Mouse">
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <label>Category</label>
                  <select class="form-control" name="product-categorie">
                    <option value="">Select Category</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int) $cat['id'] ?>">
                        <?php echo $cat['name'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Media / Image</label>
                  <div class="media-select-wrapper" style="display:flex; flex-direction: column; gap: 10px;">
                    <div style="display:flex; align-items:center;">
                      <select class="form-control" name="product-photo" id="product-photo-select" style="flex-grow:1;">
                        <option value="">Select Existing Photo</option>
                        <?php foreach ($all_photo as $photo): ?>
                          <option value="<?php echo (int) $photo['id'] ?>"
                            data-filename="<?php echo $photo['file_name'] ?>">
                            <?php echo $photo['file_name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <img id="media-dropdown-preview" src="uploads/products/no_image.png"
                        style="width: 44px; height: 44px; object-fit: cover; margin-left:10px; border-radius:8px; border:1px solid var(--border-color);">
                    </div>
                    <div class="help-block" style="margin: 0; font-size: 12px; color: #666;">
                      <?php echo __('upload_new'); ?>
                    </div>
                    <input type="file" name="product-photo-file" class="btn btn-default btn-file" accept="image/*" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <label>Initial Quantity</label>
                  <input type="number" class="form-control" name="product-quantity" placeholder="0">
                </div>
                <div class="col-md-4">
                  <label>Buying Price ($)</label>
                  <input type="number" step="0.01" class="form-control" name="buying-price" placeholder="0.00">
                </div>
                <div class="col-md-4">
                  <label>Selling Price ($)</label>
                  <input type="number" step="0.01" class="form-control" name="saleing-price" placeholder="0.00">
                </div>
              </div>
            </div>

            <div class="text-right mt-10">
              <a href="product.php" class="btn btn-default"><?php echo __('cancel'); ?></a>
              <button type="submit" name="add_product" class="btn btn-primary btn-lg">
                <span class="material-symbols-outlined">save</span> <?php echo __('save_product'); ?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>