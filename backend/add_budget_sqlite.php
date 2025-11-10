<?php
include('connect_sqlite.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $sqlite_conn->prepare(
        "INSERT INTO budgets (category, amount, start_date, end_date, status) 
         VALUES (:category, :amount, :start_date, :end_date, 'PENDING')"
    );
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':start_date', $start_date, SQLITE3_TEXT);
    $stmt->bindValue(':end_date', $end_date, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Budget added successfully.";
    } else {
        echo "Failed to add budget.";
    }
}
?>
