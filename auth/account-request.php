<?php require_once __DIR__ . '/../app/includes/layouts/begin.php'; ?>
<?php require_once __DIR__ . '/../app/config/env.php'; ?>

<div class="ar-container">
  <div class="ar-card">
    <div class="ar-content">
      <!-- Top Header Section -->
      <div class="ar-top-side">
        <div class="ar-header">
          <h2>Request Account (Member)</h2>
          <p>Please fill out the form below to create your account. All fields marked with an asterisk (*) are required.</p>
        </div>
      </div>

      <form action="post" id="arForm" class="ar-form-container">
        <!-- Left side - Account Information -->
        <div class="ar-left-side">
          <div class="ar-section-header">
            <h2>Member Information</h2>
          </div>
          <div class="fields">
            <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control" id="membershipTypeUI" placeholder="Select membership type" readonly/>
                <span class="input-group-text">
                  <select id="membershipType" style="width: 18px;">
                    <option value="" selected></option>
                    <option value="1">Savings Account</option>
                    <!-- dynamically loaded all data -->
                  </select>
                </span>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="firstName" autocomplete="off" placeholder="First name (e.g., Vuex)"/>
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="middleName" autocomplete="off" placeholder="Middle name (optional)"/>
                  </div>
                  <div class="col-md-12 mt-2">
                    <input type="text" class="form-control" id="lastName" autocomplete="off" placeholder="Enter last name (e.g., Laracast)"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-text" style="font-size: .8rem">
                  +63
                </span>
                <input type="text" class="form-control" id="contactNumber" autocomplete="off" placeholder="Enter a contact number (e.g., 9772461133)">
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <div class="row">
                  <div class="col-md-12">
                    <input type="text" class="form-control" id="houseAddress" autocomplete="off" placeholder="Enter house address (e.g., Ph7, Blk3, Lot24, Demacia III)"/>
                  </div>
                  <div class="col-md-12 mt-2">
                    <input type="text" class="form-control" id="barangay" autocomplete="off" placeholder="Enter barangay (e.g., Brgy.Mapulang Labi)"/>
                  </div>
                  <div class="col-md-6 mt-2">
                    <input type="text" class="form-control" id="municipality" autocomplete="off" placeholder="Municipality (e.g., Pandi)"/>
                  </div>
                  <div class="col-md-6 mt-2">
                    <input type="text" class="form-control" id="province" autocomplete="off" placeholder="Province (e.g., Bulacan)"/>
                  </div>
                  <div class="col-md-12 mt-2">
                    <input type="text" class="form-control" id="region" autocomplete="off" placeholder="Enter a region (e.g., Region III)"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right side - Member Information -->
        <div class="ar-form-side">
          <div class="ar-section-header">
            <h2>Account Information</h2>
          </div>
          <div class="fields">
            <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control readonly" id="accountRole" value="Member" data-id="3" readonly/>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <input type="email" class="form-control" id="email"  autocomplete="off" placeholder="Enter an email address (e.g., example@avecilla.net)"/>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control" id="username"  autocomplete="off" placeholder="Enter a username (e.g., example123)"/>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <input type="password" class="form-control" id="password"  autocomplete="off" placeholder="Enter a password"/>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <input type="password" class="form-control" id="confirmPassword" autocomplete="off" placeholder="Confirm password">
                <span class="input-group-text password-toggle" id="togglePassword">
                  <i class="fas fa-eye-slash eye-icon"></i>
                </span>
              </div>
            </div>

            <div class="form-group">
              <div class="g-recaptcha-widgets">
                <div class="g-recaptcha" name="g-recaptcha-response"
                  id="g-recaptcha-response"
                  data-sitekey="<?= env('RECAPTCHA_FRONTEND_KEY') ?>"></div>
              </div>
            </div>

            <div class="form-group ar-buttons action-buttons">
              <!-- left -->
              <button type="button" class="btn action-btn back-btn" onclick="window.location.href='login'">Back to Login</button>
              <!-- right -->
              <button type="button" class="btn action-btn request-btn">Submit</button>
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