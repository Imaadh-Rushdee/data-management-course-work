<?php

include 'connect_oracle.php';
include('../Frontend/navbar.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $expense_date_str = htmlspecialchars($_POST['expense_date']);
    $note = htmlspecialchars($_POST['note']);


    if (!$category || $amount === false || !$expense_date_str) {
        die("Error: Please fill in all required fields (Category, Amount, Date) correctly.");
    }


    $date_conversion_sql = "TO_DATE(:expense_date_str, 'YYYY-MM-DD')";

    try {

        $sql = "BEGIN add_expense(:p_category, :p_amount, " . $date_conversion_sql . ", :p_note); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":p_category", $category);
        oci_bind_by_name($stmt, ":p_amount", $amount);
        oci_bind_by_name($stmt, ":expense_date_str", $expense_date_str);
        oci_bind_by_name($stmt, ":p_note", $note);


        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);


        if ($success) {
            echo "<p style='color: green;'> Expense added successfully!</p>";
        } else {

            $e = oci_error($stmt);

            if ($e['code'] == 20001) {
                echo "<p style='color: red;'> Error: " . htmlspecialchars($e['message']) . "</p>";
            } else {
                echo "<p style='color: red;'> Error adding expense: " . htmlspecialchars($e['message']) . "</p>";
            }
        }


        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'> Connection Error: " . $e->getMessage() . "</p>";
    }


    oci_close($conn);
}
