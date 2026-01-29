<?php
require_once('load.php');

class Invoice
{
    private $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
        $this->ensure_table_exists();
    }

    private function ensure_table_exists()
    {
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
        $this->db->query($sql);

        $sql_settings = "CREATE TABLE IF NOT EXISTS business_settings (
          id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          name VARCHAR(255) NOT NULL,
          address TEXT,
          phone VARCHAR(255),
          logo VARCHAR(255),
          PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db->query($sql_settings);

        // Seed settings if empty
        $check = $this->db->query("SELECT id FROM business_settings LIMIT 1");
        if ($this->db->num_rows($check) == 0) {
            $this->db->query("INSERT INTO business_settings (name, address, phone) VALUES ('OS Inventory', '123 Business Street', '+1 234 567 890')");
        }
    }

    public function get_settings()
    {
        $sql = "SELECT * FROM business_settings WHERE id=1 LIMIT 1";
        $result = $this->db->query($sql);
        return $this->db->fetch_assoc($result);
    }

    public function update_settings($name, $address, $phone, $logo = null)
    {
        $name = $this->db->escape($name);
        $address = $this->db->escape($address);
        $phone = $this->db->escape($phone);

        $sql = "UPDATE business_settings SET name='{$name}', address='{$address}', phone='{$phone}'";
        if ($logo) {
            $logo = $this->db->escape($logo);
            $sql .= ", logo='{$logo}'";
        }
        $sql .= " WHERE id=1";
        return $this->db->query($sql);
    }

    /*--------------------------------------------------------------*/
    /* Find all invoices
    /*--------------------------------------------------------------*/
    public function find_all_invoices()
    {
        $result = $this->db->query("SELECT * FROM invoices ORDER BY date DESC");
        return $this->db->while_loop($result);
    }

    /*--------------------------------------------------------------*/
    /* Find invoice by id
    /*--------------------------------------------------------------*/
    public function find_by_id($id)
    {
        $id = (int) $id;
        $sql = $this->db->query("SELECT * FROM invoices WHERE id='{$id}' LIMIT 1");
        return $this->db->fetch_assoc($sql);
    }

    /*--------------------------------------------------------------*/
    /* Find invoice by sale_id
    /*--------------------------------------------------------------*/
    public function find_by_sale_id($sale_id)
    {
        $sale_id = (int) $sale_id;
        $sql = $this->db->query("SELECT * FROM invoices WHERE sale_id='{$sale_id}' LIMIT 1");
        return $this->db->fetch_assoc($sql);
    }

    /*--------------------------------------------------------------*/
    /* Generate Invoice Number
    /*--------------------------------------------------------------*/
    public function generate_invoice_no()
    {
        $result = $this->db->query("SELECT invoice_no FROM invoices ORDER BY id DESC LIMIT 1");
        $last_invoice = $this->db->fetch_assoc($result);
        if ($last_invoice) {
            $no = (int) substr($last_invoice['invoice_no'], 4) + 1;
            return "INV-" . str_pad($no, 6, "0", STR_PAD_LEFT);
        }
        return "INV-000001";
    }

    /*--------------------------------------------------------------*/
    /* Create Invoice
    /*--------------------------------------------------------------*/
    public function create($data)
    {
        $invoice_no = $this->generate_invoice_no();
        $customer_name = $this->db->escape($data['customer_name']);
        $customer_address = $this->db->escape($data['customer_address']);
        $sale_id = (int) $data['sale_id'];
        $total_amount = (float) $data['total_amount'];
        $date = make_date();

        $sql = "INSERT INTO invoices (invoice_no, customer_name, customer_address, sale_id, total_amount, date)";
        $sql .= " VALUES ('{$invoice_no}', '{$customer_name}', '{$customer_address}', '{$sale_id}', '{$total_amount}', '{$date}')";

        if ($this->db->query($sql)) {
            return true;
        }
        return false;
    }

    /*--------------------------------------------------------------*/
    /* Delete Invoice
    /*--------------------------------------------------------------*/
    public function delete($id)
    {
        $id = (int) $id;
        $sql = "DELETE FROM invoices WHERE id='{$id}' LIMIT 1";
        $this->db->query($sql);
        return ($this->db->affected_rows() === 1);
    }
}

$invoice_obj = new Invoice();
?>