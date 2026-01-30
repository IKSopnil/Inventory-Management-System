<?php

class Product
{
    private $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function find_all()
    {
        $sql = "SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name AS categorie,m.file_name AS image";
        $sql .= " FROM products p";
        $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id";
        $sql .= " LEFT JOIN media m ON m.id = p.media_id";
        $sql .= " ORDER BY p.id ASC";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function find_by_title($product_name)
    {
        $p_name = remove_junk($this->db->escape($product_name));
        $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function find_info_by_title($title)
    {
        $title = $this->db->escape($title);
        $sql = "SELECT * FROM products WHERE name ='{$title}' LIMIT 1";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function find_recent($limit)
    {
        $sql = "SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,m.file_name AS image";
        $sql .= " FROM products p";
        $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id";
        $sql .= " LEFT JOIN media m ON m.id = p.media_id";
        $sql .= " ORDER BY p.id DESC LIMIT " . (int) $limit;
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function find_highest_selling($limit)
    {
        $sql = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON p.id = s.product_id";
        $sql .= " GROUP BY s.product_id";
        $sql .= " ORDER BY SUM(s.qty) DESC LIMIT " . (int) $limit;
        return $this->db->query($sql);
    }

    public function update_qty($qty, $p_id)
    {
        $qty = (int) $qty;
        $p_id = (int) $p_id;
        $sql = "UPDATE products SET quantity = quantity - $qty WHERE id = '{$p_id}'";
        $result = $this->db->query($sql);
        return ($result && $this->db->affected_rows() === 1 ? true : false);
    }

    public function find_info_by_id($id)
    {
        $id = (int) $id;
        $sql = "SELECT * FROM products WHERE id ='{$id}' LIMIT 1";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }
}
