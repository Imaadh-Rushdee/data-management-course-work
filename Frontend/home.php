<?php
$usingOracle = false;


if (@include('../backend/connect_oracle.php')) {
  include('../backend/oracle_report.php');
  $usingOracle = true;
} else {
  include('../backend/connect_sqlite.php');
  include('../backend/sqlite_report.php');
}

if ($usingOracle) {
  $expenses = getAllExpensesOracle($conn);
  $budgets = getAllBudgetsOracle($conn);
  $savings = getAllSavingsOracle($conn);
} else {
  $expenses = getAllExpensesSQLite($sqlite_conn);
  $budgets = getAllBudgetsSQLite($sqlite_conn);
  $savings = getAllSavingsSQLite($sqlite_conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Finance Dashboard</title>
<link rel="stylesheet" href="styles.css">

<script>
  window.addEventListener('pageshow', function(event) {
    // If the page was loaded from bfcache (back-forward cache), reload
    if (event.persisted) {
        location.reload();
    }
});
function openModal(id) { document.getElementById(id).style.display = 'block'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => { if (event.target == modal) modal.style.display = "none"; });
}

function syncData() {
    // First, call backup.php
    fetch('../backend/backup.php')
        .then(response => response.text())
        .then(data => {
            console.log("Backup response:", data);

            // After backup, call sync_data.php
            return fetch('../backend/sync_data.php');
        })
        .then(response => response.text())
        .then(data => {
            console.log("Sync response:", data);
            alert("✅ Backup and Sync completed successfully!");

            // Reload the page after sync
            location.reload();
        })
        .catch(error => {
            console.error("Error during sync:", error);
            alert("❌ Error occurred while syncing data!");
        });
}



// Expense modals
function openUpdateExpenseModal(id, category, amount, expenseDate, note) {
    document.getElementById('update_expense_id').value = id;
    document.getElementById('update_category').value = category;
    document.getElementById('update_amount').value = amount;
    document.getElementById('update_date').value = expenseDate;
    document.getElementById('update_note').value = note;
    openModal('updateExpenseModal');
}


function openDeleteModal(id) {
    document.getElementById('delete_expense_id').value = id;
    document.getElementById('deleteExpenseForm').action = "<?= $usingOracle ? '../backend/delete_expense.php' : '../backend/delete_expense_sqlite.php' ?>";
    openModal('deleteExpenseModal');
}

// Budget modals
function openUpdateBudgetModal(id, category, amount, startDate, endDate) {
    document.getElementById('update_budget_id').value = id;
    document.getElementById('update_budget_category').value = category;
    document.getElementById('update_budget_amount').value = amount;
    document.getElementById('update_start_date').value = startDate;
    document.getElementById('update_end_date').value = endDate;

    document.getElementById('updateBudgetForm').action = "<?= $usingOracle ? '../backend/update_budget.php' : '../backend/update_budget_sqlite.php' ?>";
    openModal('updateBudgetModal');
}


function openDeleteBudgetModal(id) {
    if (!id) return alert("Error: Budget ID missing");
    if (confirm("Are you sure you want to delete this budget?")) {
        window.location.href = "<?= $usingOracle ? '../backend/delete_budget.php?id=' : '../backend/delete_budget_sqlite.php?id=' ?>" + id;
    }
}

// Savings modals
function openUpdateSavingsModal(id, goal_name, target_amount, current_amount, target_date) {
    document.getElementById('update_saving_id').value = id;
    document.getElementById('update_goal_name').value = goal_name;
    document.getElementById('update_target_amount').value = target_amount;
    document.getElementById('update_current_amount').value = current_amount;
    document.getElementById('update_target_date').value = target_date;
    document.getElementById('updateSavingsForm').action = "<?= $usingOracle ? '../backend/update_savings.php' : '../backend/update_savings_sqlite.php' ?>";
    openModal('updateSavingsModal');
}

function openDeleteSavingsModal(id) {
    if (!id) return alert("Error: Saving ID missing");
    if (confirm("Are you sure you want to delete this saving goal?")) {
        window.location.href = "<?= $usingOracle ? '../backend/delete_savings.php?id=' : '../backend/delete_savings_sqlite.php?id=' ?>" + id;
    }
}
</script>
</head>

<body>
<nav>
  <div class="nav-left">
    <a href="#expenses">Expenses</a>
    <a href="#budgets">Budgets</a>
    <a href="#savings">Savings</a>

    <div class="dropdown">
      <button class="dropbtn">Reports ▼</button>
      <div class="dropdown-content">
        <a href="../backend/saving_progress_report.php">Savings Progress</a>
        <a href="../backend/monthly_expense_report.php">Monthly Expense</a>
        <a href="../backend/expense_summary_report.php">Expense Summary</a>
        <a href="../backend/budget_vs_expense_report.php">Budget vs Expense</a>
      </div>
    </div>
  </div>

  <!-- Center DB Indicator -->
  <div class="db-indicator">
    <?php if ($usingOracle): ?>
      <span class="db-icon">🟢 Using Oracle DB (Online)</span>
    <?php else: ?>
      <span class="db-icon">🟡 Using SQLite DB (Offline)</span>
    <?php endif; ?>
  </div>

  <button class="sync-btn" onclick="syncData()">Sync</button>
</nav>




<div class="container">
  <h2 id="expenses">Expenses <button onclick="openModal('expenseModal')">+ Add Expense</button></h2>
  <table>
    <tr><th>Category</th><th>Amount</th><th>Date</th><th>Note</th><th>Sync Status</th><th>Actions</th></tr>
    <?php if (!empty($expenses)): ?>
      <?php foreach ($expenses as $exp): ?>
        <?php $exp = array_change_key_case($exp, CASE_LOWER); ?>
        <tr>
          <td><?= htmlspecialchars($exp['category'] ?? '') ?></td>
          <td><?= htmlspecialchars($exp['amount'] ?? '') ?></td>
          <td><?= htmlspecialchars($exp['expense_date'] ?? '') ?></td>
          <td><?= htmlspecialchars($exp['note'] ?? '') ?></td>
          <td><?= htmlspecialchars($exp['sync_status'] ?? '') ?></td>
          <td>
            <?php
$expense_date = isset($exp['expense_date']) ? date('Y-m-d', strtotime($exp['expense_date'])) : '';
?>

            <button onclick="openUpdateExpenseModal(
    '<?= $exp['expense_id'] ?? '' ?>',
    '<?= $exp['category'] ?? '' ?>',
    '<?= $exp['amount'] ?? '' ?>',
    '<?= $expense_date ?>',
    '<?= $exp['note'] ?? '' ?>'
)">Update</button>

            <button onclick="openDeleteModal('<?= $exp['expense_id'] ?? '' ?>')">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="6">No expenses found.</td></tr>
    <?php endif; ?>
  </table>

  <h2 id="budgets">Budgets <button onclick="openModal('budgetModal')">+ Add Budget</button></h2>
  <table>
    <tr><th>Category</th><th>Amount</th><th>Start Date</th><th>End Date</th><th>Sync Status</th><th>Actions</th></tr>
    <?php if (!empty($budgets)): ?>
      <?php foreach ($budgets as $bud): ?>
        <?php $bud = array_change_key_case($bud, CASE_LOWER); ?>
        <tr>
          <td><?= htmlspecialchars($bud['category'] ?? '') ?></td>
          <td><?= htmlspecialchars($bud['amount'] ?? '') ?></td>
          <td><?= htmlspecialchars($bud['start_date'] ?? '') ?></td>
          <td><?= htmlspecialchars($bud['end_date'] ?? '') ?></td>
          <td><?= htmlspecialchars($bud['status'] ?? '') ?></td>
          <td>
            <?php
