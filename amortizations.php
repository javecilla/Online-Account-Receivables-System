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
                                        <h4 class="module-title">Amortizations Management <span class="text-secondary" id="modeBreadCrumb">/ </span> <span class="text-secondary" id="forAccountBreadCrumb"></span></h4>
                                        <div class="tableContainerContent">
                                            <button class="btn action-btn">
                                                Export
                                            </button>
                                            <button class="btn action-btn" id="viewPaymentsLogsBtn">
                                                <i class=" fas fa-eye me-2"></i>View Payments
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
                                                <i class="fas fa-calculator me-2"></i> Loan Requests
                                            </button>
                                        </li>
                                    </ul>

                                    <!-- Tab content -->
                                    <div class="tab-content" id="amortizationContentTabsContent">
                                        <div class="tab-pane fade show active" id="amortization" role="tabpanel" aria-labelledby="amortization-tab">
                                            <div class="amortizationContent">
                                                <table id="amortizationsTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr title="Transaction Note">
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
                                        </div>
                                        <div class="tab-pane fade" id="loan-requests" role="tabpanel" aria-labelledby="loan-requests-tab">
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
                                </div>
                            </div>
                            <!-- //TODO: formContainerContent -->
                            <div class="formContainerContent hidden">
                                <!-- //TODO: paymentHistoryContent -->
                                <div id="paymentHistoryContent">
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