<?php

include 'connect_oracle.php';
include('../Frontend/navbar.php');


$procedure_name = 'delete_savings';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $saving_id = filter_var($_POST['saving_id'], FILTER_VALIDATE_INT);


    if ($saving_id === false) {
        die("Error: Please enter a valid Saving ID.");
    }

    try {

        $sql = "BEGIN {$procedure_name}(:p_saving_id); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":p_saving_id", $saving_id);


        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);


        if ($success) {

            if (oci_num_rows($stmt) > 0) {
                echo "<p style='color: green;'> Saving Goal ID  {$saving_id}  deleted successfully!</p>";
            } else {
                echo "<p style='color: orange;'> Warning: Saving Goal ID  {$saving_id}  not found.</p>";
            }
        } else {

            $e = oci_error($stmt);
            echo "<p style='color: red;'> Error deleting saving goal: " . htmlspecialchars($e['message']) . "</p>";
        }


        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'> Connection Error: " . $e->getMessage() . "</p>";
    }

    oci_close($conn);
}