$start_date = isset($bud['start_date']) ? date('Y-m-d', strtotime($bud['start_date'])) : '';
$end_date   = isset($bud['end_date']) ? date('Y-m-d', strtotime($bud['end_date'])) : '';
?>
<button onclick="openUpdateBudgetModal(
    '<?= $bud['budget_id'] ?? '' ?>',
    '<?= $bud['category'] ?? '' ?>',
    '<?= $bud['amount'] ?? '' ?>',
    '<?= $start_date ?>',
    '<?= $end_date ?>'
)">Update</button>

            <button onclick="openDeleteBudgetModal('<?= $bud['budget_id'] ?? '' ?>')">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="5">No budgets found.</td></tr>
    <?php endif; ?>
  </table>

  <h2 id="savings">Savings <button onclick="openModal('savingsModal')">+ Add Saving</button></h2>
  <table>
    <tr><th>Goal Name</th><th>Target Amount</th><th>Current Amount</th><th>Target Date</th><th>Last Entered Date</th><th>Sync Status</th><th>Actions</th></tr>
    <?php if (!empty($savings)): ?>
      <?php foreach ($savings as $sav): ?>
        <?php $sav = array_change_key_case($sav, CASE_LOWER); ?>
        <tr>
          <td><?= htmlspecialchars($sav['goal_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($sav['target_amount'] ?? '') ?></td>
          <td><?= htmlspecialchars($sav['current_amount'] ?? '') ?></td>
          <td><?= htmlspecialchars($sav['target_date'] ?? '') ?></td>
          <td><?= htmlspecialchars($sav['last_entered_date'] ?? '') ?></td>
          <td><?= htmlspecialchars($sav['status'] ?? '') ?></td>
          <td>
            <?php
$target_date = isset($sav['target_date']) ? date('Y-m-d', strtotime($sav['target_date'])) : '';
?>

            <button onclick="openUpdateSavingsModal(
    '<?= $sav['saving_id'] ?? '' ?>',
    '<?= $sav['goal_name'] ?? '' ?>',
    '<?= $sav['target_amount'] ?? '' ?>',
    '<?= $sav['current_amount'] ?? '' ?>',
    '<?= $target_date ?>'
)">Update</button>

            <button onclick="openDeleteSavingsModal('<?= $sav['saving_id'] ?? '' ?>')">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7">No savings found.</td></tr>
    <?php endif; ?>
  </table>
</div>

<!-- Include all modals here (Add/Update/Delete Expenses, Budgets, Savings) -->
<?php include 'modals.php'; ?> <!-- If you want to split modals into a separate file -->

</body>
</html>
