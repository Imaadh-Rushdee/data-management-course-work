<?php

include 'connect_oracle.php';
include('../Frontend/navbar.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $goal_name = htmlspecialchars($_POST['goal_name']);
    $target_amount = filter_var($_POST['target_amount'], FILTER_VALIDATE_FLOAT);
    $current_amount = filter_var($_POST['current_amount'], FILTER_VALIDATE_FLOAT);
    $target_date_str = htmlspecialchars($_POST['target_date']);


    if (!$goal_name || $target_amount === false || $current_amount === false || !$target_date_str) {
        die("Error: Please fill in all required fields correctly.");
    }

    $date_conversion_sql = "TO_DATE(:target_date_str, 'YYYY-MM-DD')";

    $procedure_name = 'add_savings';

    try {

        $sql = "BEGIN {$procedure_name}(:p_goal_name, :p_target_amount, :p_current_amount, " . $date_conversion_sql . "); END;";
        $stmt = oci_parse($conn, $sql);


        oci_bind_by_name($stmt, ":p_goal_name", $goal_name);
        oci_bind_by_name($stmt, ":p_target_amount", $target_amount);
        oci_bind_by_name($stmt, ":p_current_amount", $current_amount);
        oci_bind_by_name($stmt, ":target_date_str", $target_date_str);


        $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        // 5. Check execution result
        if ($success) {
            echo "<p style='color: green;'> New Saving Goal added successfully!</p>";
        } else {

            $e = oci_error($stmt);
            echo "<p style='color: red;'> Error adding saving goal: " . htmlspecialchars($e['message']) . "</p>";
        }


        oci_free_statement($stmt);
    } catch (Exception $e) {
        echo "<p style='color: red;'> Connection Error: " . $e->getMessage() . "</p>";
    }


    oci_close($conn);
}
