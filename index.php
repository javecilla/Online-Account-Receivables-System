<?php
require_once __DIR__ . '/app/helpers/system.php';
require_once __DIR__ . '/app/helpers/global.php';
require_once __DIR__ . '/app/config/session.php';
require_once __DIR__ . '/app/config/env.php';

begin_session();

if(is_authenticated()) {
   if(is_employee()) {
     header('Location: '. base_url(). '/dashboard');
   } else {
     header('Location: '. base_url(). '/my-account');
   }
   exit;
}

// If not authenticated, display the landing page content directly
?>

<?php require_once __DIR__ . '/app/includes/layouts/begin.php'; ?>

<nav class="navbar navbar-expand-lg bg-white">
  <div class="container-fluid" style="max-width: 1480px;">
    <a class="navbar-brand" href="#">
      <img src="<?= $base_url ?>/assets/images/logo-text.png" width="220" alt="logo"/>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
          <li class="nav-item fw-bold me-2">
            <a class="nav-link active nav-scroll" aria-current="page" href="#home">Home</a>
          </li>
          <li class="nav-item fw-bold me-2">
            <a class="nav-link nav-scroll" href="#about-us">About</a>
          </li>
          <li class="nav-item fw-bold me-2">
            <a class="nav-link nav-scroll" href="#services">Services</a>
          </li>
          <li class="nav-item fw-bold me-2">
            <a class="nav-link nav-scroll" href="#contact-us">Contact</a>
          </li>
        </ul>
        <div class="d-flex mt-2">
          <a href="/auth/account-request?reg=true#external" class="btn navbar-buttons members-application-btn">
            Apply Membership
          </a>
          <a href="/auth/login" class="btn navbar-buttons my-portal-btn">
              My Portal
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<main>
  <section id="home" class="hero mt-4">
    <div class="container px-4 py-5">
      <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-10 col-sm-8 col-lg-6">
          <img src="<?=$base_url?>/assets/images/hero10.png" class="d-block mx-lg-auto img-fluid" alt="hero" width="1000" height="800" loading="lazy" />
        </div>
        <div class="col-lg-6">
          <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Smart Receivables System for Your Cooperative</h1>
          <p class="lead">
            Empower your cooperative with a modern, automated receivables management system. 
            From real-time member transactions and automated billing to seamless payment integration and comprehensive reporting — everything you need to streamline your financial operations is now in one place.
          </p>
          <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="/auth/account-request?reg=true#external" class="btn hero-buttons primary-button btn-lg px-4 me-md-2">Get Started</a>
            <a href="#about-us" class="btn hero-buttons secondary-button btn-lg px-4">Learn More</a>
          </div>
        </div>
      </div>
    </div>
  </section>


  <section id="about-us" class="about-us py-5 bg-light">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-4">
          <img src="<?=$base_url?>/assets/images/hero1.png" class="img-fluid" alt="hero" loading="lazy"/>
        </div>
        <div class="col-lg-8">
          <h1 class="fw-bold mb-4">Empowering Members. Building Futures.</h1>
          <p class="lead mb-4">
            Our cooperative system is more than just a tool — it’s a gateway to financial empowerment and inclusive growth. 
            Whether you're a long-time member or new to the cooperative world, our platform is designed to support your journey 
            toward financial freedom with ease, transparency, and security.
          </p>

          <ul class="list-unstyled">
            <li class="mb-3">
              <i class="fas fa-user-shield me-2"></i>
              Seamless and secure member onboarding process
            </li>
            <li class="mb-3">
              <i class="fas fa-bolt me-2"></i>
              Fast loan processing with real-time status updates
            </li>
            <li class="mb-3">
              <i class="fas fa-chart-line me-2"></i>
              Transparent financial tracking and instant reports
            </li>
            <li class="mb-3">
              <i class="fas fa-hand-holding-usd me-2"></i>
              Member-focused benefits and growing community support
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>


  <section id="services" class="services bg-white">
  <div class="container px-4 py-5" id="icon-grid"> 
    <h2 class="pb-2 text-center fw-bold">What We Offer</h2> 
    <p class="text-center mb-5 text-muted">Explore our range of services designed to support your financial growth and success.</p>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4"> 

      <div class="col d-flex align-items-start">
        <i class="fas fa-hand-holding-usd text-primary fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Loan Assistance</h3>
          <p>Access fast and flexible loan options tailored to your financial needs.</p>
        </div>
      </div> 

      <div class="col d-flex align-items-start">
        <i class="fas fa-piggy-bank text-success fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Savings Program</h3>
          <p>Grow your savings securely with our high-return cooperative savings plans.</p>
        </div>
      </div> 

      <div class="col d-flex align-items-start">
        <i class="fas fa-user-check text-warning fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Member Onboarding</h3>
          <p>Enjoy a smooth, guided registration process and quick approval time.</p>
        </div>
      </div> 

      <div class="col d-flex align-items-start">
        <i class="fas fa-mobile-alt text-danger fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Mobile Access</h3>
          <p>Manage your membership, loans, and contributions anytime, anywhere.</p>
        </div>
      </div>

      <div class="col d-flex align-items-start">
        <i class="fas fa-chart-bar text-info fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Financial Reports</h3>
          <p>Track your transactions and statements with real-time data and analytics.</p>
        </div>
      </div>

      <div class="col d-flex align-items-start">
        <i class="fas fa-headset text-secondary fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Member Support</h3>
          <p>Get assistance and answers from our dedicated cooperative support team.</p>
        </div>
      </div>

      <div class="col d-flex align-items-start">
        <i class="fas fa-users text-dark fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Community Engagement</h3>
          <p>Be part of a growing, supportive community that uplifts one another.</p>
        </div>
      </div>

      <div class="col d-flex align-items-start">
        <i class="fas fa-bullseye text-primary fs-2 me-3"></i>
        <div>
          <h3 class="fw-bold fs-5">Goal-Oriented Planning</h3>
          <p>Achieve your personal and financial goals with guided cooperative tools.</p>
        </div>
      </div>

    </div>
  </div>
