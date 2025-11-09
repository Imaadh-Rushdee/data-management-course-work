<?php
// delete_expense.php
header('Content-Type: application/json');
require_once 'connect_sqlite.php';
require_once 'connect_oracle.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expense_id = $_POST['expense_id'] ?? 0;

    if ($expense_id == 0) {
        $response['message'] = 'Invalid expense ID.';
        echo json_encode($response);
        exit;
    }

    try {
        // 1. Delete from SQLite (Local storage)
        $sql_sqlite = "DELETE FROM expenses WHERE expense_id = :id";
        $stmt_sqlite = $pdo_sqlite->prepare($sql_sqlite);
        $stmt_sqlite->execute([':id' => $expense_id]);

        // 2. Call Oracle Stored Procedure (Central storage)
        $sql_oracle = "BEGIN delete_expense(:id); END;";
        $stmt_oracle = $pdo_oracle->prepare($sql_oracle);
        $stmt_oracle->bindParam(':id', $expense_id);
        $stmt_oracle->execute();

        $response['success'] = true;
        $response['message'] = 'Expense deleted successfully from both local and central databases.';
    } catch (PDOException $e) {
        $response['message'] = 'Database Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
