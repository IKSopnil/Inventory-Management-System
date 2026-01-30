<?php
require_once('includes/load.php');

echo "<h1>Table Structure (users)</h1>";
$sql = "DESCRIBE users";
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
?>