<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php' 
?>

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="border-bottom mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="module-title">Members Management <span class="text-secondary" id="modeBreadCrumb">/ </span> <span class="text-secondary" id="forMemberBreadCrumb"></span></h4>
                                        <div class="tableContainerContent">
                                            <button class="btn action-btn" id="refreshMembersTableBtn">
                                                <i class="fas fa-sync-alt me-2"></i>Refresh
                                            </button>
                                            <button class="btn action-btn" id="filterMembersBtn">
                                                <i class="fas fa-filter me-2"></i> Filter
                                            </button>
                                            <button class="btn action-btn" id="viewMembersTransactionsLogsBtn">
                                                <i class="fas fa-history me-2"></i> View Transaction Logs
                                            </button>
                                        </div>
                                        <div class="formContainerContent hidden">
                                            <button class="btn action-btn" id="backToTableContainerBtn">
                                                <i class="fa-solid fa-chevron-left me-2"></i>Back
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tableContainerContent">
                                <table id="membersTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Members ID</th>
                                            <th>Name</th>
                                            <!-- <th>Contact Number</th> -->
                                            <th>Membership Status</th>
                                            <th>Account Type</th>
                                            <th>Current Balance</th>
                                            <th>Opened Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded dynamically -->
                                    </tbody>
                                </table>

                                <div class="modal fade" id="memberFilterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="memberFilterModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5 fw-bold" id="memberFilterModalLabel">Filter Members</h1>
                                            </div>
                                            <div class="modal-body d-flex justify-content-center align-items-center">
                                                <div class="row">
                                                    
                                                    <div class="col-md-4">
                                                        <div id="filterMemberTypeContainer">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                        Account Types
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-type" data-type="Savings Account" />
                                                                        Savings Account
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-type" data-type="Time Deposit" />
                                                                        Time Deposit
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-type" data-type="Fixed Deposit" />
                                                                        Fixed Deposit
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-type" data-type="Special Savings" />
                                                                        Special Savings
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-type" data-type="Youth Savings" />
                                                                        Youth Savings
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-type" data-type="Loan" />
                                                                        Loan
                                                                    </label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <input type="hidden" id="filterMemberTypeSelected" />
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div id="filterAmortizationStatusContainer">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                        Membership Status
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-status" data-status="active" />
                                                                        Active
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-status" data-status="inactive" />
                                                                        Inactive
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-status" data-status="suspended" />
                                                                        Suspended
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-status" data-status="closed" />
                                                                        Closed
                                                                    </label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <input type="hidden" id="filterMemberStatusSelected" />
                                                    </div>
                                                    <!-- //TODO: MEMBER (Risk Level) FILTER -->
                                                    <div class="col-md-4">
                                                        <div>
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                        <em>TODO: </em>Risk Level Filter
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn action-btn" id="memberFilterSubmitBtn">Save and Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="updateStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5 fw-bold" id="updateStatusModalLabel">Update Member Status</h1>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <div class="row mb-3">
                                                        <label for="updateStatusCurrentStatus" class="col-sm-2 col-form-label">Current Status: </label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="updateStatusCurrentStatus" readonly/>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="updateStatusNewStatusSelection" class="col-sm-2 col-form-label">New Status: <span class="text-danger fw-bold">*</span></label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group" id="updateStatusNewStatusSelection">
                                                                <input type="text" class="form-control" id="updateStatusNewStatusUI" placeholder="Select new status" readonly />
                                                                <span class="input-group-text" style="width: 5.5%;">
                                                                    <select id="updateStatusNewStatus" style="width: 60%;" aria-label="updateStatusNewStatus" aria-describedby="updateStatusNewStatusAddon">
                                                                        <option value="" selected></option>
                                                                        <option value="active">Active</option>
                                                                        <option value="inactive">Inactive</option>
                                                                        <option value="suspended">Suspended</option>
                                                                        <option value="closed">Closed</option>
                                                                    </select>
                                                                </span>
                                                            </div>
                                                            <br/>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="sendEmailCheck">
                                                                <label class="form-check-label" for="sendEmailCheck">
                                                                    Send Email Message
                                                                </label>
                                                            </div>
                                                            <div class="form-control mt-3 hidden" contenteditable="true" id="statusMailNotifyContainer">
                                                                <small style="margin-left: 150px!important; cursor: default!important;">Send Message to Notify <strong id="updateStatusActiveMemberName">Member</strong> about their account status.</small>
                                                                <div class="row mb-3 mt-3">
                                                                    <label for="updateStatusEmailField" class="col-sm-2 col-form-label">Email: <span class="text-primary fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="updateStatusEmailField" style="cursor: default!important;">
                                                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                            <input type="text" class="form-control" id="updateStatusEmail" readonly/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row mb-3">
                                                                    <label for="updateStatusTitle" class="col-sm-2 col-form-label">Title: <span class="text-warning fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="updateStatusTitle" placeholder="Enter custom title or leave it blank to let system generate." required/>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="updateStatusMessage" class="col-sm-2 col-form-label">Message: <span class="text-warning fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <textarea class="form-control" id="updateStatusMessage" rows="3" placeholder="Enter custom message or leave it blank to let system generate."></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn action-btn" id="updateStatusCloseBtn">Close</button>
                                                <button type="button" class="btn action-btn" id="updateStatusSubmitBtn">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabContainerContent" class="hidden" style="overflow-x: hidden!important;">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs member-tabs mb-3" id="memberContentTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="member-info-tab" data-bs-toggle="tab" data-bs-target="#member-info" type="button" role="tab" aria-controls="member-info" aria-selected="true">
                                            <i class="fas fa-user me-2"></i>About Member
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="amortilization-tab" data-bs-toggle="tab" data-bs-target="#amortilization" type="button" role="tab" aria-controls="amortilization" aria-selected="false">
                                            <i class="fas fa-credit-card me-2"></i> Amortizations
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="transaction-history-tab" data-bs-toggle="tab" data-bs-target="#transaction-history" type="button" role="tab" aria-controls="transaction-history" aria-selected="false">
                                            <i class="fas fa-history me-2"></i>Transactions History
                                        </button>
                                    </li>
                                </ul>

                                <!-- Tab content -->
                                <div class="tab-content" id="memberContentTabsContent">
                                    <div class="tab-pane fade show active" id="member-info" role="tabpanel" aria-labelledby="member-info-tab">
                                        <div class="formMemberContent">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <!-- todo profile here.... -->
                                                     <div class="dashboard-card member-profile-card">
                                                        <div class="card-header mb-4">
                                                            <h2 class="card-title"><i class="fas fa-user text-secondary me-2"></i>Member Information</h2>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="profile-image-container me-3">
                                                                <img src="/assets/images/default-profile.png" class="rounded-circle img-fluid" alt="img" loading="lazy" style="width: 60px; height: 60px;"/>
                                                            </div>
                                                            <div class="member-basic-info">
                                                                <h5 id="fullName" class="mb-0 fw-bold">John S. Doe</h5>
                                                                <p class="mb-0 text-muted small">Member ID: <span id="memberId">M267066</span></p>
                                                            </div>
                                                        </div>
                                                        <hr/>

                                                        <div class="mb-3">
                                                            <p class="mb-1">Membership Status: <span id="memberStatus" class="fw-semibold">Active</span></p>
                                                            <p class="mb-1">Membership Type: <span id="memberType" class="fw-semibold">Loan</span></p>
                                                        </div>
                                                        <hr/>
                                                        <div class="mb-3">
                                                            <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i>Email: <span id="memberEmail">member@gmail.com</span></p>
                                                            <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i>Contact No.: <span id="memberContact">+639772465533</span></p>
                                                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Address: <span id="memberAddress">Ph7, Blk3, Lot24, Residence III, Brgy. Mapulang Lupa, Pandi, Bulacan, Region 3</span></p>
                                                        </div>
                                                        <hr/>
                                                        <div>
                                                            <p class="mb-1">Date Opened: <span id="memberDateOpened">April 24, 2020</span></p>
                                                            <p class="mb-1">Date Closed: <span id="memberDateClosed">No data available</span></p>
                                                        </div>
                                                        <br />
                                                        <div class="mt-2">
                                                            <button id="viewMoreAccountInfoBtn" class="btn action-btn">
                                                                <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> View Account Details
                                                            </button>
                                                        </div>
                                                     </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <!-- dashboard metrics here... -->
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="dashboard-card mb-3">
                                                                <div class="card-header">
                                                                    <h2 class="card-title"><i class="fas fa-wallet text-primary me-2"></i>Account Balance</h2>
                                                                </div>
                                                                <h3 class="card-value" id="totalCurrentBalanceValue">₱0.00</h3>
                                                                <p class="card-subtitle">Credit Balance: <span id="creditBalanceValue">₱0.00</span></p>
                                                                <p class="card-subtitle">Total Withdrawals (Last 30d): <span id="totalWithdrawalsValue">₱0.00</span></p> 
                                                            </div>
                                                        </div>
                                                        <div class="col hidden" id="savingsGoalCard">
                                                            <div class="dashboard-card mb-3">
                                                                <div class="card-header">
                                                                    <h2 class="card-title"><i class="fas fa-piggy-bank text-info me-2"></i>Savings Goal</h2> 
                                                                </div>
                                                                <h3 class="card-value" id="savingsProgressPercentage">0%</h3>
                                                                <div class="progress mb-2">
                                                                    <div class="progress-bar" id="savingsProgressBar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                                <p class="card-subtitle">Target (<span id="savingsTargetLabel">Minimum Balance</span>): <span id="savingsTargetValue">₱0.00</span></p> <!-- Dynamic Label -->
                                                                <p class="card-subtitle">Current Balance: <span id="savingsCurrentValue">₱0.00</span></p>
                                                            </div>
                                                        </div>
                                                        <div class="col hidden" id="activeLoansCard">
                                                            <div class="dashboard-card mb-3">
                                                                <div class="card-header">
                                                                    <h2 class="card-title"><i class="fas fa-credit-card text-warning me-2"></i>Active Loans</h2>
                                                                </div>
                                                                <h3 class="card-value" id="totalActiveLoansCount">0</h3>
                                                                <p class="card-subtitle">Total Principal: <span id="totalLoanAmountValue">₱0.00</span></p>
                                                                <p class="card-subtitle">Total Overdue Amount: <span id="overdueAmountValue">₱0.00</span></p>
                                                                <p class="card-subtitle">Overdue Loans: <span id="overdueLoansCount">0</span></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-md-4">
                                                            <div class="dashboard-card">
                                                                <div class="card-header">
                                                                    <h2 class="card-title"><i class="fas fa-heartbeat text-danger me-2"></i>Account Status</h2> 
                                                                </div>
                                                                <div class="account-health-status">
                                                                    <h5 class="card-value" id="membershipStatusValue">Active</h5> 
                                                                    <p class="card-subtitle">Member Since: <span id="memberSinceDate">N/A</span></p>
                                                                    <p class="card-subtitle">Loan Payment Status: <span id="loanPaymentStatusValue">N/A</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8" id="upcomingPaymentsCard">
                                                            <div class="dashboard-card">
                                                                <div class="card-header">
                                                                    <h2 class="card-title"><i class="fas fa-calendar-alt text-info me-2"></i>Upcoming Payments</h2> 
                                                                </div>
                                                                <div id="paymentCalendar">
                                                                    <div class="text-center text-muted py-3" id="noUpcomingPaymentsText">No upcoming payments</div>
                                                                    <ul class="list-group list-group-flush" id="upcomingPaymentsList"></ul> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="row">
                                                        <div class="col-md-12 mt-4">
                                                            <div class="dashboard-card">
                                                                <div class="card-header">
                                                                    <h2 class="card-title"><i class="fas fa-clock-rotate-left text-primary me-2"></i>Recent Transactions</h2>
                                                                </div>
                                                                <div class="transaction-list" id="recentTransactionsList">
                                                                    <div class="text-center text-muted py-3">TODO: Show 5 Recent Transactions</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="amortilization" role="tabpanel" aria-labelledby="amortilization-tab">
                                        <div class="amortilizationContent">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title memberAmortilizationTitle">List of approved amortizations:</h4>
                                                        <div class="memberAmortizationsListAction">
                                                            <button class="btn action-btn" id="viewLoanRequestBtn">
                                                                <i class="fas fa-calculator me-2"></i>View Loan Requests
                                                            </button>
                                                        </div>
                                                        <div class="memberAmortilizationPaymentsAction hidden">
                                                            <button class="btn action-btn backtoMemberAmortizationsListContentBtn" data-for="payments">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back Amortizations List
                                                            </button>
                                                        </div>
                                                        <div class="memberAmortizationRequestAction hidden">
                                                            <button class="btn action-btn backtoMemberAmortizationsListContentBtn" data-for="request">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back Amortizations List
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="memberAmortizationsListContent" class="table-responsive">
                                                <table id="memberAmortilizationTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Amortization Type</th>
                                                            <th>Status</th>
                                                            <th>Remaining Balance</th> <!-- Remaining balance that members need to repaid-->
                                                            <th>Total Paid</th> <!-- total amount that members already paid-->
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
                                            <div id="memberAmortilizationPaymentsContent" class="table-responsive hidden">
                                                <table id="paymentsTable" class="table table-striped table-hover">
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
                                            <div id="memberAmortizationsRequestContent" class="table-responsive hidden">
                                                <table id="memberAmortizationsRequestTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Amortization Type</th>
                                                            <th>Status</th>
                                                            <th>Remaining Balance</th> <!-- Remaining balance that members need to repaid-->
                                                            <th>Total Paid</th> <!-- total amount that members already paid-->
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
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="transaction-history" role="tabpanel" aria-labelledby="transaction-history-tab">
                                        <div class="transactionHistoryContent">
                                            <table id="memberTransactionsTable" class="table table-striped table-hover">
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
                            <div id="allTransactionsLogsContainerContent" class="hidden" style="overflow-x: hidden!important;">
                                <div>
                                    <table id="membersTransactionLogsTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Reference Number</th>
                                                <th>Name</th>
                                                <th>Transaction Type</th>
                                                <!-- <th>Payment Method</th> -->
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