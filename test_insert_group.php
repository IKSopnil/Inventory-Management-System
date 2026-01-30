<?php
require_once('includes/load.php');

// Test Data
$name = "Test Group 100";
$level = 100;
$status = 1;

echo "<h1>Testing Group Insertion</h1>";

// 1. Check Duplicate Logic
echo "Checking if level $level exists...<br>";
$exists = find_by_groupLevel($level);
if (!empty($exists)) {
    echo "Level $level ALREADY EXISTS. Deleting it to clean up...<br>";
    $db->query("DELETE FROM user_groups WHERE group_level='{$level}'");
} else {
    echo "Level $level is unique. Proceeding.<br>";
}

// 2. Insert
echo "Attempting INSERT...<br>";
$sql = "INSERT INTO user_groups (group_name,group_level,group_status) VALUES ('{$name}', '{$level}','{$status}')";
echo "SQL: $sql <br>";

try {
    $result = $db->query($sql);
    if ($result) {
        echo "<b>INSERT SUCCESSFUL!</b> ($result)<br>";
        echo "Insert ID: " . $db->insert_id() . "<br>";
    } else {
        echo "<b>INSERT FAILED</b> (Returned false)<br>";
        echo "Error: " . $db->con->error . "<br>";
    }
} catch (Exception $e) {
    echo "<b>EXCEPTION CAUGHT:</b> " . $e->getMessage() . "<br>";
}

// 3. Verify
$check = find_by_groupLevel($level);
if (!empty($check)) {
    echo "VERIFICATION: Found inserted group: <pre>" . print_r($check, true) . "</pre>";
} else {
    echo "VERIFICATION: NOT FOUND.<br>";
}

?>