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
$pendingExpenses = getPendingExpensesSqlite($sqlite_conn);
foreach ($pendingExpenses as $row) {
    $success = syncExpenseToOracle(
        $conn,
        $row['category'],
        $row['amount'],
        $row['expense_date'],
        $row['note']
    );
}
$pendingBudgets = getPendingBudgetsSqlite($sqlite_conn);
foreach ($pendingBudgets as $row) {
    $success = syncBudgetToOracle(
        $conn,
        $row['category'],
        $row['amount'],
        $row['start_date'],
        $row['end_date']
    );
}
$pendingSavings = getPendingSavingsSqlite($sqlite_conn);
foreach ($pendingSavings as $row) {
    $success = syncSavingToOracle(
        $conn,
        $row['goal_name'],
        $row['target_amount'],
        $row['current_amount'],
        $row['target_date'],
        $row['last_entered_date']
    );
}

$deletedExpenses = getDeletedExpensesFromSQLite($sqlite_conn);
foreach ($deletedExpenses as $row) {
    syncExpenseDeleteToOracle($conn, $row);
}

$deletedBudgets = getDeletedBudgetsFromSQLite($sqlite_conn);
foreach ($deletedBudgets as $row) {
    syncBudgetDeleteToOracle($conn, $row);
}

$deletedSavings = getDeletedSavingsFromSQLite($sqlite_conn);
foreach ($deletedSavings as $row) {
    syncSavingDeleteToOracle($conn, $row);
}

//sync from oracle to sqlite 

$oracleExpenses = getPendingExpensesOracle($conn);
foreach ($oracleExpenses as $row) {
    insertExpenseToSQLite(
        $sqlite_conn,
        $row['CATEGORY'],
        $row['AMOUNT'],
        $row['EXPENSE_DATE'],
        $row['NOTE']
    );
}

$oracleBudgets = getPendingBudgetsOracle($conn);
foreach ($oracleBudgets as $row) {
    insertBudgetToSQLite(
        $sqlite_conn,
        $row['CATEGORY'],
        $row['AMOUNT'],
        $row['START_DATE'],
        $row['END_DATE']
    );
}

$oracleSavings = getPendingSavingsOracle($conn);
foreach ($oracleSavings as $row) {
    insertSavingToSQLite(
        $sqlite_conn,
        $row['GOAL_NAME'],
        $row['TARGET_AMOUNT'],
        $row['CURRENT_AMOUNT'],
        $row['TARGET_DATE'],
        $row['LAST_ENTERED_DATE']
    );
}

?>