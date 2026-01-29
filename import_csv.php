<?php
require_once('includes/load.php');

$type = isset($_GET['type']) ? $_GET['type'] : 'products';

// Dynamic Page Title & Permissions
$page_title = 'Import ' . ucfirst($type) . ' from CSV';
if ($type === 'groups' || $type === 'users') {
    page_require_level(1);
} else {
    page_require_level(2);
}

if (isset($_POST['submit'])) {
    if (!empty($_FILES['csv_file']['name'])) {
        $filename = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($filename, "r");

        // Skip the first row (header)
        fgetcsv($handle);

        $success_count = 0;
        $error_count = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $sql = "";
            if ($type === 'products') {
                $name = $db->escape($data[0]);
                $cat_id = (int) $data[1];
                $qty = (int) $data[2];
                $buy = $db->escape($data[3]);
                $sale = $db->escape($data[4]);
                $media_id = isset($data[5]) ? (int) $data[5] : 0;
                $date = make_date();
                $sql = "INSERT INTO products (name, quantity, buy_price, sale_price, categorie_id, media_id, date) 
                        VALUES ('{$name}', '{$qty}', '{$buy}', '{$sale}', '{$cat_id}', '{$media_id}', '{$date}')";
            } elseif ($type === 'categories') {
                $cat_name = $db->escape($data[0]);
                $sql = "INSERT INTO categories (name) VALUES ('{$cat_name}')";
            } elseif ($type === 'groups') {
                $group_name = $db->escape($data[0]);
                $level = (int) $data[1];
                $status = (int) $data[2];
                $sql = "INSERT INTO user_groups (group_name, group_level, group_status) VALUES ('{$group_name}', '{$level}', '{$status}')";
            } elseif ($type === 'users') {
                $name = $db->escape($data[0]);
                $username = $db->escape($data[1]);
                $password = sha1($db->escape($data[2])); // Default password support in CSV
                $user_level = (int) $data[3];
                $status = (int) $data[4];
                $sql = "INSERT INTO users (name, username, password, user_level, status) VALUES ('{$name}', '{$username}', '{$password}', '{$user_level}', '{$status}')";
            }

            if ($sql && $db->query($sql)) {
                $success_count++;
            } else {
                $error_count++;
            }
        }
        fclose($handle);
        $session->msg('s', "Successfully imported {$success_count} " . $type . ". Errors: {$error_count}");

        $redirect_page = 'product.php';
        if ($type === 'categories')
            $redirect_page = 'categorie.php';
        if ($type === 'groups')
            $redirect_page = 'group.php';
        if ($type === 'users')
            $redirect_page = 'users.php';

        redirect($redirect_page, false);
    } else {
        $session->msg('d', "Please select a CSV file.");
        redirect('import_csv.php?type=' . $type, false);
    }
}

include_once('layouts/header.php');
?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="material-symbols-outlined">file_upload</span>
                    <span>Import <?php echo ucfirst($type); ?></span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <strong>Expected CSV Order (Column Headers):</strong><br>
                    <?php if ($type === 'products'): ?>
                        Name, Category_ID, Quantity, Buy_Price, Sale_Price, Media_ID
                    <?php elseif ($type === 'categories'): ?>
                        Category Name
                    <?php elseif ($type === 'groups'): ?>
                        Group Name, Level, Status (1/0)
                    <?php elseif ($type === 'users'): ?>
                        Name, Username, Password, User_Level, Status (1/0)
                    <?php endif; ?>
                    <br><small>(The first row will be automatically skipped)</small>
                </div>
                <form method="post" action="import_csv.php?type=<?php echo $type; ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="csv_file">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Upload & Import</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>