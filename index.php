<?php
ob_start();
require_once('includes/load.php');
if ($session->isUserLoggedIn()) {
  redirect('home.php', false);
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-lang-switcher">
  <?php include_once('layouts/language_switcher.php'); ?>
</div>
<div class="login-theme-toggle">
  <?php include_once('layouts/theme_toggle.php'); ?>
</div>
<div class="login-page">
  <div class="text-center">
    <h1><?php echo __('login_panel'); ?></h1>
    <h4><?php echo __('inventory_system'); ?></h4>
  </div>
  <?php echo display_msg($msg); ?>
  <form method="post" action="auth.php" class="clearfix">
    <div class="form-group">
      <label for="username" class="control-label"><?php echo __('username'); ?></label>
      <input type="name" class="form-control" name="username" placeholder="<?php echo __('username'); ?>">
    </div>
    <div class="form-group">
      <label for="Password" class="control-label"><?php echo __('password'); ?></label>
      <input type="password" name="password" class="form-control" placeholder="<?php echo __('password'); ?>">
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-danger" style="border-radius:0%"><?php echo __('login'); ?></button>
    </div>
  </form>
</div>
<?php include_once('layouts/footer.php'); ?>