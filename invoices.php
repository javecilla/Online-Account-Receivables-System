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
                                        <h4 class="module-title">Invoices Management: </h4>
                                        <div class="tableContainerContent">
                                            <button class="btn action-btn">
                                                <i class="fas fa-sync-alt me-2"></i>Refresh
                                            </button>
                                            <button class="btn action-btn">
                                                <i class="fas fa-plus-circle me-2"></i> Create Invoice
                                            </button>
                                        </div>
                                        <div class="formContainerContent hidden">
                                            <button class="btn action-btn">
                                                <i class="fa-solid fa-chevron-left me-2"></i>Back
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tableContainerContent">
                                <div id="tabContainerContent" style="overflow-x: hidden!important;">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs invoice-tabs mb-3" id="invoiceContentTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="invoice-list-tab" data-bs-toggle="tab" data-bs-target="#invoice-list" type="button" role="tab" aria-controls="invoice-list" aria-selected="false">
                                                <i class="fas fa-file-invoice"></i> Invoices
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="recurring-invoice-tab" data-bs-toggle="tab" data-bs-target="#recurring-invoice" type="button" role="tab" aria-controls="recurring-invoice" aria-selected="false">
                                                <i class="fas fa-file me-2"></i> Recurring Invoices
                                            </button>
                                        </li>
                                    </ul>

                                    <!-- Tab content -->
                                    <div class="tab-content" id="invoicesTabsContent">
                                        <div class="tab-pane fade show active" id="invoice-list" role="tabpanel" aria-labelledby="invoice-list-tab">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title">List of Regular Invoices:</h4>
                                                        <div class="invoiceListContent">
                                                            <button class="btn action-btn">
                                                                <i class="fas fa-filter me-2"></i>Filter
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invoiceListContent">
                                                <table id="invoiceListsTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Invoice Number</th>
                                                            <th>Member</th>
                                                            <th>Amount</th>
                                                            <th>Description</th>
                                                            <th>Invoice Date</th>
                                                            <th>Due Date</th>
                                                            <th>Status</th>
                                                            <th>Payment Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>INV-2023-001</td>
                                                            <td>John Smith</td>
                                                            <td>₱5,000.00</td>
                                                            <td>Monthly Membership Fee</td>
                                                            <td>2023-11-01</td>
                                                            <td>2023-11-15</td>
                                                            <td><span class="badge bg-warning">Pending</span></td>
                                                            <td><span class="badge bg-secondary">Unpaid</span></td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm action-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                        <li><a class="dropdown-item view-details-btn" href="javascript:void(0)"><i class="fas fa-eye me-2"></i> View Details</a></li>
                                                                        <!-- <li><a class="dropdown-item view-details-btn" href="javascript:void(0)"><i class="fas fa-eye me-2"></i> Update Details </a></li> -->
                                                                        <li><a class="dropdown-item update-status-btn" href="javascript:void(0)"><i class="fas fa-edit me-2"></i> Update Status</a></li>
                                                                        <li><a class="dropdown-item notify-invoice-btn" href="javascript:void(0)"><i class="fas fa-bell me-2"></i> Send Reminder</a></li>
                                                                        <li><a class="dropdown-item download-pdf-btn" href="javascript:void(0)"><i class="fas fa-file-pdf me-2"></i> Download PDF</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="recurring-invoice" role="tabpanel" aria-labelledby="recurring-invoice-tab">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title">List of Recurring Invoices:</h4>
                                                        <div class="recurringInvoiceContent">
                                                            <button class="btn action-btn">
                                                                <i class="fas fa-filter me-2"></i>Filter
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="recurringInvoiceContent">
                                                <table id="recurringInvoiceTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Invoice Number</th>
                                                            <th>Member</th>
                                                            <th>Amount</th>
                                                            <th>Description</th>
                                                            <th>Recurring Period</th>
                                                            <th>Start Date</th>
                                                            <th>Next Due Date</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>REC-2023-001</td>
                                                            <td>Emma Wilson</td>
                                                            <td>₱2,500.00</td>
                                                            <td>Monthly Maintenance Fee</td>
                                                            <td>Monthly</td>
                                                            <td>2023-11-01</td>
                                                            <td>2023-12-01</td>
                                                            <td><span class="badge bg-success">Active</span></td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm action-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                        <li><a class="dropdown-item view-details-btn" href="javascript:void(0)"><i class="fas fa-eye me-2"></i> View Details</a></li>
                                                                        <li><a class="dropdown-item update-details-btn" href="javascript:void(0)"><i class="fas fa-edit me-2"></i> Update Details</a></li>
                                                                        <li><a class="dropdown-item update-status-btn" href="javascript:void(0)"><i class="fas fa-edit me-2"></i> Update Status</a></li>
                                                                        <li><a class="dropdown-item notify-recurring-btn" href="javascript:void(0)"><i class="fas fa-bell me-2"></i> Notify Member</a></li>
                                                                        <li><a class="dropdown-item generate-invoice-btn" href="javascript:void(0)"><i class="fas fa-file-invoice me-2"></i> Generate Invoice</a></li>
                                                                        <!-- <li><a class="dropdown-item pause-recurring-btn" href="javascript:void(0)"><i class="fas fa-pause me-2"></i> Pause</a></li>
                                                                        <li><a class="dropdown-item resume-recurring-btn" href="javascript:void(0)"><i class="fas fa-play me-2"></i> Resume</a></li> -->
                                                                        <li><a class="dropdown-item history-recurring-btn" href="javascript:void(0)"><i class="fas fa-history me-2"></i> View History</a></li>
                                                                        <!-- <li><hr class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item cancel-recurring text-danger" href="javascript:void(0)"><i class="fas fa-times me-2"></i> Cancel</a></li> -->
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
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
        </div>
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php' 
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>