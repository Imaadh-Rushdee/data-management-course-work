<?php
// home.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home - Finance Dashboard</title>
<style>
:root {
  --color-primary:#0d9488;--color-primary-light:#f0fdfa;--color-background:#f1f5f9;
  --color-card-bg:#fff;--color-text-dark:#1e293b;--color-text-secondary:#64748b;
  --color-danger:#ef4444;--color-success:#22c55e;--shadow-sm:0 1px 2px rgba(0,0,0,.05);
  --border-radius:.75rem;--border-color:#e2e8f0;
}
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
     background-color:var(--color-background);color:var(--color-text-dark);font-size:16px;}
.nav-desktop{position:fixed;top:0;left:0;height:100vh;width:250px;background:var(--color-card-bg);
  border-right:1px solid var(--border-color);padding:1.5rem;flex-direction:column;display:none;}
.nav-desktop h1{font-size:1.75rem;font-weight:700;color:var(--color-primary);margin-bottom:2rem;}
.nav-desktop a{text-decoration:none;color:var(--color-text-secondary);font-weight:500;
  padding:.85rem 1rem;border-radius:.5rem;display:flex;align-items:center;margin-bottom:.5rem;}
.nav-desktop a:hover{background:#f8fafc;color:var(--color-text-dark);}
.nav-desktop a.active{background:var(--color-primary-light);color:var(--color-primary);font-weight:600;}
.nav-desktop .sync-button-container{margin-top:auto;padding-top:1rem;border-top:1px solid var(--border-color);}
.nav-mobile{position:fixed;bottom:0;left:0;right:0;height:65px;background:var(--color-card-bg);
  border-top:1px solid var(--border-color);display:flex;justify-content:space-around;align-items:center;
  box-shadow:0 -2px 10px rgba(0,0,0,.05);z-index:1000;}
.nav-mobile a{text-decoration:none;color:var(--color-text-secondary);font-size:.75rem;display:flex;
  flex-direction:column;align-items:center;padding:.5rem;}
.nav-mobile a.active{color:var(--color-primary);}
.main-content{padding:1rem;padding-bottom:80px;}
.main-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;}
.main-header h2{font-size:1.8rem;font-weight:600;}
.dashboard-grid{display:grid;grid-template-columns:1fr;gap:1.5rem;}
.card{background:var(--color-card-bg);border-radius:var(--border-radius);padding:1.5rem;
  box-shadow:var(--shadow-sm);border:1px solid var(--border-color);}
.card-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;}
.card-title{font-size:1.15rem;font-weight:600;}
.card-link{text-decoration:none;font-size:.9rem;font-weight:500;color:var(--color-primary);}
.card-link:hover{text-decoration:underline;}
.progress-bar{width:100%;height:8px;background:var(--color-background);border-radius:4px;overflow:hidden;margin-top:.5rem;}
.progress-bar-inner{height:100%;background:var(--color-primary);border-radius:4px;transition:width .5s ease;}
.text-large{font-size:2.25rem;font-weight:700;margin-top:.5rem;}
.text-meta{font-size:.9rem;color:var(--color-text-secondary);}
.transaction-list{list-style:none;padding:0;}
.transaction-item{display:flex;justify-content:space-between;align-items:center;padding:1rem 0;
  border-bottom:1px solid var(--border-color);}
