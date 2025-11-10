<?php
include 'connect_oracle.php';

header('Content-Type: application/json');

try {
    $stmt = oci_parse($conn, "BEGIN get_budget_categories(:p_cursor); END;");
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":p_cursor", $cursor, -1, OCI_B_CURSOR);
    oci_execute($stmt);
    oci_execute($cursor);

    $categories = [];
    while (($row = oci_fetch_assoc($cursor)) != false) {
        $categories[] = $row['CATEGORY'];
    }

    echo json_encode($categories);

    oci_free_statement($stmt);
    oci_free_statement($cursor);
    oci_close($conn);
} catch (Exception $e) {
    echo json_encode([]);
}
?>
