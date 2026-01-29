<ul>
  <li>
    <a href="home.php">
      <span class="material-symbols-outlined">dashboard</span>
      <span>
        <?php echo __('dashboard'); ?>
      </span>
    </a>
  </li>
  <li>
    <a href="categorie.php">
      <span class="material-symbols-outlined">category</span>
      <span>
        <?php echo __('categories'); ?>
      </span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <span class="material-symbols-outlined">inventory_2</span>
      <span>
        <?php echo __('products'); ?>
      </span>
    </a>
    <ul class="nav submenu">
      <li><a href="product.php">
          <?php echo __('manage_product'); ?>
        </a> </li>
      <li><a href="add_product.php">
          <?php echo __('add_product'); ?>
        </a> </li>
    </ul>
  </li>
  <li>
    <a href="media.php">
      <span class="material-symbols-outlined">perm_media</span>
      <span>
        <?php echo __('media'); ?>
      </span>
    </a>
  </li>
</ul>