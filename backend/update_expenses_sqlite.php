<?php
include('connect_sqlite.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $note = $_POST['note'];

    $stmt = $sqlite_conn->prepare(
        "UPDATE expenses SET category=:category, amount=:amount, expense_date=:expense_date, note=:note, sync_status='PENDING' 
         WHERE id=:id"
    );
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':expense_date', $expense_date, SQLITE3_TEXT);
    $stmt->bindValue(':note', $note, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Expense updated successfully.";
    } else {
        echo "Failed to update expense.";
    }
}
?>
