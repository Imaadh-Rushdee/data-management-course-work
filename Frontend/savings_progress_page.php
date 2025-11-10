<?php
// savings_progress_page.php - Savings Goal Tracking Report
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savings Progress - Finance Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Base Styles and Colors */
        :root {
            --color-primary: #0d9488; /* Teal-600 */
            --color-primary-light: #f0fdfa; /* Teal-50 */
            --color-background: #f1f5f9; /* Slate-100 */
            --color-card-bg: #fff;
            --color-text-dark: #1e293b; /* Slate-800 */
            --color-text-secondary: #64748b; /* Slate-500 */
            --color-success: #22c55e; /* Green-500 */
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            --border-radius: .75rem;
            --border-color: #e2e8f0; /* Slate-200 */
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Inter', sans-serif; background-color:var(--color-background);color:var(--color-text-dark);font-size:16px;}

        /* Navigation Base */
        .nav-desktop{position:fixed;top:0;left:0;height:100vh;width:250px;background:var(--color-card-bg);
            border-right:1px solid var(--border-color);padding:1.5rem;flex-direction:column;display:none;z-index:900;}
        .nav-desktop h1{font-size:1.75rem;font-weight:700;color:var(--color-primary);margin-bottom:2rem;}
        .nav-desktop a{text-decoration:none;color:var(--color-text-secondary);font-weight:500;
            padding:.85rem 1rem;border-radius:.5rem;display:flex;align-items:center;margin-bottom:.5rem;transition:all 0.2s;}
        .nav-desktop a:hover{background:#f8fafc;color:var(--color-text-dark);}
        .nav-desktop a.active{background:var(--color-primary-light);color:var(--color-primary);font-weight:600;}
        .nav-desktop .sync-button-container{margin-top:auto;padding-top:1rem;border-top:1px solid var(--border-color);}

        .nav-mobile{position:fixed;bottom:0;left:0;right:0;height:65px;background:var(--color-card-bg);
            border-top:1px solid var(--border-color);display:flex;justify-content:space-around;align-items:center;
            box-shadow:0 -5px 10px rgba(0,0,0,.05);z-index:1000;}
        .nav-mobile a{text-decoration:none;color:var(--color-text-secondary);font-size:.75rem;display:flex;
            flex-direction:column;align-items:center;padding:.5rem;transition:color 0.2s;}
        .nav-mobile a.active{color:var(--color-primary);}
        
        /* Main Content and Header */
        .main-content{padding:1rem;padding-bottom:80px;}
        .report-header{margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;}
        .report-header h2{font-size:1.8rem;font-weight:600;}

        /* Card and Goal Styles */
        .card {
            background: var(--color-card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Goal Progress Bar */
        .progress-bar-container {
            width: 100%;
            height: 10px;
            background-color: var(--color-background);
            border-radius: 9999px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        .progress-bar-fill {
            height: 100%;
            background-color: var(--color-primary);
            transition: width 0.6s ease-out;
            border-radius: 9999px;
        }
        .goal-completed .progress-bar-fill {
            background-color: var(--color-success);
        }

        /* Goal Card specific styling */
        .goal-card-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        /* Responsive adjustments */
        @media(min-width:768px){
            .nav-mobile{display:none;}
            .nav-desktop{display:flex;}
            .main-content{margin-left:250px;padding:2rem;padding-bottom:2rem;}
        }

        .message-box {
            padding: 2rem;
            text-align: center;
            color: var(--color-text-secondary);
            font-style: italic;
        }

    </style>
</head>
<body>

<!-- Desktop Navigation Sidebar -->
<nav class="nav-desktop">
    <h1>MyFinance</h1>
    <a href="home.php">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span style="margin-left:10px;">Home</span>
    </a>
    <a href="expense_report.php">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2l-1 4h10l-1-4h2a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1-4v4" /></svg>
        <span style="margin-left:10px;">Expense Tracker</span>
    </a>
    <a href="budgetvsexpense_report.php">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
        <span style="margin-left:10px;">Budget vs. Expense</span>
    </a>
    <a href="savings_progress_page.php" class="active">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.24A1.99 1.99 0 0020 18V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 001.414-.586l.707-.707zm-7.414 0L9 16m0 0l-2-2" /></svg>
        <span style="margin-left:10px;">Savings Progress</span>
    </a>
    <a href="monthly_expense_report_page.php">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-4 4V3m-1 9H8m8 0h-3m3 4H8m1-4h.01M12 7h.01M16 7h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span style="margin-left:10px;">Monthly Report</span>
    </a>
    <div class="sync-button-container">
        <!-- Replaced with a placeholder button as the main sync logic is in home.php -->
        <button class="button" disabled>Data Sync</button> 
    </div>
</nav>

<!-- Mobile Navigation Bar -->
<nav class="nav-mobile">
    <a href="home.php"><span>🏠</span><span>Home</span></a>
    <a href="expense_report.php"><span>💸</span><span>Expenses</span></a>
    <a href="budgetvsexpense_report.php"><span>📊</span><span>Budgets</span></a>
    <a href="savings_progress_page.php" class="active"><span>🎯</span><span>Savings</span></a>
    <a href="monthly_expense_report_page.php"><span>📅</span><span>Report</span></a>
</nav>

<main class="main-content">
    <header class="report-header">
        <h2>Savings Goals Tracker</h2>
        <button id="addGoalButton" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition duration-200 text-sm md:text-base">
            + New Goal
        </button>
    </header>

    <div id="goalsContainer" class="goal-card-grid">
        <!-- Savings goals will be rendered here -->
        <div class="card"><p class="message-box">Loading savings goals...</p></div>
    </div>
</main>

<script>
    // --- Configuration ---
    const BACKEND_PATH = '../backend/savings_report.php';

    // --- Utility Functions ---

    /** Formats a number as currency. */
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        }).format(amount);
    }

    /** Calculates the percentage of goal completion. */
    function calculateProgress(saved, target) {
        if (target <= 0) return 0;
        return Math.min(100, (saved / target) * 100);
    }

    /** Simulates fetching savings goals from the backend (Oracle). */
    async function fetchSavingsGoals() {
        const goalsContainer = document.getElementById('goalsContainer');
        goalsContainer.innerHTML = '<div class="card"><p class="message-box">Fetching goals from Oracle...</p></div>';

        try {
            // In a real application, you'd fetch the Oracle report HTML here
            // const response = await fetch(BACKEND_PATH);
            // const htmlText = await response.text();
            
            // --- Placeholder Data Structure (A proxy for Oracle Report Parsing) ---
            const goalsData = [
                { id: 1, name: 'Emergency Fund', target: 5000.00, saved: 4200.00, deadline: '2026-06-30' },
                { id: 2, name: 'New Laptop', target: 1200.00, saved: 150.75, deadline: '2025-12-31' },
                { id: 3, name: 'Holiday Travel', target: 3000.00, saved: 3000.00, deadline: '2025-11-01' },
                { id: 4, name: 'Investment Deposit', target: 10000.00, saved: 500.00, deadline: '2027-01-01' },
            ];
            // --- End Placeholder Data ---
            
            renderGoals(goalsData);

        } catch (e) {
            console.error("Error loading savings goals:", e);
            goalsContainer.innerHTML = `<div class="card"><p class="message-box" style="color:#ef4444;">Failed to load savings goals. Please check the backend connection.</p></div>`;
        }
    }

    /** Renders the individual savings goal cards. */
    function renderGoals(goals) {
        const goalsContainer = document.getElementById('goalsContainer');
        goalsContainer.innerHTML = '';

        if (goals.length === 0) {
            goalsContainer.innerHTML = `<div class="card w-full"><p class="message-box">You currently have no active savings goals. Click '+ New Goal' to add one!</p></div>`;
            return;
        }

        goals.forEach(goal => {
            const progress = calculateProgress(goal.saved, goal.target);
            const isCompleted = progress >= 100;
            const completionClass = isCompleted ? 'goal-completed border-green-400' : 'border-teal-200';
            const statusIcon = isCompleted ? '✅' : '⏳';
            const remaining = goal.target - goal.saved;

            const goalHtml = `
                <div class="card ${completionClass} transition duration-300 hover:shadow-lg">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">${statusIcon} ${goal.name}</h3>
                        <span class="text-sm font-medium text-gray-500">Target: ${formatCurrency(goal.target)}</span>
                    </div>
                    
                    <p class="text-3xl font-extrabold text-teal-600 mb-4">${formatCurrency(goal.saved)}</p>

                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: ${progress}%;"></div>
                    </div>

                    <div class="flex justify-between text-sm mt-2 font-medium">
                        <span class="text-gray-600">${Math.round(progress)}% Complete</span>
                        <span class="${isCompleted ? 'text-green-600' : 'text-red-500'}">
                            ${isCompleted ? 'Goal Achieved' : 'Remaining: ' + formatCurrency(remaining)}
                        </span>
                    </div>

                    <div class="text-xs text-gray-400 mt-4 pt-3 border-t border-gray-100">
                        Deadline: ${new Date(goal.deadline).toLocaleDateString()}
                        <button class="float-right text-teal-500 hover:text-teal-700 font-semibold">View Details</button>
                    </div>
                </div>
            `;
            goalsContainer.innerHTML += goalHtml;
        });
    }

    /** Placeholder function for the Add Goal Button action. */
    function handleAddGoal() {
        // This is where we would typically open a modal or navigate to a form
        alert("Action: Open form to add a new savings goal (Not implemented in this view).");
    }


    // --- Initialization ---
    window.onload = () => {
        fetchSavingsGoals();
        document.getElementById('addGoalButton').addEventListener('click', handleAddGoal);
    };
</script>
</body>
</html>