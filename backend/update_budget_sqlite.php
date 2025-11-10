<?php
try{
include('connect_sqlite.php');
include('../Frontend/navbar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $sqlite_conn->prepare(
        "UPDATE budgets SET category=:category, amount=:amount, start_date=:start_date, end_date=:end_date, status='PENDING' 
         WHERE id=:id"
    );
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':start_date', $start_date, SQLITE3_TEXT);
    $stmt->bindValue(':end_date', $end_date, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Budget updated successfully.";
    } else {
        echo "Failed to update budget.";
    }
}
return true;
} catch (PDOException $e) {
    return false;
}
?>
