<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php' 
?>
<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <!-- Summary Cards Row -->
        <div class="row mb-4">
            <!-- Total Balances -->
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-wallet text-primary me-2"></i>Total Balances</h2>
                        <button class="btn btn-sm" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Total outstanding balances of all active members">
                            <i class="fas fa-info-circle text-secondary"></i>
                        </button>
                    </div>
                    <h3 class="card-value" id="totalBalanceValue">₱0.00</h3>
                    <p class="card-subtitle">Active Members: <span id="totalMemberCurrent">0</span></p>
                </div>
            </div>
            <!-- Account Receivables -->
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-money-check text-success me-2"></i>Account Receivables</h2>
                        <button class="btn btn-sm" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Total receivable amount and number of active borrowers">
                            <i class="fas fa-info-circle text-secondary"></i>
                        </button>
                    </div>
                    <h3 class="card-value" id="totalReceivables">₱0.00</h3>
                    <p class="card-subtitle">Total Borrowers: <span id="totalBorrowers">0</span></p>

                </div>
            </div>

            <!-- Overdue Summary -->
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-exclamation-circle text-warning me-2"></i>Overdue Summary</h2>
                        <button class="btn btn-sm" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Summary of overdue accounts and total overdue amount">
                            <i class="fas fa-info-circle text-secondary"></i>
                        </button>
                    </div>
                    <h3 class="card-value" id="overdueAmount">₱0.00</h3>
                    <p class="card-subtitle">Overdue Accounts: <span id="overdueAccounts">0</span></p>
                    <p class="card-subtitle">Overdue Rate: <span id="overduePercentage">0%</span></p>
                </div>
            </div>
        </div>

        <!-- Risk Assessment Metrics -->
         <div class="row mb-4">
            <!-- Fixed Deposit -->
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-coins text-success me-2"></i>Fixed Deposit</h2>
                    </div>
                    <h3 class="card-value" id="FDtotalAccounts">0</h3>
                    <p class="card-subtitle">Average Balance: <span id="FDAverageBalance">₱0.00</span></p>
                    <p class="card-subtitle">Accounts Below Minimum: <span id="FDAccountsBelowMinimum">0</span></p>
                    <p class="card-subtitle">Percent Below Minimum: <span id="FDPercentBelowMinimum">0%</span></p>
                </div>
            </div>

            <!-- Savings Account -->
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-money-bill-transfer text-info me-2"></i>Savings Account</h2>
                    </div>
                    <h3 class="card-value" id="SAtotalAccounts">0</h3>
                    <p class="card-subtitle">Average Balance: <span id="SAAverageBalance">₱0.00</span></p>
                    <p class="card-subtitle">Accounts Below Minimum: <span id="SAAccountsBelowMinimum">0</span></p>
                    <p class="card-subtitle">Percent Below Minimum: <span id="SAPercentBelowMinimum">0%</span></p>
                </div>
            </div>

            <!-- Time Deposit -->
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-clock text-danger me-2"></i>Time Deposit</h2>
                    </div>
                    <h3 class="card-value" id="TDtotalAccounts">0</h3>
                    <p class="card-subtitle">Average Balance: <span id="TDAverageBalance">₱0.00</span></p>
                    <p class="card-subtitle">Accounts Below Minimum: <span id="TDAccountsBelowMinimum">0</span></p>
                    <p class="card-subtitle">Percent Below Minimum: <span id="TDPercentBelowMinimum">0%</span></p>
                </div>
            </div>

            <!-- Loan -->
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-hand-holding-dollar text-warning me-2"></i>Loan</h2>
                    </div>
                    <h3 class="card-value" id="loanAccounts">0</h3>
                    <p class="card-subtitle">Average Balance: <span id="loanAverageBalance">₱0.00</span></p>
                    <p class="card-subtitle">Accounts Below Minimum: <span id="loanAccountsBelowMinimum">0</span></p>
                    <p class="card-subtitle">Percent Below Minimum: <span id="loanPercentBelowMinimum">0%</span></p>
                </div>
            </div>
            
        </div>

        <!-- Daily Transaction Stats -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Daily Transaction Summary</h2>
                        <div id="dateRange" class="d-inline-block">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span>
                            <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="dailyTransactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- //TODO:  Chart's-->
        <!--
        - Outstanding receivables by member and total.
        - Payment histories and trends.
        - Monthly, quarterly, and annual financial summaries.
        -->

        <!-- Outstanding Receivables by Member & Loan Performance Metrics -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Outstanding Receivables by Member</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="OutstandingReceivablesByMemberChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Loan Performance Metrics</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="loanPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Trends Monthly & Monthly Balance Trends -->
        <div class="row mb-1">
            <div class="col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Payment Trends Monthly</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="PaymentTrendsMonthlyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Monthly Balance Trends</h2>
                        <!-- filter by year -->
                        <select class="form-select" style="width: 90px;">
                            <option value="2025" selected>2025</option>
                            <!-- populate years dynamically -->
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="monthlyBalanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Histories by Member -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Payment Histories by Member</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="PaymentHistoriesByMemberChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly, quarterly, and annual financial summaries. -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h2 class="card-title">Quarterly Financial Summary (Income from Payments)</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="quarterlyFinancialSummaryIP"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h2 class="card-title">Quarterly Financial Summary (Transactions)</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="quarterlyFinancialSummaryT"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h2 class="card-title">Monthly Financial Summary (Income from Payments)</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="monthlyFinancialSummaryIP"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h2 class="card-title">Monthly Financial Summary (Transactions)</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="monthlyFinancialSummaryT"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h2 class="card-title">Annually Financial Summary (Income from Payments)</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="annuallyFinancialSummaryIP"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h2 class="card-title">Annually Financial Summary (Transactions)</h2>
                    </div>
                    <div class="chart-container">
                        <canvas id="annuallyFinancialSummaryT"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php'
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>