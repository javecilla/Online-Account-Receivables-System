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
                                        <h4 class="module-title">Accounts Management <span class="text-secondary" id="modeBreadCrumb">/ Mode</span> <span class="text-secondary" id="forAccountBreadCrumb">/ For</span></h4>
                                        <div class="tableContainerContent">
                                            <!-- refresh button -->
                                            <button class="btn action-btn" id="refreshAccountList">
                                                <i class="fas fa-sync-alt me-2"></i>Refresh
                                            </button>
                                            <!-- filter button -->
                                            <button class="btn action-btn" id="filterAccountList">
                                                <i class="fas fa-filter me-2"></i>Filter
                                            </button>
                                            <!-- <button class="btn action-btn" style="cursor: no-drop">
                                                <i class="fas fa-history me-2"></i> View Activity Logs
                                            </button> -->
                                 
                                            <button class="btn action-btn" id="addAccountBtn">
                                                <i class="fas fa-plus-circle me-2"></i>Create Account
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
                                <table id="accountsTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Account ID</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <!-- <th>Username</th> -->
                                            <th>Status</th>
                                            <th>Verified</th>
                                            <th>Joined Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded dynamically -->
                                    </tbody>
                                </table>

                                <div class="modal fade" id="accountFilterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="accountDetailsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5 fw-bold" id="accountFilterModalLabel">Filter Accounts</h1>
                                            </div>
                                            <div class="modal-body d-flex justify-content-center align-items-center">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div id="filterAccountRoleContainer">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                        Account Roles
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-role" data-role="Administrator" />
                                                                        Administrator
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-role" data-role="Accountant" />
                                                                        Accountant
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-role" data-role="Member" />
                                                                        Member
                                                                    </label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <input type="hidden" id="filterAccountRoleSelected" />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div id="filterAccountStatusContainer">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                        Account Status
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
                                                            </ul>
                                                        </div>
                                                        <input type="hidden" id="filterAccountStatusSelected" />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div id="filterAccountVerificationContainer">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <span class="filter-label d-flex gap-2 align-items-center text-muted">
                                                                        Verification
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-verify" data-verify="verified" />
                                                                        Verified
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label class="filter-choices label d-flex gap-2 align-items-center">
                                                                        <input type="checkbox" class="checkbox-input-verify" data-verify="unverified" />
                                                                        Unverified
                                                                    </label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <input type="hidden" id="filterAccountVerificationSelected" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn action-btn" id="accountFilterSubmitBtn">Save and Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="updateStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5 fw-bold" id="updateStatusModalLabel">Update Account Status</h1>
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
                                                                <small style="margin-left: 150px!important; cursor: default!important;">Send Message to Notify <strong>Account Owner</strong> about their account status.</small>
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
                            <div class="formContainerContent hidden">
                                <div style="overflow-x: hidden!important;">
                                    <div class="formGeneralContent">
                                        <!-- form label -->
                                        <div class="row mt-4 mb-3">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <h5 class="text-secondary text-bold">Account Information</h5>
                                            </div>
                                        </div>
                                        <!-- fields -->
                                        <div class="row mb-3">
                                            <label for="accountRole" class="col-sm-2 col-form-label">Account Role: </label>
                                            <div class="col-sm-10">

                                                <div class="input-group">
                                                    <!-- //TODO: get account roles from database -->
                                                    <!-- <select class="form-control" id="membershipType" aria-label="membershipType" aria-describedby="membershipTypeAddon">
                                                        <option value="" selected>-- SELECT --</option>

                                                    </select> -->
                                                    <input type="text" class="form-control" id="accountRoleUI" placeholder="Select account role" readonly />
                                                    <span class="input-group-text" style="width: 5%;">
                                                        <!-- <i class="fa-solid fa-chevron-down" style="font-size: 10px!important;"></i> -->
                                                        <select id="accountRole" style="width: 60%;" aria-label="accountRole" aria-describedby="accountRoleAddon">
                                                            <option value="" selected>-- SELECT --</option>
                                                            <!-- data fetch dynamically via api  -->
                                                            <!-- <option value="1">Administrator</option>
                                                            <option value="2">Accountant</option>
                                                            <option value="3" selected>Member</option> -->
                                                        </select>
                                                    </span>
                                                    <!-- <span class="input-group-text" id="accountRoleAddon">@</span> -->
                                                    <div class="invalid-feedback" id="accountRoleError"></div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <label for="email" class="col-sm-2 col-form-label">Email:</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="email" placeholder="Enter an email address (e.g., example@gmail.com)" autocomplete="off" required />
                                                <div class="invalid-feedback" id="emailError"></div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="username" class="col-sm-2 col-form-label">Username:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="username" placeholder="Enter an username (e.g., user123)" autocomplete="off" required />
                                                <div class="invalid-feedback" id="usernameError"></div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="password" class="col-sm-2 col-form-label">Password:</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="password" placeholder="Enter an password" autocomplete="off" required />
                                                <div class="invalid-feedback" id="passwordError"></div>
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="confirmPasswordContainerRow">
                                            <label for="confirmPassword" class="col-sm-2 col-form-label">Confirm Password:</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm the password" autocomplete="off" required />
                                                <div class="invalid-feedback" id="confirmPasswordError"></div>
                                            </div>
                                        </div>
                                        <hr style="color: #f4f4f4" />
                                        <div id="accountAdditionalInformation" class="hidden">
                                            <div class="row mb-3">
                                                <label class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-10">
                                                    <em class="text-secondary"><span class="text-secondary text-bold">TODO:</span> Show account recent activies (login, logout...)</em>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="accountStatus" class="col-sm-2 col-form-label">Account Status:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="accountStatus" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="accountVerified" class="col-sm-2 col-form-label">Verifcation Status:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="accountVerified" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="accountCreatedAt" class="col-sm-2 col-form-label">Created At:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="accountCreatedAt" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="accountUpdatedAt" class="col-sm-2 col-form-label">Updated At:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="accountUpdatedAt" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="formMemberContent">
                                        <div class="row mt-4 mb-3">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <h5 class="text-secondary text-bold">Member Information</h5>
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

                                    <div class="formEmployeeContent hidden">
                                        <div class="row mt-4 mb-3">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <h5 class="text-secondary text-bold">Employee Information</h5>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Full Name:</label>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="employeeFirstName" placeholder="Enter first name (e.g., Jerome)" autocomplete="off" required />
                                                        <div class="invalid-feedback" id="employeeFirstNameError"></div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="employeeMiddleName" placeholder="Enter middle name (e.g., Calista)" autocomplete="off" />
                                                        <div class="invalid-feedback" id="employeeMiddleNameError"></div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="employeeLastName" placeholder="Enter last name (e.g., Avecilla)" autocomplete="off" required />
                                                        <div class="invalid-feedback" id="employeeLastNameError"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Contact Number:</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="employeeContactNumberAddon">+63</span>
                                                    <input type="text" class="form-control" id="employeeContactNumber" placeholder="Enter an contact number (e.g., 9772462211)" aria-label="Contact Number" aria-describedby="employeeContactNumberAddon">
                                                    <div class="invalid-feedback" id="employeeContactNumberError"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Salary:</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="employeeSalary" placeholder="Enter a salary" autocomplete="off" required />
                                                <div class="invalid-feedback" id="employeeSalaryError"></div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Rata:</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="employeeRata" placeholder="Enter a rata" autocomplete="off" required />
                                                <div class="invalid-feedback" id="employeeRataError"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="formActionContainer">
                                        <div class="row mb-2">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <hr style="color:rgb(138, 138, 138)" />
                                                <div class="mt-2">
                                                    <div id="createActionContainer">
                                                        <button class="btn action-btn" id="resetBtn">
                                                            Reset Form
                                                        </button>
                                                        <button class="btn action-btn" id="createAccountBtn">
                                                            <i class="fas fa-save me-2"></i> <span id="accountModeTextBtn">Create</span> Account
                                                        </button>
                                                    </div>
                                                    <div id="viewActionContainer" class="hidden">
                                                        <button id="viewMoreMemberInfoBtn" class="btn action-btn">
                                                            <i class="fas fa-eye me-2"></i> View more <span id="accountRoleTextBtn">Member</span> Information
                                                        </button>
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
        </div>
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php' 
?>

<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>