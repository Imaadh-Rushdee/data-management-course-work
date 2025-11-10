<?php
include('connect_oracle.php');

$sql = "SELECT * FROM monthly_expense_view ORDER BY expense_year DESC, expense_month_num DESC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

echo "<h2>Monthly Expense Report</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Month</th><th>Total Spent</th></tr>";

while ($row = oci_fetch_assoc($stmt)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['EXPENSE_MONTH']) . "</td>";
    echo "<td>" . $row['TOTAL_SPENT'] . "</td>";
    echo "</tr>";
}

echo "</table>";

oci_free_statement($stmt);
oci_close($conn);
?>
