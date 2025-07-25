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
                                        <h4 class="module-title" id="amortizationPanelTitle">Amortizations (Loan) Management: </h4>
                                        <div class="tableContainerContent amortizationsContent">
                                            <button class="btn action-btn" id="refreshAmortizationTableBtn">
                                                <i class="fas fa-sync-alt me-2"></i>Refresh
                                            </button>
                                            <button class="btn action-btn" id="loanApplicationBtn">
                                                <i class="fa-solid fa-edit me-2"></i> Loan Application
                                            </button>
                                            <button class="btn action-btn" id="manageLoanTypesBtn">
                                                <!-- <i class="fa-solid fa-window-restore me-2"></i> -->
                                                 <i class="fa-solid fa-list me-2"></i>
                                                Manage Loan Types
                                            </button>
                                        </div>
                                        <div class="typesContent hidden">
                                            <button class="btn action-btn">
                                                <i class="fa-solid fa-plus-circle me-2"></i>Create New
                                            </button>
                                            <button class="btn action-btn" id="backToAmortizationListBtn2">
                                                <i class="fa-solid fa-chevron-left me-2"></i>Back Amortization Lists
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tableContainerContent amortizationsContent">
                                <div id="tabContainerContent" style="overflow-x: hidden!important;">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs amortization-tabs mb-3" id="amortizationContentTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="amortization-tab" data-bs-toggle="tab" data-bs-target="#amortization" type="button" role="tab" aria-controls="amortilization" aria-selected="false">
                                                <i class="fas fa-credit-card"></i> Amortizations
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="loan-requests-tab" data-bs-toggle="tab" data-bs-target="#loan-requests" type="button" role="tab" aria-controls="loan-requests" aria-selected="false">
                                                <i class="fas fa-calculator me-2"></i> Requests
                                            </button>
                                        </li>
                                        <!-- <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab" aria-controls="invoices" aria-selected="false">
                                                <i class="fas fa-file-invoice me-2"></i> Invoices
                                            </button>
                                        </li> -->
                                    </ul>

                                    <!-- Tab content -->
                                    <div class="tab-content" id="amortizationContentTabsContent">
                                        <div class="tab-pane fade show active" id="amortization" role="tabpanel" aria-labelledby="amortization-tab">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title amortilizationTitle">List of amortizations approved:</h4>
                                                        <div class="amortizationContent">
                                                            <button class="btn action-btn" id="filterAmortizationTableBtn">
                                                                <i class="fas fa-filter me-2"></i>Filter
                                                            </button>
                                                            <button class="btn action-btn" id="viewPaymentsLogsBtn">
                                                                <i class=" fas fa-history me-2"></i>View Payments Logs
                                                            </button>
                                                        </div>
                                                        <div class="paymentsLogsContent hidden">
                                                            <button class="btn action-btn backToAmortizationListBtn" data-for="logs">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back Amortization Lists
                                                            </button>
                                                        </div>
                                                        <div class="paymentsHistoryContent hidden">
                                                            <button class="btn action-btn backToAmortizationListBtn" data-for="history">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back Amortization Lists
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="amortizationContent">
                                                <table id="amortizationsTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Amortization Type</th>
                                                            <th>Member Name</th>
                                                            <th>Remaining Balance</th>
                                                            <!-- <th>Total Paid</th>
                                                        <th>Balance Due</th> -->
                                                            <th>Status</th>
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

                                            <div class="paymentsLogsContent hidden">
                                                <table id="paymentsLogsTable" class="table table-striped table-hover">
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

                                            <div class="paymentsHistoryContent hidden">
                                                <table id="paymentsHistoryTable" class="table table-striped table-hover">
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

                                            <div class="modal fade" id="amortizationFilterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="amortizationDetailsModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5 fw-bold" id="amortizationFilterModalLabel">Filter Amortizations</h1>
                                                        </div>
                                                        <div class="modal-body d-flex justify-content-center align-items-center">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-4">
                                                                    <div id="filterAmortizationTypeContainer">
                                                                        
                                                                        <ul class="list-unstyled">
                                                                            <li>
                                                                                <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                                    Loan Types
                                                                                </span>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-type" data-type="Educational Loan" />
                                                                                    Educational Loan
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-type" data-type="Calamity Loan" />
                                                                                    Calamity Loan
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-type" data-type="Business Loan" />
                                                                                    Business Loan
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-type" data-type="Personal Loan" />
                                                                                    Personal Loan
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-type" data-type="Agricultural Loan" />
                                                                                    Agricultural Loan
                                                                                </label>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <input type="hidden" id="filterAmortizationTypeSelected" />
                                                                </div>
                                                                
                                                                <div class="col-md-4">
                                                                    <div id="filterAmortizationStatusContainer">
                                                                        <ul class="list-unstyled">
                                                                            <li>
                                                                                <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                                    Status
                                                                                </span>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-status" data-status="paid" />
                                                                                    Paid
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-status" data-status="pending" />
                                                                                    Pending
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-status" data-status="overdue" />
                                                                                    Overdue
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                                    <input type="checkbox" class="checkbox-input-status" data-status="defaulted" />
                                                                                    Defaulted
                                                                                </label>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <input type="hidden" id="filterAmortizationStatusSelected" />
                                                                </div>
                                                                <!-- //TODO: AMORTIZATION (Next Due) FILTER -->
                                                                <div class="col-md-4">
                                                                    <div id="filterAmortizationStatusContainer">
                                                                        <ul class="list-unstyled">
                                                                            <li>
                                                                                <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                                    <em>TODO: </em>Next Due Filter
                                                                                </span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn action-btn" id="amortizationFilterSubmitBtn">Save and Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="amortizationDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="amortizationDetailsModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5 fw-bold" id="amortizationDetailsModalLabel">Amortization Details</h1>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div>
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsMemberName" class="col-sm-2 col-form-label">Member Name: </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="amortizationDetailsMemberName" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsType" class="col-sm-2 col-form-label">Amortization Type: </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="amortizationDetailsType" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsStatus" class="col-sm-2 col-form-label">Status: </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="amortizationDetailsStatus" readonly/>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="row mb-3">
                                                                    <label for="amortizationDetailsApproval" class="col-sm-2 col-form-label">Approval: </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="amortizationDetailsApproval" readonly/>
                                                                    </div>
                                                                </div> -->
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsInterestRateField" class="col-sm-2 col-form-label">Interest Rate:</label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="amortizationDetailsInterestRateField">
                                                                            <span class="input-group-text">%</span>
                                                                            <input type="text" class="form-control" id="amortizationDetailsInterestRate" readonly/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsPrincipalAmountField" class="col-sm-2 col-form-label">Principal Amount:</label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="amortizationDetailsPrincipalAmountField">
                                                                            <span class="input-group-text">₱</span>
                                                                            <input type="text" class="form-control" id="amortizationDetailsPrincipalAmount" readonly/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsMonthlyAmountField" class="col-sm-2 col-form-label">Monthly Amount:</label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="amortizationDetailsMonthlyAmountField">
                                                                            <span class="input-group-text">₱</span>
                                                                            <input type="text" class="form-control" id="amortizationDetailsMonthlyAmount" readonly/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsRemainingBalanceField" class="col-sm-2 col-form-label">Remaining Balance Due:</label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="amortizationDetailsRemainingBalanceField">
                                                                            <span class="input-group-text">₱</span>
                                                                            <input type="text" class="form-control" id="amortizationDetailsRemainingBalance" readonly/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsStartDate" class="col-sm-2 col-form-label">Start Date: </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="amortizationDetailsStartDate" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="amortizationDetailsEndDate" class="col-sm-2 col-form-label">End Date: </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="amortizationDetailsEndDate" readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn action-btn" id="amortizationDetailsModalBtn" onclick="closeModal('#amortizationDetailsModal')">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="notifyMemberModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="notifyMemberModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5 fw-bold" id="notifyMemberModalLabel">Notify Member</h1>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="#">
                                                                <div class="row mb-3">
                                                                    <label for="notifyMemberNameField" class="col-sm-2 col-form-label">Name: <span class="text-primary fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="notifyMemberNameField">
                                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                            <input type="text" class="form-control" id="notifyMemberName" readonly/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="notifyEmailField" class="col-sm-2 col-form-label">Email: <span class="text-primary fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="notifyEmailField">
                                                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                            <input type="text" class="form-control" id="notifyEmail" readonly/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br/>
                                                                <div class="row mb-3">
                                                                    <label for="notifyTypeSelection" class="col-sm-2 col-form-label">Notification Type: <span class="text-danger fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group" id="notifyTypeSelection">
                                                                            <input type="text" class="form-control" id="notifyTypeUI" placeholder="Select notification type" readonly />
                                                                            <span class="input-group-text" style="width: 5.5%;">
                                                                                <select id="notifyType" style="width: 60%;" aria-label="notifyType" aria-describedby="notifyTypeAddon">
                                                                                    <option value="" selected></option>
                                                                                    <option value="payment_reminder">Payment Reminder</option>
                                                                                    <option value="overdue_notice">Overdue Notice</option>
                                                                                    <option value="system_alert">System Alert</option>
                                                                                </select>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="notifyTitle" class="col-sm-2 col-form-label">Title: <span class="text-danger fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control" id="notifyTitle" placeholder="Enter title" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="notifyMessage" class="col-sm-2 col-form-label">Message: <span class="text-danger fw-bold">*</span></label>
                                                                    <div class="col-sm-10">
                                                                        <textarea class="form-control" id="notifyMessage" rows="3" placeholder="Enter message"></textarea>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn action-btn" id="notifyMemberCloseModalBtn">Close</button>
                                                            <button type="button" class="btn action-btn" id="notifyMemberSubmitBtn">Send</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="updateStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5 fw-bold">Update Amortization Status</h1>
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
                                                                                    <option value="paid">Paid</option>
                                                                                    <option value="pending">Pending</option>
                                                                                    <option value="overdue">Overdue</option>
                                                                                    <option value="defaulted">Defaulted</option>
                                                                                </select>
                                                                            </span>
                                                                        </div>
                                                                        <br/>
                                                                        <div class="form-control hidden" contenteditable="true" id="defaultedMailNotifyContainer">
                                                                            <small style="margin-left: 150px!important; cursor: default!important;">Send Message to Notify Member about their Loan if Being Updated as <em>'Defaulted'</em></small>
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
                                                                                <label for="updateStatusTitle" class="col-sm-2 col-form-label">Title: <span class="text-danger fw-bold">*</span></label>
                                                                                <div class="col-sm-10">
                                                                                    <input type="text" class="form-control" id="updateStatusTitle" placeholder="Enter title or subject" required/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <label for="updateStatusMessage" class="col-sm-2 col-form-label">Message: <span class="text-danger fw-bold">*</span></label>
                                                                                <div class="col-sm-10">
                                                                                    <textarea class="form-control" id="updateStatusMessage" rows="3" placeholder="Enter message"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn action-btn" id="updateStatusCloseModalBtn" onclick="closeModal('#updateStatusModal')">Close</button>
                                                            <button type="button" class="btn action-btn" id="updateStatusSubmitBtn">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="loan-requests" role="tabpanel" aria-labelledby="loan-requests-tab">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title">List of amortizations requests:</h4>
                                                        <div>
                                                            <!-- filter button -->
                                                            <button class="btn action-btn" style="cursor: no-drop">
                                                                <i class="fas fa-filter me-2"></i>Filter
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="loanRequestsContent">
                                                <table id="loanRequestsTable" class="table table-striped table-hover">
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
                                        </div>
                                    </div>

                                    <!-- Modal Request Amortizations -->
                                    <div class="modal fade" id="requestAmortizationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="requestAmortizationModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5 fw-bold">Apply Loan Member</h1>
                                                </div>
                                                <div class="modal-body request-amortization">
                                                    <form action="#">
                                                        <div class="row mb-3">
                                                            <label for="createInvoiceMember" class="col-sm-2 col-form-label">Member: <span class="text-danger fw-bold">*</span></label>
                                                            <div class="col-sm-10">
                                                                <select class="form-control" id="createInvoiceMember">
                                                                    <option value="21">Jerome Avecilla</option>
                                                                </select>
                                                            </div>
                                                        </div>
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
                                                                        <label for="selectedRequestAmortizationTermMonths" class="col-sm-2 col-form-label">Term Months (Period):</label>
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
                                                            <label for="requestAmortizationTermMonths" class="col-sm-2 col-form-label">Term Months (Period): <span class="text-danger fw-bold">*</span></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="requestAmortizationTermMonths" placeholder="--" required readonly/>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="" id="termMonthsCheck" required>
                                                                    <label class="form-check-label" for="termMonthsCheck">Override term months (Period)</label>
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
                                                                    <label class="form-check-label" for="payBalanceDueCreditBalanceCheck">Use Member Credit Balance</label>
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
                            </div>
                            <div class="typesContent hidden">
                                <div style="overflow-x: hidden!important;">
                                    <table id="loanTypesTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Amortization Type</th>
                                                <th>Description</th>
                                                <th>Interest Rate</th>
                                                <th>Term Months (Period)</th>
                                                <th>Minimum Amount</th>
                                                <th>Max Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
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