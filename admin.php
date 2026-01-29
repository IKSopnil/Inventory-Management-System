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
    <a href="users.php" class="panel-box">
      <div class="panel-icon bg-blue2">
        <span class="material-symbols-outlined">person_search</span>
      </div>
      <div class="panel-value">
        <h2> <?php echo $c_user['total']; ?> </h2>
        <p><?php echo __('total_users'); ?></p>
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="categorie.php" class="panel-box">
      <div class="panel-icon bg-red">
        <span class="material-symbols-outlined">label</span>
      </div>
      <div class="panel-value">
        <h2> <?php echo $c_categorie['total']; ?> </h2>
        <p><?php echo __('total_categories'); ?></p>
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="product.php" class="panel-box">
      <div class="panel-icon bg-blue">
        <span class="material-symbols-outlined">inventory</span>
      </div>
      <div class="panel-value">
        <h2> <?php echo $c_product['total']; ?> </h2>
        <p><?php echo __('total_products'); ?></p>
      </div>
    </a>
  </div>

  <div class="col-md-3">
    <a href="sales.php" class="panel-box">
      <div class="panel-icon bg-green">
        <span class="material-symbols-outlined">payments</span>
      </div>
      <div class="panel-value">
        <h2> <?php echo $c_sale['total']; ?></h2>
        <p><?php echo __('total_sales'); ?></p>
      </div>
    </a>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-left">
          <strong>
            <span class="material-symbols-outlined text-success">analytics</span>
            <span><?php echo __('sales_analytics'); ?></span>
          </strong>
        </div>
        <div class="pull-right">
          <select id="chart-range" class="form-control input-sm" style="width: 120px; border-radius: 20px;">
            <option value="7d"><?php echo __('last_7_days'); ?></option>
            <option value="30d"><?php echo __('last_30_days'); ?></option>
            <option value="6m"><?php echo __('last_6_months'); ?></option>
            <option value="1y"><?php echo __('last_1_year'); ?></option>
          </select>
        </div>
      </div>
      <div class="panel-body">
        <canvas id="salesChart" style="width: 100%; height: 300px;"></canvas>
      </div>
    </div>
  </div>
</div>

<?php
$sales_data = find_sales_analytics('7d');
$labels = [];
$totals = [];
foreach ($sales_data as $data) {
  $labels[] = $data['label'];
  $totals[] = (float) $data['total'];
}
?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const isDark = document.body.classList.contains('dark-mode');

    let salesChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
          label: '<?php echo __('total_sales'); ?> ($)',
          data: <?php echo json_encode($totals); ?>,
          borderColor: '#d4af37',
          backgroundColor: 'rgba(212, 175, 55, 0.1)',
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#d4af37',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: isDark ? '#1e293b' : '#fff',
            titleColor: isDark ? '#f8fafc' : '#1e293b',
            bodyColor: isDark ? '#f8fafc' : '#1e293b',
            borderColor: '#e2e8f0',
            borderWidth: 1,
            padding: 12,
            displayColors: false,
            callbacks: {
              label: function (context) {
                return '$' + context.parsed.y.toLocaleString();
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: isDark ? '#94a3b8' : '#64748b',
              font: {
                family: "'Inter', sans-serif",
                size: 11
              }
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
              borderDash: [5, 5]
            },
            ticks: {
              color: isDark ? '#94a3b8' : '#64748b',
              font: {
                family: "'Inter', sans-serif",
                size: 11
              },
              callback: function (value) {
                return '$' + value;
              }
            }
          }
        }
      }
    });

    // Range Switcher
    $('#chart-range').on('change', function () {
      const range = $(this).val();
      $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {
          stats_range: range
        },
        dataType: 'json',
        success: function (res) {
          salesChart.data.labels = res.labels;
          salesChart.data.datasets[0].data = res.totals;
          salesChart.update();
        }
      });
    });

    // Handle theme toggle chart update
    const themeObserver = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        if (mutation.attributeName === "class") {
          const isDarkNow = document.body.classList.contains('dark-mode');
          salesChart.options.scales.y.grid.color = isDarkNow ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
          salesChart.options.scales.x.ticks.color = isDarkNow ? '#94a3b8' : '#64748b';
          salesChart.options.scales.y.ticks.color = isDarkNow ? '#94a3b8' : '#64748b';
          salesChart.options.plugins.tooltip.backgroundColor = isDarkNow ? '#1e293b' : '#fff';
          salesChart.options.plugins.tooltip.titleColor = isDarkNow ? '#f8fafc' : '#1e293b';
          salesChart.options.plugins.tooltip.bodyColor = isDarkNow ? '#f8fafc' : '#1e293b';
          salesChart.update();
        }
      });
    });

    themeObserver.observe(document.body, {
      attributes: true
    });
  });
</script>

<div class="row">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined text-danger">trending_up</span>
          <span><?php echo __('highest_selling_products'); ?></span>
        </strong>
      </div>
      <div class="panel-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th><?php echo __('product'); ?></th>
              <th class="text-center"><?php echo __('sold'); ?></th>
              <th class="text-center"><?php echo __('stock'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as $product_sold): ?>
              <tr>
                <td style="font-weight: 500;"><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
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
          <span><?php echo __('latest_sales'); ?></span>
        </strong>
      </div>
      <div class="panel-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th><?php echo __('item_name'); ?></th>
              <th><?php echo __('sold_date'); ?></th>
              <th><?php echo __('amount'); ?></th>
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
          <span><?php echo __('recently_added_products'); ?></span>
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
                <p class="m-0" style="font-weight:600; color:var(--text-main); font-size:14px; line-height:1.2;">
                  <?php echo remove_junk(first_character($recent_product['name'])); ?>
                </p>
                <small class="text-muted"
                  style="font-weight:400; font-size: 11px;"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></small>
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