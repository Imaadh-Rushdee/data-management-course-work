<?php
// Include the connection file
include 'connect_oracle.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $expense_date_str = htmlspecialchars($_POST['expense_date']);
    $note = htmlspecialchars($_POST['note']);

    // Basic validation
    if (!$category || $amount === false || !$expense_date_str) {
        die("Error: Please fill in all required fields (Category, Amount, Date) correctly.");
    }

    // Convert the date string to Oracle's internal date format using TO_DATE
    // Assuming the input date is in 'YYYY-MM-DD' format from an HTML date input
    $date_conversion_sql = "TO_DATE(:expense_date_str, 'YYYY-MM-DD')";

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // The procedure call is wrapped in a BEGIN...END block
        $sql = "BEGIN add_expense(:p_category, :p_amount, " . $date_conversion_sql . ", :p_note); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variables to the Oracle procedure parameters
        oci_bind_by_name($stmt, ":p_category", $category);
        oci_bind_by_name($stmt, ":p_amount", $amount);
        oci_bind_by_name($stmt, ":expense_date_str", $expense_date_str); // Bind the date string
        oci_bind_by_name($stmt, ":p_note", $note);

        // 4. Execute the statement
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS); // Commit immediately

        // 5. Check execution result
        if ($success) {
            echo "<p style='color: green;'>✅ Expense added successfully!</p>";
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            // Check for the budget limit trigger error (-20001)
            if ($e['code'] == 20001) {
                echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e['message']) . "</p>";
            } else {
                echo "<p style='color: red;'>❌ Error adding expense: " . htmlspecialchars($e['message']) . "</p>";
            }
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

<h2>Add New Expense</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="category">Category:</label><br>
    <input type="text" id="category" name="category" required><br><br>

    <label for="amount">Amount (e.g., 25.50):</label><br>
    <input type="number" step="0.01" id="amount" name="amount" required><br><br>

    <label for="expense_date">Date:</label><br>
    <input type="date" id="expense_date" name="expense_date" value="<?php echo date('Y-m-d'); ?>" required><br><br>

    <label for="note">Note (Optional):</label><br>
    <input type="text" id="note" name="note"><br><br>

    <input type="submit" value="Add Expense">
</form>