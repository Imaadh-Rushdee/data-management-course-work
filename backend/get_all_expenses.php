<?php
include 'connect_oracle.php';
try {
    $stmt = oci_parse($conn, "BEGIN get_all_expenses(:cursor); END;");
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
    oci_execute($stmt);
    oci_execute($cursor);

    echo "<h2>All Expenses</h2>";
    echo "<table border='1' cellpadding='5'><tr>
            <th>ID</th><th>Category</th><th>Amount</th><th>Date</th><th>Note</th><th>Status</th>
          </tr>";
    while (($row = oci_fetch_assoc($cursor)) != false) {
        echo "<tr>
                <td>{$row['EXPENSE_ID']}</td>
                <td>{$row['CATEGORY']}</td>
                <td>{$row['AMOUNT']}</td>
                <td>{$row['EXPENSE_DATE']}</td>
                <td>{$row['NOTE']}</td>
                <td>{$row['SYNC_STATUS']}</td>
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
