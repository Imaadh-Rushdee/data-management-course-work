<?php
include('connect_oracle.php');
include('../Frontend/navbar.php');

$sql = "SELECT * FROM expense_summary_view ORDER BY total_spent DESC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

echo "<h2>Expense Summary Report</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Category</th><th>Total Transactions</th><th>Total Spent</th></tr>";

while ($row = oci_fetch_assoc($stmt)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['CATEGORY']) . "</td>";
    echo "<td>" . $row['TOTAL_TRANSACTIONS'] . "</td>";
    echo "<td>" . $row['TOTAL_SPENT'] . "</td>";
    echo "</tr>";
}

echo "</table>";

oci_free_statement($stmt);
oci_close($conn);
?>
