<?php require_once __DIR__ . '/../app/includes/layouts/begin.php'; ?>
<?php require_once __DIR__ . '/../app/config/env.php'; ?>

<div class="login-container">
  <div class="login-card">
    <div class="login-content">
      <div class="login-hero-side">
        <img src="<?= $base_url ?>/assets/images/hero.png" class="login-hero-image" alt="hero" loading="lazy" />
      </div>

      <div class="login-form-side">
        <div class="login-header">
          <h2>Welcome back!</h2>
          <p>Enter your credentials to access your account</p>
        </div>

        <div class="login-form">
          <form id="loginForm" method="post">
            <div class="form-group">
              <!-- <label for="username">Username or Email</label> -->
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="username" autocomplete="off" placeholder="Enter your uid, username or email">
              </div>
            </div>

            <div class="form-group">
              <!-- <label for="password">Password</label> -->
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" autocomplete="off" placeholder="Enter your password">
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

            <div class="form-group login-buttons">
              <button type="button" class="btn action-btn request-btn" onclick="window.location.href='/'">Back to Home</button>
              <button type="button" class="btn action-btn login-btn">Login</button>
            </div>

            <div class="form-group links">
              <p>Don't have an account yet? <a href="/auth/account-request?reg=true#external">apply membership</a> </p>
              <p>Existing account but <a href="javascript:void(0)" style="cursor: no-drop">currently deactivated</a> or <a href="account-verification?purpose=request#external">not yet verified</a>?</p>
              <p>Forgot Password? No worries, please <a href="javascript:void(0)" style="cursor: no-drop">click here</a> to reset your password.</p>
            </div>
          </form>
          <div id="otpForm" style="display: none">
            <div class="otp-container">
              <div class="otp-inputs">
                <div class="otp-input-group">
                  <input type="text" class="otp-input" maxlength="1" placeholder="0" autocomplete="off" />
                </div>
                <div class="otp-input-group">
                  <input type="text" class="otp-input" maxlength="1" placeholder="0" autocomplete="off" />
                </div>
                <div class="otp-input-group">
                  <input type="text" class="otp-input" maxlength="1" placeholder="0" autocomplete="off" />
                </div>
                <div class="otp-input-group">
                  <input type="text" class="otp-input" maxlength="1" placeholder="0" autocomplete="off" />
                </div>
                <div class="otp-input-group">
                  <input type="text" class="otp-input" maxlength="1" placeholder="0" autocomplete="off" />
                </div>
                <div class="otp-input-group">
                  <input type="text" class="otp-input" maxlength="1" placeholder="0" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="form-group login-buttons">
              <button type="button" class="btn action-btn" id="backtoLoginBtn">Back</button>
              <button type="button" class="btn action-btn" id="verifyOTPBtn">Verify</button>
            </div>

            <div class="form-group links">
              <p>Didn't received otp? <a href="javascript:void(0)" id="resendOTPBtn">Resend</a></p>
            </div>
          </div>
        </div>

        <div class="login-footer">
          <p>Submitted by: <strong><a href="https://jerome-avecilla.vercel.app/" target="_blank" style="text-decoration: none; color: #f8f9fa">Jerome Avecilla</a></strong> - IT211 (Project) Web System Technologies </p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../app/includes/layouts/end.php'; ?>