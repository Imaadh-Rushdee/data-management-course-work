<?php
include('connect_oracle.php');

$sql = "SELECT * FROM savings_progress_view ORDER BY progress_percent DESC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

echo "<h2>Savings Progress Report</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Goal Name</th><th>Target Amount</th><th>Current Amount</th><th>Progress (%)</th></tr>";

while ($row = oci_fetch_assoc($stmt)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['GOAL_NAME']) . "</td>";
    echo "<td>" . $row['TARGET_AMOUNT'] . "</td>";
    echo "<td>" . $row['CURRENT_AMOUNT'] . "</td>";
    echo "<td>" . round($row['PROGRESS_PERCENT'], 2) . "%</td>";
    echo "</tr>";
}

echo "</table>";

oci_free_statement($stmt);
oci_close($conn);
?>
