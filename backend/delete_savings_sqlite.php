<?php
include('connect_sqlite.php');
include('../Frontend/navbar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $sqlite_conn->prepare(
        "UPDATE savings SET status='DELETED' WHERE id=:id"
    );
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        echo "Saving marked for deletion.";
    } else {
        echo "Failed to delete saving.";
    }
}
?>
