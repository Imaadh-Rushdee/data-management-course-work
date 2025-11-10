
<?php
$usingOracle = false;

if (@include('../backend/connect_oracle.php')) {
  include('../backend/oracle_report.php');
  $usingOracle = true;
} else {
  include('../backend/connect_sqlite.php');
  include('../backend/sqlite_report.php');
}
?>
<style>
    
nav {
    background-color: #0d9488;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between; /* left items and sync button separated */
    align-items: center;
}

/* Left side links + dropdown */
.nav-left {
    display: flex;
    align-items: center;
}

.nav-left a {
    color: white;
    text-decoration: none;
    margin-right: 15px;
    font-weight: bold;
}

/* Dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropbtn {
    background-color: #0d9488;
    color: white;
    padding: 8px 16px;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #ffffff;
    min-width: 180px;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
    z-index: 100;
    border-radius: 4px;
    overflow: hidden;
}

.dropdown-content a {
    color: #0d9488;
    padding: 10px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f2f2f2;
}

.dropdown:hover .dropdown-content {
    display: block;
}

/* Sync button */
.sync-btn {
    background-color: #fff;
    color: #0d9488;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.sync-btn:hover {
    background-color: #f0f0f0;
}
</style>
<nav>
  <div class="nav-left">
    <a href="home.php">Home</a>
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
