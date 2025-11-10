<?php
include 'connect_oracle.php';
include('../Frontend/navbar.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $saving_id = filter_var($_POST['saving_id'], FILTER_VALIDATE_INT);
    $goal_name = htmlspecialchars($_POST['goal_name']);
    $target_amount = filter_var($_POST['target_amount'], FILTER_VALIDATE_FLOAT);
    $current_amount = filter_var($_POST['current_amount'], FILTER_VALIDATE_FLOAT);
    $target_date_str = htmlspecialchars($_POST['target_date']);


    $last_entered_date_str = date('Y-m-d');

    if ($saving_id === false || !$goal_name || $target_amount === false || $current_amount === false || !$target_date_str) {
        die("Error: Please fill in all fields correctly.");
    }

    $sql = "BEGIN update_saving(
        :p_saving_id,
        :p_goal_name,
        :p_target_amount,
        :p_current_amount,
        TO_DATE(:p_target_date, 'YYYY-MM-DD'),
        TO_DATE(:p_last_entered_date, 'YYYY-MM-DD')
    ); END;";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":p_saving_id", $saving_id);
    oci_bind_by_name($stmt, ":p_goal_name", $goal_name);
    oci_bind_by_name($stmt, ":p_target_amount", $target_amount);
    oci_bind_by_name($stmt, ":p_current_amount", $current_amount);
    oci_bind_by_name($stmt, ":p_target_date", $target_date_str);
    oci_bind_by_name($stmt, ":p_last_entered_date", $last_entered_date_str);

    $success = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    if ($success) {
        echo "<p style='color: green;'> Saving Goal ID {$saving_id} updated successfully!</p>";
    } else {
        $e = oci_error($stmt);
        echo "<p style='color: red;'> Error updating saving goal: " . htmlspecialchars($e['message']) . "</p>";
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
