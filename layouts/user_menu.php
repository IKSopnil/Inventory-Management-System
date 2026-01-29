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