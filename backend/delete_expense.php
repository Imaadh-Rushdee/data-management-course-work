<?php
// Include the connection file
include 'connect_oracle.php';
include('../Frontend/navbar.php');

// Set the name of your Oracle stored procedure for deletion
$procedure_name = 'delete_expense';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $expense_id = filter_var($_POST['expense_id'], FILTER_VALIDATE_INT);

    // Basic validation
    if ($expense_id === false) {
        die("Error: Please enter a valid Expense ID.");
    }

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // Assumed signature: delete_expense(p_expense_id)
        $sql = "BEGIN {$procedure_name}(:p_expense_id); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variable
        oci_bind_by_name($stmt, ":p_expense_id", $expense_id);

        // 4. Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            // Check how many rows were affected (a success but 0 affected means ID wasn't found)
            if (oci_num_rows($stmt) > 0) {
                echo "<p style='color: green;'>✅ Expense ID **{$expense_id}** deleted successfully!</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Warning: Expense ID **{$expense_id}** not found.</p>";
            }
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error deleting expense: " . htmlspecialchars($e['message']) . "</p>";
        }

        // 6. Clean up
        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Connection Error: " . $e->getMessage() . "</p>";
    }

    // Close the connection
    oci_close($conn);
}
?>

---

<h2>Delete Expense</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="expense_id">Expense ID to Delete:</label><br>
    <input type="number" id="expense_id" name="expense_id" required><br><br>

    <input type="submit" value="Delete Expense">
</form>