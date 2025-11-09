<?php
// add_expense.php
header('Content-Type: application/json');

// Include both connection files
require_once 'connect_sqlite.php';
require_once 'connect_oracle.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $amount = $_POST['amount'] ?? 0.00;
    $expense_date = $_POST['expense_date'] ?? date('Y-m-d');
    $note = $_POST['note'] ?? null;

    if (empty($category) || $amount <= 0) {
        $response['message'] = 'Invalid category or amount.';
        echo json_encode($response);
        exit;
    }

    try {
        // 1. Insert into SQLite (Local storage) with PENDING status
        $sql_sqlite = "INSERT INTO expenses (category, amount, expense_date, note, sync_status) 
                       VALUES (:category, :amount, :date, :note, 'PENDING')";
        $stmt_sqlite = $pdo_sqlite->prepare($sql_sqlite);
        $stmt_sqlite->execute([
            ':category' => $category,
            ':amount' => $amount,
            ':date' => $expense_date,
            ':note' => $note
        ]);

        // 2. Call Oracle Stored Procedure (Central storage)
        // This calls the 'add_expense' procedure in dmcw.sql
        $sql_oracle = "BEGIN add_expense(:category, :amount, TO_DATE(:date_str, 'YYYY-MM-DD'), :note); END;";
        $stmt_oracle = $pdo_oracle->prepare($sql_oracle);
        $stmt_oracle->bindParam(':category', $category);
        $stmt_oracle->bindParam(':amount', $amount);
        $stmt_oracle->bindParam(':date_str', $expense_date);
        $stmt_oracle->bindParam(':note', $note);
        $stmt_oracle->execute();

        $response['success'] = true;
        $response['message'] = 'Expense added successfully to both local and central databases.';
    } catch (PDOException $e) {
        $response['message'] = 'Database Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);

