<?php
// Include the connection file
include 'connect_oracle.php';

// Set the name of your Oracle stored procedure for update
$procedure_name = 'update_budget';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $budget_id = filter_var($_POST['budget_id'], FILTER_VALIDATE_INT);
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $start_date_str = htmlspecialchars($_POST['start_date']);
    $end_date_str = htmlspecialchars($_POST['end_date']);

    // Basic validation
    if ($budget_id === false || !$category || $amount === false || !$start_date_str || !$end_date_str) {
        die("Error: Please fill in all fields correctly.");
    }

    // Convert the date strings to Oracle's internal DATE format
    $start_date_conversion_sql = "TO_DATE(:start_date_str, 'YYYY-MM-DD')";
    $end_date_conversion_sql = "TO_DATE(:end_date_str, 'YYYY-MM-DD')";

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // Assumed signature: update_budget(p_budget_id, p_category, p_amount, p_start_date, p_end_date)
        $sql = "BEGIN {$procedure_name}(:p_budget_id, :p_category, :p_amount, " . $start_date_conversion_sql . ", " . $end_date_conversion_sql . "); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variables to the Oracle procedure parameters
        oci_bind_by_name($stmt, ":p_budget_id", $budget_id);
        oci_bind_by_name($stmt, ":p_category", $category);
        oci_bind_by_name($stmt, ":p_amount", $amount);
        oci_bind_by_name($stmt, ":start_date_str", $start_date_str);
        oci_bind_by_name($stmt, ":end_date_str", $end_date_str);

        // 4. Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            echo "<p style='color: green;'>✅ Budget ID **{$budget_id}** updated successfully!</p>";
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error updating budget: " . htmlspecialchars($e['message']) . "</p>";
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

<h2>Update Budget Details</h2>
<p>Enter the **Budget ID** to modify and the **New Values** for all fields.</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="budget_id">Budget ID to Update:</label><br>
    <input type="number" id="budget_id" name="budget_id" required><br><br>

    <label for="category">New Category:</label><br>
    <input type="text" id="category" name="category" required><br><br>

    <label for="amount">New Budget Amount:</label><br>
    <input type="number" step="0.01" id="amount" name="amount" required><br><br>

    <label for="start_date">New Start Date:</label><br>
    <input type="date" id="start_date" name="start_date" required><br><br>

    <label for="end_date">New End Date:</label><br>
    <input type="date" id="end_date" name="end_date" required><br><br>

    <input type="submit" value="Update Budget">
</form>