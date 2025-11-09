<?php
// update_expense.php
header('Content-Type: application/json');
require_once 'connect_sqlite.php';
require_once 'connect_oracle.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request (assuming both DBs use the same ID logic for now)
    $expense_id = $_POST['expense_id'] ?? 0;
    $category = $_POST['category'] ?? '';
    $amount = $_POST['amount'] ?? 0.00;
    $expense_date = $_POST['expense_date'] ?? date('Y-m-d');
    $note = $_POST['note'] ?? null;

    if ($expense_id == 0 || empty($category) || $amount <= 0) {
        $response['message'] = 'Invalid input data.';
        echo json_encode($response);
        exit;
    }

    try {
        // 1. Update SQLite (Local storage) with PENDING status
        $sql_sqlite = "UPDATE expenses 
                       SET category = :category, amount = :amount, expense_date = :date, note = :note, sync_status = 'PENDING' 
                       WHERE expense_id = :id";
        $stmt_sqlite = $pdo_sqlite->prepare($sql_sqlite);
        $stmt_sqlite->execute([
            ':category' => $category,
            ':amount' => $amount,
            ':date' => $expense_date,
            ':note' => $note,
            ':id' => $expense_id
        ]);

        // 2. Call Oracle Stored Procedure (Central storage)
        $sql_oracle = "BEGIN update_expense(:id, :category, :amount, TO_DATE(:date_str, 'YYYY-MM-DD'), :note); END;";
        $stmt_oracle = $pdo_oracle->prepare($sql_oracle);
        $stmt_oracle->bindParam(':id', $expense_id);
        $stmt_oracle->bindParam(':category', $category);
        $stmt_oracle->bindParam(':amount', $amount);
        $stmt_oracle->bindParam(':date_str', $expense_date);
        $stmt_oracle->bindParam(':note', $note);
        $stmt_oracle->execute();

        $response['success'] = true;
        $response['message'] = 'Expense updated successfully in both local and central databases.';
    } catch (PDOException $e) {
        $response['message'] = 'Database Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
