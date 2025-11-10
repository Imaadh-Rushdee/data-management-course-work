<?php
include('connect_sqlite.php');
include('../Frontend/navbar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $goal_name = $_POST['goal_name'];
    $target_amount = $_POST['target_amount'];
    $current_amount = $_POST['current_amount'];
    $target_date = $_POST['target_date'];
    $last_entered_date = $_POST['last_entered_date'];

    $stmt = $sqlite_conn->prepare(
        "UPDATE savings 
         SET goal_name=:goal_name, target_amount=:target_amount, current_amount=:current_amount, 
             target_date=:target_date, last_entered_date=:last_entered_date, status='PENDING'
         WHERE id=:id"
    );
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':goal_name', $goal_name, SQLITE3_TEXT);
    $stmt->bindValue(':target_amount', $target_amount, SQLITE3_FLOAT);
    $stmt->bindValue(':current_amount', $current_amount, SQLITE3_FLOAT);
    $stmt->bindValue(':target_date', $target_date, SQLITE3_TEXT);
    $stmt->bindValue(':last_entered_date', $last_entered_date, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Saving updated successfully.";
    } else {
        echo "Failed to update saving.";
    }
}
?>
