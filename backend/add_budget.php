<?php
// Include the connection file
include 'connect_oracle.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $start_date_str = htmlspecialchars($_POST['start_date']);
    $end_date_str = htmlspecialchars($_POST['end_date']);

    // Basic validation
    if (!$category || $amount === false || !$start_date_str || !$end_date_str) {
        die("Error: Please fill in all required fields correctly.");
    }

    // Convert the date strings to Oracle's internal DATE format using TO_DATE
    // Assuming the input date is in 'YYYY-MM-DD' format from an HTML date input
    $start_date_conversion_sql = "TO_DATE(:start_date_str, 'YYYY-MM-DD')";
    $end_date_conversion_sql = "TO_DATE(:end_date_str, 'YYYY-MM-DD')";

    // Set the name of your Oracle stored procedure
    // NOTE: Replace 'add_budget' with the actual procedure name if it's different.
    $procedure_name = 'add_budget';

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // The procedure signature is assumed to be: 
        // add_budget(p_category, p_amount, p_start_date, p_end_date)
        $sql = "BEGIN {$procedure_name}(:p_category, :p_amount, " . $start_date_conversion_sql . ", " . $end_date_conversion_sql . "); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variables to the Oracle procedure parameters
        oci_bind_by_name($stmt, ":p_category", $category);
        oci_bind_by_name($stmt, ":p_amount", $amount);
        oci_bind_by_name($stmt, ":start_date_str", $start_date_str); // Bind the start date string
        oci_bind_by_name($stmt, ":end_date_str", $end_date_str);     // Bind the end date string

        // 4. Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            echo "<p style='color: green;'>✅ New Budget added successfully!</p>";
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error adding budget: " . htmlspecialchars($e['message']) . "</p>";
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

<h2>Create New Budget</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="category">Category (e.g., Groceries, Rent, Bills):</label><br>
    <input type="text" id="category" name="category" required><br><br>

    <label for="amount">Budget Amount:</label><br>
    <input type="number" step="0.01" id="amount" name="amount" required><br><br>

    <label for="start_date">Start Date:</label><br>
    <input type="date" id="start_date" name="start_date" value="<?php echo date('Y-m-01'); ?>" required><br><br>

    <label for="end_date">End Date:</label><br>
    <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-t'); ?>" required><br><br>

    <input type="submit" value="Add Budget">
</form>