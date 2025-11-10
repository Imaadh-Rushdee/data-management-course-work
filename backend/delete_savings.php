<?php
// Include the connection file
include 'connect_oracle.php';
include('../Frontend/navbar.php');

// Set the name of your Oracle stored procedure for deletion
$procedure_name = 'delete_saving';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $saving_id = filter_var($_POST['saving_id'], FILTER_VALIDATE_INT);

    // Basic validation
    if ($saving_id === false) {
        die("Error: Please enter a valid Saving ID.");
    }

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // Assumed signature: delete_saving(p_saving_id)
        $sql = "BEGIN {$procedure_name}(:p_saving_id); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variable
        oci_bind_by_name($stmt, ":p_saving_id", $saving_id);

        // 4. Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            // Check how many rows were affected 
            if (oci_num_rows($stmt) > 0) {
                echo "<p style='color: green;'>✅ Saving Goal ID **{$saving_id}** deleted successfully!</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Warning: Saving Goal ID **{$saving_id}** not found.</p>";
            }
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error deleting saving goal: " . htmlspecialchars($e['message']) . "</p>";
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

<h2>Delete Saving Goal</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="saving_id">Saving ID to Delete:</label><br>
    <input type="number" id="saving_id" name="saving_id" required><br><br>

    <input type="submit" value="Delete Saving Goal">
</form>