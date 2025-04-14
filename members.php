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
                                            <button class="btn action-btn" style="cursor: no-drop">
                                                <i class="fas fa-file-csv"></i> Export
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
                            </div>
                            <div id="tabContainerContent" class="hidden" style="overflow-x: hidden!important;">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs member-tabs mb-3" id="memberContentTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="member-info-tab" data-bs-toggle="tab" data-bs-target="#member-info" type="button" role="tab" aria-controls="member-info" aria-selected="true">
                                            <i class="fas fa-user me-2"></i>Member Information
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
                                            <div>
                                                <div class="row mt-4 mb-3">
                                                    <label class="col-sm-2 col-form-label"></label>
                                                    <div class="col-sm-10">
                                                        <h5 class="text-secondary text-bold">Member Information</h5>
                                                    </div>
                                                </div>
                                                <div class="memberAdditionalInformation hidden row mb-3">
                                                    <label for="memberUID" class="col-sm-2 col-form-label">Member UID:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="memberUID" />
                                                    </div>
                                                </div>
                                                <div class="row mt-4 mb-3">
                                                    <label for="membershipType" class="col-sm-2 col-form-label">Membership Type: </label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group">
                                                            <!-- //TODO: get membership from database -->
                                                            <!-- <select class="form-control" id="membershipType" aria-label="membershipType" aria-describedby="membershipTypeAddon">
                                                        <option value="" selected>-- SELECT --</option>

                                                    </select> -->
                                                            <input type="text" class="form-control" id="membershipTypeUI" placeholder="Select membership type" readonly />
                                                            <span class="input-group-text" style="width: 5%;">
                                                                <!-- <i class="fa-solid fa-chevron-down" style="font-size: 10px!important;"></i> -->
                                                                <select id="membershipType" style="width: 60%;" aria-label="membershipType" aria-describedby="membershipTypeAddon">
                                                                    <option value="" selected>-- SELECT --</option>
                                                                    <!-- data fetch dynamically via api  -->
                                                                    <!-- <option value="1">Savings Account</option>
                                                            <option value="2">Time Deposite</option>
                                                            <option value="3">Fixed Deposit</option>
                                                            <option value="4">Special Savings</option>
                                                            <option value="5">Youth Savings</option>
                                                            <option value="6">Loan Account</option> -->
                                                                </select>
                                                            </span>
                                                            <!-- <span class="input-group-text" id="membershipTypeAddon">@</span> -->
                                                            <div class="invalid-feedback" id="membershipTypeError"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="memberAdditionalInformation hidden row mb-3">
                                                    <label for="memberCurrentBalance" class="col-sm-2 col-form-label">Current Balance:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="memberCurrentBalance" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">Full Name:</label>
                                                    <div class="col-sm-10">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="memberFirstName" placeholder="Enter first name (e.g., Jerome)" autocomplete="off" required />
                                                                <div class="invalid-feedback" id="memberFirstNameError"></div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="memberMiddleName" placeholder="Enter middle name (e.g., Calista)" autocomplete="off" />
                                                                <div class="invalid-feedback" id="memberMiddleNameError"></div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="memberLastName" placeholder="Enter last name (e.g., Avecilla)" autocomplete="off" required />
                                                                <div class="invalid-feedback" id="memberLastNameError"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">Contact Number:</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="memberContactNumberAddon">+63</span>
                                                            <input type="text" class="form-control" id="memberContactNumber" placeholder="Enter an contact number (e.g., 9772462211)" aria-label="Contact Number" aria-describedby="memberContactNumberAddon">
                                                            <div class="invalid-feedback" id="memberContactNumberError"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">Full Address:</label>
                                                    <div class="col-sm-10">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" id="memberHouseAddress" placeholder="Enter house address (e.g., Ph7, Blk3, Lot24, Demacia III)" autocomplete="off" required />
                                                                <div class="invalid-feedback" id="memberHouseAddressError"></div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" id="memberBarangay" placeholder="Enter barangay (e.g., Brgy. Mapulang Lupa)" autocomplete="off" required />
                                                                <div class="invalid-feedback" id="memberBarangayError"></div>
                                                            </div>
                                                            <div class="col-sm-4 mt-2">
                                                                <input type="text" class="form-control" id="memberMunicipality" placeholder="Enter municipality (e.g., Pandi)" autocomplete="off" required />
                                                                <div class="invalid-feedback" id="memberMunicipalityError"></div>
                                                            </div>
                                                            <div class="col-sm-4 mt-2">
                                                                <input type="text" class="form-control" id="memberProvince" placeholder="Enter province (e.g., Bulacan)" autocomplete="off" required />
                                                                <div class="invalid-feedback" id="memberProvinceError"></div>
                                                            </div>
                                                            <div class="col-sm-4 mt-2">
                                                                <input type="text" class="form-control" id="memberRegion" placeholder="Enter region (e.g., Region 3)" autocomplete="off" required />
                                                                <div class="invalid-feedback" id="memberRegionError"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr style="color: #f4f4f4" />
                                            <div>
                                                <div class="memberAdditionalInformation hidden row mb-3">
                                                    <label for="memberCreatedAt" class="col-sm-2 col-form-label">Created At:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="memberCreatedAt" />
                                                    </div>
                                                </div>
                                                <div class="memberAdditionalInformation hidden row mb-3">
                                                    <label for="memberUpdatedAt" class="col-sm-2 col-form-label">Updated At:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="memberUpdatedAt" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="formActionContainer">
                                                <div class="row mb-2">
                                                    <label class="col-sm-2 col-form-label"></label>
                                                    <div class="col-sm-10">
                                                        <hr style="color:rgb(138, 138, 138)" />
                                                        <div class="mt-2">
                                                            <button id="viewMoreAccountInfoBtn" class="btn action-btn">
                                                                <i class="fas fa-eye me-2"></i> View more account Information
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="amortilization" role="tabpanel" aria-labelledby="amortilization-tab">
                                        <div class="amortilizationContent">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title memberAmortilizationTitle"></h4>
                                                        <div class="memberAmortilizationPaymentsAction hidden">
                                                            <button class="btn action-btn" style="cursor: no-drop">
                                                                <i class="fa-solid fa-file-pdf me-2"></i>Print
                                                            </button>
                                                            <button class="btn action-btn" style="cursor: no-drop">
                                                                <i class="fa-solid fa-file-csv me-2"></i>Export
                                                            </button>
                                                            <button class="btn action-btn" id="backtoMemberAmortizationsListContentBtn">
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