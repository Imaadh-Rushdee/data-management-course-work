<?php
// Include the connection file
include 'connect_oracle.php';

// Set the name of your Oracle stored procedure for update
$procedure_name = 'update_expense';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $expense_id = filter_var($_POST['expense_id'], FILTER_VALIDATE_INT);
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $expense_date_str = htmlspecialchars($_POST['expense_date']);
    $note = htmlspecialchars($_POST['note']);

    // Basic validation
    if ($expense_id === false || !$category || $amount === false || !$expense_date_str) {
        die("Error: Please fill in all required fields (ID, Category, Amount, Date) correctly.");
    }

    // Convert the date string to Oracle's internal DATE format
    $date_conversion_sql = "TO_DATE(:expense_date_str, 'YYYY-MM-DD')";

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // Assumed signature: update_expense(p_expense_id, p_category, p_amount, p_expense_date, p_note)
        $sql = "BEGIN {$procedure_name}(:p_expense_id, :p_category, :p_amount, " . $date_conversion_sql . ", :p_note); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variables to the Oracle procedure parameters
        oci_bind_by_name($stmt, ":p_expense_id", $expense_id);
        oci_bind_by_name($stmt, ":p_category", $category);
        oci_bind_by_name($stmt, ":p_amount", $amount);
        oci_bind_by_name($stmt, ":expense_date_str", $expense_date_str); // Bind the date string
        oci_bind_by_name($stmt, ":p_note", $note);

        // 4. Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            echo "<p style='color: green;'>✅ Expense ID **{$expense_id}** updated successfully!</p>";
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error updating expense: " . htmlspecialchars($e['message']) . "</p>";
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

<h2>Update Expense Details</h2>
<p>Enter the **Expense ID** to modify and the **New Values** for all fields.</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="expense_id">Expense ID to Update:</label><br>
    <input type="number" id="expense_id" name="expense_id" required><br><br>

    <label for="category">New Category:</label><br>
    <input type="text" id="category" name="category" required><br><br>

    <label for="amount">New Amount (e.g., 25.50):</label><br>
    <input type="number" step="0.01" id="amount" name="amount" required><br><br>

    <label for="expense_date">New Date:</label><br>
    <input type="date" id="expense_date" name="expense_date" required><br><br>

    <label for="note">New Note (Optional):</label><br>
    <input type="text" id="note" name="note"><br><br>

    <input type="submit" value="Update Expense">
</form>