<?php

class Sale
{
    private $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function find_all()
    {
        $sql = "SELECT s.id,s.qty,s.price,s.date,p.name";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " ORDER BY s.date DESC";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function find_recent($limit)
    {
        $sql = "SELECT s.id,s.qty,s.price,s.date,p.name";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " ORDER BY s.date DESC LIMIT " . (int) $limit;
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function find_sale_by_date($start_date, $end_date)
    {
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $sql = "SELECT s.date, p.name, p.buy_price, p.sale_price,";
        $sql .= "COUNT(s.product_id) AS total_records,";
        $sql .= "SUM(s.qty) AS total_sales,";
        $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
        $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
        $sql .= " GROUP BY DATE(s.date), p.name";
        $sql .= " ORDER BY DATE(s.date) DESC";
        return $this->db->query($sql);
    }

    public function daily_sales($year, $month)
    {
        $year = (int) $year;
        $month = (int) $month;
        $sql = "SELECT s.qty, DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name,";
        $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-" . sprintf("%02d", $month) . "'";
        $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function monthly_sales($year)
    {
        $year = (int) $year;
        $sql = "SELECT s.qty, DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name,";
        $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
        $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
        $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }
}
