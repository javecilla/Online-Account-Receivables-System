<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php'
?>

<main class="main-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-light d-flex align-items-center" role="alert">
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
                                    <small> tab dashboard</small>
                                    <!-- metrics card -->
                                    <!-- <div class="row">
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
                                    </div> -->
                                    
                                    <!-- Recent Transactions & Calendar Row -->
                                    <!-- <div class="row">
                                        <div class="col-md-6 mt-4">
                                            <div class="dashboard-card">
                                                <div class="card-header">
                                                    <h2 class="card-title"><i class="fas fa-clock-rotate-left text-primary me-2"></i>Recent Transactions</h2>
                                                </div>
                                                <div class="transaction-list" id="recentTransactionsList">
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
                                                    <div class="text-center text-muted py-3">No upcoming payments</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    
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
                                                    <button class="btn action-btn" style="cursor: no-drop">
                                                        <i class="fa-solid fa-file-invoice me-2"></i> Invoices
                                                    </button>
                                                    <button class="btn action-btn application-btn">
                                                        <i class="fa-solid fa-edit me-2"></i> Amortization Application
                                                    </button>
                                                </div>
                                                <div class="memberRequestAmortizationsListAction hidden">
                                                    <button class="btn action-btn request-btn" data-title="Request Amortization">
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

                                        <!-- Modal Pay Balance Due Amortization -->
                                        <div class="modal fade" id="payBalanceDueModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="payBalanceDueModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5 fw-bold" id="payBalanceDueModalLabel">Modal title</h1>
                                                    </div>
                                                    <div class="modal-body pay-balance-due-amortization">
                                                        <form action="#">
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDueRemainingBalanceField" class="col-sm-2 col-form-label">Remaining Balance Due:</label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="payBalanceDueRemainingBalanceField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="payBalanceDueRemainingBalance" placeholder="--" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDueMonthlyAmountField" class="col-sm-2 col-form-label">Monthly Amount:</label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="payBalanceDueMonthlyAmountField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="payBalanceDueMonthlyAmount" placeholder="--" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDueTotalPaidField" class="col-sm-2 col-form-label">Total Paid:</label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="payBalanceDueTotalPaidField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="payBalanceDueTotalPaid" placeholder="--" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDueCurrentCreditBalanceField" class="col-sm-2 col-form-label"></label> <!--Credit Balance:-->
                                                                <div class="col-sm-10">
                                                                    <!-- <div class="input-group" id="payBalanceDueCurrentCreditBalanceField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="payBalanceDueCurrentCreditBalance" placeholder="--" required readonly/>
                                                                    </div> -->
                                                                    <div class="form-check mt-1 mb-1">
                                                                        <input class="form-check-input" type="checkbox" value="" id="payBalanceDueCreditBalanceCheck" required>
                                                                        <label class="form-check-label" for="payBalanceDueCreditBalanceCheck">Use My Credit Balance</label>
                                                                    </div>
                                                                    <div class="input-group hidden" id="payBalanceDueUseCreditBalanceField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="payBalanceDueUseCreditBalance" placeholder="--" required/>
                                                                        <!-- <button type="button" class="btn action-btn" id="useAllCreditBalanceBtn">Use All Credit Balance</button> -->
                                                                         <span class="input-group-text" style="font-size: 0.9rem">Total Credit Balance: &nbsp;<strong>₱ <span id="payBalanceDueCurrentCreditBalance"></span></strong></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDueAmountField" class="col-sm-2 col-form-label">Amount:  <span class="text-danger fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="payBalanceDueAmountField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="payBalanceDueAmount" placeholder="--" required/>
                                                                        <button type="button" class="btn action-btn" id="payFullBalanceBtn">Pay Full Balance</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDueFinalTotalAmountField" class="col-sm-2 col-form-label">Total Amount:  <span class="text-primary fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="payBalanceDueFinalTotalAmountField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="payBalanceDueFinalTotalAmount" placeholder="--" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDuePaymentMethodSelection" class="col-sm-2 col-form-label">Payment Method: <span class="text-danger fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="payBalanceDuePaymentMethodSelection">
                                                                        <input type="text" class="form-control" id="payBalanceDuePaymentMethodUI" value="Online Payment" placeholder="Select payment method" readonly />
                                                                        <span class="input-group-text" style="width: 5.5%;">
                                                                            <select id="payBalanceDuePaymentMethod" style="width: 60%;" aria-label="payBalanceDuePaymentMethod" aria-describedby="requestAmortizationTypeAddon">
                                                                                <option value="online_payment" selected>Online Payment</option>
                                                                                <option value="check">Check</option>
                                                                                <option value="bank_transfer">Bank Transfer</option>
                                                                                <option value="cash">Cash</option>
                                                                                <option value="others">Others</option>
                                                                            </select>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDueNotes" class="col-sm-2 col-form-label">Notes: <span class="text-danger fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <textarea class="form-control" id="payBalanceDueNotes" rows="3" placeholder="Enter notes"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="payBalanceDuePaymentDate" class="col-sm-2 col-form-label">Payment Date: <span class="text-primary fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                   <input type="date" class="form-control" id="payBalanceDuePaymentDate" required readonly/>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn action-btn" id="payBalanceDueCloseModalBtn">Close</button>
                                                        <button type="button" class="btn action-btn" id="payBalanceDueSubmitBtn">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

                                        <!-- Modal Request Amortizations -->
                                        <div class="modal fade" id="requestAmortizationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="requestAmortizationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="requestAmortizationModalLabel">Modal title</h1>
                                                    </div>
                                                    <div class="modal-body request-amortization">
                                                        <form action="#">
                                                            <div class="row mb-3">
                                                                <label for="requestAmortizationTypeSelection" class="col-sm-2 col-form-label">Amortization Type: <span class="text-danger fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="requestAmortizationTypeSelection">
                                                                        <input type="text" class="form-control" id="requestAmortizationTypeUI" placeholder="Select amortization / loan type" readonly />
                                                                        <span class="input-group-text" style="width: 5.5%;">
                                                                            <select id="requestAmortizationType" style="width: 60%;" aria-label="requestAmortizationType" aria-describedby="requestAmortizationTypeAddon">
                                                                                <option value="" selected></option>
                                                                                <option value="1">Educational Loan</option>
                                                                                <!-- load dynamically -->
                                                                            </select>
                                                                        </span>
                                                                    </div>
                                                                    <div class="more-about mt-3 hidden">
                                                                        <p><i class="fas fa-info-circle"></i> More About <span id="selectedRequestAmortizationTypeName">Educational Loan</span> :</p>
                                                                        <div class="row mb-2">
                                                                            <label for="selectedRequestAmortizationDescription" class="col-sm-2 col-form-label">Description:</label>
                                                                            <div class="col-sm-10">
                                                                                <span id="selectedRequestAmortizationDescription" class="form-control more-about_context">Financial assistance for educational expenses including tuition fees, books, and other school-related costs</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                            <label for="selectedRequestAmortizationInterestRate" class="col-sm-2 col-form-label">Interest Rate:</label>
                                                                            <div class="col-sm-10">
                                                                                <span id="selectedRequestAmortizationInterestRate" class="form-control more-about_context">6.00</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                            <label for="selectedRequestAmortizationTermMonths" class="col-sm-2 col-form-label">Term Months:</label>
                                                                            <div class="col-sm-10">
                                                                                <span id="selectedRequestAmortizationTermMonths" class="form-control more-about_context">12</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                            <label for="selectedRequestAmortizationMinimumAmount" class="col-sm-2 col-form-label">Minimum Amount:</label>
                                                                            <div class="col-sm-10">
                                                                                <span id="selectedRequestAmortizationMinimumAmount" class="form-control more-about_context">5000.00</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                            <label for="selectedRequestAmortizationMaximumAmount" class="col-sm-2 col-form-label">Maximum Amount:</label>
                                                                            <div class="col-sm-10">
                                                                                <span id="selectedRequestAmortizationMaximumAmount" class="form-control more-about_context">50000.00</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="requestAmortizationTermMonths" class="col-sm-2 col-form-label">Term Months: <span class="text-danger fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" id="requestAmortizationTermMonths" placeholder="--" required readonly/>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="" id="termMonthsCheck" required>
                                                                        <label class="form-check-label" for="termMonthsCheck">Override term months</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="requestAmortizationAmountField" class="col-sm-2 col-form-label">Amount: <span class="text-danger fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="requestAmortizationAmountField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="requestAmortizationAmount" placeholder="--" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="requestAmortizationTotalRepaymentField" class="col-sm-2 col-form-label">Total Repayment: <span class="text-primary fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="requestAmortizationTotalRepaymentField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="requestAmortizationTotalRepayment" placeholder="--" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="requestAmortizationMonthlyPaymentField" class="col-sm-2 col-form-label">Monthly Payment: <span class="text-primary fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" id="requestAmortizationMonthlyPaymentField">
                                                                        <span class="input-group-text">₱</span>
                                                                        <input type="text" class="form-control" id="requestAmortizationMonthlyPayment" placeholder="--" required readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="requestAmortizationStartDate" class="col-sm-2 col-form-label">Start Date: <span class="text-danger fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="date" class="form-control" id="requestAmortizationStartDate" required readonly/>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="requestAmortizationEndDate" class="col-sm-2 col-form-label">End Date: <span class="text-primary fw-bold">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="date" class="form-control" id="requestAmortizationEndDate" required readonly/>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn action-btn" id="requestAmortizationCloseModalBtn">Close</button>
                                                        <button type="button" class="btn action-btn" id="requestAmortizationSubmitBtn">Submit</button>
                                                        <button type="button" class="btn action-btn hidden" id="requestAmortizationUpdateBtn">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php' 
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>