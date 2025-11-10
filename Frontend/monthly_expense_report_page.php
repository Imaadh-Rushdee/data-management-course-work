<?php
// monthly_expense_report_page.php - Aggregated Monthly Expense Report
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Expense Report - Finance Dashboard</title>
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
        .report-header{margin-bottom:1.5rem; display:flex; flex-direction:column; gap: 1rem;}
        .report-header h2{font-size:1.8rem;font-weight:600;}

        /* Card Styles */
        .card {
            background: var(--color-card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive adjustments */
        @media(min-width:768px){
            .nav-mobile{display:none;}
            .nav-desktop{display:flex;}
            .main-content{margin-left:250px;padding:2rem;padding-bottom:2rem;}
            .report-header{flex-direction:row; justify-content:space-between; align-items:center;}
        }

        .message-box {
            padding: 2rem;
            text-align: center;
            color: var(--color-text-secondary);
            font-style: italic;
        }

        .report-table th, .report-table td {
            padding: 0.75rem 1rem;
            text-align: left;
        }
        .report-table th {
            font-weight: 600;
            background-color: #f8fafc;
            border-bottom: 2px solid var(--border-color);
            color: var(--color-text-dark);
        }
        .report-table tr:hover {
            background-color: var(--color-primary-light);
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
    <a href="savings_progress_page.php">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.24A1.99 1.99 0 0020 18V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 001.414-.586l.707-.707zm-7.414 0L9 16m0 0l-2-2" /></svg>
        <span style="margin-left:10px;">Savings Progress</span>
    </a>
    <a href="monthly_expense_report_page.php" class="active">
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
    <a href="savings_progress_page.php"><span>🎯</span><span>Savings</span></a>
    <a href="monthly_expense_report_page.php" class="active"><span>📅</span><span>Report</span></a>
</nav>

<main class="main-content">
    <header class="report-header">
        <h2>Aggregated Monthly Expenses</h2>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <select id="monthSelector" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition duration-150">
                <!-- Options populated by JS -->
            </select>
            <select id="yearSelector" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition duration-150">
                <!-- Options populated by JS -->
            </select>
        </div>
    </header>

    <!-- Report Summary -->
    <div id="reportSummary" class="card p-6 mb-6">
        <p class="message-box">Select a month and year to generate the report.</p>
    </div>

    <!-- Category Breakdown Table -->
    <div class="card p-0 overflow-hidden">
        <h3 class="text-xl font-semibold p-4 border-b border-gray-100 bg-gray-50">Category Breakdown</h3>
        <div id="categoryBreakdown" class="overflow-x-auto">
            <!-- Table content populated by JS -->
            <p class="message-box !py-6 !mt-0 !mb-0">Awaiting data...</p>
        </div>
    </div>
</main>

<script>
    // --- Configuration ---
    const BACKEND_PATH = '../backend/monthly_report.php'; // Placeholder path

    // --- Data and State ---
    const MONTHS = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // --- Utility Functions ---

    /** Formats a number as currency. */
    function formatCurrency(amount) {
        // Ensure amount is treated as a number
        const numAmount = parseFloat(amount);
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        }).format(numAmount);
    }

    /** Populates month and year selectors with initial values. */
    function populateSelectors() {
        const monthSelector = document.getElementById('monthSelector');
        const yearSelector = document.getElementById('yearSelector');
        const now = new Date();
        const currentMonth = now.getMonth(); // 0-indexed
        const currentYear = now.getFullYear();

        // Populate Months
        monthSelector.innerHTML = MONTHS.map((name, index) => 
            `<option value="${index + 1}" ${index === currentMonth ? 'selected' : ''}>${name}</option>`
        ).join('');

        // Populate Years (Current year and the past 5 years)
        for (let year = currentYear; year >= currentYear - 5; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            if (year === currentYear) option.selected = true;
            yearSelector.appendChild(option);
        }
    }

    // --- Main Report Logic ---

    /** Simulates fetching the monthly aggregated report from Oracle. */
    async function fetchMonthlyReport(month, year) {
        const reportSummary = document.getElementById('reportSummary');
        const breakdownContainer = document.getElementById('categoryBreakdown');
        
        // Update UI to loading state
        reportSummary.innerHTML = '<p class="message-box">Loading report data...</p>';
        breakdownContainer.innerHTML = '<p class="message-box !py-6 !mt-0 !mb-0">Loading categories...</p>';

        try {
            // In a real application, you would send month and year parameters to the backend:
            // const response = await fetch(`${BACKEND_PATH}?month=${month}&year=${year}`);
            // const reportData = await response.json(); 

            // --- Placeholder Data (Simulating Oracle Aggregation) ---
            const monthName = MONTHS[month - 1];
            
            let totalExpense = 0;
            const breakdownData = [
                { category: 'Groceries', amount: 650.45, percentage: 35 },
                { category: 'Rent/Mortgage', amount: 1200.00, percentage: 60 },
                { category: 'Utilities', amount: 150.20, percentage: 7 },
                { category: 'Transport', amount: 80.50, percentage: 4 },
                { category: 'Entertainment', amount: 110.00, percentage: 5 },
                { category: 'Health', amount: 0.00, percentage: 0 },
            ].filter(item => item.amount > 0);
            
            totalExpense = breakdownData.reduce((sum, item) => sum + item.amount, 0);

            // Re-calculate percentages based on the actual total for presentation clarity
            breakdownData.forEach(item => {
                 item.percentage = totalExpense > 0 ? Math.round((item.amount / totalExpense) * 100) : 0;
            });
            
            const reportData = {
                month: month,
                year: year,
                total: totalExpense,
                categories: breakdownData,
            };
            // --- End Placeholder Data ---
            
            renderReport(reportData, monthName);

        } catch (e) {
            console.error("Error fetching monthly report:", e);
            reportSummary.innerHTML = `<div class="p-4 bg-red-100 text-red-700 rounded-lg"><p>Error: Could not load data for ${MONTHS[month-1]} ${year}. Check backend connection (${BACKEND_PATH}).</p></div>`;
            breakdownContainer.innerHTML = '<p class="message-box !py-6 !mt-0 !mb-0">Failed to load category data.</p>';
        }
    }

    /** Renders the fetched report data to the UI. */
    function renderReport(data, monthName) {
        const reportSummary = document.getElementById('reportSummary');
        const breakdownContainer = document.getElementById('categoryBreakdown');

        // 1. Render Summary Card
        const summaryHTML = `
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <p class="text-sm font-medium text-gray-500">Total Expenses for</p>
                    <h4 class="text-3xl font-extrabold text-teal-600">${monthName} ${data.year}</h4>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm font-medium text-gray-500">Total Outflow</p>
                    <h3 class="text-4xl font-extrabold ${data.total > 0 ? 'text-red-600' : 'text-gray-500'}">
                        ${formatCurrency(data.total)}
                    </h3>
                </div>
            </div>
        `;
        reportSummary.innerHTML = summaryHTML;

        // 2. Render Category Breakdown Table
        if (data.categories.length === 0) {
            breakdownContainer.innerHTML = `<p class="message-box !py-6 !mt-0 !mb-0">No expenses recorded for ${monthName} ${data.year}.</p>`;
            return;
        }

        const tableRows = data.categories.map(item => `
            <tr class="border-t border-gray-100">
                <td class="font-medium">${item.category}</td>
                <td>${formatCurrency(item.amount)}</td>
                <td class="text-right">${item.percentage}%</td>
            </tr>
        `).join('');

        const tableHTML = `
            <table class="report-table w-full border-collapse">
                <thead>
                    <tr>
                        <th class="w-1/2">Category</th>
                        <th class="w-1/4">Amount Spent</th>
                        <th class="w-1/4 text-right">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                    <!-- Total Row -->
                    <tr class="border-t-4 border-teal-500 font-bold bg-teal-50">
                        <td>Total</td>
                        <td>${formatCurrency(data.total)}</td>
                        <td class="text-right">100%</td>
                    </tr>
                </tbody>
            </table>
        `;
        breakdownContainer.innerHTML = tableHTML;
    }


    // --- Event Listeners and Initialization ---

    function handleSelectionChange() {
        const month = document.getElementById('monthSelector').value;
        const year = document.getElementById('yearSelector').value;
        if (month && year) {
            fetchMonthlyReport(month, year);
        }
    }

    window.onload = () => {
        populateSelectors();
        
        // Attach change listeners to automatically update the report
        document.getElementById('monthSelector').addEventListener('change', handleSelectionChange);
        document.getElementById('yearSelector').addEventListener('change', handleSelectionChange);
        
        // Load the report for the currently selected month/year on page load
        handleSelectionChange(); 
    };
</script>
</body>
</html>