<?php
require_once('includes/load.php');

$sql = "CREATE TABLE IF NOT EXISTS invoices (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  invoice_no VARCHAR(255) NOT NULL,
  customer_name VARCHAR(255) NOT NULL,
  customer_address TEXT,
  sale_id INT(11) UNSIGNED NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  date DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (invoice_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($db->query($sql)) {
    echo "<h3>Success!</h3>";
    echo "<p>The 'invoices' table has been created successfully.</p>";
    echo "<a href='invoices.php'>Go to Invoices Page</a>";
} else {
    echo "<h3>Error!</h3>";
    echo "<p>Failed to create the table. Please check your database permissions.</p>";
}
?>