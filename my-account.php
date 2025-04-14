<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php'
?>

<main class="main-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle bi flex-shrink-0 me-2"></i>
                    <div>
                        <small><strong>Todo members functionality:</strong> If you wish to access testing accounts for demo purposes, hover the following account:</small>&nbsp;&nbsp;
                        <span class="fw-bold" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="USERNAME: admin | PASSWORD: admin" style="cursor: default">ADMIN</span>&nbsp;&nbsp;|&nbsp;
                        <span class="fw-bold" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="USERNAME: member | PASSWORD: member" style="cursor: default">MEMBER</span>
                    </div>
                </div>
                <div class="row">
                <div class="table-responsive" style="overflow-x: hidden!important;">
                    <nav>
                        <ul class="nav nav-tabs member-tabs mb-3" id="memberContentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">
                                    <i class="fas fa-table-columns me-2"></i>Dashboard
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="savings-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#savingsWithdrawals" type="button" role="tab" aria-controls="savingsWithdrawals" aria-selected="false">
                                    <i class="fa-solid fa-piggy-bank me-2"></i> Savings / Withdrawals
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="amortilization-tab" data-bs-toggle="tab" data-bs-target="#amortilization" type="button" role="tab" aria-controls="amortilization" aria-selected="false">
                                    <i class="fas fa-credit-card me-2"></i> Amortizations (Loans)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="transaction-history-tab" data-bs-toggle="tab" data-bs-target="#transaction-history" type="button" role="tab" aria-controls="transaction-history" aria-selected="false">
                                    <i class="fas fa-history me-2"></i>Transactions History
                                </button>
                            </li>
                        </ul>
                    </nav>
                    <div class="tab-content" id="memberContentTabsContent">
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                            <div id="dashboardContent">
                                <!-- metrics card -->
                                <div class="row">
                                    <div class="col">
                                        <div class="dashboard-card mb-3">
                                            <div class="card-header">
                                                <h2 class="card-title"><i class="fas fa-wallet text-primary me-2"></i>Total Balance</h2>
                                            </div>
                                            <h3 class="card-value" id="totalBalanceValue">₱0.00</h3>
                                            <p class="card-subtitle">Monthly Savings Target: <span>₱0.00</span></p>
                                            <p class="card-subtitle">Total Withdrawals: <span>₱0.00</span></p>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="dashboard-card mb-3">
                                            <div class="card-header">
                                                <h2 class="card-title"><i class="fas fa-chart-line text-info me-2"></i>Savings Progress</h2>
                                            </div>
                                            <h3 class="card-value">0%</h3>
                                            <div class="progress mb-2">
                                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="card-subtitle">Target: <span>₱0.00</span></p>
                                            <p class="card-subtitle">Current: <span>₱0.00</span></p>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="dashboard-card mb-3">
                                            <div class="card-header">
                                                <h2 class="card-title"><i class="fas fa-credit-card text-warning me-2"></i>Active Loans</h2>
                                            </div>
                                            <h3 class="card-value" id="totalActiveLoan">0</h3>
                                            <p class="card-subtitle">Next Payment Due: <span>Not Set</span></p>
                                            <p class="card-subtitle">Overdue Amount: <span>₱0.00</span></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Recent Transactions & Calendar Row -->
                                <div class="row">
                                    <div class="col-md-6 mt-4">
                                        <div class="dashboard-card">
                                            <div class="card-header">
                                                <h2 class="card-title"><i class="fas fa-clock-rotate-left text-primary me-2"></i>Recent Transactions</h2>
                                            </div>
                                            <div class="transaction-list" id="recentTransactionsList">
                                                <!-- Transactions will be loaded dynamically -->
                                                <div class="text-center text-muted py-3">No recent transactions</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="dashboard-card">
                                            <div class="card-header">
                                                <h2 class="card-title"><i class="fas fa-calendar text-info me-2"></i>Payment Calendar</h2>
                                            </div>
                                            <div id="paymentCalendar">
                                                <!-- Calendar will be loaded dynamically -->
                                                <div class="text-center text-muted py-3">No upcoming payments</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Account Health & Savings Goals Row -->
                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="dashboard-card">
                                            <div class="card-header">
                                                <h2 class="card-title"><i class="fas fa-heart text-danger me-2"></i>Account Health</h2>
                                            </div>
                                            <div class="account-health-status">
                                                <h3 class="card-value">Good Standing</h3>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <p class="card-subtitle">Credit Score: <span class="text-success">85/100</span></p>
                                                <p class="card-subtitle">Payment History: <span class="text-success">On Time</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="dashboard-card">
                                            <div class="card-header">
                                                <h2 class="card-title"><i class="fas fa-bullseye text-success me-2"></i>Savings Goals</h2>
                                            </div>
                                            <div class="savings-goals">
                                                <div class="goal-item mb-3">
                                                    <p class="card-subtitle">Emergency Fund</p>
                                                    <div class="progress mb-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="card-subtitle">₱30,000 / ₱50,000</p>
                                                </div>
                                                <div class="goal-item">
                                                    <p class="card-subtitle">Retirement Fund</p>
                                                    <div class="progress mb-2">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="card-subtitle">₱250,000 / ₱1,000,000</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="savingsWithdrawals" role="tabpanel" aria-labelledby="savings-withdrawals-tab">
                            <small> tab savings withdrawals</small>
                        </div>
                        <div class="tab-pane fade" id="amortilization" role="tabpanel" aria-labelledby="amortilization-tab">
                            <div class="amortilizationContent">
                                <div class="mb-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h4 class="module-title memberAmortilizationTitle">List of amortizations approved:</h4>
                                            <div class="memberApprovedAmortizationsListAction">
                                                <button class="btn action-btn application-btn">
                                                    <i class="fa-solid fa-edit me-2"></i> Amortization Application
                                                </button>
                                            </div>
                                            <div class="memberRequestAmortizationsListAction hidden">
                                                <button class="btn action-btn" style="cursor: no-drop">
                                                    <i class="fa-solid fa-plus me-2"></i> Request Amortization
                                                </button>
                                                <button class="btn action-btn back-btn">
                                                    <i class="fa-solid fa-chevron-left me-2"></i>Back
                                                </button>
                                            </div>
                                            <div class="memberAmortizationPaymentsListAction hidden">
                                                <button class="btn action-btn" style="cursor: no-drop">
                                                    <i class="fa-solid fa-file-csv me-2"></i> Export
                                                </button>
                                                <button class="btn action-btn" style="cursor: no-drop">
                                                    <i class="fa-solid fa-file-pdf me-2"></i> Print
                                                </button>
                                                <button class="btn action-btn back-btn">
                                                    <i class="fa-solid fa-chevron-left me-2"></i>Back
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="memberApprovedAmortizationsListContent">
                                    <table id="memberApprovedAmortizationsTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Amortization Type</th>
                                                <th>Status</th>
                                                <th>Remaining Balance</th>
                                                <th>Total Paid</th> 
                                                <th>Balance Due</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                                <div id="memberRequestAmortizationsListContent">
                                    <table id="memberRequestAmortizationsTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Amortization Type</th>
                                                <th>Member Name</th>
                                                <th>Current Balance</th>
                                                <th>Approval</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                                <div id="memberAmortizationPaymentsListContent">
                                    <table id="memberAmortizationPaymentsTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Notes</th>
                                                <th>Reference Number</th>
                                                <th>Payment By</th>
                                                <th>Payment Method</th>
                                                <th>Amount</th>
                                                <th>Payment Date</th>
                                                <th>Processed By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="transaction-history" role="tabpanel" aria-labelledby="transaction-history-tab">
                            <div id="transactionContent">
                                <div>
                                    <table id="memberTransactionsHistoryTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Reference Number</th>
                                                <th>Transaction Type</th>
                                                <th>Amount</th>
                                                <th>Previous Balance</th>
                                                <th>New Balance</th>
                                                <th>Date and Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php' 
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>