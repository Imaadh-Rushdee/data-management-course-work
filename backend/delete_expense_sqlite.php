<?php
include('connect_sqlite.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $sqlite_conn->prepare(
        "UPDATE expenses SET sync_status='DELETED' WHERE id=:id"
    );
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        echo "Expense marked for deletion.";
    } else {
        echo "Failed to delete expense.";
    }
}
?>
