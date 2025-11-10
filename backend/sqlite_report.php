<?php
include('connect_sqlite.php'); // Make sure this file sets $sqlite_conn correctly


function getAllExpensesSQLite($sqlite_conn)
{
    $query = "SELECT id, category, amount, expense_date, note, sync_status FROM expenses";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getAllBudgetsSQLite($sqlite_conn)
{
    $query = "SELECT id, category, amount, start_date, end_date, status FROM budgets";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getAllSavingsSQLite($sqlite_conn)
{
    $query = "SELECT id, goal_name, target_amount, current_amount, target_date, last_entered_date, status FROM savings";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getExpenseForMonthSQLite($sqlite_conn, $month, $year)
{
    $month = sprintf('%02d', $month);
    $query = "SELECT id, category, amount, expense_date, note 
              FROM expenses 
              WHERE strftime('%m', expense_date) = '$month'
              AND strftime('%Y', expense_date) = '$year'";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getExpenseForCategorySQLite($sqlite_conn, $category)
{
    $query = "SELECT id, category, amount, expense_date, note 
              FROM expenses 
              WHERE category = '$category'";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getBudgetForCategorySQLite($sqlite_conn, $category)
{
    $query = "SELECT id, category, amount, start_date, end_date, status 
              FROM budgets 
              WHERE category = '$category'";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getBudgetForMonthSQLite($sqlite_conn, $month, $year)
{
    $month = sprintf('%02d', $month);
    $query = "SELECT id, category, amount, start_date, end_date, status 
              FROM budgets 
              WHERE strftime('%m', start_date) <= '$month'
              AND strftime('%m', end_date) >= '$month'
              AND strftime('%Y', start_date) <= '$year'
              AND strftime('%Y', end_date) >= '$year'";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getOverusedBudgetsSQLite($sqlite_conn)
{
    $query = "SELECT b.id, b.category, b.amount, 
                     (SELECT SUM(e.amount) FROM expenses e 
                      WHERE e.category = b.category 
                        AND e.expense_date BETWEEN b.start_date AND b.end_date) AS total_expense,
                     b.status
              FROM budgets b
              HAVING total_expense > b.amount";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}


function getCompletedSavingsSQLite($sqlite_conn)
{
    $query = "SELECT id, goal_name, target_amount, current_amount, target_date, last_entered_date, status
              FROM savings
              WHERE current_amount >= target_amount";
    $stmt = $sqlite_conn->query($query);
    $rows = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    return $rows;
}
