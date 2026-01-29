<?php
$page_title = 'Daily Sales';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>

<?php
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$sales = find_sales_by_day($date);
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
        <div class="pull-left">
          <strong>
            <span class="material-symbols-outlined">calendar_today</span>
            <span><?php echo __('daily_sales'); ?>: <?php echo date('F j, Y', strtotime($date)); ?></span>
          </strong>
        </div>
        <div class="pull-right">
          <form action="daily_sales.php" method="GET" class="form-inline">
            <div class="form-group">
              <input type="text" class="form-control datePicker" name="date" value="<?php echo $date; ?>"
                placeholder="<?php echo __('select_date'); ?>" style="width: 150px; border-radius: 20px;">
            </div>
            <button type="submit" class="btn btn-primary" style="border-radius: 20px;">
              <span class="material-symbols-outlined">search</span>
            </button>
          </form>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> <?php echo __('product_name'); ?> </th>
              <th class="text-center" style="width: 15%;"> <?php echo __('quantity_sold'); ?></th>
              <th class="text-center" style="width: 15%;"> <?php echo __('total'); ?> </th>
              <th class="text-center" style="width: 15%;"> <?php echo __('date'); ?> </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($sale['name']); ?></td>
                <td class="text-center"><?php echo (int) $sale['qty']; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
                <td class="text-center"><?php echo $sale['date']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>