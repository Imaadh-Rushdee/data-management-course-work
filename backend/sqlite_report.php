<?php
include('connect_sqlite.php'); // Make sure this file sets $sqlite_conn correctly

// Get all expenses
function getAllExpenses($sqlite_conn){
    $query = "SELECT id, category, amount, expense_date, note, sync_status FROM expenses";
    $stmt = $sqlite_conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all budgets
function getAllBudgets($sqlite_conn){
    $query = "SELECT id, category, amount, start_date, end_date, status FROM budgets";
    $stmt = $sqlite_conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all savings
function getAllSavings($sqlite_conn){
    $query = "SELECT id, goal_name, target_amount, current_amount, target_date, last_entered_date, status FROM savings";
    $stmt = $sqlite_conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get expense for a specific month and year
function getExpenseForMonth($sqlite_conn, $month, $year){
    $query = "SELECT id, category, amount, expense_date, note FROM expenses 
              WHERE strftime('%m', expense_date) = :month 
              AND strftime('%Y', expense_date) = :year";
    $stmt = $sqlite_conn->prepare($query);
    $stmt->execute([':month'=>sprintf('%02d',$month), ':year'=>$year]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get expense for a category
function getExpenseForCategory($sqlite_conn, $category){
    $query = "SELECT id, category, amount, expense_date, note FROM expenses WHERE category = :category";
    $stmt = $sqlite_conn->prepare($query);
    $stmt->execute([':category'=>$category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get budget details for a category
function getBudgetForCategory($sqlite_conn, $category){
    $query = "SELECT id, category, amount, start_date, end_date, status FROM budgets WHERE category = :category";
    $stmt = $sqlite_conn->prepare($query);
    $stmt->execute([':category'=>$category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get budget for a specific month (any budget active in that month)
function getBudgetForMonth($sqlite_conn, $month, $year){
    $query = "SELECT id, category, amount, start_date, end_date, status FROM budgets 
              WHERE strftime('%m', start_date) <= :month 
              AND strftime('%m', end_date) >= :month
              AND strftime('%Y', start_date) <= :year
              AND strftime('%Y', end_date) >= :year";
    $stmt = $sqlite_conn->prepare($query);
    $stmt->execute([':month'=>sprintf('%02d',$month), ':year'=>$year]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get overused budgets (where total expense > budget amount)
function getOverusedBudgets($sqlite_conn){
    $query = "SELECT b.id, b.category, b.amount, SUM(e.amount) AS total_expense, b.status
              FROM budgets b
              LEFT JOIN expenses e ON b.category = e.category 
              AND e.expense_date BETWEEN b.start_date AND b.end_date
              GROUP BY b.id
              HAVING total_expense > b.amount";
    $stmt = $sqlite_conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get completed or oversaved savings
function getCompletedSavings($sqlite_conn){
    $query = "SELECT id, goal_name, target_amount, current_amount, target_date, last_entered_date, status
              FROM savings
              WHERE current_amount >= target_amount";
    $stmt = $sqlite_conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
