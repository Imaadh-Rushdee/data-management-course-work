<?php
include('connect_oracle.php'); // Make sure this defines connectOracle() and returns $conn

// Helper function to execute a cursor procedure and return results as associative array
function fetchCursor($conn, $procName, $params = []) {
    $cursor = oci_new_cursor($conn);
    $stmt = oci_parse($conn, "BEGIN $procName(:cursor" . (count($params) ? ', ' . implode(', ', array_map(fn($p) => ':' . $p, array_keys($params))) : '') . "); END;");
    
    // Bind the cursor
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
    
    // Bind parameters if any
    foreach($params as $key => $val) {
        oci_bind_by_name($stmt, ":$key", $params[$key]);
    }

    oci_execute($stmt);
    oci_execute($cursor);

    $results = [];
    while (($row = oci_fetch_assoc($cursor)) != false) {
        $results[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($cursor);

    return $results;
}

// ======================= All 10 "get" functions =======================

// 1. Get all expenses
function getAllExpensesOracle($conn){
    return fetchCursor($conn, "get_all_expenses");
}

// 2. Get all budgets
function getAllBudgetsOracle($conn){
    return fetchCursor($conn, "get_all_budgets");
}

// 3. Get all savings
function getAllSavingsOracle($conn){
    return fetchCursor($conn, "get_all_savings");
}

// 4. Get pending expenses
function getPendingExpensesOracle($conn){
    return fetchCursor($conn, "get_pending_expenses");
}

// 5. Get pending budgets
function getPendingBudgetsOracle($conn){
    return fetchCursor($conn, "get_pending_budgets");
}

// 6. Get pending savings
function getPendingSavingsOracle($conn){
    return fetchCursor($conn, "get_pending_savings");
}

// 7. Get expense for a month
function getExpenseForMonthOracle($conn, $month, $year){
    return fetchCursor($conn, "get_expense_for_month", ['p_month'=>$month, 'p_year'=>$year]);
}

// 8. Get expense for a category
function getExpenseForCategoryOracle($conn, $category){
    return fetchCursor($conn, "get_expense_for_category", ['p_category'=>$category]);
}

// 9. Get budget details for a category
function getBudgetForCategoryOracle($conn, $category){
    return fetchCursor($conn, "get_budget_for_category", ['p_category'=>$category]);
}

// 10. Get overused budgets
function getOverusedBudgetsOracle($conn){
    return fetchCursor($conn, "get_overused_budgets");
}

// Optional: function to get completed/over-saved savings
function getCompletedSavingsOracle($conn){
    return fetchCursor($conn, "get_completed_savings");
}

?>
