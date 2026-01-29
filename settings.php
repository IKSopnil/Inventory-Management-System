<?php
require_once('includes/load.php');
page_require_level(1);

$settings = $invoice_obj->get_settings();

if (isset($_POST['update_settings'])) {
    $name = remove_junk($db->escape($_POST['name']));
    $address = remove_junk($db->escape($_POST['address']));
    $phone = remove_junk($db->escape($_POST['phone']));

    $logo_name = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['name'] != "") {
        $target_dir = "uploads/settings/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $logo_name = basename($_FILES["logo"]["name"]);
        $target_file = $target_dir . $logo_name;
        move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
    }

    if ($invoice_obj->update_settings($name, $address, $phone, $logo_name)) {
        $session->msg('s', "Settings updated successfully!");
        redirect('settings.php', false);
    } else {
        $session->msg('d', 'Failed to update settings.');
        redirect('settings.php', false);
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
                    <span class="material-symbols-outlined">settings</span>
                    <span>Business Settings</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="settings.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Business Name</label>
                                <input type="text" class="form-control" name="name"
                                    value="<?php echo $settings['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?php echo $settings['phone']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <label>Current Logo</label>
                            <div style="margin-bottom: 15px;">
                                <?php if ($settings['logo']): ?>
                                    <img src="uploads/settings/<?php echo $settings['logo']; ?>" alt="Logo"
                                        id="logo-preview" class="image-preview"
                                        style="max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                                <?php else: ?>
                                    <div
                                        style="height: 100px; line-height: 100px; background: #f9f9f9; border: 1px dashed #ccc;">
                                        No Logo</div>
                                    <img id="logo-preview" class="image-preview" src=""
                                        style="display:none; max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <input type="file" name="logo" class="form-control">
                                <small class="text-muted">Recommended: PNG with transparent background</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" name="address"
                            rows="4"><?php echo $settings['address']; ?></textarea>
                    </div>
                    <div class="form-group text-right">
                        <a href="admin.php" class="btn btn-default"><?php echo __('cancel'); ?></a>
                        <button type="submit" name="update_settings"
                            class="btn btn-primary"><?php echo __('save_settings'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>