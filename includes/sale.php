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
        $sql = "SELECT SUM(s.qty) AS qty, DATE_FORMAT(s.date, '%Y-%m-%d') AS date, p.name,";
        $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-" . sprintf("%02d", $month) . "'";
        $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
        $sql .= " ORDER BY s.date DESC";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function find_sales_by_date($date)
    {
        $date = $this->db->escape($date);
        $sql = "SELECT SUM(s.qty) AS qty, DATE_FORMAT(s.date, '%Y-%m-%d') AS date, p.name,";
        $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " WHERE DATE(s.date) = '{$date}'";
        $sql .= " GROUP BY s.product_id";
        $sql .= " ORDER BY s.date DESC";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function monthly_sales($year)
    {
        $year = (int) $year;
        $sql = "SELECT SUM(s.qty) AS qty, DATE_FORMAT(s.date, '%Y-%m-%d') AS date, p.name,";
        $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
        $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
        $sql .= " ORDER BY s.date DESC";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }

    public function get_sales_analytics($range = '7d')
    {
        switch ($range) {
            case '30d':
                $interval = "30 DAY";
                $group_by = "DATE(s.date)";
                $date_format = "%b %d";
                break;
            case '6m':
                $interval = "6 MONTH";
                $group_by = "DATE_FORMAT(s.date, '%Y-%m')";
                $date_format = "%b %Y";
                break;
            case '1y':
                $interval = "1 YEAR";
                $group_by = "DATE_FORMAT(s.date, '%Y-%m')";
                $date_format = "%b %Y";
                break;
            case '7d':
            default:
                $interval = "7 DAY";
                $group_by = "DATE(s.date)";
                $date_format = "%b %d";
                break;
        }

        $current_date = date('Y-m-d H:i:s');
        $sql = "SELECT DATE_FORMAT(s.date, '{$date_format}') as label, SUM(p.sale_price * s.qty) as total";
        $sql .= " FROM sales s";
        $sql .= " LEFT JOIN products p ON s.product_id = p.id";
        $sql .= " WHERE s.date >= DATE_SUB('{$current_date}', INTERVAL {$interval})";
        $sql .= " GROUP BY {$group_by}";
        $sql .= " ORDER BY s.date ASC";

        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }
}
