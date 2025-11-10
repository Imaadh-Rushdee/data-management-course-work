<?php
// Include the connection file
include 'connect_oracle.php';
include('../Frontend/navbar.php');

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $goal_name = htmlspecialchars($_POST['goal_name']);
    $target_amount = filter_var($_POST['target_amount'], FILTER_VALIDATE_FLOAT);
    $current_amount = filter_var($_POST['current_amount'], FILTER_VALIDATE_FLOAT);
    $target_date_str = htmlspecialchars($_POST['target_date']);

    // Basic validation
    if (!$goal_name || $target_amount === false || $current_amount === false || !$target_date_str) {
        die("Error: Please fill in all required fields correctly.");
    }

    // Convert the date string to Oracle's internal date format using TO_DATE
    // Assuming the input date is in 'YYYY-MM-DD' format from an HTML date input
    $date_conversion_sql = "TO_DATE(:target_date_str, 'YYYY-MM-DD')";

    // Set the name of your Oracle stored procedure
    // NOTE: Replace 'add_saving' with the actual procedure name if it's different.
    $procedure_name = 'add_savings';

    try {
        // 2. Prepare the PL/SQL block to call the procedure
        // The procedure signature is assumed to be: 
        // add_saving(p_goal_name, p_target_amount, p_current_amount, p_target_date)
        $sql = "BEGIN {$procedure_name}(:p_goal_name, :p_target_amount, :p_current_amount, " . $date_conversion_sql . "); END;";
        $stmt = oci_parse($conn, $sql);

        // 3. Bind the PHP variables to the Oracle procedure parameters
        oci_bind_by_name($stmt, ":p_goal_name", $goal_name);
        oci_bind_by_name($stmt, ":p_target_amount", $target_amount);
        oci_bind_by_name($stmt, ":p_current_amount", $current_amount);
        oci_bind_by_name($stmt, ":target_date_str", $target_date_str); // Bind the date string

        // 4. Execute the statement
        // OCI_COMMIT_ON_SUCCESS commits the transaction if the execution is successful
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            echo "<p style='color: green;'>✅ New Saving Goal added successfully!</p>";
        } else {
            // Get error details from Oracle
            $e = oci_error($stmt);
            echo "<p style='color: red;'>❌ Error adding saving goal: " . htmlspecialchars($e['message']) . "</p>";
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

<h2>Create New Saving Goal</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="goal_name">Goal Name:</label><br>
    <input type="text" id="goal_name" name="goal_name" required><br><br>

    <label for="target_amount">Target Amount (e.g., 5000.00):</label><br>
    <input type="number" step="0.01" id="target_amount" name="target_amount" required><br><br>

    <label for="current_amount">Current Amount (e.g., 500.00):</label><br>
    <input type="number" step="0.01" id="current_amount" name="current_amount" value="0.00" required><br><br>

    <label for="target_date">Target Date:</label><br>
    <input type="date" id="target_date" name="target_date" required><br><br>

    <input type="submit" value="Add Saving Goal">
</form>