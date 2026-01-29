<?php

class User
{
    private $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function authenticate($username = '', $password = '')
    {
        $username = $this->db->escape($username);
        $password = $this->db->escape($password);
        $sql = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
        $result = $this->db->query($sql);
        if ($this->db->num_rows($result)) {
            $user = $this->db->fetch_assoc($result);
            $password_request = sha1($password);
            if ($password_request === $user['password']) {
                return $user['id'];
            }
        }
        return false;
    }

    public function authenticate_v2($username = '', $password = '')
    {
        $username = $this->db->escape($username);
        $password = $this->db->escape($password);
        $sql = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
        $result = $this->db->query($sql);
        if ($this->db->num_rows($result)) {
            $user = $this->db->fetch_assoc($result);
            $password_request = sha1($password);
            if ($password_request === $user['password']) {
                return $user;
            }
        }
        return false;
    }

    public function find_by_id($id)
    {
        $id = (int) $id;
        $sql = $this->db->query("SELECT * FROM users WHERE id='{$this->db->escape($id)}' LIMIT 1");
        return $this->db->fetch_assoc($sql);
    }

    public function current()
    {
        static $current_user;
        global $session;
        if (!$current_user && $session->isUserLoggedIn()) {
            $user_id = (int) $_SESSION['user_id'];
            $current_user = $this->find_by_id($user_id);
        }
        return $current_user;
    }

    public function update_last_login($user_id)
    {
        $date = make_date();
        $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
        $result = $this->db->query($sql);
        return ($result && $this->db->affected_rows() === 1 ? true : false);
    }

    public function find_all()
    {
        $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
        $sql .= "g.group_name ";
        $sql .= "FROM users u ";
        $sql .= "LEFT JOIN user_groups g ON g.group_level=u.user_level ";
        $sql .= "ORDER BY u.name ASC";
        $result = $this->db->query($sql);
        return $this->db->while_loop($result);
    }
}
