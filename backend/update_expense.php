<?php
include 'connect_oracle.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_id = filter_var($_POST['expense_id'], FILTER_VALIDATE_INT);
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $expense_date_str = $_POST['expense_date'];
    $note = htmlspecialchars($_POST['note']);

    if ($expense_id === false || !$category || $amount === false || !$expense_date_str) {
        die("Error: Invalid input.");
    }


    $expense_date = date('d-M-Y', strtotime($expense_date_str));


    $sql = "BEGIN update_expense(:p_id, :p_category, :p_amount, :p_expense_date, :p_note); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":p_id", $expense_id);
    oci_bind_by_name($stmt, ":p_category", $category);
    oci_bind_by_name($stmt, ":p_amount", $amount);
    oci_bind_by_name($stmt, ":p_expense_date", $expense_date);
    oci_bind_by_name($stmt, ":p_note", $note);

    $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    if ($success) {
        echo "<p style='color: green;'> Expense ID {$expense_id} updated successfully!</p>";
    } else {
        $e = oci_error($stmt);
        echo "<p style='color: red;'> Error: " . htmlspecialchars($e['message']) . "</p>";
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
