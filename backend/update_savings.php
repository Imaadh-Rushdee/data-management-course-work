<?php
// Include the connection file
include 'connect_oracle.php';

// Set the name of your Oracle stored procedure for update
$procedure_name = 'update_saving';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $saving_id = filter_var($_POST['saving_id'], FILTER_VALIDATE_INT);
    $goal_name = htmlspecialchars($_POST['goal_name']);
    $target_amount = filter_var($_POST['target_amount'], FILTER_VALIDATE_FLOAT);
    $current_amount = filter_var($_POST['current_amount'], FILTER_VALIDATE_FLOAT);
    $target_date_str = htmlspecialchars($_POST['target_date']);

    // Basic validation
    if ($saving_id === false || !$goal_name || $target_amount === false || $current_amount === false || !$target_date_str) {
        die("Error: Please fill in all fields correctly.");
    }

    // Convert the date string to Oracle's internal DATE format
    $date_conversion_sql = "TO_DATE(:target_date_str, 'YYYY-MM-DD')";

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // Assumed signature: update_saving(p_saving_id, p_goal_name, p_target_amount, p_current_amount, p_target_date)
        $sql = "BEGIN {$procedure_name}(:p_saving_id, :p_goal_name, :p_target_amount, :p_current_amount, " . $date_conversion_sql . "); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variables to the Oracle procedure parameters
        oci_bind_by_name($stmt, ":p_saving_id", $saving_id);
        oci_bind_by_name($stmt, ":p_goal_name", $goal_name);
        oci_bind_by_name($stmt, ":p_target_amount", $target_amount);
        oci_bind_by_name($stmt, ":p_current_amount", $current_amount);
        oci_bind_by_name($stmt, ":target_date_str", $target_date_str);

        // 4. Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            echo "<p style='color: green;'>✅ Saving Goal ID **{$saving_id}** updated successfully!</p>";
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error updating saving goal: " . htmlspecialchars($e['message']) . "</p>";
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

<h2>Update Saving Goal Details</h2>
<p>Enter the **Saving ID** to modify and the **New Values** for all fields.</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="saving_id">Saving ID to Update:</label><br>
    <input type="number" id="saving_id" name="saving_id" required><br><br>

    <label for="goal_name">New Goal Name:</label><br>
    <input type="text" id="goal_name" name="goal_name" required><br><br>

    <label for="target_amount">New Target Amount:</label><br>
    <input type="number" step="0.01" id="target_amount" name="target_amount" required><br><br>

    <label for="current_amount">New Current Amount:</label><br>
    <input type="number" step="0.01" id="current_amount" name="current_amount" required><br><br>

    <label for="target_date">New Target Date:</label><br>
    <input type="date" id="target_date" name="target_date" required><br><br>

    <input type="submit" value="Update Saving Goal">
</form>