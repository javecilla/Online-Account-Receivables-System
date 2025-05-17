<?php require_once __DIR__ . '/../app/includes/layouts/begin.php'; ?>
<?php require_once __DIR__ . '/../app/config/env.php'; ?>

<div class="ar-container">
  <div class="ar-card">
    <div class="ar-content">
      <!-- Top Header Section -->
      

      <form action="#" style="margin-top: 875px">
        <div class="ar-top-side mb-5">
          <div class="ar-header">
            <h2>Members Application</h2>
            <p>Please fill out the form below to create your account. All fields marked with an asterisk (*) are required.</p>
          </div>
        </div>
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
                </div>
            </div>
            <div class="d-flex justify-content-center">
              <div class="g-recaptcha-widgets">
                <div class="g-recaptcha" name="g-recaptcha-response"
                  id="g-recaptcha-response"
                  data-sitekey="<?= env('RECAPTCHA_FRONTEND_KEY') ?>"></div>
              </div>
            </div>
            <div class="row mb-3 mt-5">
                <label for="cmContactNumber" class="col-sm-2 col-form-label"><span class="visually-hidden">Actions</span></label>
                <div class="col-sm-10">
                    <div class="d-flex align-items-inline">
                        <!-- <button type="button" class="btn w-50 action-btn cmActionBtn" id="clearFormMemberRegistrationBtn">Clear Form</button> -->
                        <button type="button" class="btn w-50 action-btn cmActionBtn" onclick="window.location.href='/'">Back to Home</button>
                        <button type="button" class="btn w-50 action-btn cmActionBtn" id="submitRegisterMemberBtn">Submit</button>
                        <button type="button" class="btn w-100 action-btn umActionBtn hidden" id="submitUpadteMemberBtn">Save Changes</button>
                    </div>
                    <div class="d-flex align-items-inline text-secondary mt-3">
                      <p>Already have an account? <a href="/auth/login" class="text-secondary fw-bold">go to portal</a> </p>
                    </div>
                </div>
            </div>
        </div>
      </form>

      <div class="ar-bottom-side">
      </div>
    </div>

    <div class="ar-footer">
      <p>Submitted by: <strong>Jerome Avecilla</strong> - IT211 (Project) Web System Technologies </p>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../app/includes/layouts/end.php'; ?>