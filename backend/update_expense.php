<?php
include 'connect_oracle.php';

// Check form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_id = filter_var($_POST['expense_id'], FILTER_VALIDATE_INT);
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $expense_date_str = $_POST['expense_date']; // e.g., '2025-11-10'
    $note = htmlspecialchars($_POST['note']);

    if ($expense_id === false || !$category || $amount === false || !$expense_date_str) {
        die("Error: Invalid input.");
    }

    // Convert PHP date string to Oracle date format
    $expense_date = date('d-M-Y', strtotime($expense_date_str)); // '10-NOV-2025'

    // Prepare Oracle PL/SQL block
    $sql = "BEGIN update_expense(:p_id, :p_category, :p_amount, :p_expense_date, :p_note); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":p_id", $expense_id);
    oci_bind_by_name($stmt, ":p_category", $category);
    oci_bind_by_name($stmt, ":p_amount", $amount);
    oci_bind_by_name($stmt, ":p_expense_date", $expense_date); // pass as string in 'DD-MON-YYYY'
    oci_bind_by_name($stmt, ":p_note", $note);

    $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    if ($success) {
        echo "<p style='color: green;'>✅ Expense ID {$expense_id} updated successfully!</p>";
    } else {
        $e = oci_error($stmt);
        echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e['message']) . "</p>";
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>
