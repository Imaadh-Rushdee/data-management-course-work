<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Finance Dashboard</title>

    <!-- 
      Plain CSS for a "Mint.com" inspired look.
    -->
    <style>
        /* 1. Theme & Color Palette (Mint-inspired) */
        :root {
            --color-primary: #0d9488; /* Teal */
            --color-primary-light: #f0fdfa; /* Teal light background */
            --color-background: #f1f5f9; /* Slate 100 (light grey) */
            --color-card-bg: #ffffff;
            --color-text-dark: #1e293b; /* Slate 800 */
            --color-text-secondary: #64748b; /* Slate 500 */
            --color-danger: #ef4444; /* Red */
            --color-success: #22c55e; /* Green */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --border-radius: 0.75rem; /* 12px */
            --border-color: #e2e8f0; /* Slate 200 */
        }

        /* 2. Base & Layout Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--color-background);
            color: var(--color-text-dark);
            font-size: 16px;
        }

        /* 3. Navigation (Dual System: Desktop Sidebar + Mobile Bottom Bar) */

        /* --- Desktop Sidebar --- */
        .nav-desktop {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: var(--color-card-bg);
            border-right: 1px solid var(--border-color);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            display: none; /* Hidden by default, shown by media query */
        }
        .nav-desktop h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 2rem;
        }
        .nav-desktop a {
            text-decoration: none;
            color: var(--color-text-secondary);
            font-weight: 500;
            padding: 0.85rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .nav-desktop a:hover {
            background-color: #f8fafc; /* slate-50 */
            color: var(--color-text-dark);
        }
        .nav-desktop a.active {
            background-color: var(--color-primary-light);
            color: var(--color-primary);
            font-weight: 600;
        }
        .nav-desktop .sync-button-container {
            margin-top: auto; /* Pushes sync button to the bottom */
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        /* --- Mobile Bottom Bar --- */
        .nav-mobile {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65px;
            background-color: var(--color-card-bg);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            z-index: 1000;
        }
        .nav-mobile a {
            text-decoration: none;
            color: var(--color-text-secondary);
            font-size: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
        }
        .nav-mobile a.active {
            color: var(--color-primary);
        }

        /* --- Main Content Area --- */
        .main-content {
            padding: 1rem;
            padding-bottom: 80px; /* Space for mobile nav */
        }
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .main-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* 4. Dashboard Components (Cards, Grids) */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr; /* 1 column on mobile */
            gap: 1.5rem;
        }
        .card {
            background-color: var(--color-card-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .card-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--color-text-dark);
        }
        .card-link {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--color-primary);
        }
        .card-link:hover {
            text-decoration: underline;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: var(--color-background);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        .progress-bar-inner {
            height: 100%;
            background-color: var(--color-primary);
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        .text-large {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--color-text-dark);
            margin-top: 0.5rem;
        }
        .text-meta {
            font-size: 0.9rem;
            color: var(--color-text-secondary);
        }

        /* Transaction List */
        .transaction-list {
            list-style: none;
            padding: 0;
        }
        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        .transaction-item:last-child {
            border-bottom: none;
        }
        .transaction-details {
            display: flex;
            align-items: center;
        }
        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
        }
        .icon-food { background-color: #fff1f2; color: #f43f5e; } /* Red */
        .icon-utils { background-color: #eff6ff; color: #3b82f6; } /* Blue */
        .icon-income { background-color: #f0fdf4; color: #22c55e; } /* Green */
        
        .transaction-info p {
            line-height: 1.4;
        }
        .transaction-info .category {
            font-weight: 500;
        }
        .transaction-info .note {
            font-size: 0.9rem;
            color: var(--color-text-secondary);
        }
        .transaction-amount {
            font-weight: 600;
        }

        /* Button */
        .button {
            display: block;
            width: 100%;
            text-align: center;
            background-color: var(--color-primary);
            color: white;
            font-weight: 600;
            padding: 0.85rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .button:hover {
            background-color: #0f766e; /* Darker teal */
        }
        .button:disabled {
            background-color: #94a3b8; /* Slate 400 */
            cursor: not-allowed;
        }

        /* 5. Responsive Media Query */
        @media (min-width: 768px) {
            .nav-mobile {
                display: none; /* Hide mobile nav */
            }
            .nav-desktop {
                display: flex; /* Show desktop nav */
            }
            .main-content {
                margin-left: 250px; /* Make space for desktop nav */
                padding: 2rem;
                padding-bottom: 2rem; /* Remove mobile nav padding */
            }
            .main-header {
                display: none; /* Hide mobile header */
            }
            .dashboard-grid {
                 /* 2 columns on tablet/desktop */
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .dashboard-grid {
                 /* 3 columns on large desktop */
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>

    <!-- 
      DESKTOP SIDEBAR NAVIGATION
      Points to your exact file structure.
    -->
    <nav class="nav-desktop">
        <h1>MyFinance</h1>
        <a href="home.php" class="active">
            <span>🏠</span>
            <span style="margin-left: 10px;">Home</span>
        </a>
        <a href="expense_report.php">
            <span>💸</span>
            <span style="margin-left: 10px;">Expense Report</span>
        </a>
        <a href="budgetvsexpense_report.php">
            <span>📊</span>
            <span style="margin-left: 10px;">Budget vs. Expense</span>
        </a>
        <a href="savings_progress_page.php">
            <span>🎯</span>
            <span style="margin-left: 10px;">Savings Progress</span>
        </a>
        <a href="monthly_expense_report_page.php">
            <span>📅</span>
            <span style="margin-left: 10px;">Monthly Report</span>
        </a>
        
        <!-- Sync Button (Connects to backend/sync_data.php) -->
        <div class="sync-button-container">
            <button id="sync-button" class="button">
                <span id="sync-text">Sync Data</span>
            </button>
        </div>
    </nav>

    <!-- 
      MOBILE BOTTOM BAR NAVIGATION
      Points to your exact file structure.
    -->
    <nav class="nav-mobile">
        <a href="home.php" class="active">
            <span>🏠</span>
            <span>Home</span>
        </a>
        <a href="expense_report.php">
            <span>💸</span>
            <span>Expenses</span>
        </a>
        <a href="budgetvsexpense_report.php">
            <span>📊</span>
            <span>Budgets</span>
        </a>
        <a href="savings_progress_page.php">
            <span>🎯</span>
            <span>Savings</span>
        </a>
        <a href="monthly_expense_report_page.php">
            <span>📅</span>
            <span>Report</span>
        </a>
    </nav>

    <!-- 
      MAIN PAGE CONTENT
      This is the dashboard.
    -->
    <main class="main-content">
        <!-- Mobile Header (hidden on desktop) -->
        <header class="main-header">
            <h2>Overview</h2>
            <!-- Mobile Sync Button -->
            <button id="mobile-sync-button" style="background:none; border:none; cursor:pointer; color: var(--color-primary); font-size: 1.5rem;">🔄</button>
        </header>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">

            <!-- Card 1: Budget vs. Expense -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Budget vs. Expense</span>
                    <a href="budgetvsexpense_report.php" class="card-link">View Details</a>
                </div>
                <!-- Placeholder Data -->
                <p class="text-meta">October Budget</p>
                <div class="text-large" style="color: var(--color-success);">$1,240.50</div>
                <p class="text-meta">Remaining of $4,000.00</p>
                <div class="progress-bar">
                    <!-- Example: 1240.50 / 4000 = 31% ... so 100 - 31 = 69% spent -->
                    <div class="progress-bar-inner" style="width: 69%;"></div>
                </div>
            </div>

            <!-- Card 2: Savings Progress -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Savings Goal</span>
                    <a href="savings_progress_page.php" class="card-link">View All</a>
                </div>
                <!-- Placeholder Data -->
                <p class="text-meta">Goal: New Phone</p>
                <div class="text-large">$850.00</div>
                <p class="text-meta">Target of $1,500.00</p>
                <div class="progress-bar">
                    <!-- Example: 850 / 1500 = 56.6% -->
                    <div class="progress-bar-inner" style="width: 56.6%; background-color: #3b82f6;"></div>
                </div>
            </div>

            <!-- Card 3: Recent Transactions -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Recent Transactions</span>
                    <a href="expense_report.php" class="card-link">View All</a>
                </div>
                <!-- Placeholder Data -->
                <ul class="transaction-list">
                    <!-- Placeholder Item 1 -->
                    <li class="transaction-item">
                        <div class="transaction-details">
                            <span class="transaction-icon icon-food">🛒</span>
                            <div class="transaction-info">
                                <p class="category">Groceries</p>
                                <p class="note">Weekly shopping</p>
                            </div>
                        </div>
                        <span class="transaction-amount" style="color: var(--color-danger);">- $120.50</span>
                    </li>
                    <!-- Placeholder Item 2 -->
                    <li class="transaction-item">
                        <div class="transaction-details">
                            <span class="transaction-icon icon-income">💼</span>
                            <div class="transaction-info">
                                <p class="category">Paycheck</p>
                                <p class="note">October Salary</p>
                            </div>
                        </div>
                        <span class="transaction-amount" style="color: var(--color-success);">+ $2,500.00</span>
                    </li>
                    <!-- Placeholder Item 3 -->
                    <li class="transaction-item">
                        <div class="transaction-details">
                            <span class="transaction-icon icon-utils">💡</span>
                            <div class="transaction-info">
                                <p class="category">Electricity Bill</p>
                                <p class="note">Monthly Utility</p>
                            </div>
                        </div>
                        <span class="transaction-amount" style="color: var(--color-danger);">- $75.00</span>
                    </li>
                </ul>
            </div>

        </div> <!-- /dashboard-grid -->
    </main>

    <!-- 
      Plain JavaScript for Interactions
    -->
    <script>
        // Get both sync buttons (desktop and mobile)
        const syncButtonDesktop = document.getElementById('sync-button');
        const syncButtonMobile = document.getElementById('mobile-sync-button');
        
        // Get the text element from the desktop button
        const syncText = document.getElementById('sync-text');

        // This function handles the sync logic
        function handleSync() {
            console.log('Sync started...');
            
            // 1. Set loading state
            if (syncText) {
                syncText.textContent = 'Syncing...';
            }
            syncButtonDesktop.disabled = true;
            syncButtonMobile.disabled = true;
            syncButtonMobile.style.color = "var(--color-text-secondary)"; // Show disabled state

            // 2. --- PLACEHOLDER for backend/sync_data.php ---
            // The file links are relative, so we use ../backend/sync_data.php
            fetch('../backend/sync_data.php', {
                method: 'POST', 
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Simulate JSON response for demonstration
                return { status: 'success' }; 
            })
            .then(data => {
                // 3. Handle success
                console.log('Sync successful:', data);
                if (syncText) {
                    syncText.textContent = 'Sync Complete!';
                }
                
                // Reset button after a short delay
                setTimeout(resetSyncButton, 2000);
            })
            .catch(error => {
                // 4. Handle error
                console.error('Sync failed:', error);
                if (syncText) {
                    syncText.textContent = 'Sync Failed';
                }
                // Reset button after a short delay
                setTimeout(resetSyncButton, 2000);
            });
        }

        // Helper function to reset the sync buttons
        function resetSyncButton() {
            if (syncText) {
                syncText.textContent = 'Sync Data';
            }
            syncButtonDesktop.disabled = false;
            syncButtonMobile.disabled = false;
            syncButtonMobile.style.color = "var(--color-primary)";
        }

        // Attach the event listener to both buttons
        syncButtonDesktop.addEventListener('click', handleSync);
        syncButtonMobile.addEventListener('click', handleSync);

    </script>

</body>
</html>