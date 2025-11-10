<?php

include 'connect_oracle.php';
include('../Frontend/navbar.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Get and sanitize input data
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $start_date_str = htmlspecialchars($_POST['start_date']);
    $end_date_str = htmlspecialchars($_POST['end_date']);


    if (!$category || $amount === false || !$start_date_str || !$end_date_str) {
        die("Error: Please fill in all required fields correctly.");
    }



    $start_date_conversion_sql = "TO_DATE(:start_date_str, 'YYYY-MM-DD')";
    $end_date_conversion_sql = "TO_DATE(:end_date_str, 'YYYY-MM-DD')";

    // Set the name of your Oracle table
    $procedure_name = 'add_budget';

    try {
        // Prepare PL/SQL to call the procedure

        $sql = "BEGIN {$procedure_name}(:p_category, :p_amount, " . $start_date_conversion_sql . ", " . $end_date_conversion_sql . "); END;";
        $stmt = oci_parse($conn, $sql);

        //Bind the PHP variables to the Oracle procedure parameters
        oci_bind_by_name($stmt, ":p_category", $category);
        oci_bind_by_name($stmt, ":p_amount", $amount);
        oci_bind_by_name($stmt, ":start_date_str", $start_date_str);
        oci_bind_by_name($stmt, ":end_date_str", $end_date_str);

        //Execute the statement, committing on success
        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);


        if ($success) {
            echo "<p style='color: green;'>New Budget added successfully!</p>";
        } else {

            $e = oci_error($stmt);
            echo "<p style='color: red;'>Error adding budget: " . htmlspecialchars($e['message']) . "</p>";
        }


        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'> Connection Error: " . $e->getMessage() . "</p>";
    }


    oci_close($conn);
}
