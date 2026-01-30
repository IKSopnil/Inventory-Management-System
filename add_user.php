<?php
$page_title = 'Add User';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
$groups = find_all('user_groups');
?>
<?php
if (isset($_POST['add_user'])) {

  $req_fields = array('full-name', 'username', 'password', 'level');
  validate_fields($req_fields);

  if (empty($errors)) {
    $name = remove_junk($db->escape($_POST['full-name']));
    $username = remove_junk($db->escape($_POST['username']));
    $password = remove_junk($db->escape($_POST['password']));

    // Get Group ID and Lookup Level
    $group_id = (int) $db->escape($_POST['level']); // Using 'level' name for dropdown but it sends ID
    $group_info = find_by_id('user_groups', $group_id);
    $user_level = (int) $group_info['group_level'];

    $password = sha1($password);
    $query = "INSERT INTO users (";
    $query .= "name,username,password,user_level,group_id,status";
    $query .= ") VALUES (";
    $query .= " '{$name}', '{$username}', '{$password}', '{$user_level}', '{$group_id}','1'";
    $query .= ")";
    if ($db->query($query)) {
      //sucess
      $session->msg('s', "User account has been creted! ");
      redirect('add_user.php', false);
    } else {
      //failed
      $session->msg('d', ' Sorry failed to create account!');
      redirect('add_user.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_user.php', false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="material-symbols-outlined">person_add</span>
        <span>Add New User</span>
      </strong>
      <div class="pull-right">
        <a href="users.php" class="btn btn-default btn-xs" title="Back" data-toggle="tooltip">
          <span class="material-symbols-outlined">arrow_back</span>
        </a>
      </div>
    </div>
    <div class="panel-body">
      <div class="col-md-6">
        <form method="post" action="add_user.php">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="full-name" placeholder="Full Name">
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password">
          </div>
          <div class="form-group">
            <label for="level">User Role</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group): ?>
                <option value="<?php echo $group['id']; ?>"><?php echo ucwords($group['group_name']); ?> (Level
                  <?php echo $group['group_level']; ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group clearfix text-right">
            <a href="users.php" class="btn btn-default"><?php echo __('cancel'); ?></a>
            <button type="submit" name="add_user" class="btn btn-primary"><?php echo __('add_user'); ?></button>
          </div>
        </form>
      </div>

    </div>

  </div>
</div>

<?php include_once('layouts/footer.php'); ?>