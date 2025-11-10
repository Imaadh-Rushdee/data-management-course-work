<?php
include('connect_sqlite.php');
include('connect_oracle.php');

//get all data for syncing from SQlite
function getPendingExpensesSqlite($sqlite_conn)
{
    $data = [];
    $query = "SELECT id, category, amount, expense_date, note 
              FROM expenses 
              WHERE sync_status = 'PENDING'";
    $result = $sqlite_conn->query($query);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}
function getPendingBudgetsSqlite($sqlite_conn)
{
    $data = [];
    $query = "SELECT id, category, amount, start_date, end_date 
              FROM budgets 
              WHERE status = 'PENDING'";
    $result = $sqlite_conn->query($query);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}
function getPendingSavingsSqlite($sqlite_conn)
{
    $data = [];
    $query = "SELECT id, goal_name, target_amount, current_amount, target_date, last_entered_date 
              FROM savings 
              WHERE status = 'PENDING'";
    $result = $sqlite_conn->query($query);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

function getDeletedExpensesFromSQLite($sqlite_conn) {
    $data = [];
    $query = "SELECT id, oracle_id, category, amount, expense_date, note 
              FROM expenses 
              WHERE sync_status = 'DELETED'";
    $result = $sqlite_conn->query($query);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

function getDeletedBudgetsFromSQLite($sqlite_conn) {
    $data = [];
    $query = "SELECT id, oracle_id, category, amount, start_date, end_date 
              FROM budgets 
              WHERE status = 'DELETED'";
    $result = $sqlite_conn->query($query);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

function getDeletedSavingsFromSQLite($sqlite_conn) {
    $data = [];
    $query = "SELECT id, oracle_id, goal_name, target_amount, current_amount, target_date, last_entered_date 
              FROM savings 
              WHERE status = 'DELETED'";
    $result = $sqlite_conn->query($query);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

//get data from Oracle to sync for SQlite 
function getPendingExpensesOracle($conn) {
    $sql = "BEGIN get_pending_expenses(:cursor); END;";
    $stmt = oci_parse($conn, $sql);

    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    oci_execute($stmt);
    oci_execute($cursor);

    $data = [];
    while ($row = oci_fetch_assoc($cursor)) {
        $data[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($cursor);
    return $data;
}
function getPendingBudgetsOracle($conn) {
    $sql = "BEGIN get_pending_budgets(:cursor); END;";
    $stmt = oci_parse($conn, $sql);

    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    oci_execute($stmt);
    oci_execute($cursor);

    $data = [];
    while ($row = oci_fetch_assoc($cursor)) {
        $data[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($cursor);
    return $data;
}
function getPendingSavingsOracle($conn) {
    $sql = "BEGIN get_pending_savings(:cursor); END;";
    $stmt = oci_parse($conn, $sql);

    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

    oci_execute($stmt);
    oci_execute($cursor);

    $data = [];
    while ($row = oci_fetch_assoc($cursor)) {
        $data[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($cursor);
    return $data;
}



//functions to enter data to Oracle
function syncExpenseToOracle($conn, $category, $amount, $expense_date, $note) {
    $sql = "BEGIN sync_expenses(:category, :amount, TO_DATE(:expense_date,'YYYY-MM-DD'), :note); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":category", $category);
    oci_bind_by_name($stmt, ":amount", $amount);
    oci_bind_by_name($stmt, ":expense_date", $expense_date);
    oci_bind_by_name($stmt, ":note", $note);

    return oci_execute($stmt);
}
function syncBudgetToOracle($conn, $category, $amount, $start_date, $end_date) {
    $sql = "BEGIN sync_budgets(:category, :amount, TO_DATE(:start_date,'YYYY-MM-DD'), TO_DATE(:end_date,'YYYY-MM-DD')); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":category", $category);
    oci_bind_by_name($stmt, ":amount", $amount);
    oci_bind_by_name($stmt, ":start_date", $start_date);
    oci_bind_by_name($stmt, ":end_date", $end_date);

    return oci_execute($stmt);
}
function syncSavingToOracle($conn, $goal_name, $target_amount, $current_amount, $target_date, $last_entered_date) {
    $sql = "BEGIN sync_savings(:goal_name, :target_amount, :current_amount, TO_DATE(:target_date,'YYYY-MM-DD'), TO_DATE(:last_entered_date,'YYYY-MM-DD')); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":goal_name", $goal_name);
    oci_bind_by_name($stmt, ":target_amount", $target_amount);
    oci_bind_by_name($stmt, ":current_amount", $current_amount);
    oci_bind_by_name($stmt, ":target_date", $target_date);
    oci_bind_by_name($stmt, ":last_entered_date", $last_entered_date);

    return oci_execute($stmt);
}

function syncExpenseDeleteToOracle($conn, $row) {
    $sql = "BEGIN sync_expenses_delete(:oracle_id, :category, :amount, TO_DATE(:expense_date,'YYYY-MM-DD'), :note); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":oracle_id", $row['oracle_id']);
    oci_bind_by_name($stmt, ":category", $row['category']);
    oci_bind_by_name($stmt, ":amount", $row['amount']);
    oci_bind_by_name($stmt, ":expense_date", $row['expense_date']);
    oci_bind_by_name($stmt, ":note", $row['note']);

    oci_execute($stmt);
}

function syncBudgetDeleteToOracle($conn, $row) {
    $sql = "BEGIN sync_budgets_delete(:oracle_id, :category, :amount, TO_DATE(:start_date,'YYYY-MM-DD'), TO_DATE(:end_date,'YYYY-MM-DD')); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":oracle_id", $row['oracle_id']);
    oci_bind_by_name($stmt, ":category", $row['category']);
    oci_bind_by_name($stmt, ":amount", $row['amount']);
    oci_bind_by_name($stmt, ":start_date", $row['start_date']);
    oci_bind_by_name($stmt, ":end_date", $row['end_date']);

    oci_execute($stmt);
}

function syncSavingDeleteToOracle($conn, $row) {
    $sql = "BEGIN sync_savings_delete(:oracle_id, :goal_name, :target_amount, :current_amount, TO_DATE(:target_date,'YYYY-MM-DD'), TO_DATE(:last_entered_date,'YYYY-MM-DD')); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":oracle_id", $row['oracle_id']);
    oci_bind_by_name($stmt, ":goal_name", $row['goal_name']);
    oci_bind_by_name($stmt, ":target_amount", $row['target_amount']);
    oci_bind_by_name($stmt, ":current_amount", $row['current_amount']);
    oci_bind_by_name($stmt, ":target_date", $row['target_date']);
    oci_bind_by_name($stmt, ":last_entered_date", $row['last_entered_date']);

    oci_execute($stmt);
}


//functions to enter data to SQlite 
function insertExpenseToSQLite($sqlite_conn, $category, $amount, $expense_date, $note) {
    $stmt = $sqlite_conn->prepare("INSERT INTO expenses (category, amount, expense_date, note, sync_status) VALUES (:category, :amount, :expense_date, :note, 'SYNCED')");
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':expense_date', $expense_date, SQLITE3_TEXT);
    $stmt->bindValue(':note', $note, SQLITE3_TEXT);
    return $stmt->execute();
}
function insertBudgetToSQLite($sqlite_conn, $category, $amount, $start_date, $end_date) {
    $stmt = $sqlite_conn->prepare("INSERT INTO budgets (category, amount, start_date, end_date, status) VALUES (:category, :amount, :start_date, :end_date, 'SYNCED')");
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':start_date', $start_date, SQLITE3_TEXT);
    $stmt->bindValue(':end_date', $end_date, SQLITE3_TEXT);
    return $stmt->execute();
}
function insertSavingToSQLite($sqlite_conn, $goal_name, $target_amount, $current_amount, $target_date, $last_entered_date) {
    $stmt = $sqlite_conn->prepare("INSERT INTO savings (goal_name, target_amount, current_amount, target_date, last_entered_date, status) VALUES (:goal_name, :target_amount, :current_amount, :target_date, :last_entered_date, 'SYNCED')");
    $stmt->bindValue(':goal_name', $goal_name, SQLITE3_TEXT);
    $stmt->bindValue(':target_amount', $target_amount, SQLITE3_FLOAT);
    $stmt->bindValue(':current_amount', $current_amount, SQLITE3_FLOAT);
    $stmt->bindValue(':target_date', $target_date, SQLITE3_TEXT);
    $stmt->bindValue(':last_entered_date', $last_entered_date, SQLITE3_TEXT);
    return $stmt->execute();
}


//sync from Sqlite to Oracle 
echo "Starting sync process...\n";

// --- Pending data from SQLite to Oracle ---
$pendingExpenses = getPendingExpensesSqlite($sqlite_conn);
echo "Pending Expenses in SQLite: " . count($pendingExpenses) . "\n";
foreach ($pendingExpenses as $row) {
    $success = syncExpenseToOracle($conn, $row['category'], $row['amount'], $row['expense_date'], $row['note']);
    echo $success ? "Expense synced to Oracle: {$row['category']}, {$row['amount']}\n" : "Failed to sync expense: {$row['category']}\n";
}

$pendingBudgets = getPendingBudgetsSqlite($sqlite_conn);
echo "Pending Budgets in SQLite: " . count($pendingBudgets) . "\n";
foreach ($pendingBudgets as $row) {
    $success = syncBudgetToOracle($conn, $row['category'], $row['amount'], $row['start_date'], $row['end_date']);
    echo $success ? "Budget synced to Oracle: {$row['category']}, {$row['amount']}\n" : "Failed to sync budget: {$row['category']}\n";
}

$pendingSavings = getPendingSavingsSqlite($sqlite_conn);
echo "Pending Savings in SQLite: " . count($pendingSavings) . "\n";
foreach ($pendingSavings as $row) {
    $success = syncSavingToOracle($conn, $row['goal_name'], $row['target_amount'], $row['current_amount'], $row['target_date'], $row['last_entered_date']);
    echo $success ? "Saving synced to Oracle: {$row['goal_name']}, {$row['target_amount']}\n" : "Failed to sync saving: {$row['goal_name']}\n";
}

// --- Deleted data from SQLite to Oracle ---
$deletedExpenses = getDeletedExpensesFromSQLite($sqlite_conn);
echo "Deleted Expenses in SQLite: " . count($deletedExpenses) . "\n";
foreach ($deletedExpenses as $row) {
    syncExpenseDeleteToOracle($conn, $row);
    echo "Deleted expense synced to Oracle: {$row['oracle_id']}\n";
}

$deletedBudgets = getDeletedBudgetsFromSQLite($sqlite_conn);
echo "Deleted Budgets in SQLite: " . count($deletedBudgets) . "\n";
foreach ($deletedBudgets as $row) {
    syncBudgetDeleteToOracle($conn, $row);
    echo "Deleted budget synced to Oracle: {$row['oracle_id']}\n";
}

$deletedSavings = getDeletedSavingsFromSQLite($sqlite_conn);
echo "Deleted Savings in SQLite: " . count($deletedSavings) . "\n";
foreach ($deletedSavings as $row) {
    syncSavingDeleteToOracle($conn, $row);
    echo "Deleted saving synced to Oracle: {$row['oracle_id']}\n";
}

// --- Pending data from Oracle to SQLite ---
$oracleExpenses = getPendingExpensesOracle($conn);
echo "Pending Expenses in Oracle: " . count($oracleExpenses) . "\n";
foreach ($oracleExpenses as $row) {
    insertExpenseToSQLite($sqlite_conn, $row['CATEGORY'], $row['AMOUNT'], $row['EXPENSE_DATE'], $row['NOTE']);
    echo "Expense inserted into SQLite: {$row['CATEGORY']}, {$row['AMOUNT']}\n";
}

$oracleBudgets = getPendingBudgetsOracle($conn);
echo "Pending Budgets in Oracle: " . count($oracleBudgets) . "\n";
foreach ($oracleBudgets as $row) {
    insertBudgetToSQLite($sqlite_conn, $row['CATEGORY'], $row['AMOUNT'], $row['START_DATE'], $row['END_DATE']);
    echo "Budget inserted into SQLite: {$row['CATEGORY']}, {$row['AMOUNT']}\n";
}

$oracleSavings = getPendingSavingsOracle($conn);
echo "Pending Savings in Oracle: " . count($oracleSavings) . "\n";
foreach ($oracleSavings as $row) {
    insertSavingToSQLite($sqlite_conn, $row['GOAL_NAME'], $row['TARGET_AMOUNT'], $row['CURRENT_AMOUNT'], $row['TARGET_DATE'], $row['LAST_ENTERED_DATE']);
    echo "Saving inserted into SQLite: {$row['GOAL_NAME']}, {$row['TARGET_AMOUNT']}\n";
}

echo "Sync process completed.\n";

?>