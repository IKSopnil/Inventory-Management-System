<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
$invoice = $invoice_obj->find_by_id((int) $_GET['id']);
if (!$invoice) {
    $session->msg("d", "Missing Invoice id.");
    redirect('invoices.php');
}
if ($invoice_obj->delete((int) $_GET['id'])) {
    $session->msg("s", "Invoice deleted.");
    redirect('invoices.php');
} else {
    $session->msg("d", "Invoice deletion failed.");
    redirect('invoices.php');
}
?>