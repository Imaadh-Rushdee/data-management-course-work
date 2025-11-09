<?php
include 'connect_oracle.php';
$month = 10; // Change month here
$year = 2025; // Change year here

try {
    $stmt = oci_parse($conn, "BEGIN get_expenses_for_month(:p_month, :p_year, :cursor); END;");
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":p_month", $month);
    oci_bind_by_name($stmt, ":p_year", $year);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    oci_execute($stmt);
    oci_execute($cursor);

    echo "<h2>Expenses for $month/$year</h2>";
    echo "<table border='1' cellpadding='5'><tr>
            <th>ID</th><th>Category</th><th>Amount</th><th>Date</th><th>Note</th>
          </tr>";
    while (($row = oci_fetch_assoc($cursor)) != false) {
        echo "<tr>
                <td>{$row['ID']}</td>
                <td>{$row['CATEGORY']}</td>
                <td>{$row['AMOUNT']}</td>
                <td>{$row['EXPENSE_DATE']}</td>
                <td>{$row['NOTE']}</td>
              </tr>";
    }
    echo "</table>";

    oci_free_statement($stmt);
    oci_free_statement($cursor);
    oci_close($conn);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
