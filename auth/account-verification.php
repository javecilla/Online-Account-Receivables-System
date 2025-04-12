<?php require_once __DIR__ . '/../app/includes/layouts/begin.php'; ?>
<?php require_once __DIR__ . '/../app/config/env.php'; ?>

<div class="av-container">
  <div class="av-card">
    <div class="av-content">
      <!-- Left side - Hero Image -->
      <div class="av-hero-side">
        <img src="<?= $base_url ?>/assets/images/hero2.png" class="av-hero-image" alt="hero" loading="lazy" />
      </div>

      <!-- Right side - av Form -->
      <div class="av-form-side">
        <div class="av-header">
          <h2>Request Account Verification</h2>
          <p>Enter your email address to start the verification process. We'll send you a secure link to confirm your account.</p>
        </div>

        <div class="av-form">
          <form id="avForm" method="post">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" autocomplete="off" placeholder="Enter an email address (e.g., example@avecilla.net)">
              </div>
            </div>

            <div class="form-group">
              <div class="g-recaptcha-widgets">
                <div class="g-recaptcha" name="g-recaptcha-response"
                  id="g-recaptcha-response"
                  data-sitekey="<?= env('RECAPTCHA_FRONTEND_KEY') ?>"></div>
              </div>
            </div>

            <div class="form-group av-buttons">
              <button type="button" onclick="window.location.href='login'" class="btn action-btn">Back to Login</button>
              <button type="button" class="btn action-btn request-btn" id="requestVerificationBtn">Request Verification</button>
            </div>

            <div class="form-group links">
              <p>Don't have an account yet? <a href="account-request?reg=true#external" style="cursor: no-drop">register here</a>.</p>
              <p>Forgot Password? No worries, please <a href="password-reset?mode=findEmail&step=1" style="cursor: no-drop">click here</a> to reset your password.</p>
            </div>
          </form>
          <div id="avLoadVerifying" class="d-none">
            <div class="av-loading-content">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="loading-text mt-3">Verifying your account...</p>
            </div>
            <div class="av-success-content d-none">
              <div class="success-icon">
                <i class="fas fa-check-circle text-success"></i>
              </div>
              <h4 class="success-title mt-3">Account Verified!</h4>
              <p class="success-message">Your account has been successfully verified.</p>
              <div class="form-group av-buttons">
                <button onclick="window.location.href='/auth/login'" class="btn action-btn">Login to your account</button>
              </div>
            </div>
            <div class="av-error-content d-none">
              <div class="error-icon">
                <i class="fas fa-times-circle text-danger"></i>
              </div>
              <h4 class="error-title mt-3">Verification Failed</h4>
              <p class="error-message">An error occurred during verification. Please try again.</p>
            </div>
          </div>
        </div>

        <div class="av-footer">
          <p>Submitted by: <strong>Jerome Avecilla</strong> - IT211 (Project) Web System Technologies </p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../app/includes/layouts/end.php'; ?>