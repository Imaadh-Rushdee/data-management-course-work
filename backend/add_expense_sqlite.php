<?php
include('connect_sqlite.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $note = $_POST['note'];

    $stmt = $sqlite_conn->prepare(
        "INSERT INTO expenses (category, amount, expense_date, note, sync_status) 
         VALUES (:category, :amount, :expense_date, :note, 'PENDING')"
    );
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':expense_date', $expense_date, SQLITE3_TEXT);
    $stmt->bindValue(':note', $note, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Expense added successfully.";
    } else {
        echo "Failed to add expense.";
    }
}
?>
