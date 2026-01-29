<?php
$page_title = 'Edit product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>
<?php
$product = find_by_id('products', (int) $_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if (!$product) {
  $session->msg("d", "Missing product id.");
  redirect('product.php');
}
?>
<?php
if (isset($_POST['product'])) {
  $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = (int) $_POST['product-categorie'];
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['saleing-price']));
    if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
      $media_id = '0';
    } else {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    }
    $query = "UPDATE products SET";
    $query .= " name ='{$p_name}', quantity ='{$p_qty}',";
    $query .= " buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}',media_id='{$media_id}'";
    $query .= " WHERE id ='{$product['id']}'";
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Product updated ");
      redirect('product.php', false);
    } else {
      $session->msg('d', ' Sorry failed to updated!');
      redirect('edit_product.php?id=' . $product['id'], false);
    }

  } else {
    $session->msg("d", $errors);
    redirect('edit_product.php?id=' . $product['id'], false);
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
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined">edit</span>
          <span>Add New Product</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-7">
          <form method="post" action="edit_product.php?id=<?php echo (int) $product['id'] ?>">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="material-symbols-outlined">category</span>
                </span>
                <input type="text" class="form-control" name="product-title"
                  value="<?php echo remove_junk($product['name']); ?>">
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control" name="product-categorie">
                    <option value=""> Select a categorie</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int) $cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']):
                            echo "selected";
                          endif; ?>>
                        <?php echo remove_junk($cat['name']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <div class="media-select-wrapper" style="display:flex; align-items:center;">
                    <select class="form-control" name="product-photo" id="product-photo-select" style="flex-grow:1;">
                      <option value=""> No image</option>
                      <?php foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int) $photo['id']; ?>"
                          data-filename="<?php echo $photo['file_name'] ?>" <?php if ($product['media_id'] === $photo['id']):
                               echo "selected";
                             endif; ?>>
                          <?php echo $photo['file_name'] ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <?php
                    $p_media = find_by_id('media', (int) $product['media_id']);
                    $p_image = ($p_media) ? $p_media['file_name'] : 'no_image.png';
                    ?>
                    <img id="media-dropdown-preview" src="uploads/products/<?php echo $p_image; ?>"
                      style="width: 44px; height: 44px; object-fit: cover; margin-left:10px; border-radius:8px; border:1px solid var(--border-color);">
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Quantity</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <span class="material-symbols-outlined">shopping_cart</span>
                      </span>
                      <input type="number" class="form-control" name="product-quantity"
                        value="<?php echo remove_junk($product['quantity']); ?>">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Buying price</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <span class="material-symbols-outlined">attach_money</span>
                      </span>
                      <input type="number" class="form-control" name="buying-price"
                        value="<?php echo remove_junk($product['buy_price']); ?>">
                      <span class="input-group-addon">.00</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Selling price</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <span class="material-symbols-outlined">attach_money</span>
                      </span>
                      <input type="number" class="form-control" name="saleing-price"
                        value="<?php echo remove_junk($product['sale_price']); ?>">
                      <span class="input-group-addon">.00</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-right mt-10">
              <a href="product.php" class="btn btn-default"><?php echo __('cancel'); ?></a>
              <button type="submit" name="product" class="btn btn-danger"><?php echo __('update'); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include_once('layouts/footer.php'); ?>