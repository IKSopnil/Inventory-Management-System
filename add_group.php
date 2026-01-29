<?php
$page_title = 'Add Group';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
?>
<?php
if (isset($_POST['add'])) {

  $req_fields = array('group-name', 'group-level');
  validate_fields($req_fields);

  if (find_by_groupName($_POST['group-name']) === false) {
    $session->msg('d', '<b>Sorry!</b> Entered Group Name already in database!');
    redirect('add_group.php', false);
  } elseif (find_by_groupLevel($_POST['group-level']) === false) {
    $session->msg('d', '<b>Sorry!</b> Entered Group Level already in database!');
    redirect('add_group.php', false);
  }
  if (empty($errors)) {
    $name = remove_junk($db->escape($_POST['group-name']));
    $level = remove_junk($db->escape($_POST['group-level']));
    $status = remove_junk($db->escape($_POST['status']));

    $query = "INSERT INTO user_groups (";
    $query .= "group_name,group_level,group_status";
    $query .= ") VALUES (";
    $query .= " '{$name}', '{$level}','{$status}'";
    $query .= ")";
    if ($db->query($query)) {
      //sucess
      $session->msg('s', "Group has been creted! ");
      redirect('add_group.php', false);
    } else {
      //failed
      $session->msg('d', ' Sorry failed to create Group!');
      redirect('add_group.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_group.php', false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined">group_add</span>
          <span>Add New Group</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_group.php" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Group Name</label>
            <input type="name" class="form-control" name="group-name" placeholder="e.g. Sales Team">
          </div>
          <div class="form-group">
            <label for="level" class="control-label">Group Level</label>
            <select class="form-control" name="group-level">
              <option value="1">Level 1 - Admin Access</option>
              <option value="2">Level 2 - Inventory Access</option>
              <option value="3">Level 3 - Sales Access</option>
            </select>
            <p class="help-block text-muted" style="font-size:12px; margin-top:5px;">Select the appropriate permission
              level.</p>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status">
              <option value="1">Active</option>
              <option value="0">Deactive</option>
            </select>
          </div>
          <div class="form-group clearfix text-right">
            <a href="group.php" class="btn btn-default"><?php echo __('cancel'); ?></a>
            <button type="submit" name="add" class="btn btn-primary"><?php echo __('create_group'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="material-symbols-outlined">info</span>
          <span>Access Level Guide</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="list-group">
          <div class="list-group-item">
            <h4 class="list-group-item-heading text-primary" style="font-weight:600;">Level 1: Admin Access</h4>
            <p class="list-group-item-text text-muted">
              Complete access to all system features. Admins can manage users, groups, settings, reports, invalid
              categories, and all other modules.
            </p>
          </div>
          <div class="list-group-item">
            <h4 class="list-group-item-heading text-primary" style="font-weight:600;">Level 2: Inventory Access</h4>
            <p class="list-group-item-text text-muted">
              Designed for Inventory Managers. Grants access to:
            <ul style="padding-left: 20px; font-size: 13px; color: #64748b;">
              <li>Dashboard</li>
              <li>Product Management (Add/Edit)</li>
              <li>Category Management</li>
              <li>Media Files</li>
            </ul>
            <strong>Restricted from:</strong> Sales, Reports, and User Management.
            </p>
          </div>
          <div class="list-group-item">
            <h4 class="list-group-item-heading text-primary" style="font-weight:600;">Level 3: Sales Access</h4>
            <p class="list-group-item-text text-muted">
              Designed for Sales Agents/Cashiers. Grants access to:
            <ul style="padding-left: 20px; font-size: 13px; color: #64748b;">
              <li>Dashboard</li>
              <li>Sales Management (Add/Edit)</li>
              <li>Daily & Monthly Sales Reports</li>
            </ul>
            <strong>Restricted from:</strong> Product/Inventory modification and System Settings.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>