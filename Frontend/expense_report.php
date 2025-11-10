<?php
// expense_report.php - Detailed Expense Tracker
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker - Finance Dashboard</title>
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
            --color-danger: #ef4444; /* Red-500 */
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

        /* Report Styles */
        .report-container {
            background: var(--color-card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow-x: auto; /* Makes table scrollable on small screens */
            padding: 1.5rem;
        }

        /* Table Styling */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .data-table th, .data-table td {
            padding: 1rem 0.5rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .data-table th {
            background-color: var(--color-primary-light);
            color: var(--color-primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .data-table tbody tr:hover {
            background-color: #f8fafc; /* Slate-50 hover */
        }
        .data-table td:last-child, .data-table th:last-child {
            text-align: right;
        }

        /* Loading/Error State */
        .message-box {
            padding: 2rem;
            text-align: center;
            color: var(--color-text-secondary);
            font-style: italic;
        }

        /* Responsive adjustments */
        @media(min-width:768px){
            .nav-mobile{display:none;}
            .nav-desktop{display:flex;}
            .main-content{margin-left:250px;padding:2rem;padding-bottom:2rem;}
        }

        /* Sync Button Styling (for mobile header) */
        .mobile-sync-button {
            color: var(--color-primary);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.8rem;
            transition: transform 0.2s;
        }
        .mobile-sync-button:disabled {
            color: #94a3b8; /* Slate-400 */
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
    <a href="expense_report.php" class="active">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2l-1 4h10l-1-4h2a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1-4v4" /></svg>
        <span style="margin-left:10px;">Expense Tracker</span>
    </a>
    <a href="budgetvsexpense_report.php">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
        <span style="margin-left:10px;">Budget vs. Expense</span>
    </a>
    <a href="savings_progress_page.php">
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
    <a href="expense_report.php" class="active"><span>💸</span><span>Expenses</span></a>
    <a href="budgetvsexpense_report.php"><span>📊</span><span>Budgets</span></a>
    <a href="savings_progress_page.php"><span>🎯</span><span>Savings</span></a>
    <a href="monthly_expense_report_page.php"><span>📅</span><span>Report</span></a>
</nav>

<main class="main-content">
    <header class="report-header">
        <h2>Detailed Expense History</h2>
        <!-- Mobile sync button for consistency -->
        <button id="mobile-sync-button" class="mobile-sync-button" disabled>🔄</button>
    </header>

    <div class="report-container">
        <!-- Table for Expense Data -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Date</th>
                    <th style="width: 35%;">Description</th>
                    <th style="width: 25%;">Category</th>
                    <th style="width: 15%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody id="expenseTableBody">
                <tr>
                    <td colspan="4" class="message-box">Loading expenses...</td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<script>
    // --- Configuration ---
    const BACKEND_PATH = '../backend/expense_report.php';

    // --- Utility Functions ---

    /** Parses a row of HTML table data into a structured expense object. */
    function parseExpenseRow(row) {
        const tds = row.querySelectorAll('td');
        if (tds.length < 4) return null; // Ensure we have enough columns

        const date = tds[0].textContent.trim();
        const description = tds[1].textContent.trim();
        const category = tds[2].textContent.trim();
        let amount = tds[3].textContent.trim();

        // Clean up amount string (remove $, currency symbols, etc.)
        amount = amount.replace(/[^0-9.-]/g, '');

        return {
            date,
            description,
            category,
            amount: parseFloat(amount) || 0,
        };
    }

    /** Renders the expense data into the table body. */
    function renderExpenses(expenses) {
        const tbody = document.getElementById('expenseTableBody');
        tbody.innerHTML = ''; // Clear existing content

        if (expenses.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="message-box">No detailed expense records found.</td></tr>`;
            return;
        }
        
        // Sort by date descending (assuming date is in a sortable format like YYYY-MM-DD or MM/DD/YYYY)
        // Since we are parsing from HTML, we trust the backend data order, but we can improve sorting if needed.
        // For simple data extraction, we will use the order provided by the Oracle report.

        expenses.forEach(expense => {
            const amountColor = expense.amount < 0 ? 'var(--color-success)' : 'var(--color-danger)';
            const formattedAmount = (expense.amount * (expense.amount < 0 ? -1 : 1)).toFixed(2);
            const sign = expense.amount < 0 ? '+' : '-'; // Expense is usually '-', Income is '+'

            tbody.innerHTML += `
                <tr>
                    <td>${expense.date}</td>
                    <td>${expense.description}</td>
                    <td><span class="font-medium text-xs rounded-full px-3 py-1 bg-gray-100 text-gray-700">${expense.category}</span></td>
                    <td style="color:${amountColor}; font-weight:600; text-align:right;">
                        ${sign} $${formattedAmount}
                    </td>
                </tr>
            `;
        });
    }

    /** Fetches the HTML expense report and extracts the table data. */
    async function fetchExpenseReport() {
        const tbody = document.getElementById('expenseTableBody');
        tbody.innerHTML = `<tr><td colspan="4" class="message-box">Fetching data from Oracle...</td></tr>`;

        try {
            const response = await fetch(BACKEND_PATH);
            const htmlText = await response.text();

            // 1. Create a temporary element to hold and parse the HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');

            // 2. Find the relevant table rows (assuming the report is the main table content)
            const rows = doc.querySelectorAll('tr'); 
            const expenses = [];

            // Skip the header rows and start processing data
            let isDataStart = false; 

            rows.forEach(row => {
                // Simple logic to detect when actual data rows start (e.g., first row with <td>s)
                if (!isDataStart && row.querySelector('td')) {
                    isDataStart = true;
                }
                
                if (isDataStart) {
                    const expense = parseExpenseRow(row);
                    if (expense) {
                        expenses.push(expense);
                    }
                }
            });

            console.log(`Extracted ${expenses.length} expense records.`);
            renderExpenses(expenses);

        } catch (e) {
            console.error("Error loading expense report:", e);
            tbody.innerHTML = `<tr><td colspan="4" class="message-box" style="color:var(--color-danger);">Failed to load data. Please check the backend connection.</td></tr>`;
        }
    }

    // --- Initialization ---
    window.onload = fetchExpenseReport;
</script>
</body>
</html>