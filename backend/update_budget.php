<?php

include 'connect_oracle.php';
include('../Frontend/navbar.php');


$procedure_name = 'update_budget';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and sanitize input data
    $budget_id = filter_var($_POST['budget_id'], FILTER_VALIDATE_INT);
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $start_date_str = htmlspecialchars($_POST['start_date']);
    $end_date_str = htmlspecialchars($_POST['end_date']);


    if ($budget_id === false || !$category || $amount === false || !$start_date_str || !$end_date_str) {
        die("Error: Please fill in all fields correctly.");
    }


    $start_date_conversion_sql = "TO_DATE(:start_date_str, 'YYYY-MM-DD')";
    $end_date_conversion_sql = "TO_DATE(:end_date_str, 'YYYY-MM-DD')";

    try {

        $sql = "BEGIN {$procedure_name}(:p_budget_id, :p_category, :p_amount, " . $start_date_conversion_sql . ", " . $end_date_conversion_sql . "); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":p_budget_id", $budget_id);
        oci_bind_by_name($stmt, ":p_category", $category);
        oci_bind_by_name($stmt, ":p_amount", $amount);
        oci_bind_by_name($stmt, ":start_date_str", $start_date_str);
        oci_bind_by_name($stmt, ":end_date_str", $end_date_str);


        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);


        if ($success) {
            echo "<p style='color: green;'> Budget ID  {$budget_id}  updated successfully!</p>";
        } else {

            $e = oci_error($stmt);
            echo "<p style='color: red;'> Error updating budget: " . htmlspecialchars($e['message']) . "</p>";
        }


        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'> Connection Error: " . $e->getMessage() . "</p>";
    }


    oci_close($conn);
}
