<?php
include('connect_oracle.php');


function fetchCursor($conn, $procName, $params = [])
{
    $cursor = oci_new_cursor($conn);
    $stmt = oci_parse($conn, "BEGIN $procName(:cursor" . (count($params) ? ', ' . implode(', ', array_map(fn($p) => ':' . $p, array_keys($params))) : '') . "); END;");


    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);


    foreach ($params as $key => $val) {
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




function getAllExpensesOracle($conn)
{
    return fetchCursor($conn, "get_all_expenses");
}


function getAllBudgetsOracle($conn)
{
    return fetchCursor($conn, "get_all_budgets");
}


function getAllSavingsOracle($conn)
{
    return fetchCursor($conn, "get_all_savings");
}


function getPendingExpensesOracle($conn)
{
    return fetchCursor($conn, "get_pending_expenses");
}


function getPendingBudgetsOracle($conn)
{
    return fetchCursor($conn, "get_pending_budgets");
}


function getPendingSavingsOracle($conn)
{
    return fetchCursor($conn, "get_pending_savings");
}


function getExpenseForMonthOracle($conn, $month, $year)
{
    return fetchCursor($conn, "get_expense_for_month", ['p_month' => $month, 'p_year' => $year]);
}


function getExpenseForCategoryOracle($conn, $category)
{
    return fetchCursor($conn, "get_expense_for_category", ['p_category' => $category]);
}


function getBudgetForCategoryOracle($conn, $category)
{
    return fetchCursor($conn, "get_budget_for_category", ['p_category' => $category]);
}


function getOverusedBudgetsOracle($conn)
{
    return fetchCursor($conn, "get_overused_budgets");
}


function getCompletedSavingsOracle($conn)
{
    return fetchCursor($conn, "get_completed_savings");
}
