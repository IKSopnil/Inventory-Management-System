<ul>
  <li>
    <a href="admin.php">
      <span class="material-symbols-outlined">dashboard</span>
      <span>
        <?php echo __('dashboard'); ?>
      </span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <span class="material-symbols-outlined">admin_panel_settings</span>
      <span>
        <?php echo __('user_management'); ?>
      </span>
    </a>
    <ul class="nav submenu">
      <li><a href="group.php">
          <?php echo __('manage_groups'); ?>
        </a> </li>
      <li><a href="users.php">
          <?php echo __('manage_users'); ?>
        </a> </li>
    </ul>
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
  <li>
    <a href="#" class="submenu-toggle">
      <span class="material-symbols-outlined">shopping_cart</span>
      <span>
        <?php echo __('sales'); ?>
      </span>
    </a>
    <ul class="nav submenu">
      <li><a href="sales.php">
          <?php echo __('manage_sales'); ?>
        </a> </li>
      <li><a href="add_sale.php">
          <?php echo __('add_sale'); ?>
        </a> </li>
    </ul>
  </li>
  <li>
    <a href="invoices.php">
      <span class="material-symbols-outlined">receipt_long</span>
      <span>
        <?php echo __('invoices'); ?>
      </span>
    </a>
  </li>
  <li>
    <a href="settings.php">
      <span class="material-symbols-outlined">settings</span>
      <span>
        <?php echo __('settings'); ?>
      </span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <span class="material-symbols-outlined">analytics</span>
      <span>
        <?php echo __('sales_report'); ?>
      </span>
    </a>
    <ul class="nav submenu">
      <li><a href="sales_report.php">
          <?php echo __('sales_by_dates'); ?>
        </a></li>
      <li><a href="monthly_sales.php">
          <?php echo __('monthly_sales'); ?>
        </a></li>
      <li><a href="daily_sales.php">
          <?php echo __('daily_sales'); ?>
        </a> </li>
    </ul>
  </li>
</ul>