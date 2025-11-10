<?php
include('connect_sqlite.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $sqlite_conn->prepare(
        "UPDATE budgets SET status='DELETED' WHERE id=:id"
    );
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        echo "Budget marked for deletion.";
    } else {
        echo "Failed to delete budget.";
    }
}
?>