.transaction-item:last-child{border-bottom:none;}
.transaction-details{display:flex;align-items:center;}
.transaction-icon{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-size:1.2rem;margin-right:1rem;}
.icon-food{background:#fff1f2;color:#f43f5e;}
.icon-utils{background:#eff6ff;color:#3b82f6;}
.icon-income{background:#f0fdf4;color:#22c55e;}
.transaction-amount{font-weight:600;}
.button{display:block;width:100%;text-align:center;background:var(--color-primary);color:#fff;font-weight:600;
  padding:.85rem 1rem;border-radius:.5rem;border:none;cursor:pointer;transition:.2s;}
.button:hover{background:#0f766e;}
.button:disabled{background:#94a3b8;cursor:not-allowed;}
@media(min-width:768px){
 .nav-mobile{display:none;}
 .nav-desktop{display:flex;}
 .main-content{margin-left:250px;padding:2rem;padding-bottom:2rem;}
 .main-header{display:none;}
 .dashboard-grid{grid-template-columns:repeat(2,1fr);}
}
@media(min-width:1024px){.dashboard-grid{grid-template-columns:repeat(3,1fr);}}
</style>
</head>
<body>

<nav class="nav-desktop">
  <h1>MyFinance</h1>
  <a href="home.php" class="active"><span>🏠</span><span style="margin-left:10px;">Home</span></a>
  <a href="expense_report.php"><span>💸</span><span style="margin-left:10px;">Expense Report</span></a>
  <a href="budgetvsexpense_report.php"><span>📊</span><span style="margin-left:10px;">Budget vs. Expense</span></a>
  <a href="savings_progress_page.php"><span>🎯</span><span style="margin-left:10px;">Savings Progress</span></a>
  <a href="monthly_expense_report_page.php"><span>📅</span><span style="margin-left:10px;">Monthly Report</span></a>
  <div class="sync-button-container">
    <button id="sync-button" class="button"><span id="sync-text">Sync Data</span></button>
  </div>
</nav>

<nav class="nav-mobile">
  <a href="home.php" class="active"><span>🏠</span><span>Home</span></a>
  <a href="expense_report.php"><span>💸</span><span>Expenses</span></a>
  <a href="budgetvsexpense_report.php"><span>📊</span><span>Budgets</span></a>
  <a href="savings_progress_page.php"><span>🎯</span><span>Savings</span></a>
  <a href="monthly_expense_report_page.php"><span>📅</span><span>Report</span></a>
</nav>

<main class="main-content">
  <header class="main-header">
    <h2>Overview</h2>
    <button id="mobile-sync-button" style="background:none;border:none;cursor:pointer;color:var(--color-primary);font-size:1.5rem;">🔄</button>
  </header>

  <div class="dashboard-grid">
    <div class="card" id="budgetCard">
      <div class="card-header">
        <span class="card-title">Budget vs. Expense</span>
        <a href="budgetvsexpense_report.php" class="card-link">View Details</a>
      </div>
      <p class="text-meta" id="budgetLabel">Loading...</p>
      <div class="text-large" id="budgetRemaining">$0.00</div>
      <p class="text-meta" id="budgetTotal">Remaining of $0.00</p>
      <div class="progress-bar"><div class="progress-bar-inner" id="budgetProgress" style="width:0%;"></div></div>
    </div>

    <div class="card" id="savingsCard">
      <div class="card-header">
        <span class="card-title">Savings Goal</span>
        <a href="savings_progress_page.php" class="card-link">View All</a>
      </div>
      <p class="text-meta" id="savingsGoal">Loading...</p>
      <div class="text-large" id="savingsCurrent">$0.00</div>
      <p class="text-meta" id="savingsTarget">Target of $0.00</p>
      <div class="progress-bar"><div class="progress-bar-inner" id="savingsProgress" style="width:0%;background-color:#3b82f6;"></div></div>
    </div>

    <div class="card" id="transactionsCard">
      <div class="card-header">
        <span class="card-title">Recent Transactions</span>
        <a href="expense_report.php" class="card-link">View All</a>
      </div>
      <ul class="transaction-list" id="recentTransactions"><li>Loading...</li></ul>
    </div>
  </div>
</main>

<script>
// === SYNC BUTTON ===
const syncButtonDesktop=document.getElementById('sync-button');
const syncButtonMobile=document.getElementById('mobile-sync-button');
const syncText=document.getElementById('sync-text');
function handleSync(){
  syncText.textContent='Syncing...';
  syncButtonDesktop.disabled=true;syncButtonMobile.disabled=true;
  fetch('../backend/sync_data.php',{method:'POST'})
  .then(r=>r.json())
  .then(data=>{
    console.log('Sync:',data);
    syncText.textContent='Sync Complete!';
    setTimeout(()=>{syncText.textContent='Sync Data';
      syncButtonDesktop.disabled=false;syncButtonMobile.disabled=false;},2000);
  })
  .catch(e=>{
    console.error('Sync failed',e);
    syncText.textContent='Sync Failed';
    setTimeout(()=>{syncText.textContent='Sync Data';
      syncButtonDesktop.disabled=false;syncButtonMobile.disabled=false;},2000);
  });
}
syncButtonDesktop.addEventListener('click',handleSync);
syncButtonMobile.addEventListener('click',handleSync);

// === DASHBOARD DATA LOADING ===
async function loadDashboard(){
  await Promise.all([loadBudget(),loadSavings(),loadTransactions()]);
}
async function loadBudget(){
  const res=await fetch('../backend/budget_vs_expense_report.php');
  const html=await res.text();
  const match=html.match(/<td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td>/);
  if(match){
    const category=match[1],budget=parseFloat(match[2]),spent=parseFloat(match[3]),remain=parseFloat(match[4]);
    document.getElementById('budgetLabel').textContent=category+" Budget";
    document.getElementById('budgetRemaining').textContent='$'+remain.toFixed(2);
    document.getElementById('budgetTotal').textContent=`Remaining of $${budget.toFixed(2)}`;
    const percent=(spent/budget)*100;
    document.getElementById('budgetProgress').style.width=Math.min(percent,100)+'%';
  }
}
async function loadSavings(){
  const res=await fetch('../backend/saving_progress_report.php');
  const html=await res.text();
  const match=html.match(/<td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)%<\/td>/);
  if(match){
    const goal=match[1],target=parseFloat(match[2]),current=parseFloat(match[3]),progress=parseFloat(match[4]);
    document.getElementById('savingsGoal').textContent='Goal: '+goal;
    document.getElementById('savingsCurrent').textContent='$'+current.toFixed(2);
    document.getElementById('savingsTarget').textContent='Target of $'+target.toFixed(2);
    document.getElementById('savingsProgress').style.width=Math.min(progress,100)+'%';
  }
}
async function loadTransactions(){
  const res=await fetch('../backend/expense_summary_report.php');
  const html=await res.text();
  const regex=/<td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td>/g;
  const container=document.getElementById('recentTransactions');
  container.innerHTML='';
  let m;let count=0;
  while((m=regex.exec(html))&&count<3){
    const cat=m[1],txns=m[2],spent=m[3];
    container.innerHTML+=`
      <li class="transaction-item">
        <div class="transaction-details">
          <span class="transaction-icon icon-food">💸</span>
          <div class="transaction-info">
            <p class="category">${cat}</p>
            <p class="note">${txns} txns</p>
          </div>
        </div>
        <span class="transaction-amount" style="color:var(--color-danger);">- $${parseFloat(spent).toFixed(2)}</span>
      </li>`;
    count++;
  }
  if(count===0)container.innerHTML='<li>No transactions found</li>';
}
loadDashboard();
</script>
</body>
</html>
