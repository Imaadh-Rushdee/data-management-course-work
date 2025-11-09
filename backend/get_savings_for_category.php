<?php
include 'connect_oracle.php';
$category_id = 1; // Replace with the saving_id you want

try {
    $stmt = oci_parse($conn, "BEGIN get_savings_for_category(:p_id, :cursor); END;");
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":p_id", $category_id);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    oci_execute($stmt);
    oci_execute($cursor);

    echo "<h2>Savings for ID $category_id</h2>";
    echo "<table border='1' cellpadding='5'><tr>
            <th>ID</th><th>Goal</th><th>Target</th><th>Current</th><th>Target Date</th><th>Last Entered</th><th>Status</th>
          </tr>";
    while (($row = oci_fetch_assoc($cursor)) != false) {
        echo "<tr>
                <td>{$row['SAVING_ID']}</td>
                <td>{$row['GOAL_NAME']}</td>
                <td>{$row['TARGET_AMOUNT']}</td>
                <td>{$row['CURRENT_AMOUNT']}</td>
                <td>{$row['TARGET_DATE']}</td>
                <td>{$row['LAST_ENTERED_DATE']}</td>
                <td>{$row['STATUS']}</td>
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
