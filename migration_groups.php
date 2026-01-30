<?php
require_once('includes/load.php');
global $db;

echo "<h1>Starting Migration (v2)...</h1>";

// 1. Add group_id to users table (if not exists)
echo "Checking `users` table for `group_id` column...<br>";
$check = $db->query("SHOW COLUMNS FROM users LIKE 'group_id'");
if ($db->num_rows($check) == 0) {
    echo "Adding `group_id` column to `users` table...<br>";
    $sql = "ALTER TABLE users ADD COLUMN group_id INT(11) DEFAULT NULL AFTER user_level";
    if ($db->query($sql)) {
        echo "SUCCESS: Added `group_id` column.<br>";
    } else {
        echo "ERROR: Failed to add column. " . $db->con->error . "<br>";
    }
} else {
    echo "SKIPPED: `group_id` column already exists.<br>";
}

// 2. Drop Foreign Key on users table
// The error usually mentions the constraint name. Common default is 'users_ibfk_1' or similar. 
// We will try to find it and drop it.
echo "Attempting to drop Foreign Key constraint on `users` table...<br>";

// Helper to find FK name if we don't know it exactly (assuming it is on user_level)
$sql = "SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'users' 
        AND COLUMN_NAME = 'user_level' 
        AND TABLE_SCHEMA = '" . DB_NAME . "'";
$res = $db->query($sql);
if ($row = $res->fetch_assoc()) {
    $fk_name = $row['CONSTRAINT_NAME'];
    echo "Found Foreign Key: $fk_name. Dropping it...<br>";
    $sql = "ALTER TABLE users DROP FOREIGN KEY `$fk_name`";
    if ($db->query($sql)) {
        echo "SUCCESS: Dropped Foreign Key `$fk_name`.<br>";
    } else {
        echo "ERROR: Failed to drop FK. " . $db->con->error . "<br>";
    }
} else {
    echo "NOTICE: No Foreign Key found on `user_level`. Proceeding.<br>";
}

// 3. Remove UNIQUE key from user_groups (group_level)
echo "Attempting to drop UNIQUE index on `group_level`...<br>";
$sql = "ALTER TABLE user_groups DROP INDEX group_level";
if ($db->query($sql)) {
    echo "SUCCESS: Dropped UNIQUE index on `group_level`.<br>";
} else {
    // If it fails, maybe it's just 'group_level' key?
    echo "NOTICE: " . $db->con->error . "<br>";
}

// 4. Backfill group_id for existing users
echo "Backfilling `group_id` for existing users based on `user_level`...<br>";
$all_groups = find_all('user_groups');
foreach ($all_groups as $group) {
    // CAST TO INT to be safe
    $lvl = (int) $group['group_level'];
    $gid = (int) $group['id'];

    // Update users with this level
    $update_sql = "UPDATE users SET group_id = '{$gid}' WHERE user_level = '{$lvl}' AND (group_id IS NULL OR group_id = 0)";
    $db->query($update_sql);
}
echo "Backfill complete.<br>";

echo "<h1>Migration Finished.</h1>";
?>