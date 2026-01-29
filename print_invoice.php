<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
$id = (int) $_GET['id'];
$invoice = $invoice_obj->find_by_id($id);
if (!$invoice) {
    $session->msg("d", "Invoice not found.");
    redirect('invoices.php');
}
// Get detailed sale/product info
$sql = "SELECT s.*, p.name as product_name, p.sale_price";
$sql .= " FROM sales s";
$sql .= " LEFT JOIN products p ON s.product_id = p.id";
$sql .= " WHERE s.id = '{$invoice['sale_id']}' LIMIT 1";
$result = $db->query($sql);
$sale_details = $db->fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice -
        <?php echo $invoice['invoice_no']; ?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }

        body {
            background: #fff;
            padding: 40px;
            font-family: 'Inter', sans-serif;
        }

        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color: #3b82f6;
        }

        .company-info {
            text-align: right;
        }

        .details-row {
            margin-bottom: 40px;
        }

        .table th {
            background: #f8fafc !important;
        }

        .total-section {
            text-align: right;
            margin-top: 30px;
        }

        .total-section h3 {
            color: #3b82f6;
            font-weight: 700;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="container">
        <div class="row no-print" style="margin-bottom:20px;">
            <div class="col-md-12">
                <button class="btn btn-default" onclick="window.close();">Close Window</button>
                <button class="btn btn-primary" onclick="window.print();">Print Again</button>
            </div>
        </div>

        <?php $settings = $invoice_obj->get_settings(); ?>
        <div class="invoice-header clearfix">
            <div class="pull-left">
                <div class="invoice-title">INVOICE</div>
                <p><strong>No:</strong> <?php echo $invoice['invoice_no']; ?></p>
                <p><strong>Date:</strong> <?php echo read_date($invoice['date']); ?></p>
            </div>
            <div class="pull-right company-info">
                <?php if ($settings['logo']): ?>
                    <img src="uploads/settings/<?php echo $settings['logo']; ?>" alt="Logo"
                        style="max-height: 60px; margin-bottom: 10px;">
                <?php endif; ?>
                <h3><?php echo $settings['name']; ?></h3>
                <p><?php echo nl2br($settings['address']); ?><br>Phone: <?php echo $settings['phone']; ?></p>
            </div>
        </div>

        <div class="row details-row">
            <div class="col-xs-6">
                <h5><strong>BILL TO:</strong></h5>
                <p>
                    <strong>
                        <?php echo $invoice['customer_name']; ?>
                    </strong><br>
                    <?php echo nl2br($invoice['customer_address']); ?>
                </p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center" style="width: 15%;">Quantity</th>
                    <th class="text-right" style="width: 20%;">Unit Price</th>
                    <th class="text-right" style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo $sale_details['product_name']; ?>
                    </td>
                    <td class="text-center">
                        <?php echo (int) $sale_details['qty']; ?>
                    </td>
                    <td class="text-right">$
                        <?php echo $sale_details['sale_price']; ?>
                    </td>
                    <td class="text-right">$
                        <?php echo $invoice['total_amount']; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <p>Subtotal: $
                <?php echo $invoice['total_amount']; ?>
            </p>
            <p>Tax (0%): $0.00</p>
            <hr>
            <h3>Total: $
                <?php echo $invoice['total_amount']; ?>
            </h3>
        </div>

        <div style="margin-top: 50px; text-align: center; color: #94a3b8;">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>