</section>


  <section id="contact-us" class="contact-us py-5 bg-light">
    <div class="container">
    <h2 class="pb-2 text-center fw-bold">Get in Touch</h2> 
    <p class="text-center mb-5 text-muted">Have questions or need help? We're here to support you every step of the way.</p>

      <div class="row g-4">
        <!-- Map Column -->
        <div class="col-lg-8 mb-4 mb-lg-0">
          <div class="card h-100 shadow-sm">
            <div class="card-body p-0">
              <div id="contact-map" style="height: 600px; width: 100%;"></div>
            </div>
          </div>
        </div>
        
        <!-- Contact Form Column -->
        <div class="col-lg-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body p-4">
              <h3 class="card-title fw-bold mb-4">Send Us a Message</h3>
              <form id="contactForm">
                <div class="mb-3">
                  <label for="name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="name" placeholder="Enter your full name"/>
                </div>
                
                <div class="mb-3">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="email" class="form-control" id="email" placeholder="Enter your email address"/>
                </div>
                
                <div class="mb-3">
                  <label for="message" class="form-label">Message</label>
                  <textarea class="form-control" id="message" rows="5" placeholder="Enter your message"></textarea>
                </div>

                <div class="mb-3 g-recaptcha-widgets">
                  <div class="g-recaptcha" name="g-recaptcha-response"
                    id="g-recaptcha-response"
                    data-sitekey="<?= env('RECAPTCHA_FRONTEND_KEY') ?>"></div>
                </div>
                
                <div class="d-grid">
                  <button type="submit" class="btn action-btn py-2" id="submit">Send Message</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<footer class="bg-dark text-white py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <!-- <img src="/assets/images/logo-text.png" width="200" alt="logo" class="mb-3" /> -->
        <span class="mb-3 fw-bold">OARSMC</span>
        <p class="text-white-50">Empowering members through financial inclusion and cooperative growth. Together we build stronger communities.</p>
        <div class="social-icons mt-3">
          <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-md-6">
        <h5 class="mb-4">Quick Links</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Home</a></li>
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Services</a></li>
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Contact</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6">
        <h5 class="mb-4">Our Services</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Loan Programs</a></li>
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Savings Accounts</a></li>
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Financial Advisory</a></li>
          <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Member Benefits</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6">
        <h5 class="mb-4">Contact Info</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Bulacan, Philippines</li>
          <li class="mb-2"><i class="fas fa-phone me-2"></i> +63 912 345 6789</li>
          <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@cooperative.com</li>
          <li class="mb-2"><i class="fas fa-clock me-2"></i> Mon-Fri: 8:00 AM - 5:00 PM</li>
        </ul>
      </div>
    </div>
    
    <hr class="my-4 bg-secondary">

    <div class="row">
      <div class="col-md-6 text-center text-md-start">
        <p class="mb-0 text-white-50">&copy; <?= date('Y') ?> Requirements for Web System Technologies</p>
      </div>
      <div class="col-md-6 text-center text-md-end">
        <p class="mb-0 text-white-50">Submitted by: <a href="https://jerome-avecilla.vercel.app/" target="_blank" class="text-white">Avecilla, Jerome</a></p>
      </div>
    </div>
  </div>
</footer>

<?php require_once __DIR__ . '/app/includes/layouts/end.php'; ?>
