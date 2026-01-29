<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
$all_invoices = $invoice_obj->find_all_invoices();
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span>All Invoices</span>
                </strong>
                <div class="pull-right">
                    <a href="add_invoice.php" class="btn btn-primary">Add New Invoice</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Invoice No </th>
                            <th> Customer Name </th>
                            <th class="text-center" style="width: 15%;"> Total Amount </th>
                            <th class="text-center" style="width: 15%;"> Date </th>
                            <th class="text-center" style="width: 100px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_invoices as $invoice): ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo count_id(); ?>
                                </td>
                                <td>
                                    <?php echo remove_junk($invoice['invoice_no']); ?>
                                </td>
                                <td>
                                    <?php echo remove_junk($invoice['customer_name']); ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk($invoice['total_amount']); ?>
                                </td>
                                <td class="text-center">
                                    <?php echo read_date($invoice['date']); ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="print_invoice.php?id=<?php echo (int) $invoice['id']; ?>"
                                            class="btn btn-info btn-xs" title="Print" data-toggle="tooltip" target="_blank">
                                            <span class="material-symbols-outlined">print</span>
                                        </a>
                                        <a href="delete_invoice.php?id=<?php echo (int) $invoice['id']; ?>"
                                            class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                                            <span class="material-symbols-outlined">delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>