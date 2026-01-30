<?php
require_once('includes/load.php');

// 1. Show existing groups
echo "<h1>Existing Groups</h1>";
$groups = find_all('user_groups');
echo "<pre>";
print_r($groups);
echo "</pre>";

// 2. Describe Table
echo "<h1>Table Structure (user_groups)</h1>";
$sql = "DESCRIBE user_groups";
$result = $db->query($sql);
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach ($row as $cell) {
        echo "<td>" . $cell . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

// 3. Test Insert (Rollback logic or just separate test)
echo "<h1>Test Insert Inspection</h1>";
echo "Attempting to insert a test entry safely...<br>";
// We won't actually insert to avoid spamming, unless needed. 
// Just checking schema is enough for now.
?>