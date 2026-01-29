<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
$all_sales = find_all_sale();
$get_sale_id = isset($_GET['sale_id']) ? (int) $_GET['sale_id'] : '';
?>
<?php
if (isset($_POST['add_invoice'])) {
    $req_fields = array('customer_name', 'sale_id');
    validate_fields($req_fields);
    if (empty($errors)) {
        $customer_name = remove_junk($db->escape($_POST['customer_name']));
        $customer_address = remove_junk($db->escape($_POST['customer_address']));
        $sale_id = (int) $_POST['sale_id'];

        // Get sale total
        $sql = "SELECT price FROM sales WHERE id='{$sale_id}' LIMIT 1";
        $result = $db->query($sql);
        $sale = $db->fetch_assoc($result);
        $total_amount = $sale['price'];

        $data = array(
            'customer_name' => $customer_name,
            'customer_address' => $customer_address,
            'sale_id' => $sale_id,
            'total_amount' => $total_amount
        );

        if ($invoice_obj->create($data)) {
            $session->msg('s', "Invoice generated successfully! ");
            redirect('invoices.php', false);
        } else {
            $session->msg('d', ' Sorry failed to generate invoice!');
            redirect('add_invoice.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_invoice.php', false);
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
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="material-symbols-outlined">add</span>
                    <span>Generate New Invoice</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_invoice.php" class="clearfix">
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" placeholder="Customer Name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="customer_address">Address</label>
                        <textarea class="form-control" name="customer_address" rows="3"
                            placeholder="Customer Address"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="sale_id">Select Sale Record</label>
                        <select class="form-control" name="sale_id" required>
                            <option value="">Select Sale</option>
                            <?php foreach ($all_sales as $sale): ?>
                                <option value="<?php echo (int) $sale['id']; ?>" <?php if ($get_sale_id == $sale['id'])
                                        echo 'selected'; ?>>
                                    Sale ID:
                                    <?php echo (int) $sale['id']; ?> - Product:
                                    <?php echo $sale['name']; ?> - Total: $
                                    <?php echo $sale['price']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <a href="invoices.php" class="btn btn-default"><?php echo __('cancel'); ?></a>
                        <button type="submit" name="add_invoice"
                            class="btn btn-primary"><?php echo __('generate_invoice'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>