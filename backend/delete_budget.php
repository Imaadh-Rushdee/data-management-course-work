<?php
// Include the connection file
include 'connect_oracle.php';
include('../Frontend/navbar.php');

// Set the name of your Oracle stored procedure for deletion
$procedure_name = 'delete_budget';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $budget_id = filter_var($_POST['budget_id'], FILTER_VALIDATE_INT);

    // Basic validation
    if ($budget_id === false) {
        die("Error: Please enter a valid Budget ID.");
    }

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // Assumed signature: delete_budget(p_budget_id)
        $sql = "BEGIN {$procedure_name}(:p_budget_id); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variable
        oci_bind_by_name($stmt, ":p_budget_id", $budget_id);

        // 4. Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            // Check how many rows were affected (a success but 0 affected means ID wasn't found)
            if (oci_num_rows($stmt) > 0) {
                echo "<p style='color: green;'>✅ Budget ID **{$budget_id}** deleted successfully!</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Warning: Budget ID **{$budget_id}** not found.</p>";
            }
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error deleting budget: " . htmlspecialchars($e['message']) . "</p>";
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

<h2>Delete Budget</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="budget_id">Budget ID to Delete:</label><br>
    <input type="number" id="budget_id" name="budget_id" required><br><br>

    <input type="submit" value="Delete Budget">
</form>