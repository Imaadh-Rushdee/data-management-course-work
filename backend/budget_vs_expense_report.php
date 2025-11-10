<?php
include('connect_oracle.php');

$sql = "SELECT * FROM mv_budgets_vs_expenses ORDER BY remaining_budget DESC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

echo "<h2>Budgets vs Expenses Report</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Category</th><th>Budget Amount</th><th>Actual Spent</th><th>Remaining Budget</th></tr>";

while ($row = oci_fetch_assoc($stmt)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['CATEGORY']) . "</td>";
    echo "<td>" . $row['BUDGET_AMOUNT'] . "</td>";
    echo "<td>" . $row['ACTUAL_SPENT'] . "</td>";
    echo "<td>" . $row['REMAINING_BUDGET'] . "</td>";
    echo "</tr>";
}

echo "</table>";

oci_free_statement($stmt);
oci_close($conn);
?>
