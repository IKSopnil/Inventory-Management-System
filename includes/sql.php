<?php
require_once('includes/load.php');

// Initialize Models
$user_obj = new User();
$product_obj = new Product();
$sale_obj = new Sale();

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table));
  }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
  return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table, $id)
{
  global $db;
  $id = (int) $id;
  if (tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
    if ($result = $db->fetch_assoc($sql))
      return $result;
    else
      return null;
  }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table, $id)
{
  global $db;
  if (tableExists($table)) {
    $sql = "DELETE FROM " . $db->escape($table);
    $sql .= " WHERE id=" . $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table)
{
  global $db;
  if (tableExists($table)) {
    $sql = "SELECT COUNT(id) AS total FROM " . $db->escape($table);
    $result = $db->query($sql);
    return ($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table)
{
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM ' . DB_NAME . ' LIKE "' . $db->escape($table) . '"');
  if ($table_exit) {
    if ($db->num_rows($table_exit) > 0)
      return true;
    else
      return false;
  }
}
/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
/* coming from the login form.
/*--------------------------------------------------------------*/
function authenticate($username = '', $password = '')
{
  global $user_obj;
  return $user_obj->authenticate($username, $password);
}
function authenticate_v2($username = '', $password = '')
{
  global $user_obj;
  return $user_obj->authenticate_v2($username, $password);
}


/*--------------------------------------------------------------*/
/* Find current log in user by session id
/*--------------------------------------------------------------*/
function current_user()
{
  global $user_obj;
  return $user_obj->current();
}
/*--------------------------------------------------------------*/
/* Find all user by
/* Joining users table and user gropus table
/*--------------------------------------------------------------*/
function find_all_user()
{
  global $user_obj;
  return $user_obj->find_all();
}
/*--------------------------------------------------------------*/
/* Function to update the last log in of a user
/*--------------------------------------------------------------*/

function updateLastLogIn($user_id)
{
  global $user_obj;
  return $user_obj->update_last_login($user_id);
}

/*--------------------------------------------------------------*/
/* Find all Group name
/*--------------------------------------------------------------*/
function find_by_groupName($val)
{
  global $db;
  $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*--------------------------------------------------------------*/
/* Find group level
/*--------------------------------------------------------------*/
function find_by_groupLevel($level)
{
  global $db;
  $sql = "SELECT * FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->fetch_assoc($result));
}
/*--------------------------------------------------------------*/
/* Function for cheaking which user level has access to page
/*--------------------------------------------------------------*/
function page_require_level($require_level)
{
  global $session;
  $current_user = current_user();
  $login_level = find_by_groupLevel($current_user['user_level']);
  //if user not login
  if (!$session->isUserLoggedIn()):
    $session->msg('d', 'Please login...');
    redirect('index.php', false);
    //if Group status Deactive
  elseif ($login_level && $login_level['group_status'] === '0'):
    $session->msg('d', 'This level user has been band!');
    redirect('home.php', false);
    //cheackin log in User level and Require level is Less than or equal to
  elseif ($current_user['user_level'] <= (int) $require_level):
    return true;
  else:
    $session->msg("d", "Sorry! you dont have permission to view the page.");
    redirect('home.php', false);
  endif;

}
/*--------------------------------------------------------------*/
/* Function for Finding all product name
/* JOIN with categorie  and media database table
/*--------------------------------------------------------------*/
function join_product_table()
{
  global $product_obj;
  return $product_obj->find_all();
}
/*--------------------------------------------------------------*/
/* Function for Finding all product name
/* Request coming from ajax.php for auto suggest
/*--------------------------------------------------------------*/

function find_product_by_title($product_name)
{
  global $product_obj;
  return $product_obj->find_by_title($product_name);
}

/*--------------------------------------------------------------*/
/* Function for Finding all product info by product title
/* Request coming from ajax.php
/*--------------------------------------------------------------*/
function find_all_product_info_by_title($title)
{
  global $product_obj;
  return $product_obj->find_info_by_title($title);
}

/*--------------------------------------------------------------*/
/* Function for Update product quantity
/*--------------------------------------------------------------*/
function update_product_qty($qty, $p_id)
{
  global $product_obj;
  return $product_obj->update_qty($qty, $p_id);
}
/*--------------------------------------------------------------*/
/* Function for Display Recent product Added
/*--------------------------------------------------------------*/
function find_recent_product_added($limit)
{
  global $product_obj;
  return $product_obj->find_recent($limit);
}
/*--------------------------------------------------------------*/
/* Function for Find Highest saleing Product
/*--------------------------------------------------------------*/
function find_higest_saleing_product($limit)
{
  global $product_obj;
  return $product_obj->find_highest_selling($limit);
}
/*--------------------------------------------------------------*/
/* Function for find all sales
/*--------------------------------------------------------------*/
function find_all_sale()
{
  global $sale_obj;
  return $sale_obj->find_all();
}
/*--------------------------------------------------------------*/
/* Function for Display Recent sale
/*--------------------------------------------------------------*/
function find_recent_sale_added($limit)
{
  global $sale_obj;
  return $sale_obj->find_recent($limit);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date, $end_date)
{
  global $sale_obj;
  return $sale_obj->find_sale_by_date($start_date, $end_date);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function dailySales($year, $month)
{
  global $sale_obj;
  return $sale_obj->daily_sales($year, $month);
}

function find_sales_by_day($date)
{
  global $sale_obj;
  return $sale_obj->find_sales_by_date($date);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function monthlySales($year)
{
  global $sale_obj;
  return $sale_obj->monthly_sales($year);
}

function find_sales_analytics($limit = 10)
{
  global $sale_obj;
  return $sale_obj->get_sales_analytics($limit);
}

?>