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
                                        <h4 class="module-title" id="memberPanelTitle">Members Management: </h4>
                                        <div class="tableContainerContent membersContent">
                                            <button class="btn action-btn" id="refreshMemberContentTableBtn">
                                                <i class="fas fa-sync-alt me-2"></i>Refresh
                                            </button>
                                            <!-- <button class="btn action-btn" id="cooperativeApplicationBtn">
                                                <i class="fa-solid fa-edit me-2"></i> Membership Application
                                            </button> -->
                                            <button class="btn action-btn" id="manageLoanTypesBtn" style="cursor: no-drop">
                                                 <i class="fa-solid fa-list me-2"></i>
                                                Manage Membership Types
                                            </button>
                                        </div>
                                        <div class="typesContent hidden">
                                            <button class="btn action-btn">
                                                <i class="fa-solid fa-plus-circle me-2"></i>Create New
                                            </button>
                                            <button class="btn action-btn" id="backToMembersListBtn2">
                                                <i class="fa-solid fa-chevron-left me-2"></i>Back Members Lists
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tableContainerContent">
                                <div id="tabContainerContent" style="overflow-x: hidden!important;">
                                    <ul class="nav nav-tabs member-tabs mb-3" id="memberContentTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="registered-members-tab" data-bs-toggle="tab" data-bs-target="#registered-members" type="button" role="tab" aria-controls="registered-members" aria-selected="false">
                                                <i class="fa-solid fa-user-check me-2"></i> Registered Members
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pending-members-tab" data-bs-toggle="tab" data-bs-target="#pending-members" type="button" role="tab" aria-controls="pending-members" aria-selected="false">
                                                <i class="fa-solid fa-user-clock me-2"></i> Applicant / Pending
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="membersContentTabsContent">
                                        <div class="tab-pane fade show active" id="registered-members" role="tabpanel" aria-labelledby="registered-members-tab">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title" id="registeredMembersModalTitle" data-orginal-title="List of registered members:">List of registered members:</h4>
                                                        <div class="registeredMembersContent">
                                                            <button class="btn action-btn" style="cursor: no-drop">
                                                                <i class="fas fa-filter me-2"></i>Filter
                                                            </button>
                                                            <button class="btn action-btn" id="applySerivcesBtn">
                                                                <i class=" fas fa-edit me-2"></i>Apply Services
                                                            </button>
                                                            <button class="btn action-btn" id="registerNewMemberBtn">
                                                                <i class=" fas fa-plus-circle me-2"></i>Register New Member
                                                            </button>
                                                        </div>
                                                        <div class="createNewMemberContent hidden">
                                                            <button class="btn action-btn backToRegisteredMembersContent">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back to Members Lists
                                                            </button>
                                                        </div>
                                                        <div class="viewMemberContent hidden">
                                                            <button class="btn action-btn backToRegisteredMembersContent memberPanelMetricsContent">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back to Members Lists
                                                            </button>
                                                            <button class="btn action-btn backToMemberPanelContent memberTransactionsHistoryContent hidden" data-from="transactions">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back to Member Panel
                                                            </button>
                                                            <button class="btn action-btn backToMemberPanelContent memberCooperativeContent amortizationApprovedList hidden" data-from="cooperative">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back to Member Panel
                                                            </button>
                                                            <button class="btn action-btn viewMemberAmortizationList memberCooperativeContent memberAmortizationsContent amortizationApprovedList hidden">
                                                                <i class="fa-solid fa-calculator me-2"></i>View Member Loan Requests
                                                            </button>
                                                            <button class="btn action-btn backToMemberAmortizationsContent memberCooperativeContent amortizationPaymentList hidden">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back to Member Amortizations
                                                            </button>
                                                            <button class="btn action-btn backToMemberAmortizationsContent memberCooperativeContent amortizationRequestList hidden">
                                                                <i class="fa-solid fa-chevron-left me-2"></i>Back to Member Amortizations
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="registeredMembersContent">
                                                <table id="registeredMembersTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Member #</th>
                                                            <th>Profile</th>
                                                            <th>Name</th>
                                                            <th>Sex</th>
                                                            <th>Contact</th>
                                                            <!-- <th>Barangay</th> -->
                                                            <th>Municipality</th>
                                                            <th>Province</th>
                                                            <!-- <th>Address</th> -->
                                                            <!-- <th>Current Balance</th>
                                                            <th>Credit Balance</th> -->
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8" class="text-center">Loading records...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="createNewMemberContent hidden">
                                                <div class="container">
                                                    <form action="#">
                                                        <div class="formGroupContainer accountInformation" id="accountInformation">
                                                            <!-- <h1>Account Information</h1> -->
                                                            <div class="row mb-3 mt-3">
                                                                <label class="col-sm-2 col-form-label">Profile Picture: <span class="fw-bold text-warning">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="profile-picture-container">
                                                                        <img id="profilePreview" src="/assets/images/default-profile.png" alt="Profile Picture" loading="lazy"/>
                                                                    </div>
                                                                    <input type="file" id="cmProfilePicture" accept=".png, .jpg, .jpeg"/>
                                                                    <label for="cmProfilePicture" id="profileUploadBtn" class="profile-upload-btn">
                                                                        <i class="fas fa-upload me-2"></i> Upload Profile Picture
                                                                    </label>
                                                                    <label id="profileRemoveBtn" class="profile-upload-btn profile-remove-btn hidden">
                                                                        <i class="fas fa-trash me-2"></i> Remove Profile Picture
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 visually-hidden">
                                                                <label for="cmAccountRole" class="col-sm-2 col-form-label">Account Role:</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control readonly" id="cmAccountRole" value="Member" data-id="3" readonly/>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="cmEmail" class="col-sm-2 col-form-label">Email: <span class="fw-bold text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="email" class="form-control" id="cmEmail" autocomplete="off" placeholder="Enter an email address (e.g., example@avecilla.net)"/>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="cmUsername" class="col-sm-2 col-form-label">Username: <span class="fw-bold text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" id="cmUsername" autocomplete="off" placeholder="Enter a username (e.g., example123)"/>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="cmPassword" class="col-sm-2 col-form-label">Password: <span class="fw-bold text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="password" class="form-control" id="cmPassword" autocomplete="off" placeholder="Enter a password"/>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="cmConfirmPassword" class="col-sm-2 col-form-label">Confirm Password: <span class="fw-bold text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group">
                                                                        <input type="password" class="form-control" id="cmConfirmPassword" autocomplete="off" placeholder="Confirm password">
                                                                        <span class="input-group-text password-toggle" id="togglePassword"><i class="fas fa-eye-slash eye-icon"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="formGroupContainer memberInformation" id="memberInformation">
                                                            <div class="row mb-3">
                                                                <label for="cmEmail" class="col-sm-2 col-form-label">Name: <span class="fw-bold text-primary">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <input type="text" class="form-control" id="cmFirstName" autocomplete="off" placeholder="First Name"/>
                                                                        </div>
                                                                        <div class="col">
                                                                            <input type="text" class="form-control" id="cmMiddleName" autocomplete="off" placeholder="Middle Name (optional)"/>
                                                                        </div>
                                                                        <div class="col">
                                                                            <input type="text" class="form-control" id="cmLastName" autocomplete="off" placeholder="Last Name"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="cmSex" class="col-sm-2 col-form-label">Sex: <span class="fw-bold text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="cmSexUI" placeholder="Select sex type" readonly/>
                                                                        <span class="input-group-text">
                                                                            <select id="cmSex" style="width: 18px;">
                                                                                <option value="" selected></option>
                                                                                <option value="M">Male</option>
                                                                                <option value="F">Female</option>
                                                                            </select>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="cmContactNumber" class="col-sm-2 col-form-label">Contact Number: <span class="fw-bold text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group">
                                                                        <span class="input-group-text" style="font-size: .8rem">+63</span>
                                                                        <input type="text" class="form-control" id="cmContactNumber" autocomplete="off" placeholder="Enter a contact number (e.g., 9772461133)">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label class="col-sm-2 col-form-label">Address: <span class="fw-bold text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control" id="cmHouseAddress" autocomplete="off" placeholder="Enter house address (e.g., Ph7, Blk3, Lot24, Demacia III)"/>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control" id="cmBarangay" autocomplete="off" placeholder="Enter barangay (e.g., Brgy.Mapulang Labi)"/>
                                                                        </div>
                                                                        <div class="col-md-4 mt-2">
                                                                            <input type="text" class="form-control" id="cmMunicipality" autocomplete="off" placeholder="Municipality (e.g., Pandi)"/>
                                                                        </div>
                                                                        <div class="col-md-4 mt-2">
                                                                            <input type="text" class="form-control" id="cmProvince" autocomplete="off" placeholder="Province (e.g., Bulacan)"/>
                                                                        </div>
                                                                        <div class="col-md-4 mt-2">
                                                                            <input type="text" class="form-control" id="cmRegion" autocomplete="off" placeholder="Enter a region (e.g., Region III)"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="formGroupContainer servicesOffer" id="servicesOffer">
                                                            <div class="row mb-3">
                                                                <label class="col-sm-2 col-form-label"><span class="visually-hidden">Services Offer</span></label>
                                                                <div class="col-sm-10">
                                                                    <!-- <div class="mb-3">
                                                                        <button type="button" class="btn action-btn"><i class="fas fa-plus-circle me-2"></i>Add New Services</button>
                                                                    </div> -->
                                                                    <div class="alert alert-light" role="alert">
                                                                        <small>A simple light alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.</small>
                                                                    </div>
                                                                    <label class="soSelection" data-type-id="3">
                                                                        <img src="/assets/images/fixed-account.png" width="70" alt="Fixed Account" loading="lazy"/>
                                                                        <input type="checkbox" data-id="3"/> Fixed Account
                                                                    </label>
                                                                    <label class="soSelection" data-type-id="1">
                                                                        <img src="/assets/images/savings-account.png" width="70" alt="Savings Account" loading="lazy"/>
                                                                        <input type="checkbox" data-id="1"/> Savings Account
                                                                    </label>
                                                                    <label class="soSelection" data-type-id="2">
                                                                        <img src="/assets/images/time-deposit.png" width="70" alt="Time Deposit" loading="lazy"/>
                                                                        <input type="checkbox" data-id="2"/> Time Deposit
                                                                    </label>
                                                                    <label class="soSelection" data-type-id="6">
                                                                        <img src="/assets/images/loan-account.png" width="70" alt="Loan" loading="lazy"/>
                                                                        <input type="checkbox" data-id="6"/> Loan
                                                                    </label>
                                                                    <input type="hidden" id="cmSelectedCooperativeAccounts" />
                                                                    <input type="hidden" id="umSelectedCooperativeAccounts" />
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 mt-5">
                                                                <label for="cmContactNumber" class="col-sm-2 col-form-label"><span class="visually-hidden">Actions</span></label>
                                                                <div class="col-sm-10">
                                                                    <div class="d-flex align-items-inline">
                                                                        <button type="button" class="btn w-50 action-btn cmActionBtn" id="clearFormMemberRegistrationBtn">Clear Form</button>
                                                                        <button type="button" class="btn w-50 action-btn cmActionBtn" id="submitRegisterMemberBtn">Submit</button>
                                                                        <button type="button" class="btn w-100 action-btn umActionBtn hidden" id="submitUpadteMemberBtn">Save Changes</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="applyServicesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="applyServicesModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5 fw-bold" id="applyServicesModalLabel">Apply Member for New Services:</h1>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <select class="form-control" id="applyServicesMember">
                                                                    <option value="21">Jerome Avecilla (M267066)</option>
                                                                </select>
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col-md-5">
                                                                    <div style="margin-left: 50px">
                                                                        <img src="/assets/images/default-profile.png" class="aMemberProfile rounded-circle" width="160" height="160" alt="Profile Picture" loading="lazy"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div style="margin-left: -230px">
                                                                        <p style="font-size: 24px; font-weight: bold;" class="aMemberName">Member Name</p>
                                                                        <p style="margin-top: -15px" class="aMemberUID">M267xxx</p>
                                                                        <p class="aMemberEmail">member@gmail.com</p>
                                                                        <p style="margin-top: -10px" class="aMemberContact">+639772465xxx</p>
                                                                        <p style="margin-top: -10px" class="aMemberAddress">Member Full Address, Bulacan, Region 3</p>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="servicesApply servicesOffer">
                                                                <div class="alert alert-light" role="alert">
                                                                    <small>A simple light alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.</small>
                                                                </div>
                                                                <span class="amServices d-none">
                                                                    <label class="soSelection" data-type-id="3">
                                                                        <img src="/assets/images/fixed-account.png" width="70" alt="Fixed Account" loading="lazy"/>
                                                                        <input type="checkbox" data-id="3"/> Fixed Account
                                                                    </label>
                                                                    <label class="soSelection" data-type-id="1">
                                                                        <img src="/assets/images/savings-account.png" width="70" alt="Savings Account" loading="lazy"/>
                                                                        <input type="checkbox" data-id="1"/> Savings Account
                                                                    </label>
                                                                    <label class="soSelection" data-type-id="2">
                                                                        <img src="/assets/images/time-deposit.png" width="70" alt="Time Deposit" loading="lazy"/>
                                                                        <input type="checkbox" data-id="2"/> Time Deposit
                                                                    </label>
                                                                    <label class="soSelection" data-type-id="6">
                                                                        <img src="/assets/images/loan-account.png" width="70" alt="Loan" loading="lazy"/>
                                                                        <input type="checkbox" data-id="6"/> Loan
                                                                    </label>
                                                                    <input type="hidden" id="amSelectedCooperativeAccounts" />
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn action-btn" id="applyServicesCloseModalBtn">Close</button>
                                                            <button type="button" class="btn action-btn" id="applyServicesSubmitBtn">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="viewMemberContent hidden">
                                                <div class="memberPanelMetricsContent">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <!-- todo profile here.... -->
                                                            <div class="dashboard-card member-profile-card">
                                                                <div class="card-header mb-4">
                                                                    <h2 class="card-title"><i class="fas fa-user text-secondary me-2"></i>Member Information</h2>
                                                                </div>
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="profile-image-container me-3">
                                                                        <img src="/assets/images/default-profile.png" id="vMemberProfileImage" class="rounded-circle img-fluid" alt="img" loading="lazy" style="width: 60px; height: 60px;"/>
                                                                    </div>
                                                                    <div class="member-basic-info">
                                                                        <h5 id="vMemberName" class="mb-0 fw-bold">John S. Doe</h5>
                                                                        <p class="mb-0 text-muted small">Member ID: <span id="vMemberUID">M267066</span></p>
                                                                    </div>
                                                                </div>
                                                                <hr/>

                                                                <div class="mb-3">
                                                                    <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i>Email: <span id="vMemberEmail">member@gmail.com</span></p>
                                                                    <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i>Contact No.: <span id="vMemberContact">+639772465533</span></p>
                                                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Address: <span id="vMemberAddress">Ph7, Blk3, Lot24, Residence III, Brgy. Mapulang Lupa, Pandi, Bulacan, Region 3</span></p>
                                                                    <p class="mb-1"><i class="fas fa-calendar me-2 text-muted"></i>Member Since: <span id="vMemberSince">April 24, 2020</span></p>
                                                                </div>
                                                                <hr/>
                                                                <!-- <div class="mb-3 w-100">
                                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                                        <p class="mb-1">List of all services members have:</p>
                                                                        <button type="button" class="btn action-btn" style="margin-right: -1px">
                                                                            <i class="fas fa-plus-circle"></i> Services
                                                                        </button>
                                                                    </div>
                                                                </div> -->
                                                                <div class="d-flex flex-column flex-md-row gap-4 align-items-center justify-content-center"> 
                                                                    <div class="list-group w-100">
                                                                        <a href="javascript:void(0)" data-type-id="6" class="list-group-item list-group-item-action vServicesAvailedItem d-flex gap-3 py-3" aria-current="true" style="display: none"> 
                                                                            <img src="/assets/images/loan-account.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                            <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                <div>
                                                                                    <h6 class="mb-0">Loan</h6> 
                                                                                    <p class="mb-0 opacity-75">Date Opened: <span class="doLoan">February 17, 2024</span></p> 
                                                                                </div> 
                                                                                <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                            </div>
                                                                        </a>
                                                                        <a href="javascript:void(0)" data-type-id="3" class="list-group-item list-group-item-action vServicesAvailedItem d-flex gap-3 py-3" aria-current="true" style="display: none"> 
                                                                            <img src="/assets/images/fixed-account.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                            <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                <div>
                                                                                    <h6 class="mb-0">Fixed Deposit</h6> 
                                                                                    <p class="mb-0 opacity-75">Date Opened: <span class="doFixedDeposit">September 28, 2024</span></p> 
                                                                                </div> 
                                                                                <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                            </div>
                                                                        </a>
                                                                        <a href="javascript:void(0)" data-type-id="1" class="list-group-item list-group-item-action vServicesAvailedItem d-flex gap-3 py-3" aria-current="true" style="display: none"> 
                                                                            <img src="/assets/images/savings-account.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                            <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                <div>
                                                                                    <h6 class="mb-0">Savings</h6> 
                                                                                    <p class="mb-0 opacity-75">Date Opened: <span class="doSavings">November 11, 2024</span></p> 
                                                                                </div> 
                                                                                <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                            </div>
                                                                        </a>
                                                                        <a href="javascript:void(0)" data-type-id="2" class="list-group-item list-group-item-action vServicesAvailedItem d-flex gap-3 py-3" aria-current="true" style="display: none"> 
                                                                            <img src="/assets/images/time-deposit.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                            <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                <div>
                                                                                    <h6 class="mb-0">Time Deposit</h6> 
                                                                                    <p class="mb-0 opacity-75">Date Opened: <span class="doTimeDeposit">April 13, 2025</span></p> 
                                                                                </div> 
                                                                                <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                            </div>
                                                                        </a>
                                                                    </div>
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
                                                                        <h3 class="card-value" id="vTotalCurrentBalanceValue">₱0.00</h3>
                                                                        <p class="card-subtitle">Credit Balance: <span id="vCreditBalanceValue">₱0.00</span></p>
                                                                        <p class="card-subtitle">Total Withdrawals (Last 30d): <span id="vTotalWithdrawalsValue">₱0.00</span></p> 
                                                                    </div>
                                                                </div>
                                                                <div class="col" id="savingsGoalCard">
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
                                                                <div class="col" id="activeLoansCard">
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
                                                                <div class="col-md-6" id="upcomingPaymentsCard">
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
                                                                <div class="col-md-6">
                                                                    <div class="dashboard-card">
                                                                        <div class="card-header">
                                                                            <h2 class="card-title"><i class="fas fa-clock-rotate-left text-primary me-2"></i>Recent Transactions</h2>
                                                                        </div>
                                                                        <div class="transaction-list" id="recentTransactionsList">
                                                                            <div class="text-center text-muted py-3">TODO: Show 10 Recent Transactions</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="memberTransactionsHistoryContent hidden">
                                                    <table id="memberTransactionsTable" class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Reference Number</th>
                                                                <th>Transaction Type</th>
                                                                <th>Amount</th>
                                                                <th>Previous Balance</th>
                                                                <th>New Balance</th>
                                                                <th>Date and Time</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="7" class="text-center">Loading records...</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="memberCooperativeContent hidden">
                                                    <div class="memberAmortizationsContent hidden">
                                                        <div class="amortizationApprovedList">
                                                            <table id="memberAmortilizationTable" class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Amortization Type</th>
                                                                        <th>Principal Amount</th>
                                                                        <th>Balance Due</th>
                                                                        <th>Total Paid</th>
                                                                        <th>Progress</th>
                                                                        <th>Status</th>
                                                                        <!-- <th>Start Date</th>
                                                                        <th>End Date</th> -->
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="7" class="text-center">Loading records...</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="amortizationRequestList hidden">
                                                            <table id="memberAmortizationsRequestTable" class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Amortization Type</th>
                                                                        <th>Principal Amount</th>
                                                                        <th>Interest Rate</th>
                                                                        <th>Monthly Amount</th>
                                                                        <th>Total Repayment</th>
                                                                        <th>Approval</th>
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">Loading records...</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="amortizationPaymentList hidden">
                                                           <table id="memberPaymentsTable" class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Referrence Number</th>
                                                                        <th>Payment Method</th>
                                                                        <th>Amount</th>
                                                                        <th>Payment Date</th>
                                                                        <th>Processed By</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="6" class="text-center">Loading records...</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="hidden">
                                                        <h1>fixed account</h1>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pending-members" role="tabpanel" aria-labelledby="pending-members-tab">
                                            <div class="mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h4 class="module-title" data-orginal-title="List of pending members:">List of pending members:</h4>
                                                        <div class="pendingMembersContent">
                                                            <!-- <button class="btn action-btn" style="cursor: no-drop">
                                                                <i class="fas fa-filter me-2"></i>Filter
                                                            </button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pendingMembersContent">
                                                <table id="pendingMembersTable" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Member #</th>
                                                            <th>Name</th>
                                                            <th>Profile</th>
                                                            <th>Sex</th>
                                                            <th>Contact</th>
                                                            <!-- <th>Barangay</th> -->
                                                            <th>Date Registered</th>
                                                            <th>Status</th>
                                                            <!-- <th>Address</th> -->
                                                            <!-- <th>Current Balance</th>
                                                            <th>Credit Balance</th> -->
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8" class="text-center">Loading records...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="modal fade" id="requestMemberViewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="requestMemberViewModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5 fw-bold" id="requestMemberViewModalLabel">View Member Request:</h1>
                                                            </div>
                                                            <div class="modal-body d-flex justify-content-center align-items-center">
                                                                <div class="row mt-3 mb-3 w-100">
                                                                    <div class="col">
                                                                        <div class="dashboard-card member-profile-card">
                                                                            <div class="card-header mb-4">
                                                                                <h2 class="card-title"><i class="fas fa-user text-secondary me-2"></i>Member Information</h2>
                                                                            </div>
                                                                            <div class="d-flex align-items-center mb-3">
                                                                                <div class="profile-image-container me-3">
                                                                                    <img src="/assets/images/default-profile.png" id="rvMemberProfileImage" class="rounded-circle img-fluid" alt="img" loading="lazy" style="width: 60px; height: 60px;"/>
                                                                                </div>
                                                                                <div class="member-basic-info">
                                                                                    <h5 id="rvMemberName" class="mb-0 fw-bold">John S. Doe</h5>
                                                                                    <p class="mb-0 text-muted small">Member ID: <span id="rvMemberUID">M267066</span></p>
                                                                                </div>
                                                                            </div>
                                                                            <hr/>

                                                                            <div class="mb-3">
                                                                                <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i>Email: <span id="rvMemberEmail">member@gmail.com</span></p>
                                                                                <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i>Contact No.: <span id="rvMemberContact">+639772465533</span></p>
                                                                                <p class="mb-1"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Address: <span id="rvMemberAddress">Ph7, Blk3, Lot24, Residence III, Brgy. Mapulang Lupa, Pandi, Bulacan, Region 3</span></p>
                                                                                <p class="mb-1"><i class="fas fa-calendar me-2 text-muted"></i>Application Date: <span id="rvMemberApplicationDate">April 24, 2020</span></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="dashboard-card member-profile-card">
                                                                            <div class="card-header mb-4">
                                                                                <h2 class="card-title"><i class="fas fa-screwdriver-wrench text-secondary me-2"></i>Services Avail on Membership</h2>
                                                                            </div>
                                                                            <div class="alert alert-light" role="alert">
                                                                                <small><b>Please note:</b> All services available to members will only be <b>active</b> once the membership is approved.</small>
                                                                            </div>
                                                                            <div class="d-flex flex-column flex-md-row gap-4 align-items-center justify-content-center"> 
                                                                                <div class="list-group w-100">
                                                                                    <a href="javascript:void(0)" data-type-id="6" class="list-group-item list-group-item-action rvServicesAvailedItem d-flex gap-3 py-3" aria-current="true"> 
                                                                                        <img src="/assets/images/loan-account.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                                        <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                            <div>
                                                                                                <h6 class="mb-0">Loan</h6> 
                                                                                                <p class="mb-0 opacity-75">Status: <span class="sLoan">Inactive</span></p>
                                                                                            </div> 
                                                                                            <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                                        </div>
                                                                                    </a>
                                                                                    <a href="javascript:void(0)" data-type-id="3" class="list-group-item list-group-item-action rvServicesAvailedItem d-flex gap-3 py-3" aria-current="true"> 
                                                                                        <img src="/assets/images/fixed-account.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                                        <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                            <div>
                                                                                                <h6 class="mb-0">Fixed Deposit</h6> 
                                                                                                <p class="mb-0 opacity-75">Status: <span class="sFixedDeposit">Inactive</span></p> 
                                                                                            </div> 
                                                                                            <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                                        </div>
                                                                                    </a>
                                                                                    <a href="javascript:void(0)" data-type-id="1" class="list-group-item list-group-item-action rvServicesAvailedItem d-flex gap-3 py-3" aria-current="true"> 
                                                                                        <img src="/assets/images/savings-account.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                                        <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                            <div>
                                                                                                <h6 class="mb-0">Savings</h6> 
                                                                                                <p class="mb-0 opacity-75">Status: <span class="sSavings">Inactive</span></p>
                                                                                            </div> 
                                                                                            <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                                        </div>
                                                                                    </a>
                                                                                    <a href="javascript:void(0)" data-type-id="2" class="list-group-item list-group-item-action rvServicesAvailedItem d-flex gap-3 py-3" aria-current="true"> 
                                                                                        <img src="/assets/images/time-deposit.png" alt="img" width="42" height="42" class="flex-shrink-0" loading="lazy"/> 
                                                                                        <div class="d-flex gap-2 w-100 justify-content-between"> 
                                                                                            <div>
                                                                                                <h6 class="mb-0">Time Deposit</h6> 
                                                                                                <p class="mb-0 opacity-75">Status: <span class="sTimeDeposit">Inactive</span></p>
                                                                                            </div> 
                                                                                            <!-- <small class="opacity-50 text-nowrap">3</small>  -->
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn action-btn" onclick="closeModal('#requestMemberViewModal')">Close</button>
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
        </div>
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php'
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>