<?php

include 'connect_oracle.php';
include('../Frontend/navbar.php');


$procedure_name = 'delete_budget';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $budget_id = filter_var($_POST['budget_id'], FILTER_VALIDATE_INT);


    if ($budget_id === false) {
        die("Error: Please enter a valid Budget ID.");
    }

    try {

        $sql = "BEGIN {$procedure_name}(:p_budget_id); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":p_budget_id", $budget_id);


        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);


        if ($success) {

            if (oci_num_rows($stmt) > 0) {
                echo "<p style='color: green;'> Budget ID {$budget_id} deleted successfully!</p>";
            } else {
                echo "<p style='color: orange;'> Warning: Budget ID {$budget_id} not found.</p>";
            }
        } else {

            $e = oci_error($stmt);
            echo "<p style='color: red;'> Error deleting budget: " . htmlspecialchars($e['message']) . "</p>";
        }


        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'> Connection Error: " . $e->getMessage() . "</p>";
    }


    oci_close($conn);
}
