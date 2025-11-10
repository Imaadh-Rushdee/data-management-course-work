<?php

include 'connect_oracle.php';
include('../Frontend/navbar.php');


$procedure_name = 'delete_expense';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['expense_id'])) {
        // Log or handle the error gracefully if the required field is missing
        die("<p style='color: red;'>Error: Expense ID is missing from the form submission.</p>");
    }

    $expense_id = filter_var($_POST['expense_id'], FILTER_VALIDATE_INT);


    if ($expense_id === false) {
        die("Error: Please enter a valid Expense ID.");
    }

    try {

        $sql = "BEGIN {$procedure_name}(:p_expense_id); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":p_expense_id", $expense_id);

        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);


        if ($success) {

            if (oci_num_rows($stmt) > 0) {
                echo "<p style='color: green;'> Expense ID  {$expense_id}  deleted successfully!</p>";
            } else {
                echo "<p style='color: orange;'> Warning: Expense ID  {$expense_id} not found.</p>";
            }
        } else {

            $e = oci_error($stmt);
            echo "<p style='color: red;'> Error deleting expense: " . htmlspecialchars($e['message']) . "</p>";
        }


        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'> Connection Error: " . $e->getMessage() . "</p>";
    }


    oci_close($conn);
}
