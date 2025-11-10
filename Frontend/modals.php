<!-- Expenses Modals -->
 <script>
function loadCategories() {
    fetch('<?= $usingOracle ? "../backend/get_categories.php" : "../backend/get_categories_sqlite.php" ?>')
        .then(response => response.json())
        .then(data => {
            const addSelect = document.getElementById('expense_category');
            const updateSelect = document.getElementById('update_category');

            addSelect.innerHTML = '<option value="">-- Select Category --</option>';
            updateSelect.innerHTML = '<option value="">-- Select Category --</option>';

            data.forEach(cat => {
                const option1 = document.createElement('option');
                option1.value = cat;
                option1.text = cat;
                addSelect.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = cat;
                option2.text = cat;
                updateSelect.appendChild(option2);
            });
        });
}

// Call it on page load
window.onload = loadCategories;
</script>

<div id="expenseModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('expenseModal')">&times;</span>
    <h3>Add New Expense</h3>
    <form method="POST" action="<?= $usingOracle ? '../backend/add_expense.php' : '../backend/add_expense_sqlite.php' ?>">
        <label>Category:</label><br>
<select name="category" id="expense_category" required>
    <option value="">-- Select Category --</option>
</select><br><br>


        <label>Amount:</label><br>
        <input type="number" step="0.01" name="amount" required><br><br>

        <label>Date:</label><br>
        <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required><br><br>

        <label>Note:</label><br>
        <input type="text" name="note"><br><br>

        <input type="submit" value="Add Expense">
    </form>
  </div>
</div>

<div id="updateExpenseModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('updateExpenseModal')">&times;</span>
    <h3>Update Expense</h3>

    <form method="POST" id="updateExpenseForm" action="<?= $usingOracle ? '../backend/update_expense.php' : '../backend/update_expense_sqlite.php' ?>">
        <input type="hidden" name="expense_id" id="update_expense_id">

        <label>Category:</label><br>
        <select name="category" id="update_category" required>
            <option value="">-- Select Category --</option>
        </select><br><br>

        <label>Amount:</label><br>
        <input type="number" step="0.01" name="amount" id="update_amount" required><br><br>

        <label>Date:</label><br>
        <input type="date" name="expense_date" id="update_date" required><br><br>

        <label>Note:</label><br>
        <input type="text" name="note" id="update_note"><br><br>

        <input type="submit" value="Update Expense">
    </form>
  </div>
</div>


<div id="deleteExpenseModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('deleteExpenseModal')">&times;</span>
    <h3>Delete Expense</h3>
    <p>Are you sure you want to delete this expense?</p>
    <form method="POST" id="deleteExpenseForm">
        <input type="hidden" name="id" id="delete_expense_id">
        <input type="submit" value="Yes, Delete">
        <button type="button" onclick="closeModal('deleteExpenseModal')">Cancel</button>
    </form>
  </div>
</div>

<!-- Budgets Modals -->
<!-- Budgets Modal -->
<!-- Add Budget Modal -->
<div id="budgetModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('budgetModal')">&times;</span>
    <h3>Add New Budget</h3>
    <form method="POST" action="<?= $usingOracle ? '../backend/add_budget.php' : '../backend/add_budget_sqlite.php' ?>">
        <label>Category:</label><br>
        <input type="text" name="category" required><br><br>

        <label>Amount:</label><br>
        <input type="number" step="0.01" name="amount" required><br><br>

        <label>Start Date:</label><br>
        <input type="date" name="start_date" value="<?= date('Y-m-01') ?>" required><br><br>

        <label>End Date:</label><br>
        <input type="date" name="end_date" value="<?= date('Y-m-t') ?>" required><br><br>

        <input type="submit" value="Add Budget">
    </form>
  </div>
</div>

<!-- Update Budget Modal -->
<div id="updateBudgetModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('updateBudgetModal')">&times;</span>
    <h3>Update Budget</h3>
    <form method="POST" id="updateBudgetForm">
        <input type="hidden" name="budget_id" id="update_budget_id">

        <label>Category:</label><br>
        <input type="text" name="category" id="update_budget_category" required><br><br>

        <label>Amount:</label><br>
        <input type="number" step="0.01" name="amount" id="update_budget_amount" required><br><br>

        <label>Start Date:</label><br>
        <input type="date" name="start_date" id="update_start_date" required><br><br>

        <label>End Date:</label><br>
        <input type="date" name="end_date" id="update_end_date" required><br><br>

        <input type="submit" value="Update Budget">
    </form>
  </div>
</div>

<!-- Savings Modals -->
<div id="savingsModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('savingsModal')">&times;</span>
    <h3>Add New Saving</h3>
    <form method="POST" action="<?= $usingOracle ? '../backend/add_savings.php' : '../backend/add_savings_sqlite.php' ?>">
        <label>Goal Name:</label><br>
        <input type="text" name="goal_name" required><br><br>

        <label>Target Amount:</label><br>
        <input type="number" step="0.01" name="target_amount" required><br><br>

        <label>Current Amount:</label><br>
        <input type="number" step="0.01" name="current_amount" value="0" required><br><br>

        <label>Target Date:</label><br>
        <input type="date" name="target_date" value="<?= date('Y-m-d') ?>" required><br><br>

        <input type="submit" value="Add Saving">
    </form>
  </div>
</div>

<div id="updateSavingsModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('updateSavingsModal')">&times;</span>
    <h3>Update Saving Goal</h3>
    <form method="POST" id="updateSavingsForm">
        <input type="hidden" name="saving_id" id="update_saving_id">

        <label>Goal Name:</label><br>
        <input type="text" name="goal_name" id="update_goal_name" required><br><br>

        <label>Target Amount:</label><br>
        <input type="number" step="0.01" name="target_amount" id="update_target_amount" required><br><br>

        <label>Current Amount:</label><br>
        <input type="number" step="0.01" name="current_amount" id="update_current_amount" required><br><br>

        <label>Target Date:</label><br>
        <input type="date" name="target_date" id="update_target_date" required><br><br>

        <input type="submit" value="Update Saving">
    </form>
  </div>
</div>
